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
           RoKenAI — Landing Page (sesuai desain.md 5.1)
           ================================================================ */

        .landing {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            display: flex;
            flex-direction: column;
            gap: 40px;
            align-items: center;
            padding: 32px 0 20px;
        }
        @media (min-width: 1024px) {
            .hero-section {
                flex-direction: row;
                align-items: center;
                gap: 60px;
            }
            .hero-section .col-left { flex: 1.1; }
            .hero-section .col-right { flex: 0.9; }
        }

        .hero-content h1 {
            font-family: var(--font-heading);
            font-size: clamp(32px, 5vw, 44px);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.1;
            color: var(--ink-900);
            margin-bottom: 16px;
        }
        .hero-content h1 .highlight {
            color: var(--primary-700);
        }
        .hero-content .hero-desc {
            font-size: 15px;
            color: var(--ink-600);
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 28px;
        }

        .hero-badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
        }

        .hero-stats {
            display: flex;
            gap: 24px;
            margin-top: 32px;
        }
        .hero-stat {
            text-align: center;
        }
        .hero-stat .hs-num {
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-700);
        }
        .hero-stat .hs-label {
            font-size: 12px;
            color: var(--ink-600);
        }

        /* ===== Hero Image Placeholder ===== */
        .hero-image-wrap {
            width: 100%;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--primary-100);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-lg);
            aspect-ratio: 4/3;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .hero-image-wrap .hi-placeholder {
            text-align: center;
            color: var(--primary-700);
        }
        .hero-image-wrap .hi-placeholder i {
            width: 64px;
            height: 64px;
            margin-bottom: 12px;
        }
        .hero-image-wrap .hi-placeholder p {
            font-size: 14px;
            font-weight: 500;
        }
        /* Bounding box overlay simulasi */
        .hero-image-wrap .bbox-overlay {
            position: absolute;
            border: 2px solid var(--marka-400);
            background: rgba(250, 204, 21, 0.1);
            border-radius: 4px;
            pointer-events: none;
        }
        .hero-image-wrap .bbox-overlay.b1 {
            top: 30%; left: 15%; width: 35%; height: 40%;
        }
        .hero-image-wrap .bbox-overlay.b2 {
            top: 50%; right: 20%; width: 25%; height: 30%;
        }
        .hero-image-wrap .bbox-label {
            position: absolute;
            background: var(--marka-400);
            color: #0F172A;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: var(--font-mono);
            pointer-events: none;
        }
        .hero-image-wrap .bbox-label.l1 { top: calc(30% - 20px); left: 15%; }
        .hero-image-wrap .bbox-label.l2 { top: calc(50% - 20px); right: 20%; }

        /* ===== CARA KERJA (3 Langkah) ===== */
        .how-it-works {
            margin-top: 60px;
            text-align: center;
        }
        .how-it-works h2 {
            font-family: var(--font-heading);
            font-size: 28px;
            font-weight: 700;
            color: var(--ink-900);
            margin-bottom: 8px;
        }
        .how-it-works > p {
            color: var(--ink-600);
            margin-bottom: 40px;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        @media (min-width: 640px) { .steps-grid { grid-template-columns: repeat(3, 1fr); } }

        .step-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            padding: 32px 24px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .step-card:hover {
            border-color: var(--primary-100);
            box-shadow: var(--shadow-glow);
            transform: translateY(-3px);
        }
        .step-card .s-icon {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-lg);
            background: var(--primary-100);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--primary-700);
        }
        .step-card .s-icon i { width: 28px; height: 28px; }
        .step-card .s-num {
            font-family: var(--font-heading);
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-500);
            margin-bottom: 4px;
        }
        .step-card h3 {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 600;
            color: var(--ink-900);
            margin-bottom: 6px;
        }
        .step-card p {
            font-size: 13px;
            color: var(--ink-600);
            line-height: 1.6;
        }

        /* ===== STATISTIK ===== */
        .stats-section {
            margin-top: 60px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        @media (max-width: 640px) { .stats-section { grid-template-columns: 1fr; } }

        .stat-card-landing {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            box-shadow: var(--shadow-card);
            padding: 28px 24px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .stat-card-landing:hover {
            border-color: var(--primary-100);
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
        }
        .stat-card-landing .s-num {
            font-family: var(--font-heading);
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-700);
        }
        .stat-card-landing .s-label {
            font-size: 13px;
            color: var(--ink-600);
            margin-top: 4px;
        }

        /* ===== FITUR ===== */
        .features-section {
            margin-top: 60px;
        }
        .features-section h2 {
            font-family: var(--font-heading);
            font-size: 28px;
            font-weight: 700;
            color: var(--ink-900);
            text-align: center;
            margin-bottom: 32px;
        }
        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        @media (min-width: 640px) { .features-grid { grid-template-columns: repeat(3, 1fr); } }

        /* ===== LAPORAN TERBARU ===== */
        .recent-section {
            margin-top: 60px;
        }
        .recent-section .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .recent-section .section-header h3 {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 600;
            color: var(--ink-900);
        }
        .recent-section .section-header a {
            font-size: 13px;
            color: var(--primary-500);
            text-decoration: none;
        }
        .recent-section .section-header a:hover {
            color: var(--primary-700);
        }

        .recent-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .recent-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--line-200);
            transition: all 0.2s ease;
        }
        .recent-item:hover {
            border-color: var(--primary-100);
            background: #FAFBFC;
        }
        .recent-item .r-icon {
            width: 40px; height: 40px; min-width: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .recent-item .r-icon i { width: 18px; height: 18px; }
        .recent-item .r-info { flex: 1; }
        .recent-item .r-title { font-size: 14px; font-weight: 600; color: var(--ink-900); }
        .recent-item .r-meta { font-size: 12px; color: #94A3B8; }

        @media (max-width: 480px) {
            .landing { padding: 16px; }
            .hero-stat .hs-num { font-size: 20px; }
            .hero-stats { gap: 16px; flex-wrap: wrap; }
        }
    </style>
</head>
<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <main class="landing page-enter">

            <!-- ===== HERO SECTION (desain.md 5.1) ===== -->
            <div class="hero-section">
                <!-- LEFT COLUMN -->
                <div class="col-left">
                    <div class="hero-content">
                        <div class="hero-badge-row">
                            <span class="badge">AI Deteksi Jalan Rusak v2.0</span>
                        </div>

                        <h1>
                            Lihat Jalan Rusak?<br>
                            <span class="highlight">Laporkan dalam Hitungan Detik</span>
                        </h1>

                        <p class="hero-desc">
                            RoKenAI menggunakan teknologi Computer Vision (YOLOv8) untuk mendeteksi dan mengklasifikasikan 
                            kerusakan jalan secara otomatis. Cukup foto, AI kami yang verifikasi.
                        </p>

                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            <a href="upload.php" class="btn-primary">
                                <i data-lucide="camera"></i>
                                Lapor Sekarang
                            </a>
                            <a href="#how-it-works" class="btn-secondary">
                                <i data-lucide="info"></i>
                                Cara Kerja
                            </a>
                        </div>

                        <div class="hero-stats">
                            <div class="hero-stat">
                                <div class="hs-num">1.200+</div>
                                <div class="hs-label">Laporan Ditindaklanjuti</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hs-num">94%</div>
                                <div class="hs-label">Akurasi Deteksi AI</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hs-num">12ms</div>
                                <div class="hs-label">Kecepatan Inferensi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN — Foto jalan dengan bounding box AI overlay -->
                <div class="col-right">
                    <div class="hero-image-wrap">
                        <div class="hi-placeholder">
                            <i data-lucide="scan-line"></i>
                            <p>Foto Jalan Rusak + Deteksi AI</p>
                            <span style="font-size:12px;color:#94A3B8;">(unggah foto untuk melihat hasil deteksi)</span>
                        </div>
                        <!-- Simulasi bounding box overlay (contoh visual deteksi AI) -->
                        <div class="bbox-overlay b1"></div>
                        <div class="bbox-label l1">Lubang Jalan 92%</div>
                        <div class="bbox-overlay b2"></div>
                        <div class="bbox-label l2">Retak 87%</div>
                    </div>
                </div>
            </div>

            <!-- ===== CARA KERJA (3 langkah) ===== -->
            <div class="how-it-works" id="how-it-works">
                <h2>Bagaimana Cara Kerjanya?</h2>
                <p>Tiga langkah mudah untuk melaporkan kerusakan jalan</p>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="s-icon"><i data-lucide="camera"></i></div>
                        <div class="s-num">Langkah 1</div>
                        <h3>Foto Jalan Rusak</h3>
                        <p>Ambil foto jalan yang rusak menggunakan kamera HP atau upload dari galeri. Pastikan foto jelas dan terkena cahaya.</p>
                    </div>
                    <div class="step-card">
                        <div class="s-icon"><i data-lucide="sparkles"></i></div>
                        <div class="s-num">Langkah 2</div>
                        <h3>Deteksi Otomatis oleh AI</h3>
                        <p>Model YOLOv8 kami akan mendeteksi jenis kerusakan (lubang, retak, bergelombang) dan tingkat keparahannya secara otomatis.</p>
                    </div>
                    <div class="step-card">
                        <div class="s-icon"><i data-lucide="check-circle-2"></i></div>
                        <div class="s-num">Langkah 3</div>
                        <h3>Ditindaklanjuti</h3>
                        <p>Laporan masuk ke dashboard admin untuk diverifikasi dan ditindaklanjuti. Pantau status perbaikan secara real-time.</p>
                    </div>
                </div>
            </div>

            <!-- ===== STATISTIK ===== -->
            <div class="stats-section">
                <div class="stat-card-landing">
                    <div class="s-num">1.247</div>
                    <div class="s-label">Total Laporan</div>
                </div>
                <div class="stat-card-landing">
                    <div class="s-num">892</div>
                    <div class="s-label">Selesai Diperbaiki</div>
                </div>
                <div class="stat-card-landing">
                    <div class="s-num">4.2</div>
                    <div class="s-label">Rata-rata Respons (hari)</div>
                </div>
            </div>

            <!-- ===== FITUR-FITUR ===== -->
            <div class="features-section">
                <h2>Fitur RoKenAI</h2>
                <div class="features-grid">
                    <div class="step-card">
                        <div class="s-icon" style="background:rgba(59,130,246,0.1);color:var(--primary-500);">
                            <i data-lucide="bot"></i>
                        </div>
                        <h3>Tanya AI</h3>
                        <p>Konsultasi dengan asisten AI tentang jenis kerusakan, prioritas perbaikan, dan rekomendasi penanganan.</p>
                        <a href="chat.php" class="btn-ghost" style="margin-top:8px;">
                            Mulai Chat <i data-lucide="arrow-right"></i>
                        </a>
                    </div>
                    <div class="step-card">
                        <div class="s-icon" style="background:rgba(59,130,246,0.1);color:var(--primary-500);">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <h3>Lacak Status Perbaikan</h3>
                        <p>Pantau perkembangan laporan Anda melalui "Garis Jalan" — lihat progres dari Dilaporkan hingga Selesai Diperbaiki.</p>
                        <a href="profile.php" class="btn-ghost" style="margin-top:8px;">
                            Lihat Riwayat <i data-lucide="arrow-right"></i>
                        </a>
                    </div>
                    <div class="step-card">
                        <div class="s-icon" style="background:rgba(59,130,246,0.1);color:var(--primary-500);">
                            <i data-lucide="bell"></i>
                        </div>
                        <h3>Notifikasi Real-time</h3>
                        <p>Dapatkan pemberitahuan setiap kali status laporan berubah — dari diverifikasi hingga selesai diperbaiki.</p>
                    </div>
                </div>
            </div>

            <!-- ===== LAPORAN TERBARU ===== -->
            <div class="recent-section">
                <div class="section-header">
                    <h3>Laporan Terbaru</h3>
                    <a href="profile.php">Lihat semua &rarr;</a>
                </div>
                <div class="recent-list">
                    <div class="recent-item">
                        <div class="r-icon" style="background:rgba(59,130,246,0.1);color:var(--primary-700);">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="r-info">
                            <div class="r-title">Jl. Ahmad Yani — Lubang Jalan</div>
                            <div class="r-meta">2 jam lalu &bull; ID: #RK-2026-0421</div>
                        </div>
                        <span class="status-badge selesai"><span class="s-dot"></span> Selesai</span>
                    </div>
                    <div class="recent-item">
                        <div class="r-icon" style="background:rgba(245,158,11,0.1);color:var(--status-warning);">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="r-info">
                            <div class="r-title">Jl. Diponegoro — Retak Jalan</div>
                            <div class="r-meta">5 jam lalu &bull; ID: #RK-2026-0420</div>
                        </div>
                        <span class="status-badge diverifikasi"><span class="s-dot"></span> Diverifikasi</span>
                    </div>
                    <div class="recent-item">
                        <div class="r-icon" style="background:rgba(37,99,235,0.1);color:var(--status-progress);">
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
