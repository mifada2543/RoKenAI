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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Masuk</title>
    <link rel="icon" type="image/png" href="../assets/Logo.png">
    <?php include '../partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Login (desain.md 5.2)
           Split layout: kiri form putih, kanan ilustrasi jalan + overlay biru
           ================================================================ */

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--font-body);
            background: var(--surface-muted);
            color: var(--ink-900);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .auth-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ===== Left: Form ===== */
        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background: var(--surface);
        }
        .auth-form-inner {
            width: 100%;
            max-width: 400px;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 32px;
        }
        .auth-logo img { height: 36px; }
        .auth-logo span {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--ink-900);
            font-family: var(--font-heading);
        }
        .auth-logo span .accent { color: var(--primary-700); }

        .auth-head {
            margin-bottom: 28px;
        }
        .auth-head h1 {
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 700;
            color: var(--ink-900);
            margin-bottom: 6px;
        }
        .auth-head p {
            font-size: 14px;
            color: var(--ink-600);
        }

        .alert {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            animation: fadeInUp 0.3s ease;
        }
        .alert i { width: 16px; height: 16px; flex-shrink: 0; }
        .alert-error { background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.15); color: var(--status-danger); }
        .alert-success { background: rgba(22,163,74,0.08); border: 1px solid rgba(22,163,74,0.15); color: var(--status-success); }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-900);
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrap .input-icon {
            position: absolute;
            left: 12px;
            color: #94A3B8;
            pointer-events: none;
            display: flex;
        }
        .input-wrap .input-icon i { width: 16px; height: 16px; }

        .field-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border-radius: var(--radius-sm);
            background: var(--surface);
            border: 1.5px solid var(--line-200);
            color: var(--ink-900);
            font-size: 14px;
            font-family: var(--font-body);
            outline: none;
            transition: all 0.2s ease;
        }
        .field-input:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .field-input::placeholder { color: #94A3B8; }

        .pass-toggle {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 4px;
            display: flex;
        }
        .pass-toggle:hover { color: var(--ink-600); }
        .pass-toggle i { width: 16px; height: 16px; }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .check-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .check-wrap input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--primary-700);
            cursor: pointer;
        }
        .check-label { font-size: 13px; color: var(--ink-600); }
        .forgot-link {
            font-size: 13px;
            color: var(--primary-500);
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { color: var(--primary-700); text-decoration: underline; }

        .btn-submit {
            width: 100%;
            padding: 14px 24px;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--primary-700);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font-body);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(29, 78, 216, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: var(--primary-500);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
        }
        .btn-submit i { width: 18px; height: 18px; }

        .auth-foot {
            text-align: center;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--line-200);
        }
        .auth-foot p { font-size: 13px; color: var(--ink-600); }
        .auth-foot a { color: var(--primary-500); text-decoration: none; font-weight: 600; }
        .auth-foot a:hover { color: var(--primary-700); text-decoration: underline; }

        .admin-login-link {
            text-align: center;
            margin-top: 12px;
            font-size: 12px;
        }
        .admin-login-link a {
            color: #94A3B8;
            text-decoration: none;
        }
        .admin-login-link a:hover {
            color: var(--ink-600);
            text-decoration: underline;
        }

        /* ===== Right: Ilustrasi ===== */
        .auth-illustration {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1D4ED8 0%, #1E3A8A 100%);
            position: relative;
            overflow: hidden;
            padding: 40px;
        }
        .auth-illustration::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200"><path d="M0 100 Q 50 80, 100 100 T 200 100 T 300 100 T 400 100" stroke="rgba(255,255,255,0.05)" fill="none" stroke-width="2" stroke-dasharray="8 8"/></svg>') repeat;
            background-size: 400px 200px;
            opacity: 0.3;
        }
        .auth-illustration .ill-content {
            text-align: center;
            color: #fff;
            position: relative;
            z-index: 1;
            max-width: 400px;
        }
        .auth-illustration .ill-content i {
            width: 64px; height: 64px;
            margin-bottom: 20px;
            opacity: 0.8;
        }
        .auth-illustration .ill-content h2 {
            font-family: var(--font-heading);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .auth-illustration .ill-content .ill-quote {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.85;
            margin-bottom: 24px;
        }
        .auth-illustration .ill-content .ill-stats {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.1);
            border-radius: var(--radius-full);
            font-size: 14px;
            font-weight: 600;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .auth-illustration { display: none; }
            .auth-form-side { padding: 24px 16px; }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- LEFT: Form Login -->
        <div class="auth-form-side">
            <div class="auth-form-inner">
                <a href="../index.php" class="auth-logo">
                    <img src="../assets/Logo.png" alt="RoKenAI">
                    <span>RoKen<span class="accent">AI</span></span>
                </a>

                <div class="auth-head">
                    <h1>Selamat Datang</h1>
                    <p>Masuk untuk melaporkan dan memantau perbaikan jalan.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <i data-lucide="circle-alert"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success">
                        <i data-lucide="check-circle-2"></i>
                        <span>Akun berhasil dibuat! Silakan masuk.</span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Nama Pengguna atau Email</label>
                        <div class="input-wrap">
                            <span class="input-icon"><i data-lucide="user"></i></span>
                            <input class="field-input" type="text" id="username" name="username" placeholder="Masukkan nama pengguna atau email" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrap">
                            <span class="input-icon"><i data-lucide="lock"></i></span>
                            <input class="field-input" type="password" id="password" name="password" placeholder="Masukkan kata sandi" required autocomplete="current-password">
                            <button type="button" class="pass-toggle" id="togglePass" onclick="togglePassword()" tabindex="-1" aria-label="Toggle visibility">
                                <i data-lucide="eye" id="passIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="check-wrap">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="check-label">Ingat saya</span>
                        </label>
                        <a href="#" class="forgot-link">Lupa password?</a>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button type="submit" name="login" class="btn-submit">
                        <i data-lucide="log-in"></i>
                        Masuk
                    </button>
                </form>

                <div class="auth-foot">
                    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
                </div>
                <div class="admin-login-link">
                    <a href="#">Masuk sebagai Admin</a>
                </div>
            </div>
        </div>

        <!-- RIGHT: Ilustrasi -->
        <div class="auth-illustration">
            <div class="ill-content">
                <i data-lucide="route"></i>
                <h2>RoKenAI</h2>
                <div class="ill-quote">
                    "Laporkan jalan rusak dalam hitungan detik.<br>
                    AI kami yang verifikasi,<br>
                    pemerintah yang tindak lanjuti."
                </div>
                <div class="ill-stats">
                    <i data-lucide="check-circle-2"></i>
                    1.200+ laporan sudah ditindaklanjuti
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

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
