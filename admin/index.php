<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | RoKenAI</title>
    <?php include '../partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Dashboard Admin (desain.md 5.6)
           Sidebar gelap (#1E293B), konten putih, tabel laporan dengan
           status badge & progress "Garis Jalan".
           ================================================================ */

        .admin-layout {
            display: flex;
            min-height: calc(100vh - 80px);
        }

        /* ===== SIDEBAR GELAP (#1E293B) ===== */
        .admin-sidebar {
            width: 250px;
            min-width: 250px;
            background: #1E293B;
            padding: 20px 12px 24px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 12px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 12px;
        }
        .sidebar-brand img { height: 28px; width: auto; }
        .sidebar-brand span {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #F8FAFC;
            font-family: var(--font-heading);
        }
        .sidebar-brand span .accent { color: #3B82F6; }

        .sidebar-section {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748B;
            padding: 16px 12px 8px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: #94A3B8;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            font-family: var(--font-body);
        }
        .sidebar-item i { width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar-item:hover {
            background: rgba(255,255,255,0.06);
            color: #F1F5F9;
        }
        .sidebar-item.active {
            background: rgba(59, 130, 246, 0.12);
            color: #3B82F6;
        }
        .sidebar-item .s-badge {
            margin-left: auto;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            background: rgba(59, 130, 246, 0.15);
            color: #60A5FA;
        }
        .sidebar-item.logout {
            margin-top: auto;
            color: #EF4444;
        }
        .sidebar-item.logout:hover {
            background: rgba(239, 68, 68, 0.08);
            color: #FCA5A5;
        }

        /* ===== MAIN CONTENT (Putih) ===== */
        .admin-main {
            flex: 1;
            padding: 28px 32px 40px;
            max-width: 1200px;
            min-width: 0;
        }

        .admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
        }
        .admin-header h1 {
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 700;
            color: var(--ink-900);
        }
        .admin-header h1 span {
            font-weight: 400;
            font-size: 16px;
            color: #94A3B8;
        }
        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .date-badge {
            padding: 6px 14px;
            border-radius: var(--radius-full);
            background: var(--primary-100);
            font-size: 12px;
            color: var(--primary-700);
            font-weight: 500;
        }

        /* ===== STATS CARDS (Putih) ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            padding: 20px 24px;
            position: relative;
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            border-color: var(--primary-100);
            box-shadow: var(--shadow-glow);
        }
        .stat-card .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        .stat-card .stat-value {
            font-family: var(--font-heading);
            font-size: 30px;
            font-weight: 700;
            color: var(--ink-900);
            letter-spacing: -0.03em;
        }
        .stat-card .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
        }
        .stat-card .stat-change.up { color: var(--status-success); }
        .stat-card .stat-change.down { color: var(--status-danger); }
        .stat-card .stat-change i { width: 14px; height: 14px; }

        .stat-card .stat-icon-bg {
            position: absolute;
            right: 16px;
            top: 16px;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-card .stat-icon-bg i { width: 18px; height: 18px; }

        /* ===== SECTION CARDS (Putih) ===== */
        .admin-section {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .admin-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid var(--line-200);
            flex-wrap: wrap;
            gap: 12px;
        }
        .admin-section-header h2 {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 600;
            color: var(--ink-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .admin-section-header h2 i { width: 18px; height: 18px; color: var(--primary-700); }
        .admin-section-header .section-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== TABLE ===== */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th {
            text-align: left;
            padding: 12px 24px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94A3B8;
            border-bottom: 1px solid var(--line-200);
            background: var(--surface-muted);
        }
        .admin-table td {
            padding: 14px 24px;
            font-size: 13px;
            color: var(--ink-600);
            border-bottom: 1px solid var(--line-200);
            vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(59, 130, 246, 0.02); }

        .admin-table .td-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-table .td-id {
            font-family: var(--font-mono);
            font-size: 11px;
            color: #94A3B8;
        }
        .admin-table .td-lokasi {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink-900);
        }
        .admin-table .td-thumb {
            width: 40px; height: 40px;
            border-radius: var(--radius-sm);
            background: var(--surface-muted);
            border: 1px solid var(--line-200);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
        }
        .admin-table .td-thumb i { width: 16px; height: 16px; }
        .admin-table .td-user {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .admin-table .td-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--primary-700);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
        }

        /* ===== Garis Jalan Mini di Tabel ===== */
        .admin-table .garis-jalan.mini {
            max-width: 140px;
        }

        /* ===== Activity Feed (Putih) ===== */
        .activity-feed { padding: 8px; }
        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            border-radius: var(--radius-sm);
            transition: background 0.15s ease;
        }
        .activity-item:hover { background: var(--surface-muted); }
        .activity-dot {
            width: 8px; height: 8px; min-width: 8px;
            border-radius: 50%;
            margin-top: 5px;
        }
        .activity-content { flex: 1; }
        .activity-title {
            font-size: 13px;
            color: var(--ink-900);
            font-weight: 500;
        }
        .activity-desc {
            font-size: 12px;
            color: var(--ink-600);
            margin-top: 2px;
        }
        .activity-time {
            font-size: 11px;
            color: #94A3B8;
            margin-top: 4px;
        }

        /* ===== System Info Grid ===== */
        .sys-info-row {
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 20px 24px;
        }
        .sys-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sys-item .sys-label {
            font-size: 13px;
            color: var(--ink-600);
        }
        .sys-item .sys-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-900);
            font-family: var(--font-mono);
        }
        .sys-bar {
            height: 6px;
            background: var(--line-200);
            border-radius: 100px;
            overflow: hidden;
        }
        .sys-bar .sys-fill {
            height: 100%;
            border-radius: 100px;
            transition: width 0.5s ease;
        }

        /* ===== Two column row ===== */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        @media (max-width: 900px) {
            .two-col { grid-template-columns: 1fr; }
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .admin-sidebar { display: none; }
            .admin-main { padding: 20px 16px 32px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .admin-main { padding: 16px 12px 24px; }
            .stats-grid { grid-template-columns: 1fr; }
            .admin-header h1 { font-size: 20px; }
            .admin-header h1 span { display: block; font-size: 14px; }
            .admin-table th, .admin-table td { padding: 10px 14px; }
            .admin-table .td-id { display: none; }
            .admin-section-header { padding: 14px 16px; }
        }
    </style>
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="admin-layout">

            <!-- ===== SIDEBAR GELAP (#1E293B) ===== -->
            <aside class="admin-sidebar">
                <div class="sidebar-brand">
                    <img src="../assets/Logo.png" alt="RoKenAI">
                    <span>RoKen<span class="accent">AI</span></span>
                </div>

                <div class="sidebar-section">Utama</div>
                <a class="sidebar-item active" href="index.php">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
                <a class="sidebar-item" href="#">
                    <i data-lucide="image"></i> Laporan
                    <span class="s-badge">48</span>
                </a>
                <a class="sidebar-item" href="#">
                    <i data-lucide="users"></i> Pengguna
                    <span class="s-badge">12</span>
                </a>
                <a class="sidebar-item" href="#">
                    <i data-lucide="message-square"></i> Chat AI
                </a>

                <div class="sidebar-section">Sistem</div>
                <a class="sidebar-item" href="#">
                    <i data-lucide="settings"></i> Pengaturan
                </a>
                <a class="sidebar-item" href="#">
                    <i data-lucide="activity"></i> Log Aktivitas
                </a>

                <a class="sidebar-item logout" href="../auth/logout.php">
                    <i data-lucide="log-out"></i> Keluar
                </a>
            </aside>

            <!-- ===== MAIN CONTENT (Putih) ===== -->
            <main class="admin-main page-enter">

                <!-- Header -->
                <div class="admin-header">
                    <h1>Dashboard <span>/ Ringkasan</span></h1>
                    <div class="admin-header-right">
                        <span class="date-badge">
                            <i data-lucide="calendar" style="width:12px;height:12px;margin-right:4px;vertical-align:middle;"></i>
                            <?= date('d F Y') ?>
                        </span>
                        <button class="btn-secondary" style="padding:8px 16px;font-size:12px;" onclick="alert('Fitur ekspor akan segera tersedia!')">
                            <i data-lucide="download"></i> Ekspor
                        </button>
                    </div>
                </div>

                <!-- ===== STATS CARDS ===== -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon-bg" style="background:rgba(29,78,216,0.1);color:var(--primary-700);">
                            <i data-lucide="flag"></i>
                        </div>
                        <div class="stat-label">Total Laporan</div>
                        <div class="stat-value">1.247</div>
                        <div class="stat-change up">
                            <i data-lucide="trending-up"></i> +12.5%
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon-bg" style="background:rgba(22,163,74,0.1);color:var(--status-success);">
                            <i data-lucide="check-circle-2"></i>
                        </div>
                        <div class="stat-label">Selesai</div>
                        <div class="stat-value">892</div>
                        <div class="stat-change up">
                            <i data-lucide="trending-up"></i> +8.2%
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon-bg" style="background:rgba(245,158,11,0.1);color:var(--status-warning);">
                            <i data-lucide="clock"></i>
                        </div>
                        <div class="stat-label">Dalam Proses</div>
                        <div class="stat-value">234</div>
                        <div class="stat-change down">
                            <i data-lucide="trending-down"></i> -3.1%
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon-bg" style="background:rgba(59,130,246,0.1);color:var(--primary-500);">
                            <i data-lucide="brain"></i>
                        </div>
                        <div class="stat-label">Akurasi AI</div>
                        <div class="stat-value">94.7%</div>
                        <div class="stat-change up">
                            <i data-lucide="trending-up"></i> +2.1%
                        </div>
                    </div>
                </div>

                <!-- ===== TABEL LAPORAN TERBARU ===== -->
                <div class="admin-section">
                    <div class="admin-section-header">
                        <h2><i data-lucide="list"></i> Laporan Terbaru</h2>
                        <div class="section-actions">
                            <button class="btn-secondary" style="padding:6px 12px;font-size:12px;" onclick="alert('Fitur filter akan segera tersedia!')">
                                <i data-lucide="filter"></i> Filter
                            </button>
                            <button class="btn-primary" style="padding:6px 12px;font-size:12px;" onclick="alert('Menampilkan semua laporan...')">
                                <i data-lucide="eye"></i> Lihat Semua
                            </button>
                        </div>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Lokasi</th>
                                <th>Jenis</th>
                                <th>AI Confidence</th>
                                <th>Pelapor</th>
                                <th>Tanggal</th>
                                <th>Progres</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="td-id">#RK-0421</span></td>
                                <td><span class="td-lokasi">Jl. Ahmad Yani</span></td>
                                <td>Lubang Jalan</td>
                                <td><span class="badge badge-success">96.2%</span></td>
                                <td>
                                    <div class="td-user">
                                        <div class="td-avatar">AB</div>
                                        <span>Andi Budiman</span>
                                    </div>
                                </td>
                                <td>24 Jun 2026</td>
                                <td>
                                    <!-- Garis Jalan Progres: 50% (Dilaporkan + Diverifikasi) -->
                                    <div class="garis-jalan mini">
                                        <div class="progress-fill" style="width:50%;"></div>
                                        <div class="marka-line"></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point"><i data-lucide="circle" class="gj-icon"></i></div>
                                        <div class="gj-point"><i data-lucide="circle" class="gj-icon"></i></div>
                                    </div>
                                </td>
                                <td><span class="status-badge diverifikasi"><span class="s-dot"></span> Diverifikasi</span></td>
                            </tr>
                            <tr>
                                <td><span class="td-id">#RK-0420</span></td>
                                <td><span class="td-lokasi">Jl. Diponegoro</span></td>
                                <td>Retak Jalan</td>
                                <td><span class="badge badge-warning">84.5%</span></td>
                                <td>
                                    <div class="td-user">
                                        <div class="td-avatar">SR</div>
                                        <span>Sari Rahayu</span>
                                    </div>
                                </td>
                                <td>23 Jun 2026</td>
                                <td>
                                    <!-- Garis Jalan Progres: 25% (Dilaporkan saja) -->
                                    <div class="garis-jalan mini">
                                        <div class="progress-fill" style="width:25%;"></div>
                                        <div class="marka-line"></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point"><i data-lucide="circle" class="gj-icon"></i></div>
                                        <div class="gj-point"><i data-lucide="circle" class="gj-icon"></i></div>
                                        <div class="gj-point"><i data-lucide="circle" class="gj-icon"></i></div>
                                    </div>
                                </td>
                                <td><span class="status-badge dilaporkan"><span class="s-dot"></span> Dilaporkan</span></td>
                            </tr>
                            <tr>
                                <td><span class="td-id">#RK-0419</span></td>
                                <td><span class="td-lokasi">Jl. Sudirman</span></td>
                                <td>Bergelombang</td>
                                <td><span class="badge badge-success">91.3%</span></td>
                                <td>
                                    <div class="td-user">
                                        <div class="td-avatar">DW</div>
                                        <span>Dwi Wijaya</span>
                                    </div>
                                </td>
                                <td>22 Jun 2026</td>
                                <td>
                                    <!-- Garis Jalan Progres: 100% (Selesai) -->
                                    <div class="garis-jalan mini">
                                        <div class="progress-fill" style="width:100%;"></div>
                                        <div class="marka-line"></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                    </div>
                                </td>
                                <td><span class="status-badge selesai"><span class="s-dot"></span> Selesai</span></td>
                            </tr>
                            <tr>
                                <td><span class="td-id">#RK-0418</span></td>
                                <td><span class="td-lokasi">Jl. Basuki Rahmat</span></td>
                                <td>Lubang Jalan</td>
                                <td><span class="badge badge-success">93.8%</span></td>
                                <td>
                                    <div class="td-user">
                                        <div class="td-avatar">RM</div>
                                        <span>Rudi Maulana</span>
                                    </div>
                                </td>
                                <td>21 Jun 2026</td>
                                <td>
                                    <!-- Garis Jalan Progres: 75% (Diperbaiki) -->
                                    <div class="garis-jalan mini">
                                        <div class="progress-fill" style="width:75%;"></div>
                                        <div class="marka-line"></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point active"><i data-lucide="check" class="gj-icon"></i></div>
                                        <div class="gj-point"><i data-lucide="circle" class="gj-icon"></i></div>
                                    </div>
                                </td>
                                <td><span class="status-badge diperbaiki"><span class="s-dot"></span> Diperbaiki</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ===== TWO COLUMN: Aktivitas & Info Sistem ===== -->
                <div class="two-col">
                    <!-- Aktivitas Terbaru -->
                    <div class="admin-section">
                        <div class="admin-section-header">
                            <h2><i data-lucide="activity"></i> Aktivitas Terbaru</h2>
                        </div>
                        <div class="activity-feed">
                            <div class="activity-item">
                                <div class="activity-dot" style="background:var(--status-success);"></div>
                                <div class="activity-content">
                                    <div class="activity-title">Laporan baru dari Jl. Ahmad Yani</div>
                                    <div class="activity-desc">Deteksi: Lubang Jalan (96.2%) — menunggu verifikasi</div>
                                    <div class="activity-time">2 menit yang lalu</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot" style="background:var(--primary-700);"></div>
                                <div class="activity-content">
                                    <div class="activity-title">Pengguna baru mendaftar</div>
                                    <div class="activity-desc">Email: budi@email.com</div>
                                    <div class="activity-time">15 menit yang lalu</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot" style="background:var(--status-warning);"></div>
                                <div class="activity-content">
                                    <div class="activity-title">Model AI diperbarui</div>
                                    <div class="activity-desc">YOLOv8 v8.2.0 — peningkatan akurasi 2.1%</div>
                                    <div class="activity-time">1 jam yang lalu</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot" style="background:var(--status-success);"></div>
                                <div class="activity-content">
                                    <div class="activity-title">Perbaikan Jl. Sudirman selesai</div>
                                    <div class="activity-desc">Status diperbarui menjadi Selesai</div>
                                    <div class="activity-time">3 jam yang lalu</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Sistem -->
                    <div class="admin-section">
                        <div class="admin-section-header">
                            <h2><i data-lucide="server"></i> Info Sistem</h2>
                        </div>
                        <div class="sys-info-row">
                            <div>
                                <div class="sys-item">
                                    <span class="sys-label">GPU Utilization</span>
                                    <span class="sys-value">42%</span>
                                </div>
                                <div class="sys-bar" style="margin-top:6px;">
                                    <div class="sys-fill" style="width:42%;background:linear-gradient(90deg,var(--primary-700),var(--primary-500));"></div>
                                </div>
                            </div>
                            <div>
                                <div class="sys-item">
                                    <span class="sys-label">Memory Usage</span>
                                    <span class="sys-value">6.2 / 16 GB</span>
                                </div>
                                <div class="sys-bar" style="margin-top:6px;">
                                    <div class="sys-fill" style="width:39%;background:linear-gradient(90deg,var(--primary-500),#60A5FA);"></div>
                                </div>
                            </div>
                            <div>
                                <div class="sys-item">
                                    <span class="sys-label">Storage</span>
                                    <span class="sys-value">24.8 / 100 GB</span>
                                </div>
                                <div class="sys-bar" style="margin-top:6px;">
                                    <div class="sys-fill" style="width:25%;background:linear-gradient(90deg,var(--status-success),#4ADE80);"></div>
                                </div>
                            </div>
                            <div>
                                <div class="sys-item">
                                    <span class="sys-label">API Requests (24h)</span>
                                    <span class="sys-value">2,847</span>
                                </div>
                                <div class="sys-bar" style="margin-top:6px;">
                                    <div class="sys-fill" style="width:74%;background:linear-gradient(90deg,var(--status-warning),var(--status-danger));"></div>
                                </div>
                            </div>
                            <div style="display:flex;gap:12px;padding-top:12px;border-top:1px solid var(--line-200);">
                                <span class="badge badge-success"><i data-lucide="check-circle" style="width:12px;height:12px;"></i> Semua Sistem Online</span>
                                <span class="badge">v3.2.1</span>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
