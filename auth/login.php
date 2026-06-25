<?php
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ../index.php');
    exit;
}

$error = '';

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!verify_csrf()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $identifier = trim($_POST['username'] ?? '');
        $password   = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $error = 'Please fill in all fields.';
        } else {
            $stmt = $conn->prepare("SELECT id, username, password, is_active, role FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Verify account status
                    if ($user['is_active'] == 0) {
                        $error = "Akses ditolak untuk akun Guest.";
                    } elseif ($user['is_active'] == 2) {
                        $error = "Akun Anda sedang menunggu verifikasi admin.";
                    } else {
                        // Successful login
                        $_SESSION['user_logged_in'] = true;
                        $_SESSION['user_id']        = $user['id'];
                        $_SESSION['username']       = $user['username'];
                        $_SESSION['role']           = $user['role'];
                        session_regenerate_id(true);
                        header('Location: ../index.php');
                        exit;
                    }
                } else {
                    $error = 'Username atau password salah.';
                }
            } else {
                $error = 'Username atau password salah.';
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
    <title>RoKenAI | Login</title>
    <meta name="description" content="Login to RoKenAI - AI-powered road infrastructure inspection platform">
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
            --bg-primary:   #0B0F19;
            --bg-card:      #1E293B;
            --bg-input:     rgba(255,255,255,0.04);
            --bg-hover:     rgba(255,255,255,0.07);
            --brand-yellow: #FACC15;
            --brand-amber:  #EAB308;
            --brand-indigo: #6366F1;
            --text-primary: #F1F5F9;
            --text-secondary:#94A3B8;
            --text-muted:   #64748B;
            --border-color: rgba(255,255,255,0.08);
            --border-subtle:rgba(255,255,255,0.06);
            --border-muted: rgba(255,255,255,0.04);
            --shadow-lg:    0 25px 50px rgba(0,0,0,0.4);
            --orb-yellow:   rgba(250,204,21,0.08);
            --orb-indigo:   rgba(99,102,241,0.06);
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
            overflow: hidden;
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
        .orb-1 { width:350px; height:350px; background:var(--orb-yellow); top:-120px; right:-80px; animation: floatY 8s ease-in-out infinite; }
        .orb-2 { width:400px; height:400px; background:var(--orb-indigo); bottom:-150px; left:-120px; animation: floatY 12s ease-in-out infinite reverse; }
        .orb-3 { width:200px; height:200px; background:var(--orb-indigo); top:50%; right:10%; animation: floatY 10s ease-in-out infinite; }

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
            max-width: 440px;
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
            background: linear-gradient(90deg, transparent, var(--brand-yellow), transparent);
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
            border-color: rgba(250,204,21,.3);
            background: var(--bg-hover);
            box-shadow: 0 0 0 4px rgba(250,204,21,.06);
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

        /* ===== Submit button ===== */
        .btn-auth {
            width: 100%;
            padding: 15px 24px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #FACC15, #EAB308);
            color: #0B0F19;
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
            box-shadow: 0 0 25px rgba(250,204,21,.12);
        }
        .btn-auth:hover  { transform: translateY(-2px); box-shadow: 0 0 40px rgba(250,204,21,.28); }
        .btn-auth:active { transform: translateY(0); }
        .btn-auth::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
            pointer-events: none;
        }
        .btn-auth svg { width:18px; height:18px; position:relative; z-index:1; }
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
        .auth-alert-warning { background:rgba(250,204,21,.1); border:1px solid rgba(250,204,21,.2); color:#FACC15; }

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

        /* ===== Checkbox ===== */
        .form-check-input:checked { background-color:var(--brand-yellow)!important; border-color:var(--brand-yellow)!important; }

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
                <h1 class="fw-bold mb-1" style="font-size:22px; letter-spacing:-.02em; color:var(--text-primary);">Selamat Datang</h1>
                <p class="mb-0" style="font-size:14px; color:var(--text-secondary); line-height:1.5;">Masuk untuk mengakses dashboard inspeksi jalan berbasis AI.</p>
            </div>

            <!-- Alerts -->
            <?php if (!empty($error)): ?>
                <div class="auth-alert auth-alert-error mb-3">
                    <i data-lucide="circle-alert"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
                <div class="auth-alert auth-alert-success mb-3">
                    <i data-lucide="check-circle-2"></i>
                    <span>Pendaftaran berhasil! Silakan tunggu verifikasi admin, lalu masuk.</span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- Username / Email -->
                <div class="mb-3">
                    <label class="form-label-auth" for="username">Username atau Email</label>
                    <div class="input-group-auth">
                        <span class="input-icon-auth"><i data-lucide="user"></i></span>
                        <input
                            class="form-input-auth"
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Masukkan username atau email"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                            required
                            autocomplete="username"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label-auth" for="password">Password</label>
                    <div class="input-group-auth">
                        <span class="input-icon-auth"><i data-lucide="lock"></i></span>
                        <input
                            class="form-input-auth"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="pass-toggle" id="togglePass" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password visibility">
                            <i data-lucide="eye" id="passIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Options -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember" style="font-size:13px; color:var(--text-secondary); cursor:pointer;">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" style="font-size:13px; color:var(--brand-indigo); text-decoration:none; font-weight:500; transition:color .2s ease;" onmouseover="this.style.color='var(--brand-yellow)'" onmouseout="this.style.color='var(--brand-indigo)'">
                        Lupa password?
                    </a>
                </div>

                <!-- Submit -->
                <button type="submit" name="login" class="btn-auth">
                    <i data-lucide="log-in"></i>
                    <span>Masuk</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer text-center mt-4 pt-4">
                <p class="mb-0" style="font-size:13px; color:var(--text-muted);">
                    Belum punya akun? <a href="register.php">Daftar sekarang</a>
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
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('passIcon');
            const isHidden = pass.type === 'password';
            pass.type = isHidden ? 'text' : 'password';
            icon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');
            lucide.createIcons();
        }
    </script>
</body>

</html>