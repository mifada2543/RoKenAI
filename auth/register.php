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
<html lang="id">

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

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#0B0F19',
                        'dark-card': '#1E293B',
                        'brand-yellow': '#FACC15',
                        'brand-amber': '#EAB308',
                        'brand-indigo': '#6366F1',
                    },
                    fontFamily: {
                        'jakarta': ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    keyframes: {
                        floatY: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-14px)' },
                        },
                        fadeInUp: {
                            from: { opacity: '0', transform: 'translateY(18px)' },
                            to: { opacity: '1', transform: 'translateY(0)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        },
                    },
                    animation: {
                        'float': 'floatY 9s ease-in-out infinite',
                        'float-slow': 'floatY 13s ease-in-out infinite reverse',
                        'float-med': 'floatY 11s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp .55s ease',
                        'shimmer': 'shimmer 3s ease-in-out infinite',
                    },
                }
            }
        }
    </script>

    <style>
        /* ===== CSS minimal ===== */
        *, *::before, *::after { transition: background-color .3s ease, border-color .3s ease, color .3s ease; box-sizing: border-box; }
        body { -webkit-font-smoothing: antialiased; }
        .orb { position:fixed; border-radius:50%; pointer-events:none; z-index:0; filter:blur(80px); }
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.08); border-radius:4px; }

        .gradient-text-logo {
            background: linear-gradient(135deg, #FACC15 0%, #6366F1 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-shimmer::after {
            content:''; position:absolute; inset:0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
            pointer-events: none;
        }
        /* Dark mode overrides untuk Bootstrap form-check */
        [data-theme="dark"] .form-check-input:checked { background-color:#FACC15 !important; border-color:#FACC15 !important; }
        [data-theme="light"] .form-check-input:checked { background-color:#6366F1 !important; border-color:#6366F1 !important; }
    </style>
</head>

<body class="font-jakarta min-h-screen flex items-center justify-center overflow-x-hidden antialiased" style="background:var(--bg-body, #0B0F19);">

    <!-- Background Orbs -->
    <div class="orb w-[350px] h-[350px] -top-25 -left-20 animate-float" style="background:rgba(99,102,241,0.06)"></div>
    <div class="orb w-[400px] h-[400px] -bottom-36 -right-30 animate-float-slow" style="background:rgba(250,204,21,0.08)"></div>
    <div class="orb w-[200px] h-[200px] top-1/2 left-[5%] animate-float-med" style="background:rgba(250,204,21,0.06)"></div>

    <!-- Theme Toggle -->
    <button class="fixed top-5 right-5 z-10 w-11 h-11 rounded-xl bg-[var(--bg-card,#1E293B)] border border-[var(--border-color,rgba(255,255,255,0.08))] flex items-center justify-center cursor-pointer text-gray-400 hover:border-yellow-500/30 hover:text-yellow-500 hover:shadow-[0_0_20px_rgba(250,204,21,0.08)] transition-all backdrop-blur-xl" id="themeToggleBtn" aria-label="Toggle theme">
        <i data-lucide="moon" id="themeIcon" class="w-5 h-5"></i>
    </button>

    <!-- Auth Wrapper -->
    <div class="relative z-1 w-full max-w-[460px] p-5 animate-fade-in-up">

        <!-- Back to Home -->
        <a href="../index.php" class="inline-flex items-center gap-1.5 text-gray-500 no-underline text-sm font-medium mb-6 hover:text-yellow-500 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Beranda</span>
        </a>

        <div class="bg-[var(--bg-card,#1E293B)] border border-[var(--border-color,rgba(255,255,255,0.08))] rounded-[28px] p-10 px-9 shadow-[0_25px_50px_rgba(0,0,0,0.4)] relative overflow-hidden backdrop-blur-2xl"
             style="background:var(--bg-card,#1E293B); border-color:var(--border-color,rgba(255,255,255,0.08));">
            <!-- Gradient top border -->
            <div class="absolute inset-x-0 top-0 h-px opacity-50" style="background:linear-gradient(90deg, transparent, #6366F1, transparent)"></div>

            <!-- Logo -->
            <a href="../index.php" class="flex items-center justify-center gap-3 no-underline mb-2">
                <img src="../assets/Logo.png" alt="RoKenAI Logo" class="h-[46px] w-auto">
                <span class="text-2xl font-extrabold tracking-tight gradient-text-logo">RoKenAI</span>
            </a>

            <!-- Header -->
            <div class="text-center mb-4 mt-1">
                <h1 class="font-bold mb-1 text-[22px] tracking-tight" style="color:var(--text-primary,#F1F5F9);">Buat Akun</h1>
                <p class="mb-0 text-sm" style="color:var(--text-secondary,#94A3B8); line-height:1.5;">Daftar dan tunggu verifikasi admin untuk mulai menggunakan platform.</p>
            </div>

            <!-- Error Alert -->
            <?php if (!empty($error)): ?>
                <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-[13px] font-medium animate-fade-in-up mb-3 bg-[rgba(239,68,68,0.1)] border border-[rgba(239,68,68,0.2)] text-red-500">
                    <i data-lucide="circle-alert" class="w-[18px] h-[18px] shrink-0"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- Username -->
                <div class="mb-3">
                    <label class="block text-[12px] font-semibold mb-[7px] tracking-[0.04em] uppercase" for="reg_username" style="color:var(--text-secondary,#94A3B8);">Username</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-[15px] pointer-events-none" style="color:var(--text-muted,#64748B);"><i data-lucide="user" class="w-[18px] h-[18px]"></i></span>
                        <input class="w-full py-[13px] px-[46px] rounded-xl text-sm font-medium outline-none transition-all duration-250 border"
                            type="text" id="reg_username" name="username"
                            placeholder="Minimal 3 karakter, hanya huruf/angka/_"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                            required autocomplete="username" minlength="3" maxlength="32"
                            style="background:var(--bg-input,rgba(255,255,255,0.04)); border-color:var(--border-subtle,rgba(255,255,255,0.06)); color:var(--text-primary,#F1F5F9); font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <p class="text-[11px] mt-1" style="color:var(--text-muted,#64748B);">Huruf, angka, dan underscore saja. Tidak boleh mengandung 'guest'.</p>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="block text-[12px] font-semibold mb-[7px] tracking-[0.04em] uppercase" for="reg_email" style="color:var(--text-secondary,#94A3B8);">Email</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-[15px] pointer-events-none" style="color:var(--text-muted,#64748B);"><i data-lucide="mail" class="w-[18px] h-[18px]"></i></span>
                        <input class="w-full py-[13px] px-[46px] rounded-xl text-sm font-medium outline-none transition-all duration-250 border"
                            type="email" id="reg_email" name="email"
                            placeholder="nama@contoh.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            required autocomplete="email"
                            style="background:var(--bg-input,rgba(255,255,255,0.04)); border-color:var(--border-subtle,rgba(255,255,255,0.06)); color:var(--text-primary,#F1F5F9); font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="block text-[12px] font-semibold mb-[7px] tracking-[0.04em] uppercase" for="reg_password" style="color:var(--text-secondary,#94A3B8);">Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-[15px] pointer-events-none" style="color:var(--text-muted,#64748B);"><i data-lucide="lock" class="w-[18px] h-[18px]"></i></span>
                        <input class="w-full py-[13px] px-[46px] rounded-xl text-sm font-medium outline-none transition-all duration-250 border"
                            type="password" id="reg_password" name="password"
                            placeholder="Minimal 8 karakter" required autocomplete="new-password" minlength="8"
                            oninput="checkStrength(this.value)"
                            style="background:var(--bg-input,rgba(255,255,255,0.04)); border-color:var(--border-subtle,rgba(255,255,255,0.06)); color:var(--text-primary,#F1F5F9); font-family:'Plus Jakarta Sans',sans-serif;">
                        <button type="button" class="absolute right-[13px] bg-none border-none text-gray-500 cursor-pointer p-1 flex items-center hover:text-gray-400 transition-colors" onclick="togglePass('reg_password','icon1')" tabindex="-1" aria-label="Tampilkan password">
                            <i data-lucide="eye" id="icon1" class="w-[18px] h-[18px]"></i>
                        </button>
                    </div>
                    <!-- Strength bar -->
                    <div class="h-1 rounded overflow-hidden mt-2" style="background:var(--border-color,rgba(255,255,255,0.08));">
                        <div class="strength-fill h-full rounded transition-all duration-400" id="strengthFill" style="width:0; background:transparent;"></div>
                    </div>
                    <p class="text-[11px] mt-1" id="strengthLabel" style="color:var(--text-muted,#64748B);"></p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="block text-[12px] font-semibold mb-[7px] tracking-[0.04em] uppercase" for="reg_confirm" style="color:var(--text-secondary,#94A3B8);">Konfirmasi Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-[15px] pointer-events-none" style="color:var(--text-muted,#64748B);"><i data-lucide="shield-check" class="w-[18px] h-[18px]"></i></span>
                        <input class="w-full py-[13px] px-[46px] rounded-xl text-sm font-medium outline-none transition-all duration-250 border"
                            type="password" id="reg_confirm" name="confirm_password"
                            placeholder="Ulangi password" required autocomplete="new-password"
                            style="background:var(--bg-input,rgba(255,255,255,0.04)); border-color:var(--border-subtle,rgba(255,255,255,0.06)); color:var(--text-primary,#F1F5F9); font-family:'Plus Jakarta Sans',sans-serif;">
                        <button type="button" class="absolute right-[13px] bg-none border-none text-gray-500 cursor-pointer p-1 flex items-center hover:text-gray-400 transition-colors" onclick="togglePass('reg_confirm','icon2')" tabindex="-1" aria-label="Tampilkan konfirmasi password">
                            <i data-lucide="eye" id="icon2" class="w-[18px] h-[18px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" name="register" class="btn-shimmer relative w-full py-[15px] px-6 border-none rounded-[16px] text-[15px] font-bold font-jakarta cursor-pointer flex items-center justify-center gap-2 overflow-hidden transition-all duration-250"
                    style="background:linear-gradient(135deg, #6366F1, #4F46E5); color:#fff; box-shadow:0 0 25px rgba(99,102,241,0.18);">
                    <i data-lucide="user-plus" class="w-[18px] h-[18px] relative z-1"></i>
                    <span class="relative z-1">Daftar Sekarang</span>
                </button>
            </form>

            <!-- Pending notice -->
            <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-[13px] font-medium mt-3 bg-[rgba(99,102,241,0.08)] border border-[rgba(99,102,241,0.2)]" style="color:var(--brand-indigo,#6366F1);">
                <i data-lucide="info" class="w-[18px] h-[18px] shrink-0"></i>
                <span>Akun baru membutuhkan verifikasi admin sebelum dapat digunakan.</span>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4 pt-4 border-t" style="border-color:var(--border-muted,rgba(255,255,255,0.04));">
                <p class="mb-0 text-[13px]" style="color:var(--text-muted,#64748B);">
                    Sudah punya akun? <a href="login.php" class="text-yellow-500 no-underline font-semibold hover:text-amber-500 transition-colors">Masuk di sini</a>
                </p>
            </div>

        </div>
    </div>

    <style>
        /* Focus style untuk input auth */
        input:focus {
            border-color: rgba(99,102,241,.4) !important;
            background: rgba(255,255,255,0.07) !important;
            box-shadow: 0 0 0 4px rgba(99,102,241,.07) !important;
        }
        button[name="register"]:hover { transform: translateY(-2px); box-shadow: 0 0 40px rgba(99,102,241,.35) !important; }
        button[name="register"]:active { transform: translateY(0); }
    </style>

    <script>
        lucide.createIcons();

        // Apply inline focus/blur handlers
        document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]').forEach(function(el) {
            el.addEventListener('focus', function(e) {
                e.target.style.borderColor = 'rgba(99,102,241,.4)';
                e.target.style.background = 'rgba(255,255,255,0.07)';
                e.target.style.boxShadow = '0 0 0 4px rgba(99,102,241,.07)';
            });
            el.addEventListener('blur', function(e) {
                e.target.style.borderColor = '';
                e.target.style.background = '';
                e.target.style.boxShadow = '';
            });
        });

        // ===== Theme =====
        function getTheme()    { return localStorage.getItem('roken-theme') || 'dark'; }
        function applyTheme(t) {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');
            const card = document.querySelector('[class*="rounded-\\[28px\\]"]');
            if (t === 'light') {
                html.setAttribute('data-theme', 'light');
                icon.setAttribute('data-lucide', 'sun');
                document.querySelector('meta[name="theme-color"]')?.setAttribute('content', '#F5F7FA');
                html.style.setProperty('--bg-body', '#F5F7FA');
                html.style.setProperty('--text-primary', '#0F172A');
                html.style.setProperty('--text-secondary', '#475569');
                if (card) {
                    card.style.setProperty('--bg-card', '#FFFFFF');
                    card.style.setProperty('--border-color', 'rgba(0,0,0,0.08)');
                }
            } else {
                html.setAttribute('data-theme', 'dark');
                icon.setAttribute('data-lucide', 'moon');
                document.querySelector('meta[name="theme-color"]')?.setAttribute('content', '#0B0F19');
                html.style.setProperty('--bg-body', '#0B0F19');
                html.style.setProperty('--text-primary', '#F1F5F9');
                html.style.setProperty('--text-secondary', '#94A3B8');
                if (card) {
                    card.style.setProperty('--bg-card', '#1E293B');
                    card.style.setProperty('--border-color', 'rgba(255,255,255,0.08)');
                }
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

        // Hover effect untuk submit button via JS
        document.querySelector('button[name="register"]')?.addEventListener('mouseenter', function(e) {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 0 40px rgba(99,102,241,.35)';
        });
        document.querySelector('button[name="register"]')?.addEventListener('mouseleave', function(e) {
            this.style.transform = '';
            this.style.boxShadow = '0 0 25px rgba(99,102,241,.18)';
        });
        document.querySelector('button[name="register"]')?.addEventListener('mousedown', function(e) {
            this.style.transform = 'translateY(0)';
        });
    </script>
</body>

</html>
