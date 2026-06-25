<?php session_name('RoKenAI'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Examples</title>
    <?php include 'partials/link.php'; ?>
    <style>
        .examples-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 12px;
        }

        .page-header p {
            font-size: 15px;
            color: var(--text-secondary);
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ===== Search & Filter ===== */
        .examples-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .example-search-wrap {
            flex: 1;
            position: relative;
            min-width: 200px;
        }

        .example-search-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .example-search {
            width: 100%;
            padding: 12px 16px 12px 46px;
            border-radius: 14px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: all 0.25s ease;
        }

        .example-search::placeholder { color: var(--text-muted); }
        .example-search:focus {
            border-color: rgba(250, 204, 21, 0.3);
            background: var(--bg-hover);
            box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.06);
        }

        .filter-chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 8px 16px;
            border-radius: 100px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-chip:hover, .filter-chip.active {
            background: rgba(250, 204, 21, 0.1);
            border-color: rgba(250, 204, 21, 0.2);
            color: var(--brand-yellow);
        }

        /* ===== Examples Grid ===== */
        .examples-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 640px) { .examples-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .examples-grid { grid-template-columns: repeat(3, 1fr); } }

        .example-card {
            background: var(--bg-elevated);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .example-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }

        .example-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(250, 204, 21, 0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .example-card:hover::before { opacity: 1; }

        .example-card .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .example-card .card-icon i { width: 22px; height: 22px; }

        .example-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }

        .example-card p {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .example-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .example-tag {
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .example-tag.detection { background: rgba(250, 204, 21, 0.1); color: var(--brand-yellow); }
        .example-tag.analysis { background: rgba(99, 102, 241, 0.1); color: var(--brand-indigo); }
        .example-tag.report { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
        .example-tag.config { background: rgba(148, 163, 184, 0.1); color: var(--text-secondary); }
        .example-tag.training { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

        .example-card .try-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            padding: 6px 14px;
            border-radius: 100px;
            background: rgba(250, 204, 21, 0.08);
            border: 1px solid rgba(250, 204, 21, 0.1);
            font-size: 12px;
            font-weight: 600;
            color: var(--brand-yellow);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .example-card .try-btn:hover {
            background: rgba(250, 204, 21, 0.15);
            transform: translateX(2px);
        }

        .example-card .try-btn i { width: 14px; height: 14px; }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div id="content-wrapper">
        <main class="examples-page">

            <div class="page-header">
                <h1 class="gradient-text" data-i18n="examples.title">Example Prompts</h1>
                <p data-i18n="examples.desc">Explore ready-to-use prompts and example use cases for road damage analysis with RoKenAI.</p>
            </div>

            <!-- Toolbar -->
            <div class="examples-toolbar">
                <div class="example-search-wrap">
                    <i data-lucide="search"></i>
                    <input class="example-search" type="text" id="exampleSearch" data-i18n="examples.search" placeholder="Search examples..." oninput="filterExamples()">
                </div>
                <div class="filter-chips">
                    <span class="filter-chip active" data-filter="all" onclick="setFilter(this, 'all')" data-i18n="examples.all">All</span>
                    <span class="filter-chip" data-filter="detection" onclick="setFilter(this, 'detection')" data-i18n="examples.detection">Detection</span>
                    <span class="filter-chip" data-filter="analysis" onclick="setFilter(this, 'analysis')" data-i18n="examples.analysis">Analysis</span>
                    <span class="filter-chip" data-filter="report" onclick="setFilter(this, 'report')" data-i18n="examples.reports">Reports</span>
                    <span class="filter-chip" data-filter="training" onclick="setFilter(this, 'training')" data-i18n="examples.training">Training</span>
                </div>
            </div>

            <!-- Grid -->
            <div class="examples-grid" id="examplesGrid">

                <div class="example-card" data-category="detection" data-title="Crack Detection">
                    <div class="card-icon" style="background:rgba(250,204,21,0.12);color:var(--brand-yellow);"><i data-lucide="scan-line"></i></div>
                    <h3>Road Crack Detection</h3>
                    <p>Identify and classify cracks in asphalt surfaces using YOLOv8 segmentation. Supports longitudinal, transverse, and alligator cracks.</p>
                    <div class="example-tags">
                        <span class="example-tag detection">Detection</span>
                        <span class="example-tag analysis">Analysis</span>
                    </div>
                    <a href="chat.php?prompt=How+to+detect+road+cracks+using+YOLOv8%3F" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

                <div class="example-card" data-category="detection" data-title="Pothole Detection">
                    <div class="card-icon" style="background:rgba(239,68,68,0.12);color:#EF4444;"><i data-lucide="triangle-alert"></i></div>
                    <h3>Pothole Detection & Analysis</h3>
                    <p>Detect potholes of various sizes and depths. Get severity ratings and recommended repair prioritization based on dimensions.</p>
                    <div class="example-tags">
                        <span class="example-tag detection">Detection</span>
                        <span class="example-tag analysis">Analysis</span>
                    </div>
                    <a href="chat.php?prompt=Show+me+how+to+analyze+pothole+severity+with+YOLOv8" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

                <div class="example-card" data-category="analysis" data-title="Surface Analysis">
                    <div class="card-icon" style="background:rgba(99,102,241,0.12);color:var(--brand-indigo);"><i data-lucide="bar-chart-3"></i></div>
                    <h3>Road Surface Quality Index</h3>
                    <p>Generate a comprehensive Road Surface Quality Index (RSQI) score from detection data. Includes crack density, pothole count, and surface wear analysis.</p>
                    <div class="example-tags">
                        <span class="example-tag analysis">Analysis</span>
                        <span class="example-tag report">Report</span>
                    </div>
                    <a href="chat.php?prompt=Calculate+road+surface+quality+index+from+detection+data" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

                <div class="example-card" data-category="report" data-title="Report Generation">
                    <div class="card-icon" style="background:rgba(34,197,94,0.12);color:#22C55E;"><i data-lucide="file-text"></i></div>
                    <h3>Automated Inspection Report</h3>
                    <p>Generate detailed PDF inspection reports with image annotations, detection counts, severity maps, and repair recommendations.</p>
                    <div class="example-tags">
                        <span class="example-tag report">Report</span>
                        <span class="example-tag analysis">Analysis</span>
                    </div>
                    <a href="chat.php?prompt=How+to+generate+an+inspection+report+with+images+and+annotations%3F" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

                <div class="example-card" data-category="training" data-title="Model Training">
                    <div class="card-icon" style="background:rgba(250,204,21,0.12);color:var(--brand-yellow);"><i data-lucide="bot"></i></div>
                    <h3>Custom Model Training</h3>
                    <p>Train YOLOv8 on your own road damage dataset. Includes dataset preparation, annotation format conversion, and training pipeline setup.</p>
                    <div class="example-tags">
                        <span class="example-tag training">Training</span>
                        <span class="example-tag config">Config</span>
                    </div>
                    <a href="chat.php?prompt=Guide+me+through+training+YOLOv8+on+a+custom+road+damage+dataset" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

                <div class="example-card" data-category="config" data-title="Export Setup">
                    <div class="card-icon" style="background:rgba(148,163,184,0.12);color:var(--text-secondary);"><i data-lucide="settings-2"></i></div>
                    <h3>OpenVINO Export & Optimization</h3>
                    <p>Convert trained YOLOv8 models to OpenVINO format for optimized inference. Includes benchmark comparison between PyTorch and OpenVINO.</p>
                    <div class="example-tags">
                        <span class="example-tag config">Config</span>
                        <span class="example-tag training">Training</span>
                    </div>
                    <a href="chat.php?prompt=How+to+export+YOLOv8+model+to+OpenVINO+format%3F" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

                <div class="example-card" data-category="detection" data-title="Real-time">
                    <div class="card-icon" style="background:rgba(99,102,241,0.12);color:var(--brand-indigo);"><i data-lucide="camera"></i></div>
                    <h3>Real-time Video Detection</h3>
                    <p>Set up real-time road damage detection from video streams. Supports CCTV feeds, drone footage, and dashcam video analysis.</p>
                    <div class="example-tags">
                        <span class="example-tag detection">Detection</span>
                        <span class="example-tag analysis">Analysis</span>
                    </div>
                    <a href="chat.php?prompt=How+to+set+up+real-time+video+detection+for+road+damage%3F" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

                <div class="example-card" data-category="analysis" data-title="Damage Stats">
                    <div class="card-icon" style="background:rgba(250,204,21,0.12);color:var(--brand-yellow);"><i data-lucide="pie-chart"></i></div>
                    <h3>Damage Statistics Dashboard</h3>
                    <p>Analyze historical detection data to identify trends, high-risk areas, and optimize road maintenance schedules using predictive analytics.</p>
                    <div class="example-tags">
                        <span class="example-tag analysis">Analysis</span>
                        <span class="example-tag report">Report</span>
                    </div>
                    <a href="chat.php?prompt=Show+me+how+to+analyze+damage+statistics+and+trends" class="try-btn"><i data-lucide="arrow-right"></i> <span data-i18n="examples.tryInChat">Try in Chat</span></a>
                </div>

            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        function setFilter(el, filter) {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            filterExamples();
        }

        function filterExamples() {
            const search = document.getElementById('exampleSearch').value.toLowerCase();
            const activeFilter = document.querySelector('.filter-chip.active')?.dataset.filter || 'all';
            document.querySelectorAll('.example-card').forEach(card => {
                const cat = card.dataset.category;
                const title = card.dataset.title.toLowerCase();
                const matchFilter = activeFilter === 'all' || cat === activeFilter;
                const matchSearch = title.includes(search) || card.querySelector('p').textContent.toLowerCase().includes(search);
                card.style.display = matchFilter && matchSearch ? '' : 'none';
            });
        }
    </script>
</body>
</html>
