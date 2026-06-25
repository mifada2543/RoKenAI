<?php
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ../index.php');
    exit;
}

$error   = '';
$success = '';

// Handle registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (!verify_csrf()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
            $error = 'Harap isi semua kolom.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Masukkan alamat email yang valid.';
        } elseif (strlen($username) < 3 || strlen($username) > 32) {
            $error = 'Username harus antara 3 hingga 32 karakter.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $error = 'Username hanya boleh berisi huruf, angka, dan underscore (_).';
        } elseif (stripos($username, 'guest') !== false) {
            $error = "Username 'Guest' dicadangkan oleh sistem.";
        } elseif ($password !== $confirm) {
            $error = 'Password tidak cocok.';
        } else {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = 'Username atau email sudah terdaftar.';
            } else {
                // Hash password and create user
                $hashed = defined('PASSWORD_ARGON2ID')
                    ? password_hash($password, PASSWORD_ARGON2ID)
                    : password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_active, role, created_at) VALUES (?, ?, ?, 2, 'user', NOW())");
                $stmt->bind_param("sss", $username, $email, $hashed);

                if ($stmt->execute()) {
                    header('Location: login.php?registered=1');
                    exit;
                } else {
                    $error = 'Pendaftaran gagal. Silakan coba lagi.';
                }
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Daftar</title>
    <meta name="description" content="Buat akun RoKenAI - Platform inspeksi infrastruktur jalan berbasis AI">
    <meta name="theme-color" content="#0B0F19">
    <link rel="icon" type="image/png" href="../assets/Logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="../assets/css/tailwind.css">
    <!-- Lucide Icons -->
    <script src="../assets/js/lucide.js"></script>
    <!-- Tailwind JS config -->
    <script src="../assets/js/tailwind.js"></script>

    <style>
        /* ===== CSS Custom Properties ===== */
        :root {
            --bg-primary:    #0B0F19;
            --bg-card:       #1E293B;
            --bg-input:      rgba(255,255,255,0.04);
            --bg-hover:      rgba(255,255,255,0.07);
            --brand-yellow:  #FACC15;
            --brand-amber:   #EAB308;
            --brand-indigo:  #6366F1;
            --text-primary:  #F1F5F9;
            --text-secondary:#94A3B8;
            --text-muted:    #64748B;
            --border-color:  rgba(255,255,255,0.08);
            --border-subtle: rgba(255,255,255,0.06);
            --border-muted:  rgba(255,255,255,0.04);
            --shadow-lg:     0 25px 50px rgba(0,0,0,0.4);
            --orb-yellow:    rgba(250,204,21,0.08);
            --orb-indigo:    rgba(99,102,241,0.06);
        }

        [data-theme="light"] {
            --bg-primary:    #F5F7FA;
            --bg-card:       #FFFFFF;
            --bg-input:      rgba(0,0,0,0.04);
            --bg-hover:      rgba(0,0,0,0.04);
            --text-primary:  #0F172A;
            --text-secondary:#475569;
            --text-muted:    #94A3B8;
            --border-color:  rgba(0,0,0,0.08);
            --border-subtle: rgba(0,0,0,0.06);
            --border-muted:  rgba(0,0,0,0.04);
            --shadow-lg:     0 25px 50px rgba(0,0,0,0.10);
            --orb-yellow:    rgba(250,204,21,0.10);
            --orb-indigo:    rgba(99,102,241,0.08);
        }

        *, *::before, *::after { transition: background-color .3s ease, border-color .3s ease, color .3s ease; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Orbs ===== */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(80px);
        }
        .orb-1 { width:350px; height:350px; background:var(--orb-indigo); top:-100px; left:-80px; animation: floatY 9s ease-in-out infinite; }
        .orb-2 { width:400px; height:400px; background:var(--orb-yellow); bottom:-150px; right:-120px; animation: floatY 13s ease-in-out infinite reverse; }
        .orb-3 { width:200px; height:200px; background:var(--orb-yellow); top:50%; left:5%; animation: floatY 11s ease-in-out infinite; }

        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-14px); }
        }

        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(18px); }
            to   { opacity:1; transform:translateY(0); }
        }

        @keyframes shimmer {
            0%   { background-position:-200% 0; }
            100% { background-position: 200% 0; }
        }

        /* ===== Auth wrapper ===== */
        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            padding: 20px;
            animation: fadeInUp .55s ease;
        }

        /* ===== Card ===== */
        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 40px 36px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(24px);
        }
        .auth-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--brand-indigo), transparent);
            opacity: .5;
        }

        /* ===== Back link ===== */
        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
        }
        .back-home:hover { color: var(--brand-yellow); }
        .back-home svg  { width:16px; height:16px; }

        /* ===== Logo ===== */
        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 8px;
        }
        .auth-logo img  { height: 46px; width: auto; }
        .auth-logo span {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.03em;
            background: linear-gradient(135deg, #FACC15 0%, #6366F1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== Input ===== */
        .input-group-auth {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon-auth {
            position: absolute;
            left: 15px;
            color: var(--text-muted);
            display: flex;
            pointer-events: none;
        }
        .input-icon-auth svg { width:18px; height:18px; }

        .form-input-auth {
            width: 100%;
            padding: 13px 46px;
            border-radius: 14px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            outline: none;
            transition: all .25s ease;
        }
        .form-input-auth::placeholder { color: var(--text-muted); font-weight: 400; }
        .form-input-auth:focus {
            border-color: rgba(99,102,241,.4);
            background: var(--bg-hover);
            box-shadow: 0 0 0 4px rgba(99,102,241,.07);
        }

        /* ===== Password toggle ===== */
        .pass-toggle {
            position: absolute;
            right: 13px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }
        .pass-toggle:hover { color: var(--text-secondary); }
        .pass-toggle svg   { width:18px; height:18px; }

        /* ===== Password strength ===== */
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: var(--border-color);
            overflow: hidden;
            margin-top: 8px;
        }
        .strength-fill {
            height: 100%;
            border-radius: 4px;
            transition: width .4s ease, background .4s ease;
            width: 0;
        }

        /* ===== Submit button ===== */
        .btn-auth {
            width: 100%;
            padding: 15px 24px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #6366F1, #4F46E5);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
            box-shadow: 0 0 25px rgba(99,102,241,.18);
        }
        .btn-auth:hover  { transform: translateY(-2px); box-shadow: 0 0 40px rgba(99,102,241,.35); }
        .btn-auth:active { transform: translateY(0); }
        .btn-auth::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
            pointer-events: none;
        }
        .btn-auth svg  { width:18px; height:18px; position:relative; z-index:1; }
        .btn-auth span { position:relative; z-index:1; }

        /* ===== Alert ===== */
        .auth-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 500;
            animation: fadeInUp .3s ease;
        }
        .auth-alert svg { width:18px; height:18px; flex-shrink:0; }
        .auth-alert-error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:#EF4444; }
        .auth-alert-success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.2); color:#22C55E; }

        /* ===== Label ===== */
        .form-label-auth {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 7px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* ===== Hint text ===== */
        .hint-text {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* ===== Theme toggle ===== */
        .theme-btn {
            position: fixed;
            top: 20px; right: 20px;
            z-index: 10;
            width: 44px; height: 44px;
            border-radius: 14px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all .25s ease;
            backdrop-filter: blur(12px);
        }
        .theme-btn:hover { border-color:rgba(250,204,21,.3); color:var(--brand-yellow); box-shadow:0 0 20px rgba(250,204,21,.08); }
        .theme-btn svg   { width:20px; height:20px; }

        /* ===== Footer ===== */
        .auth-footer { border-top:1px solid var(--border-muted); }
        .auth-footer a { color:var(--brand-yellow); text-decoration:none; font-weight:600; transition:color .2s ease; }
        .auth-footer a:hover { color:var(--brand-amber); }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:var(--border-color); border-radius:4px; }

        @media (max-width:480px) {
            .auth-card    { padding:28px 20px; border-radius:20px; }
            .auth-wrapper { padding:12px; }
        }
    </style>
</head>

<body>
    <!-- Background Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Theme Toggle -->
    <button class="theme-btn" id="themeToggleBtn" aria-label="Toggle theme">
        <i data-lucide="moon" id="themeIcon"></i>
    </button>

    <!-- Auth Wrapper -->
    <div class="auth-wrapper">

        <!-- Back to Home -->
        <a href="../index.php" class="back-home">
            <i data-lucide="arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>

        <div class="auth-card">

            <!-- Logo -->
            <a href="../index.php" class="auth-logo">
                <img src="../assets/Logo.png" alt="RoKenAI Logo">
                <span>RoKenAI</span>
            </a>

            <!-- Header -->
            <div class="text-center mb-4 mt-1">
                <h1 class="fw-bold mb-1" style="font-size:22px; letter-spacing:-.02em; color:var(--text-primary);">Buat Akun</h1>
                <p class="mb-0" style="font-size:14px; color:var(--text-secondary); line-height:1.5;">Daftar dan tunggu verifikasi admin untuk mulai menggunakan platform.</p>
            </div>

            <!-- Error Alert -->
            <?php if (!empty($error)): ?>
                <div class="auth-alert auth-alert-error mb-3">
                    <i data-lucide="circle-alert"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label-auth" for="reg_username">Username</label>
                    <div class="input-group-auth">
                        <span class="input-icon-auth"><i data-lucide="user"></i></span>
                        <input
                            class="form-input-auth"
                            type="text"
                            id="reg_username"
                            name="username"
                            placeholder="Minimal 3 karakter, hanya huruf/angka/_"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                            required
                            autocomplete="username"
                            minlength="3" maxlength="32"
                        >
                    </div>
                    <p class="hint-text">Huruf, angka, dan underscore saja. Tidak boleh mengandung 'guest'.</p>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label-auth" for="reg_email">Email</label>
                    <div class="input-group-auth">
                        <span class="input-icon-auth"><i data-lucide="mail"></i></span>
                        <input
                            class="form-input-auth"
                            type="email"
                            id="reg_email"
                            name="email"
                            placeholder="nama@contoh.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label-auth" for="reg_password">Password</label>
                    <div class="input-group-auth">
                        <span class="input-icon-auth"><i data-lucide="lock"></i></span>
                        <input
                            class="form-input-auth"
                            type="password"
                            id="reg_password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            autocomplete="new-password"
                            minlength="8"
                            oninput="checkStrength(this.value)"
                        >
                        <button type="button" class="pass-toggle" onclick="togglePass('reg_password','icon1')" tabindex="-1" aria-label="Tampilkan password">
                            <i data-lucide="eye" id="icon1"></i>
                        </button>
                    </div>
                    <!-- Strength bar -->
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <p class="hint-text" id="strengthLabel"></p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="form-label-auth" for="reg_confirm">Konfirmasi Password</label>
                    <div class="input-group-auth">
                        <span class="input-icon-auth"><i data-lucide="shield-check"></i></span>
                        <input
                            class="form-input-auth"
                            type="password"
                            id="reg_confirm"
                            name="confirm_password"
                            placeholder="Ulangi password"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="pass-toggle" onclick="togglePass('reg_confirm','icon2')" tabindex="-1" aria-label="Tampilkan konfirmasi password">
                            <i data-lucide="eye" id="icon2"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" name="register" class="btn-auth">
                    <i data-lucide="user-plus"></i>
                    <span>Daftar Sekarang</span>
                </button>
            </form>

            <!-- Pending notice -->
            <div class="auth-alert auth-alert-success mt-3" style="background:rgba(99,102,241,.08); border-color:rgba(99,102,241,.2); color:var(--brand-indigo);">
                <i data-lucide="info"></i>
                <span>Akun baru membutuhkan verifikasi admin sebelum dapat digunakan.</span>
            </div>

            <!-- Footer -->
            <div class="auth-footer text-center mt-4 pt-4">
                <p class="mb-0" style="font-size:13px; color:var(--text-muted);">
                    Sudah punya akun? <a href="login.php">Masuk di sini</a>
                </p>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        // ===== Theme =====
        function getTheme()    { return localStorage.getItem('roken-theme') || 'dark'; }
        function applyTheme(t) {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');
            if (t === 'light') {
                html.setAttribute('data-theme', 'light');
                icon.setAttribute('data-lucide', 'sun');
                document.querySelector('meta[name="theme-color"]')?.setAttribute('content', '#F5F7FA');
            } else {
                html.setAttribute('data-theme', 'dark');
                icon.setAttribute('data-lucide', 'moon');
                document.querySelector('meta[name="theme-color"]')?.setAttribute('content', '#0B0F19');
            }
            localStorage.setItem('roken-theme', t);
            lucide.createIcons();
        }
        document.getElementById('themeToggleBtn').addEventListener('click', () => {
            applyTheme(getTheme() === 'dark' ? 'light' : 'dark');
        });
        applyTheme(getTheme());

        // ===== Password toggle =====
        function togglePass(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon  = document.getElementById(iconId);
            const show  = field.type === 'password';
            field.type  = show ? 'text' : 'password';
            icon.setAttribute('data-lucide', show ? 'eye-off' : 'eye');
            lucide.createIcons();
        }

        // ===== Password strength =====
        function checkStrength(val) {
            const fill  = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');
            let score   = 0;
            if (val.length >= 8)  score++;
            if (val.length >= 12) score++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^a-zA-Z0-9]/.test(val)) score++;

            const levels = [
                { pct:'0%',   color:'transparent', text:'' },
                { pct:'25%',  color:'#EF4444',     text:'Sangat lemah' },
                { pct:'50%',  color:'#F97316',     text:'Lemah' },
                { pct:'75%',  color:'#FACC15',     text:'Cukup' },
                { pct:'90%',  color:'#22C55E',     text:'Kuat' },
                { pct:'100%', color:'#6366F1',     text:'Sangat kuat' },
            ];
            const lvl = levels[Math.min(score, 5)];
            fill.style.width      = lvl.pct;
            fill.style.background = lvl.color;
            label.textContent     = lvl.text;
            label.style.color     = lvl.color;
        }
    </script>
</body>

</html>