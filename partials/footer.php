<!-- ================================================================
     RoKenAI — Footer Putih (Light Mode)
     ================================================================ -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <a href="index.php" class="footer-logo">
                    <img src="assets/Logo.png" alt="RoKenAI">
                    <span>RoKen<span class="text-accent">AI</span></span>
                </a>
                <p class="footer-desc">Platform AI untuk pelaporan dan deteksi kerusakan jalan berbasis Computer Vision. Laporkan jalan rusak dalam hitungan detik.</p>
            </div>
            <div class="footer-links">
                <div class="footer-col">
                    <h4>Layanan</h4>
                    <a href="upload.php">Lapor Kerusakan</a>
                    <a href="chat.php">Tanya AI</a>
                    <a href="examples.php">Panduan</a>
                    <a href="profile.php">Riwayat Laporan</a>
                </div>
                <div class="footer-col">
                    <h4>Informasi</h4>
                    <a href="news.php">Berita</a>
                    <a href="index.php">Tentang RoKenAI</a>
                    <a href="https://docs.ultralytics.com/" target="_blank">YOLOv8 Docs</a>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <a href="#">Pusat Bantuan</a>
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; 2026 RoKenAI. All rights reserved.</span>
            <span class="footer-tech">Powered by YOLOv8 &bull; Computer Vision</span>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        border-top: 1px solid var(--line-200);
        background: var(--surface);
        margin-top: 60px;
    }

    .footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 48px 24px 24px;
    }

    .footer-top {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        margin-bottom: 32px;
    }

    .footer-brand {
        flex: 1;
        min-width: 240px;
        max-width: 320px;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        margin-bottom: 12px;
    }
    .footer-logo img { height: 32px; width: auto; }
    .footer-logo span {
        font-size: 18px;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--ink-900);
        font-family: var(--font-heading);
    }
    .footer-logo .text-accent { color: var(--primary-700); }

    .footer-desc {
        font-size: 13px;
        color: var(--ink-600);
        line-height: 1.7;
    }

    .footer-links {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
    }

    .footer-col h4 {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-900);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 16px;
        font-family: var(--font-heading);
    }

    .footer-col a {
        display: block;
        font-size: 13px;
        color: var(--ink-600);
        text-decoration: none;
        padding: 4px 0;
        transition: color 0.2s ease;
    }
    .footer-col a:hover {
        color: var(--primary-700);
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--line-200);
        font-size: 12px;
        color: #94A3B8;
    }

    .footer-tech {
        font-family: var(--font-mono);
        font-size: 11px;
    }

    @media (max-width: 768px) {
        .footer-top { flex-direction: column; gap: 32px; }
        .footer-brand { max-width: 100%; }
        .footer-links { gap: 24px; }
        .footer-bottom { flex-direction: column; text-align: center; }
    }

    @media (max-width: 480px) {
        .footer-inner { padding: 32px 16px 20px; }
        .footer-links { flex-direction: column; gap: 20px; }
    }
</style>
