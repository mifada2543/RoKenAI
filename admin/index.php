<?php
session_name('RoKenAI');
session_start();

require_once __DIR__ . '/../auth/config.php';
require_once __DIR__ . '/../controller/report.php';

// Cek admin login
$isAdmin = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true && ($_SESSION['role'] ?? '') === 'admin';
if (!$isAdmin) {
    header('Location: ' . (isset($_SESSION['user_logged_in']) ? '../index.php' : '../auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'])));
    exit;
}

$adminName = $_SESSION['username'] ?? 'Admin';

// ===== Ambil data statistik langsung dari server (bypass session API issue) =====
$statsData = [];
$r = $conn->query("SELECT COUNT(*) as v FROM reports");
$statsData['total_reports'] = $r ? (int)$r->fetch_assoc()['v'] : 0;
$r = $conn->query("SELECT status, COUNT(*) as v FROM reports GROUP BY status");
$byStatus = ['dilaporkan' => 0, 'diverifikasi' => 0, 'diperbaiki' => 0, 'selesai' => 0];
if ($r) while ($row = $r->fetch_assoc()) $byStatus[$row['status']] = (int)$row['v'];
$statsData['by_status'] = $byStatus;
$r = $conn->query("SELECT COUNT(*) as v FROM users");
$statsData['total_users'] = $r ? (int)$r->fetch_assoc()['v'] : 0;
$r = $conn->query("SELECT COUNT(*) as v FROM users WHERE is_active = 2");
$statsData['pending_users'] = $r ? (int)$r->fetch_assoc()['v'] : 0;
$r = $conn->query("SELECT COUNT(*) as v FROM users WHERE is_active = 1");
$statsData['active_users'] = $r ? (int)$r->fetch_assoc()['v'] : 0;

// ===== Ambil data laporan dari server (initial load) =====
$reportsData = [];
$rptStmt = $conn->prepare("SELECT r.*, u.username, u.full_name FROM reports r LEFT JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
if ($rptStmt) {
    $rptStmt->execute();
    $result = $rptStmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['created_ago'] = timeAgo($row['created_at']);
        $row['damage_label'] = damageTypeLabel($row['damage_type']);
        $row['status_label'] = statusLabel($row['status']);
        $row['badge_class'] = getStatusBadgeClass($row['status']);
        $reportsData[] = $row;
    }
    $rptStmt->close();
}

// ===== Ambil data pengguna dari server (initial load) =====
$usersData = [];
$usrStmt = $conn->prepare("SELECT id, username, full_name, email, phone, address, role, is_active, created_at FROM users ORDER BY created_at DESC");
if ($usrStmt) {
    $usrStmt->execute();
    $result = $usrStmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['created_ago'] = timeAgo($row['created_at']);
        switch ($row['is_active']) {
            case 0: $row['status_label'] = 'Nonaktif'; $row['badge_class'] = 'inactive'; break;
            case 1: $row['status_label'] = 'Aktif';    $row['badge_class'] = 'active'; break;
            case 2: $row['status_label'] = 'Pending';  $row['badge_class'] = 'pending'; break;
        }
        $rc = $conn->query("SELECT COUNT(*) as v FROM reports WHERE user_id = " . (int)$row['id']);
        $row['report_count'] = ($rc) ? (int)$rc->fetch_assoc()['v'] : 0;
        $usersData[] = $row;
    }
    $usrStmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | RoKenAI</title>
    <meta name="description" content="Admin Panel - RoKenAI Platform Deteksi Kerusakan Jalan">
    <meta name="theme-color" content="#1D4ED8">
    <link rel="icon" type="image/png" href="../assets/Logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="../assets/js/lucide.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>

    <style>
        /* ===== Base styles (self-contained, tidak bergantung link.php) ===== */
        :root {
            --font-heading: 'Plus Jakarta Sans', 'Inter', sans-serif;
            --font-body: 'Inter', 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'IBM Plex Mono', monospace;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; -webkit-font-smoothing: antialiased; }
        body {
            font-family: var(--font-body);
            background: #F8FAFC;
            color: #475569;
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
        }
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ===== Admin CSS (dari sini ke bawah masih sama seperti sebelumnya) ===== */
        /* ===== Admin Top Bar (Mobile) ===== */
        .admin-topbar {
            display: none;
            position: fixed; top: 0; left: 0; right: 0; z-index: 120;
            height: 56px; background: #fff;
            border-bottom: 1px solid #E2E8F0;
            align-items: center; padding: 0 16px; gap: 12px;
        }
        .admin-topbar .at-logo {
            display: flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .admin-topbar .at-logo img { height: 26px; width: auto; }
        .admin-topbar .at-logo span {
            font-size: 16px; font-weight: 800; letter-spacing: -0.03em;
            color: #0F172A; font-family: var(--font-heading);
        }
        .admin-topbar .at-title {
            flex: 1; font-size: 14px; font-weight: 600; color: #0F172A;
            font-family: var(--font-heading); text-align: center;
        }
        .admin-hamburger {
            display: none;
            width: 36px; height: 36px; min-width: 36px;
            border-radius: 8px; border: 1px solid #E2E8F0;
            background: #F8FAFC; cursor: pointer;
            flex-direction: column; align-items: center; justify-content: center;
            gap: 4px; transition: all 0.2s ease;
        }
        .admin-hamburger:hover { border-color: #3B82F6; background: #DBEAFE; }
        .admin-hamburger .ah-line {
            display: block; width: 16px; height: 2px;
            background: #475569; border-radius: 2px;
            transition: all 0.3s ease;
        }
        .admin-hamburger.active .ah-line:nth-child(1) { transform: translateY(6px) rotate(45deg); }
        .admin-hamburger.active .ah-line:nth-child(2) { opacity: 0; }
        .admin-hamburger.active .ah-line:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }
        .admin-hamburger.active .ah-line { background: #1D4ED8; }

        /* ===== Admin Layout ===== */
        .admin-layout { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 220px; min-width: 220px;
            background: #fff; border-right: 1px solid #E2E8F0;
            padding: 20px 12px; display: flex; flex-direction: column; gap: 4px;
        }
        .admin-sidebar .as-logo {
            display: flex; align-items: center; gap: 10px;
            padding: 4px 12px 16px; border-bottom: 1px solid #E2E8F0; margin-bottom: 12px;
            text-decoration: none;
        }
        .admin-sidebar .as-logo img { height: 28px; width: auto; }
        .admin-sidebar .as-logo span {
            font-size: 18px; font-weight: 800; letter-spacing: -0.03em;
            color: #0F172A; font-family: var(--font-heading);
        }
        .admin-sidebar .as-title {
            font-size: 10px; font-weight: 700; color: #94A3B8;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 8px 12px 4px; font-family: var(--font-heading);
        }
        .admin-sidebar .as-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: #475569;
            text-decoration: none; cursor: pointer; transition: all 0.15s ease;
            border: none; background: none; width: 100%; text-align: left; font-family: var(--font-body);
        }
        .admin-sidebar .as-link:hover { background: #F8FAFC; color: #1D4ED8; }
        .admin-sidebar .as-link.active { background: #DBEAFE; color: #1D4ED8; font-weight: 600; }
        .admin-sidebar .as-link i { width: 16px; height: 16px; flex-shrink: 0; }

        .admin-main { flex: 1; padding: 24px 32px; overflow-y: auto; }
        .admin-main h1 {
            font-family: var(--font-heading); font-size: 22px; font-weight: 700;
            color: #0F172A; margin-bottom: 4px;
        }
        .admin-main .page-desc { font-size: 14px; color: #475569; margin-bottom: 24px; }

        /* ===== Stat Cards ===== */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 24px; }
        .stat-card {
            background: #fff; border-radius: 10px; border: 1px solid #E2E8F0;
            padding: 16px 20px; box-shadow: 0 1px 3px rgba(15,23,42,0.04);
        }
        .stat-card .sc-value { font-family: var(--font-heading); font-size: 24px; font-weight: 700; color: #0F172A; }
        .stat-card .sc-label { font-size: 12px; color: #94A3B8; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.03em; font-weight: 500; }
        .stat-card .sc-icon { float: right; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }

        /* ===== Tables ===== */
        .table-wrap {
            background: #fff; border-radius: 12px; border: 1px solid #E2E8F0;
            overflow: hidden; box-shadow: 0 1px 3px rgba(15,23,42,0.04);
        }
        .table-toolbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px; border-bottom: 1px solid #E2E8F0; flex-wrap: wrap; gap: 8px;
        }
        .table-toolbar .filter-group { display: flex; gap: 8px; align-items: center; }
        .table-toolbar select, .table-toolbar input {
            padding: 8px 12px; border-radius: 8px; border: 1.5px solid #E2E8F0;
            font-size: 13px; font-family: var(--font-body); color: #0F172A; outline: none;
            background: #fff; transition: all 0.2s ease;
        }
        .table-toolbar select:focus, .table-toolbar input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

        table { width: 100%; border-collapse: collapse; }
        thead { background: #F8FAFC; }
        th { text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 600; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
        td { padding: 10px 16px; font-size: 13px; color: #0F172A; border-top: 1px solid #F1F5F9; }
        tr:hover td { background: #FAFBFC; }

        .cell-mono { font-family: var(--font-mono); font-size: 12px; color: #64748B; }
        .cell-small { font-size: 12px; color: #94A3B8; }

        .action-btns { display: flex; gap: 4px; flex-wrap: wrap; }
        .action-btns .abtn {
            padding: 6px 12px; border-radius: 6px; border: none;
            font-size: 11px; font-weight: 600; cursor: pointer;
            transition: all 0.15s ease; font-family: var(--font-body);
            display: inline-flex; align-items: center; gap: 4px;
        }
        .abtn-primary { background: #DBEAFE; color: #1D4ED8; }
        .abtn-primary:hover { background: rgba(29,78,216,0.15); }
        .abtn-success { background: rgba(22,163,74,0.1); color: #16A34A; }
        .abtn-success:hover { background: rgba(22,163,74,0.18); }
        .abtn-warning { background: rgba(245,158,11,0.1); color: #D97706; }
        .abtn-warning:hover { background: rgba(245,158,11,0.18); }
        .abtn-danger { background: rgba(220,38,38,0.1); color: #DC2626; }
        .abtn-danger:hover { background: rgba(220,38,38,0.18); }
        .abtn-ghost { background: #F1F5F9; color: #64748B; }
        .abtn-ghost:hover { background: #E2E8F0; }

        /* ===== Content sections ===== */
        .admin-section { display: none; }
        .admin-section.active { display: block; animation: fadeInUp 0.3s ease; }

        /* ===== Badge (reuse) ===== */
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; }
        .status-badge .s-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .status-badge.dilaporkan { background: rgba(59,130,246,0.1); color: #2563EB; }
        .status-badge.dilaporkan .s-dot { background: #2563EB; }
        .status-badge.diverifikasi { background: rgba(245,158,11,0.1); color: #F59E0B; }
        .status-badge.diverifikasi .s-dot { background: #F59E0B; }
        .status-badge.diperbaiki { background: rgba(37,99,235,0.1); color: #2563EB; }
        .status-badge.diperbaiki .s-dot { background: #2563EB; }
        .status-badge.selesai { background: rgba(22,163,74,0.1); color: #16A34A; }
        .status-badge.selesai .s-dot { background: #16A34A; }

        .status-badge.active { background: rgba(22,163,74,0.1); color: #16A34A; }
        .status-badge.active .s-dot { background: #16A34A; }
        .status-badge.inactive { background: rgba(100,116,139,0.1); color: #64748B; }
        .status-badge.inactive .s-dot { background: #64748B; }
        .status-badge.pending { background: rgba(245,158,11,0.1); color: #F59E0B; }
        .status-badge.pending .s-dot { background: #F59E0B; }

        /* ===== Modal ===== */
        .modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(15,23,42,0.4); backdrop-filter: blur(4px);
            align-items: center; justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-card {
            background: #fff; border-radius: 16px; padding: 0;
            max-width: 560px; width: 90%; max-height: 85vh; overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15); animation: fadeInUp 0.25s ease;
        }
        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 24px; border-bottom: 1px solid #E2E8F0; position: sticky; top: 0;
            background: #fff; z-index: 1;
        }
        .modal-header h2 { font-family: var(--font-heading); font-size: 16px; font-weight: 600; color: #0F172A; }
        .modal-close {
            width: 32px; height: 32px; border-radius: 8px; border: none;
            background: #F1F5F9; color: #64748B; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .modal-close:hover { background: #E2E8F0; }
        .modal-body { padding: 20px 24px; }

        .detail-row { display: flex; padding: 8px 0; border-bottom: 1px solid #F1F5F9; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 120px; font-size: 12px; font-weight: 600; color: #94A3B8; flex-shrink: 0; }
        .detail-value { flex: 1; font-size: 13px; color: #0F172A; }

        .detail-img { width: 100%; border-radius: 8px; margin: 8px 0; max-height: 300px; object-fit: cover; }

        /* ===== Sidebar Overlay (Mobile) ===== */
        .admin-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 130;
            background: rgba(15,23,42,0.3);
            backdrop-filter: blur(4px);
            opacity: 0; transition: opacity 0.3s ease;
        }
        .admin-overlay.open { display: block; opacity: 1; }

        /* ===== Responsive: Mobile ===== */
        @media (max-width: 768px) {
            .admin-topbar { display: flex; }
            .admin-hamburger { display: flex; }
            .admin-layout { padding-top: 56px; }
            .admin-sidebar {
                position: fixed; top: 0; left: 0; z-index: 140;
                height: 100vh; padding-top: 64px;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
                display: flex;
            }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-sidebar .as-logo { display: none; } /* top bar sudah ada logo */
            body.sidebar-open { overflow: hidden; }
            .admin-main { padding: 16px; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .table-toolbar { flex-direction: column; align-items: stretch; }
            .table-toolbar .filter-group { flex-wrap: wrap; }
            table { font-size: 12px; }
            th, td { padding: 8px 10px; }
            .action-btns .abtn { font-size: 10px; padding: 4px 8px; }
        }
        @media (min-width: 769px) {
            .admin-overlay { display: none !important; }
        }
    </style>
</head>
<body>

    <!-- Mobile Top Bar -->
    <div class="admin-topbar" id="adminTopbar">
        <button class="admin-hamburger" id="adminHamburger" onclick="toggleSidebar()" aria-label="Toggle menu">
            <span class="ah-line"></span>
            <span class="ah-line"></span>
            <span class="ah-line"></span>
        </button>
        <div class="at-title">Admin Panel</div>
        <a href="../index.php" class="at-logo">
            <img src="../assets/Logo.png" alt="RoKenAI">
        </a>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="admin-overlay" id="adminOverlay" onclick="toggleSidebar()"></div>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <a href="../index.php" class="as-logo">
                <img src="../assets/Logo.png" alt="RoKenAI">
                <span>RoKen<span style="color:#1D4ED8;">AI</span></span>
            </a>
            <div class="as-title">Navigasi</div>
            <button class="as-link active" data-section="overview" onclick="switchSection('overview', this)">
                <i data-lucide="layout-dashboard"></i> Overview
            </button>
            <button class="as-link" data-section="reports" onclick="switchSection('reports', this)">
                <i data-lucide="file-text"></i> Laporan
            </button>
            <button class="as-link" data-section="users" onclick="switchSection('users', this)">
                <i data-lucide="users"></i> Pengguna
            </button>
            <div style="flex:1;"></div>
            <div style="padding:12px;border-top:1px solid #E2E8F0;font-size:12px;color:#94A3B8;text-align:center;">
                <?= htmlspecialchars($adminName) ?>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">

            <!-- ===== SECTION: Overview ===== -->
            <div class="admin-section active" id="section-overview">
                <h1>Dashboard</h1>
                <p class="page-desc">Selamat datang di panel admin RoKenAI. Pantau dan kelola platform di sini.</p>

                <div class="stat-grid" id="statsGrid">
                    <div class="stat-card"><div class="sc-value">—</div><div class="sc-label">Total Laporan</div></div>
                    <div class="stat-card"><div class="sc-value">—</div><div class="sc-label">Dilaporkan</div></div>
                    <div class="stat-card"><div class="sc-value">—</div><div class="sc-label">Diverifikasi</div></div>
                    <div class="stat-card"><div class="sc-value">—</div><div class="sc-label">Diperbaiki</div></div>
                    <div class="stat-card"><div class="sc-value">—</div><div class="sc-label">Selesai</div></div>
                    <div class="stat-card"><div class="sc-value">—</div><div class="sc-label">Total Pengguna</div></div>
                    <div class="stat-card"><div class="sc-value">—</div><div class="sc-label">Aktif</div></div>
                    <div class="stat-card"><div class="sc-value" style="color:#F59E0B;">—</div><div class="sc-label">Pending Verifikasi</div></div>
                </div>
            </div>

            <!-- ===== SECTION: Reports ===== -->
            <div class="admin-section" id="section-reports">
                <h1>Laporan</h1>
                <p class="page-desc">Verifikasi dan kelola laporan kerusakan jalan dari pengguna.</p>

                <div class="table-wrap">
                    <div class="table-toolbar">
                        <div class="filter-group">
                            <select id="reportStatusFilter" onchange="loadReports()">
                                <option value="all">Semua Status</option>
                                <option value="dilaporkan">Dilaporkan</option>
                                <option value="diverifikasi">Diverifikasi</option>
                                <option value="diperbaiki">Diperbaiki</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <input type="text" id="reportSearch" placeholder="Cari laporan..." oninput="loadReports()">
                        </div>
                        <button class="abtn abtn-primary" onclick="loadReports()">🔄 Muat Ulang</button>
                    </div>
                    <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelapor</th>
                                    <th>Jenis</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="reportTableBody">
                                <tr><td colspan="7" style="text-align:center;color:#94A3B8;padding:40px;">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION: Users ===== -->
            <div class="admin-section" id="section-users">
                <h1>Pengguna</h1>
                <p class="page-desc">Verifikasi pendaftaran baru dan kelola akun pengguna.</p>

                <div class="table-wrap">
                    <div class="table-toolbar">
                        <div class="filter-group">
                            <select id="userStatusFilter" onchange="loadUsers()">
                                <option value="all">Semua</option>
                                <option value="pending">Pending</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <button class="abtn abtn-primary" onclick="loadUsers()">🔄 Muat Ulang</button>
                    </div>
                    <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Laporan</th>
                                    <th>Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <tr><td colspan="9" style="text-align:center;color:#94A3B8;padding:40px;">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- ===== Modal Detail Laporan ===== -->
    <div class="modal-overlay" id="reportModal">
        <div class="modal-card">
            <div class="modal-header">
                <h2>Detail Laporan</h2>
                <button class="modal-close" onclick="closeModal()"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-body" id="reportModalBody">
                <div style="text-align:center;color:#94A3B8;padding:20px;">Memuat...</div>
            </div>
        </div>
    </div>

    <!-- ===== Embedded Data dari Server ===== -->
    <script id="adminData" type="application/json"><?= json_encode([
        'stats' => $statsData,
        'reports' => $reportsData,
        'users' => $usersData,
        'csrf_token' => $_SESSION['csrf_token']
    ]) ?></script>

    <script>
        lucide.createIcons();

        /**
         * Baca data yang sudah di-render server (bypass session issue pada fetch)
         */
        var adminData = null;
        try {
            var el = document.getElementById('adminData');
            if (el) adminData = JSON.parse(el.textContent);
        } catch(e) { console.error('[Admin] Failed to parse embedded data:', e); }

        // ===== Tab / Section =====
        function switchSection(name, btn) {
            document.querySelectorAll('.admin-section').forEach(function(el) { el.classList.remove('active'); });
            document.querySelectorAll('.as-link').forEach(function(el) { el.classList.remove('active'); });
            document.getElementById('section-' + name).classList.add('active');
            if (btn) btn.classList.add('active');

            if (name === 'overview') loadStats();
            if (name === 'reports') loadReports();
            if (name === 'users') loadUsers();
        }

        // ===== Modal =====
        function openModal() { document.getElementById('reportModal').classList.add('active'); }
        function closeModal() { document.getElementById('reportModal').classList.remove('active'); }
        document.getElementById('reportModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ===== Load Stats (fetch API, untuk refresh & post-update) =====
        function loadStats() {
            // Init section sudah render data embedded, fungsi ini untuk refresh
            fetch('../controller/admin_handler.php?action=get_stats')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'success' && data.data) renderStats(data.data);
            })
            .catch(function(err) {
                console.error('[Admin] Stats fetch error:', err.message);
            });
        }

        function renderStats(s) {
            if (!s) return;
            var cards = document.querySelectorAll('#statsGrid .sc-value');
            if (cards.length >= 8) {
                cards[0].textContent = s.total_reports;
                cards[1].textContent = s.by_status.dilaporkan;
                cards[2].textContent = s.by_status.diverifikasi;
                cards[3].textContent = s.by_status.diperbaiki;
                cards[4].textContent = s.by_status.selesai;
                cards[5].textContent = s.total_users;
                cards[6].textContent = s.active_users;
                cards[7].textContent = s.pending_users;
                if (s.pending_users > 0) cards[7].style.color = '#F59E0B'; else cards[7].style.color = '#0F172A';
            }
        }

        // ===== Load Reports (fetch dari API, mendukung filter & search) =====
        function loadReports() {
            var status = document.getElementById('reportStatusFilter').value;
            var search = document.getElementById('reportSearch').value;
            var url = '../controller/admin_handler.php?action=get_reports&status=' + encodeURIComponent(status) + '&search=' + encodeURIComponent(search);

            fetch(url)
            .then(function(r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function(data) {
                if (data.status === 'success') renderReportTable(data.data || []);
            })
            .catch(function(err) {
                console.error('[Admin] Reports fetch:', err.message);
            });
        }

        function renderReportTable(reports) {
            var tbody = document.getElementById('reportTableBody');
            if (!reports || reports.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#94A3B8;padding:40px;">Tidak ada laporan.</td></tr>';
                return;
            }
            var html = '';
            reports.forEach(function(r) {
                var loc = r.address ? r.address.substring(0, 30) + (r.address.length > 30 ? '...' : '') : '—';
                html += '<tr>' +
                    '<td><span class="cell-mono">#' + r.report_id + '</span></td>' +
                    '<td>' + esc(r.username || '—') + '</td>' +
                    '<td>' + esc(r.damage_label) + '</td>' +
                    '<td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + esc(loc) + '</td>' +
                    '<td><span class="status-badge ' + r.badge_class + '"><span class="s-dot"></span> ' + esc(r.status_label) + '</span></td>' +
                    '<td class="cell-small">' + esc(r.created_ago) + '</td>' +
                    '<td><div class="action-btns">' +
                        '<button class="abtn abtn-primary" onclick="viewReport(' + r.id + ')">Detail</button>' +
                        '<button class="abtn abtn-success" onclick="updateStatus(' + r.id + ',\'selesai\')">Selesai</button>' +
                    '</div></td>' +
                '</tr>';
            });
            tbody.innerHTML = html;
            lucide.createIcons();
        }

        // ===== View Report Detail =====
        function viewReport(id) {
            fetch('../controller/admin_handler.php?action=get_report_detail&id=' + id)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status !== 'success') {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } });
                    return;
                }
                var r = data.data;
                var imgHtml = r.image_path ? '<img class="detail-img" src="../' + r.image_path + '" alt="Foto">' : '';
                var statusActions = '';
                var statuses = ['dilaporkan', 'diverifikasi', 'diperbaiki', 'selesai'];
                statuses.forEach(function(s) {
                    var active = s === r.status;
                    statusActions += '<button class="abtn ' + (active ? 'abtn-success' : 'abtn-ghost') + '" onclick="updateStatus(' + r.id + ',\'' + s + '\');closeModal();" ' + (active ? 'disabled style="opacity:0.5;cursor:default;"' : '') + '>' + statusLabelText(s) + '</button> ';
                });

                document.getElementById('reportModalBody').innerHTML =
                    imgHtml +
                    '<div class="detail-row"><span class="detail-label">ID Laporan</span><span class="detail-value cell-mono">#' + esc(r.report_id) + '</span></div>' +
                    '<div class="detail-row"><span class="detail-label">Pelapor</span><span class="detail-value">' + esc(r.full_name || r.username || '—') + '</span></div>' +
                    '<div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">' + esc(r.email || '—') + '</span></div>' +
                    '<div class="detail-row"><span class="detail-label">Jenis</span><span class="detail-value">' + esc(r.damage_label) + '</span></div>' +
                    '<div class="detail-row"><span class="detail-label">Tingkat</span><span class="detail-value">' + esc(r.damage_severity || '—') + '</span></div>' +
                    '<div class="detail-row"><span class="detail-label">Confidence</span><span class="detail-value">' + (r.detection_confidence || '—') + '%</span></div>' +
                    '<div class="detail-row"><span class="detail-label">Lokasi</span><span class="detail-value">' + esc(r.address || '—') + '</span></div>' +
                    (r.latitude ? '<div class="detail-row"><span class="detail-label">Koordinat</span><span class="detail-value cell-mono">' + r.latitude + ', ' + r.longitude + '</span></div>' : '') +
                    '<div class="detail-row"><span class="detail-label">Deskripsi</span><span class="detail-value">' + esc(r.description || '—') + '</span></div>' +
                    '<div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><span class="status-badge ' + r.badge_class + '"><span class="s-dot"></span> ' + esc(r.status_label) + '</span></span></div>' +
                    '<div class="detail-row"><span class="detail-label">Dibuat</span><span class="detail-value">' + esc(r.created_ago) + '</span></div>' +
                    '<div style="margin-top:16px;padding-top:16px;border-top:1px solid #E2E8F0;">' +
                        '<label style="font-size:12px;font-weight:600;color:#64748B;display:block;margin-bottom:8px;">Ubah Status:</label>' +
                        '<div class="action-btbs" style="display:flex;gap:4px;flex-wrap:wrap;">' + statusActions + '</div>' +
                    '</div>';
                openModal();
                lucide.createIcons();
            });
        }

        // ===== Update Report Status =====
        function updateStatus(id, status) {
            var formData = new FormData();
            formData.append('action', 'update_report_status');
            formData.append('id', id);
            formData.append('status', status);
            formData.append('csrf_token', adminData ? adminData.csrf_token : '');

            fetch('../controller/admin_handler.php', { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[16px]' } });
                    loadReports();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } });
                }
            });
        }

        // ===== Load Users (fetch dari API, mendukung filter) =====
        function loadUsers() {
            var status = document.getElementById('userStatusFilter').value;
            fetch('../controller/admin_handler.php?action=get_users&status=' + encodeURIComponent(status))
            .then(function(r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function(data) {
                if (data.status === 'success') renderUserTable(data.data || []);
            })
            .catch(function(err) {
                console.error('[Admin] Users fetch:', err.message);
            });
        }

        function renderUserTable(users) {
            var tbody = document.getElementById('userTableBody');
            if (!users || users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#94A3B8;padding:40px;">Tidak ada pengguna.</td></tr>';
                return;
            }
            var html = '';
            users.forEach(function(u) {
                var roleBtn = u.role === 'admin'
                    ? '<button class="abtn abtn-warning" onclick="changeRole(' + u.id + ',\'user\')">⬇ Jadikan User</button>'
                    : '<button class="abtn abtn-primary" onclick="changeRole(' + u.id + ',\'admin\')">⬆ Jadikan Admin</button>';
                var statusBtn = '';
                var approveBtn = '';
                if (u.is_active == 2) {
                    approveBtn = '<button class="abtn abtn-success" onclick="approveUser(' + u.id + ')">✓ Setujui</button>';
                } else if (u.is_active == 1) {
                    statusBtn = '<button class="abtn abtn-warning" onclick="toggleUser(' + u.id + ')">⛔ Nonaktifkan</button>';
                } else {
                    statusBtn = '<button class="abtn abtn-success" onclick="toggleUser(' + u.id + ')">✓ Aktifkan</button>';
                }
                html += '<tr>' +
                    '<td>' + u.id + '</td>' +
                    '<td><strong>' + esc(u.username) + '</strong></td>' +
                    '<td>' + esc(u.full_name || '—') + '</td>' +
                    '<td class="cell-small">' + esc(u.email) + '</td>' +
                    '<td><span class="status-badge ' + (u.role === 'admin' ? 'selesai' : 'dilaporkan') + '" style="font-size:10px;padding:2px 8px;">' + u.role + '</span></td>' +
                    '<td><span class="status-badge ' + u.badge_class + '"><span class="s-dot"></span> ' + u.status_label + '</span></td>' +
                    '<td>' + u.report_count + '</td>' +
                    '<td class="cell-small">' + (u.created_ago || '—') + '</td>' +
                    '<td><div class="action-btns">' + approveBtn + statusBtn + roleBtn + '</div></td>' +
                '</tr>';
            });
            tbody.innerHTML = html;
            lucide.createIcons();
        }

        // ===== User Actions =====
        function approveUser(id) {
            var formData = new FormData();
            formData.append('action', 'approve_user');
            formData.append('id', id);
            formData.append('csrf_token', adminData ? adminData.csrf_token : '');
            fetch('../controller/admin_handler.php', { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Pengguna Diverifikasi!', text: data.message, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[16px]' } });
                    loadUsers(); loadStats();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } });
                }
            });
        }

        function toggleUser(id) {
            var formData = new FormData();
            formData.append('action', 'toggle_user_status');
            formData.append('id', id);
            formData.append('csrf_token', adminData ? adminData.csrf_token : '');
            fetch('../controller/admin_handler.php', { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Status Diubah!', text: data.message, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[16px]' } });
                    loadUsers(); loadStats();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } });
                }
            });
        }

        function changeRole(id, role) {
            var formData = new FormData();
            formData.append('action', 'update_user_role');
            formData.append('id', id);
            formData.append('role', role);
            formData.append('csrf_token', adminData ? adminData.csrf_token : '');
            fetch('../controller/admin_handler.php', { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Role Diubah!', text: data.message, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[16px]' } });
                    loadUsers();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#DC2626', customClass: { popup: 'rounded-[16px]' } });
                }
            });
        }

        // ===== Helpers =====
        function esc(s) { if (!s) return ''; var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
        function statusLabelText(s) {
            var m = { 'dilaporkan': 'Dilaporkan', 'diverifikasi': 'Diverifikasi', 'diperbaiki': 'Diperbaiki', 'selesai': 'Selesai' };
            return m[s] || s;
        }

        // ===== Mobile Sidebar Toggle =====
        function toggleSidebar() {
            var sidebar = document.getElementById('adminSidebar');
            var overlay = document.getElementById('adminOverlay');
            var hamburger = document.getElementById('adminHamburger');
            if (!sidebar || !overlay || !hamburger) return;
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
            hamburger.classList.toggle('active');
            document.body.classList.toggle('sidebar-open', sidebar.classList.contains('open'));
        }

        // Tutup sidebar dengan Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var sidebar = document.getElementById('adminSidebar');
                if (sidebar && sidebar.classList.contains('open')) toggleSidebar();
            }
        });

        // Tutup sidebar saat link diklik (mobile)
        document.querySelectorAll('.as-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    var sidebar = document.getElementById('adminSidebar');
                    if (sidebar && sidebar.classList.contains('open')) toggleSidebar();
                }
            });
        });

        // ===== Init: render data embedded dari server (tanpa fetch) =====
        if (adminData) {
            if (adminData.stats) renderStats(adminData.stats);
            if (adminData.reports) renderReportTable(adminData.reports);
            if (adminData.users) renderUserTable(adminData.users);
        }
    </script>
</body>
</html>
