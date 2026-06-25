<meta name="description" content="RoKenAI - Platform AI untuk Pelaporan Jalan Rusak berbasis Computer Vision">
<meta name="theme-color" content="#1D4ED8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="manifest" href="assets/manifest.json">
<link rel="icon" type="image/png" href="assets/Logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!-- Font: Plus Jakarta Sans (heading), Inter (body), IBM Plex Mono (data) sesuai desain.md -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<script src="assets/js/lucide.js"></script>
<script src="assets/js/i18n.js"></script>

<style>
    /* ================================================================
       RoKenAI Design System v3 — Light Mode | Civic-Tech 2026
       Acuan: desain.md — warna, tipografi, radius, shadow
       ================================================================ */

    /* ----- CSS Custom Properties (Light Mode) sesuai desain.md ----- */
    :root {
        /* Core palette — warna utama */
        --primary-700: #1D4ED8;   /* Warna utama brand, navbar, tombol primer */
        --primary-500: #3B82F6;   /* Hover state, link aktif, ikon */
        --primary-100: #DBEAFE;   /* Background section terang, badge info */

        /* Surface */
        --surface: #FFFFFF;        /* Background utama card & halaman */
        --surface-muted: #F8FAFC;  /* Background halaman (sedikit abu agar card menonjol) */

        /* Teks */
        --ink-900: #0F172A;        /* Teks judul/heading */
        --ink-600: #475569;        /* Teks body/paragraf */

        /* Border */
        --line-200: #E2E8F0;      /* Border, divider */

        /* Aksen marka jalan — signature element */
        --marka-400: #FACC15;      /* Kuning marka — khusus di progress line & highlight */

        /* Status */
        --status-danger: #DC2626;  /* Rusak Parah */
        --status-warning: #F59E0B; /* Rusak Sedang / Menunggu Verifikasi */
        --status-progress: #2563EB;/* Sedang Diperbaiki */
        --status-success: #16A34A; /* Selesai Diperbaiki */

        /* Derived / semantic tokens */
        --primary: var(--primary-700);
        --primary-hover: var(--primary-500);
        --primary-light: var(--primary-500);
        --primary-bg: var(--primary-100);
        --bg-page: var(--surface-muted);
        --bg-card: var(--surface);
        --bg-card-hover: #F1F5F9;
        --text-primary: var(--ink-900);
        --text-body: var(--ink-600);
        --text-secondary: var(--ink-600);
        --text-muted: #94A3B8;
        --text-dim: #CBD5E1;
        --border-color: var(--line-200);
        --border-subtle: 1px solid var(--line-200);

        /* Status semantic */
        --success: var(--status-success);
        --warning: var(--status-warning);
        --danger: var(--status-danger);
        --info: var(--primary-500);

        /* Shadow — tipis aja, jangan dramatis */
        --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.04);
        --shadow-md: 0 1px 3px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.04);
        --shadow-lg: 0 4px 12px rgba(15, 23, 42, 0.08), 0 2px 4px rgba(15, 23, 42, 0.04);
        --shadow-card: 0 1px 3px rgba(15, 23, 42, 0.08);
        --shadow-glow: 0 0 0 1px rgba(29, 78, 216, 0.08), 0 4px 12px rgba(29, 78, 216, 0.06);

        /* Radius — sesuai desain.md */
        --radius-sm: 8px;       /* Button, input */
        --radius-md: 10px;
        --radius-lg: 12px;      /* Card */
        --radius-xl: 16px;
        --radius-2xl: 20px;
        --radius-full: 9999px;

        /* Tipografi */
        --font-heading: 'Plus Jakarta Sans', 'Inter', sans-serif;
        --font-body: 'Inter', 'Plus Jakarta Sans', sans-serif;
        --font-mono: 'IBM Plex Mono', 'JetBrains Mono', monospace;

        /* Selection */
        --selection-bg: rgba(29, 78, 216, 0.15);
    }

    /* ===== Global Reset & Base ===== */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    html {
        scroll-behavior: smooth;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    body {
        font-family: var(--font-body);
        background: var(--bg-page);
        color: var(--text-body);
        min-height: 100vh;
        overflow-x: hidden;
        line-height: 1.6;
        font-size: 15px;
    }

    ::selection {
        background: var(--selection-bg);
        color: var(--ink-900);
    }

    /* ===== Subtle Transitions ===== */
    * {
        transition: background-color 0.2s ease,
                    border-color 0.2s ease,
                    box-shadow 0.2s ease,
                    color 0.2s ease,
                    transform 0.2s ease;
    }

    /* ===== Scrollbar ===== */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 100px; }
    ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    * { scrollbar-width: thin; scrollbar-color: #CBD5E1 transparent; }

    /* ================================================================
       ANIMATIONS
       ================================================================ */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* ================================================================
       UTILITY CLASSES
       ================================================================ */

    /* ----- Card ----- */
    .card {
        background: var(--bg-card);
        border-radius: var(--radius-lg); /* 12px sesuai desain.md */
        border: var(--border-subtle);
        box-shadow: var(--shadow-card);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        border-color: var(--primary-100);
        box-shadow: var(--shadow-glow);
        transform: translateY(-2px);
    }

    /* ----- Badge / Pill ----- */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: var(--radius-full);
        background: var(--primary-100);
        border: 1px solid rgba(29, 78, 216, 0.15);
        font-size: 11px;
        font-weight: 600;
        color: var(--primary-700);
        white-space: nowrap;
    }
    .badge-success {
        background: rgba(22, 163, 74, 0.1);
        border-color: rgba(22, 163, 74, 0.2);
        color: var(--status-success);
    }
    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.2);
        color: var(--status-warning);
    }
    .badge-danger {
        background: rgba(220, 38, 38, 0.1);
        border-color: rgba(220, 38, 38, 0.2);
        color: var(--status-danger);
    }
    .badge-progress {
        background: rgba(37, 99, 235, 0.1);
        border-color: rgba(37, 99, 235, 0.2);
        color: var(--status-progress);
    }

    /* ----- Status Badge (dengan teks + warna, ramah buta warna) ----- */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 600;
    }
    .status-badge .s-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .status-badge.dilaporkan {
        background: rgba(59, 130, 246, 0.1);
        color: #2563EB;
    }
    .status-badge.dilaporkan .s-dot { background: #2563EB; }
    .status-badge.diverifikasi {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }
    .status-badge.diverifikasi .s-dot { background: #F59E0B; }
    .status-badge.diperbaiki {
        background: rgba(37, 99, 235, 0.1);
        color: #2563EB;
    }
    .status-badge.diperbaiki .s-dot { background: #2563EB; }
    .status-badge.selesai {
        background: rgba(22, 163, 74, 0.1);
        color: #16A34A;
    }
    .status-badge.selesai .s-dot { background: #16A34A; }
    .status-badge.rusak-parah {
        background: rgba(220, 38, 38, 0.1);
        color: #DC2626;
    }
    .status-badge.rusak-parah .s-dot { background: #DC2626; }
    .status-badge.rusak-sedang {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }
    .status-badge.rusak-sedang .s-dot { background: #F59E0B; }

    /* ================================================================
       SIGNATURE ELEMENT: "GARIS JALAN" PROGRESS LINE
       Progress bar berbentuk jalan — garis horizontal abu dengan strip
       putus-putus kuning yang terisi sesuai progres laporan.
       ================================================================ */
    .garis-jalan {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        position: relative;
        padding: 8px 0;
    }
    /* Garis aspal (background) */
    .garis-jalan::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background: #E2E8F0; /* aspal abu */
        border-radius: 2px;
        transform: translateY(-50%);
        z-index: 0;
    }
    /* Garis marka putus-putus kuning */
    .garis-jalan .marka-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: repeating-linear-gradient(
            to right,
            var(--marka-400) 0px,
            var(--marka-400) 8px,
            transparent 8px,
            transparent 16px
        );
        transform: translateY(-50%);
        z-index: 1;
        opacity: 0.5;
    }
    /* Progress terisi biru */
    .garis-jalan .progress-fill {
        position: absolute;
        top: 50%;
        left: 0;
        height: 4px;
        background: var(--primary-700);
        border-radius: 2px;
        transform: translateY(-50%);
        z-index: 2;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    /* Titik-titik status */
    .garis-jalan .gj-point {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--surface);
        border: 3px solid #E2E8F0;
        z-index: 3;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
        cursor: pointer;
    }
    .garis-jalan .gj-point.active {
        border-color: var(--primary-700);
        background: var(--primary-700);
        box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.15);
    }
    .garis-jalan .gj-point.active .gj-icon {
        color: #fff;
    }
    .garis-jalan .gj-point .gj-icon {
        width: 10px;
        height: 10px;
        color: #94A3B8;
    }
    /* Label di bawah titik */
    .garis-jalan .gj-label {
        position: absolute;
        top: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        font-weight: 600;
        color: #94A3B8;
        white-space: nowrap;
        font-family: var(--font-body);
        text-align: center;
    }
    .garis-jalan .gj-point.active .gj-label {
        color: var(--primary-700);
    }

    /* Mini version for card list (tanpa label) */
    .garis-jalan.mini {
        padding: 4px 0;
    }
    .garis-jalan.mini .gj-point {
        width: 12px;
        height: 12px;
        border-width: 2px;
    }
    .garis-jalan.mini .gj-point .gj-icon { display: none; }
    .garis-jalan.mini::before { height: 3px; }
    .garis-jalan.mini .marka-line { height: 1.5px; }
    .garis-jalan.mini .progress-fill { height: 3px; }

    /* ===== Buttons ===== */

    /* Primary Button */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: var(--radius-sm); /* 8px sesuai desain.md */
        background: var(--primary-700);
        color: #fff;
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(29, 78, 216, 0.2);
    }
    .btn-primary:hover {
        background: var(--primary-500);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
    }
    .btn-primary:active {
        transform: translateY(0);
    }
    .btn-primary i { width: 18px; height: 18px; }

    /* Secondary / Outline Button */
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: var(--radius-sm);
        background: transparent;
        color: var(--primary-700);
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: 1.5px solid var(--primary-700);
        transition: all 0.2s ease;
    }
    .btn-secondary:hover {
        background: var(--primary-100);
    }
    .btn-secondary i { width: 16px; height: 16px; }

    /* Ghost Button */
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        background: transparent;
        color: var(--ink-600);
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-ghost:hover {
        background: var(--surface-muted);
        color: var(--ink-900);
    }
    .btn-ghost i { width: 16px; height: 16px; }

    /* Danger Button */
    .btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: var(--radius-sm);
        background: var(--status-danger);
        color: #fff;
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-danger:hover {
        background: #B91C1C;
    }

    /* Icon Button */
    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--ink-600);
        background: transparent;
        border: 1px solid var(--line-200);
        transition: all 0.2s ease;
    }
    .btn-icon:hover {
        background: var(--primary-100);
        border-color: var(--primary-500);
        color: var(--primary-700);
    }
    .btn-icon i { width: 18px; height: 18px; }

    /* ===== Input ===== */
    .input-field {
        width: 100%;
        padding: 10px 14px;
        border-radius: var(--radius-sm);
        background: var(--surface);
        border: 1.5px solid var(--line-200);
        color: var(--ink-900);
        font-size: 14px;
        font-family: var(--font-body);
        outline: none;
        transition: all 0.2s ease;
    }
    .input-field::placeholder { color: #94A3B8; }
    .input-field:focus {
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* ===== Page Heading ===== */
    .page-heading {
        text-align: center;
        margin-bottom: 40px;
    }
    .page-heading h1 {
        font-family: var(--font-heading);
        font-size: clamp(28px, 4vw, 36px);
        font-weight: 700;
        letter-spacing: -0.03em;
        margin-bottom: 12px;
        color: var(--ink-900);
    }
    .page-heading p {
        font-size: 15px;
        color: var(--ink-600);
        max-width: 520px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* ===== Page Transition ===== */
    .page-enter {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* ===== Content Wrapper ===== */
    #content-wrapper {
        padding-top: 80px;
        min-height: 100vh;
    }
    #content-wrapper.blurred {
        filter: blur(6px);
        pointer-events: none;
        user-select: none;
    }

    /* ===== Section Container ===== */
    .section-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* ===== Focus State (aksesibilitas) ===== */
    :focus-visible {
        outline: 2px solid rgba(29, 78, 216, 0.5);
        outline-offset: 2px;
        border-radius: var(--radius-sm);
    }

    /* ===== Gradient Text ===== */
    .gradient-text {
        background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ===== Status Dot ===== */
    .status-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--status-success);
        flex-shrink: 0;
        position: relative;
    }
    .status-dot::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 50%;
        background: rgba(22, 163, 74, 0.2);
        animation: pulse 2s ease-in-out infinite;
    }
    .status-dot.offline {
        background: var(--text-muted);
    }
    .status-dot.offline::after {
        display: none;
    }

    /* ===== Skeleton Loading ===== */
    .skeleton {
        background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 50%, #F1F5F9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s ease-in-out infinite;
        border-radius: var(--radius-sm);
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* ===== Kustomisasi untuk halaman auth (link kecil) ===== */
    .auth-foot-link {
        font-size: 12px;
        color: var(--text-muted);
    }
    .auth-foot-link a {
        color: var(--primary-500);
        text-decoration: none;
        font-weight: 500;
    }
    .auth-foot-link a:hover {
        color: var(--primary-700);
        text-decoration: underline;
    }

    /* ===== Responsive ===== */
    @media (max-width: 640px) {
        #content-wrapper { padding-top: 72px; }
        .section-container { padding: 0 16px; }
    }
</style>

<!-- Preconnect -->
<link rel="dns-prefetch" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://fonts.gstatic.com">
