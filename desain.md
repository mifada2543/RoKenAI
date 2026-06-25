# Desain Frontend — RoKenAI

> **RoKenAI** — Solusi Cerdas Pelaporan Jalan Rusak berbasis AI
> Platform untuk warga melaporkan kerusakan jalan lewat foto, dideteksi otomatis oleh model AI (YOLO `best.pt`), lalu ditindaklanjuti lewat dashboard admin.

---

## 1. Filosofi Desain

Tema besar RoKenAI adalah **jalan** — infrastruktur publik, garis marka, aspal, dan proses perbaikan yang bertahap. Desain harus terasa **clean & profesional** (sesuai brief: putih + biru), seperti aplikasi layanan publik/pemerintah yang bisa dipercaya, tapi tetap modern dan tidak kaku.

Prinsip yang dipegang:
- **Dipercaya seperti aplikasi pemerintah/civic-tech**, tapi secepat dan semudah app konsumer.
- **Status laporan adalah warna pertama yang dilihat mata** — pengguna harus langsung tahu "laporan saya sudah sejauh mana" tanpa membaca teks.
- **Garis jalan sebagai elemen visual khas** — bukan dekorasi acak, tapi dipakai sebagai progress indicator/divider yang punya makna (lihat bagian 4).

---

## 2. Design Tokens

### 2.1 Warna

| Token | Hex | Penggunaan |
|---|---|---|
| `--primary-700` | `#1D4ED8` | Warna utama brand, navbar, tombol primer |
| `--primary-500` | `#3B82F6` | Hover state, link aktif, ikon |
| `--primary-100` | `#DBEAFE` | Background section terang, badge info |
| `--surface` | `#FFFFFF` | Background utama card & halaman |
| `--surface-muted` | `#F8FAFC` | Background halaman (sedikit abu agar card menonjol) |
| `--ink-900` | `#0F172A` | Teks judul/heading |
| `--ink-600` | `#475569` | Teks body/paragraf |
| `--line-200` | `#E2E8F0` | Border, divider |
| `--marka-400` *(aksen)* | `#FACC15` | Marka kuning jalan — dipakai khusus di signature element & highlight "dalam proses" |
| `--status-danger` | `#DC2626` | Status: Rusak Parah |
| `--status-warning` | `#F59E0B` | Status: Rusak Sedang / Menunggu Verifikasi |
| `--status-progress` | `#2563EB` | Status: Sedang Diperbaiki |
| `--status-success` | `#16A34A` | Status: Selesai Diperbaiki |

> Biru jadi warna kepercayaan & aksi, kuning marka jadi satu-satunya aksen "hangat" — dipakai pelit, supaya tetap kerasa profesional, bukan ramai.

### 2.2 Tipografi

| Role | Font | Catatan |
|---|---|---|
| Display/Heading | **Plus Jakarta Sans** (600–700) | Tegas, geometris, cocok untuk produk civic-tech/Indonesia |
| Body | **Inter** (400–500) | Netral, sangat mudah dibaca di form & tabel |
| Data/Mono | **IBM Plex Mono** (500) | Khusus untuk ID laporan, koordinat GPS, timestamp |

Skala tipografi:
```
H1  32px / 700   — Judul halaman (Beranda, Dashboard)
H2  24px / 600   — Judul section/card besar
H3  18px / 600   — Judul card laporan
Body 15px / 400  — Paragraf, label form
Small 13px / 500 — Caption, metadata, badge
Mono 13px / 500  — ID laporan, lat/long
```

### 2.3 Spacing & Radius

- Spacing base: skala 4px (4, 8, 12, 16, 24, 32, 48)
- Radius card: `12px` — cukup lembut tapi tetap formal (bukan 24px yang terlalu playful)
- Radius button/input: `8px`
- Shadow card: `0 1px 3px rgba(15,23,42,0.08)` — tipis, jangan dramatis

---

## 3. Signature Element: "Garis Jalan" Progress Line

Karena tema utama adalah jalan, elemen unik RoKenAI adalah **progress bar berbentuk jalan** — garis horizontal abu (aspal) dengan strip putus-putus kuning (marka) yang terisi sesuai progres laporan.

```
Dilaporkan ●━━━━━━━━━━ Diverifikasi ●╌╌╌╌╌╌ Diperbaiki ○╌╌╌╌╌╌ Selesai ○
           (terisi biru penuh)      (terisi sebagian)  (belum, abu)
```

Dipakai di:
- Detail laporan milik warga (tracking status)
- Card ringkas di list laporan (versi mini, tanpa label)
- Dashboard admin (versi horizontal di tabel)

Ini bukan sekadar progress bar generik — bentuknya literally meniru jalan, jadi setiap kali pengguna cek status, mereka "melihat jalannya sendiri sedang diperbaiki".

---

## 4. Komponen UI

### Navbar
- Putih, border bawah `--line-200`, logo RoKenAI di kiri (ikon pin lokasi + retakan jalan kecil)
- Menu: Beranda · Lapor Kerusakan · Tanya AI · Riwayat Laporan · Profil
- Untuk admin: sidebar gelap `--ink-900` dengan aksen biru, bukan navbar atas

### Tombol
- Primary: background `--primary-700`, teks putih, radius 8px, hover ke `--primary-500`
- Secondary/outline: border `--primary-700`, teks `--primary-700`, background transparan
- Danger (hapus/tolak laporan di admin): `--status-danger`

### Badge Status
Pill kecil dengan dot warna + teks, contoh:
`🔵 Diverifikasi` `🟡 Menunggu` `🟢 Selesai` `🔴 Rusak Parah`

### Card Laporan
Layout: foto jalan (rasio 4:3, hasil deteksi AI dengan bounding box overlay opsional) → judul lokasi → badge status → mini progress line → tombol "Lihat Detail"

### Chat Bubble (chat.php)
- Bubble AI: background `--primary-100`, teks `--ink-900`, rata kiri, avatar bulat ikon robot/AI
- Bubble user: background `--primary-700`, teks putih, rata kanan
- Input bar bawah: rounded-full, ikon attach foto + kirim

### Form Upload (upload.php)
- Dropzone besar dengan border dashed `--line-200`, ikon kamera, teks "Tarik foto jalan rusak di sini atau klik untuk pilih"
- Setelah upload: preview foto + hasil deteksi AI (label "Lubang Jalan — Tingkat: Parah, Confidence 92%") ditampilkan sebagai overlay box kuning di atas foto
- Form lokasi: auto-fill dari GPS (lat/long mono font) + field alamat manual

---

## 5. Desain Per Halaman

### 5.1 `index.php` — Landing Page
```
┌─────────────────────────────────────────┐
│ Navbar                                   │
├─────────────────────────────────────────┤
│ HERO                                     │
│ "Lihat Jalan Rusak? Laporkan dalam       │
│  Hitungan Detik — AI Kami yang Verifikasi│
│  [Lapor Sekarang]  [Lihat Peta Laporan]  │
│  (gambar: foto jalan rusak + bounding    │
│   box AI overlay, sebagai bukti nyata    │
│   kemampuan deteksi)                     │
├─────────────────────────────────────────┤
│ Cara Kerja (3 langkah, bukan numbering   │
│ generik — tapi memang proses berurutan:  │
│ Foto → Deteksi AI → Ditindaklanjuti)      │
├─────────────────────────────────────────┤
│ Peta interaktif: titik-titik laporan     │
│ berwarna sesuai status di seluruh kota   │
├─────────────────────────────────────────┤
│ Statistik: Total Laporan · Selesai       │
│ Diperbaiki · Rata-rata waktu respons     │
├─────────────────────────────────────────┤
│ Footer                                   │
└─────────────────────────────────────────┘
```
Hero adalah tesis halaman: bukan ilustrasi generik, tapi foto jalan rusak sungguhan dengan bounding box AI — langsung menunjukkan apa yang produk ini bisa lakukan.

### 5.2 `auth/` — Login & Register
- Split layout: kiri form (putih), kanan ilustrasi/foto jalan dengan overlay biru gradient + quote singkat ("1.200+ laporan sudah ditindaklanjuti")
- Form minimal: email/no. HP, password, tombol primary penuh lebar
- Link kecil di bawah: "Belum punya akun? Daftar" / "Masuk sebagai Admin" (link terpisah, kecil, di footer form — bukan tombol besar, supaya warga biasa tidak bingung)

### 5.3 `upload.php` — Lapor Kerusakan
Form satu kolom, step-by-step terasa ringan:
1. Upload foto (dropzone, lihat komponen di atas)
2. Hasil deteksi AI tampil otomatis (kategori kerusakan + tingkat keparahan)
3. Konfirmasi lokasi (peta kecil + GPS auto-fill)
4. Catatan tambahan (textarea opsional)
5. Tombol besar "Kirim Laporan"

Setelah submit → halaman sukses dengan ID laporan (mono font) + progress line di posisi "Dilaporkan"

### 5.4 `chat.php` — Tanya AI
- Layout chat klasik, tapi ada **quick-reply chips** di atas input: "Cara melaporkan", "Status laporan saya", "Jenis kerusakan apa yang dideteksi?"
- Sidebar kiri (desktop) menampilkan riwayat percakapan, seperti ChatGPT tapi lebih sederhana

### 5.5 `profile.php`
- Header: foto profil, nama, total laporan dikirim, badge "Pelapor Aktif" jika sudah lapor >5 kali (gamifikasi ringan, civic engagement)
- Tab: Data Diri · Riwayat Laporan (list card laporan dengan progress line) · Ubah Password

### 5.6 `admin/` — Dashboard Admin
- Sidebar gelap kiri, konten utama putih
- Dashboard utama: kartu ringkasan (Total Laporan, Menunggu Verifikasi, Dalam Perbaikan, Selesai) dengan angka besar + ikon
- Tabel laporan: kolom Foto thumbnail · Lokasi · Tingkat Kerusakan (badge) · Status (dropdown untuk update) · Tanggal · Aksi
- Detail laporan: foto full + bounding box AI, peta lokasi, tombol ubah status sepanjang progress line (klik titik untuk update status laporan)

---

## 6. Responsif & Aksesibilitas

- Mobile-first untuk halaman warga (`index`, `upload`, `chat`, `profile`) — mayoritas pelapor akan akses dari HP saat di jalan
- Admin dashboard prioritas desktop, tapi tabel laporan tetap bisa di-scroll horizontal di tablet
- Kontras teks minimal 4.5:1 (biru `#1D4ED8` di atas putih sudah aman)
- Semua badge status pakai warna **+ teks**, jangan warna saja (penting untuk pengguna buta warna membedakan tingkat kerusakan)
- Focus state keyboard terlihat jelas (outline biru 2px) di semua tombol & input

---

## 7. Ikon & Aset

- Gunakan icon set **Lucide** atau **Phosphor** (outline style, konsisten dengan rasa "clean professional")
- Ikon khas yang perlu custom: pin lokasi + retak jalan (logo), kamera/upload, bounding-box/scan (untuk merepresentasikan AI detection)
- Foto-foto contoh kerusakan jalan sebaiknya pakai foto asli (bukan ilustrasi), karena ini memperkuat kredibilitas "AI ini benar-benar mendeteksi dunia nyata"

---

*Dokumen ini jadi acuan saat ngerjain HTML/CSS di `partials/`, `assets/`, dan halaman-halaman utama supaya konsisten dari awal sampai akhir.*
