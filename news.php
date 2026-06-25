<?php session_name('RoKenAI'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | News & Updates</title>
    <?php include 'partials/link.php'; ?>
    <style>
        .news-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== Featured Article ===== */
        .featured-article {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            border: 1px solid rgba(51, 65, 85, 0.3);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            margin-bottom: 28px;
            display: grid;
            grid-template-columns: 1fr;
            transition: all 0.3s ease;
        }
        .featured-article:hover {
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
            border-color: rgba(37, 99, 235, 0.2);
        }
        @media (min-width: 768px) { .featured-article { grid-template-columns: 1fr 1fr; } }

        .featured-article .f-image {
            min-height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(37,99,235,0.04), rgba(124,58,237,0.06));
            position: relative;
            overflow: hidden;
        }
        .featured-article .f-image i { width: 80px; height: 80px; color: rgba(37, 99, 235, 0.08); }

        .featured-article .f-content {
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .featured-article .f-content .f-date {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .featured-article .f-content h2 {
            font-family: var(--font-heading);
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }
        .featured-article .f-content p {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 14px;
        }
        .featured-article .f-content .read-more {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-light);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .featured-article .f-content .read-more:hover { gap: 10px; color: var(--primary); }
        .featured-article .f-content .read-more i { width: 16px; height: 16px; }

        /* ===== News Grid ===== */
        .news-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        @media (min-width: 640px) { .news-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .news-grid { grid-template-columns: repeat(3, 1fr); } }

        .n-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(51, 65, 85, 0.3);
            box-shadow: var(--shadow-card);
            padding: 24px;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .n-card:hover {
            border-color: rgba(37, 99, 235, 0.2);
            box-shadow: var(--shadow-glow);
            transform: translateY(-3px);
        }
        .n-card .n-icon {
            width: 42px; height: 42px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }
        .n-card .n-icon i { width: 20px; height: 20px; }
        .n-card .n-date {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        .n-card h3 {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
            line-height: 1.4;
        }
        .n-card p {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 12px;
        }
        .n-card .n-tag {
            display: inline-block;
            padding: 2px 10px;
            border-radius: var(--radius-full);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .n-tag.release { background: rgba(37,99,235,0.1); color: var(--primary-light); }
        .n-tag.update { background: rgba(124,58,237,0.1); color: var(--purple); }
        .n-tag.event { background: rgba(34,197,94,0.1); color: var(--success); }
        .n-tag.tutorial { background: rgba(239,68,68,0.1); color: var(--danger); }

        /* ===== Newsletter ===== */
        .newsletter-card {
            margin-top: 28px;
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            border: 1px solid rgba(51, 65, 85, 0.3);
            box-shadow: var(--shadow-md);
            padding: 36px;
            text-align: center;
        }
        .newsletter-card h3 { font-family: var(--font-heading); font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .newsletter-card p { font-size: 13px; color: var(--text-secondary); margin-bottom: 18px; }

        .newsletter-card .nl-form {
            display: flex;
            gap: 10px;
            max-width: 420px;
            margin: 0 auto;
        }
        .newsletter-card .nl-form input {
            flex: 1;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(51, 65, 85, 0.3);
            color: var(--text-primary);
            font-size: 13px;
            font-family: var(--font-body);
            outline: none;
            transition: all 0.2s ease;
        }
        .newsletter-card .nl-form input:focus {
            border-color: rgba(37, 99, 235, 0.4);
        }
        .newsletter-card .nl-form button {
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            border: none;
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: var(--font-body);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .newsletter-card .nl-form button:hover {
            background: #1D4ED8;
            transform: translateY(-1px);
        }
        @media (max-width: 480px) {
            .news-page { padding: 16px; }
            .newsletter-card .nl-form { flex-direction: column; }
            .featured-article .f-content { padding: 20px; }
            .newsletter-card { padding: 24px; }
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div id="content-wrapper">
        <main class="news-page page-enter">
            <div class="page-heading">
                <h1><span class="gradient-text">Berita &amp; Pembaruan</span></h1>
                <p>Ikuti perkembangan fitur RoKenAI terbaru, terobosan penelitian, dan acara komunitas.</p>
            </div>

            <!-- Featured -->
            <div class="featured-article">
                <div class="f-image">
                    <i data-lucide="sparkles"></i>
                    <span class="badge" style="position:absolute;top:16px;left:16px;">Unggulan</span>
                </div>
                <div class="f-content">
                    <div class="f-date"><i data-lucide="calendar" style="width:14px;height:14px;"></i> 20 Juni 2026</div>
                    <h2>YOLOv8 + OpenVINO: Inferensi 3x Lebih Cepat</h2>
                    <p>Kami merilis pembaruan performa besar. Dengan mengonversi model YOLOv8 ke format OpenVINO, kecepatan inferensi meningkat hingga 300% pada hardware yang didukung.</p>
                    <a href="chat.php?prompt=Tell+me+about+the+YOLOv8+OpenVINO+performance+update" class="read-more">
                        Baca selengkapnya <i data-lucide="arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Grid -->
            <div class="news-grid">
                <div class="n-card">
                    <div class="n-icon" style="background:rgba(37,99,235,0.12);color:var(--primary-light);"><i data-lucide="rocket"></i></div>
                    <div class="n-date">15 Juni 2026</div>
                    <h3>RoKenAI v2.0 Launch</h3>
                    <p>Pembaruan terbesar kami — UI baru, ruang obrolan AI, pipeline deteksi real-time, dan pembuatan laporan dengan ekspor PDF.</p>
                    <span class="n-tag release">Rilis</span>
                </div>
                <div class="n-card">
                    <div class="n-icon" style="background:rgba(124,58,237,0.12);color:var(--purple);"><i data-lucide="database"></i></div>
                    <div class="n-date">10 Juni 2026</div>
                    <h3>Dataset Kerusakan Jalan v2</h3>
                    <p>Dataset diperluas dengan 15.000+ gambar jalan beranotasi mencakup 8 kategori kerusakan. Termasuk kondisi malam dan permukaan basah.</p>
                    <span class="n-tag update">Pembaruan</span>
                </div>
                <div class="n-card">
                    <div class="n-icon" style="background:rgba(34,197,94,0.12);color:var(--success);"><i data-lucide="users"></i></div>
                    <div class="n-date">5 Juni 2026</div>
                    <h3>Webinar Komunitas: Road AI</h3>
                    <p>Ikuti webinar kami pada 12 Juli untuk diskusi praktik terbaik dalam penerapan sistem inspeksi jalan berbasis YOLOv8.</p>
                    <span class="n-tag event">Acara</span>
                </div>
                <div class="n-card">
                    <div class="n-icon" style="background:rgba(239,68,68,0.12);color:var(--danger);"><i data-lucide="book-open"></i></div>
                    <div class="n-date">28 Mei 2026</div>
                    <h3>Tutorial Baru: Training YOLOv8</h3>
                    <p>Panduan langkah demi langkah mencakup persiapan dataset, konfigurasi YOLOv8, pipeline pelatihan, dan evaluasi model.</p>
                    <span class="n-tag tutorial">Tutorial</span>
                </div>
                <div class="n-card">
                    <div class="n-icon" style="background:rgba(37,99,235,0.12);color:var(--primary-light);"><i data-lucide="cpu"></i></div>
                    <div class="n-date">20 Mei 2026</div>
                    <h3>Integrasi OpenVINO Selesai</h3>
                    <p>Model YOLOv8 kini dapat diekspor ke format OpenVINO IR untuk inferensi yang dioptimalkan pada CPU, GPU, dan VPU Intel.</p>
                    <span class="n-tag update">Pembaruan</span>
                </div>
                <div class="n-card">
                    <div class="n-icon" style="background:rgba(124,58,237,0.12);color:var(--purple);"><i data-lucide="upload"></i></div>
                    <div class="n-date">12 Mei 2026</div>
                    <h3>Upload &amp; Pemrosesan Batch</h3>
                    <p>Fitur pemrosesan batch baru memungkinkan upload dan analisis beberapa gambar jalan secara bersamaan. Mendukung arsip ZIP.</p>
                    <span class="n-tag release">Rilis</span>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="newsletter-card">
                <i data-lucide="mail" style="width:28px;height:28px;color:var(--primary-light);margin-bottom:10px;"></i>
                <h3>Tetap Terupdate</h3>
                <p>Dapatkan berita, tutorial, dan pembaruan RoKenAI terbaru di kotak masuk Anda.</p>
                <form class="nl-form" onsubmit="alert('Berhasil berlangganan! (Demo)'); return false;">
                    <input type="email" placeholder="Masukkan alamat email" required>
                    <button type="submit">Langganan</button>
                </form>
            </div>
        </main>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
