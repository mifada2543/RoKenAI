<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
           RoKenAI — Halaman Profil (desain.md 5.5)
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
                <div class="profile-avatar">AB</div>
                <div class="profile-info">
                    <h1>Andi Budiman</h1>
                    <div class="p-email">andi.budiman@email.com</div>
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
                    <div class="pstat-value">12</div>
                    <div class="pstat-label">Total Laporan</div>
                </div>
                <div class="profile-stat">
                    <div class="pstat-value">8</div>
                    <div class="pstat-label">Selesai</div>
                </div>
                <div class="profile-stat">
                    <div class="pstat-value">3</div>
                    <div class="pstat-label">Dalam Proses</div>
                </div>
                <div class="profile-stat">
                    <div class="pstat-value">94%</div>
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
                        <button class="btn-primary" style="padding:6px 14px;font-size:11px;" onclick="alert('Data berhasil disimpan! (Demo)')">
                            Simpan
                        </button>
                    </div>
                    <div class="profile-card-body">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input class="form-input" type="text" value="Andi Budiman" placeholder="Nama lengkap">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" value="andi.budiman@email.com" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input class="form-input" type="tel" value="+62 812 3456 7890" placeholder="No. telepon">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat</label>
                            <input class="form-input" type="text" value="Jl. Raya No. 123, Surabaya" placeholder="Alamat">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== TAB: Riwayat Laporan ===== -->
            <div class="tab-content" id="tab-riwayat">
                <div class="report-list">

                    <!-- Laporan 1 — dengan garis jalan progress -->
                    <div class="report-card">
                        <div class="r-header">
                            <span class="r-title">Jl. Ahmad Yani — Lubang Jalan</span>
                            <span class="r-id">#RK-2026-0421</span>
                        </div>
                        <!-- Garis Jalan Progress: Dilaporkan (active) → Diverifikasi (active) → Diperbaiki → Selesai -->
                        <div class="garis-jalan mini" style="margin:8px 0;">
                            <div class="progress-fill" style="width:50%;"></div>
                            <div class="marka-line"></div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point">
                                <i data-lucide="circle" class="gj-icon"></i>
                            </div>
                            <div class="gj-point">
                                <i data-lucide="circle" class="gj-icon"></i>
                            </div>
                        </div>
                        <div class="r-meta">
                            <span><i data-lucide="calendar"></i> 24 Juni 2026</span>
                            <span><i data-lucide="map-pin"></i> Surabaya</span>
                            <span class="status-badge diverifikasi"><span class="s-dot"></span> Diverifikasi</span>
                        </div>
                    </div>

                    <!-- Laporan 2 -->
                    <div class="report-card">
                        <div class="r-header">
                            <span class="r-title">Jl. Diponegoro — Retak Jalan</span>
                            <span class="r-id">#RK-2026-0420</span>
                        </div>
                        <div class="garis-jalan mini" style="margin:8px 0;">
                            <div class="progress-fill" style="width:25%;"></div>
                            <div class="marka-line"></div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point">
                                <i data-lucide="circle" class="gj-icon"></i>
                            </div>
                            <div class="gj-point">
                                <i data-lucide="circle" class="gj-icon"></i>
                            </div>
                            <div class="gj-point">
                                <i data-lucide="circle" class="gj-icon"></i>
                            </div>
                        </div>
                        <div class="r-meta">
                            <span><i data-lucide="calendar"></i> 23 Juni 2026</span>
                            <span><i data-lucide="map-pin"></i> Surabaya</span>
                            <span class="status-badge dilaporkan"><span class="s-dot"></span> Dilaporkan</span>
                        </div>
                    </div>

                    <!-- Laporan 3 — Selesai -->
                    <div class="report-card">
                        <div class="r-header">
                            <span class="r-title">Jl. Sudirman — Jalan Bergelombang</span>
                            <span class="r-id">#RK-2026-0419</span>
                        </div>
                        <div class="garis-jalan mini" style="margin:8px 0;">
                            <div class="progress-fill" style="width:100%;"></div>
                            <div class="marka-line"></div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                        </div>
                        <div class="r-meta">
                            <span><i data-lucide="calendar"></i> 20 Juni 2026</span>
                            <span><i data-lucide="map-pin"></i> Surabaya</span>
                            <span class="status-badge selesai"><span class="s-dot"></span> Selesai</span>
                        </div>
                    </div>

                    <!-- Laporan 4 -->
                    <div class="report-card">
                        <div class="r-header">
                            <span class="r-title">Jl. Basuki Rahmat — Lubang Jalan</span>
                            <span class="r-id">#RK-2026-0418</span>
                        </div>
                        <div class="garis-jalan mini" style="margin:8px 0;">
                            <div class="progress-fill" style="width:75%;"></div>
                            <div class="marka-line"></div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point active">
                                <i data-lucide="check" class="gj-icon"></i>
                            </div>
                            <div class="gj-point">
                                <i data-lucide="circle" class="gj-icon"></i>
                            </div>
                        </div>
                        <div class="r-meta">
                            <span><i data-lucide="calendar"></i> 18 Juni 2026</span>
                            <span><i data-lucide="map-pin"></i> Surabaya</span>
                            <span class="status-badge diperbaiki"><span class="s-dot"></span> Diperbaiki</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===== TAB: Ubah Password ===== -->
            <div class="tab-content" id="tab-password">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <h2><i data-lucide="lock"></i> Ubah Password</h2>
                    </div>
                    <div class="profile-card-body">
                        <div class="form-group">
                            <label class="form-label">Password Saat Ini</label>
                            <input class="form-input" type="password" placeholder="Masukkan password saat ini">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input class="form-input" type="password" placeholder="Min. 8 karakter">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input class="form-input" type="password" placeholder="Ulangi password baru">
                        </div>
                        <button class="btn-primary" style="margin-top:8px;" onclick="alert('Password berhasil diubah! (Demo)')">
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
                        <div class="activity-item">
                            <div class="activity-icon" style="background:rgba(29,78,216,0.1);color:var(--primary-700);">
                                <i data-lucide="upload"></i>
                            </div>
                            <div class="activity-text">
                                <div class="atitle">Melaporkan jalan rusak di Jl. Ahmad Yani</div>
                                <div class="atime">2 jam lalu</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background:rgba(245,158,11,0.1);color:var(--status-warning);">
                                <i data-lucide="refresh-cw"></i>
                            </div>
                            <div class="activity-text">
                                <div class="atitle">Laporan Jl. Diponegoro diverifikasi</div>
                                <div class="atime">5 jam lalu</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background:rgba(22,163,74,0.1);color:var(--status-success);">
                                <i data-lucide="check-circle-2"></i>
                            </div>
                            <div class="activity-text">
                                <div class="atitle">Perbaikan Jl. Sudirman selesai</div>
                                <div class="atime">2 hari lalu</div>
                            </div>
                        </div>
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
            // Sembunyikan semua konten tab
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.remove('active');
            });
            // Non-aktifkan semua tab button
            document.querySelectorAll('.profile-tab').forEach(function(el) {
                el.classList.remove('active');
            });
            // Aktifkan tab yang dipilih
            document.getElementById('tab-' + tabName).classList.add('active');
            btn.classList.add('active');
        }
    </script>
</body>
</html>
