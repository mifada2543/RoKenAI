<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name('RoKenAI');
    session_start();
}

require_once 'auth/config.php';
require_once 'controller/report.php';

$userId = null;
$user = null;
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    $userId = $_SESSION['user_id'];
    $user = getUserById($conn, $userId);
}

if (!$userId || !$user) {
    header('Location: auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$initials = '??';
$displayName = 'Pengguna';
$displayEmail = '';
if ($user) {
    $displayName = htmlspecialchars($user['username']);
    $displayEmail = htmlspecialchars($user['email']);
    $nameParts = explode(' ', $user['username']);
    if (count($nameParts) >= 2) {
        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
    } else {
        $initials = strtoupper(substr($user['username'], 0, 2));
    }
}

$userStats = ['total' => 0, 'selesai' => 0, 'in_progress' => 0, 'response_rate' => 0];
if ($userId) $userStats = getUserStats($conn, $userId);

$reports = [];
if ($userId) $reports = getUserReports($conn, $userId);

function isStepActive($stepIndex, $status) {
    $steps = ['dilaporkan' => 0, 'diverifikasi' => 1, 'diperbaiki' => 2, 'selesai' => 3];
    return ($steps[$status] ?? 0) >= $stepIndex;
}
function getProgressWidth($status) {
    $steps = ['dilaporkan' => 0, 'diverifikasi' => 1, 'diperbaiki' => 2, 'selesai' => 3];
    return (($steps[$status] ?? 0) / 3) * 100;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | RoKenAI</title>
    <?php include 'partials/link.php'; ?>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="max-w-[960px] mx-auto px-6 py-6 pb-12 animate-fade-in-up">

            <!-- Profile Header -->
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-8 mb-6 flex items-center gap-6 flex-wrap">
                <div class="w-20 h-20 rounded-xl bg-[#1D4ED8] flex items-center justify-center text-[28px] font-bold text-white font-heading shrink-0">
                    <?= $initials ?>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <h1 class="font-heading text-[22px] font-bold text-[#0F172A] mb-1"><?= $displayName ?></h1>
                    <div class="text-[14px] text-[#475569] mb-2"><?= $displayEmail ?></div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-light text-[#1D4ED8] text-[11px] font-semibold">
                        <i data-lucide="award" style="width:12px;height:12px;"></i> Pelapor Aktif
                    </span>
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 rounded-lg bg-transparent text-[#475569] border border-[#E2E8F0] text-[12px] font-medium cursor-pointer transition-all duration-200 hover:bg-[#F8FAFC] hover:border-[#CBD5E1]" onclick="document.querySelector('[data-tab=\\'data-diri\\']').click()">
                        <i data-lucide="edit-3" style="width:15px;height:15px;"></i> Edit Profil
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-3 mb-6">
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-4 text-center">
                    <div class="font-heading text-[22px] font-bold text-[#0F172A]"><?= $userStats['total'] ?></div>
                    <div class="text-[11px] text-[#94A3B8] font-medium mt-1 uppercase tracking-[0.04em]">Total Laporan</div>
                </div>
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-4 text-center">
                    <div class="font-heading text-[22px] font-bold text-[#0F172A]"><?= $userStats['selesai'] ?></div>
                    <div class="text-[11px] text-[#94A3B8] font-medium mt-1 uppercase tracking-[0.04em]">Selesai</div>
                </div>
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-4 text-center">
                    <div class="font-heading text-[22px] font-bold text-[#0F172A]"><?= $userStats['in_progress'] ?></div>
                    <div class="text-[11px] text-[#94A3B8] font-medium mt-1 uppercase tracking-[0.04em]">Dalam Proses</div>
                </div>
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-4 text-center">
                    <div class="font-heading text-[22px] font-bold text-[#0F172A]"><?= $userStats['response_rate'] ?>%</div>
                    <div class="text-[11px] text-[#94A3B8] font-medium mt-1 uppercase tracking-[0.04em]">Respons Rate</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-1 mb-5 border-b border-[#E2E8F0] pb-0">
                <button class="profile-tab active" data-tab="data-diri" onclick="switchTab('data-diri', this)">Data Diri</button>
                <button class="profile-tab" data-tab="riwayat" onclick="switchTab('riwayat', this)">Riwayat Laporan</button>
                <button class="profile-tab" data-tab="password" onclick="switchTab('password', this)">Ubah Password</button>
            </div>

            <!-- TAB: Data Diri -->
            <div class="tab-content active" id="tab-data-diri">
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card overflow-hidden mb-4">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-[#E2E8F0]">
                        <h2 class="font-heading text-[15px] font-semibold flex items-center gap-2 text-[#0F172A]"><i data-lucide="user" style="width:16px;height:16px;color:#1D4ED8;"></i> Informasi Akun</h2>
                        <button class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border-none bg-[#1D4ED8] text-white text-[11px] font-semibold cursor-pointer shadow-[0_2px_6px_rgba(29,78,216,0.2)] transition-all duration-200 hover:bg-[#3B82F6] hover:-translate-y-0.5" onclick="submitProfile()">Simpan</button>
                    </div>
                    <div class="px-6 py-[18px] pb-6">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Username</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)] disabled:bg-[#F8FAFC] disabled:text-[#94A3B8]" type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled></div>
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Nama Lengkap</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="text" id="inputFullName" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" placeholder="Nama lengkap"></div>
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Email</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="email" id="inputEmail" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="Email"></div>
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Nomor Telepon</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="tel" id="inputPhone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="No. telepon"></div>
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Alamat</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="text" id="inputAddress" value="<?= htmlspecialchars($user['address'] ?? '') ?>" placeholder="Alamat"></div>
                    </div>
                </div>
            </div>

            <!-- TAB: Riwayat Laporan -->
            <div class="tab-content hidden" id="tab-riwayat">
                <div class="flex flex-col gap-3">
                    <?php if (empty($reports)): ?>
                    <div class="text-center py-10 px-5 text-[#94A3B8]">
                        <i data-lucide="inbox" style="width:48px;height:48px;margin-bottom:12px;opacity:0.5;display:inline-block;"></i>
                        <p style="font-size:14px;font-weight:500;color:#64748B;margin-bottom:4px;">Belum ada laporan</p>
                        <p style="font-size:12px;">Laporkan kerusakan jalan pertama Anda melalui menu Lapor Kerusakan.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($reports as $report): 
                        $progressWidth = getProgressWidth($report['status']);
                        $steps = ['dilaporkan', 'diverifikasi', 'diperbaiki', 'selesai'];
                        $title = damageTypeLabel($report['damage_type']);
                        $location = $report['address'] ?: 'Lokasi tidak diketahui';
                    ?>
                    <div class="bg-white rounded-[10px] border border-[#E2E8F0] p-4 transition-all duration-200 hover:border-primary-light hover:shadow-glow">
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="font-heading text-[14px] font-semibold text-[#0F172A]"><?= htmlspecialchars($location) ?> — <?= $title ?></span>
                            <span class="font-mono text-[11px] text-[#94A3B8]">#<?= htmlspecialchars($report['report_id']) ?></span>
                        </div>
                        <!-- Garis Jalan Progress -->
                        <div class="garis-jalan mini" style="margin:8px 0;">
                            <div class="progress-fill" style="width:<?= $progressWidth ?>%;"></div>
                            <div class="marka-line"></div>
                            <?php foreach ($steps as $i => $step): ?>
                            <div class="gj-point <?= isStepActive($i, $report['status']) ? 'active' : '' ?>">
                                <i data-lucide="<?= isStepActive($i, $report['status']) ? 'check' : 'circle' ?>" class="gj-icon"></i>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex gap-3 text-[12px] text-[#94A3B8] mt-1.5 flex-wrap">
                            <span><i data-lucide="calendar" style="width:14px;height:14px;vertical-align:middle;"></i> <?= date('j M Y', strtotime($report['created_at'])) ?></span>
                            <?php if ($report['address']): ?><span><i data-lucide="map-pin" style="width:14px;height:14px;vertical-align:middle;"></i> <?= htmlspecialchars($report['address']) ?></span><?php endif; ?>
                            <span class="status-badge <?= getStatusBadgeClass($report['status']) ?>"><span class="s-dot"></span> <?= statusLabel($report['status']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TAB: Ubah Password -->
            <div class="tab-content hidden" id="tab-password">
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card overflow-hidden mb-4">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-[#E2E8F0]">
                        <h2 class="font-heading text-[15px] font-semibold flex items-center gap-2 text-[#0F172A]"><i data-lucide="lock" style="width:16px;height:16px;color:#1D4ED8;"></i> Ubah Password</h2>
                    </div>
                    <div class="px-6 py-[18px] pb-6">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Password Saat Ini</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="password" id="pwCurrent" placeholder="Masukkan password saat ini"></div>
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Password Baru</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="password" id="pwNew" placeholder="Min. 8 karakter"></div>
                        <div class="mb-4"><label class="block text-[12px] font-semibold text-[#475569] mb-1.5 uppercase tracking-[0.03em]">Konfirmasi Password Baru</label><input class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]" type="password" id="pwConfirm" placeholder="Ulangi password baru"></div>
                        <button class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg border-none bg-[#1D4ED8] text-white text-[13px] font-semibold cursor-pointer shadow-[0_2px_6px_rgba(29,78,216,0.2)] transition-all duration-200 hover:bg-[#3B82F6] hover:-translate-y-0.5 mt-2" onclick="submitPassword()"><i data-lucide="save" style="width:15px;height:15px;"></i> Simpan Password</button>
                    </div>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card overflow-hidden">
                    <div class="flex items-center px-6 py-4 border-b border-[#E2E8F0]">
                        <h2 class="font-heading text-[15px] font-semibold flex items-center gap-2 text-[#0F172A]"><i data-lucide="activity" style="width:16px;height:16px;color:#1D4ED8;"></i> Aktivitas Terbaru</h2>
                    </div>
                    <div class="px-6 py-2">
                        <?php $activityReports = array_slice($reports, 0, 5); ?>
                        <?php if (empty($activityReports)): ?>
                        <div class="flex gap-3 py-2.5">
                            <div class="flex-1"><div style="color:#94A3B8;">Belum ada aktivitas</div></div>
                        </div>
                        <?php else: ?>
                        <?php foreach ($activityReports as $act):
                            $actTitle = $act['address'] ?: 'Jalan';
                            $actLabel = $act['status'] === 'dilaporkan' ? 'Melaporkan' : ($act['status'] === 'selesai' ? 'Perbaikan selesai' : 'Pembaruan status');
                        ?>
                        <div class="flex gap-3 py-2.5 border-b border-[#E2E8F0] last:border-none">
                            <div class="w-9 h-9 min-w-[36px] rounded-lg bg-[rgba(29,78,216,0.1)] flex items-center justify-center text-[#1D4ED8]">
                                <i data-lucide="<?= $act['status'] === 'selesai' ? 'check-circle-2' : 'upload' ?>" style="width:16px;height:16px;"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-[13px] text-[#0F172A] font-medium"><?= $actLabel ?> di <?= htmlspecialchars($actTitle) ?></div>
                                <div class="text-[11px] text-[#94A3B8] mt-0.5"><?= timeAgo($act['created_at']) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <style>
        .profile-tab {
            padding: 10px 20px; font-size: 13px; font-weight: 500;
            color: #475569; cursor: pointer; border: none;
            background: transparent; border-bottom: 2px solid transparent;
            margin-bottom: -1px; transition: all 0.2s ease;
        }
        .profile-tab:hover { color: #1D4ED8; }
        .profile-tab.active { color: #1D4ED8; border-bottom-color: #1D4ED8; font-weight: 600; }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeInUp 0.3s ease; }
        @media (max-width: 768px) {
            .p-8 { flex-direction: column; text-align: center; padding: 24px; }
            .flex.gap-2 { width: 100%; justify-content: center; }
        }
        @media (max-width: 480px) {
            .max-w-\[960px\] { padding: 16px; }
        }
    </style>

    <script>
        lucide.createIcons();

        function switchTab(tabName, btn) {
            document.querySelectorAll('.tab-content').forEach(function(el) { el.classList.remove('active'); el.classList.add('hidden'); });
            document.querySelectorAll('.profile-tab').forEach(function(el) { el.classList.remove('active'); });
            var tab = document.getElementById('tab-' + tabName);
            tab.classList.add('active');
            tab.classList.remove('hidden');
            btn.classList.add('active');
        }

        function submitProfile() {
            var fullName = document.getElementById('inputFullName').value.trim();
            var email    = document.getElementById('inputEmail').value.trim();
            var phone    = document.getElementById('inputPhone').value.trim();
            var address  = document.getElementById('inputAddress').value.trim();
            if (!email) { Swal.fire({ icon: 'warning', title: 'Email diperlukan', text: 'Email tidak boleh kosong.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } }); return; }
            var csrfToken = document.querySelector('#tab-data-diri input[name="csrf_token"]').value;
            var formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('csrf_token', csrfToken);
            formData.append('full_name', fullName);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('address', address);
            fetch('controller/profile_handler.php', { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Tersimpan!', text: data.message, timer: 2000, showConfirmButton: true, confirmButtonColor: '#1D4ED8', customClass: { popup: 'rounded-[16px]' } });
                    if (data.data && data.data.full_name) { var nameEl = document.querySelector('.text-\\[22px\\].font-bold'); if (nameEl) nameEl.textContent = data.data.full_name; }
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } }); }
            })
            .catch(function () { Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Gagal terhubung ke server.', confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } }); });
        }

        function submitPassword() {
            var currentPw = document.getElementById('pwCurrent').value;
            var newPw     = document.getElementById('pwNew').value;
            var confirmPw = document.getElementById('pwConfirm').value;
            if (!currentPw || !newPw || !confirmPw) { Swal.fire({ icon: 'warning', title: 'Lengkapi data', text: 'Semua field password harus diisi.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } }); return; }
            if (newPw.length < 8) { Swal.fire({ icon: 'warning', title: 'Terlalu pendek', text: 'Password baru minimal 8 karakter.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } }); return; }
            if (newPw !== confirmPw) { Swal.fire({ icon: 'warning', title: 'Tidak cocok', text: 'Konfirmasi password baru tidak cocok.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } }); return; }
            var csrfToken = document.querySelector('#tab-password input[name="csrf_token"]').value;
            var formData = new FormData();
            formData.append('action', 'change_password');
            formData.append('csrf_token', csrfToken);
            formData.append('current_password', currentPw);
            formData.append('new_password', newPw);
            formData.append('confirm_password', confirmPw);
            fetch('controller/profile_handler.php', { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Password Diubah!', text: data.message, confirmButtonColor: '#1D4ED8', customClass: { popup: 'rounded-[16px]' } })
                    .then(function () { document.getElementById('pwCurrent').value = ''; document.getElementById('pwNew').value = ''; document.getElementById('pwConfirm').value = ''; });
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } }); }
            })
            .catch(function () { Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Gagal terhubung ke server.', confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } }); });
        }
    </script>
</body>
</html>
