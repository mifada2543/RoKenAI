<meta name="description" content="RoKenAI - Platform AI untuk Pelaporan Jalan Rusak berbasis Computer Vision">
<meta name="theme-color" content="#1D4ED8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="manifest" href="assets/manifest.json">
<link rel="icon" type="image/png" href="assets/Logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Lucide icons & i18n -->
<script src="assets/js/lucide.js"></script>
<script src="assets/js/i18n.js"></script>

<!-- Tailwind CSS JIT runtime -->
<script src="assets/js/tailwind.js"></script>
<script>
    /* ================================================================
       Tailwind config — map design tokens ke Tailwind classes
       ================================================================ */
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary:    { DEFAULT: '#1D4ED8', light: '#DBEAFE', hover: '#3B82F6' },
                    ink:        { 900: '#0F172A', 600: '#475569' },
                    surface:    { DEFAULT: '#FFFFFF', muted: '#F8FAFC' },
                    line:       { 200: '#E2E8F0' },
                    marka:      { 400: '#FACC15' },
                    danger:     '#DC2626',
                    warning:    '#F59E0B',
                    progress:   '#2563EB',
                    success:    '#16A34A',
                },
                fontFamily: {
                    heading: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    body:    ['Inter', '"Plus Jakarta Sans"', 'sans-serif'],
                    mono:    ['"IBM Plex Mono"', 'monospace'],
                },
                borderRadius: {
                    sm:   '8px',
                    md:   '10px',
                    lg:   '12px',
                    xl:   '16px',
                    '2xl':'20px',
                },
                boxShadow: {
                    card: '0 1px 3px rgba(15,23,42,0.08)',
                    md:   '0 1px 3px rgba(15,23,42,0.08), 0 1px 2px rgba(15,23,42,0.04)',
                    lg:   '0 4px 12px rgba(15,23,42,0.08), 0 2px 4px rgba(15,23,42,0.04)',
                    glow: '0 0 0 1px rgba(29,78,216,0.08), 0 4px 12px rgba(29,78,216,0.06)',
                },
            }
        }
    }
</script>

<style>
    /* ================================================================
       RoKenAI Design System — CSS Custom Properties
       Hanya properti yang tidak bisa diganti Tailwind
       ================================================================ */
    :root {
        --primary-700: #1D4ED8;
        --primary-500: #3B82F6;
        --primary-100: #DBEAFE;
        --surface:        #FFFFFF;
        --surface-muted:  #F8FAFC;
        --ink-900:        #0F172A;
        --ink-600:        #475569;
        --line-200:       #E2E8F0;
        --marka-400:      #FACC15;
        --status-danger:  #DC2626;
        --status-warning: #F59E0B;
        --status-progress:#2563EB;
        --status-success: #16A34A;
        --font-heading:   'Plus Jakarta Sans', 'Inter', sans-serif;
        --font-body:      'Inter', 'Plus Jakarta Sans', sans-serif;
        --font-mono:      'IBM Plex Mono', monospace;
        --radius-sm: 8px; --radius-md: 10px; --radius-lg: 12px;
        --radius-xl: 16px; --radius-full: 9999px;
        --shadow-card: 0 1px 3px rgba(15,23,42,0.08);
        --shadow-glow: 0 0 0 1px rgba(29,78,216,0.08), 0 4px 12px rgba(29,78,216,0.06);
        --shadow-lg:   0 4px 12px rgba(15,23,42,0.08);
    }

    /* ===== Reset & Base ===== */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; -webkit-font-smoothing: antialiased; }
    body {
        font-family: var(--font-body);
        background: var(--surface-muted);
        color: var(--ink-600);
        min-height: 100vh;
        overflow-x: hidden;
        line-height: 1.6;
        font-size: 15px;
    }
    ::selection { background: rgba(29,78,216,0.15); color: var(--ink-900); }

    /* ===== Scrollbar ===== */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 100px; }
    ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

    /* ===== Animations ===== */
    @keyframes fadeInUp  { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes fadeIn    { from { opacity:0; } to { opacity:1; } }
    @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
    @keyframes pulse     { 0%,100% { opacity:1; } 50% { opacity:0.5; } }
    @keyframes shimmer   { 0% { background-position:-200% 0; } 100% { background-position:200% 0; } }
    @keyframes typing    { 0%,80%,100% { opacity:0.2; transform:scale(0.8); } 40% { opacity:1; transform:scale(1); } }

    .page-enter { animation: fadeInUp 0.5s cubic-bezier(0.16,1,0.3,1); }

    /* ===== Layout Helpers ===== */
    #content-wrapper { padding-top: 80px; min-height: 100vh; }
    .section-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

    /* ===== Focus ===== */
    :focus-visible { outline: 2px solid rgba(29,78,216,0.5); outline-offset: 2px; border-radius: var(--radius-sm); }

    /* ===== Gradient Text ===== */
    .gradient-text {
        background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ===== Skeleton ===== */
    .skeleton {
        background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 50%, #F1F5F9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s ease-in-out infinite;
        border-radius: var(--radius-sm);
    }

    /* ===== Status Dot (with pulse) ===== */
    .status-dot {
        display: inline-block; width: 7px; height: 7px;
        border-radius: 50%; background: var(--status-success); flex-shrink: 0; position: relative;
    }
    .status-dot::after {
        content: ''; position: absolute; inset: -2px; border-radius: 50%;
        background: rgba(22,163,74,0.2); animation: pulse 2s ease-in-out infinite;
    }
    .status-dot.offline { background: #94A3B8; }
    .status-dot.offline::after { display: none; }

    /* ===== Status Badges ===== */
    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: var(--radius-full);
        font-size: 12px; font-weight: 600;
    }
    .status-badge .s-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .status-badge.dilaporkan  { background:rgba(59,130,246,0.1);  color:#2563EB; }
    .status-badge.dilaporkan .s-dot { background:#2563EB; }
    .status-badge.diverifikasi{ background:rgba(245,158,11,0.1);  color:#F59E0B; }
    .status-badge.diverifikasi .s-dot { background:#F59E0B; }
    .status-badge.diperbaiki  { background:rgba(37,99,235,0.1);   color:#2563EB; }
    .status-badge.diperbaiki .s-dot  { background:#2563EB; }
    .status-badge.selesai     { background:rgba(22,163,74,0.1);   color:#16A34A; }
    .status-badge.selesai .s-dot     { background:#16A34A; }
    .status-badge.rusak-parah { background:rgba(220,38,38,0.1);   color:#DC2626; }
    .status-badge.rusak-parah .s-dot { background:#DC2626; }
    .status-badge.rusak-sedang{ background:rgba(245,158,11,0.1);  color:#F59E0B; }
    .status-badge.rusak-sedang .s-dot{ background:#F59E0B; }

    /* ===== GARIS JALAN — Signature Progress Bar ===== */
    .garis-jalan {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%; position: relative; padding: 8px 0;
    }
    .garis-jalan::before {
        content: ''; position: absolute; top: 50%; left: 0; right: 0;
        height: 4px; background: #E2E8F0; border-radius: 2px;
        transform: translateY(-50%); z-index: 0;
    }
    .garis-jalan .marka-line {
        position: absolute; top: 50%; left: 0; right: 0; height: 2px;
        background: repeating-linear-gradient(to right, var(--marka-400) 0px, var(--marka-400) 8px, transparent 8px, transparent 16px);
        transform: translateY(-50%); z-index: 1; opacity: 0.5;
    }
    .garis-jalan .progress-fill {
        position: absolute; top: 50%; left: 0; height: 4px;
        background: var(--primary-700); border-radius: 2px;
        transform: translateY(-50%); z-index: 2;
        transition: width 0.8s cubic-bezier(0.4,0,0.2,1);
    }
    .garis-jalan .gj-point {
        width: 20px; height: 20px; border-radius: 50%;
        background: var(--surface); border: 3px solid #E2E8F0;
        z-index: 3; position: relative; display: flex;
        align-items: center; justify-content: center;
        transition: all 0.4s ease; cursor: pointer;
    }
    .garis-jalan .gj-point.active {
        border-color: var(--primary-700); background: var(--primary-700);
        box-shadow: 0 0 0 4px rgba(29,78,216,0.15);
    }
    .garis-jalan .gj-point.active .gj-icon { color: #fff; }
    .garis-jalan .gj-point .gj-icon { width:10px; height:10px; color:#94A3B8; }
    .garis-jalan .gj-label {
        position: absolute; top: calc(100% + 6px); left: 50%;
        transform: translateX(-50%); font-size: 10px; font-weight: 600;
        color: #94A3B8; white-space: nowrap; font-family: var(--font-body);
    }
    .garis-jalan .gj-point.active .gj-label { color: var(--primary-700); }
    .garis-jalan.mini { padding: 4px 0; }
    .garis-jalan.mini .gj-point { width:12px; height:12px; border-width:2px; }
    .garis-jalan.mini .gj-point .gj-icon { display:none; }
    .garis-jalan.mini::before { height: 3px; }
    .garis-jalan.mini .marka-line { height: 1.5px; }
    .garis-jalan.mini .progress-fill { height: 3px; }

    /* ===== Responsive ===== */
    @media (max-width: 640px) {
        #content-wrapper { padding-top: 72px; }
        .section-container { padding: 0 16px; }
    }
</style>
