<?php
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

// Handle registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (!verify_csrf()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
            $error = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($username) < 3 || strlen($username) > 32) {
            $error = 'Username must be between 3 and 32 characters.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = 'Username or email already exists.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("sss", $username, $email, $hashed);

                if ($stmt->execute()) {
                    header('Location: login.php?registered=1');
                    exit;
                } else {
                    $error = 'Registration failed. Please try again.';
                }
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
    <title>RoKenAI | Daftar</title>
    <link rel="icon" type="image/png" href="../assets/Logo.png">
    <?php include '../partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Daftar (desain.md 5.2)
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
            max-width: 420px;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 28px;
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
            margin-bottom: 24px;
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

        .form-group { margin-bottom: 16px; }
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

        /* Password strength bars */
        .pw-strength {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }
        .pw-bar {
            flex: 1;
            height: 3px;
            border-radius: 100px;
            background: var(--line-200);
            transition: all 0.3s ease;
        }
        .pw-bar.active.weak { background: var(--status-danger); }
        .pw-bar.active.medium { background: var(--status-warning); }
        .pw-bar.active.strong { background: var(--status-success); }
        .pw-text {
            font-size: 11px;
            color: #94A3B8;
            margin-top: 4px;
            min-height: 16px;
            font-weight: 500;
        }

        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 18px;
        }
        .terms-check input[type="checkbox"] {
            width: 15px; height: 15px; min-width: 15px;
            accent-color: var(--primary-700);
            margin-top: 2px;
            cursor: pointer;
        }
        .terms-check label {
            font-size: 12px;
            color: var(--ink-600);
            line-height: 1.5;
            cursor: pointer;
        }
        .terms-check a {
            color: var(--primary-500);
            text-decoration: none;
            font-weight: 500;
        }
        .terms-check a:hover { color: var(--primary-700); text-decoration: underline; }

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

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0 16px;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line-200);
        }
        .auth-divider span {
            font-size: 12px;
            color: #94A3B8;
            font-weight: 500;
            white-space: nowrap;
        }

        .social-btns {
            display: flex;
            gap: 10px;
        }
        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px;
            border-radius: var(--radius-sm);
            background: var(--surface);
            border: 1.5px solid var(--line-200);
            color: var(--ink-600);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: var(--font-body);
        }
        .social-btn:hover {
            background: var(--primary-100);
            border-color: var(--primary-500);
            color: var(--primary-700);
        }
        .social-btn i { width: 18px; height: 18px; }

        .auth-foot {
            text-align: center;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--line-200);
        }
        .auth-foot p { font-size: 13px; color: var(--ink-600); }
        .auth-foot a { color: var(--primary-500); text-decoration: none; font-weight: 600; }
        .auth-foot a:hover { color: var(--primary-700); text-decoration: underline; }

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
        <!-- LEFT: Form Daftar -->
        <div class="auth-form-side">
            <div class="auth-form-inner">
                <a href="../index.php" class="auth-logo">
                    <img src="../assets/Logo.png" alt="RoKenAI">
                    <span>RoKen<span class="accent">AI</span></span>
                </a>

                <div class="auth-head">
                    <h1>Buat Akun Baru</h1>
                    <p>Daftar untuk mulai melaporkan dan memantau perbaikan jalan.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <i data-lucide="circle-alert"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="regUsername">Nama Pengguna</label>
                        <div class="input-wrap">
                            <span class="input-icon"><i data-lucide="user"></i></span>
                            <input class="field-input" type="text" id="regUsername" name="username" placeholder="Pilih nama pengguna" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required minlength="3" autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrap">
                            <span class="input-icon"><i data-lucide="mail"></i></span>
                            <input class="field-input" type="email" id="email" name="email" placeholder="Masukkan email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autocomplete="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="regPassword">Kata Sandi</label>
                        <div class="input-wrap">
                            <span class="input-icon"><i data-lucide="lock"></i></span>
                            <input class="field-input" type="password" id="regPassword" name="password" placeholder="Minimal 8 karakter" required minlength="8" autocomplete="new-password" oninput="checkStrength(this.value)">
                            <button type="button" class="pass-toggle" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password visibility">
                                <i data-lucide="eye" id="regPassIcon"></i>
                            </button>
                        </div>
                        <!-- ================================================================
                             Password Strength Indicator
                             Penjelasan: 3 bar horizontal yang berubah warna berdasarkan
                             kekuatan password (weak = merah, medium = kuning, strong = hijau).
                             ================================================================ -->
                        <div class="pw-strength" id="pwBars">
                            <div class="pw-bar" data-idx="0"></div>
                            <div class="pw-bar" data-idx="1"></div>
                            <div class="pw-bar" data-idx="2"></div>
                        </div>
                        <div class="pw-text" id="pwText"></div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Kata Sandi</label>
                        <div class="input-wrap">
                            <span class="input-icon"><i data-lucide="lock"></i></span>
                            <input class="field-input" type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi kata sandi" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="terms-check">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a></label>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button type="submit" name="register" class="btn-submit">
                        <i data-lucide="user-plus"></i>
                        Daftar
                    </button>
                </form>

                <div class="auth-divider"><span>Atau daftar dengan</span></div>
                <div class="social-btns">
                    <button class="social-btn" onclick="alert('Login dengan Google segera hadir!')"><i data-lucide="chrome"></i> Google</button>
                    <button class="social-btn" onclick="alert('Login dengan GitHub segera hadir!')"><i data-lucide="github"></i> GitHub</button>
                </div>

                <div class="auth-foot">
                    <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
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

        // ================================================================
        // FUNGSI TOGGLE PASSWORD
        // Menampilkan/sembunyikan teks password dengan ikon eye/eye-off.
        // ================================================================
        function togglePassword() {
            const pass = document.getElementById('regPassword');
            const icon = document.getElementById('regPassIcon');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                pass.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        // ================================================================
        // FUNGSI CHECK PASSWORD STRENGTH
        // Mengecek kekuatan password berdasarkan panjang, huruf besar,
        // angka, dan karakter spesial. Menampilkan 3 bar indikator.
        // ================================================================
        function checkStrength(val) {
            const bars = document.querySelectorAll('.pw-bar');
            const text = document.getElementById('pwText');
            bars.forEach(function(b) { b.className = 'pw-bar'; });

            if (val.length === 0) {
                text.textContent = '';
                return;
            }

            var score = 0;
            if (val.length >= 8) score++;
            if (val.length >= 12) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            var level, label;
            if (score <= 2) { level = 'weak'; label = 'Lemah'; }
            else if (score <= 3) { level = 'medium'; label = 'Sedang'; }
            else { level = 'strong'; label = 'Kuat'; }

            var activeBars = score <= 2 ? 1 : score <= 3 ? 2 : 3;
            for (var i = 0; i < activeBars; i++) {
                bars[i].classList.add('active', level);
            }

            text.textContent = 'Kekuatan password: ' + label;
            text.style.color = level === 'weak' ? '#DC2626' : level === 'medium' ? '#F59E0B' : '#16A34A';
        }
    </script>
</body>
</html>
