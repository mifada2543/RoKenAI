<?php session_name('RoKenAI'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Platform Pelaporan Jalan Rusak berbasis AI</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Beranda / Landing Page
           Menggunakan Tailwind utilities + CSS untuk hal spesifik
           ================================================================ */

        .landing { max-width: 1200px; margin: 0 auto; padding: 24px 24px 64px; }

        /* ===== Hero ===== */
        .hero-section {
            display: flex; flex-direction: column;
            gap: 40px; align-items: center; padding: 32px 0 20px;
        }
        @media (min-width: 1024px) {
            .hero-section { flex-direction: row; gap: 60px; }
            .hero-section .col-left  { flex: 1.1; }
            .hero-section .col-right { flex: 0.9; }
        }

        /* Hero image area — menampilkan Home.png */
        .hero-img-wrap {
            width: 100%; border-radius: 16px; overflow: hidden;
            border: 1px solid #E2E8F0; box-shadow: var(--shadow-lg);
            background: #DBEAFE; aspect-ratio: 4/3;
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .hero-img-wrap img {
            width: 100%; height: 100%; object-fit: cover; object-position: top;
            transition: transform 0.4s ease;
        }
        .hero-img-wrap:hover img { transform: scale(1.02); }

        /* AI detection overlay badge di atas foto */
        .hero-ai-badge {
            position: absolute; bottom: 14px; left: 14px;
            display: flex; gap: 8px; flex-wrap: wrap;
        }
        .ai-tag {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 12px; background: var(--marka-400); color: #0F172A;
            border-radius: 8px; font-size: 12px; font-weight: 700;
            font-family: var(--font-mono); box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            animation: fadeInUp 0.6s ease backwards;
        }
        .ai-tag:nth-child(2) { animation-delay: 0.15s; }
        .ai-tag i { width: 13px; height: 13px; }

        /* ===== Steps Grid ===== */
        .steps-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 640px) { .steps-grid { grid-template-columns: repeat(3, 1fr); } }

        .step-card {
            background: #fff; border-radius: 12px;
            border: 1px solid #E2E8F0; box-shadow: var(--shadow-card);
            padding: 28px 22px; text-align: center;
            transition: all 0.3s ease;
        }
        .step-card:hover {
            border-color: #DBEAFE; box-shadow: var(--shadow-glow); transform: translateY(-3px);
        }
        .step-card .s-icon {
            width: 60px; height: 60px; border-radius: 12px;
            background: #DBEAFE; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px; color: #1D4ED8;
        }
        .step-card .s-icon i { width: 26px; height: 26px; }
        .step-card .s-num { font-size: 13px; font-weight: 700; color: #3B82F6; margin-bottom: 4px; font-family: var(--font-heading); }
        .step-card h3 { font-family: var(--font-heading); font-size: 17px; font-weight: 600; color: #0F172A; margin-bottom: 6px; }
        .step-card p { font-size: 13px; color: #475569; line-height: 1.6; }

        /* ===== Stats ===== */
        .stats-section { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 640px) { .stats-section { grid-template-columns: 1fr; } }

        .stat-card {
            background: #fff; border-radius: 12px; border: 1px solid #E2E8F0;
            box-shadow: var(--shadow-card); padding: 26px 22px; text-align: center;
            transition: all 0.3s ease;
        }
        .stat-card:hover { border-color: #DBEAFE; box-shadow: var(--shadow-glow); transform: translateY(-2px); }
        .stat-card .s-num { font-family: var(--font-heading); font-size: 30px; font-weight: 700; color: #1D4ED8; }
        .stat-card .s-label { font-size: 13px; color: #475569; margin-top: 4px; }

        /* ===== Features ===== */
        .features-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media (min-width: 640px) { .features-grid { grid-template-columns: repeat(3, 1fr); } }

        /* Feature card pakai step-card style, ditambah gambar ilustrasi */
        .feat-card {
            background: #fff; border-radius: 12px; border: 1px solid #E2E8F0;
            box-shadow: var(--shadow-card); padding: 28px 22px;
            transition: all 0.3s ease; text-align: center;
        }
        .feat-card:hover { border-color: #DBEAFE; box-shadow: var(--shadow-glow); transform: translateY(-3px); }
        .feat-card .f-icon {
            width: 60px; height: 60px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .feat-card .f-icon i { width: 26px; height: 26px; }
        .feat-card h3 { font-family: var(--font-heading); font-size: 17px; font-weight: 600; color: #0F172A; margin-bottom: 8px; }
        .feat-card p { font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 14px; }
        .feat-card .feat-img {
            width: 100%; border-radius: 8px; overflow: hidden;
            margin-top: 16px; border: 1px solid #E2E8F0;
        }
        .feat-card .feat-img img { width: 100%; height: auto; display: block; object-fit: cover; }

        /* ===== Recent Reports ===== */
        .recent-list { display: flex; flex-direction: column; gap: 8px; }
        .recent-item {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 18px; background: #fff; border-radius: 10px;
            border: 1px solid #E2E8F0; transition: all 0.2s ease;
        }
        .recent-item:hover { border-color: #DBEAFE; background: #FAFBFC; }
        .recent-item .r-icon { width: 40px; height: 40px; min-width: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .recent-item .r-icon i { width: 18px; height: 18px; }
        .recent-item .r-info { flex: 1; }
        .recent-item .r-title { font-size: 14px; font-weight: 600; color: #0F172A; }
        .recent-item .r-meta { font-size: 12px; color: #94A3B8; }

        /* ===== Responsive ===== */
        @media (max-width: 480px) {
            .landing { padding: 16px; }
            .hero-stats { flex-wrap: wrap; }
        }
    </style>
</head>
<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <main class="landing page-enter">

            <!-- ===== HERO ===== -->
            <div class="hero-section">
                <!-- LEFT -->
                <div class="col-left">
                    <div>
                        <!-- Badge -->
                        <div class="flex flex-wrap gap-2 mb-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-light border border-primary/20 text-[11px] font-semibold text-primary">
                                <i data-lucide="cpu" style="width:12px;height:12px;"></i>
                                <span data-i18n="dashboard.badge">AI Deteksi Jalan Rusak v2.0</span>
                            </span>
                        </div>

                        <!-- Heading -->
                        <h1 class="font-heading font-bold text-ink-900 leading-tight mb-4"
                            style="font-size: clamp(28px,4.5vw,44px); letter-spacing:-0.03em;">
                            <span data-i18n="dashboard.h1a">Lihat Jalan Rusak?</span><br>
                            <span class="text-primary-700" data-i18n="dashboard.h1b">Laporkan dalam Hitungan Detik</span>
                        </h1>

                        <p class="text-[15px] text-ink-600 leading-7 max-w-md mb-7"
                           data-i18n="dashboard.desc">
                            RoKenAI menggunakan teknologi Computer Vision (YOLOv8) untuk mendeteksi dan mengklasifikasikan kerusakan jalan secara otomatis. Cukup foto, AI kami yang verifikasi.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex gap-3 flex-wrap mb-8">
                            <a href="upload.php"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-700 text-white text-[14px] font-semibold no-underline shadow-md hover:bg-primary-hover hover:-translate-y-0.5 transition-all duration-200">
                                <i data-lucide="camera" style="width:17px;height:17px;"></i>
                                <span data-i18n="dashboard.cta">Lapor Sekarang</span>
                            </a>
                            <a href="#how-it-works"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border-[1.5px] border-primary-700 text-primary-700 text-[14px] font-semibold no-underline hover:bg-primary-light transition-all duration-200">
                                <i data-lucide="info" style="width:17px;height:17px;"></i>
                                <span data-i18n="dashboard.howCta">Cara Kerja</span>
                            </a>
                        </div>

                        <!-- Stats Row -->
                        <div class="flex gap-6 hero-stats">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary-700 font-heading">1.200+</div>
                                <div class="text-[12px] text-ink-600" data-i18n="dashboard.stat1">Laporan Ditindaklanjuti</div>
                            </div>
                            <div class="w-px bg-line-200"></div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary-700 font-heading">94%</div>
                                <div class="text-[12px] text-ink-600" data-i18n="dashboard.stat2">Akurasi Deteksi AI</div>
                            </div>
                            <div class="w-px bg-line-200"></div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary-700 font-heading">12ms</div>
                                <div class="text-[12px] text-ink-600" data-i18n="dashboard.stat3">Kecepatan Inferensi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT — Foto Beranda dari assets/img/Home.png -->
                <div class="col-right">
                    <div class="hero-img-wrap">
                        <img src="assets/img/Home.png" alt="RoKenAI Dashboard Preview">
                        <!-- AI Detection tags overlay -->
                        <div class="hero-ai-badge">
                            <span class="ai-tag"><i data-lucide="scan"></i> Lubang Jalan — 92%</span>
                            <span class="ai-tag"><i data-lucide="alert-triangle"></i> Retak — 87%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== CARA KERJA ===== -->
            <div class="mt-16" id="how-it-works">
                <div class="text-center mb-10">
                    <h2 class="font-heading text-[26px] font-bold text-ink-900 mb-2" data-i18n="dashboard.howTitle">Bagaimana Cara Kerjanya?</h2>
                    <p class="text-[14px] text-ink-600" data-i18n="dashboard.howDesc">Tiga langkah mudah untuk melaporkan kerusakan jalan</p>
                </div>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="s-icon"><i data-lucide="camera"></i></div>
                        <div class="s-num" data-i18n="dashboard.step1">Langkah 1</div>
                        <h3 data-i18n="dashboard.step1t">Foto Jalan Rusak</h3>
                        <p data-i18n="dashboard.step1d">Ambil foto jalan yang rusak menggunakan kamera HP. Pastikan foto jelas dan terkena cahaya.</p>
                    </div>
                    <div class="step-card">
                        <div class="s-icon"><i data-lucide="sparkles"></i></div>
                        <div class="s-num" data-i18n="dashboard.step2">Langkah 2</div>
                        <h3 data-i18n="dashboard.step2t">Deteksi Otomatis oleh AI</h3>
                        <p data-i18n="dashboard.step2d">Model YOLOv8 kami akan mendeteksi jenis kerusakan dan tingkat keparahannya secara otomatis.</p>
                    </div>
                    <div class="step-card">
                        <div class="s-icon"><i data-lucide="check-circle-2"></i></div>
                        <div class="s-num" data-i18n="dashboard.step3">Langkah 3</div>
                        <h3 data-i18n="dashboard.step3t">Ditindaklanjuti</h3>
                        <p data-i18n="dashboard.step3d">Laporan masuk ke dashboard admin untuk diverifikasi dan ditindaklanjuti. Pantau status perbaikan secara real-time.</p>
                    </div>
                </div>
            </div>

            <!-- ===== STATISTIK ===== -->
            <div class="mt-14">
                <div class="text-center mb-8">
                    <h2 class="font-heading text-[22px] font-bold text-ink-900" data-i18n="dashboard.statsTitle">Statistik Platform</h2>
                </div>
                <div class="stats-section">
                    <div class="stat-card">
                        <div class="s-num">1.247</div>
                        <div class="s-label" data-i18n="dashboard.totalReports">Total Laporan</div>
                    </div>
                    <div class="stat-card">
                        <div class="s-num">892</div>
                        <div class="s-label" data-i18n="dashboard.repaired">Selesai Diperbaiki</div>
                    </div>
                    <div class="stat-card">
                        <div class="s-num">4.2</div>
                        <div class="s-label" data-i18n="dashboard.avgResp">Rata-rata Respons (hari)</div>
                    </div>
                </div>
            </div>

            <!-- ===== FITUR ===== -->
            <div class="mt-14">
                <div class="text-center mb-8">
                    <h2 class="font-heading text-[26px] font-bold text-ink-900" data-i18n="dashboard.featTitle">Fitur RoKenAI</h2>
                </div>
                <div class="features-grid">

                    <!-- Fitur 1: Tanya AI — pakai Chat.png sebagai ilustrasi -->
                    <div class="feat-card">
                        <div class="f-icon" style="background:rgba(59,130,246,0.1);color:#3B82F6;">
                            <i data-lucide="bot"></i>
                        </div>
                        <h3 data-i18n="dashboard.feat1t">Tanya AI</h3>
                        <p data-i18n="dashboard.feat1d">Konsultasi dengan asisten AI tentang jenis kerusakan, prioritas perbaikan, dan rekomendasi penanganan.</p>
                        <a href="chat.php"
                           class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-primary-700 no-underline hover:gap-2.5 transition-all duration-200">
                            <span data-i18n="dashboard.feat1cta">Mulai Chat</span>
                            <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                        </a>
                        <!-- Ilustrasi dari assets/img/Chat.png -->
                        <div class="feat-img">
                            <img src="assets/img/Chat.png" alt="Chat AI">
                        </div>
                    </div>

                    <!-- Fitur 2: Lacak Status — pakai Profile.png -->
                    <div class="feat-card">
                        <div class="f-icon" style="background:rgba(59,130,246,0.1);color:#3B82F6;">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <h3 data-i18n="dashboard.feat2t">Lacak Status Perbaikan</h3>
                        <p data-i18n="dashboard.feat2d">Pantau perkembangan laporan Anda melalui "Garis Jalan" — dari Dilaporkan hingga Selesai Diperbaiki.</p>
                        <a href="profile.php"
                           class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-primary-700 no-underline hover:gap-2.5 transition-all duration-200">
                            <span data-i18n="dashboard.feat2cta">Lihat Riwayat</span>
                            <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                        </a>
                        <!-- Ilustrasi dari assets/img/Profile.png -->
                        <div class="feat-img">
                            <img src="assets/img/Profile.png" alt="Riwayat Laporan">
                        </div>
                    </div>

                    <!-- Fitur 3: Notifikasi — pakai Android mockup -->
                    <div class="feat-card">
                        <div class="f-icon" style="background:rgba(59,130,246,0.1);color:#3B82F6;">
                            <i data-lucide="bell"></i>
                        </div>
                        <h3 data-i18n="dashboard.feat3t">Notifikasi Real-time</h3>
                        <p data-i18n="dashboard.feat3d">Dapatkan pemberitahuan setiap kali status laporan berubah — dari diverifikasi hingga selesai diperbaiki.</p>
                        <!-- Mobile app mockup dari assets/img/Android Compact - 2.png -->
                        <div class="feat-img">
                            <img src="assets/img/Android Compact - 2.png" alt="Mobile App RoKenAI">
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===== LAPORAN TERBARU ===== -->
            <div class="mt-14">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-heading text-[17px] font-semibold text-ink-900" data-i18n="dashboard.recentTitle">Laporan Terbaru</h3>
                    <a href="profile.php" class="text-[13px] text-primary-500 no-underline hover:text-primary-700 transition-colors" data-i18n="dashboard.viewAll">Lihat semua →</a>
                </div>
                <div class="recent-list">
                    <div class="recent-item">
                        <div class="r-icon" style="background:rgba(59,130,246,0.1);color:#1D4ED8;">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="r-info">
                            <div class="r-title">Jl. Ahmad Yani — Lubang Jalan</div>
                            <div class="r-meta">2 jam lalu &bull; ID: #RK-2026-0421</div>
                        </div>
                        <span class="status-badge selesai"><span class="s-dot"></span> Selesai</span>
                    </div>
                    <div class="recent-item">
                        <div class="r-icon" style="background:rgba(245,158,11,0.1);color:#F59E0B;">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="r-info">
                            <div class="r-title">Jl. Diponegoro — Retak Jalan</div>
                            <div class="r-meta">5 jam lalu &bull; ID: #RK-2026-0420</div>
                        </div>
                        <span class="status-badge diverifikasi"><span class="s-dot"></span> Diverifikasi</span>
                    </div>
                    <div class="recent-item">
                        <div class="r-icon" style="background:rgba(37,99,235,0.1);color:#2563EB;">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="r-info">
                            <div class="r-title">Jl. Sudirman — Jalan Bergelombang</div>
                            <div class="r-meta">1 hari lalu &bull; ID: #RK-2026-0419</div>
                        </div>
                        <span class="status-badge diperbaiki"><span class="s-dot"></span> Diperbaiki</span>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
