<?php session_name('RoKenAI'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Dashboard</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ===== Dashboard Layout ===== */
        .dashboard {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== Hero Section ===== */
        .hero-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 100px;
            background: rgba(250, 204, 21, 0.08);
            border: 1px solid rgba(250, 204, 21, 0.15);
            font-size: 12px;
            font-weight: 500;
            color: var(--brand-yellow);
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 768px) {
            .hero-title {
                font-size: 48px;
            }
        }

        .hero-subtitle {
            font-size: 16px;
            color: var(--text-secondary);
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ===== Bento Grid ===== */
        .bento-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .bento-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        @media (min-width: 1024px) {
            .bento-grid {
                grid-template-columns: 3fr 2fr;
            }
        }

        /* ===== Bento Card Base ===== */
        .bento-card {
            background: var(--bg-elevated);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 28px;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .bento-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .bento-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(250, 204, 21, 0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bento-card:hover::before {
            opacity: 1;
        }

        /* ===== Main Action Card (Bento 1) ===== */
        .bento-main {
            min-height: 340px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .bento-main .card-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 100px;
            background: rgba(250, 204, 21, 0.1);
            font-size: 11px;
            font-weight: 600;
            color: var(--brand-yellow);
            letter-spacing: 0.03em;
            text-transform: uppercase;
            width: fit-content;
        }

        .bento-main h2 {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 20px 0 12px;
            letter-spacing: -0.02em;
        }

        .bento-main p {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.7;
            max-width: 480px;
        }

        .bento-main .action-btn {
            display: block;
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #FACC15, #EAB308);
            color: #0B0F19;
            font-weight: 700;
            font-size: 15px;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 0 25px rgba(250, 204, 21, 0.12);
            position: relative;
            overflow: hidden;
        }

        .bento-main .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px rgba(250, 204, 21, 0.25);
        }

        .bento-main .action-btn:active {
            transform: translateY(0);
        }

        .bento-main .action-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        /* ===== Right Column: Stats + Recent ===== */
        .bento-right {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ===== Stats Card ===== */
        .bento-stats {
            padding: 24px;
        }

        .stats-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stats-header h3 {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .stats-header .live-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 500;
            color: #22C55E;
        }

        .stat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-muted);
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
        }

        .stat-value {
            font-size: 13px;
            font-weight: 600;
            font-family: 'SF Mono', 'JetBrains Mono', monospace;
        }

        .stat-value.yellow {
            color: var(--brand-yellow);
        }
        .stat-value.indigo {
            color: var(--brand-indigo);
        }
        .stat-value.green {
            color: #22C55E;
        }
        .stat-value.emerald {
            color: #34D399;
        }

        /* ===== Model Accuracy Bar ===== */
        .accuracy-bar-wrap {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-muted);
        }

        .accuracy-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .accuracy-bar {
            height: 6px;
            border-radius: 100px;
            background: var(--bg-hover);
            overflow: hidden;
            position: relative;
        }

        .accuracy-bar-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, #FACC15, #6366F1);
            width: 94.2%;
            transition: width 1s ease;
        }

        /* ===== Recent Inspections Card ===== */
        .bento-recent {
            padding: 24px;
        }

        .recent-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .recent-header h3 {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .recent-header a {
            font-size: 12px;
            color: var(--brand-indigo);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .recent-header a:hover {
            color: var(--brand-yellow);
        }

        .recent-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-muted);
        }

        .recent-item:last-child {
            border-bottom: none;
        }

        .recent-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .recent-icon.road {
            background: rgba(250, 204, 21, 0.1);
            color: var(--brand-yellow);
        }
        .recent-icon.pothole {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }
        .recent-icon.crack {
            background: rgba(99, 102, 241, 0.1);
            color: var(--brand-indigo);
        }

        .recent-icon i {
            width: 18px;
            height: 18px;
        }

        .recent-info {
            flex: 1;
            min-width: 0;
        }

        .recent-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .recent-meta {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .recent-status {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 100px;
        }

        .recent-status.completed {
            background: rgba(34, 197, 94, 0.1);
            color: #22C55E;
        }

        .recent-status.processing {
            background: rgba(250, 204, 21, 0.1);
            color: var(--brand-yellow);
        }

        /* ===== Bottom row: extra bento cards ===== */
        .bento-bottom-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 1200px;
            margin: 20px auto 0;
        }

        @media (min-width: 768px) {
            .bento-bottom-row {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }

        .bento-mini {
            padding: 20px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .bento-mini .mini-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .bento-mini .mini-icon i {
            width: 20px;
            height: 20px;
        }

        .bento-mini h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .bento-mini p {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* ===== Quick Stats ===== */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            max-width: 1200px;
            margin: 20px auto 0;
        }

        .quick-stat-card {
            background: var(--bg-light);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-muted);
            border-radius: 16px;
            padding: 18px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .quick-stat-card:hover {
            border-color: var(--border-subtle);
            transform: translateY(-2px);
        }

        @media (max-width: 640px) {
            .quick-stats {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        .quick-stat-number {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .quick-stat-label {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <main class="dashboard">

            <!-- Hero Section -->
            <div class="hero-section">
                <div class="hero-badge" data-i18n="dashboard.heroBadge">
                    <span class="status-dot" style="width:5px;height:5px;"></span>
                    AI Engine v2.0 • Real-time Inference
                </div>
                <h1 class="hero-title gradient-text" data-i18n="dashboard.heroTitle">
                    The Future of Road Infrastructure Inspection
                </h1>
                <p class="hero-subtitle" data-i18n="dashboard.heroSubtitle">
                    Powered by RoKenAI — reconstructing road infrastructure monitoring through deep learning computer vision with YOLOv8.
                </p>
            </div>

            <!-- Bento Grid Main -->
            <div class="bento-grid">

                <!-- Bento 1: Main Action Card (kiri, besar) -->
                <div class="bento-card bento-main">
                    <div>
                        <div class="card-label" data-i18n="dashboard.aiReady">
                            <i data-lucide="sparkles" style="width:14px;height:14px;"></i>
                            AI Engine Ready
                        </div>
                        <h2 data-i18n="dashboard.detectionTitle">Deteksi & Analisis Kerusakan Jalan</h2>
                        <p data-i18n="dashboard.detectionDesc">Unggah dokumentasi foto jalan raya untuk memproses segmentasi objek kerusakan jalan, lubang, dan retakan secara real-time via YOLOv8.</p>
                    </div>
                    <a href="upload.php" class="action-btn" data-i18n="dashboard.openDetection">
                        Buka Modul Deteksi
                    </a>
                </div>

                <!-- Bento Right Column -->
                <div class="bento-right">

                    <!-- Bento 2: Stats Card -->
                    <div class="bento-card bento-stats">
                        <div class="stats-header">
                            <h3 data-i18n="dashboard.modelSpecs">Model Core Specs</h3>
                            <div class="live-indicator">
                                <span class="status-dot" style="width:5px;height:5px;"></span>
                                <span data-i18n="dashboard.live">Live</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label" data-i18n="dashboard.weightsFile">Weights File</span>
                            <span class="stat-value yellow">best.pt</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label" data-i18n="dashboard.framework">Framework</span>
                            <span class="stat-value indigo">PyTorch / YOLO</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label" data-i18n="dashboard.inferenceSpeed">Inference Speed</span>
                            <span class="stat-value green">12ms</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label" data-i18n="dashboard.modelStatus">Model Status</span>
                            <span class="stat-value emerald" style="display:flex;align-items:center;gap:6px;">
                                <span class="status-dot" style="width:6px;height:6px;"></span>
                                <span data-i18n="dashboard.online">Online</span>
                            </span>
                        </div>
                        <div class="accuracy-bar-wrap">
                            <div class="accuracy-bar-label">
                                <span data-i18n="dashboard.accuracy">YOLOv8 Accuracy</span>
                                <span style="color:var(--brand-yellow);font-weight:600;">94.2%</span>
                            </div>
                            <div class="accuracy-bar">
                                <div class="accuracy-bar-fill"></div>
                            </div>
                        </div>
                        <div style="margin-top:12px;padding:10px 14px;background:var(--bg-input);border-radius:12px;text-align:center;font-size:12px;color:var(--text-muted);font-family:'SF Mono','JetBrains Mono',monospace;" data-i18n="dashboard.linked">
                            modules/main.py linked
                        </div>
                    </div>

                    <!-- Bento 3: Recent Inspections -->
                    <div class="bento-card bento-recent">
                        <div class="recent-header">
                            <h3 data-i18n="dashboard.recentInspections">Recent Inspections</h3>
                            <a href="#" data-i18n="dashboard.viewAll">View all →</a>
                        </div>
                        <div class="recent-item">
                            <div class="recent-icon road">
                                <i data-lucide="route"></i>
                            </div>
                            <div class="recent-info">
                                <div class="recent-title">Jalan Ahmad Yani</div>
                                <div class="recent-meta">2 hours ago • 3 detections</div>
                            </div>
                            <span class="recent-status completed">Completed</span>
                        </div>
                        <div class="recent-item">
                            <div class="recent-icon pothole">
                                <i data-lucide="triangle-alert"></i>
                            </div>
                            <div class="recent-info">
                                <div class="recent-title">Jl. Diponegoro</div>
                                <div class="recent-meta">5 hours ago • 7 detections</div>
                            </div>
                            <span class="recent-status completed">Completed</span>
                        </div>
                        <div class="recent-item">
                            <div class="recent-icon crack">
                                <i data-lucide="scan-line"></i>
                            </div>
                            <div class="recent-info">
                                <div class="recent-title">Jl. Sudirman</div>
                                <div class="recent-meta">1 day ago • 12 detections</div>
                            </div>
                            <span class="recent-status processing">Processing</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Quick Stats Row -->
            <div class="quick-stats">
                <div class="quick-stat-card">
                    <div class="quick-stat-number gradient-text">1,247</div>
                    <div class="quick-stat-label" data-i18n="dashboard.totalInspections">Total Inspections</div>
                </div>
                <div class="quick-stat-card">
                    <div class="quick-stat-number" style="color:#22C55E;">94.2%</div>
                    <div class="quick-stat-label" data-i18n="dashboard.detectionAccuracy">Detection Accuracy</div>
                </div>
                <div class="quick-stat-card">
                    <div class="quick-stat-number" style="color:var(--brand-indigo);">12ms</div>
                    <div class="quick-stat-label" data-i18n="dashboard.avgInference">Avg. Inference</div>
                </div>
            </div>

            <!-- Bottom Mini Bento Cards -->
            <div class="bento-bottom-row">
                <div class="bento-card bento-mini">
                    <div class="mini-icon" style="background:rgba(250, 204, 21, 0.1);color:var(--brand-yellow);">
                        <i data-lucide="bot"></i>
                    </div>
                    <h4 data-i18n="dashboard.aiChat">AI Chat Assistant</h4>
                    <p data-i18n="dashboard.aiChatDesc">Consult with RoKenAI about road damage analysis results.</p>
                </div>
                <div class="bento-card bento-mini">
                    <div class="mini-icon" style="background:rgba(99, 102, 241, 0.1);color:var(--brand-indigo);">
                        <i data-lucide="file-text"></i>
                    </div>
                    <h4 data-i18n="dashboard.reports">Generate Reports</h4>
                    <p data-i18n="dashboard.reportsDesc">Export detailed inspection reports in PDF format.</p>
                </div>
                <div class="bento-card bento-mini">
                    <div class="mini-icon" style="background:rgba(34, 197, 94, 0.1);color:#22C55E;">
                        <i data-lucide="database"></i>
                    </div>
                    <h4 data-i18n="dashboard.training">Model Training</h4>
                    <p data-i18n="dashboard.trainingDesc">Retrain the model with new road damage datasets.</p>
                </div>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>
