<?php
/**
 * RoKenAI Profile Handler
 * Memproses update data diri dan ubah password
 */

session_name('RoKenAI');
session_start();

require_once __DIR__ . '/../auth/config.php';
require_once __DIR__ . '/report.php';

header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Verify CSRF
if (!verify_csrf()) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid security token. Silakan refresh halaman.']);
    exit;
}

switch ($action) {

    // ================================================================
    // UPDATE DATA DIRI
    // ================================================================
    case 'update_profile':
        $fullName  = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $address   = trim($_POST['address'] ?? '');

        // Validasi basic
        if (empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email tidak boleh kosong.']);
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
            exit;
        }

        // Cek apakah email sudah dipakai user lain
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email sudah digunakan oleh akun lain.']);
            exit;
        }

        // Update database
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $fullName, $email, $phone, $address, $userId);

        if ($stmt->execute()) {
            // Update session username jika full_name diisi
            if (!empty($fullName)) {
                $_SESSION['username'] = $fullName;
            }
            echo json_encode([
                'status' => 'success',
                'message' => 'Data diri berhasil disimpan!',
                'data' => [
                    'full_name' => $fullName,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data. Silakan coba lagi.']);
        }
        break;

    // ================================================================
    // UBAH PASSWORD
    // ================================================================
    case 'change_password':
        $currentPw    = $_POST['current_password'] ?? '';
        $newPw        = $_POST['new_password'] ?? '';
        $confirmPw    = $_POST['confirm_password'] ?? '';

        // Validasi
        if (empty($currentPw) || empty($newPw) || empty($confirmPw)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua field password harus diisi.']);
            exit;
        }

        if (strlen($newPw) < 8) {
            echo json_encode(['status' => 'error', 'message' => 'Password baru minimal 8 karakter.']);
            exit;
        }

        if ($newPw !== $confirmPw) {
            echo json_encode(['status' => 'error', 'message' => 'Konfirmasi password baru tidak cocok.']);
            exit;
        }

        // Verifikasi password saat ini
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!password_verify($currentPw, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Password saat ini salah.']);
            exit;
        }

        // Hash password baru
        $newHash = password_hash($newPw, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newHash, $userId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Password berhasil diubah!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah password. Silakan coba lagi.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenal.']);
        break;
}
