# Panduan Refactoring UI/UX Total (V2) - RoKenAI Platform

Dokumen ini berisi panduan perombakan desain secara masif dan radikal (*Total Redesign*) untuk **RoKenAI**. Desain versi ini meninggalkan kesan visual lama yang terlalu kosong/simpel dan menggantinya dengan antarmuka aplikasi SaaS AI modern berbasis **Bento Grid layout**, efek kedalaman mewah, serta interaksi dinamis yang setara dengan platform AI kelas dunia (seperti ChatGPT Premium, Claude, atau Perplexity).

---

## 1. Sistem Desain Baru (Premium Design Tokens)

### A. Palet Warna Sinematik & Modern (Cyber-Organic Palette)
Kita beralih dari abu-abu mati ke kombinasi warna gelap/terang bertekstur tinggi yang memberikan kesan premium.
*   **Warna Latar Belakang (Base Background):** `#FAFAFA` (Pure Light Mode) atau `#0B0F19` (Deep Space Dark Mode). Sangat disarankan mengadopsi struktur *Dark Mode* sebagai bawaan untuk kenyamanan visual pengerjaan AI.
*   **Warna Aksen Utama (Brand Signature):** `#FACC15` (Kuning RoKen) dipertahankan, namun dipadukan dengan `#EAB308` untuk gradasi. Ditambahkan warna komplementer premium: `#6366F1` (Indigo Neon) untuk indikator kecerdasan buatan/AI.
*   **Warna Kartu & Panel:** `#FFFFFF` (Light) atau `#1E293B` (Dark).
*   **Sistem Gradasi (Glow Effect):** `linear-gradient(135deg, #FACC15 0%, #6366F1 100%)`.

### B. Tipografi High-End
*   **Font Family:** **Plus Jakarta Sans** atau **Satoshi Sans** (wajib diload lewat Google Fonts).
*   **Hierarki Baru:**
    *   Super Title: `28pt` - `36pt` (Extra Bold, menggunakan efek *Gradient Text*).
    *   Section Header: `16pt` (Semi-Bold, tracking tight `-0.02em`).
    *   Body Text: `11pt` (Regular, line-height `1.6`).

### C. Efek Kaca Premium (Glassmorphism & Shadows)
*   **Borders:** Ketebalan `1px` solid dengan transparansi tinggi: `rgba(255, 255, 255, 0.08)`.
*   **Glow Shadow:** `box-shadow: 0 0 25px rgba(250, 204, 21, 0.15);` untuk elemen AI aktif.

---

## 2. Transformasi Total Layout Halaman

### A. Halaman Utama (`index.php`) - Dari Kosong Menjadi Berisi (*Bento Grid Layout*)
*   **Desain Lama:** Hanya satu tombol kotak kuning besar di tengah layar kosong.
*   **Desain Baru (Total Perubahan):** 
    *   Halaman diubah menjadi struktur **Bento Grid** (kotak-kotak informasi yang tersusun rapi secara asimetris).
    *   **Hero Section (Atas):** Teks besar di tengah layar menggunakan gradasi warna: **"The Future of Road Infrastructure Inspection Powered by RoKenAI"**.
    *   **Pusat Aksi (Bento Grid 1 - Utama):** Tombol kuning besar diubah menjadi **Interactive Dashboard Card** berukuran besar di sisi kiri. Isinya adalah area navigasi instan: *"Mulai Deteksi Kerusakan Jalan"* dengan tombol interaktif yang memancarkan efek cahaya halus (*glowing pulse*).
    *   **Bento Grid 2 (Kanan Atas):** Kotak statistik mini yang menampilkan metrik tiruan (dummy data) performa model AI (misal: `YOLOv8 Accuracy: 94.2%`, `Inference Speed: 12ms`). Ini memberikan kesan bahwa web ini adalah dashboard AI yang canggih sejak pertama kali dibuka.
    *   **Bento Grid 3 (Kanan Bawah):** Kotak riwayat aktivitas terakhir pengguna (*Recent Inspections*) yang menampilkan daftar laporan jalan secara ringkas.

### B. Menu Navigasi Sidebar - Dari List Simpel ke Dock Sidebar Mewah
*   **Desain Lama:** List teks biasa di sebelah kiri dengan background gelap monoton.
*   **Desain Baru (Total Perubahan):**
    *   Sidebar diubah menggunakan konsep **Glassmorphism Nav Dock** yang mengapung (*floating*) di sisi kiri, terpisah beberapa piksel dari tepi layar.
    *   Setiap menu ("Chat", "Contoh", "Berita") dipisahkan ke dalam bilah baris yang memiliki ikon kustom bertekstur warna (Kuning untuk Chat, Indigo untuk Contoh, Slate untuk Berita).
    *   Bagian bawah sidebar dilengkapi dengan panel profil pengguna (*User Profile Widget*) yang menampilkan nama, avatar bulat bercahaya, dan status koneksi server AI.

### C. Halaman Obrolan (`chat.php`) - Antarmuka Workspace AI
*   **Desain Lama:** Layar abu-abu kosong dengan satu gelembung chat di atas dan input box di bawah.
*   **Desain Baru (Total Perubahan):**
    *   Layar dibagi menjadi dua kolom (*Split Workspace Layout*):
        *   **Kolom Kiri (30% lebar):** Berisi riwayat percakapan (*Chat History Session Manager*) yang dikelompokkan berdasarkan waktu (Hari Ini, Kemarin, Minggu Lalu).
        *   **Kolom Kanan (70% lebar):** Ruang obrolan utama.
    *   **Gelembung Chat (Chat Bubbles):** Tidak lagi berbentuk kotak rounded biasa. Chat dari AI memiliki latar belakang warna abu-abu satin ultra-lembut dengan ikon logo mini RoKenAI di pojok kiri atasnya. Kode program yang dihasilkan oleh AI akan otomatis dibungkus ke dalam komponen *Code Block Preview* yang memiliki tombol "Copy Code".
    *   **Input Box Baru:** Kapsul chat di bawah dibuat melebar penuh mengikuti lebar kolom kanan, mengapung mewah di atas lapisan blur statis (*sticky frosted glass panel*). Tombol kirim berbentuk bulat penuh dengan warna gradasi kuning-indigo cerah.

### D. Halaman Unggah Berkas (`upload.php`) - Drag & Drop Imersif
*   **Desain Lama:** Kotak putih kecil di tengah dengan garis putus-putus kuning tipis.
*   **Desain Baru (Total Perubahan):**
    *   Kotak dropzone diubah menjadi **Fullscreen-Proportional Area** (lebar 80% dari area kerja).
    *   Garis putus-putus kuning dihapus, diganti dengan **Neon Dash Border Glow** berwarna indigo transparan yang berkedip pelan (*breathing animation effect*) saat standby.
    *   **Efek Interaksi Tingkat Tinggi:** Ketika file gambar/video jalan rusak diseret (*drag*) ke atas layar, seluruh halaman web akan otomatis meredup secara halus, dan area dropzone akan membesar secara visual (*scaling up 1.02x*) dengan warna luar berubah menjadi kuning emas menyala, memberikan feedback visual yang sangat memuaskan bagi pengguna sebelum melepaskan file gambar.

---

## 3. Blueprint Struktur Kode Baru (Panduan Implementasi AI)

Berikan potongan struktur ini kepada asisten AI untuk memandu penulisan CSS/HTML barunya:

```html
<!-- Struktur Bento Grid Baru untuk index.php -->
<main class="min-h-screen bg-[#0B0F19] text-white pt-24 px-8 pb-12">
  <!-- Hero Heading -->
  <div class="text-center mb-12">
    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight bg-gradient-to-r from-yellow-400 via-amber-400 to-indigo-400 bg-clip-text text-transparent">
      RoKenAI Workspace
    </h1>
    <p class="text-slate-400 mt-4 text-lg max-w-xl mx-auto">Reconstructing road infrastructure monitoring through deep learning computer vision.</p>
  </div>

  <!-- Bento Grid Container -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
    
    <!-- Blok Besar 1: Akses Utama Utama -->
    <div class="md:col-span-2 bg-[#1E293B]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-8 hover:border-yellow-400/40 transition-all duration-300 shadow-2xl group flex flex-col justify-between min-h-[320px]">
      <div>
        <span class="bg-yellow-400/10 text-yellow-400 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">AI Engine Ready</span>
        <h2 class="text-2xl font-bold mt-4 group-hover:text-yellow-400 transition-colors">Deteksi & Analisis Kerusakan Jalan</h2>
        <p class="text-slate-400 mt-2 text-sm">Unggah dokumentasi foto jalan raya untuk memproses segmentasi objek kerusakan jalan, lubang, dan retakan secara real-time via YOLOv8.</p>
      </div>
      <a href="upload.php" class="w-full py-4 bg-gradient-to-r from-yellow-400 to-amber-500 text-slate-950 font-bold rounded-2xl text-center shadow-lg shadow-yellow-500/20 hover:shadow-yellow-500/40 hover:-translate-y-1 transition-all duration-300">
        Buka Modul Deteksi
      </a>
    </div>

    <!-- Blok 2: Real-time Monitor ( dummy stats ) -->
    <div class="bg-[#1E293B]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-6 flex flex-col justify-between">
      <h3 class="text-slate-400 font-medium text-sm">Model Core Specs</h3>
      <div class="my-6 space-y-4">
        <div class="flex justify-between border-b border-white/5 pb-2">
          <span class="text-sm text-slate-400">Weights File</span>
          <span class="text-sm font-mono text-yellow-400">best.pt</span>
        </div>
        <div class="flex justify-between border-b border-white/5 pb-2">
          <span class="text-sm text-slate-400">Framework</span>
          <span class="text-sm font-mono text-indigo-400">PyTorch / YOLO</span>
        </div>
        <div class="flex justify-between">
          <span class="text-sm text-slate-400">Status</span>
          <span class="text-sm font-semibold text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Online</span>
        </div>
      </div>
      <div class="bg-slate-900/50 rounded-xl p-3 text-center text-xs text-slate-400 font-mono">
        modules/main.py linked
      </div>
    </div>

  </div>
</main>
```

---
*Catatan Penting: Desain V2 ini sengaja merombak drastis aspek estetik platform agar memiliki nilai jual tinggi, terlihat fungsional, dan sepenuhnya menghapus impresi halaman kosong "tahap pengembangan".*
