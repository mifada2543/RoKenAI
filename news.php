<?php session_name('RoKenAI'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Berita &amp; Pembaruan</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Berita & Pembaruan (News)
           ================================================================ */

        .news-page {
            max-width: 1100px; margin: 0 auto;
            padding: 24px 24px 56px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== Featured Article ===== */
        .featured-article {
            background: #fff; border-radius: 16px;
            border: 1.5px solid #E2E8F0; box-shadow: var(--shadow-card);
            overflow: hidden; margin-bottom: 28px;
            display: grid; grid-template-columns: 1fr;
            transition: all 0.3s ease;
        }
        .featured-article:hover {
            box-shadow: var(--shadow-glow); transform: translateY(-2px);
            border-color: rgba(29,78,216,0.2);
        }
        @media (min-width: 768px) { .featured-article { grid-template-columns: 1fr 1fr; } }

        /* Sisi kiri featured — gambar Home.png sebagai ilustrasi artikel unggulan */
        .featured-article .f-image {
            min-height: 260px; display: flex; align-items: center;
            justify-content: center; position: relative; overflow: hidden;
            background: linear-gradient(135deg, rgba(29,78,216,0.04), rgba(124,58,237,0.06));
        }
        .featured-article .f-image img {
            width: 100%; height: 100%; object-fit: cover;
            opacity: 0.85; transition: opacity 0.3s ease;
        }
        .featured-article:hover .f-image img { opacity: 1; }

        .featured-article .f-content {
            padding: 32px; display: flex;
            flex-direction: column; justify-content: center;
        }
        .f-date {
            font-size: 11px; color: #94A3B8; font-weight: 500;
            margin-bottom: 8px; display: flex; align-items: center; gap: 6px;
        }
        .f-date i { width: 14px; height: 14px; }
        .f-content h2 {
            font-family: var(--font-heading); font-size: 20px;
            font-weight: 700; color: #0F172A; margin-bottom: 10px; line-height: 1.35;
        }
        .f-content > p {
            font-size: 13px; color: #475569; line-height: 1.7; margin-bottom: 16px;
        }
        .read-more {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; color: #3B82F6;
            text-decoration: none; transition: all 0.2s ease;
        }
        .read-more:hover { gap: 10px; color: #1D4ED8; }
        .read-more i { width: 16px; height: 16px; }

        /* ===== News Grid ===== */
        .news-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media (min-width: 640px)  { .news-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .news-grid { grid-template-columns: repeat(3, 1fr); } }

        /* ===== News Card ===== */
        .n-card {
            background: #fff; border-radius: 12px;
            border: 1.5px solid #E2E8F0; box-shadow: var(--shadow-card);
            padding: 24px; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            display: flex; flex-direction: column;
        }
        .n-card:hover {
            border-color: rgba(29,78,216,0.2);
            box-shadow: var(--shadow-glow); transform: translateY(-3px);
        }
        .n-icon {
            width: 42px; height: 42px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px; flex-shrink: 0;
        }
        .n-icon i { width: 20px; height: 20px; }
        .n-date {
            font-size: 10px; font-weight: 600; color: #94A3B8;
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;
        }
        .n-card h3 {
            font-family: var(--font-heading); font-size: 16px; font-weight: 700;
            color: #0F172A; margin-bottom: 6px; line-height: 1.4;
        }
        .n-card > p {
            font-size: 12px; color: #475569;
            line-height: 1.6; margin-bottom: 14px; flex: 1;
        }
        .n-tag {
            display: inline-block; padding: 2px 10px;
            border-radius: 9999px; font-size: 10px; font-weight: 600;
            text-transform: uppercase; align-self: flex-start;
        }
        .n-tag.release  { background: rgba(29,78,216,0.1);  color: #3B82F6; }
        .n-tag.update   { background: rgba(124,58,237,0.1); color: #7C3AED; }
        .n-tag.event    { background: rgba(22,163,74,0.1);  color: #16A34A; }
        .n-tag.tutorial { background: rgba(220,38,38,0.1);  color: #DC2626; }

        /* ===== Newsletter Card ===== */
        .newsletter-card {
            margin-top: 28px; background: #fff; border-radius: 16px;
            border: 1.5px solid #E2E8F0; box-shadow: var(--shadow-card);
            padding: 36px; text-align: center;
        }
        .newsletter-card .nl-icon { width: 28px; height: 28px; color: #3B82F6; margin-bottom: 10px; }
        .newsletter-card h3 {
            font-family: var(--font-heading); font-size: 18px;
            font-weight: 700; color: #0F172A; margin-bottom: 8px;
        }
        .newsletter-card > p {
            font-size: 13px; color: #475569; margin-bottom: 20px;
        }
        .nl-form {
            display: flex; gap: 10px; max-width: 420px; margin: 0 auto;
        }
        .nl-form input {
            flex: 1; padding: 10px 16px; border-radius: 8px;
            background: #F8FAFC; border: 1.5px solid #E2E8F0;
            color: #0F172A; font-size: 13px; font-family: var(--font-body);
            outline: none; transition: all 0.2s ease;
        }
        .nl-form input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .nl-form button {
            padding: 10px 24px; border-radius: 8px; border: none;
            background: #1D4ED8; color: #fff; font-weight: 600;
            font-size: 13px; cursor: pointer; font-family: var(--font-body);
            box-shadow: 0 4px 12px rgba(29,78,216,0.2);
            transition: all 0.2s ease;
        }
        .nl-form button:hover { background: #3B82F6; transform: translateY(-1px); }

        /* ===== Featured badge ===== */
        .feat-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 14px; border-radius: 9999px;
            background: #DBEAFE; border: 1px solid rgba(29,78,216,0.15);
            font-size: 11px; font-weight: 600; color: #1D4ED8;
            position: absolute; top: 14px; left: 14px;
        }

        @media (max-width: 480px) {
            .news-page { padding: 16px; }
            .nl-form { flex-direction: column; }
            .f-content { padding: 20px; }
            .newsletter-card { padding: 24px; }
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <main class="news-page page-enter">

            <!-- Page Heading -->
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-ink-900 mb-2" style="font-size:clamp(24px,3.5vw,32px);letter-spacing:-0.02em;">
                    <span class="gradient-text" data-i18n="news.title">Berita &amp; Pembaruan</span>
                </h1>
                <p class="text-[14px] text-ink-600 max-w-md mx-auto leading-6" data-i18n="news.desc">
                    Ikuti perkembangan fitur RoKenAI terbaru, terobosan penelitian, dan acara komunitas.
                </p>
            </div>

            <!-- ===== Featured Article — pakai Home.png sebagai hero image ===== -->
            <div class="featured-article">
                <div class="f-image">
                    <!-- Gunakan Home.png sebagai ilustrasi artikel unggulan -->
                    <img src="assets/img/Home.png" alt="YOLOv8 OpenVINO Update">
                    <!-- Badge Unggulan -->
                    <span class="feat-badge">
                        <i data-lucide="star" style="width:12px;height:12px;"></i>
                        <span data-i18n="news.featured">Unggulan</span>
                    </span>
                </div>
                <div class="f-content">
                    <div class="f-date">
                        <i data-lucide="calendar"></i>
                        20 Juni 2026
                    </div>
                    <h2>YOLOv8 + OpenVINO: Inferensi 3x Lebih Cepat</h2>
                    <p>Kami merilis pembaruan performa besar. Dengan mengonversi model YOLOv8 ke format OpenVINO, kecepatan inferensi meningkat hingga 300% pada hardware yang didukung.</p>
                    <a href="chat.php?prompt=Tell+me+about+the+YOLOv8+OpenVINO+performance+update" class="read-more">
                        <span data-i18n="news.readMore">Baca selengkapnya</span>
                        <i data-lucide="arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- ===== News Grid ===== -->
            <div class="news-grid">

                <div class="n-card">
                    <div class="n-icon" style="background:rgba(29,78,216,0.1);color:#3B82F6;">
                        <i data-lucide="rocket"></i>
                    </div>
                    <div class="n-date">15 Juni 2026</div>
                    <h3>RoKenAI v2.0 Launch</h3>
                    <p>Pembaruan terbesar kami — UI baru, ruang obrolan AI, pipeline deteksi real-time, dan pembuatan laporan dengan ekspor PDF.</p>
                    <span class="n-tag release">Rilis</span>
                </div>

                <div class="n-card">
                    <div class="n-icon" style="background:rgba(124,58,237,0.1);color:#7C3AED;">
                        <i data-lucide="database"></i>
                    </div>
                    <div class="n-date">10 Juni 2026</div>
                    <h3>Dataset Kerusakan Jalan v2</h3>
                    <p>Dataset diperluas dengan 15.000+ gambar jalan beranotasi mencakup 8 kategori kerusakan. Termasuk kondisi malam dan permukaan basah.</p>
                    <span class="n-tag update">Pembaruan</span>
                </div>

                <div class="n-card">
                    <div class="n-icon" style="background:rgba(22,163,74,0.1);color:#16A34A;">
                        <i data-lucide="users"></i>
                    </div>
                    <div class="n-date">5 Juni 2026</div>
                    <h3>Webinar Komunitas: Road AI</h3>
                    <p>Ikuti webinar kami pada 12 Juli untuk diskusi praktik terbaik dalam penerapan sistem inspeksi jalan berbasis YOLOv8.</p>
                    <span class="n-tag event">Acara</span>
                </div>

                <div class="n-card">
                    <div class="n-icon" style="background:rgba(220,38,38,0.1);color:#DC2626;">
                        <i data-lucide="book-open"></i>
                    </div>
                    <div class="n-date">28 Mei 2026</div>
                    <h3>Tutorial Baru: Training YOLOv8</h3>
                    <p>Panduan langkah demi langkah mencakup persiapan dataset, konfigurasi YOLOv8, pipeline pelatihan, dan evaluasi model.</p>
                    <span class="n-tag tutorial">Tutorial</span>
                </div>

                <div class="n-card">
                    <div class="n-icon" style="background:rgba(29,78,216,0.1);color:#3B82F6;">
                        <i data-lucide="cpu"></i>
                    </div>
                    <div class="n-date">20 Mei 2026</div>
                    <h3>Integrasi OpenVINO Selesai</h3>
                    <p>Model YOLOv8 kini dapat diekspor ke format OpenVINO IR untuk inferensi yang dioptimalkan pada CPU, GPU, dan VPU Intel.</p>
                    <span class="n-tag update">Pembaruan</span>
                </div>

                <div class="n-card">
                    <div class="n-icon" style="background:rgba(124,58,237,0.1);color:#7C3AED;">
                        <i data-lucide="upload"></i>
                    </div>
                    <div class="n-date">12 Mei 2026</div>
                    <h3>Upload &amp; Pemrosesan Batch</h3>
                    <p>Fitur pemrosesan batch baru memungkinkan upload dan analisis beberapa gambar jalan secara bersamaan. Mendukung arsip ZIP.</p>
                    <span class="n-tag release">Rilis</span>
                </div>

            </div>

            <!-- ===== Newsletter ===== -->
            <div class="newsletter-card">
                <i data-lucide="mail" class="nl-icon" style="margin:0 auto 10px;display:block;"></i>
                <h3 data-i18n="news.stayUpdated">Tetap Terupdate</h3>
                <p data-i18n="news.nlDesc">Dapatkan berita, tutorial, dan pembaruan RoKenAI terbaru di kotak masuk Anda.</p>
                <form class="nl-form" onsubmit="handleSubscribe(event)">
                    <input type="email" id="nlEmail"
                           data-i18n-placeholder="news.nlPlaceholder"
                           placeholder="Masukkan alamat email" required>
                    <button type="submit" data-i18n="news.subscribe">Langganan</button>
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
            alert('Berhasil berlangganan dengan email: ' + email + '\n(Demo mode)');
            e.target.reset();
        }
    </script>
</body>
</html>
