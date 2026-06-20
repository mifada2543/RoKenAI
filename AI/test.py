import os
import sys
from ultralytics import YOLO

# --- KONFIGURASI ---
MODEL_ASLI = 'best.pt'
FOLDER_OPENVINO = 'best_openvino_model'

# Folder tujuan kustom untuk hasil deteksi web PHP
FOLDER_HASIL = '/opt/lampp/htdocs/RoKenAI/AI/hasil'

# Ganti ini dengan file yang ingin Anda uji (bisa .jpg, .png, atau .mp4)
# Jika ingin pakai WEBCAM, ganti nilainya menjadi angka 0 (tanpa kutip)
SUMBER_DATA = 'test.jpeg' 

# Ambang batas deteksi (0.4 = model hanya menampilkan jika yakin di atas 40%)
CONFIDENCE_THRESHOLD = 0.4 
# -------------------

def main():
    # 1. Validasi File Model Asli
    if not os.path.exists(MODEL_ASLI):
        print(f"[ERROR] File '{MODEL_ASLI}' tidak ditemukan di folder ini.")
        print("Silakan pindahkan file 'best.pt' hasil download dari Colab ke folder yang sama dengan skrip ini.")
        sys.exit(1)

    # 2. Proses Eksport ke OpenVINO (Hanya berjalan sekali di awal)
    if not os.path.exists(FOLDER_OPENVINO):
        print("[INFO] Folder OpenVINO belum ditemukan. Memulai konversi model...")
        print("[INFO] Proses ini hanya memakan waktu sekali untuk optimasi Intel...")
        try:
            model_awal = YOLO(MODEL_ASLI)
            # Menggunakan half=True (FP16) agar sangat ringan di Intel Core i3/i5
            model_awal.export(format='openvino', half=True)
            print("[SUCCESS] Konversi ke OpenVINO berhasil!")
        except Exception as e:
            print(f"[WARNING] Gagal konversi ke OpenVINO: {e}")
            print("[INFO] Sistem akan mencoba berjalan menggunakan model .pt standar.")

    # 3. Load Model yang Sudah Dioptimasi
    print("\n[INFO] Memuat model ke sistem...")
    if os.path.exists(FOLDER_OPENVINO):
        model = YOLO(FOLDER_OPENVINO, task='detect') 
        print("[INFO] Model yang digunakan: OpenVINO (Intel Optimized)")
    else:
        model = YOLO(MODEL_ASLI, task='detect')
        print("[INFO] Model yang digunakan: PyTorch Standard (Format .pt)")

    # 4. Jalankan Deteksi
    print(f"[INFO] Memulai deteksi pada sumber: {SUMBER_DATA} ...")
    
    # device='cpu' adalah yang paling aman dan stabil di Intel CPU + OpenVINO
    # imgsz=512 disesuaikan dengan dataset Roboflow Anda sebelumnya
    results = model.predict(
        source=SUMBER_DATA, 
        conf=CONFIDENCE_THRESHOLD, 
        imgsz=512, 
        device='cpu', 
        save=True,
        project=FOLDER_HASIL, # <--- Menyimpan langsung ke folder hasil proyek Anda
        name='.',             # <--- Menghilangkan subfolder tambahan (predict/)
        exist_ok=True         # <--- Menimpa file lama agar storage server tidak penuh
    )

    print("\n" + "="*50)
    print("[SELESAI] Deteksi sukses dilakukan!")
    print(f"Silakan cek hasilnya di dalam folder: {FOLDER_HASIL}")
    print("="*50)

if __name__ == "__main__":
    main()