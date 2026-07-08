<?php
// Cek status login untuk navbar
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$headerUsername = '';
if ($isLoggedIn && isset($_SESSION['user_id'])) {
    // Ambil username dari session (bisa diset saat login)
    $headerUsername = $_SESSION['username'] ?? 'User';
}
?>
<!-- ================================================================
     RoKenAI — Navbar (Light Mode + Language Switcher)
     ================================================================ -->

<nav class="top-nav">
    <div class="top-nav-inner">
        <!-- Left: Logo -->
        <a href="index.php" class="top-nav-logo">
            <img src="assets/Logo.png" alt="RoKenAI">
            <span>RoKen<span class="text-primary-700">AI</span></span>
        </a>

        <!-- Center: Navigation Links (Desktop) -->
        <div class="top-nav-links" id="desktopNav">
            <a href="index.php" class="nav-link" id="navDashboard">
                <i data-lucide="layout-dashboard"></i>
                <span data-i18n="nav.home">Beranda</span>
            </a>
            <a href="upload.php" class="nav-link" id="navUpload">
                <i data-lucide="camera"></i>
                <span data-i18n="nav.upload">Lapor Kerusakan</span>
            </a>
            <a href="chat.php" class="nav-link" id="navChat">
                <i data-lucide="bot"></i>
                <span data-i18n="nav.chat">Tanya AI</span>
            </a>
            <a href="examples.php" class="nav-link" id="navExamples">
                <i data-lucide="book-open"></i>
                <span data-i18n="nav.examples">Panduan</span>
            </a>
            <a href="news.php" class="nav-link" id="navNews">
                <i data-lucide="newspaper"></i>
                <span data-i18n="nav.news">Berita</span>
            </a>
            <?php if ($isLoggedIn): ?>
            <a href="profile.php" class="nav-link" id="navProfile">
                <i data-lucide="file-text"></i>
                <span data-i18n="nav.history">Riwayat</span>
            </a>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="admin/index.php" class="nav-link" id="navAdmin">
                <i data-lucide="shield"></i>
                <span>Admin</span>
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Right: Actions -->
        <div class="top-nav-actions">
            <?php if ($isLoggedIn): ?>
            <!-- Profile (sudah login) -->
            <a href="profile.php" class="profile-btn logged-in" title="Profil Saya">
                <span class="profile-initials"><?= strtoupper(substr($headerUsername, 0, 2)) ?></span>
            </a>
            <?php else: ?>
            <!-- Login -->
            <a href="auth/login.php" class="profile-btn" title="Masuk" data-i18n-title="auth.signIn">
                <i data-lucide="user"></i>
            </a>
            <?php endif; ?>

            <!-- Hamburger (Mobile) -->
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Menu">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </div>
</nav>

<!-- ===== Mobile Menu Overlay ===== -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-inner">
        <div class="mobile-nav-links">
            <a href="index.php" class="mobile-link" id="mNavDashboard">
                <i data-lucide="layout-dashboard"></i>
                <span data-i18n="nav.home">Beranda</span>
            </a>
            <a href="upload.php" class="mobile-link" id="mNavUpload">
                <i data-lucide="camera"></i>
                <span data-i18n="nav.upload">Lapor Kerusakan</span>
            </a>
            <a href="chat.php" class="mobile-link" id="mNavChat">
                <i data-lucide="bot"></i>
                <span data-i18n="nav.chat">Tanya AI</span>
            </a>
            <a href="examples.php" class="mobile-link" id="mNavExamples">
                <i data-lucide="book-open"></i>
                <span data-i18n="nav.examples">Panduan</span>
            </a>
            <a href="news.php" class="mobile-link" id="mNavNews">
                <i data-lucide="newspaper"></i>
                <span data-i18n="nav.news">Berita</span>
            </a>
            <hr class="mobile-divider">
            <?php if ($isLoggedIn): ?>
            <a href="profile.php" class="mobile-link" id="mNavProfile">
                <i data-lucide="file-text"></i>
                <span data-i18n="nav.history">Riwayat</span>
            </a>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="admin/index.php" class="mobile-link" id="mNavAdmin">
                <i data-lucide="shield"></i>
                <span>Admin Panel</span>
            </a>
            <?php endif; ?>
            <a href="auth/logout.php" class="mobile-link mobile-logout">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </a>
            <?php else: ?>
            <a href="auth/login.php" class="mobile-link mobile-login">
                <i data-lucide="log-in"></i>
                <span>Masuk</span>
            </a>
            <a href="auth/register.php" class="mobile-link mobile-register">
                <i data-lucide="user-plus"></i>
                <span>Daftar</span>
            </a>
            <?php endif; ?>
            <hr class="mobile-divider">
            <div class="mobile-lang">
                <span style="font-size:12px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;">Bahasa</span>
                <div style="display:flex;gap:8px;margin-top:8px;">
                    <button class="mobile-lang-opt" data-lang="id" onclick="switchLang('id')">🇮🇩 Indonesia</button>
                    <button class="mobile-lang-opt" data-lang="en" onclick="switchLang('en')">🇬🇧 English</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mobile-overlay" id="mobileOverlay"></div>


<style>
    /* ================================================================
       Navbar — Light Mode
       ================================================================ */
    .top-nav {
        position: fixed; top: 0; left: 0; right: 0;
        z-index: 100; height: 64px;
        background: #fff;
        border-bottom: 1px solid #E2E8F0;
        box-shadow: 0 1px 3px rgba(15,23,42,0.04);
    }
    .top-nav-inner {
        max-width: 1200px; margin: 0 auto; padding: 0 20px;
        height: 100%; display: flex; align-items: center;
        justify-content: space-between; gap: 12px;
    }

    /* Logo */
    .top-nav-logo {
        display: flex; align-items: center; gap: 10px;
        text-decoration: none; flex-shrink: 0;
    }
    .top-nav-logo img { height: 32px; width: auto; }
    .top-nav-logo span {
        font-size: 20px; font-weight: 800; letter-spacing: -0.03em;
        color: #0F172A; font-family: var(--font-heading);
    }

    /* Nav Links */
    .top-nav-links { display: flex; align-items: center; gap: 2px; }
    .nav-link {
        display: flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px;
        text-decoration: none; font-size: 13px; font-weight: 500;
        color: #475569; transition: all 0.2s ease; white-space: nowrap;
    }
    .nav-link i { width: 16px; height: 16px; }
    .nav-link:hover { color: #1D4ED8; background: #DBEAFE; }
    .nav-link.active { background: #DBEAFE; color: #1D4ED8; font-weight: 600; }

    /* Actions */
    .top-nav-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

    /* Profile Button */
    .profile-btn {
        width: 38px; height: 38px; border-radius: 50%;
        background: #F8FAFC; border: 1px solid #E2E8F0;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #475569; text-decoration: none;
        transition: all 0.2s ease;
    }
    .profile-btn:hover { border-color: #3B82F6; color: #1D4ED8; background: #DBEAFE; }
    .profile-btn i { width: 18px; height: 18px; }
    .profile-btn.logged-in {
        background: #1D4ED8; color: #fff; border-color: #1D4ED8;
        font-size: 12px; font-weight: 700; font-family: var(--font-heading);
    }
    .profile-btn.logged-in:hover { background: #3B82F6; border-color: #3B82F6; color: #fff; }
    .profile-initials { line-height: 1; }

    /* ===== Hamburger Button ===== */
    .hamburger-btn {
        display: none;
        width: 38px; height: 38px; min-width: 38px;
        border-radius: 8px; border: 1px solid #E2E8F0;
        background: #F8FAFC; cursor: pointer;
        flex-direction: column; align-items: center; justify-content: center;
        gap: 4px; transition: all 0.2s ease;
    }
    .hamburger-btn:hover { border-color: #3B82F6; background: #DBEAFE; }
    .hamburger-line {
        display: block; width: 16px; height: 2px;
        background: #475569; border-radius: 2px;
        transition: all 0.3s ease;
    }
    .hamburger-btn.active .hamburger-line:nth-child(1) {
        transform: translateY(6px) rotate(45deg);
    }
    .hamburger-btn.active .hamburger-line:nth-child(2) {
        opacity: 0;
    }
    .hamburger-btn.active .hamburger-line:nth-child(3) {
        transform: translateY(-6px) rotate(-45deg);
    }
    .hamburger-btn.active .hamburger-line { background: #1D4ED8; }

    /* ===== Mobile Menu Overlay ===== */
    .mobile-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 150;
        background: rgba(15,23,42,0.3);
        backdrop-filter: blur(4px);
        opacity: 0; transition: opacity 0.3s ease;
    }
    .mobile-overlay.open { display: block; opacity: 1; }

    /* ===== Mobile Menu Panel ===== */
    .mobile-menu {
        display: none;
        position: fixed; top: 0; right: 0; z-index: 160;
        width: 300px; max-width: 85vw; height: 100vh;
        background: #fff; box-shadow: -8px 0 24px rgba(15,23,42,0.1);
        transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
        overflow-y: auto;
    }
    .mobile-menu.open { display: block; transform: translateX(0); }
    .mobile-menu-inner { padding: 80px 20px 24px; }

    .mobile-nav-links { display: flex; flex-direction: column; gap: 2px; }
    .mobile-link {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 16px; border-radius: 10px;
        text-decoration: none; font-size: 15px; font-weight: 500;
        color: #0F172A; transition: all 0.15s ease;
    }
    .mobile-link:hover { background: #F8FAFC; color: #1D4ED8; }
    .mobile-link i { width: 20px; height: 20px; color: #94A3B8; flex-shrink: 0; }
    .mobile-link:hover i { color: #1D4ED8; }
    .mobile-link.active { background: #DBEAFE; color: #1D4ED8; font-weight: 600; }
    .mobile-link.active i { color: #1D4ED8; }
    .mobile-login { color: #1D4ED8; font-weight: 600; }
    .mobile-login i { color: #1D4ED8; }
    .mobile-register { color: #475569; }
    .mobile-logout { color: #DC2626; }
    .mobile-logout i { color: #DC2626; }
    .mobile-logout:hover { background: rgba(220,38,38,0.06); color: #DC2626; }
    .mobile-divider {
        border: none; border-top: 1px solid #E2E8F0;
        margin: 8px 16px;
    }
    .mobile-lang { padding: 12px 16px; }
    .mobile-lang-opt {
        flex: 1; padding: 10px; border-radius: 8px;
        border: 1.5px solid #E2E8F0; background: #fff;
        font-size: 12px; font-weight: 500; cursor: pointer;
        font-family: var(--font-body); color: #0F172A;
        transition: all 0.15s ease;
    }
    .mobile-lang-opt:hover { border-color: #3B82F6; background: #DBEAFE; }
    .mobile-lang-opt.active-lang { border-color: #1D4ED8; background: #DBEAFE; color: #1D4ED8; font-weight: 600; }

    /* ===== Responsive: Mobile ===== */
    @media (max-width: 900px) {
        .top-nav-links { display: none; }
        .hamburger-btn { display: flex; }
        .top-nav { height: 58px; }
        .top-nav-inner { padding: 0 12px; }
        .top-nav-logo span { font-size: 17px; }
        .top-nav-logo img { height: 28px; }
    }
    @media (min-width: 901px) {
        .mobile-menu, .mobile-overlay { display: none !important; }
        .hamburger-btn { display: none !important; }
    }
</style>

<script>
    // ================================================================
    // NAVBAR — Active link + Language + Mobile Hamburger
    // ================================================================
    document.addEventListener('DOMContentLoaded', function () {
        // — Page identification —
        const path = window.location.pathname.split('/').pop();

        // — Active nav (desktop) —
        const navMap = {
            'index.php':    'navDashboard',
            'upload.php':   'navUpload',
            'chat.php':     'navChat',
            'examples.php': 'navExamples',
            'news.php':     'navNews',
            'profile.php':  'navProfile'
        };
        const activeId = navMap[path];
        if (activeId) {
            var el = document.getElementById(activeId);
            if (el) el.classList.add('active');
        }

        // — Active nav (mobile) —
        const mNavMap = {
            'index.php':    'mNavDashboard',
            'upload.php':   'mNavUpload',
            'chat.php':     'mNavChat',
            'examples.php': 'mNavExamples',
            'news.php':     'mNavNews',
            'profile.php':  'mNavProfile'
        };
        const mActiveId = mNavMap[path];
        if (mActiveId) {
            var el = document.getElementById(mActiveId);
            if (el) el.classList.add('active');
        }

        // Update language UI — mobile language options
        function updateLangUI() {
            const lang = i18n.getLang();
            document.querySelectorAll('.mobile-lang-opt').forEach(function (btn) {
                btn.classList.toggle('active-lang', btn.dataset.lang === lang);
            });
        }

        updateLangUI();
        document.addEventListener('languageChanged', updateLangUI);
        document.addEventListener('i18nApplied', updateLangUI);

        // — Hamburger menu —
        const hamburger     = document.getElementById('hamburgerBtn');
        const mobileMenu    = document.getElementById('mobileMenu');
        const mobileOverlay = document.getElementById('mobileOverlay');

        if (hamburger && mobileMenu && mobileOverlay) {
            function openMobile() {
                hamburger.classList.add('active');
                mobileMenu.classList.add('open');
                mobileOverlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeMobile() {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('open');
                mobileOverlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            hamburger.addEventListener('click', function () {
                if (mobileMenu.classList.contains('open')) {
                    closeMobile();
                } else {
                    openMobile();
                }
            });

            mobileOverlay.addEventListener('click', closeMobile);

            // Tutup menu saat link di klik
            mobileMenu.querySelectorAll('.mobile-link').forEach(function (link) {
                link.addEventListener('click', closeMobile);
            });

            // Tutup dengan Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                    closeMobile();
                }
            });
        }
    });

    function switchLang(code) {
        i18n.setLang(code);
        lucide.createIcons();
    }
</script>
