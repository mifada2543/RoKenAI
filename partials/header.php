<?php
// Cek status login untuk navbar
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$headerUsername = '';
if ($isLoggedIn && isset($_SESSION['user_id'])) {
    $headerUsername = $_SESSION['username'] ?? 'User';
}
?>
<!-- RoKenAI — Navbar (Tailwind) -->
<nav class="fixed top-0 left-0 right-0 z-[100] h-16 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-6xl mx-auto px-5 h-full flex items-center justify-between gap-3">
        
        <!-- Left: Logo -->
        <a href="index.php" class="flex items-center gap-2.5 no-underline shrink-0">
            <img src="assets/Logo.png" alt="RoKenAI" class="h-8 w-auto">
            <span class="text-xl font-extrabold tracking-tight text-[#0F172A] font-heading">
                RoKen<span class="text-[#1D4ED8]">AI</span>
            </span>
        </a>

        <!-- Center: Navigation Links (Desktop) -->
        <div class="hidden lg:flex items-center gap-0.5" id="desktopNav">
            <a href="index.php" class="nav-link-ds" id="navDashboard">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                <span data-i18n="nav.home">Beranda</span>
            </a>
            <a href="upload.php" class="nav-link-ds" id="navUpload">
                <i data-lucide="camera" class="w-4 h-4"></i>
                <span data-i18n="nav.upload">Lapor Kerusakan</span>
            </a>
            <a href="chat.php" class="nav-link-ds" id="navChat">
                <i data-lucide="bot" class="w-4 h-4"></i>
                <span data-i18n="nav.chat">Tanya AI</span>
            </a>
            <a href="examples.php" class="nav-link-ds" id="navExamples">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                <span data-i18n="nav.examples">Panduan</span>
            </a>
            <a href="news.php" class="nav-link-ds" id="navNews">
                <i data-lucide="newspaper" class="w-4 h-4"></i>
                <span data-i18n="nav.news">Berita</span>
            </a>
            <?php if ($isLoggedIn): ?>
            <a href="profile.php" class="nav-link-ds" id="navProfile">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span data-i18n="nav.history">Riwayat</span>
            </a>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="admin/index.php" class="nav-link-ds" id="navAdmin">
                <i data-lucide="shield" class="w-4 h-4"></i>
                <span>Admin</span>
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2 shrink-0">
            <?php if ($isLoggedIn): ?>
            <a href="profile.php" class="profile-btn-ds logged-in" title="Profil Saya">
                <span class="profile-initials-ds"><?= strtoupper(substr($headerUsername, 0, 2)) ?></span>
            </a>
            <?php else: ?>
            <a href="auth/login.php" class="profile-btn-ds" title="Masuk">
                <i data-lucide="user" class="w-[18px] h-[18px]"></i>
            </a>
            <?php endif; ?>

            <!-- Hamburger (Mobile) -->
            <button class="hamburger-btn-ds" id="hamburgerBtn" aria-label="Menu">
                <span class="hamburger-line block w-4 h-0.5 bg-[#475569] rounded-sm"></span>
                <span class="hamburger-line block w-4 h-0.5 bg-[#475569] rounded-sm"></span>
                <span class="hamburger-line block w-4 h-0.5 bg-[#475569] rounded-sm"></span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Overlay -->
<div class="fixed inset-0 z-[150] bg-black/30 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300" id="mobileOverlay"></div>

<!-- Mobile Menu Panel -->
<div class="fixed top-0 right-0 z-[160] w-[300px] max-w-[85vw] h-screen bg-white shadow-2xl translate-x-full transition-transform duration-350 ease-in-out overflow-y-auto" id="mobileMenu">
    <div class="pt-20 pb-6 px-5">
        <div class="flex flex-col gap-0.5">
            <a href="index.php" class="mobile-link-ds" id="mNavDashboard">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span data-i18n="nav.home">Beranda</span>
            </a>
            <a href="upload.php" class="mobile-link-ds" id="mNavUpload">
                <i data-lucide="camera" class="w-5 h-5"></i>
                <span data-i18n="nav.upload">Lapor Kerusakan</span>
            </a>
            <a href="chat.php" class="mobile-link-ds" id="mNavChat">
                <i data-lucide="bot" class="w-5 h-5"></i>
                <span data-i18n="nav.chat">Tanya AI</span>
            </a>
            <a href="examples.php" class="mobile-link-ds" id="mNavExamples">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span data-i18n="nav.examples">Panduan</span>
            </a>
            <a href="news.php" class="mobile-link-ds" id="mNavNews">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
                <span data-i18n="nav.news">Berita</span>
            </a>
            <hr class="border-none border-t border-gray-200 my-2 mx-4">
            <?php if ($isLoggedIn): ?>
            <a href="profile.php" class="mobile-link-ds" id="mNavProfile">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span data-i18n="nav.history">Riwayat</span>
            </a>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="admin/index.php" class="mobile-link-ds" id="mNavAdmin">
                <i data-lucide="shield" class="w-5 h-5"></i>
                <span>Admin Panel</span>
            </a>
            <?php endif; ?>
            <a href="auth/logout.php" class="mobile-link-ds text-[#DC2626] hover:bg-red-50">
                <i data-lucide="log-out" class="w-5 h-5 text-[#DC2626]"></i>
                <span>Keluar</span>
            </a>
            <?php else: ?>
            <a href="auth/login.php" class="mobile-link-ds text-[#1D4ED8] font-semibold">
                <i data-lucide="log-in" class="w-5 h-5 text-[#1D4ED8]"></i>
                <span>Masuk</span>
            </a>
            <a href="auth/register.php" class="mobile-link-ds">
                <i data-lucide="user-plus" class="w-5 h-5 text-[#475569]"></i>
                <span>Daftar</span>
            </a>
            <?php endif; ?>
            <hr class="border-none border-t border-gray-200 my-2 mx-4">
            <div class="px-4 py-3">
                <span class="text-[12px] text-[#94A3B8] font-semibold uppercase tracking-wide">Bahasa</span>
                <div class="flex gap-2 mt-2">
                    <button class="mobile-lang-btn" data-lang="id" onclick="switchLang('id')">🇮🇩 Indonesia</button>
                    <button class="mobile-lang-btn" data-lang="en" onclick="switchLang('en')">🇬🇧 English</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== Desktop Nav Links ===== */
    .nav-link-ds {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px;
        text-decoration: none; font-size: 13px; font-weight: 500;
        color: #475569; transition: all 0.2s ease; white-space: nowrap;
    }
    .nav-link-ds:hover { color: #1D4ED8; background: #DBEAFE; }
    .nav-link-ds.active { background: #DBEAFE; color: #1D4ED8; font-weight: 600; }

    /* ===== Profile Button ===== */
    .profile-btn-ds {
        width: 38px; height: 38px; border-radius: 50%;
        background: #F8FAFC; border: 1px solid #E2E8F0;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #475569; text-decoration: none;
        transition: all 0.2s ease;
    }
    .profile-btn-ds:hover { border-color: #3B82F6; color: #1D4ED8; background: #DBEAFE; }
    .profile-btn-ds.logged-in {
        background: #1D4ED8; color: #fff; border-color: #1D4ED8;
        font-size: 12px; font-weight: 700;
    }
    .profile-btn-ds.logged-in:hover { background: #3B82F6; border-color: #3B82F6; color: #fff; }
    .profile-initials-ds { line-height: 1; }

    /* ===== Hamburger ===== */
    .hamburger-btn-ds {
        display: none;
        width: 38px; height: 38px; min-width: 38px;
        border-radius: 8px; border: 1px solid #E2E8F0;
        background: #F8FAFC; cursor: pointer;
        flex-direction: column; align-items: center; justify-content: center;
        gap: 4px; transition: all 0.2s ease;
    }
    .hamburger-btn-ds:hover { border-color: #3B82F6; background: #DBEAFE; }

    /* ===== Mobile Links ===== */
    .mobile-link-ds {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 16px; border-radius: 10px;
        text-decoration: none; font-size: 15px; font-weight: 500;
        color: #0F172A; transition: all 0.15s ease;
    }
    .mobile-link-ds:hover { background: #F8FAFC; color: #1D4ED8; }
    .mobile-link-ds i { color: #94A3B8; flex-shrink: 0; }
    .mobile-link-ds:hover i { color: #1D4ED8; }
    .mobile-link-ds.active { background: #DBEAFE; color: #1D4ED8; font-weight: 600; }
    .mobile-link-ds.active i { color: #1D4ED8; }

    /* ===== Mobile Lang Button ===== */
    .mobile-lang-btn {
        flex: 1; padding: 10px; border-radius: 8px;
        border: 1.5px solid #E2E8F0; background: #fff;
        font-size: 12px; font-weight: 500; cursor: pointer;
        color: #0F172A; transition: all 0.15s ease;
    }
    .mobile-lang-btn:hover { border-color: #3B82F6; background: #DBEAFE; }
    .mobile-lang-btn.active-lang { border-color: #1D4ED8; background: #DBEAFE; color: #1D4ED8; font-weight: 600; }

    /* ===== Responsive ===== */
    @media (max-width: 1023px) {
        .hamburger-btn-ds { display: flex; }
        nav { height: 58px; }
        nav .px-5 { padding-left: 12px; padding-right: 12px; }
        nav .text-xl { font-size: 17px; }
        nav .h-8 { height: 28px; }
    }
    @media (min-width: 1024px) {
        #mobileMenu, #mobileOverlay { display: none !important; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const path = window.location.pathname.split('/').pop();

        // Active desktop nav
        const navMap = {
            'index.php': 'navDashboard', 'upload.php': 'navUpload', 'chat.php': 'navChat',
            'examples.php': 'navExamples', 'news.php': 'navNews', 'profile.php': 'navProfile'
        };
        const el = document.getElementById(navMap[path]);
        if (el) el.classList.add('active');

        // Active mobile nav
        const mNavMap = {
            'index.php': 'mNavDashboard', 'upload.php': 'mNavUpload', 'chat.php': 'mNavChat',
            'examples.php': 'mNavExamples', 'news.php': 'mNavNews', 'profile.php': 'mNavProfile'
        };
        const mEl = document.getElementById(mNavMap[path]);
        if (mEl) mEl.classList.add('active');

        // Language UI
        function updateLangUI() {
            const lang = (typeof i18n !== 'undefined') ? i18n.getLang() : 'id';
            document.querySelectorAll('.mobile-lang-btn').forEach(function (btn) {
                btn.classList.toggle('active-lang', btn.dataset.lang === lang);
            });
        }
        updateLangUI();
        document.addEventListener('languageChanged', updateLangUI);
        document.addEventListener('i18nApplied', updateLangUI);

        // Hamburger menu
        const hamburger = document.getElementById('hamburgerBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileOverlay');

        if (hamburger && mobileMenu && overlay) {
            function openMobile() {
                hamburger.classList.add('hamburger-active');
                mobileMenu.classList.add('open');
                overlay.classList.add('open');
                overlay.classList.remove('pointer-events-none', 'opacity-0');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
                document.body.style.overflow = 'hidden';
            }
            function closeMobile() {
                hamburger.classList.remove('hamburger-active');
                mobileMenu.classList.remove('open');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                document.body.style.overflow = '';
            }

            hamburger.addEventListener('click', function () {
                mobileMenu.classList.contains('open') ? closeMobile() : openMobile();
            });
            overlay.addEventListener('click', closeMobile);
            mobileMenu.querySelectorAll('.mobile-link-ds').forEach(function (link) {
                link.addEventListener('click', closeMobile);
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && mobileMenu.classList.contains('open')) closeMobile();
            });
        }
    });

    function switchLang(code) {
        if (typeof i18n !== 'undefined') {
            i18n.setLang(code);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }
</script>
