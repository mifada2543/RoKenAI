import os
import sys
from ultralytics import YOLO

# --- KONFIGURASI ---
MODEL_ASLI = 'best.pt'
FOLDER_OPENVINO = 'best_openvino_model'

# Folder tujuan kustom untuk hasil deteksi web PHP
FOLDER_HASIL = '/opt/lampp/htdocs/RoKenAI/AI/hasil'

# Ambang batas deteksi (0.4 = model hanya menampilkan jika yakin di atas 40%)
CONFIDENCE_THRESHOLD = 0.4 
# -------------------

def main():
    # 1. Cek Input Gambar dari User via Terminal
    if len(sys.argv) < 2:
        print("\n[PERINGATAN] Mohon masukkan input gambar!")
        print("Contoh penggunaan: python test.py nama_gambar.jpeg")
        print("Atau gunakan angka 0 untuk webcam: python test.py 0\n")
        sys.exit(1)
        
    SUMBER_DATA = sys.argv[1]

    # Jika user menginput angka '0', ubah tipenya menjadi integer untuk webcam
    # Kita juga set variabel is_webcam untuk pengaturan parameter YOLO nanti
    is_webcam = False
    if SUMBER_DATA == '0':
        SUMBER_DATA = 0
        is_webcam = True
    # Jika input berupa file gambar/video, cek apakah filenya benar-benar ada
    elif not os.path.exists(SUMBER_DATA):
        print(f"[ERROR] File gambar '{SUMBER_DATA}' tidak ditemukan di folder ini.")
        sys.exit(1)

    # 2. Validasi File Model Asli
    if not os.path.exists(MODEL_ASLI):
        print(f"[ERROR] File '{MODEL_ASLI}' tidak ditemukan di folder ini.")
        print("Silakan pindahkan file 'best.pt' hasil download dari Colab ke folder yang sama dengan skrip ini.")
        sys.exit(1)

    # 3. Proses Eksport ke OpenVINO (Hanya berjalan sekali di awal)
    if not os.path.exists(FOLDER_OPENVINO):
        print("[INFO] Folder OpenVINO belum ditemukan. Memulai konversi model...")
        try:
            model_awal = YOLO(MODEL_ASLI)
            model_awal.export(format='openvino', half=True)
            print("[SUCCESS] Konversi ke OpenVINO berhasil!")
        except Exception as e:
            print(f"[WARNING] Gagal konversi ke OpenVINO: {e}")

    # 4. Load Model yang Sudah Dioptimasi
    print("\n[INFO] Memuat model ke sistem...")
    if os.path.exists(FOLDER_OPENVINO):
        model = YOLO(FOLDER_OPENVINO, task='detect') 
        print("[INFO] Model yang digunakan: OpenVINO (Intel Optimized)")
    else:
        model = YOLO(MODEL_ASLI, task='detect')
        print("[INFO] Model yang digunakan: PyTorch Standard")

    # 5. Jalankan Deteksi
    print(f"[INFO] Memulai deteksi pada sumber: {SUMBER_DATA} ...")
    if is_webcam:
        print("[INFO] Menyalakan kamera... Tekan tombol 'Q' pada jendela kamera untuk keluar.")
    
    results = model.predict(
        source=SUMBER_DATA, 
        conf=CONFIDENCE_THRESHOLD, 
        imgsz=512, 
        device='cpu', 
        save=True,
        show=True,            # <--- PENTING: Ini akan memunculkan jendela Live Preview Kamera di Linux Anda!
        project=FOLDER_HASIL, 
        name='.',             
        exist_ok=True         
    )

    print("\n" + "="*50)
    print("[SELESAI] Deteksi sukses dilakukan!")
    print(f"Silakan cek hasilnya di dalam folder: {FOLDER_HASIL}")
    print("="*50)

if __name__ == "__main__":
    main()