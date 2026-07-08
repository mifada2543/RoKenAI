<?php session_name('RoKenAI'); session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Contoh Penggunaan</title>
    <?php include 'partials/link.php'; ?>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <main class="max-w-[1100px] mx-auto px-6 py-6 pb-14 animate-fade-in-up">

            <!-- Page Heading -->
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-[#0F172A] mb-2" style="font-size:clamp(24px,3.5vw,32px);letter-spacing:-0.02em;">
                    <span class="gradient-text" data-i18n="examples.title">Contoh Penggunaan</span>
                </h1>
                <p class="text-[14px] text-[#475569] max-w-md mx-auto leading-6" data-i18n="examples.desc">
                    Jelajahi pertanyaan siap pakai dan contoh kasus untuk analisis kerusakan jalan dengan RoKenAI.
                </p>
            </div>

            <!-- Toolbar -->
            <div class="flex items-center gap-3 mb-7 flex-wrap">
                <div class="flex-1 relative min-w-[200px]">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#94A3B8] pointer-events-none"></i>
                    <input type="text" id="exSearch" data-i18n-placeholder="examples.search"
                           placeholder="Cari contoh..." oninput="filterEx()"
                           class="w-full pl-10 pr-3.5 py-2.5 rounded-lg bg-white border border-[#E2E8F0] text-[#0F172A] text-[13px] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-[3px] focus:ring-[rgba(59,130,246,0.1)]">
                </div>
                <div class="flex gap-1.5 flex-wrap" id="filterGroup">
                    <span class="filter-chip active" data-f="all" onclick="setFilter(this,'all')"><span data-i18n="examples.all">Semua</span></span>
                    <span class="filter-chip" data-f="detection" onclick="setFilter(this,'detection')"><span data-i18n="examples.detection">Deteksi</span></span>
                    <span class="filter-chip" data-f="analysis" onclick="setFilter(this,'analysis')"><span data-i18n="examples.analysis">Analisis</span></span>
                    <span class="filter-chip" data-f="report" onclick="setFilter(this,'report')"><span data-i18n="examples.report">Laporan</span></span>
                    <span class="filter-chip" data-f="training" onclick="setFilter(this,'training')"><span data-i18n="examples.training">Pelatihan</span></span>
                </div>
            </div>

            <!-- Examples Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="exGrid">
                <?php 
                $examples = [
                    ['icon' => 'scan-line', 'iconBg' => 'rgba(29,78,216,0.1)', 'iconColor' => '#3B82F6', 'title' => 'Deteksi Retak Jalan', 'cat' => 'detection', 'tags' => ['detection','analysis'], 'prompt' => 'How+to+detect+road+cracks+using+YOLOv8%3F'],
                    ['icon' => 'triangle-alert', 'iconBg' => 'rgba(220,38,38,0.1)', 'iconColor' => '#DC2626', 'title' => 'Deteksi Lubang Jalan', 'cat' => 'detection', 'tags' => ['detection','analysis'], 'prompt' => 'Show+me+how+to+analyze+pothole+severity+with+YOLOv8'],
                    ['icon' => 'bar-chart-3', 'iconBg' => 'rgba(124,58,237,0.1)', 'iconColor' => '#7C3AED', 'title' => 'Indeks Kualitas Jalan', 'cat' => 'analysis', 'tags' => ['analysis','report'], 'prompt' => 'Calculate+road+surface+quality+index+from+detection+data'],
                    ['icon' => 'file-text', 'iconBg' => 'rgba(22,163,74,0.1)', 'iconColor' => '#16A34A', 'title' => 'Laporan Inspeksi Otomatis', 'cat' => 'report', 'tags' => ['report','analysis'], 'prompt' => 'How+to+generate+an+inspection+report+with+images+and+annotations%3F'],
                    ['icon' => 'bot', 'iconBg' => 'rgba(29,78,216,0.1)', 'iconColor' => '#3B82F6', 'title' => 'Pelatihan Model Kustom', 'cat' => 'training', 'tags' => ['training','config'], 'prompt' => 'Guide+me+through+training+YOLOv8+on+a+custom+road+damage+dataset'],
                    ['icon' => 'settings-2', 'iconBg' => 'rgba(100,116,139,0.1)', 'iconColor' => '#64748B', 'title' => 'Ekspor OpenVINO', 'cat' => 'config', 'tags' => ['config','training'], 'prompt' => 'How+to+export+YOLOv8+model+to+OpenVINO+format%3F'],
                    ['icon' => 'camera', 'iconBg' => 'rgba(124,58,237,0.1)', 'iconColor' => '#7C3AED', 'title' => 'Deteksi Video Real-time', 'cat' => 'detection', 'tags' => ['detection','analysis'], 'prompt' => 'How+to+set+up+real-time+video+detection+for+road+damage%3F'],
                    ['icon' => 'pie-chart', 'iconBg' => 'rgba(29,78,216,0.1)', 'iconColor' => '#3B82F6', 'title' => 'Statistik Kerusakan', 'cat' => 'analysis', 'tags' => ['analysis','report'], 'prompt' => 'Show+me+how+to+analyze+damage+statistics+and+trends'],
                ];
                $tagLabels = ['detection' => 'Deteksi', 'analysis' => 'Analisis', 'report' => 'Laporan', 'training' => 'Pelatihan', 'config' => 'Konfigurasi'];
                $tagColors = ['detection' => '#3B82F6', 'analysis' => '#7C3AED', 'report' => '#16A34A', 'training' => '#DC2626', 'config' => '#64748B'];
                $descTexts = [
                    'Deteksi Retak Jalan' => 'Identifikasi dan klasifikasi retak pada permukaan aspal menggunakan YOLOv8.',
                    'Deteksi Lubang Jalan' => 'Deteksi lubang berbagai ukuran dan kedalaman. Dapatkan tingkat keparahan.',
                    'Indeks Kualitas Jalan' => 'Hasilkan skor Indeks Kualitas Permukaan Jalan dari data deteksi.',
                    'Laporan Inspeksi Otomatis' => 'Hasilkan laporan inspeksi PDF dengan anotasi gambar dan rekomendasi.',
                    'Pelatihan Model Kustom' => 'Latih YOLOv8 pada dataset kerusakan jalan Anda sendiri.',
                    'Ekspor OpenVINO' => 'Konversi model YOLOv8 ke format OpenVINO untuk inferensi optimal.',
                    'Deteksi Video Real-time' => 'Atur deteksi kerusakan jalan real-time dari streaming video.',
                    'Statistik Kerusakan' => 'Analisis data deteksi historis untuk mengidentifikasi tren dan area risiko.',
                ];
                foreach ($examples as $ex): 
                    $dataTitle = strtolower(str_replace(' ', '_', $ex['title']));
                ?>
                <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-6 cursor-pointer transition-all duration-300 hover:border-[rgba(29,78,216,0.2)] hover:shadow-glow hover:-translate-y-1 flex flex-col" data-cat="<?= $ex['cat'] ?>" data-title="<?= $dataTitle ?>">
                    <div class="w-11 h-11 rounded-lg flex items-center justify-center mb-3.5 shrink-0" style="background:<?= $ex['iconBg'] ?>;color:<?= $ex['iconColor'] ?>">
                        <i data-lucide="<?= $ex['icon'] ?>" style="width:22px;height:22px;"></i>
                    </div>
                    <h3 class="font-heading text-[15px] font-bold text-[#0F172A] mb-1.5"><?= $ex['title'] ?></h3>
                    <p class="text-[12px] text-[#475569] leading-relaxed mb-3.5 flex-1"><?= $descTexts[$ex['title']] ?></p>
                    <div class="flex gap-1.5 flex-wrap mb-3">
                        <?php foreach ($ex['tags'] as $tag): ?>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase" style="background:<?= $tagColors[$tag] ?>1a;color:<?= $tagColors[$tag] ?>"><?= $tagLabels[$tag] ?></span>
                        <?php endforeach; ?>
                    </div>
                    <a href="chat.php?prompt=<?= $ex['prompt'] ?>" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#F8FAFC] border border-[#E2E8F0] text-[11px] font-semibold text-[#475569] no-underline transition-all duration-200 hover:text-[#3B82F6] hover:border-[rgba(29,78,216,0.3)] hover:bg-[rgba(29,78,216,0.06)] self-start">
                        <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
                        <span data-i18n="examples.try">Coba</span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <?php include 'partials/footer.php'; ?>

    <style>
        .filter-chip {
            padding: 7px 16px; border-radius: 9999px;
            background: #fff; border: 1.5px solid #E2E8F0;
            font-size: 12px; font-weight: 500; color: #475569;
            cursor: pointer; transition: all 0.2s ease;
        }
        .filter-chip:hover, .filter-chip.active {
            border-color: rgba(29,78,216,0.3);
            color: #3B82F6; background: rgba(29,78,216,0.06);
        }
        @media (max-width: 480px) {
            main { padding-left: 16px; padding-right: 16px; }
            .flex.items-center.gap-3 { flex-direction: column; }
            .flex-1.relative { width: 100%; }
        }
    </style>

    <script>
        lucide.createIcons();
        function setFilter(el, f) {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            filterEx();
        }
        function filterEx() {
            var search = document.getElementById('exSearch').value.toLowerCase();
            var activeF = document.querySelector('.filter-chip.active')?.dataset?.f || 'all';
            document.querySelectorAll('#exGrid > div').forEach(function(card) {
                var cat = card.dataset.cat;
                var title = (card.dataset.title || '').toLowerCase();
                var text = (card.querySelector('p')?.textContent || '').toLowerCase();
                var h3 = (card.querySelector('h3')?.textContent || '').toLowerCase();
                card.style.display = (activeF === 'all' || cat === activeF) && (title.includes(search) || text.includes(search) || h3.includes(search)) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
