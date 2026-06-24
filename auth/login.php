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
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Please fill in all fields.';
        } else {
            // Query user from database
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header('Location: ../index.php');
                    exit;
                } else {
                    $error = 'Invalid username or password.';
                }
            } else {
                $error = 'Invalid username or password.';
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="../assets/js/lucide.js"></script>
    <script src="../assets/js/i18n.js"></script>

    <style>
        /* ===== Theme Variables (matching the main site) ===== */
        :root {
            --bg-primary: #0B0F19;
            --bg-card: #1E293B;
            --bg-elevated: rgba(30, 41, 59, 0.6);
            --bg-input: rgba(255, 255, 255, 0.04);
            --bg-hover: rgba(255, 255, 255, 0.06);
            --brand-yellow: #FACC15;
            --brand-amber: #EAB308;
            --brand-indigo: #6366F1;
            --text-primary: #F1F5F9;
            --text-secondary: #94A3B8;
            --text-muted: #64748B;
            --border-color: rgba(255, 255, 255, 0.08);
            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-muted: rgba(255, 255, 255, 0.04);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 25px 50px rgba(0, 0, 0, 0.4);
            --success: #22C55E;
            --danger: #EF4444;
            --orb-yellow: rgba(250, 204, 21, 0.08);
            --orb-indigo: rgba(99, 102, 241, 0.06);
        }

        [data-theme="light"] {
            --bg-primary: #F5F7FA;
            --bg-card: #FFFFFF;
            --bg-elevated: rgba(255, 255, 255, 0.85);
            --bg-input: rgba(0, 0, 0, 0.04);
            --bg-hover: rgba(0, 0, 0, 0.04);
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --text-muted: #94A3B8;
            --border-color: rgba(0, 0, 0, 0.08);
            --border-subtle: rgba(0, 0, 0, 0.06);
            --border-muted: rgba(0, 0, 0, 0.04);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 25px 50px rgba(0, 0, 0, 0.1);
            --orb-yellow: rgba(250, 204, 21, 0.1);
            --orb-indigo: rgba(99, 102, 241, 0.08);
        }

        /* Smooth theme transition */
        * {
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
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== Background Orbs ===== */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(80px);
        }

        .orb-1 {
            width: 350px;
            height: 350px;
            background: var(--orb-yellow);
            top: -120px;
            right: -80px;
            animation: float 8s ease-in-out infinite;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: var(--orb-indigo);
            bottom: -150px;
            left: -120px;
            animation: float 12s ease-in-out infinite reverse;
        }

        .orb-3 {
            width: 200px;
            height: 200px;
            background: var(--orb-indigo);
            top: 50%;
            right: 10%;
            animation: float 10s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 0 20px rgba(250, 204, 21, 0.1); }
            50% { box-shadow: 0 0 35px rgba(250, 204, 21, 0.25); }
        }

        /* ===== Auth Container ===== */
        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            animation: fadeInUp 0.6s ease;
        }

        /* ===== Back to Home ===== */
        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            transition: all 0.2s ease;
        }

        .back-home:hover {
            color: var(--brand-yellow);
        }

        .back-home i {
            width: 16px;
            height: 16px;
        }

        /* ===== Auth Card ===== */
        .auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 40px 36px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--brand-yellow), transparent);
            opacity: 0.5;
        }

        /* ===== Logo ===== */
        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 8px;
        }

        .auth-logo img {
            height: 48px;
            width: auto;
        }

        .auth-logo span {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #FACC15 0%, #6366F1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== Auth Header ===== */
        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .auth-header p {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        /* ===== Error/Success Alert ===== */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            animation: fadeInUp 0.3s ease;
        }

        .alert i {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #EF4444;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #22C55E;
        }

        /* ===== Form Group ===== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .form-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrap .input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-input-wrap .input-icon i {
            width: 18px;
            height: 18px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border-radius: 14px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            outline: none;
            transition: all 0.25s ease;
        }

        .form-input::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .form-input:focus {
            border-color: rgba(250, 204, 21, 0.3);
            background: var(--bg-hover);
            box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.06);
        }

        .form-input.input-error {
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* ===== Password Toggle ===== */
        .password-toggle {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--text-secondary);
        }

        .password-toggle i {
            width: 18px;
            height: 18px;
        }

        /* ===== Form Options ===== */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: var(--brand-yellow);
            cursor: pointer;
        }

        .checkbox-label {
            font-size: 13px;
            color: var(--text-secondary);
            user-select: none;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--brand-indigo);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--brand-yellow);
        }

        /* ===== Submit Button ===== */
        .btn-submit {
            width: 100%;
            padding: 16px 24px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #FACC15, #EAB308);
            color: #0B0F19;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 25px rgba(250, 204, 21, 0.12);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px rgba(250, 204, 21, 0.25);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
            pointer-events: none;
        }

        .btn-submit i {
            width: 18px;
            height: 18px;
            position: relative;
            z-index: 1;
        }

        .btn-submit span {
            position: relative;
            z-index: 1;
        }

        /* ===== Auth Footer ===== */
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border-muted);
        }

        .auth-footer p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--brand-yellow);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: var(--brand-amber);
        }

        /* ===== Theme Toggle on Login ===== */
        .theme-toggle-login {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10;
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.25s ease;
            backdrop-filter: blur(12px);
        }

        .theme-toggle-login:hover {
            border-color: rgba(250, 204, 21, 0.3);
            color: var(--brand-yellow);
            box-shadow: 0 0 20px rgba(250, 204, 21, 0.08);
        }

        .theme-toggle-login i {
            width: 20px;
            height: 20px;
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        /* ===== Responsive ===== */
        @media (max-width: 480px) {
            .auth-card {
                padding: 28px 20px;
                border-radius: 20px;
            }
            .auth-container {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <!-- Background Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Theme Toggle -->
    <button class="theme-toggle-login" id="themeToggleLogin" aria-label="Toggle theme">
        <i data-lucide="moon" id="themeIconLogin"></i>
    </button>

    <!-- Auth Container -->
    <div class="auth-container">            <a href="../index.php" class="back-home">
                <i data-lucide="arrow-left"></i>
                <span data-i18n="auth.backToHome">Back to Home</span>
            </a>

        <div class="auth-card">

            <!-- Logo -->
            <a href="../index.php" class="auth-logo">
                <img src="../assets/Logo.png" alt="RoKenAI Logo">
                <span>RoKenAI</span>
            </a>

            <!-- Header -->
            <div class="auth-header">
                <h1 data-i18n="auth.welcomeBack">Welcome Back</h1>
                <p data-i18n="auth.signInDesc">Sign in to access your AI-powered road inspection dashboard.</p>
            </div>

            <!-- Error Alert -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i data-lucide="circle-alert"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Success/Info from register -->
            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success">
                    <i data-lucide="check-circle-2"></i>
                    <span>Account created successfully! Please sign in.</span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username" data-i18n="auth.usernameOrEmail">Username or Email</label>
                    <div class="form-input-wrap">
                        <span class="input-icon"><i data-lucide="user"></i></span>
                        <input class="form-input" type="text" id="username" name="username" data-i18n="auth.usernamePlaceholder" placeholder="Enter your username or email" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" data-i18n="auth.password">Password</label>
                    <div class="form-input-wrap">
                        <span class="input-icon"><i data-lucide="lock"></i></span>
                        <input class="form-input" type="password" id="password" name="password" data-i18n="auth.passwordPlaceholder" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="togglePass" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password visibility">
                            <i data-lucide="eye" id="passIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkbox-label" data-i18n="auth.rememberMe">Remember me</span>
                    </label>
                    <a href="#" class="forgot-link" data-i18n="auth.forgotPassword">Forgot password?</a>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit" name="login" class="btn-submit">
                    <i data-lucide="log-in"></i>
                    <span data-i18n="auth.signIn">Sign In</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                <p><span data-i18n="auth.noAccount">Don't have an account?</span> <a href="register.php" data-i18n="auth.createOne">Create one</a></p>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        // ===== Theme Toggle =====
        function getTheme() {
            return localStorage.getItem('roken-theme') || 'dark';
        }

        function setTheme(theme) {
            const html = document.documentElement;
            const icon = document.getElementById('themeIconLogin');
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
            lucide.createIcons();
        }

        function toggleTheme() {
            const current = getTheme();
            setTheme(current === 'dark' ? 'light' : 'dark');
        }

        // Apply saved theme on load
        setTheme(getTheme());

        document.getElementById('themeToggleLogin').addEventListener('click', toggleTheme);

        // ===== Password Toggle =====
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('passIcon');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                pass.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>

</body>
</html>
