<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        // 1. BUAT SATU NAMA FILE UNIK DI AWAL (Gunakan ini untuk semua proses)
        $namaFileUnik = time() . '_' . uniqid() . '.' . $extension;

        // Folder uploads sementara
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $destPath = $uploadDir . $namaFileUnik;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            chmod($destPath, 0666);

            $path_python_venv = '/opt/lampp/htdocs/RoKenAI/RokenAI/bin/python';
            $dir_ai = '/opt/lampp/htdocs/RoKenAI/controller/AI';
            $nama_script = 'AI.py';

            // Jalankan model CV dengan file input yang bernama $namaFileUnik
            $command = "cd " . escapeshellarg($dir_ai) . " && " .
                "HOME=/tmp " .
                "LD_PRELOAD=/usr/lib/x86_64-linux-gnu/libstdc++.so.6 " .
                "LD_LIBRARY_PATH= " .
                escapeshellcmd($path_python_venv) . " " .
                escapeshellarg($nama_script) . " " .
                escapeshellarg($destPath) . " 2>&1";

            // ... [Proses eksekusi Python di atas tetap sama] ...
            $output = shell_exec($command);

            // Path fisik dan URL untuk file hasil prediksi YOLO
            $path_cek_fisik = "/opt/lampp/htdocs/RoKenAI/controller/uploads/" . $namaFileUnik;
            $url_hasil_gambar = "/RoKenAI/controller/uploads/" . $namaFileUnik;

            // Path URL cadangan ke folder uploads (jika YOLO tidak menghasilkan file baru karena no detections)
            $url_gambar_asli = "/RoKenAI/controller/uploads/" . $namaFileUnik;

            if (file_exists($path_cek_fisik)) {
                // KONDISI 1: Objek terdeteksi dan gambar hasil berhasil dibuat oleh YOLO
                echo json_encode([
                    'status' => 'success',
                    'pesan' => "<b>Deteksi Selesai!</b> Berikut adalah hasil analisis RoKenAI:<br><br>" .
                        "<img src='" . $url_hasil_gambar . "' style='max-width:100%; border-radius:12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 8px;' alt='Hasil Deteksi'><br>" .
                        "<span style='font-size:12px; color:#10b981;'>Objek berhasil dideteksi dan dianalisis.</span>"
                ]);
            } else {
                // KONDISI 2: File tidak ditemukan (Kemungkinan besar karena 'no detections')
                // Kita tampilkan gambar asli milik user agar chat tidak error/kosong
                echo json_encode([
                    'status' => 'success', // Tetap tandai sukses agar gambar muncul di chat
                    'pesan' => "<b>Deteksi Selesai!</b><br><br>" .
                        "<img src='" . $url_gambar_asli . "' style='max-width:100%; border-radius:12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 8px;' alt='Gambar Asli'><br>" .
                        "<span style='font-size:12px; color:#ef4444; font-weight:bold;'>RoKenAI tidak mendeteksi adanya objek pada gambar ini.</span>"
                ]);
            }
        } else {
            echo json_encode(['status' => 'error', 'error' => 'Gagal memindahkan file ke folder upload.']);
        }
    }
}
