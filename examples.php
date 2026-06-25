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
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .toolbar .search-wrap {
            flex: 1;
            position: relative;
            min-width: 200px;
        }
        .toolbar .search-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--text-muted);
            pointer-events: none;
        }
        .toolbar .search-wrap input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            border: 1px solid rgba(51, 65, 85, 0.3);
            color: var(--text-primary);
            font-size: 13px;
            font-family: var(--font-body);
            outline: none;
            transition: all 0.2s ease;
        }
        .toolbar .search-wrap input:focus {
            border-color: rgba(37, 99, 235, 0.4);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }
        .toolbar .search-wrap input::placeholder { color: var(--text-muted); }

        .filters {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .filters .f-chip {
            padding: 7px 16px;
            border-radius: var(--radius-full);
            background: var(--bg-card);
            border: 1px solid rgba(51, 65, 85, 0.3);
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: var(--font-body);
        }
        .filters .f-chip:hover,
        .filters .f-chip.active {
            border-color: rgba(37, 99, 235, 0.3);
            color: var(--primary-light);
            background: rgba(37, 99, 235, 0.06);
        }

        .examples-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        @media (min-width: 640px) { .examples-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .examples-grid { grid-template-columns: repeat(3, 1fr); } }

        .ex-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(51, 65, 85, 0.3);
            box-shadow: var(--shadow-card);
            padding: 24px;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ex-card:hover {
            border-color: rgba(37, 99, 235, 0.2);
            box-shadow: var(--shadow-glow);
            transform: translateY(-3px);
        }
        .ex-card .ex-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }
        .ex-card .ex-icon i { width: 22px; height: 22px; }
        .ex-card h3 {
            font-family: var(--font-heading);
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }
        .ex-card p {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 14px;
        }
        .ex-card .ex-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .ex-card .ex-tag {
            padding: 2px 10px;
            border-radius: var(--radius-full);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .ex-tag.detection { background: rgba(37,99,235,0.1); color: var(--primary-light); }
        .ex-tag.analysis { background: rgba(124,58,237,0.1); color: var(--purple); }
        .ex-tag.report { background: rgba(34,197,94,0.1); color: var(--success); }
        .ex-tag.config { background: rgba(100,116,139,0.1); color: var(--text-muted); }
        .ex-tag.training { background: rgba(239,68,68,0.1); color: var(--danger); }

        .ex-card .try-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: var(--radius-full);
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(51, 65, 85, 0.2);
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .ex-card .try-btn:hover {
            color: var(--primary-light);
            border-color: rgba(37, 99, 235, 0.3);
            background: rgba(37, 99, 235, 0.06);
        }
        .ex-card .try-btn i { width: 14px; height: 14px; }

        @media (max-width: 480px) {
            .examples-page { padding: 16px; }
            .toolbar { flex-direction: column; }
            .toolbar .search-wrap { width: 100%; }
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div id="content-wrapper">
        <main class="examples-page page-enter">
            <div class="page-heading">
                <h1><span class="gradient-text">Contoh Penggunaan</span></h1>
                <p>Jelajahi pertanyaan siap pakai dan contoh kasus untuk analisis kerusakan jalan dengan RoKenAI.</p>
            </div>

            <div class="toolbar">
                <div class="search-wrap">
                    <i data-lucide="search"></i>
                    <input type="text" id="exSearch" placeholder="Cari contoh..." oninput="filterEx()">
                </div>
                <div class="filters">
                    <span class="f-chip active" data-f="all" onclick="setFilter(this,'all')">Semua</span>
                    <span class="f-chip" data-f="detection" onclick="setFilter(this,'detection')">Deteksi</span>
                    <span class="f-chip" data-f="analysis" onclick="setFilter(this,'analysis')">Analisis</span>
                    <span class="f-chip" data-f="report" onclick="setFilter(this,'report')">Laporan</span>
                    <span class="f-chip" data-f="training" onclick="setFilter(this,'training')">Pelatihan</span>
                </div>
            </div>

            <div class="examples-grid" id="exGrid">
                <div class="ex-card" data-cat="detection" data-title="Crack Detection">
                    <div class="ex-icon" style="background:rgba(37,99,235,0.12);color:var(--primary-light);"><i data-lucide="scan-line"></i></div>
                    <h3>Deteksi Retak Jalan</h3>
                    <p>Identifikasi dan klasifikasi retak pada permukaan aspal menggunakan YOLOv8. Mendukung retak longitudinal, transversal, dan alligator.</p>
                    <div class="ex-tags"><span class="ex-tag detection">Deteksi</span><span class="ex-tag analysis">Analisis</span></div>
                    <a href="chat.php?prompt=How+to+detect+road+cracks+using+YOLOv8%3F" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
                <div class="ex-card" data-cat="detection" data-title="Pothole Detection">
                    <div class="ex-icon" style="background:rgba(239,68,68,0.12);color:var(--danger);"><i data-lucide="triangle-alert"></i></div>
                    <h3>Deteksi Lubang Jalan</h3>
                    <p>Deteksi lubang berbagai ukuran dan kedalaman. Dapatkan tingkat keparahan dan rekomendasi prioritas perbaikan.</p>
                    <div class="ex-tags"><span class="ex-tag detection">Deteksi</span><span class="ex-tag analysis">Analisis</span></div>
                    <a href="chat.php?prompt=Show+me+how+to+analyze+pothole+severity+with+YOLOv8" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
                <div class="ex-card" data-cat="analysis" data-title="Surface Analysis">
                    <div class="ex-icon" style="background:rgba(124,58,237,0.12);color:var(--purple);"><i data-lucide="bar-chart-3"></i></div>
                    <h3>Indeks Kualitas Jalan</h3>
                    <p>Hasilkan skor Indeks Kualitas Permukaan Jalan dari data deteksi. Termasuk kepadatan retak, jumlah lubang, dan analisis keausan.</p>
                    <div class="ex-tags"><span class="ex-tag analysis">Analisis</span><span class="ex-tag report">Laporan</span></div>
                    <a href="chat.php?prompt=Calculate+road+surface+quality+index+from+detection+data" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
                <div class="ex-card" data-cat="report" data-title="Report Generation">
                    <div class="ex-icon" style="background:rgba(34,197,94,0.12);color:var(--success);"><i data-lucide="file-text"></i></div>
                    <h3>Laporan Inspeksi Otomatis</h3>
                    <p>Hasilkan laporan inspeksi PDF dengan anotasi gambar, jumlah deteksi, peta tingkat keparahan, dan rekomendasi perbaikan.</p>
                    <div class="ex-tags"><span class="ex-tag report">Laporan</span><span class="ex-tag analysis">Analisis</span></div>
                    <a href="chat.php?prompt=How+to+generate+an+inspection+report+with+images+and+annotations%3F" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
                <div class="ex-card" data-cat="training" data-title="Model Training">
                    <div class="ex-icon" style="background:rgba(37,99,235,0.12);color:var(--primary-light);"><i data-lucide="bot"></i></div>
                    <h3>Pelatihan Model Kustom</h3>
                    <p>Latih YOLOv8 pada dataset kerusakan jalan Anda sendiri. Termasuk persiapan dataset, konversi format anotasi, dan pipeline pelatihan.</p>
                    <div class="ex-tags"><span class="ex-tag training">Pelatihan</span><span class="ex-tag config">Konfigurasi</span></div>
                    <a href="chat.php?prompt=Guide+me+through+training+YOLOv8+on+a+custom+road+damage+dataset" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
                <div class="ex-card" data-cat="config" data-title="Export Setup">
                    <div class="ex-icon" style="background:rgba(100,116,139,0.12);color:var(--text-muted);"><i data-lucide="settings-2"></i></div>
                    <h3>Ekspor OpenVINO</h3>
                    <p>Konversi model YOLOv8 ke format OpenVINO untuk inferensi yang dioptimalkan. Termasuk perbandingan benchmark PyTorch vs OpenVINO.</p>
                    <div class="ex-tags"><span class="ex-tag config">Konfigurasi</span><span class="ex-tag training">Pelatihan</span></div>
                    <a href="chat.php?prompt=How+to+export+YOLOv8+model+to+OpenVINO+format%3F" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
                <div class="ex-card" data-cat="detection" data-title="Real-time">
                    <div class="ex-icon" style="background:rgba(124,58,237,0.12);color:var(--purple);"><i data-lucide="camera"></i></div>
                    <h3>Deteksi Video Real-time</h3>
                    <p>Atur deteksi kerusakan jalan real-time dari streaming video. Mendukung feed CCTV, rekaman drone, dan dashcam.</p>
                    <div class="ex-tags"><span class="ex-tag detection">Deteksi</span><span class="ex-tag analysis">Analisis</span></div>
                    <a href="chat.php?prompt=How+to+set+up+real-time+video+detection+for+road+damage%3F" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
                <div class="ex-card" data-cat="analysis" data-title="Damage Stats">
                    <div class="ex-icon" style="background:rgba(37,99,235,0.12);color:var(--primary-light);"><i data-lucide="pie-chart"></i></div>
                    <h3>Statistik Kerusakan</h3>
                    <p>Analisis data deteksi historis untuk mengidentifikasi tren, area berisiko tinggi, dan optimalkan jadwal pemeliharaan jalan.</p>
                    <div class="ex-tags"><span class="ex-tag analysis">Analisis</span><span class="ex-tag report">Laporan</span></div>
                    <a href="chat.php?prompt=Show+me+how+to+analyze+damage+statistics+and+trends" class="try-btn"><i data-lucide="arrow-right"></i> Coba</a>
                </div>
            </div>
        </main>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        lucide.createIcons();
        function setFilter(el, f) {
            document.querySelectorAll('.f-chip').forEach(function(c) { c.classList.remove('active'); });
            el.classList.add('active');
            filterEx();
        }
        function filterEx() {
            var search = document.getElementById('exSearch').value.toLowerCase();
            var activeF = (document.querySelector('.f-chip.active')?.dataset?.f) || 'all';
            document.querySelectorAll('.ex-card').forEach(function(card) {
                var cat = card.dataset.cat;
                var title = (card.dataset.title || '').toLowerCase();
                var text = (card.querySelector('p')?.textContent || '').toLowerCase();
                var matchF = activeF === 'all' || cat === activeF;
                var matchS = title.includes(search) || text.includes(search);
                card.style.display = matchF && matchS ? '' : 'none';
            });
        }
    </script>
</body>
</html>
