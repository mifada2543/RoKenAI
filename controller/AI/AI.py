import os
import sys
from ultralytics import YOLO

# --- KONFIGURASI ---
MODEL_ASLI = 'best.pt'
FOLDER_OPENVINO = 'best_openvino_model'
FOLDER_HASIL = '/opt/lampp/htdocs/RoKenAI/controller/uploads'
CONFIDENCE_THRESHOLD = 0.4 
# -------------------

def main():
    if len(sys.argv) < 2:
        print("\n[PERINGATAN] Mohon masukkan input gambar!")
        print("Contoh penggunaan: python test.py nama_gambar.jpeg")
        sys.exit(1)
        
    SUMBER_DATA = sys.argv[1]
    is_webcam = False
    
    if SUMBER_DATA == '0':
        SUMBER_DATA = 0
        is_webcam = True
    elif not os.path.exists(SUMBER_DATA):
        print(f"[ERROR] File gambar '{SUMBER_DATA}' tidak ditemukan.")
        sys.exit(1)

    if not os.path.exists(MODEL_ASLI) and not os.path.exists(FOLDER_OPENVINO):
        print(f"[ERROR] Model '{MODEL_ASLI}' atau '{FOLDER_OPENVINO}' tidak ditemukan.")
        sys.exit(1)

    # Proses Eksport ke OpenVINO (Hanya berjalan sekali di awal)
    if not os.path.exists(FOLDER_OPENVINO) and os.path.exists(MODEL_ASLI):
        print("[INFO] Folder OpenVINO belum ditemukan. Memulai konversi model...")
        try:
            model_awal = YOLO(MODEL_ASLI)
            # Ditambahkan opsi imgsz=512 agar proses ekspor OpenVINO match dengan ukuran prediksi Anda
            model_awal.export(format='openvino', half=True, imgsz=512)
            print("[SUCCESS] Konversi ke OpenVINO berhasil!")
        except Exception as e:
            print(f"[WARNING] Gagal konversi ke OpenVINO: {e}")

    print("\n[INFO] Memuat model ke sistem...")
    if os.path.exists(FOLDER_OPENVINO):
        model = YOLO(FOLDER_OPENVINO, task='detect') 
        print("[INFO] Model yang digunakan: OpenVINO (Intel Optimized)")
    else:
        model = YOLO(MODEL_ASLI, task='detect')
        print("[INFO] Model yang digunakan: PyTorch Standard")

    print(f"[INFO] Memulai deteksi pada sumber: {SUMBER_DATA} ...")
    
    # Deteksi otomatis: Jika lewat web (bukan webcam), matikan live preview agar tidak crash
    tampilkan_jendela = True if is_webcam else False

    results = model.predict(
        source=SUMBER_DATA, 
        conf=CONFIDENCE_THRESHOLD, 
        imgsz=512, 
        device='cpu', 
        save=True,
        show=tampilkan_jendela,  # <--- Otomatis dinamis sekarang            
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