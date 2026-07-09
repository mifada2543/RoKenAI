<?php
session_name('RoKenAI');
session_start();

// Ensure CSRF token is initialized
require_once 'auth/config.php';

// For demo: auto-login as first active user if not logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    $isGuest = true;
} else {
    $isGuest = false;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Lapor Kerusakan</title>
    <?php include 'partials/link.php'; ?>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="fixed inset-0 z-[9999] bg-[rgba(15,23,42,0.4)] backdrop-blur-sm hidden items-center justify-center" id="loadingOverlay">
        <div class="bg-white rounded-xl p-10 text-center shadow-2xl max-w-xs w-[90%]">
            <div class="w-12 h-12 border-4 border-[#E2E8F0] border-t-[#1D4ED8] rounded-full animate-spin mx-auto mb-4"></div>
            <p class="font-heading text-[15px] font-semibold text-[#0F172A] mb-1">Mengirim laporan...</p>
            <small class="text-[12px] text-[#94A3B8]">Mohon tunggu sebentar</small>
        </div>
    </div>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="max-w-[680px] mx-auto px-6 py-6 pb-14 animate-fade-in-up">

            <!-- Page Heading -->
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-[#0F172A] mb-2" style="font-size:clamp(24px,3.5vw,32px);letter-spacing:-0.02em;" data-i18n="upload.title">Lapor Kerusakan Jalan</h1>
                <p class="text-[14px] text-[#475569] max-w-sm mx-auto leading-6" data-i18n="upload.desc">Laporkan kerusakan jalan yang Anda temukan. AI kami akan mendeteksi jenis dan tingkat keparahan secara otomatis.</p>
            </div>

            <form id="reportForm" enctype="multipart/form-data" method="POST" action="controller/upload_handler.php">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="latitude" id="latField" value="-7.250445">
                <input type="hidden" name="longitude" id="lngField" value="112.768845">
                <input type="hidden" name="damage_type" id="damageTypeField" value="">
                <input type="hidden" name="damage_severity" id="severityField" value="">
                <input type="hidden" name="detection_confidence" id="confidenceField" value="">

                <!-- STEP 1: Upload Foto -->
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-7 mb-5">
                    <div class="flex items-center gap-2.5 mb-[18px]">
                        <span class="w-7 h-7 rounded-full bg-[#1D4ED8] text-white flex items-center justify-center text-[13px] font-bold font-heading shrink-0">1</span>
                        <h2 class="font-heading text-[16px] font-semibold text-[#0F172A]" data-i18n="upload.step1">Upload Foto Jalan Rusak</h2>
                    </div>

                    <!-- Dropzone -->
                    <div class="relative w-full min-h-[280px] rounded-[10px] bg-[#F8FAFC] border-2 border-dashed border-[#E2E8F0] flex flex-col items-center justify-center p-8 text-center cursor-pointer transition-all duration-300 hover:border-[#3B82F6] hover:bg-[rgba(59,130,246,0.03)]" id="dropZone">
                        <div class="w-[110px] h-[110px] rounded-xl overflow-hidden mb-4 border border-[#E2E8F0] shadow-card">
                            <img src="assets/Logo.png" alt="Upload" class="w-full h-full object-cover">
                        </div>
                        <div class="font-heading text-[15px] font-semibold text-[#0F172A] mb-1.5" data-i18n="upload.dropTitle">Tarik foto jalan rusak di sini</div>
                        <div class="text-[13px] text-[#94A3B8] mb-4" data-i18n="upload.dropSub">Format: JPG, PNG &bull; Maks 10MB</div>
                        <button type="button" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-transparent text-[#1D4ED8] text-[13px] font-semibold cursor-pointer border border-[#1D4ED8] transition-all duration-200 hover:bg-primary-light" id="browseBtn">
                            <i data-lucide="folder-open" style="width:16px;height:16px;"></i>
                            <span data-i18n="upload.browse">Pilih Foto</span>
                        </button>
                    </div>
                    <input type="file" id="fileInput" name="image" accept="image/*" required class="hidden">

                    <!-- Preview -->
                    <div id="previewWrap" class="hidden animate-fade-in-up mt-4">
                        <div class="bg-white rounded-[10px] border border-[#E2E8F0] overflow-hidden">
                            <div class="relative bg-[#F1F5F9] max-h-[400px] flex items-center justify-center">
                                <img class="w-full h-auto max-h-[380px] object-contain" id="previewImg" src="" alt="Preview">
                                <div class="absolute bottom-3 left-3 right-3 flex gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#FACC15] text-[#0F172A] rounded-lg text-[12px] font-bold font-mono shadow-md"><i data-lucide="scan" style="width:14px;height:14px;"></i> Lubang Jalan — 92%</span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#FACC15] text-[#0F172A] rounded-lg text-[12px] font-bold font-mono shadow-md"><i data-lucide="alert-triangle" style="width:14px;height:14px;"></i> Tingkat: Parah</span>
                                </div>
                            </div>
                            <div class="flex gap-2.5 p-4 border-t border-[#E2E8F0]">
                                <button type="button" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-transparent text-[#475569] text-[13px] font-medium cursor-pointer border border-[#E2E8F0] transition-all duration-200 hover:bg-[#F8FAFC]" id="clearBtn">
                                    <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                                    <span data-i18n="upload.delete">Hapus</span>
                                </button>
                                <button type="button" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-[#1D4ED8] text-white text-[13px] font-semibold cursor-pointer border-none transition-all duration-200 hover:bg-[#3B82F6]" id="analyzeBtn">
                                    <i data-lucide="sparkles" style="width:15px;height:15px;"></i>
                                    <span data-i18n="upload.redetect">Deteksi Ulang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Konfirmasi Lokasi -->
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-7 mb-5">
                    <div class="flex items-center gap-2.5 mb-[18px]">
                        <span class="w-7 h-7 rounded-full bg-[#1D4ED8] text-white flex items-center justify-center text-[13px] font-bold font-heading shrink-0">2</span>
                        <h2 class="font-heading text-[16px] font-semibold text-[#0F172A]" data-i18n="upload.step2">Konfirmasi Lokasi</h2>
                    </div>

                    <div class="mb-[18px]">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">
                            <span data-i18n="upload.location">Lokasi</span>
                            <span class="font-normal text-[#94A3B8] text-[12px]" data-i18n="upload.locAuto">(otomatis dari GPS)</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[#94A3B8] pointer-events-none"><i data-lucide="map-pin" style="width:16px;height:16px;"></i></span>
                            <input class="w-full pl-10 pr-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[14px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="text" id="locationField"
                                   placeholder="Deteksi lokasi otomatis..." value="-7.250445, 112.768845" readonly>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-2 mt-1.5 bg-[#DBEAFE] rounded-lg text-[12px] text-[#1D4ED8]">
                            <i data-lucide="lock" style="width:14px;height:14px;"></i>
                            <span>Lokasi terdeteksi: </span>
                            <span class="font-mono text-[12px]">-7.250445, 112.768845</span>
                            <span>&bull;</span>
                            <span>Surabaya</span>
                        </div>
                    </div>

                    <div class="mb-[18px]">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">
                            <span data-i18n="upload.address">Alamat</span>
                            <span class="font-normal text-[#94A3B8] text-[12px]" data-i18n="upload.addrOpt">(opsional, bisa diedit)</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[#94A3B8] pointer-events-none"><i data-lucide="home" style="width:16px;height:16px;"></i></span>
                            <input class="w-full pl-10 pr-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[14px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="text" name="address" id="alamatField"
                                   placeholder="Masukkan alamat lokasi" value="Jl. Raya Ahmad Yani, Surabaya">
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Catatan Tambahan -->
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-7 mb-5">
                    <div class="flex items-center gap-2.5 mb-[18px]">
                        <span class="w-7 h-7 rounded-full bg-[#1D4ED8] text-white flex items-center justify-center text-[13px] font-bold font-heading shrink-0">3</span>
                        <h2 class="font-heading text-[16px] font-semibold text-[#0F172A]" data-i18n="upload.step3">Catatan Tambahan</h2>
                    </div>
                    <div class="mb-[18px]">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">
                            <span data-i18n="upload.descLabel">Deskripsi</span>
                            <span class="font-normal text-[#94A3B8] text-[12px]" data-i18n="upload.descOpt">(opsional)</span>
                        </label>
                        <textarea class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[14px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)] min-h-[80px] resize-y" name="description" id="catatanField"
                                  placeholder="Contoh: Jalan ini sudah rusak sejak 2 minggu lalu..."></textarea>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex gap-3">
                    <button type="button" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-transparent text-[#1D4ED8] text-[14px] font-semibold cursor-pointer border border-[#1D4ED8] transition-all duration-200 hover:bg-primary-light" onclick="history.back()">
                        <i data-lucide="arrow-left" style="width:17px;height:17px;"></i>
                        <span data-i18n="upload.back">Kembali</span>
                    </button>
                    <button type="submit" class="flex-[2] inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-lg bg-[#1D4ED8] text-white text-[14px] font-semibold cursor-pointer border-none shadow-[0_2px_6px_rgba(29,78,216,0.2)] transition-all duration-250 hover:bg-[#3B82F6] hover:-translate-y-0.5 hover:shadow-[0_4px_12px_rgba(29,78,216,0.25)]" id="submitBtn">
                        <i data-lucide="send" style="width:17px;height:17px;"></i>
                        <span data-i18n="upload.submit">Kirim Laporan</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <style>
        .dropzone.dragover { border-color: #3B82F6 !important; background: rgba(59,130,246,0.06) !important; transform: scale(1.01); }
        @media (max-width: 480px) {
            form .p-7 { padding: 20px; }
            .min-h-\[280px\] { min-height: 220px; }
            .flex.gap-3 { flex-direction: column; }
        }
    </style>

    <script>
        lucide.createIcons();

        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const browseBtn = document.getElementById('browseBtn');
        const previewWrap = document.getElementById('previewWrap');
        const previewImg = document.getElementById('previewImg');
        const clearBtn = document.getElementById('clearBtn');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const reportForm = document.getElementById('reportForm');

        browseBtn.addEventListener('click', function (e) { e.stopPropagation(); fileInput.click(); });
        dropZone.addEventListener('click', function () { fileInput.click(); });
        fileInput.addEventListener('change', function (e) { if (e.target.files[0]) showPreview(e.target.files[0]); });
        dropZone.addEventListener('dragenter', function (e) { e.preventDefault(); this.classList.add('dragover'); });
        dropZone.addEventListener('dragover', function (e) { e.preventDefault(); this.classList.add('dragover'); });
        dropZone.addEventListener('dragleave', function (e) { e.preventDefault(); this.classList.remove('dragover'); });
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
        clearBtn.addEventListener('click', function () { previewWrap.style.display = 'none'; previewImg.src = ''; fileInput.value = ''; });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                var lat = pos.coords.latitude.toFixed(6);
                var lng = pos.coords.longitude.toFixed(6);
                document.getElementById('locationField').value = lat + ', ' + lng;
                document.getElementById('latField').value = lat;
                document.getElementById('lngField').value = lng;
            });
        }

        reportForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!fileInput.files[0]) { alert('Silakan upload foto jalan rusak terlebih dahulu.'); return; }
            loadingOverlay.classList.add('flex');
            var formData = new FormData(reportForm);
            fetch('controller/upload_handler.php', { method: 'POST', body: formData })
            .then(function (resp) { return resp.json(); })
            .then(function (data) {
                loadingOverlay.classList.remove('flex');
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Laporan Terkirim!', html: 'Terima kasih!<br><br><strong>ID Laporan:</strong> ' + data.report_id + '<br><strong>Status:</strong> Dilaporkan', confirmButtonText: 'Lihat Riwayat', confirmButtonColor: '#1D4ED8', showCancelButton: true, cancelButtonText: 'Kembali', cancelButtonColor: '#94A3B8', allowOutsideClick: false, customClass: { popup: 'rounded-[16px]' } })
                    .then(function (result) {
                        if (result.isConfirmed) window.location.href = 'profile.php?report=' + data.report_id;
                        else { reportForm.reset(); document.getElementById('previewWrap').style.display = 'none'; }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal Mengirim', text: data.message || 'Terjadi kesalahan.', confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } });
                }
            })
            .catch(function () {
                loadingOverlay.classList.remove('flex');
                Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Terjadi kesalahan sistem.', confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } });
            });
        });
    </script>
</body>
</html>
