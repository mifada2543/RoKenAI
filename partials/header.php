<!-- ================================================================
     RoKenAI — Navbar Putih (Light Mode sesuai desain.md)
     ================================================================ -->

<nav class="top-nav">
    <div class="top-nav-inner">
        <!-- Left: Logo & Brand -->
        <a href="index.php" class="top-nav-logo">
            <img src="assets/Logo.png" alt="RoKenAI">
            <span>RoKen<span class="text-accent">AI</span></span>
        </a>

        <!-- Center: Navigation Links -->
        <div class="top-nav-links">
            <a href="index.php" class="nav-link" id="navDashboard">
                <i data-lucide="layout-dashboard"></i>
                <span>Beranda</span>
            </a>
            <a href="upload.php" class="nav-link" id="navUpload">
                <i data-lucide="camera"></i>
                <span>Lapor Kerusakan</span>
            </a>
            <a href="chat.php" class="nav-link" id="navChat">
                <i data-lucide="bot"></i>
                <span>Tanya AI</span>
            </a>
            <a href="examples.php" class="nav-link" id="navExamples">
                <i data-lucide="book-open"></i>
                <span>Panduan</span>
            </a>
            <a href="news.php" class="nav-link" id="navNews">
                <i data-lucide="newspaper"></i>
                <span>Berita</span>
            </a>
            <a href="profile.php" class="nav-link" id="navProfile">
                <i data-lucide="file-text"></i>
                <span>Riwayat</span>
            </a>
        </div>

        <!-- Right: Actions -->
        <div class="top-nav-actions">
            <!-- Profile / Login -->
            <a href="auth/login.php" class="profile-btn" title="Masuk">
                <i data-lucide="user"></i>
            </a>
        </div>
    </div>
</nav>

<style>
    /* ================================================================
       RoKenAI — Navbar Putih (Light Mode)
       ================================================================ */

    .top-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        height: 64px;
        background: var(--surface);
        border-bottom: 1px solid var(--line-200);
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }

    .top-nav-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    /* ===== Logo ===== */
    .top-nav-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex-shrink: 0;
    }
    .top-nav-logo img { height: 32px; width: auto; }
    .top-nav-logo span {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--ink-900);
        font-family: var(--font-heading);
    }
    .top-nav-logo .text-accent { color: var(--primary-700); }

    /* ===== Navigation Links ===== */
    .top-nav-links {
        display: flex;
        align-items: center;
        gap: 2px;
    }
    .nav-link {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        color: var(--ink-600);
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .nav-link i { width: 16px; height: 16px; }
    .nav-link:hover {
        color: var(--primary-700);
        background: var(--primary-100);
    }
    .nav-link.active {
        background: var(--primary-100);
        color: var(--primary-700);
        font-weight: 600;
    }
    .nav-link.active i { color: var(--primary-700); }

    @media (max-width: 768px) {
        .nav-link span { display: none; }
        .nav-link { padding: 8px 12px; }
        .nav-link i { width: 18px; height: 18px; }
    }
    @media (max-width: 480px) {
        .top-nav-links { gap: 0px; }
        .nav-link { padding: 8px 10px; }
        .top-nav { height: 58px; }
        .top-nav-inner { padding: 0 12px; }
        .top-nav-logo span { font-size: 17px; }
        .top-nav-logo img { height: 28px; }
    }

    /* ===== Right Actions ===== */
    .top-nav-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .profile-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--surface-muted);
        border: 1px solid var(--line-200);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--ink-600);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .profile-btn:hover {
        border-color: var(--primary-500);
        color: var(--primary-700);
        background: var(--primary-100);
    }
    .profile-btn i { width: 18px; height: 18px; }
</style>

<script>
    // ================================================================
    // ACTIVE NAV LINK — highlight menu sesuai halaman yang dibuka
    // ================================================================
    document.addEventListener('DOMContentLoaded', function() {
        const path = window.location.pathname.split('/').pop();
        const navMap = {
            'index.php': 'navDashboard',
            'upload.php': 'navUpload',
            'chat.php': 'navChat',
            'examples.php': 'navExamples',
            'news.php': 'navNews',
            'profile.php': 'navProfile'
        };
        const activeId = navMap[path];
        if (activeId) {
            const el = document.getElementById(activeId);
            if (el) el.classList.add('active');
        }
    });
</script>
