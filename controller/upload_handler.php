<?php
/**
 * RoKenAI Upload Handler
 * Processes report submission: saves image, creates database record
 */

session_name('RoKenAI');
session_start();

require_once __DIR__ . '/../auth/config.php';
require_once __DIR__ . '/report.php';

header('Content-Type: application/json');

// Check if user is logged in
$userId = null;
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    $userId = $_SESSION['user_id'];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Verify CSRF
if (!verify_csrf()) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid security token']);
    exit;
}

// Get form data
$address = trim($_POST['address'] ?? '');
$description = trim($_POST['description'] ?? '');
$latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
$longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;

// Build initial data array
$data = [
    'damage_type' => $_POST['damage_type'] ?? null,
    'damage_severity' => $_POST['damage_severity'] ?? null,
    'detection_confidence' => !empty($_POST['detection_confidence']) ? (float)$_POST['detection_confidence'] : null,
    'image_path' => null,
    'image_output_path' => null,
    'latitude' => $latitude,
    'longitude' => $longitude,
    'address' => $address,
    'description' => $description,
];

// Handle image upload
$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($extension, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung. Gunakan JPG, PNG, atau WebP.']);
        exit;
    }

    // Validate file size (max 10MB)
    if ($_FILES['image']['size'] > 10 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'message' => 'File terlalu besar. Maksimum 10MB.']);
        exit;
    }

    // Create upload directories if needed
    $uploadDir = __DIR__ . '/uploads/reports/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique filename
    $timestamp = time();
    $uniqueName = $timestamp . '_' . uniqid() . '.' . $extension;
    $destPath = $uploadDir . $uniqueName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        chmod($destPath, 0644);
        // Store relative path for web access
        $data['image_path'] = 'controller/uploads/reports/' . $uniqueName;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan file.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Silakan upload foto jalan rusak.']);
    exit;
}

// If user is not logged in, create a guest user or handle accordingly
if (!$userId) {
    // For now, use first active user as fallback in demo mode
    // In production, this should redirect to login
    $result = $conn->query("SELECT id FROM users WHERE is_active = 1 ORDER BY id ASC LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $userId = $result->fetch_assoc()['id'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
        exit;
    }
}

// Create report in database
$reportId = createReport($conn, $userId, $data);

if ($reportId) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Laporan berhasil dikirim!',
        'report_id' => $reportId
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan laporan. Silakan coba lagi.'
    ]);
}
