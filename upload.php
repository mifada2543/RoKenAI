<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Lapor Kerusakan</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Upload / Lapor Kerusakan
           ================================================================ */

        .upload-page {
            max-width: 680px; margin: 0 auto;
            padding: 24px 24px 56px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== Form Section ===== */
        .form-section {
            background: #fff; border-radius: 12px;
            border: 1px solid #E2E8F0; box-shadow: var(--shadow-card);
            padding: 28px; margin-bottom: 20px;
        }
        .fs-title {
            display: flex; align-items: center; gap: 10px; margin-bottom: 18px;
        }
        .fs-num {
            width: 28px; height: 28px; border-radius: 50%;
            background: #1D4ED8; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; font-family: var(--font-heading);
            flex-shrink: 0;
        }
        .fs-title h2 {
            font-family: var(--font-heading); font-size: 16px; font-weight: 600; color: #0F172A;
        }

        /* ===== Dropzone ===== */
        .dropzone {
            position: relative; width: 100%; min-height: 280px;
            border-radius: 10px; background: #F8FAFC;
            border: 2px dashed #E2E8F0;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 32px 24px; cursor: pointer; text-align: center;
            transition: all 0.3s ease; overflow: hidden;
        }
        .dropzone:hover { border-color: #3B82F6; background: rgba(59,130,246,0.03); }
        .dropzone.dragover { border-color: #3B82F6; background: rgba(59,130,246,0.06); transform: scale(1.01); }

        /* Upload ilustrasi dari assets/img/upload.png */
        .dz-img {
            width: 110px; height: 110px; border-radius: 12px;
            overflow: hidden; margin-bottom: 16px;
            border: 1px solid #E2E8F0; box-shadow: var(--shadow-card);
        }
        .dz-img img { width: 100%; height: 100%; object-fit: cover; }

        .dz-title {
            font-family: var(--font-heading); font-size: 15px;
            font-weight: 600; color: #0F172A; margin-bottom: 6px;
        }
        .dz-sub { font-size: 13px; color: #94A3B8; margin-bottom: 16px; }
        #fileInput { display: none; }

        /* Browse Button (Tailwind-style manual) */
        .browse-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 20px; border-radius: 8px;
            background: transparent; color: #1D4ED8;
            font-family: var(--font-body); font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1.5px solid #1D4ED8;
            transition: all 0.2s ease; pointer-events: auto;
        }
        .browse-btn:hover { background: #DBEAFE; }
        .browse-btn i { width: 16px; height: 16px; }

        /* ===== Preview ===== */
        #previewWrap { animation: fadeInUp 0.4s ease; margin-top: 16px; }
        .preview-card {
            background: #fff; border-radius: 10px;
            border: 1px solid #E2E8F0; overflow: hidden;
        }
        .prev-img-wrap {
            position: relative; background: #F1F5F9;
            max-height: 400px; display: flex; align-items: center; justify-content: center;
        }
        .prev-img { width: 100%; height: auto; max-height: 380px; object-fit: contain; }

        .detection-overlay {
            position: absolute; bottom: 12px; left: 12px; right: 12px;
            display: flex; flex-wrap: wrap; gap: 8px;
        }
        .detection-tag {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; background: var(--marka-400); color: #0F172A;
            border-radius: 8px; font-size: 12px; font-weight: 600;
            font-family: var(--font-mono); box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .detection-tag i { width: 14px; height: 14px; }

        .prev-actions {
            display: flex; gap: 10px; padding: 16px;
            border-top: 1px solid #E2E8F0;
        }
        .prev-actions > * { flex: 1; }

        /* ===== Form Fields ===== */
        .form-group { margin-bottom: 18px; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #0F172A; margin-bottom: 6px; }
        .form-label .label-desc { font-weight: 400; color: #94A3B8; font-size: 12px; }

        .input-with-icon {
            position: relative; display: flex; align-items: center;
        }
        .input-with-icon .iicon {
            position: absolute; left: 12px; color: #94A3B8;
            pointer-events: none; display: flex;
        }
        .input-with-icon .iicon i { width: 16px; height: 16px; }

        .field {
            width: 100%; padding: 10px 14px 10px 38px;
            border-radius: 8px; background: #fff;
            border: 1.5px solid #E2E8F0; color: #0F172A;
            font-size: 14px; font-family: var(--font-body);
            outline: none; transition: all 0.2s ease;
        }
        .field::placeholder { color: #94A3B8; }
        .field:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .field.textarea { min-height: 80px; resize: vertical; padding-left: 14px; }

        .gps-info {
            display: flex; align-items: center; gap: 8px; padding: 8px 12px;
            background: #DBEAFE; border-radius: 8px; font-size: 12px;
            color: #1D4ED8; margin-top: 6px;
        }
        .gps-info i { width: 14px; height: 14px; }
        .gps-mono { font-family: var(--font-mono); font-size: 12px; }

        /* ===== Buttons ===== */
        .btn-back {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 24px; border-radius: 8px;
            background: transparent; color: #1D4ED8;
            font-family: var(--font-body); font-size: 14px; font-weight: 600;
            cursor: pointer; border: 1.5px solid #1D4ED8; transition: all 0.2s ease;
        }
        .btn-back:hover { background: #DBEAFE; }
        .btn-back i { width: 17px; height: 17px; }

        .btn-submit {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 24px; border-radius: 8px;
            background: #1D4ED8; color: #fff;
            font-family: var(--font-body); font-size: 14px; font-weight: 600;
            cursor: pointer; border: none;
            box-shadow: 0 2px 6px rgba(29,78,216,0.2);
            transition: all 0.25s ease;
        }
        .btn-submit:hover { background: #3B82F6; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,78,216,0.25); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit i { width: 17px; height: 17px; }

        .btn-ghost-small {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            background: transparent; color: #475569;
            font-family: var(--font-body); font-size: 13px; font-weight: 500;
            cursor: pointer; border: 1.5px solid #E2E8F0; transition: all 0.2s ease;
        }
        .btn-ghost-small:hover { background: #F8FAFC; }
        .btn-ghost-small i { width: 15px; height: 15px; }

        .btn-primary-small {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            background: #1D4ED8; color: #fff;
            font-family: var(--font-body); font-size: 13px; font-weight: 600;
            cursor: pointer; border: none; transition: all 0.2s ease;
        }
        .btn-primary-small:hover { background: #3B82F6; }
        .btn-primary-small i { width: 15px; height: 15px; }

        @media (max-width: 480px) {
            .upload-page { padding: 16px; }
            .form-section { padding: 20px; }
            .dropzone { min-height: 220px; padding: 24px 16px; }
            .prev-actions { flex-direction: column; }
        }
    </style>
</head>
<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="upload-page page-enter">

            <!-- Page Heading -->
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-ink-900 mb-2" style="font-size:clamp(24px,3.5vw,32px);letter-spacing:-0.02em;" data-i18n="upload.title">Lapor Kerusakan Jalan</h1>
                <p class="text-[14px] text-ink-600 max-w-sm mx-auto leading-6" data-i18n="upload.desc">Laporkan kerusakan jalan yang Anda temukan. AI kami akan mendeteksi jenis dan tingkat keparahan secara otomatis.</p>
            </div>

            <!-- ===== STEP 1: Upload Foto ===== -->
            <div class="form-section">
                <div class="fs-title">
                    <span class="fs-num">1</span>
                    <h2 data-i18n="upload.step1">Upload Foto Jalan Rusak</h2>
                </div>

                <!-- Dropzone — dengan ilustrasi upload.png -->
                <div class="dropzone" id="dropZone">
                    <!-- Ilustrasi dari assets/img/upload.png -->
                    <div class="dz-img">
                        <img src="assets/img/upload.png" alt="Upload">
                    </div>
                    <div class="dz-title" data-i18n="upload.dropTitle">Tarik foto jalan rusak di sini</div>
                    <div class="dz-sub" data-i18n="upload.dropSub">Format: JPG, PNG &bull; Maks 10MB</div>
                    <button type="button" class="browse-btn" id="browseBtn">
                        <i data-lucide="folder-open"></i>
                        <span data-i18n="upload.browse">Pilih Foto</span>
                    </button>
                </div>
                <input type="file" id="fileInput" accept="image/*">

                <!-- Preview + Hasil Deteksi AI -->
                <div id="previewWrap" style="display:none;">
                    <div class="preview-card">
                        <div class="prev-img-wrap">
                            <img class="prev-img" id="previewImg" src="" alt="Preview">
                            <div class="detection-overlay">
                                <span class="detection-tag"><i data-lucide="scan"></i> Lubang Jalan — 92%</span>
                                <span class="detection-tag"><i data-lucide="alert-triangle"></i> Tingkat: Parah</span>
                            </div>
                        </div>
                        <div class="prev-actions">
                            <button class="btn-ghost-small" id="clearBtn">
                                <i data-lucide="trash-2"></i>
                                <span data-i18n="upload.delete">Hapus</span>
                            </button>
                            <button class="btn-primary-small" id="analyzeBtn">
                                <i data-lucide="sparkles"></i>
                                <span data-i18n="upload.redetect">Deteksi Ulang</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 2: Konfirmasi Lokasi ===== -->
            <div class="form-section">
                <div class="fs-title">
                    <span class="fs-num">2</span>
                    <h2 data-i18n="upload.step2">Konfirmasi Lokasi</h2>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span data-i18n="upload.location">Lokasi</span>
                        <span class="label-desc" data-i18n="upload.locAuto">(otomatis dari GPS)</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="iicon"><i data-lucide="map-pin"></i></span>
                        <input class="field" type="text" id="locationField"
                               placeholder="Deteksi lokasi otomatis..." value="-7.250445, 112.768845" readonly>
                    </div>
                    <div class="gps-info">
                        <i data-lucide="lock"></i>
                        <span>Lokasi terdeteksi: </span>
                        <span class="gps-mono">-7.250445, 112.768845</span>
                        <span>&bull;</span>
                        <span>Jl. Raya No. 123, Surabaya</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span data-i18n="upload.address">Alamat</span>
                        <span class="label-desc" data-i18n="upload.addrOpt">(opsional, bisa diedit)</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="iicon"><i data-lucide="home"></i></span>
                        <input class="field" type="text" id="alamatField"
                               placeholder="Masukkan alamat lokasi" value="Jl. Raya Ahmad Yani No. 123, Surabaya">
                    </div>
                </div>
            </div>

            <!-- ===== STEP 3: Catatan Tambahan ===== -->
            <div class="form-section">
                <div class="fs-title">
                    <span class="fs-num">3</span>
                    <h2 data-i18n="upload.step3">Catatan Tambahan</h2>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <span data-i18n="upload.descLabel">Deskripsi</span>
                        <span class="label-desc" data-i18n="upload.descOpt">(opsional)</span>
                    </label>
                    <textarea class="field textarea" id="catatanField"
                              placeholder="Contoh: Jalan ini sudah rusak sejak 2 minggu lalu...">Lubang cukup dalam, diameter sekitar 30cm. Berbahaya bagi pengendara motor terutama saat malam hari.</textarea>
                </div>
            </div>

            <!-- ===== TOMBOL SUBMIT ===== -->
            <div class="flex gap-3">
                <button class="btn-back" style="flex:1;" onclick="history.back()">
                    <i data-lucide="arrow-left"></i>
                    <span data-i18n="upload.back">Kembali</span>
                </button>
                <button class="btn-submit" style="flex:2;" id="submitBtn">
                    <i data-lucide="send"></i>
                    <span data-i18n="upload.submit">Kirim Laporan</span>
                </button>
            </div>

        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        lucide.createIcons();

        const dropZone   = document.getElementById('dropZone');
        const fileInput  = document.getElementById('fileInput');
        const browseBtn  = document.getElementById('browseBtn');
        const previewWrap = document.getElementById('previewWrap');
        const previewImg  = document.getElementById('previewImg');
        const clearBtn   = document.getElementById('clearBtn');

        browseBtn.addEventListener('click', function (e) { e.stopPropagation(); fileInput.click(); });
        dropZone.addEventListener('click', function () { fileInput.click(); });

        fileInput.addEventListener('change', function (e) {
            if (e.target.files[0]) showPreview(e.target.files[0]);
        });

        dropZone.addEventListener('dragenter', function (e) { e.preventDefault(); dropZone.classList.add('dragover'); });
        dropZone.addEventListener('dragover',  function (e) { e.preventDefault(); dropZone.classList.add('dragover'); });
        dropZone.addEventListener('dragleave', function (e) { e.preventDefault(); dropZone.classList.remove('dragover'); });

        document.addEventListener('drop', function (e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) {
                var file = e.dataTransfer.files[0];
                if (file.type.startsWith('image/')) showPreview(file);
            }
        });
        document.addEventListener('dragend', function () { dropZone.classList.remove('dragover'); });

        function showPreview(file) {
            if (file.size > 10 * 1024 * 1024) { alert('File terlalu besar. Maksimum 10MB.'); return; }
            var reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewWrap.style.display = 'block';
                previewWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                lucide.createIcons();
            };
            reader.readAsDataURL(file);
        }

        clearBtn.addEventListener('click', function () {
            previewWrap.style.display = 'none';
            previewImg.src = '';
            fileInput.value = '';
        });

        document.getElementById('submitBtn').addEventListener('click', function () {
            if (!previewImg.src || previewWrap.style.display === 'none') {
                alert('Silakan upload foto jalan rusak terlebih dahulu.');
                return;
            }
            var reportId = 'RK-' + new Date().getFullYear() + '-' +
                String(Math.floor(Math.random() * 9999)).padStart(4, '0');
            alert('Laporan berhasil dikirim!\n\nID Laporan: ' + reportId + '\nStatus: Dilaporkan\n\nPantau progres perbaikan melalui menu Riwayat Laporan.');
            window.location.href = 'profile.php';
        });
    </script>
</body>
</html>
