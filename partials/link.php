<meta name="description" content="RoKenAI - Platform AI untuk analisis dan deteksi kerusakan jalan">
<meta name="theme-color" content="#0B0F19">
<link rel="manifest" href="assets/manifest.json">
<link rel="icon" type="image/png" href="assets/Logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<script src="assets/js/tailwind.js"></script>
<script src="assets/js/lucide.js"></script>
<script src="assets/js/i18n.js"></script>

<style>
    /* ===== Theme System (Dark & Light Mode) ===== */
    :root {
        --bg-primary: #0B0F19;
        --bg-card: #1E293B;
        --bg-elevated: rgba(30, 41, 59, 0.6);
        --bg-glass: rgba(30, 41, 59, 0.85);
        --bg-subtle: rgba(30, 41, 59, 0.4);
        --bg-light: rgba(30, 41, 59, 0.3);
        --bg-hover: rgba(255, 255, 255, 0.04);
        --bg-navbar: rgba(11, 15, 25, 0.8);
        --bg-sidebar: rgba(15, 23, 42, 0.85);
        --bg-bubble: rgba(30, 41, 59, 0.6);
        --bg-input: rgba(255, 255, 255, 0.04);
        --bg-active: rgba(250, 204, 21, 0.08);
        --brand-yellow: #FACC15;
        --brand-amber: #EAB308;
        --brand-indigo: #6366F1;
        --text-primary: #F1F5F9;
        --text-secondary: #94A3B8;
        --text-muted: #64748B;
        --border-color: rgba(255, 255, 255, 0.08);
        --border-subtle: rgba(255, 255, 255, 0.06);
        --border-muted: rgba(255, 255, 255, 0.04);
        --border-hover: rgba(255, 255, 255, 0.12);
        --orb-yellow: rgba(250, 204, 21, 0.08);
        --orb-indigo: rgba(99, 102, 241, 0.06);
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.2);
        --shadow-lg: 0 25px 50px rgba(0, 0, 0, 0.4);
        --profile-border: #0F172A;
    }

    [data-theme="light"] {
        --bg-primary: #F5F7FA;
        --bg-card: #FFFFFF;
        --bg-elevated: rgba(255, 255, 255, 0.85);
        --bg-glass: rgba(255, 255, 255, 0.95);
        --bg-subtle: rgba(255, 255, 255, 0.7);
        --bg-light: rgba(255, 255, 255, 0.5);
        --bg-hover: rgba(0, 0, 0, 0.04);
        --bg-navbar: rgba(255, 255, 255, 0.85);
        --bg-sidebar: rgba(255, 255, 255, 0.95);
        --bg-bubble: rgba(255, 255, 255, 0.85);
        --bg-input: rgba(0, 0, 0, 0.04);
        --bg-active: rgba(250, 204, 21, 0.12);
        --text-primary: #0F172A;
        --text-secondary: #475569;
        --text-muted: #94A3B8;
        --border-color: rgba(0, 0, 0, 0.08);
        --border-subtle: rgba(0, 0, 0, 0.06);
        --border-muted: rgba(0, 0, 0, 0.04);
        --border-hover: rgba(0, 0, 0, 0.12);
        --orb-yellow: rgba(250, 204, 21, 0.1);
        --orb-indigo: rgba(99, 102, 241, 0.08);
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 25px 50px rgba(0, 0, 0, 0.1);
        --profile-border: #E2E8F0;
    }

    /* Smooth theme transition */
    html,
    html *,
    html *::before,
    html *::after {
        transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease, color 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--bg-primary);
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* ===== Scrollbar Styling ===== */
    ::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* ===== Glow Keyframes ===== */
    @keyframes glow-pulse {
        0%, 100% { box-shadow: 0 0 20px rgba(250, 204, 21, 0.1); }
        50% { box-shadow: 0 0 35px rgba(250, 204, 21, 0.25); }
    }

    @keyframes breathe-border {
        0%, 100% { opacity: 0.4; }
        50% { opacity: 0.8; }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* ===== Glassmorphism Utility ===== */
    .glass {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-subtle);
    }

    .glass-strong {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* ===== Gradient Text ===== */
    .gradient-text {
        background: linear-gradient(135deg, #FACC15 0%, #6366F1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ===== Glow Button ===== */
    .btn-glow {
        background: linear-gradient(135deg, #FACC15, #EAB308);
        color: #0B0F19;
        font-weight: 700;
        border: none;
        border-radius: 16px;
        padding: 14px 28px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 25px rgba(250, 204, 21, 0.15);
    }

    .btn-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 35px rgba(250, 204, 21, 0.3);
    }

    .btn-glow:active {
        transform: translateY(0);
    }

    /* ===== Gradient Border Card ===== */
    .card-gradient-border {
        position: relative;
        background: rgba(30, 41, 59, 0.5);
        border-radius: 24px;
        backdrop-filter: blur(16px);
    }

    .card-gradient-border::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 24px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(250, 204, 21, 0.3), rgba(99, 102, 241, 0.3));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    /* ===== Floating Orbs (Background Decor) ===== */
    .orb {
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        z-index: -1;
        filter: blur(80px);
    }

    .orb-1 {
        width: 300px;
        height: 300px;
        background: rgba(250, 204, 21, 0.08);
        top: -100px;
        right: -100px;
        animation: float 8s ease-in-out infinite;
    }

    .orb-2 {
        width: 400px;
        height: 400px;
        background: rgba(99, 102, 241, 0.06);
        bottom: -150px;
        left: -150px;
        animation: float 12s ease-in-out infinite reverse;
    }

    .orb-3 {
        width: 200px;
        height: 200px;
        background: rgba(99, 102, 241, 0.05);
        top: 50%;
        right: 10%;
        animation: float 10s ease-in-out infinite;
    }

    /* ===== Status Indicator ===== */
    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22C55E;
        box-shadow: 0 0 8px rgba(34, 197, 94, 0.6);
        animation: glow-pulse 2s ease-in-out infinite;
    }
</style>