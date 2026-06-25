<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Lapor Kerusakan</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Upload / Lapor Kerusakan (desain.md 5.3)
           Form satu kolom, step-by-step, desain ringan dan rapi
           ================================================================ */

        .upload-page {
            max-width: 680px;
            margin: 0 auto;
            padding: 24px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== Form Section ===== */
        .form-section {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            padding: 28px;
            margin-bottom: 20px;
        }
        .form-section .fs-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        .form-section .fs-title .fs-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--primary-700);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            font-family: var(--font-heading);
        }
        .form-section .fs-title h2 {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 600;
            color: var(--ink-900);
        }
        .form-section .fs-title i {
            width: 18px;
            height: 18px;
            color: var(--primary-700);
        }

        /* ===== Dropzone ===== */
        .dropzone {
            position: relative;
            width: 100%;
            min-height: 280px;
            border-radius: var(--radius-md);
            background: var(--surface-muted);
            border: 2px dashed var(--line-200);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }
        .dropzone:hover {
            border-color: var(--primary-500);
            background: rgba(59, 130, 246, 0.03);
        }
        .dropzone.dragover {
            border-color: var(--primary-500);
            background: rgba(59, 130, 246, 0.06);
            transform: scale(1.01);
        }
        .dropzone .dz-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-lg);
            background: var(--primary-100);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: var(--primary-700);
        }
        .dropzone .dz-icon i { width: 24px; height: 24px; }
        .dropzone .dz-title {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 600;
            color: var(--ink-900);
            margin-bottom: 6px;
        }
        .dropzone .dz-sub {
            font-size: 13px;
            color: #94A3B8;
            margin-bottom: 16px;
        }
        #fileInput { display: none; }

        /* ===== Preview & Hasil Deteksi AI ===== */
        #previewWrap {
            animation: fadeInUp 0.4s ease;
            margin-top: 16px;
        }
        .preview-card {
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--line-200);
            overflow: hidden;
        }
        .preview-card .prev-img-wrap {
            position: relative;
            background: #F1F5F9;
            max-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .preview-card .prev-img {
            width: 100%;
            height: auto;
            max-height: 380px;
            object-fit: contain;
        }
        /* Overlay hasil deteksi AI (kotak kuning di atas foto) */
        .detection-overlay {
            position: absolute;
            bottom: 12px;
            left: 12px;
            right: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .detection-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--marka-400);
            color: #0F172A;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            font-family: var(--font-mono);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .detection-tag i { width: 14px; height: 14px; }

        .preview-card .prev-actions {
            display: flex;
            gap: 10px;
            padding: 16px;
            border-top: 1px solid var(--line-200);
        }
        .preview-card .prev-actions button {
            flex: 1;
        }

        /* ===== Form Fields ===== */
        .form-group {
            margin-bottom: 18px;
        }
        .form-group:last-child { margin-bottom: 0; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-900);
            margin-bottom: 6px;
        }
        .form-label .label-desc {
            font-weight: 400;
            color: #94A3B8;
            font-size: 12px;
        }

        /* Input dengan ikon */
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrap .input-icon {
            position: absolute;
            left: 12px;
            color: #94A3B8;
            pointer-events: none;
            display: flex;
        }
        .input-wrap .input-icon i { width: 16px; height: 16px; }

        .input-field.with-icon {
            padding: 10px 14px 10px 38px;
        }
        .input-field.textarea {
            min-height: 80px;
            resize: vertical;
            font-family: var(--font-body);
        }

        /* Lock icon untuk auto-fill GPS */
        .gps-info {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: var(--primary-100);
            border-radius: var(--radius-sm);
            font-size: 12px;
            color: var(--primary-700);
            margin-top: 6px;
        }
        .gps-info i { width: 14px; height: 14px; }
        .gps-info .mono {
            font-family: var(--font-mono);
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .upload-page { padding: 16px; }
            .form-section { padding: 20px; }
            .dropzone { min-height: 220px; padding: 28px 16px; }
            .preview-card .prev-actions { flex-direction: column; }
        }
    </style>
</head>
<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="upload-page page-enter">

            <div class="page-heading">
                <h1>Lapor Kerusakan Jalan</h1>
                <p>Laporkan kerusakan jalan yang Anda temukan. AI kami akan mendeteksi jenis dan tingkat keparahan secara otomatis.</p>
            </div>

            <!-- ===== STEP 1: Upload Foto ===== -->
            <div class="form-section">
                <div class="fs-title">
                    <span class="fs-num">1</span>
                    <h2>Upload Foto Jalan Rusak</h2>
                </div>

                <!-- Dropzone -->
                <div class="dropzone" id="dropZone">
                    <div class="dz-icon">
                        <i data-lucide="camera"></i>
                    </div>
                    <div class="dz-title">Tarik foto jalan rusak di sini</div>
                    <div class="dz-sub">Format: JPG, PNG &bull; Maks 10MB</div>
                    <button type="button" class="btn-secondary" id="browseBtn" style="pointer-events:auto;padding:8px 20px;font-size:13px;">
                        <i data-lucide="folder-open"></i>
                        <span>Pilih Foto</span>
                    </button>
                </div>
                <input type="file" id="fileInput" accept="image/*">

                <!-- Preview + Hasil Deteksi AI -->
                <div id="previewWrap" style="display:none;">
                    <div class="preview-card">
                        <div class="prev-img-wrap">
                            <img class="prev-img" id="previewImg" src="" alt="Preview">
                            <!-- Overlay hasil deteksi AI -->
                            <div class="detection-overlay">
                                <span class="detection-tag"><i data-lucide="scan"></i> Lubang Jalan — 92%</span>
                                <span class="detection-tag"><i data-lucide="alert-triangle"></i> Tingkat: Parah</span>
                            </div>
                        </div>
                        <div class="prev-actions">
                            <button class="btn-ghost" id="clearBtn" style="border:1.5px solid var(--line-200);padding:8px 16px;">
                                <i data-lucide="trash-2"></i> Hapus
                            </button>
                            <button class="btn-primary" id="analyzeBtn" style="padding:8px 16px;">
                                <i data-lucide="sparkles"></i> Deteksi Ulang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 2: Konfirmasi Lokasi ===== -->
            <div class="form-section">
                <div class="fs-title">
                    <span class="fs-num">2</span>
                    <h2>Konfirmasi Lokasi</h2>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi <span class="label-desc">(otomatis dari GPS)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon"><i data-lucide="map-pin"></i></span>
                        <input class="input-field with-icon" type="text" id="locationField" placeholder="Deteksi lokasi otomatis..." value="-7.250445, 112.768845" readonly>
                    </div>
                    <div class="gps-info">
                        <i data-lucide="lock"></i>
                        <span>Lokasi terdeteksi otomatis: </span>
                        <span class="mono">-7.250445, 112.768845</span>
                        <span> &bull; </span>
                        <span>Jl. Raya No. 123, Surabaya</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat <span class="label-desc">(opsional, bisa diedit)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon"><i data-lucide="home"></i></span>
                        <input class="input-field with-icon" type="text" id="alamatField" placeholder="Masukkan alamat lokasi" value="Jl. Raya Ahmad Yani No. 123, Surabaya">
                    </div>
                </div>
            </div>

            <!-- ===== STEP 3: Catatan Tambahan ===== -->
            <div class="form-section">
                <div class="fs-title">
                    <span class="fs-num">3</span>
                    <h2>Catatan Tambahan</h2>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="label-desc">(opsional)</span></label>
                    <textarea class="input-field textarea" id="catatanField" placeholder="Contoh: Jalan ini sudah rusak sejak 2 minggu lalu, sering dilewati truk besar...">Lubang cukup dalam, diameter sekitar 30cm. Berbahaya bagi pengendara motor terutama saat malam hari.</textarea>
                </div>
            </div>

            <!-- ===== TOMBOL SUBMIT ===== -->
            <div style="display:flex;gap:12px;">
                <button class="btn-secondary" style="flex:1;padding:14px;" onclick="history.back()">
                    <i data-lucide="arrow-left"></i> Kembali
                </button>
                <button class="btn-primary" style="flex:2;padding:14px;" id="submitBtn">
                    <i data-lucide="send"></i> Kirim Laporan
                </button>
            </div>

        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        lucide.createIcons();

        // ================================================================
        // LOGIKA DROPZONE UPLOAD
        // Penjelasan: Dropzone ini menerima drag & drop atau klik untuk
        // memilih file gambar. Setelah dipilih, ditampilkan preview foto
        // dan simulasi hasil deteksi AI (bounding box + confidence).
        // ================================================================

        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const browseBtn = document.getElementById('browseBtn');
        const previewWrap = document.getElementById('previewWrap');
        const previewImg = document.getElementById('previewImg');
        const clearBtn = document.getElementById('clearBtn');

        // Klik browse button → buka file picker
        browseBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.click();
        });

        // Klik area dropzone → buka file picker
        dropZone.addEventListener('click', function() {
            fileInput.click();
        });

        // Saat file dipilih dari dialog
        fileInput.addEventListener('change', function(e) {
            if (e.target.files[0]) showPreview(e.target.files[0]);
        });

        // Drag events
        dropZone.addEventListener('dragenter', function(e) {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });

        // Drop event — menerima file yang di-drag
        document.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) {
                var file = e.dataTransfer.files[0];
                if (file.type.startsWith('image/')) showPreview(file);
            }
        });
        document.addEventListener('dragend', function() {
            dropZone.classList.remove('dragover');
        });

        // Fungsi menampilkan preview gambar
        function showPreview(file) {
            // Cek ukuran file (maks 10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('File terlalu besar. Maksimum 10MB.');
                return;
            }
            // Baca file sebagai data URL untuk ditampilkan
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewWrap.style.display = 'block';
                previewWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
            };
            reader.readAsDataURL(file);
        }

        // Tombol hapus preview
        clearBtn.addEventListener('click', function() {
            previewWrap.style.display = 'none';
            previewImg.src = '';
            fileInput.value = '';
        });

        // ================================================================
        // SIMULASI SUBMIT LAPORAN
        // Penjelasan: Saat tombol Kirim Laporan diklik, tampilkan alert
        // sukses dengan ID laporan (format mono font) dan progress status
        // "Garis Jalan" di posisi "Dilaporkan".
        // ================================================================
        document.getElementById('submitBtn').addEventListener('click', function() {
            // Validasi sederhana: cek apakah ada foto yang diupload
            if (!previewImg.src || previewWrap.style.display === 'none') {
                alert('Silakan upload foto jalan rusak terlebih dahulu.');
                return;
            }

            // Generate ID laporan acak
            var reportId = 'RK-' + new Date().getFullYear() + '-' +
                String(Math.floor(Math.random() * 9999)).padStart(4, '0');

            // Tampilkan alert sukses
            alert(
                'Laporan berhasil dikirim!\n\n' +
                'ID Laporan: ' + reportId + '\n' +
                'Status: Dilaporkan\n\n' +
                'Pantau progres perbaikan melalui menu Riwayat Laporan.'
            );

            // Redirect ke halaman profil/riwayat
            window.location.href = 'profile.php';
        });
    </script>
</body>
</html>
