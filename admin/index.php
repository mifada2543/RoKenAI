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

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="../assets/css/tailwind.css">
    <!-- Lucide Icons -->
    <script src="../assets/js/lucide.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <!-- Tailwind JS config -->
    <script src="../assets/js/tailwind.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:    { DEFAULT: '#1D4ED8', light: '#DBEAFE', hover: '#3B82F6' },
                        ink:        { 900: '#0F172A', 600: '#475569' },
                        surface:    { DEFAULT: '#FFFFFF', muted: '#F8FAFC' },
                        line:       { 200: '#E2E8F0' },
                        marka:      { 400: '#FACC15' },
                        danger:     '#DC2626',
                        warning:    '#F59E0B',
                        progress:   '#2563EB',
                        success:    '#16A34A',
                    },
                    fontFamily: {
                        heading: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        body:    ['Inter', '"Plus Jakarta Sans"', 'sans-serif'],
                        mono:    ['"IBM Plex Mono"', 'monospace'],
                    },
                    borderRadius: {
                        sm:   '8px',  md:   '10px',
                        lg:   '12px', xl:   '16px',
                        '2xl':'20px',
                    },
                    boxShadow: {
                        card: '0 1px 3px rgba(15,23,42,0.08)',
                        md:   '0 1px 3px rgba(15,23,42,0.08), 0 1px 2px rgba(15,23,42,0.04)',
                        lg:   '0 4px 12px rgba(15,23,42,0.08), 0 2px 4px rgba(15,23,42,0.04)',
                        glow: '0 0 0 1px rgba(29,78,216,0.08), 0 4px 12px rgba(29,78,216,0.06)',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(12px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.3s ease',
                    },
                }
            }
        }
    </script>

    <style>
        /* ===== CSS minimal — hanya yang tidak bisa Tailwind ===== */
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; -webkit-font-smoothing: antialiased; }
        body { min-height:100vh; overflow-x:hidden; }
        ::selection { background:rgba(29,78,216,0.15); color:#0F172A; }
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:#CBD5E1; border-radius:100px; }
        ::-webkit-scrollbar-thumb:hover { background:#94A3B8; }

        /* Hamburger animation */
        .admin-section { display: none; }
        .admin-section.active { display: block; }
        .ah-line { transition: all 0.3s ease; }
        .hamburger-active .ah-line:nth-child(1) { transform: translateY(6px) rotate(45deg); }
        .hamburger-active .ah-line:nth-child(2) { opacity: 0; }
        .hamburger-active .ah-line:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }
        .hamburger-active .ah-line { background: #1D4ED8; }

        /* Status badges (reuse pattern dari link.php) */
        .status-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:9999px; font-size:12px; font-weight:600; }
        .status-badge .s-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .status-badge.dilaporkan  { background:rgba(59,130,246,0.1); color:#2563EB; }
        .status-badge.dilaporkan .s-dot { background:#2563EB; }
        .status-badge.diverifikasi{ background:rgba(245,158,11,0.1); color:#F59E0B; }
        .status-badge.diverifikasi .s-dot { background:#F59E0B; }
        .status-badge.diperbaiki  { background:rgba(37,99,235,0.1); color:#2563EB; }
        .status-badge.diperbaiki .s-dot  { background:#2563EB; }
        .status-badge.selesai     { background:rgba(22,163,74,0.1); color:#16A34A; }
        .status-badge.selesai .s-dot     { background:#16A34A; }
        .status-badge.active { background:rgba(22,163,74,0.1); color:#16A34A; }
        .status-badge.active .s-dot { background:#16A34A; }
        .status-badge.inactive { background:rgba(100,116,139,0.1); color:#64748B; }
        .status-badge.inactive .s-dot { background:#64748B; }
        .status-badge.pending { background:rgba(245,158,11,0.1); color:#F59E0B; }
        .status-badge.pending .s-dot { background:#F59E0B; }

        /* Mobile sidebar transition */
        .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        .admin-sidebar.open { transform: translateX(0); }

        /* Action buttons */
        .abtn { padding:6px 12px; border-radius:6px; border:none; font-size:11px; font-weight:600; cursor:pointer; transition:all .15s ease; display:inline-flex; align-items:center; gap:4px; }
        .abtn-primary { background:#DBEAFE; color:#1D4ED8; }
        .abtn-primary:hover { background:rgba(29,78,216,0.15); }
        .abtn-success { background:rgba(22,163,74,0.1); color:#16A34A; }
        .abtn-success:hover { background:rgba(22,163,74,0.18); }
        .abtn-warning { background:rgba(245,158,11,0.1); color:#D97706; }
        .abtn-warning:hover { background:rgba(245,158,11,0.18); }
        .abtn-danger { background:rgba(220,38,38,0.1); color:#DC2626; }
        .abtn-danger:hover { background:rgba(220,38,38,0.18); }
        .abtn-ghost { background:#F1F5F9; color:#64748B; }
        .abtn-ghost:hover { background:#E2E8F0; }
        .abtn:disabled { opacity:0.5; cursor:default; }

        @media (min-width: 769px) {
            .admin-sidebar { transform: none; }
            .admin-overlay { display: none !important; }
        }
        @media (max-width: 768px) {
            body.sidebar-open { overflow: hidden; }
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-gray-600 font-body">

    <!-- Mobile Top Bar -->
    <div class="fixed top-0 left-0 right-0 z-[120] h-14 bg-white border-b border-gray-200 flex items-center px-4 gap-3 md:hidden">
        <button class="w-9 h-9 min-w-[36px] rounded-lg border border-gray-200 bg-gray-50 cursor-pointer flex flex-col items-center justify-center gap-1 hover:border-blue-500 hover:bg-blue-50 transition-all" id="adminHamburger" onclick="toggleSidebar()" aria-label="Toggle menu">
            <span class="ah-line block w-4 h-0.5 bg-gray-600 rounded"></span>
            <span class="ah-line block w-4 h-0.5 bg-gray-600 rounded"></span>
            <span class="ah-line block w-4 h-0.5 bg-gray-600 rounded"></span>
        </button>
        <div class="flex-1 text-sm font-semibold text-gray-900 text-center font-heading">Admin Panel</div>
        <a href="../index.php" class="flex items-center gap-2 no-underline">
            <img src="../assets/Logo.png" alt="RoKenAI" class="h-[26px] w-auto">
        </a>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="fixed inset-0 z-[130] bg-[rgba(15,23,42,0.3)] backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 admin-overlay" id="adminOverlay" onclick="toggleSidebar()"></div>

    <div class="flex min-h-screen pt-14 md:pt-0">
        <!-- Sidebar -->
        <aside class="admin-sidebar fixed md:sticky top-0 left-0 z-[140] md:z-auto w-[220px] min-w-[220px] h-screen md:h-auto bg-white border-r border-gray-200 p-5 pt-16 md:pt-5 flex flex-col gap-1" id="adminSidebar">
            <a href="../index.php" class="hidden md:flex items-center gap-2.5 pb-4 mb-3 border-b border-gray-200 no-underline">
                <img src="../assets/Logo.png" alt="RoKenAI" class="h-7 w-auto">
                <span class="text-lg font-extrabold tracking-tight text-gray-900 font-heading">RoKen<span style="color:#1D4ED8;">AI</span></span>
            </a>
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider px-3 pt-2 pb-1 font-heading">Navigasi</div>
            <button class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 no-underline cursor-pointer border-none bg-transparent w-full text-left font-body hover:bg-gray-50 hover:text-blue-700 transition-all data-[active=true]:bg-blue-50 data-[active=true]:text-blue-700 data-[active=true]:font-semibold" data-section="overview" data-active="true" onclick="switchSection('overview', this)">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i> Overview
            </button>
            <button class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 no-underline cursor-pointer border-none bg-transparent w-full text-left font-body hover:bg-gray-50 hover:text-blue-700 transition-all" data-section="reports" onclick="switchSection('reports', this)">
                <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i> Laporan
            </button>
            <button class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 no-underline cursor-pointer border-none bg-transparent w-full text-left font-body hover:bg-gray-50 hover:text-blue-700 transition-all" data-section="users" onclick="switchSection('users', this)">
                <i data-lucide="users" class="w-4 h-4 shrink-0"></i> Pengguna
            </button>
            <div class="flex-1"></div>
            <div class="px-3 pt-3 border-t border-gray-200 text-xs text-gray-400 text-center">
                <?= htmlspecialchars($adminName) ?>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto">

            <!-- ===== SECTION: Overview ===== -->
            <div class="hidden admin-section active" id="section-overview">
                <h1 class="font-heading text-[22px] font-bold text-gray-900 mb-1">Dashboard</h1>
                <p class="text-sm text-gray-600 mb-6">Selamat datang di panel admin RoKenAI. Pantau dan kelola platform di sini.</p>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-6" id="statsGrid">
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-gray-900" id="stat-total">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="total">Total Laporan</div>
                    </div>
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-gray-900" id="stat-dilaporkan">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="dilaporkan">Dilaporkan</div>
                    </div>
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-gray-900" id="stat-diverifikasi">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="diverifikasi">Diverifikasi</div>
                    </div>
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-gray-900" id="stat-diperbaiki">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="diperbaiki">Diperbaiki</div>
                    </div>
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-gray-900" id="stat-selesai">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="selesai">Selesai</div>
                    </div>
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-gray-900" id="stat-users">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="users">Total Pengguna</div>
                    </div>
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-gray-900" id="stat-active">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="active">Aktif</div>
                    </div>
                    <div class="bg-white rounded-[10px] border border-gray-200 p-4 shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                        <div class="font-heading text-2xl font-bold text-amber-500" id="stat-pending">—</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-medium" data-label="pending">Pending Verifikasi</div>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION: Reports ===== -->
            <div class="hidden admin-section" id="section-reports">
                <h1 class="font-heading text-[22px] font-bold text-gray-900 mb-1">Laporan</h1>
                <p class="text-sm text-gray-600 mb-6">Verifikasi dan kelola laporan kerusakan jalan dari pengguna.</p>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-200 flex-wrap gap-2">
                        <div class="flex gap-2 items-center">
                            <select id="reportStatusFilter" onchange="loadReports()" class="px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-900 bg-white outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10 transition-all">
                                <option value="all">Semua Status</option>
                                <option value="dilaporkan">Dilaporkan</option>
                                <option value="diverifikasi">Diverifikasi</option>
                                <option value="diperbaiki">Diperbaiki</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <input type="text" id="reportSearch" placeholder="Cari laporan..." oninput="loadReports()" class="px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-900 bg-white outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10 transition-all">
                        </div>
                        <button class="abtn abtn-primary" onclick="loadReports()">🔄 Muat Ulang</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">ID</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Pelapor</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Jenis</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Lokasi</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Status</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Tanggal</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="reportTableBody">
                                <tr><td colspan="7" class="text-center text-gray-400 py-10">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION: Users ===== -->
            <div class="hidden admin-section" id="section-users">
                <h1 class="font-heading text-[22px] font-bold text-gray-900 mb-1">Pengguna</h1>
                <p class="text-sm text-gray-600 mb-6">Verifikasi pendaftaran baru dan kelola akun pengguna.</p>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-[0_1px_3px_rgba(15,23,42,0.04)]">
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-200 flex-wrap gap-2">
                        <div class="flex gap-2 items-center">
                            <select id="userStatusFilter" onchange="loadUsers()" class="px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-900 bg-white outline-none focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10 transition-all">
                                <option value="all">Semua</option>
                                <option value="pending">Pending</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <button class="abtn abtn-primary" onclick="loadUsers()">🔄 Muat Ulang</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">ID</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Username</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Nama</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Email</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Role</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Status</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Laporan</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Daftar</th>
                                    <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <tr><td colspan="9" class="text-center text-gray-400 py-10">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- ===== Modal Detail Laporan ===== -->
    <div class="fixed inset-0 z-[9999] bg-[rgba(15,23,42,0.4)] backdrop-blur-sm hidden items-center justify-center" id="reportModal">
        <div class="bg-white rounded-2xl max-w-[560px] w-[90%] max-h-[85vh] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.15)] animate-fade-in-up">
            <div class="flex items-center justify-between px-6 py-4.5 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h2 class="font-heading text-base font-semibold text-gray-900">Detail Laporan</h2>
                <button class="w-8 h-8 rounded-lg border-none bg-gray-100 text-gray-500 cursor-pointer flex items-center justify-center hover:bg-gray-200 transition-all" onclick="closeModal()"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
            <div class="p-6" id="reportModalBody">
                <div class="text-center text-gray-400 py-5">Memuat...</div>
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

        var adminData = null;
        try {
            var el = document.getElementById('adminData');
            if (el) adminData = JSON.parse(el.textContent);
        } catch(e) { console.error('[Admin] Failed to parse embedded data:', e); }

        // ===== Tab / Section =====
        function switchSection(name, btn) {
            document.querySelectorAll('.admin-section').forEach(function(el) { el.classList.remove('active'); });
            document.querySelectorAll('.as-link-sidebar, [data-section]').forEach(function(el) {
                el.removeAttribute('data-active');
            });
            var section = document.getElementById('section-' + name);
            if (section) { section.classList.add('active'); }
            if (btn) btn.setAttribute('data-active', 'true');

            if (name === 'overview') loadStats();
            if (name === 'reports') loadReports();
            if (name === 'users') loadUsers();
        }

        // ===== Modal =====
        function openModal() { document.getElementById('reportModal').classList.add('flex'); document.getElementById('reportModal').classList.remove('hidden'); }
        function closeModal() { document.getElementById('reportModal').classList.remove('flex'); document.getElementById('reportModal').classList.add('hidden'); }
        document.getElementById('reportModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ===== Load Stats =====
        function loadStats() {
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
            var el = function(id) { return document.getElementById('stat-' + id); };
            if (el('total')) el('total').textContent = s.total_reports;
            if (el('dilaporkan')) el('dilaporkan').textContent = s.by_status.dilaporkan;
            if (el('diverifikasi')) el('diverifikasi').textContent = s.by_status.diverifikasi;
            if (el('diperbaiki')) el('diperbaiki').textContent = s.by_status.diperbaiki;
            if (el('selesai')) el('selesai').textContent = s.by_status.selesai;
            if (el('users')) el('users').textContent = s.total_users;
            if (el('active')) el('active').textContent = s.active_users;
            if (el('pending')) {
                el('pending').textContent = s.pending_users;
                el('pending').style.color = s.pending_users > 0 ? '#F59E0B' : '#0F172A';
            }
        }

        // ===== Load Reports =====
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
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-400 py-10">Tidak ada laporan.</td></tr>';
                return;
            }
            var html = '';
            reports.forEach(function(r) {
                var loc = r.address ? r.address.substring(0, 30) + (r.address.length > 30 ? '...' : '') : '—';
                html += '<tr>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><span class="font-mono text-xs text-gray-500">#' + r.report_id + '</span></td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100">' + esc(r.username || '—') + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100">' + esc(r.damage_label) + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100 max-w-[160px] overflow-hidden text-ellipsis whitespace-nowrap">' + esc(loc) + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><span class="status-badge ' + r.badge_class + '"><span class="s-dot"></span> ' + esc(r.status_label) + '</span></td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><span class="text-xs text-gray-400">' + esc(r.created_ago) + '</span></td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><div class="flex gap-1 flex-wrap">' +
                        '<button class="abtn abtn-primary" onclick="viewReport(' + r.id + ')">Detail</button>' +
                        '<button class="abtn abtn-success" onclick="updateStatus(' + r.id + ',\\'selesai\\')">Selesai</button>' +
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
                var imgHtml = r.image_path ? '<img class="w-full rounded-lg mb-2 max-h-[300px] object-cover" src="../' + r.image_path + '" alt="Foto">' : '';
                var statusActions = '';
                var statuses = ['dilaporkan', 'diverifikasi', 'diperbaiki', 'selesai'];
                statuses.forEach(function(s) {
                    var active = s === r.status;
                    statusActions += '<button class="abtn ' + (active ? 'abtn-success' : 'abtn-ghost') + '" onclick="updateStatus(' + r.id + ',\\'' + s + '\\');closeModal();" ' + (active ? 'disabled' : '') + '>' + statusLabelText(s) + '</button> ';
                });

                document.getElementById('reportModalBody').innerHTML =
                    imgHtml +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">ID Laporan</span><span class="flex-1 text-[13px] text-gray-900 font-mono text-xs">#' + esc(r.report_id) + '</span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Pelapor</span><span class="flex-1 text-[13px] text-gray-900">' + esc(r.full_name || r.username || '—') + '</span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Email</span><span class="flex-1 text-[13px] text-gray-900">' + esc(r.email || '—') + '</span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Jenis</span><span class="flex-1 text-[13px] text-gray-900">' + esc(r.damage_label) + '</span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Tingkat</span><span class="flex-1 text-[13px] text-gray-900">' + esc(r.damage_severity || '—') + '</span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Confidence</span><span class="flex-1 text-[13px] text-gray-900">' + (r.detection_confidence || '—') + '%</span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Lokasi</span><span class="flex-1 text-[13px] text-gray-900">' + esc(r.address || '—') + '</span></div>' +
                    (r.latitude ? '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Koordinat</span><span class="flex-1 text-[13px] text-gray-900 font-mono text-xs">' + r.latitude + ', ' + r.longitude + '</span></div>' : '') +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Deskripsi</span><span class="flex-1 text-[13px] text-gray-900">' + esc(r.description || '—') + '</span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Status</span><span class="flex-1 text-[13px] text-gray-900"><span class="status-badge ' + r.badge_class + '"><span class="s-dot"></span> ' + esc(r.status_label) + '</span></span></div>' +
                    '<div class="flex py-2 border-b border-gray-100"><span class="w-[120px] text-xs font-semibold text-gray-400 shrink-0">Dibuat</span><span class="flex-1 text-[13px] text-gray-900">' + esc(r.created_ago) + '</span></div>' +
                    '<div class="mt-4 pt-4 border-t border-gray-200">' +
                        '<label class="text-xs font-semibold text-gray-500 block mb-2">Ubah Status:</label>' +
                        '<div class="flex gap-1 flex-wrap">' + statusActions + '</div>' +
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

        // ===== Load Users =====
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
                tbody.innerHTML = '<tr><td colspan="9" class="text-center text-gray-400 py-10">Tidak ada pengguna.</td></tr>';
                return;
            }
            var html = '';
            users.forEach(function(u) {
                var roleBtn = u.role === 'admin'
                    ? '<button class="abtn abtn-warning" onclick="changeRole(' + u.id + ',\\'user\\')">⬇ Jadikan User</button>'
                    : '<button class="abtn abtn-primary" onclick="changeRole(' + u.id + ',\\'admin\\')">⬆ Jadikan Admin</button>';
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
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100">' + u.id + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><strong>' + esc(u.username) + '</strong></td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100">' + esc(u.full_name || '—') + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100 text-xs text-gray-400">' + esc(u.email) + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><span class="status-badge ' + (u.role === 'admin' ? 'selesai' : 'dilaporkan') + '" style="font-size:10px;padding:2px 8px;">' + u.role + '</span></td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><span class="status-badge ' + u.badge_class + '"><span class="s-dot"></span> ' + u.status_label + '</span></td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100">' + u.report_count + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100 text-xs text-gray-400">' + (u.created_ago || '—') + '</td>' +
                    '<td class="px-4 py-2.5 text-[13px] text-gray-900 border-t border-gray-100"><div class="flex gap-1 flex-wrap">' + approveBtn + statusBtn + roleBtn + '</div></td>' +
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
            overlay.classList.toggle('hidden');
            hamburger.classList.toggle('hamburger-active');
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
        document.querySelectorAll('[data-section]').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    var sidebar = document.getElementById('adminSidebar');
                    if (sidebar && sidebar.classList.contains('open')) toggleSidebar();
                }
            });
        });

        // ===== Init: render data embedded =====
        if (adminData) {
            if (adminData.stats) renderStats(adminData.stats);
            if (adminData.reports) renderReportTable(adminData.reports);
            if (adminData.users) renderUserTable(adminData.users);
        }
    </script>
</body>
</html>
