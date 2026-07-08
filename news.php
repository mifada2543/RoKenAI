<?php session_name('RoKenAI'); session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Berita &amp; Pembaruan</title>
    <?php include 'partials/link.php'; ?>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <main class="max-w-[1100px] mx-auto px-6 py-6 pb-14 animate-fade-in-up">

            <!-- Page Heading -->
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-[#0F172A] mb-2" style="font-size:clamp(24px,3.5vw,32px);letter-spacing:-0.02em;">
                    <span class="gradient-text" data-i18n="news.title">Berita &amp; Pembaruan</span>
                </h1>
                <p class="text-[14px] text-[#475569] max-w-md mx-auto leading-6" data-i18n="news.desc">
                    Ikuti perkembangan fitur RoKenAI terbaru, terobosan penelitian, dan acara komunitas.
                </p>
            </div>

            <!-- Featured Article -->
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card overflow-hidden mb-7 grid grid-cols-1 md:grid-cols-2 transition-all duration-300 hover:shadow-glow hover:-translate-y-0.5 hover:border-[rgba(29,78,216,0.2)]">
                <div class="min-h-[260px] flex items-center justify-center relative overflow-hidden bg-gradient-to-br from-[rgba(29,78,216,0.04)] to-[rgba(124,58,237,0.06)]">
                    <img src="assets/img/Home.png" alt="YOLOv8 OpenVINO Update" class="w-full h-full object-cover opacity-85 hover:opacity-100 transition-opacity duration-300">
                    <span class="absolute top-3.5 left-3.5 inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-primary-light border border-[rgba(29,78,216,0.15)] text-[11px] font-semibold text-[#1D4ED8]">
                        <i data-lucide="star" style="width:12px;height:12px;"></i>
                        <span data-i18n="news.featured">Unggulan</span>
                    </span>
                </div>
                <div class="p-8 flex flex-col justify-center">
                    <div class="text-[11px] text-[#94A3B8] font-medium mb-2 flex items-center gap-1.5">
                        <i data-lucide="calendar" style="width:14px;height:14px;"></i>
                        20 Juni 2026
                    </div>
                    <h2 class="font-heading text-[20px] font-bold text-[#0F172A] mb-2.5 leading-snug">YOLOv8 + OpenVINO: Inferensi 3x Lebih Cepat</h2>
                    <p class="text-[13px] text-[#475569] leading-relaxed mb-4">Kami merilis pembaruan performa besar. Dengan mengonversi model YOLOv8 ke format OpenVINO, kecepatan inferensi meningkat hingga 300% pada hardware yang didukung.</p>
                    <a href="chat.php?prompt=Tell+me+about+the+YOLOv8+OpenVINO+performance+update" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#3B82F6] no-underline hover:gap-2.5 hover:text-[#1D4ED8] transition-all duration-200">
                        <span data-i18n="news.readMore">Baca selengkapnya</span>
                        <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                    </a>
                </div>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php 
                $articles = [
                    ['icon' => 'rocket', 'iconBg' => 'rgba(29,78,216,0.1)', 'iconColor' => '#3B82F6', 'date' => '15 Juni 2026', 'title' => 'RoKenAI v2.0 Launch', 'desc' => 'Pembaruan terbesar kami — UI baru, ruang obrolan AI, pipeline deteksi real-time, dan pembuatan laporan dengan ekspor PDF.', 'tag' => 'release', 'tagLabel' => 'Rilis', 'tagColor' => '#3B82F6'],
                    ['icon' => 'database', 'iconBg' => 'rgba(124,58,237,0.1)', 'iconColor' => '#7C3AED', 'date' => '10 Juni 2026', 'title' => 'Dataset Kerusakan Jalan v2', 'desc' => 'Dataset diperluas dengan 15.000+ gambar jalan beranotasi mencakup 8 kategori kerusakan.', 'tag' => 'update', 'tagLabel' => 'Pembaruan', 'tagColor' => '#7C3AED'],
                    ['icon' => 'users', 'iconBg' => 'rgba(22,163,74,0.1)', 'iconColor' => '#16A34A', 'date' => '5 Juni 2026', 'title' => 'Webinar Komunitas: Road AI', 'desc' => 'Ikuti webinar kami pada 12 Juli untuk diskusi praktik terbaik dalam penerapan sistem inspeksi jalan berbasis YOLOv8.', 'tag' => 'event', 'tagLabel' => 'Acara', 'tagColor' => '#16A34A'],
                    ['icon' => 'book-open', 'iconBg' => 'rgba(220,38,38,0.1)', 'iconColor' => '#DC2626', 'date' => '28 Mei 2026', 'title' => 'Tutorial Baru: Training YOLOv8', 'desc' => 'Panduan langkah demi langkah mencakup persiapan dataset, konfigurasi YOLOv8, pipeline pelatihan, dan evaluasi model.', 'tag' => 'tutorial', 'tagLabel' => 'Tutorial', 'tagColor' => '#DC2626'],
                    ['icon' => 'cpu', 'iconBg' => 'rgba(29,78,216,0.1)', 'iconColor' => '#3B82F6', 'date' => '20 Mei 2026', 'title' => 'Integrasi OpenVINO Selesai', 'desc' => 'Model YOLOv8 kini dapat diekspor ke format OpenVINO IR untuk inferensi yang dioptimalkan pada CPU, GPU, dan VPU Intel.', 'tag' => 'update', 'tagLabel' => 'Pembaruan', 'tagColor' => '#7C3AED'],
                    ['icon' => 'upload', 'iconBg' => 'rgba(124,58,237,0.1)', 'iconColor' => '#7C3AED', 'date' => '12 Mei 2026', 'title' => 'Upload &amp; Pemrosesan Batch', 'desc' => 'Fitur pemrosesan batch baru memungkinkan upload dan analisis beberapa gambar jalan secara bersamaan.', 'tag' => 'release', 'tagLabel' => 'Rilis', 'tagColor' => '#3B82F6'],
                ];
                foreach ($articles as $a): ?>
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-6 cursor-pointer transition-all duration-300 hover:border-[rgba(29,78,216,0.2)] hover:shadow-glow hover:-translate-y-1 flex flex-col">
                    <div class="w-[42px] h-[42px] rounded-lg flex items-center justify-center mb-3.5 shrink-0" style="background:<?= $a['iconBg'] ?>;color:<?= $a['iconColor'] ?>">
                        <i data-lucide="<?= $a['icon'] ?>" style="width:20px;height:20px;"></i>
                    </div>
                    <div class="text-[10px] font-semibold text-[#94A3B8] uppercase tracking-wide mb-1.5"><?= $a['date'] ?></div>
                    <h3 class="font-heading text-[16px] font-bold text-[#0F172A] mb-1.5 leading-snug"><?= $a['title'] ?></h3>
                    <p class="text-[12px] text-[#475569] leading-relaxed mb-3.5 flex-1"><?= $a['desc'] ?></p>
                    <span class="self-start px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase" style="background:<?= $a['tagColor'] ?>1a;color:<?= $a['tagColor'] ?>"><?= $a['tagLabel'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Newsletter -->
            <div class="mt-7 bg-white rounded-xl border border-[#E2E8F0] shadow-card p-9 text-center">
                <i data-lucide="mail" style="width:28px;height:28px;color:#3B82F6;margin:0 auto 10px;display:block;"></i>
                <h3 class="font-heading text-[18px] font-bold text-[#0F172A] mb-2" data-i18n="news.stayUpdated">Tetap Terupdate</h3>
                <p class="text-[13px] text-[#475569] mb-5" data-i18n="news.nlDesc">Dapatkan berita, tutorial, dan pembaruan RoKenAI terbaru di kotak masuk Anda.</p>
                <form class="flex gap-2.5 max-w-[420px] mx-auto max-sm:flex-col" onsubmit="handleSubscribe(event)">
                    <input type="email" id="nlEmail" required
                           data-i18n-placeholder="news.nlPlaceholder"
                           placeholder="Masukkan alamat email"
                           class="flex-1 px-4 py-2.5 rounded-lg bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]">
                    <button type="submit" data-i18n="news.subscribe"
                            class="px-6 py-2.5 rounded-lg border-none bg-[#1D4ED8] text-white font-semibold text-[13px] cursor-pointer shadow-[0_4px_12px_rgba(29,78,216,0.2)] transition-all duration-200 hover:bg-[#3B82F6] hover:-translate-y-0.5">
                        Langganan
                    </button>
                </form>
            </div>

        </main>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        lucide.createIcons();
        function handleSubscribe(e) {
            e.preventDefault();
            const email = document.getElementById('nlEmail').value;
            alert('Berhasil berlangganan dengan email: ' + email + ' (Demo mode)');
            e.target.reset();
        }
    </script>
</body>
</html>
