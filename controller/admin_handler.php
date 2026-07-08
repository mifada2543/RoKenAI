<?php
/**
 * RoKenAI Admin Handler
 * API untuk dashboard admin: kelola laporan & pengguna
 */

session_name('RoKenAI');
session_start();

require_once __DIR__ . '/../auth/config.php';
require_once __DIR__ . '/report.php';

header('Content-Type: application/json');

// Pastikan admin yang login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']); exit;
}

// Verifikasi CSRF untuk state-changing actions (POST)
$stateChanging = ['update_report_status', 'approve_user', 'toggle_user_status', 'update_user_role'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_check = $_POST['action'] ?? '';
    if (in_array($action_check, $stateChanging) && !verify_csrf()) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid security token. Silakan refresh halaman.']); exit;
    }
}

$action = $_REQUEST['action'] ?? '';

switch ($action) {

    // ================================================================
    // DASHBOARD STATS
    // ================================================================
    case 'get_stats':
        $stats = [];

        // Total reports
        $r = $conn->query("SELECT COUNT(*) as v FROM reports"); $stats['total_reports'] = $r->fetch_assoc()['v'];

        // Reports by status
        $r = $conn->query("SELECT status, COUNT(*) as v FROM reports GROUP BY status");
        $byStatus = ['dilaporkan' => 0, 'diverifikasi' => 0, 'diperbaiki' => 0, 'selesai' => 0];
        while ($row = $r->fetch_assoc()) $byStatus[$row['status']] = (int)$row['v'];
        $stats['by_status'] = $byStatus;

        // Total users
        $r = $conn->query("SELECT COUNT(*) as v FROM users"); $stats['total_users'] = $r->fetch_assoc()['v'];

        // Pending users (is_active = 2)
        $r = $conn->query("SELECT COUNT(*) as v FROM users WHERE is_active = 2"); $stats['pending_users'] = $r->fetch_assoc()['v'];

        // Active users (is_active = 1)
        $r = $conn->query("SELECT COUNT(*) as v FROM users WHERE is_active = 1"); $stats['active_users'] = $r->fetch_assoc()['v'];

        echo json_encode(['status' => 'success', 'data' => $stats]);
        break;

    // ================================================================
    // GET ALL REPORTS
    // ================================================================
    case 'get_reports':
        $statusFilter = $_GET['status'] ?? '';
        $search       = $_GET['search'] ?? '';

        $sql = "SELECT r.*, u.username, u.full_name 
                FROM reports r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE 1=1";
        $params = [];
        $types  = '';

        if ($statusFilter && $statusFilter !== 'all') {
            $sql .= " AND r.status = ?";
            $params[] = $statusFilter;
            $types .= 's';
        }
        if ($search) {
            $sql .= " AND (r.report_id LIKE ? OR r.address LIKE ? OR u.username LIKE ?)";
            $like = '%' . $search . '%';
            $params[] = $like; $params[] = $like; $params[] = $like;
            $types .= 'sss';
        }

        $sql .= " ORDER BY r.created_at DESC";

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $reports = $result->fetch_all(MYSQLI_ASSOC);

        // Format data
        foreach ($reports as &$rep) {
            $rep['created_ago'] = timeAgo($rep['created_at']);
            $rep['damage_label'] = damageTypeLabel($rep['damage_type']);
            $rep['status_label'] = statusLabel($rep['status']);
            $rep['badge_class']  = getStatusBadgeClass($rep['status']);
        }

        echo json_encode(['status' => 'success', 'data' => $reports]);
        break;

    // ================================================================
    // GET SINGLE REPORT DETAIL
    // ================================================================
    case 'get_report_detail':
        $reportId = (int)($_GET['id'] ?? 0);
        if (!$reportId) { echo json_encode(['status' => 'error', 'message' => 'ID laporan tidak valid.']); exit; }

        $stmt = $conn->prepare("SELECT r.*, u.username, u.full_name, u.email 
                                FROM reports r LEFT JOIN users u ON r.user_id = u.id 
                                WHERE r.id = ?");
        $stmt->bind_param("i", $reportId);
        $stmt->execute();
        $rep = $stmt->get_result()->fetch_assoc();

        if (!$rep) { echo json_encode(['status' => 'error', 'message' => 'Laporan tidak ditemukan.']); exit; }

        $rep['damage_label'] = damageTypeLabel($rep['damage_type']);
        $rep['status_label'] = statusLabel($rep['status']);
        $rep['created_ago']  = timeAgo($rep['created_at']);

        echo json_encode(['status' => 'success', 'data' => $rep]);
        break;

    // ================================================================
    // UPDATE REPORT STATUS
    // ================================================================
    case 'update_report_status':
        $reportId = (int)($_POST['id'] ?? 0);
        $newStatus = $_POST['status'] ?? '';

        $allowed = ['dilaporkan', 'diverifikasi', 'diperbaiki', 'selesai'];
        if (!in_array($newStatus, $allowed)) {
            echo json_encode(['status' => 'error', 'message' => 'Status tidak valid.']); exit;
        }

        $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $reportId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Status laporan diperbarui menjadi ' . statusLabel($newStatus) . '.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status.']);
        }
        break;

    // ================================================================
    // GET ALL USERS
    // ================================================================
    case 'get_users':
        $statusFilter = $_GET['status'] ?? '';

        $sql = "SELECT id, username, full_name, email, phone, address, role, is_active, created_at FROM users WHERE 1=1";
        $params = [];
        $types  = '';

        if ($statusFilter && $statusFilter !== 'all') {
            if ($statusFilter === 'pending') {
                $sql .= " AND is_active = 2";
            } elseif ($statusFilter === 'active') {
                $sql .= " AND is_active = 1";
            } elseif ($statusFilter === 'inactive') {
                $sql .= " AND is_active = 0";
            }
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($users as &$u) {
            $u['created_ago'] = timeAgo($u['created_at']);
            switch ($u['is_active']) {
                case 0: $u['status_label'] = 'Nonaktif'; $u['badge_class'] = 'inactive'; break;
                case 1: $u['status_label'] = 'Aktif';    $u['badge_class'] = 'active'; break;
                case 2: $u['status_label'] = 'Pending';  $u['badge_class'] = 'pending'; break;
            }
            $u['report_count'] = 0;
            $rc = $conn->query("SELECT COUNT(*) as v FROM reports WHERE user_id = " . (int)$u['id']);
            if ($rc) $u['report_count'] = (int)$rc->fetch_assoc()['v'];
        }

        echo json_encode(['status' => 'success', 'data' => $users]);
        break;

    // ================================================================
    // APPROVE PENDING USER (is_active 2 → 1)
    // ================================================================
    case 'approve_user':
        $userId = (int)($_POST['id'] ?? 0);
        if (!$userId) { echo json_encode(['status' => 'error', 'message' => 'ID pengguna tidak valid.']); exit; }

        $stmt = $conn->prepare("UPDATE users SET is_active = 1 WHERE id = ? AND is_active = 2");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Pengguna berhasil diverifikasi!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Pengguna tidak dalam status pending.']);
        }
        break;

    // ================================================================
    // TOGGLE USER ACTIVE STATUS (0/1)
    // ================================================================
    case 'toggle_user_status':
        $userId = (int)($_POST['id'] ?? 0);
        if (!$userId) { echo json_encode(['status' => 'error', 'message' => 'ID pengguna tidak valid.']); exit; }

        // Cari status sekarang
        $stmt = $conn->prepare("SELECT is_active FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();
        if (!$u) { echo json_encode(['status' => 'error', 'message' => 'Pengguna tidak ditemukan.']); exit; }

        $newActive = ($u['is_active'] == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        $stmt->bind_param("ii", $newActive, $userId);

        if ($stmt->execute()) {
            $label = $newActive ? 'diaktifkan' : 'dinonaktifkan';
            echo json_encode(['status' => 'success', 'message' => 'Pengguna berhasil ' . $label . '!', 'new_status' => $newActive]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status.']);
        }
        break;

    // ================================================================
    // UPDATE USER ROLE
    // ================================================================
    case 'update_user_role':
        $userId = (int)($_POST['id'] ?? 0);
        $newRole = $_POST['role'] ?? '';

        if (!in_array($newRole, ['user', 'admin'])) {
            echo json_encode(['status' => 'error', 'message' => 'Role tidak valid.']); exit;
        }

        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $userId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Role pengguna diubah menjadi ' . ucfirst($newRole) . '.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah role.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
        break;
}
