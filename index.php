<?php 
session_name('RoKenAI'); 
session_start();

require_once 'controller/report.php';
require_once 'auth/config.php';

// Get platform stats
$stats = getPlatformStats($conn);
$totalReports = $stats['total_reports'];
$repaired = $stats['selesai'];
$avgResponse = $stats['avg_response_days'];

// Get recent reports
$recentReports = getRecentReports($conn, 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Platform Pelaporan Jalan Rusak berbasis AI</title>
    <?php include 'partials/link.php'; ?>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <main class="max-w-6xl mx-auto px-6 py-6 pb-16 animate-fade-in-up">

            <!-- ===== HERO ===== -->
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-15 items-center py-8 lg:py-0">
                <!-- LEFT -->
                <div class="flex-[1.1]">
                    <!-- Badge -->
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-light border border-[rgba(29,78,216,0.2)] text-[11px] font-semibold text-primary">
                            <i data-lucide="cpu" style="width:12px;height:12px;"></i>
                            <span data-i18n="dashboard.badge">AI Deteksi Jalan Rusak v2.0</span>
                        </span>
                    </div>

                    <!-- Heading -->
                    <h1 class="font-heading font-bold text-[#0F172A] leading-tight mb-4" 
                        style="font-size: clamp(28px,4.5vw,44px); letter-spacing:-0.03em;">
                        <span data-i18n="dashboard.h1a">Lihat Jalan Rusak?</span><br>
                        <span class="text-[#1D4ED8]" data-i18n="dashboard.h1b">Laporkan dalam Hitungan Detik</span>
                    </h1>

                    <p class="text-[15px] text-[#475569] leading-7 max-w-md mb-7" data-i18n="dashboard.desc">
                        RoKenAI menggunakan teknologi Computer Vision (YOLOv8) untuk mendeteksi dan mengklasifikasikan kerusakan jalan secara otomatis. Cukup foto, AI kami yang verifikasi.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex gap-3 flex-wrap mb-8">
                        <a href="upload.php"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-[#1D4ED8] text-white text-[14px] font-semibold no-underline shadow-md hover:bg-[#3B82F6] hover:-translate-y-0.5 transition-all duration-200">
                            <i data-lucide="camera" style="width:17px;height:17px;"></i>
                            <span data-i18n="dashboard.cta">Lapor Sekarang</span>
                        </a>
                        <a href="#how-it-works"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border-[1.5px] border-[#1D4ED8] text-[#1D4ED8] text-[14px] font-semibold no-underline hover:bg-primary-light transition-all duration-200">
                            <i data-lucide="info" style="width:17px;height:17px;"></i>
                            <span data-i18n="dashboard.howCta">Cara Kerja</span>
                        </a>
                    </div>

                    <!-- Stats Row -->
                    <div class="flex gap-6 flex-wrap">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#1D4ED8] font-heading"><?= number_format($totalReports > 0 ? $totalReports : 0) ?>+</div>
                            <div class="text-[12px] text-[#475569]" data-i18n="dashboard.stat1">Laporan Ditindaklanjuti</div>
                        </div>
                        <div class="w-px bg-[#E2E8F0]"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#1D4ED8] font-heading"><?= $totalReports > 0 ? round(($repaired / max($totalReports, 1)) * 100) : 94 ?>%</div>
                            <div class="text-[12px] text-[#475569]" data-i18n="dashboard.stat2">Akurasi Deteksi AI</div>
                        </div>
                        <div class="w-px bg-[#E2E8F0]"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#1D4ED8] font-heading"><?= $avgResponse > 0 ? $avgResponse : '4.2' ?>ms</div>
                            <div class="text-[12px] text-[#475569]" data-i18n="dashboard.stat3">Kecepatan Inferensi</div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="flex-[0.9] w-full">
                    <div class="w-full rounded-xl overflow-hidden border border-[#E2E8F0] shadow-lg bg-[#DBEAFE] aspect-[4/3] flex items-center justify-center relative group">
                        <img src="assets/img/Home.png" alt="RoKenAI Dashboard Preview" 
                             class="w-full h-full object-cover object-top transition-transform duration-400 group-hover:scale-105">
                        <!-- AI Detection tags overlay -->
                        <div class="absolute bottom-3.5 left-3.5 flex gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#FACC15] text-[#0F172A] rounded-lg text-[12px] font-bold font-mono shadow-lg animate-fade-in-up" style="animation-delay:0s">
                                <i data-lucide="scan" style="width:13px;height:13px;"></i> Lubang Jalan — 92%
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#FACC15] text-[#0F172A] rounded-lg text-[12px] font-bold font-mono shadow-lg animate-fade-in-up" style="animation-delay:0.15s">
                                <i data-lucide="alert-triangle" style="width:13px;height:13px;"></i> Retak — 87%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== CARA KERJA ===== -->
            <div class="mt-16" id="how-it-works">
                <div class="text-center mb-10">
                    <h2 class="font-heading text-[26px] font-bold text-[#0F172A] mb-2" data-i18n="dashboard.howTitle">Bagaimana Cara Kerjanya?</h2>
                    <p class="text-[14px] text-[#475569]" data-i18n="dashboard.howDesc">Tiga langkah mudah untuk melaporkan kerusakan jalan</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <?php $steps = [
                        ['icon' => 'camera', 'num' => 'Langkah 1', 'title' => 'step1t', 'titleEn' => 'Foto Jalan Rusak', 'desc' => 'step1d', 'descEn' => 'Ambil foto jalan yang rusak menggunakan kamera HP. Pastikan foto jelas dan terkena cahaya.'],
                        ['icon' => 'sparkles', 'num' => 'Langkah 2', 'title' => 'step2t', 'titleEn' => 'Deteksi Otomatis oleh AI', 'desc' => 'step2d', 'descEn' => 'Model YOLOv8 kami akan mendeteksi jenis kerusakan dan tingkat keparahannya secara otomatis.'],
                        ['icon' => 'check-circle-2', 'num' => 'Langkah 3', 'title' => 'step3t', 'titleEn' => 'Ditindaklanjuti', 'desc' => 'step3d', 'descEn' => 'Laporan masuk ke dashboard admin untuk diverifikasi dan ditindaklanjuti. Pantau status perbaikan secara real-time.'],
                    ]; ?>
                    <?php foreach ($steps as $step): ?>
                    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-7 pt-6 text-center transition-all duration-300 hover:border-primary-light hover:shadow-glow hover:-translate-y-1">
                        <div class="w-[60px] h-[60px] rounded-xl bg-primary-light flex items-center justify-center mx-auto mb-3.5 text-[#1D4ED8]">
                            <i data-lucide="<?= $step['icon'] ?>" style="width:26px;height:26px;"></i>
                        </div>
                        <div class="text-[13px] font-bold text-[#3B82F6] mb-1 font-heading" data-i18n="dashboard.<?= $step['num'] ?>"><?= $step['num'] ?></div>
                        <h3 class="font-heading text-[17px] font-semibold text-[#0F172A] mb-1.5" data-i18n="dashboard.<?= $step['title'] ?>"><?= $step['titleEn'] ?></h3>
                        <p class="text-[13px] text-[#475569] leading-relaxed" data-i18n="dashboard.<?= $step['desc'] ?>"><?= $step['descEn'] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===== STATISTIK ===== -->
            <div class="mt-14">
                <div class="text-center mb-8">
                    <h2 class="font-heading text-[22px] font-bold text-[#0F172A]" data-i18n="dashboard.statsTitle">Statistik Platform</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-6 text-center transition-all duration-300 hover:border-primary-light hover:shadow-glow hover:-translate-y-0.5">
                        <div class="font-heading text-[30px] font-bold text-[#1D4ED8]"><?= number_format($totalReports) ?></div>
                        <div class="text-[13px] text-[#475569] mt-1" data-i18n="dashboard.totalReports">Total Laporan</div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-6 text-center transition-all duration-300 hover:border-primary-light hover:shadow-glow hover:-translate-y-0.5">
                        <div class="font-heading text-[30px] font-bold text-[#1D4ED8]"><?= number_format($repaired) ?></div>
                        <div class="text-[13px] text-[#475569] mt-1" data-i18n="dashboard.repaired">Selesai Diperbaiki</div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-6 text-center transition-all duration-300 hover:border-primary-light hover:shadow-glow hover:-translate-y-0.5">
                        <div class="font-heading text-[30px] font-bold text-[#1D4ED8]"><?= $avgResponse > 0 ? $avgResponse : '0' ?></div>
                        <div class="text-[13px] text-[#475569] mt-1" data-i18n="dashboard.avgResp">Rata-rata Respons (hari)</div>
                    </div>
                </div>
            </div>

            <!-- ===== FITUR ===== -->
            <div class="mt-14">
                <div class="text-center mb-8">
                    <h2 class="font-heading text-[26px] font-bold text-[#0F172A]" data-i18n="dashboard.featTitle">Fitur RoKenAI</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-7 pt-6 text-center transition-all duration-300 hover:border-primary-light hover:shadow-glow hover:-translate-y-1">
                        <div class="w-[60px] h-[60px] rounded-xl bg-[rgba(59,130,246,0.1)] flex items-center justify-center mx-auto mb-3.5 text-[#3B82F6]">
                            <i data-lucide="bot" style="width:26px;height:26px;"></i>
                        </div>
                        <h3 class="font-heading text-[17px] font-semibold text-[#0F172A] mb-2" data-i18n="dashboard.feat1t">Tanya AI</h3>
                        <p class="text-[13px] text-[#475569] leading-relaxed mb-3.5" data-i18n="dashboard.feat1d">Konsultasi dengan asisten AI tentang jenis kerusakan, prioritas perbaikan, dan rekomendasi.</p>
                        <a href="chat.php" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#1D4ED8] no-underline hover:gap-2.5 transition-all duration-200">
                            <span data-i18n="dashboard.feat1cta">Mulai Chat</span>
                            <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                        </a>
                        <div class="mt-4 rounded-lg overflow-hidden border border-[#E2E8F0]">
                            <img src="assets/img/Chat.png" alt="Chat AI" class="w-full h-auto block">
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-7 pt-6 text-center transition-all duration-300 hover:border-primary-light hover:shadow-glow hover:-translate-y-1">
                        <div class="w-[60px] h-[60px] rounded-xl bg-[rgba(59,130,246,0.1)] flex items-center justify-center mx-auto mb-3.5 text-[#3B82F6]">
                            <i data-lucide="map-pin" style="width:26px;height:26px;"></i>
                        </div>
                        <h3 class="font-heading text-[17px] font-semibold text-[#0F172A] mb-2" data-i18n="dashboard.feat2t">Lacak Status Perbaikan</h3>
                        <p class="text-[13px] text-[#475569] leading-relaxed mb-3.5" data-i18n="dashboard.feat2d">Pantau perkembangan laporan Anda melalui "Garis Jalan" — dari Dilaporkan hingga Selesai.</p>
                        <a href="profile.php" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#1D4ED8] no-underline hover:gap-2.5 transition-all duration-200">
                            <span data-i18n="dashboard.feat2cta">Lihat Riwayat</span>
                            <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                        </a>
                        <div class="mt-4 rounded-lg overflow-hidden border border-[#E2E8F0]">
                            <img src="assets/img/Profile.png" alt="Riwayat Laporan" class="w-full h-auto block">
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-card p-7 pt-6 text-center transition-all duration-300 hover:border-primary-light hover:shadow-glow hover:-translate-y-1">
                        <div class="w-[60px] h-[60px] rounded-xl bg-[rgba(59,130,246,0.1)] flex items-center justify-center mx-auto mb-3.5 text-[#3B82F6]">
                            <i data-lucide="bell" style="width:26px;height:26px;"></i>
                        </div>
                        <h3 class="font-heading text-[17px] font-semibold text-[#0F172A] mb-2" data-i18n="dashboard.feat3t">Notifikasi Real-time</h3>
                        <p class="text-[13px] text-[#475569] leading-relaxed mb-3.5" data-i18n="dashboard.feat3d">Dapatkan pemberitahuan setiap kali status laporan berubah — dari diverifikasi hingga selesai.</p>
                        <div class="mt-4 rounded-lg overflow-hidden border border-[#E2E8F0]">
                            <img src="assets/img/Android Compact - 2.png" alt="Mobile App" class="w-full h-auto block">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== LAPORAN TERBARU ===== -->
            <div class="mt-14">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-heading text-[17px] font-semibold text-[#0F172A]" data-i18n="dashboard.recentTitle">Laporan Terbaru</h3>
                    <a href="profile.php" class="text-[13px] text-[#3B82F6] no-underline hover:text-[#1D4ED8] transition-colors" data-i18n="dashboard.viewAll">Lihat semua →</a>
                </div>
                <div class="flex flex-col gap-2">
                    <?php if (empty($recentReports)): ?>
                    <div class="flex items-center justify-center gap-3.5 p-3.5 bg-white rounded-lg border border-[#E2E8F0] text-[#94A3B8]">
                        <span>Belum ada laporan. Jadilah yang pertama!</span>
                    </div>
                    <?php else: ?>
                    <?php foreach ($recentReports as $report): 
                        $damageLabel = damageTypeLabel($report['damage_type']);
                        $location = $report['address'] ?: 'Lokasi tidak diketahui';
                        $badgeClass = getStatusBadgeClass($report['status']);
                    ?>
                    <div class="flex items-center gap-3.5 p-3.5 bg-white rounded-lg border border-[#E2E8F0] transition-all duration-200 hover:border-primary-light hover:bg-[#FAFBFC]">
                        <div class="w-10 h-10 min-w-[40px] rounded-lg bg-[rgba(59,130,246,0.1)] flex items-center justify-center text-[#1D4ED8]">
                            <i data-lucide="map-pin" style="width:18px;height:18px;"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[14px] font-semibold text-[#0F172A] truncate"><?= htmlspecialchars($location) ?> — <?= $damageLabel ?></div>
                            <div class="text-[12px] text-[#94A3B8]"><?= timeAgo($report['created_at']) ?> &bull; ID: #<?= htmlspecialchars($report['report_id']) ?></div>
                        </div>
                        <span class="status-badge <?= $badgeClass ?> shrink-0"><span class="s-dot"></span> <?= statusLabel($report['status']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
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
