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

// Jika belum login, redirect ke halaman login
if (!$userId || !$user) {
    header('Location: auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Get user initials for avatar
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

// Get user stats
$userStats = ['total' => 0, 'selesai' => 0, 'in_progress' => 0, 'response_rate' => 0];
if ($userId) {
    $userStats = getUserStats($conn, $userId);
}

// Get user reports
$reports = [];
if ($userId) {
    $reports = getUserReports($conn, $userId);
}

// Helper for Garis Jalan — reused in report rendering
function isStepActive($stepIndex, $status) {
    $steps = ['dilaporkan' => 0, 'diverifikasi' => 1, 'diperbaiki' => 2, 'selesai' => 3];
    $currentStep = $steps[$status] ?? 0;
    return $stepIndex <= $currentStep;
}

function getProgressWidth($status) {
    $steps = ['dilaporkan' => 0, 'diverifikasi' => 1, 'diperbaiki' => 2, 'selesai' => 3];
    $currentStep = $steps[$status] ?? 0;
    return ($currentStep / 3) * 100;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | RoKenAI</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Profil
           Header profil, riwayat laporan dengan progress line, tab
           ================================================================ */

        .profile-layout {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== Profile Header ===== */
        .profile-header {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            padding: 32px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-lg);
            background: var(--primary-700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            font-family: var(--font-heading);
            flex-shrink: 0;
        }
        .profile-info {
            flex: 1;
            min-width: 200px;
        }
        .profile-info h1 {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 700;
            color: var(--ink-900);
            margin-bottom: 4px;
        }
        .profile-info .p-email {
            font-size: 14px;
            color: var(--ink-600);
            margin-bottom: 8px;
        }
        .profile-info .p-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: var(--radius-full);
            background: var(--primary-100);
            color: var(--primary-700);
            font-size: 11px;
            font-weight: 600;
        }
        .profile-info .p-badge i { width: 12px; height: 12px; }
        .profile-actions {
            display: flex;
            gap: 8px;
        }

        /* ===== Stats Row ===== */
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .profile-stat {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            padding: 16px 20px;
            text-align: center;
        }
        .profile-stat .pstat-value {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 700;
            color: var(--ink-900);
        }
        .profile-stat .pstat-label {
            font-size: 11px;
            color: #94A3B8;
            font-weight: 500;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ===== Tabs ===== */
        .profile-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--line-200);
            padding-bottom: 0;
        }
        .profile-tab {
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 500;
            color: var(--ink-600);
            cursor: pointer;
            border: none;
            background: transparent;
            font-family: var(--font-body);
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: all 0.2s ease;
        }
        .profile-tab:hover {
            color: var(--primary-700);
        }
        .profile-tab.active {
            color: var(--primary-700);
            border-bottom-color: var(--primary-700);
            font-weight: 600;
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* ===== Card dalam Tab ===== */
        .profile-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            margin-bottom: 16px;
        }
        .profile-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid var(--line-200);
        }
        .profile-card-header h2 {
            font-family: var(--font-heading);
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--ink-900);
        }
        .profile-card-header h2 i { width: 16px; height: 16px; color: var(--primary-700); }
        .profile-card-body {
            padding: 18px 24px 24px;
        }

        /* ===== Form Fields ===== */
        .form-group {
            margin-bottom: 16px;
        }
        .form-group:last-child { margin-bottom: 0; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-600);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            background: var(--surface);
            border: 1.5px solid var(--line-200);
            color: var(--ink-900);
            font-size: 13px;
            font-family: var(--font-body);
            outline: none;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-input:disabled {
            background: var(--surface-muted);
            color: #94A3B8;
        }

        /* ===== Riwayat Laporan ===== */
        .report-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .report-card {
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--line-200);
            padding: 16px 20px;
            transition: all 0.2s ease;
        }
        .report-card:hover {
            border-color: var(--primary-100);
            box-shadow: var(--shadow-glow);
        }
        .report-card .r-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .report-card .r-title {
            font-family: var(--font-heading);
            font-size: 14px;
            font-weight: 600;
            color: var(--ink-900);
        }
        .report-card .r-id {
            font-family: var(--font-mono);
            font-size: 11px;
            color: #94A3B8;
        }
        .report-card .r-meta {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #94A3B8;
            margin-top: 6px;
        }
        .report-card .r-meta i { width: 14px; height: 14px; vertical-align: middle; }

        /* ===== Aktivitas Terbaru ===== */
        .activity-item {
            display: flex;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid var(--line-200);
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-icon {
            width: 36px; height: 36px; min-width: 36px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .activity-icon i { width: 16px; height: 16px; }
        .activity-text { flex: 1; }
        .activity-text .atitle {
            font-size: 13px;
            color: var(--ink-900);
            font-weight: 500;
        }
        .activity-text .atime {
            font-size: 11px;
            color: #94A3B8;
            margin-top: 2px;
        }

        /* ===== Tombol (karena tidak pakai Bootstrap) ===== */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 8px; border: none;
            background: #1D4ED8; color: #fff;
            font-family: var(--font-body); font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(29,78,216,0.2);
        }
        .btn-primary:hover { background: #3B82F6; transform: translateY(-1px); }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 8px;
            background: transparent; color: #475569;
            border: 1.5px solid #E2E8F0;
            font-family: var(--font-body); font-size: 13px; font-weight: 500;
            cursor: pointer; transition: all 0.2s ease;
        }
        .btn-secondary:hover { background: #F8FAFC; border-color: #CBD5E1; }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .profile-header { flex-direction: column; text-align: center; padding: 24px; }
            .profile-actions { width: 100%; justify-content: center; }
            .profile-stats { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .profile-layout { padding: 16px; }
            .profile-avatar { width: 64px; height: 64px; font-size: 22px; }
            .profile-info h1 { font-size: 18px; }
            .profile-stats { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .profile-stat { padding: 12px 16px; }
            .profile-card-body { padding: 14px 16px 20px; }
            .profile-card-header { padding: 14px 16px; }
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="profile-layout page-enter">

            <!-- ===== Profile Header ===== -->
            <div class="profile-header">
                <div class="profile-avatar"><?= $initials ?></div>
                <div class="profile-info">
                    <h1><?= $displayName ?></h1>
                    <div class="p-email"><?= $displayEmail ?></div>
                    <span class="p-badge"><i data-lucide="award"></i> Pelapor Aktif</span>
                </div>
                <div class="profile-actions">
                    <button class="btn-secondary" style="padding:8px 16px;font-size:12px;">
                        <i data-lucide="share-2"></i> Bagikan
                    </button>
                    <button class="btn-primary" style="padding:8px 16px;font-size:12px;" onclick="document.querySelector('[data-tab=\'data-diri\']').click()">
                        <i data-lucide="edit-3"></i> Edit Profil
                    </button>
                </div>
            </div>

            <!-- ===== Stats ===== -->
            <div class="profile-stats">
                <div class="profile-stat">
                    <div class="pstat-value"><?= $userStats['total'] ?></div>
                    <div class="pstat-label">Total Laporan</div>
                </div>
                <div class="profile-stat">
                    <div class="pstat-value"><?= $userStats['selesai'] ?></div>
                    <div class="pstat-label">Selesai</div>
                </div>
                <div class="profile-stat">
                    <div class="pstat-value"><?= $userStats['in_progress'] ?></div>
                    <div class="pstat-label">Dalam Proses</div>
                </div>
                <div class="profile-stat">
                    <div class="pstat-value"><?= $userStats['response_rate'] ?>%</div>
                    <div class="pstat-label">Respons Rate</div>
                </div>
            </div>

            <!-- ===== Tabs ===== -->
            <div class="profile-tabs">
                <button class="profile-tab active" data-tab="data-diri" onclick="switchTab('data-diri', this)">Data Diri</button>
                <button class="profile-tab" data-tab="riwayat" onclick="switchTab('riwayat', this)">Riwayat Laporan</button>
                <button class="profile-tab" data-tab="password" onclick="switchTab('password', this)">Ubah Password</button>
            </div>

            <!-- ===== TAB: Data Diri ===== -->
            <div class="tab-content active" id="tab-data-diri">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h2><i data-lucide="user"></i> Informasi Akun</h2>
                        <button class="btn-primary" style="padding:6px 14px;font-size:11px;" onclick="submitProfile()">
                            Simpan
                        </button>
                    </div>
                    <div class="profile-card-body">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input class="form-input" type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input class="form-input" type="text" id="inputFullName" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" placeholder="Nama lengkap">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" id="inputEmail" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input class="form-input" type="tel" id="inputPhone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="No. telepon">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat</label>
                            <input class="form-input" type="text" id="inputAddress" value="<?= htmlspecialchars($user['address'] ?? '') ?>" placeholder="Alamat">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== TAB: Riwayat Laporan ===== -->
            <div class="tab-content" id="tab-riwayat">
                <div class="report-list">

                    <?php if (empty($reports)): ?>
                    <div style="text-align:center;padding:40px 20px;color:#94A3B8;">
                        <i data-lucide="inbox" style="width:48px;height:48px;margin-bottom:12px;opacity:0.5;"></i>
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
                    <div class="report-card">
                        <div class="r-header">
                            <span class="r-title"><?= htmlspecialchars($location) ?> — <?= $title ?></span>
                            <span class="r-id">#<?= htmlspecialchars($report['report_id']) ?></span>
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
                        <div class="r-meta">
                            <span><i data-lucide="calendar"></i> <?= date('j M Y', strtotime($report['created_at'])) ?></span>
                            <?php if ($report['address']): ?>
                            <span><i data-lucide="map-pin"></i> <?= htmlspecialchars($report['address']) ?></span>
                            <?php endif; ?>
                            <span class="status-badge <?= getStatusBadgeClass($report['status']) ?>">
                                <span class="s-dot"></span> <?= statusLabel($report['status']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>

            <!-- ===== TAB: Ubah Password ===== -->
            <div class="tab-content" id="tab-password">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h2><i data-lucide="lock"></i> Ubah Password</h2>
                    </div>
                    <div class="profile-card-body">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <div class="form-group">
                            <label class="form-label">Password Saat Ini</label>
                            <input class="form-input" type="password" id="pwCurrent" placeholder="Masukkan password saat ini">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input class="form-input" type="password" id="pwNew" placeholder="Min. 8 karakter">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input class="form-input" type="password" id="pwConfirm" placeholder="Ulangi password baru">
                        </div>
                        <button class="btn-primary" style="margin-top:8px;" onclick="submitPassword()">
                            <i data-lucide="save"></i> Simpan Password
                        </button>
                    </div>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h2><i data-lucide="activity"></i> Aktivitas Terbaru</h2>
                    </div>
                    <div class="profile-card-body" style="padding:8px 24px;">
                        <?php 
                        $activityReports = array_slice($reports, 0, 5);
                        if (empty($activityReports)): ?>
                        <div class="activity-item">
                            <div class="activity-text">
                                <div class="atitle" style="color:#94A3B8;">Belum ada aktivitas</div>
                            </div>
                        </div>
                        <?php else: ?>
                        <?php foreach ($activityReports as $act): 
                            $actTitle = $act['address'] ?: 'Jalan';
                            $actLabel = $act['status'] === 'dilaporkan' ? 'Melaporkan' : 
                                       ($act['status'] === 'selesai' ? 'Perbaikan selesai' : 'Pembaruan status');
                        ?>
                        <div class="activity-item">
                            <div class="activity-icon" style="background:rgba(29,78,216,0.1);color:var(--primary-700);">
                                <i data-lucide="<?= $act['status'] === 'selesai' ? 'check-circle-2' : 'upload' ?>"></i>
                            </div>
                            <div class="activity-text">
                                <div class="atitle"><?= $actLabel ?> di <?= htmlspecialchars($actTitle) ?></div>
                                <div class="atime"><?= timeAgo($act['created_at']) ?></div>
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

    <script>
        lucide.createIcons();

        // ================================================================
        // LOGIKA TAB
        // Penjelasan: Fungsi switchTab untuk mengganti tab yang aktif
        // (Data Diri, Riwayat Laporan, Ubah Password).
        // ================================================================
        function switchTab(tabName, btn) {
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.remove('active');
            });
            document.querySelectorAll('.profile-tab').forEach(function(el) {
                el.classList.remove('active');
            });
            document.getElementById('tab-' + tabName).classList.add('active');
            btn.classList.add('active');
        }

        // ================================================================
        // SIMPAN DATA DIRI (via AJAX + SweetAlert)
        // ================================================================
        function submitProfile() {
            var fullName = document.getElementById('inputFullName').value.trim();
            var email    = document.getElementById('inputEmail').value.trim();
            var phone    = document.getElementById('inputPhone').value.trim();
            var address  = document.getElementById('inputAddress').value.trim();

            if (!email) {
                Swal.fire({ icon: 'warning', title: 'Email diperlukan', text: 'Email tidak boleh kosong.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } });
                return;
            }

            // Cari CSRF token
            var csrfToken = document.querySelector('#tab-data-diri input[name="csrf_token"]').value;

            var formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('csrf_token', csrfToken);
            formData.append('full_name', fullName);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('address', address);

            fetch('controller/profile_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: true,
                        confirmButtonColor: '#1D4ED8',
                        customClass: { popup: 'rounded-[16px]' }
                    });
                    // Update nama di profile header jika berubah
                    if (data.data && data.data.full_name) {
                        var nameEl = document.querySelector('.profile-info h1');
                        if (nameEl) nameEl.textContent = data.data.full_name;
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        confirmButtonColor: '#DC2626',
                        customClass: { popup: 'rounded-[16px]' }
                    });
                }
            })
            .catch(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Gagal terhubung ke server.',
                    confirmButtonColor: '#DC2626',
                    customClass: { popup: 'rounded-[16px]' }
                });
            });
        }

        // ================================================================
        // UBAH PASSWORD (via AJAX + SweetAlert)
        // ================================================================
        function submitPassword() {
            var currentPw = document.getElementById('pwCurrent').value;
            var newPw     = document.getElementById('pwNew').value;
            var confirmPw = document.getElementById('pwConfirm').value;

            if (!currentPw || !newPw || !confirmPw) {
                Swal.fire({ icon: 'warning', title: 'Lengkapi data', text: 'Semua field password harus diisi.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } });
                return;
            }

            if (newPw.length < 8) {
                Swal.fire({ icon: 'warning', title: 'Terlalu pendek', text: 'Password baru minimal 8 karakter.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } });
                return;
            }

            if (newPw !== confirmPw) {
                Swal.fire({ icon: 'warning', title: 'Tidak cocok', text: 'Konfirmasi password baru tidak cocok.', confirmButtonColor: '#F59E0B', customClass: { popup: 'rounded-[16px]' } });
                return;
            }

            var csrfToken = document.querySelector('#tab-password input[name="csrf_token"]').value;

            var formData = new FormData();
            formData.append('action', 'change_password');
            formData.append('csrf_token', csrfToken);
            formData.append('current_password', currentPw);
            formData.append('new_password', newPw);
            formData.append('confirm_password', confirmPw);

            fetch('controller/profile_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Diubah!',
                        text: data.message,
                        confirmButtonColor: '#1D4ED8',
                        customClass: { popup: 'rounded-[16px]' }
                    }).then(function () {
                        // Kosongkan field password
                        document.getElementById('pwCurrent').value = '';
                        document.getElementById('pwNew').value = '';
                        document.getElementById('pwConfirm').value = '';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        confirmButtonColor: '#DC2626',
                        customClass: { popup: 'rounded-[16px]' }
                    });
                }
            })
            .catch(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Gagal terhubung ke server.',
                    confirmButtonColor: '#DC2626',
                    customClass: { popup: 'rounded-[16px]' }
                });
            });
        }
    </script>
</body>
</html>
