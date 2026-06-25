<style>
    /* ===== Navbar Premium ===== */
    .roken-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        height: 68px;
        background: var(--bg-navbar);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border-bottom: 1px solid var(--border-subtle);
    }

    .nav-inner {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 24px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nav-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .nav-logo img {
        height: 44px;
        width: auto;
        object-fit: contain;
    }

    .nav-logo span {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.03em;
        background: linear-gradient(135deg, #FACC15 0%, #6366F1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .nav-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .nav-icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--bg-input);
        border: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.25s ease;
    }

    .nav-icon-btn:hover {
        background: var(--bg-hover);
        border-color: rgba(250, 204, 21, 0.2);
        color: var(--brand-yellow);
    }

    .nav-icon-btn i {
        width: 20px;
        height: 20px;
    }

    /* ===== Profile Circle ===== */
    .profile-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(250, 204, 21, 0.15), rgba(99, 102, 241, 0.15));
        border: 2px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
        color: var(--text-secondary);
    }

    .profile-circle:hover {
        border-color: rgba(250, 204, 21, 0.4);
        box-shadow: 0 0 20px rgba(250, 204, 21, 0.1);
    }

    .profile-circle i {
        width: 18px;
        height: 18px;
    }

    /* ===== AI Status Badge ===== */
    .ai-status {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 100px;
        background: rgba(34, 197, 94, 0.08);
        border: 1px solid rgba(34, 197, 94, 0.15);
        font-size: 12px;
        font-weight: 500;
        color: #22C55E;
    }

    @media (min-width: 768px) {
        .ai-status {
            display: flex;
        }
    }

    /* ===== Glassmorphism Sidebar ===== */
    #sidebar {
        position: fixed;
        top: 12px;
        bottom: 12px;
        left: 12px;
        width: 280px;
        z-index: 200;
        background: var(--bg-sidebar);
        backdrop-filter: blur(32px);
        -webkit-backdrop-filter: blur(32px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        transform: translateX(-300px);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-lg);
    }

    #sidebar.active {
        transform: translateX(0);
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-subtle);
    }

    .sidebar-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }

    .sidebar-close-btn {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: var(--bg-input);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .sidebar-close-btn:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .sidebar-close-btn i {
        width: 18px;
        height: 18px;
    }

    /* ===== Sidebar Menu Items ===== */
    .nav-section-label {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 8px;
        padding: 0 12px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 14px;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        position: relative;
    }

    .menu-item:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .menu-item.active {
        background: var(--bg-active);
        color: var(--brand-yellow);
    }

    .menu-item .menu-icon {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .menu-item .menu-icon i {
        width: 18px;
        height: 18px;
    }

    .menu-item .menu-icon.chat-icon {
        background: rgba(250, 204, 21, 0.12);
        color: var(--brand-yellow);
    }

    .menu-item .menu-icon.examples-icon {
        background: rgba(99, 102, 241, 0.12);
        color: var(--brand-indigo);
    }

    .menu-item .menu-icon.news-icon {
        background: rgba(148, 163, 184, 0.1);
        color: var(--text-secondary);
    }

    .menu-item .menu-badge {
        margin-left: auto;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 100px;
        background: rgba(250, 204, 21, 0.12);
        color: var(--brand-yellow);
    }

    /* ===== Sidebar Divider ===== */
    .sidebar-divider {
        height: 1px;
        background: var(--border-subtle);
        margin: 12px 0;
    }

    /* ===== Sidebar Footer / Profile Widget ===== */
    .sidebar-profile {
        margin-top: auto;
        padding: 14px;
        border-radius: 16px;
        background: var(--bg-input);
        border: 1px solid var(--border-muted);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .profile-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(250, 204, 21, 0.2), rgba(99, 102, 241, 0.2));
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--text-secondary);
        position: relative;
    }

    .profile-avatar i {
        width: 18px;
        height: 18px;
    }

    .profile-avatar .online-dot {
        position: absolute;
        bottom: -1px;
        right: -1px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #22C55E;
        border: 2px solid var(--profile-border);
    }

    .profile-info {
        flex: 1;
        min-width: 0;
    }

    .profile-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .profile-status {
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ===== Overlay ===== */
    #overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 150;
        display: none;
    }

    #overlay.active {
        display: block;
    }

    /* ===== Content Wrapper ===== */
    #content-wrapper {
        padding-top: 68px;
        min-height: 100vh;
        transition: filter 0.3s ease;
    }

    #content-wrapper.blurred {
        filter: blur(6px);
        pointer-events: none;
        user-select: none;
    }

    /* ===== Skip Sidebar Padding ===== */
    @media (min-width: 1024px) {
        .roken-navbar {
            padding-left: 0;
        }
    }

    /* ===== Mobile menu button highlight ===== */
    .menu-toggle-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--bg-input);
        border: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.25s ease;
    }

    .menu-toggle-btn:hover {
        background: var(--bg-hover);
        border-color: rgba(250, 204, 21, 0.2);
        color: var(--brand-yellow);
    }

    .menu-toggle-btn i {
        width: 22px;
        height: 22px;
    }

    /* ===== Notification System ===== */
    .nav-notif-wrap {
        position: relative;
    }

    .notif-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        min-width: 18px;
        height: 18px;
        border-radius: 100px;
        background: #EF4444;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid #0B0F19;
        box-shadow: 0 0 8px rgba(239, 68, 68, 0.4);
        animation: notifPop 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    /* ===== Settings Overlay ===== */
    #settingsOverlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 500;
        display: none;
        animation: fadeIn 0.25s ease;
    }

    #settingsOverlay.active { display: block; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ===== Settings Modal ===== */
    #settingsModal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 520px;
        max-width: calc(100vw - 32px);
        max-height: calc(100vh - 32px);
        background: var(--bg-sidebar);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
        z-index: 600;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #settingsModal.active { display: flex; }

    .settings-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border-muted);
        flex-shrink: 0;
    }

    .settings-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .settings-close {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: var(--bg-input);
        border: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .settings-close:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .settings-close i { width: 18px; height: 18px; }

    .settings-body {
        padding: 20px 24px 24px;
        overflow-y: auto;
        flex: 1;
    }

    .settings-section {
        margin-bottom: 24px;
    }

    .settings-section:last-child { margin-bottom: 0; }

    .settings-section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .settings-section-label i {
        width: 18px;
        height: 18px;
        color: var(--brand-yellow);
    }

    .settings-section-desc {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 14px;
        line-height: 1.5;
    }

    /* ===== Language Search & Select ===== */
    .lang-search-wrap {
        position: relative;
        margin-bottom: 10px;
    }

    .lang-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: var(--text-muted);
        pointer-events: none;
    }

    .lang-search {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border-radius: 12px;
        background: var(--bg-input);
        border: 1px solid var(--border-subtle);
        color: var(--text-primary);
        font-size: 13px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none;
        transition: all 0.2s ease;
    }

    .lang-search::placeholder { color: var(--text-muted); }
    .lang-search:focus {
        border-color: rgba(250, 204, 21, 0.3);
        box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.06);
    }

    .lang-list {
        max-height: 200px;
        overflow-y: auto;
        border-radius: 12px;
        border: 1px solid var(--border-muted);
        background: var(--bg-input);
    }

    .lang-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        cursor: pointer;
        transition: all 0.15s ease;
        border-bottom: 1px solid var(--border-muted);
    }

    .lang-item:last-child { border-bottom: none; }

    .lang-item:hover { background: var(--bg-hover); }
    .lang-item.active {
        background: rgba(250, 204, 21, 0.08);
        color: var(--brand-yellow);
    }

    .lang-item .lang-check {
        width: 18px;
        height: 18px;
        min-width: 18px;
        border-radius: 50%;
        border: 2px solid var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .lang-item.active .lang-check {
        border-color: var(--brand-yellow);
        background: var(--brand-yellow);
    }

    .lang-item.active .lang-check::after {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #0B0F19;
    }

    .lang-item .lang-native {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .lang-item .lang-name {
        font-size: 11px;
        color: var(--text-muted);
        margin-left: auto;
    }

    .lang-no-results {
        padding: 20px;
        text-align: center;
        font-size: 13px;
        color: var(--text-muted);
    }

    /* ===== Model AI Select ===== */
    .model-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .model-option {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid var(--border-subtle);
        background: var(--bg-input);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .model-option:hover {
        border-color: var(--border-color);
        background: var(--bg-hover);
    }

    .model-option.active {
        border-color: rgba(250, 204, 21, 0.3);
        background: rgba(250, 204, 21, 0.06);
    }

    .model-option .model-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .model-option .model-icon i { width: 20px; height: 20px; }

    .model-option .model-info { flex: 1; min-width: 0; }
    .model-option .model-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }
    .model-option .model-desc {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .model-option .model-radio {
        width: 20px;
        height: 20px;
        min-width: 20px;
        border-radius: 50%;
        border: 2px solid var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .model-option.active .model-radio {
        border-color: var(--brand-yellow);
    }

    .model-option.active .model-radio::after {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--brand-yellow);
    }

    .model-option.coming-soon {
        opacity: 0.5;
        cursor: not-allowed;
        position: relative;
    }

    .model-option.coming-soon .model-badge {
        font-size: 9px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 100px;
        background: rgba(250, 204, 21, 0.12);
        color: var(--brand-yellow);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    @keyframes notifPop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes notifShake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(6deg); }
        75% { transform: rotate(-6deg); }
    }

    .notif-bell-shake {
        animation: notifShake 0.4s ease;
    }

    /* ===== Notification Dropdown ===== */
    .notif-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: -8px;
        width: 360px;
        max-height: 420px;
        background: var(--bg-sidebar);
        backdrop-filter: blur(32px);
        -webkit-backdrop-filter: blur(32px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        display: none;
        flex-direction: column;
        z-index: 300;
        overflow: hidden;
        animation: fadeInUp 0.2s ease;
    }

    .notif-dropdown.active {
        display: flex;
    }

    .notif-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px 12px;
        border-bottom: 1px solid var(--border-muted);
    }

    .notif-dropdown-header span {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .notif-dropdown-header button {
        background: none;
        border: none;
        font-size: 12px;
        color: var(--text-muted);
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .notif-dropdown-header button:hover {
        color: #EF4444;
    }

    .notif-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
        max-height: 320px;
    }

    .notif-item {
        display: flex;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 4px;
        align-items: flex-start;
    }

    .notif-item:hover {
        background: var(--bg-hover);
    }

    .notif-item.unread {
        background: rgba(250, 204, 21, 0.04);
    }

    .notif-item .notif-dot {
        width: 8px;
        height: 8px;
        min-width: 8px;
        border-radius: 50%;
        background: var(--brand-yellow);
        margin-top: 6px;
    }

    .notif-item.unread .notif-dot {
        display: block;
    }

    .notif-item .notif-content {
        flex: 1;
        min-width: 0;
    }

    .notif-item .notif-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .notif-item .notif-body {
        font-size: 12px;
        color: var(--text-secondary);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .notif-item .notif-time {
        font-size: 10px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .notif-item .notif-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notif-item .notif-icon i {
        width: 18px;
        height: 18px;
    }

    .notif-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 32px 16px;
        color: var(--text-muted);
        font-size: 13px;
    }

    @media (max-width: 480px) {
        .notif-dropdown {
            width: calc(100vw - 32px);
            right: -60px;
        }
    }

    /* ===== Active page detection ===== */
    .menu-item.active-page-chat {
        background: rgba(250, 204, 21, 0.08);
        color: var(--brand-yellow);
    }
    .menu-item.active-page-chat .menu-icon.chat-icon {
        background: rgba(250, 204, 21, 0.2);
    }
    .menu-item.active-page {
        background: rgba(250, 204, 21, 0.06);
        color: var(--brand-yellow);
    }
</style>

<!-- Background Orbs -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<!-- Overlay -->
<div id="overlay" onclick="toggleSidebar()"></div>

<!-- Glassmorphism Sidebar -->
<div id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <span class="sidebar-title" data-i18n="nav.navigation">Navigation</span>
        <button class="sidebar-close-btn" onclick="toggleSidebar()" aria-label="Close">
            <i data-lucide="x"></i>
        </button>
    </div>

    <!-- Main Navigation -->
    <div class="nav-section-label" data-i18n="nav.mainMenu">Main Menu</div>
    <nav style="flex:1;">
        <a href="chat.php" class="menu-item" id="menuChat">
            <div class="menu-icon chat-icon"><i data-lucide="message-square"></i></div>
            <span data-i18n="nav.chat">Chat</span>
            <span class="menu-badge">AI</span>
        </a>
        <a href="examples.php" class="menu-item">
            <div class="menu-icon examples-icon"><i data-lucide="book-open"></i></div>
            <span data-i18n="nav.examples">Examples</span>
        </a>
        <a href="news.php" class="menu-item">
            <div class="menu-icon news-icon"><i data-lucide="newspaper"></i></div>
            <span data-i18n="nav.news">News</span>
        </a>

        <div class="sidebar-divider"></div>
        <div class="nav-section-label" data-i18n="nav.tools">Tools</div>

        <a href="upload.php" class="menu-item">
            <div class="menu-icon" style="background:rgba(99, 102, 241, 0.1);color:var(--brand-indigo);"><i data-lucide="upload"></i></div>
            <span data-i18n="nav.upload">Upload</span>
        </a>
        <a href="index.php" class="menu-item">
            <div class="menu-icon" style="background:rgba(250, 204, 21, 0.08);color:var(--brand-yellow);"><i data-lucide="layout-dashboard"></i></div>
            <span data-i18n="nav.dashboard">Dashboard</span>
        </a>
    </nav>

    <!-- Profile Widget -->
    <a href="auth/login.php" class="sidebar-profile" style="text-decoration:none;">
        <div class="profile-avatar">
            <i data-lucide="user"></i>
            <div class="online-dot"></div>
        </div>
        <div class="profile-info">
            <div class="profile-name" data-i18n="nav.signIn">Sign In</div>
            <div class="profile-status">
                <span class="status-dot" style="width:6px;height:6px;"></span>
                <span data-i18n="nav.aiOnline">AI Server Online</span>
            </div>
        </div>
    </a>
</div>

<!-- Navbar -->
<nav class="roken-navbar">
    <div class="nav-inner">
        <div style="display:flex;align-items:center;gap:16px;">
            <button class="menu-toggle-btn" onclick="toggleSidebar()" aria-label="Menu">
                <i data-lucide="menu"></i>
            </button>
            <a href="index.php" class="nav-logo">
                <img src="assets/Logo.png" alt="RoKenAI Logo" loading="lazy">
                <span>RoKenAI</span>
            </a>
        </div>

        <div class="nav-actions">
            <div class="ai-status">
                <span class="status-dot" style="width:6px;height:6px;"></span>
                <span data-i18n="nav.aiBadge">YOLOv8 Model Active</span>
            </div>
            <div class="nav-notif-wrap">
                <button class="nav-icon-btn" id="notifBell" aria-label="Notifications" onclick="toggleNotifDropdown()">
                    <i data-lucide="bell"></i>
                    <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
                </button>
                <!-- Notification Dropdown -->
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-dropdown-header">
                        <span data-i18n="notif.title">Notifications</span>
                        <button onclick="clearAllNotifs()" data-i18n="notif.clearAll">Clear all</button>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="notif-empty">
                            <i data-lucide="bell-off" style="width:20px;height:20px;"></i>
                            <span data-i18n="notif.empty">No notifications yet</span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="nav-icon-btn" id="settingsBtn" aria-label="Settings" onclick="toggleSettings()">
                <i data-lucide="settings"></i>
            </button>
            <button class="nav-icon-btn" id="themeToggle" aria-label="Toggle theme">
                <i data-lucide="moon" id="themeIcon"></i>
            </button>
            <a href="auth/login.php" class="profile-circle" title="Sign in">
                <i data-lucide="user"></i>
            </a>
        </div>
    </div>
</nav>

<!-- ===== Settings Modal ===== -->
<div id="settingsOverlay" onclick="closeSettings()"></div>
<div id="settingsModal">
    <div class="settings-header">
        <h2 data-i18n="settings.title">Settings</h2>
        <button class="settings-close" onclick="closeSettings()" aria-label="Close">
            <i data-lucide="x"></i>
        </button>
    </div>
    <div class="settings-body" id="settingsBody">
        <!-- Language Section -->
        <div class="settings-section">
            <div class="settings-section-label">
                <i data-lucide="globe"></i>
                <span data-i18n="settings.language">Language</span>
            </div>
            <div class="settings-section-desc" data-i18n="settings.languageDesc">Choose your preferred language for the interface.</div>
            <div class="lang-search-wrap">
                <i data-lucide="search"></i>
                <input class="lang-search" type="text" id="langSearch" data-i18n="settings.searchLanguage" data-i18n-attr="placeholder" placeholder="Search language..." oninput="filterLanguages()">
            </div>
            <div class="lang-list" id="langList"></div>
        </div>

        <!-- Model AI Section -->
        <div class="settings-section">
            <div class="settings-section-label">
                <i data-lucide="cpu"></i>
                <span data-i18n="settings.modelAI">AI Model</span>
            </div>
            <div class="settings-section-desc" data-i18n="settings.modelAIDesc">Select the AI model for detection and analysis.</div>
            <div class="model-list">
                <div class="model-option active" onclick="selectModel(this, 'yolov8')">
                    <div class="model-icon" style="background:rgba(250,204,21,0.12);color:var(--brand-yellow);">
                        <i data-lucide="bot"></i>
                    </div>
                    <div class="model-info">
                        <div class="model-name" data-i18n="settings.modelDefault">YOLOv8 (Default)</div>
                        <div class="model-desc">Ultralytics YOLOv8 • Real-time object detection</div>
                    </div>
                    <div class="model-radio"></div>
                </div>
                <div class="model-option coming-soon" onclick="return false;">
                    <div class="model-icon" style="background:rgba(99,102,241,0.1);color:var(--brand-indigo);">
                        <i data-lucide="sparkles"></i>
                    </div>
                    <div class="model-info">
                        <div class="model-name">YOLOv9</div>
                        <div class="model-desc" data-i18n="settings.modelPlaceholder">More models coming soon...</div>
                    </div>
                    <span class="model-badge">Soon</span>
                </div>
                <div class="model-option coming-soon" onclick="return false;">
                    <div class="model-icon" style="background:rgba(34,197,94,0.1);color:#22C55E;">
                        <i data-lucide="cpu"></i>
                    </div>
                    <div class="model-info">
                        <div class="model-name">OpenVINO</div>
                        <div class="model-desc">Intel OpenVINO • Optimized inference</div>
                    </div>
                    <span class="model-badge">Soon</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('overlay').classList.toggle('active');
        const cw = document.getElementById('content-wrapper');
        if (cw) cw.classList.toggle('blurred');
    }

    // ===== Theme Toggle =====
    function getTheme() {
        return localStorage.getItem('roken-theme') || 'dark';
    }

    function setTheme(theme) {
        const html = document.documentElement;
        const icon = document.getElementById('themeIcon');
        if (theme === 'light') {
            html.setAttribute('data-theme', 'light');
            icon.setAttribute('data-lucide', 'sun');
            document.querySelector('meta[name="theme-color"]')?.setAttribute('content', '#F5F7FA');
        } else {
            html.removeAttribute('data-theme');
            icon.setAttribute('data-lucide', 'moon');
            document.querySelector('meta[name="theme-color"]')?.setAttribute('content', '#0B0F19');
        }
        localStorage.setItem('roken-theme', theme);
        // Re-render lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function toggleTheme() {
        const current = getTheme();
        setTheme(current === 'dark' ? 'light' : 'dark');
    }

    // ===== Notification System =====
    let notifCount = 0;
    let notifData = [];

    function addNotification(title, body, iconType, link) {
        const icons = {
            'chat': { bg: 'rgba(250,204,21,0.12)', color: 'var(--brand-yellow)', lucide: 'message-square' },
            'ai': { bg: 'rgba(99,102,241,0.12)', color: 'var(--brand-indigo)', lucide: 'sparkles' },
            'upload': { bg: 'rgba(34,197,94,0.12)', color: '#22C55E', lucide: 'upload' },
            'alert': { bg: 'rgba(239,68,68,0.12)', color: '#EF4444', lucide: 'alert-circle' },
            'info': { bg: 'rgba(250,204,21,0.12)', color: 'var(--brand-yellow)', lucide: 'bell' },
        };
        const ic = icons[iconType] || icons.info;

        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

        notifData.unshift({
            id: Date.now(),
            title: title,
            body: body,
            icon: ic,
            time: timeStr,
            link: link || null,
            read: false
        });

        notifCount++;
        updateNotifUI();

        // Shake the bell
        const bell = document.getElementById('notifBell');
        if (bell) {
            bell.classList.add('notif-bell-shake');
            setTimeout(() => bell.classList.remove('notif-bell-shake'), 400);
        }

        // Play notification sound (optional, simple approach)
        try {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('RoKenAI', { body: title + ': ' + body, icon: 'assets/Logo.png' });
            }
        } catch(e) {}
    }

    function updateNotifUI() {
        const badge = document.getElementById('notifBadge');
        const list = document.getElementById('notifList');
        if (!badge || !list) return;

        if (notifCount > 0) {
            badge.style.display = 'flex';
            badge.textContent = notifCount > 99 ? '99+' : notifCount;
        } else {
            badge.style.display = 'none';
        }

        if (notifData.length === 0) {
            const emptyText = (typeof i18n !== 'undefined') ? i18n.t('notif.empty') : 'No notifications yet';
            list.innerHTML = `
                <div class="notif-empty">
                    <i data-lucide="bell-off" style="width:20px;height:20px;"></i>
                    <span>${emptyText}</span>
                </div>
            `;
        } else {
            list.innerHTML = notifData.map(n => `
                <div class="notif-item ${n.read ? '' : 'unread'}" onclick="${n.link ? "window.location.href='" + n.link + "'" : ''}">
                    <div class="notif-icon" style="background:${n.icon.bg};color:${n.icon.color};">
                        <i data-lucide="${n.icon.lucide}"></i>
                    </div>
                    <div class="notif-content">
                        <div class="notif-title">${n.title}</div>
                        <div class="notif-body">${n.body}</div>
                        <div class="notif-time">${n.time}</div>
                    </div>
                    <div class="notif-dot" style="display:${n.read ? 'none' : 'block'}"></div>
                </div>
            `).join('');
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function toggleNotifDropdown() {
        const dd = document.getElementById('notifDropdown');
        if (dd) {
            dd.classList.toggle('active');
            // Mark all as read when opening
            if (dd.classList.contains('active')) {
                notifCount = 0;
                notifData.forEach(n => n.read = true);
                updateNotifUI();
            }
        }
    }

    function clearAllNotifs() {
        notifData = [];
        notifCount = 0;
        updateNotifUI();
    }

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const wrap = document.querySelector('.nav-notif-wrap');
        const dd = document.getElementById('notifDropdown');
        if (wrap && dd && !wrap.contains(e.target) && dd.classList.contains('active')) {
            dd.classList.remove('active');
        }
    });

    // ===== Settings Modal =====
    function toggleSettings() {
        const modal = document.getElementById('settingsModal');
        const overlay = document.getElementById('settingsOverlay');
        if (!modal || !overlay) return;
        const isOpen = modal.classList.contains('active');
        if (isOpen) {
            closeSettings();
        } else {
            openSettings();
        }
    }

    function openSettings() {
        const modal = document.getElementById('settingsModal');
        const overlay = document.getElementById('settingsOverlay');
        if (!modal || !overlay) return;
        modal.classList.add('active');
        overlay.classList.add('active');
        populateLanguages();
        document.body.style.overflow = 'hidden';
    }

    function closeSettings() {
        const modal = document.getElementById('settingsModal');
        const overlay = document.getElementById('settingsOverlay');
        if (!modal || !overlay) return;
        modal.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close settings with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('settingsModal');
            if (modal && modal.classList.contains('active')) {
                closeSettings();
            }
        }
    });

    function populateLanguages(filter) {
        const list = document.getElementById('langList');
        if (!list) return;
        const query = (filter || '').toLowerCase();
        const langs = typeof LANGUAGES !== 'undefined' ? LANGUAGES : [];
        const current = typeof i18n !== 'undefined' ? i18n.getLang() : 'en';

        if (langs.length === 0) {
            list.innerHTML = '<div class="lang-no-results">Loading languages...</div>';
            return;
        }

        const filtered = query ? langs.filter(l =>
            l.name.toLowerCase().includes(query) ||
            l.native.toLowerCase().includes(query) ||
            l.code.toLowerCase().includes(query)
        ) : langs;

        if (filtered.length === 0) {
            list.innerHTML = '<div class="lang-no-results">No languages found</div>';
            return;
        }

        list.innerHTML = filtered.map(l => `
            <div class="lang-item ${l.code === current ? 'active' : ''}" onclick="selectLanguage('${l.code}')">
                <div class="lang-check"></div>
                <span class="lang-native">${l.native}</span>
                <span class="lang-name">${l.name}</span>
            </div>
        `).join('');
    }

    function filterLanguages() {
        const input = document.getElementById('langSearch');
        if (input) populateLanguages(input.value);
    }

    function selectLanguage(code) {
        if (typeof i18n !== 'undefined') {
            i18n.setLang(code);
        }
        populateLanguages();
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function selectModel(el, model) {
        document.querySelectorAll('.model-option').forEach(m => m.classList.remove('active'));
        if (el && !el.classList.contains('coming-soon')) {
            el.classList.add('active');
        }
    }

    // Auto-highlight current page in sidebar
    document.addEventListener('DOMContentLoaded', function() {
        // Apply saved theme
        setTheme(getTheme());

        // Theme toggle button
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleTheme);
        }

        const path = window.location.pathname.split('/').pop();
        document.querySelectorAll('.menu-item').forEach(function(item) {
            const href = item.getAttribute('href');
            if (href === path) {
                item.classList.add('active');
            }
        });

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    });
</script>
