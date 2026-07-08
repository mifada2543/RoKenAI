<?php
/**
 * RoKenAI Report Controller
 * Helper functions for creating, reading, and managing reports
 */

/**
 * Generate a unique report ID in format RK-YYYY-NNNN
 */
function generateReportId($conn) {
    $year = date('Y');
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM reports WHERE report_id LIKE ?");
    $yearPattern = 'RK-' . $year . '-%';
    $stmt->bind_param("s", $yearPattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['total'] + 1;

    return 'RK-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
}

/**
 * Create a new report
 * Returns the report_id on success, or false on failure
 */
function createReport($conn, $userId, $data) {
    $reportId = generateReportId($conn);

    $stmt = $conn->prepare("INSERT INTO reports 
        (report_id, user_id, damage_type, damage_severity, detection_confidence, 
         image_path, image_output_path, latitude, longitude, address, description, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'dilaporkan')");

    $stmt->bind_param("sisssssddss",
        $reportId,
        $userId,
        $data['damage_type'],
        $data['damage_severity'],
        $data['detection_confidence'],
        $data['image_path'],
        $data['image_output_path'],
        $data['latitude'],
        $data['longitude'],
        $data['address'],
        $data['description']
    );

    if ($stmt->execute()) {
        return $reportId;
    }
    return false;
}

/**
 * Get all reports for a specific user
 */
function getUserReports($conn, $userId, $limit = null) {
    $sql = "SELECT * FROM reports WHERE user_id = ? ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $limit);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get recent reports (for landing page)
 */
function getRecentReports($conn, $limit = 5) {
    $stmt = $conn->prepare("
        SELECT r.*, u.username 
        FROM reports r 
        LEFT JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get platform-wide statistics
 */
function getPlatformStats($conn) {
    $stats = [];

    // Total reports
    $result = $conn->query("SELECT COUNT(*) as total FROM reports");
    $stats['total_reports'] = $result->fetch_assoc()['total'];

    // Reports by status
    $result = $conn->query("
        SELECT 
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status IN ('diverifikasi', 'diperbaiki') THEN 1 ELSE 0 END) in_progress,
            SUM(CASE WHEN status = 'dilaporkan' THEN 1 ELSE 0 END) as dilaporkan
        FROM reports
    ");
    $row = $result->fetch_assoc();
    $stats['selesai'] = (int)$row['selesai'];
    $stats['in_progress'] = (int)$row['in_progress'];
    $stats['dilaporkan'] = (int)$row['dilaporkan'];

    // Average response time (days between created_at and updated_at for completed)
    $result = $conn->query("
        SELECT COALESCE(AVG(DATEDIFF(updated_at, created_at)), 0) as avg_days
        FROM reports WHERE status = 'selesai'
    ");
    $stats['avg_response_days'] = round($result->fetch_assoc()['avg_days'], 1);

    // Get total users count
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
    $stats['total_users'] = $result->fetch_assoc()['total'];

    return $stats;
}

/**
 * Get user statistics (for profile page)
 */
function getUserStats($conn, $userId) {
    $stats = [];

    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status IN ('dilaporkan', 'diverifikasi', 'diperbaiki') THEN 1 ELSE 0 END) as in_progress
        FROM reports WHERE user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stats['total'] = (int)$row['total'];
    $stats['selesai'] = (int)$row['selesai'];
    $stats['in_progress'] = (int)$row['in_progress'];

    // Response rate
    $totalReports = $stats['total'];
    if ($totalReports > 0) {
        $completed = $stats['selesai'];
        $stats['response_rate'] = round(($completed / $totalReports) * 100);
    } else {
        $stats['response_rate'] = 0;
    }

    return $stats;
}

/**
 * Get status progress data for Garis Jalan
 * Returns [current_step, total_steps, percentage]
 */
function getStatusProgress($status) {
    $steps = [
        'dilaporkan' => 0,
        'diverifikasi' => 1,
        'diperbaiki' => 2,
        'selesai' => 3,
    ];

    $currentStep = $steps[$status] ?? 0;
    $totalSteps = 3;
    $percentage = ($totalSteps > 0) ? round(($currentStep / $totalSteps) * 100) : 0;

    return [
        'current_step' => $currentStep,
        'total_steps' => $totalSteps,
        'percentage' => $percentage
    ];
}

/**
 * Format time ago string
 */
function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;

    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
    if ($diff < 2592000) return floor($diff / 86400) . ' hari lalu';
    return date('j M Y', $time);
}

/**
 * Format status label in Indonesian
 */
function statusLabel($status) {
    $labels = [
        'dilaporkan' => 'Dilaporkan',
        'diverifikasi' => 'Diverifikasi',
        'diperbaiki' => 'Diperbaiki',
        'selesai' => 'Selesai'
    ];
    return $labels[$status] ?? $status;
}

/**
 * Format damage type in Indonesian
 */
function damageTypeLabel($type) {
    $labels = [
        'pothole' => 'Lubang Jalan',
        'crack' => 'Retak Jalan',
        'alligator_crack' => 'Retak Buaya',
        'rutting' => 'Jalan Bergelombang',
        'depression' => 'Amblas Jalan',
        'edge_damage' => 'Kerusakan Tepi',
        'patch' => 'Tambalan',
        'other' => 'Kerusakan Lain'
    ];
    return $labels[$type] ?? ($type ?: 'Kerusakan Jalan');
}

/**
 * Get status badge class name
 */
function getStatusBadgeClass($status) {
    $map = [
        'dilaporkan' => 'dilaporkan',
        'diverifikasi' => 'diverifikasi',
        'diperbaiki' => 'diperbaiki',
        'selesai' => 'selesai'
    ];
    return $map[$status] ?? 'dilaporkan';
}

/**
 * Get user by ID
 */
function getUserById($conn, $userId) {
    $stmt = $conn->prepare("SELECT id, username, full_name, email, phone, address, role, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
