<!-- ================================================================
     RoKenAI — Footer (Light Mode)
     ================================================================ -->
<footer class="border-t border-line-200 bg-white mt-16">
    <div class="max-w-screen-xl mx-auto px-6 pt-12 pb-6">

        <!-- Top: Brand + Links -->
        <div class="flex flex-wrap gap-10 mb-8">

            <!-- Brand -->
            <div class="flex-1 min-w-[240px] max-w-xs">
                <a href="index.php" class="flex items-center gap-2 no-underline mb-3">
                    <img src="assets/Logo.png" alt="RoKenAI" class="h-8 w-auto">
                    <span class="text-lg font-extrabold tracking-tight text-ink-900 font-heading">
                        RoKen<span class="text-primary">AI</span>
                    </span>
                </a>
                <p class="text-[13px] text-ink-600 leading-7" data-i18n="footer.desc">
                    Platform AI untuk pelaporan dan deteksi kerusakan jalan berbasis Computer Vision.
                </p>
            </div>

            <!-- Links -->
            <div class="flex gap-10 flex-wrap">

                <div class="flex flex-col gap-0.5">
                    <h4 class="text-[11px] font-bold text-ink-900 uppercase tracking-widest mb-3 font-heading"
                        data-i18n="footer.services">Layanan</h4>
                    <a href="upload.php"  class="footer-link" data-i18n="nav.upload">Lapor Kerusakan</a>
                    <a href="chat.php"    class="footer-link" data-i18n="nav.chat">Tanya AI</a>
                    <a href="examples.php"class="footer-link" data-i18n="nav.examples">Panduan</a>
                    <a href="profile.php" class="footer-link" data-i18n="nav.history">Riwayat Laporan</a>
                </div>

                <div class="flex flex-col gap-0.5">
                    <h4 class="text-[11px] font-bold text-ink-900 uppercase tracking-widest mb-3 font-heading"
                        data-i18n="footer.info">Informasi</h4>
                    <a href="news.php"    class="footer-link" data-i18n="nav.news">Berita</a>
                    <a href="index.php"   class="footer-link" data-i18n="footer.about">Tentang RoKenAI</a>
                    <a href="https://docs.ultralytics.com/" target="_blank" class="footer-link">YOLOv8 Docs</a>
                </div>

                <div class="flex flex-col gap-0.5">
                    <h4 class="text-[11px] font-bold text-ink-900 uppercase tracking-widest mb-3 font-heading"
                        data-i18n="footer.contact">Kontak</h4>
                    <a href="#" class="footer-link" data-i18n="footer.help">Pusat Bantuan</a>
                    <a href="#" class="footer-link" data-i18n="footer.privacy">Kebijakan Privasi</a>
                    <a href="#" class="footer-link" data-i18n="footer.terms">Syarat &amp; Ketentuan</a>
                </div>

            </div>
        </div>

        <!-- Bottom -->
        <div class="flex justify-between items-center flex-wrap gap-3 pt-5 border-t border-line-200 text-[12px] text-[#94A3B8]">
            <span>&copy; 2026 RoKenAI. All rights reserved.</span>
            <span class="font-mono text-[11px]">Powered by YOLOv8 &bull; Computer Vision</span>
        </div>

    </div>
</footer>

<style>
    .footer-link {
        display: block; font-size: 13px; color: #475569;
        text-decoration: none; padding: 4px 0;
        transition: color 0.2s ease;
    }
    .footer-link:hover { color: #1D4ED8; }
    @media (max-width: 768px) {
        footer .flex-wrap { flex-direction: column; gap: 32px; }
        footer .max-w-xs { max-width: 100%; }
        footer .flex.gap-10 { gap: 24px; }
        footer .flex.justify-between { flex-direction: column; text-align: center; }
    }
    @media (max-width: 480px) {
        footer .px-6 { padding-left: 16px; padding-right: 16px; }
        footer .flex.gap-10.flex-wrap { flex-direction: column; gap: 20px; }
    }
</style>
