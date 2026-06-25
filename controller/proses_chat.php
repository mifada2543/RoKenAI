<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        // 1. Ambil nama file tanpa ekstensi untuk disinkronkan ke .jpg nanti
        $namaMurni = time() . '_' . uniqid();
        $namaFileInput  = $namaMurni . '.' . $extension; // Ekstensi asli (bisa png/jpeg)
        $namaFileOutput = $namaMurni . '.jpg';          // DIPAKSA .jpg sesuai output YOLO

        // Struktur Folder Input & Output
        $uploadDirInput  = __DIR__ . '/uploads/input/';
        $uploadDirOutput = __DIR__ . '/uploads/output/';

        if (!is_dir($uploadDirInput)) {
            mkdir($uploadDirInput, 0777, true);
        }
        if (!is_dir($uploadDirOutput)) {
            mkdir($uploadDirOutput, 0777, true);
        }

        // Jalur simpan file asli masuk ke folder input
        $destPath = $uploadDirInput . $namaFileInput;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            chmod($destPath, 0666);

            $path_python_venv = '/opt/lampp/htdocs/RoKenAI/RokenAI/bin/python';
            $dir_ai = '/opt/lampp/htdocs/RoKenAI/controller/AI';
            $nama_script = 'AI.py';

            // Jalankan perintah Python ke AI.py
            $command = "cd " . escapeshellarg($dir_ai) . " && " .
                "HOME=/tmp " .
                "LD_PRELOAD=/usr/lib/x86_64-linux-gnu/libstdc++.so.6 " .
                "LD_LIBRARY_PATH= " .
                escapeshellcmd($path_python_venv) . " " .
                escapeshellarg($nama_script) . " " .
                escapeshellarg($destPath) . " 2>&1";

            $output = shell_exec($command);

            // 2. PATH PENGECEKAN - Fokus mencari file .jpg di folder output
            $path_cek_fisik   = "/opt/lampp/htdocs/RoKenAI/controller/uploads/output/" . $namaFileOutput;
            $url_hasil_gambar = "/RoKenAI/controller/uploads/output/" . $namaFileOutput;
            $url_gambar_asli  = "/RoKenAI/controller/uploads/input/" . $namaFileInput;

            if (file_exists($path_cek_fisik)) {
                chmod($path_cek_fisik, 0666);

                // KONDISI 1: Objek terdeteksi, tampilkan gambar .jpg hasil sorotan YOLO dari folder output
                echo json_encode([
                    'status' => 'success',
                    'pesan' => "<b>Deteksi Selesai!</b> Berikut adalah hasil analisis RoKenAI:<br><br>" .
                        "<img src='" . $url_hasil_gambar . "' style='max-width:100%; border-radius:12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 8px;' alt='Hasil Deteksi'><br>" .
                        "<span style='font-size:12px; color:#10b981;'>Objek berhasil dideteksi dan dianalisis.</span>"
                ]);
            } else {
                // KONDISI 2: No Detections, tampilkan gambar asli dari folder input sebagai cadangan
                echo json_encode([
                    'status' => 'success',
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
