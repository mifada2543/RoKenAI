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

        <!-- Center: Navigation Links -->
        <div class="top-nav-links">
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
            <a href="profile.php" class="nav-link" id="navProfile">
                <i data-lucide="file-text"></i>
                <span data-i18n="nav.history">Riwayat</span>
            </a>
        </div>

        <!-- Right: Actions -->
        <div class="top-nav-actions">
            <!-- Language Switcher -->
            <button class="lang-btn" id="langBtn" title="Ganti Bahasa" aria-label="Language">
                <i data-lucide="globe"></i>
                <span id="langLabel">ID</span>
            </button>

            <!-- Login -->
            <a href="auth/login.php" class="profile-btn" title="Masuk" data-i18n-title="auth.signIn">
                <i data-lucide="user"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Language Switcher Dropdown -->
<div class="lang-dropdown" id="langDropdown">
    <div class="lang-dropdown-inner">
        <div class="lang-dropdown-title" data-i18n="settings.language">Bahasa</div>
        <button class="lang-option" data-lang="id" onclick="switchLang('id')">
            <span class="lang-flag">🇮🇩</span>
            <span class="lang-name">Bahasa Indonesia</span>
            <i data-lucide="check" class="lang-check" id="checkId"></i>
        </button>
        <button class="lang-option" data-lang="en" onclick="switchLang('en')">
            <span class="lang-flag">🇬🇧</span>
            <span class="lang-name">English</span>
            <i data-lucide="check" class="lang-check" id="checkEn"></i>
        </button>
    </div>
</div>

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

    /* Language Button */
    .lang-btn {
        display: flex; align-items: center; gap: 5px;
        height: 38px; padding: 0 12px;
        border-radius: 8px; border: 1px solid #E2E8F0;
        background: #F8FAFC; color: #475569;
        font-size: 12px; font-weight: 700;
        cursor: pointer; font-family: var(--font-body);
        transition: all 0.2s ease;
    }
    .lang-btn:hover { border-color: #3B82F6; color: #1D4ED8; background: #DBEAFE; }
    .lang-btn i { width: 15px; height: 15px; }

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

    /* Language Dropdown */
    .lang-dropdown {
        position: fixed; top: 70px; right: 20px;
        background: #fff; border: 1px solid #E2E8F0;
        border-radius: 12px; box-shadow: 0 8px 24px rgba(15,23,42,0.12);
        z-index: 200; min-width: 200px;
        display: none; animation: slideDown 0.2s ease;
    }
    .lang-dropdown.open { display: block; }
    .lang-dropdown-inner { padding: 10px; }
    .lang-dropdown-title {
        font-size: 10px; font-weight: 700; color: #94A3B8;
        text-transform: uppercase; letter-spacing: 0.06em;
        padding: 4px 8px 10px; font-family: var(--font-heading);
    }
    .lang-option {
        display: flex; align-items: center; gap: 10px;
        width: 100%; padding: 10px 12px;
        border-radius: 8px; border: none; background: transparent;
        cursor: pointer; text-align: left; font-family: var(--font-body);
        font-size: 13px; color: #0F172A; transition: all 0.15s ease;
    }
    .lang-option:hover { background: #F8FAFC; }
    .lang-option[data-lang].active-lang { background: #DBEAFE; color: #1D4ED8; font-weight: 600; }
    .lang-flag { font-size: 18px; flex-shrink: 0; }
    .lang-name { flex: 1; }
    .lang-check { width: 14px; height: 14px; color: #1D4ED8; display: none; }
    .lang-option.active-lang .lang-check { display: block; }

    /* Responsive */
    @media (max-width: 768px) {
        .nav-link span { display: none; }
        .nav-link { padding: 8px 12px; }
        .nav-link i { width: 18px; height: 18px; }
    }
    @media (max-width: 480px) {
        .top-nav-links { gap: 0; }
        .nav-link { padding: 8px 10px; }
        .top-nav { height: 58px; }
        .top-nav-inner { padding: 0 12px; }
        .top-nav-logo span { font-size: 17px; }
        .top-nav-logo img { height: 28px; }
        .lang-dropdown { right: 12px; }
    }
</style>

<script>
    // ================================================================
    // NAVBAR — Active link + Language Switcher
    // ================================================================
    document.addEventListener('DOMContentLoaded', function () {
        // — Active nav —
        const path = window.location.pathname.split('/').pop();
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
            const el = document.getElementById(activeId);
            if (el) el.classList.add('active');
        }

        // — Language switcher —
        const langBtn      = document.getElementById('langBtn');
        const langDropdown = document.getElementById('langDropdown');
        const langLabel    = document.getElementById('langLabel');

        langBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            langDropdown.classList.toggle('open');
            lucide.createIcons();
        });

        document.addEventListener('click', function () {
            langDropdown.classList.remove('open');
        });

        // Update button label + active state on init & change
        function updateLangUI() {
            const lang = i18n.getLang();
            langLabel.textContent = lang.toUpperCase();
            document.querySelectorAll('.lang-option').forEach(function (opt) {
                opt.classList.toggle('active-lang', opt.dataset.lang === lang);
            });
        }

        updateLangUI();
        document.addEventListener('languageChanged', updateLangUI);
        document.addEventListener('i18nApplied', updateLangUI);
    });

    function switchLang(code) {
        i18n.setLang(code);
        document.getElementById('langDropdown').classList.remove('open');
        lucide.createIcons();
    }
</script>
