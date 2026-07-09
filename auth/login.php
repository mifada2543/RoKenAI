<?php
include 'config.php';

// Baca parameter redirect jika ada — simpan di session agar tidak hilang saat POST
if (!empty($_GET['redirect'])) {
    $_SESSION['login_redirect'] = $_GET['redirect'];
}
$redirectUrl = $_SESSION['login_redirect'] ?? null;

// Validasi redirect URL — tolak URL absolute (://) dan protocol-relative (//)
if ($redirectUrl && (strpos($redirectUrl, '://') !== false || strpos($redirectUrl, '//') === 0)) {
    $redirectUrl = null;
    unset($_SESSION['login_redirect']);
}

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ' . ($redirectUrl ?: '../profile.php'));
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
                        // Redirect admin ke panel admin
                        if ($user['role'] === 'admin' && !$redirectUrl) {
                            $redirectUrl = '../admin/index.php';
                        }
                        $finalRedirect = $redirectUrl ?: '../profile.php';
                        unset($_SESSION['login_redirect']);
                        header('Location: ' . $finalRedirect);
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
<html lang="id">

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
                        'float': 'floatY 8s ease-in-out infinite',
                        'float-slow': 'floatY 12s ease-in-out infinite reverse',
                        'float-med': 'floatY 10s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp .55s ease',
                        'shimmer': 'shimmer 3s ease-in-out infinite',
                    },
                }
            }
        }
    </script>

    <style>
        /* ===== CSS minimal — orbs, scrollbar, gradient text ===== */
        *, *::before, *::after { transition: background-color .3s ease, border-color .3s ease, color .3s ease; box-sizing: border-box; }
        body { -webkit-font-smoothing: antialiased; overflow: hidden; }
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
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
            pointer-events: none;
        }
        /* Dark mode overrides untuk Bootstrap form-check */
        [data-theme="dark"] .form-check-input:checked { background-color:#FACC15 !important; border-color:#FACC15 !important; }
        [data-theme="light"] .form-check-input:checked { background-color:#6366F1 !important; border-color:#6366F1 !important; }
    </style>
</head>

<body class="font-jakarta min-h-screen flex items-center justify-center overflow-hidden antialiased" style="background:var(--bg-body, #0B0F19);">

    <!-- Background Orbs -->
    <div class="orb w-[350px] h-[350px] -top-30 -right-20 animate-float" style="background:rgba(250,204,21,0.08)"></div>
    <div class="orb w-[400px] h-[400px] -bottom-36 -left-30 animate-float-slow" style="background:rgba(99,102,241,0.06)"></div>
    <div class="orb w-[200px] h-[200px] top-1/2 right-[10%] animate-float-med" style="background:rgba(99,102,241,0.06)"></div>

    <!-- Theme Toggle -->
    <button class="fixed top-5 right-5 z-10 w-11 h-11 rounded-xl bg-[var(--bg-card,#1E293B)] border border-[var(--border-color,rgba(255,255,255,0.08))] flex items-center justify-center cursor-pointer text-gray-400 hover:border-yellow-500/30 hover:text-yellow-500 hover:shadow-[0_0_20px_rgba(250,204,21,0.08)] transition-all backdrop-blur-xl" id="themeToggleBtn" aria-label="Toggle theme">
        <i data-lucide="moon" id="themeIcon" class="w-5 h-5"></i>
    </button>

    <!-- Auth Wrapper -->
    <div class="relative z-1 w-full max-w-[440px] p-5 animate-fade-in-up">

        <!-- Back to Home -->
        <a href="../index.php" class="inline-flex items-center gap-1.5 text-gray-500 no-underline text-sm font-medium mb-6 hover:text-yellow-500 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Beranda</span>
        </a>

        <div class="bg-[var(--bg-card,#1E293B)] border border-[var(--border-color,rgba(255,255,255,0.08))] rounded-[28px] p-10 px-9 shadow-[0_25px_50px_rgba(0,0,0,0.4)] relative overflow-hidden backdrop-blur-2xl"
             style="background:var(--bg-card,#1E293B); border-color:var(--border-color,rgba(255,255,255,0.08));">
            <!-- Gradient top border -->
            <div class="absolute inset-x-0 top-0 h-px opacity-50" style="background:linear-gradient(90deg, transparent, #FACC15, transparent)"></div>

            <!-- Logo -->
            <a href="../index.php" class="flex items-center justify-center gap-3 no-underline mb-2">
                <img src="../assets/Logo.png" alt="RoKenAI Logo" class="h-[46px] w-auto">
                <span class="text-2xl font-extrabold tracking-tight gradient-text-logo">RoKenAI</span>
            </a>

            <!-- Header -->
            <div class="text-center mb-4 mt-1">
                <h1 class="font-bold mb-1 text-[22px] tracking-tight" style="color:var(--text-primary,#F1F5F9);">Selamat Datang</h1>
                <p class="mb-0 text-sm" style="color:var(--text-secondary,#94A3B8); line-height:1.5;">Masuk untuk mengakses dashboard inspeksi jalan berbasis AI.</p>
            </div>

            <!-- Alerts -->
            <?php if (!empty($error)): ?>
                <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-[13px] font-medium animate-fade-in-up mb-3 bg-[rgba(239,68,68,0.1)] border border-[rgba(239,68,68,0.2)] text-red-500">
                    <i data-lucide="circle-alert" class="w-[18px] h-[18px] shrink-0"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
                <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-[13px] font-medium animate-fade-in-up mb-3 bg-[rgba(34,197,94,0.1)] border border-[rgba(34,197,94,0.2)] text-green-500">
                    <i data-lucide="check-circle-2" class="w-[18px] h-[18px] shrink-0"></i>
                    <span>Pendaftaran berhasil! Silakan tunggu verifikasi admin, lalu masuk.</span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- Username / Email -->
                <div class="mb-3">
                    <label class="block text-[12px] font-semibold mb-[7px] tracking-[0.04em] uppercase" for="username" style="color:var(--text-secondary,#94A3B8);">Username atau Email</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-[15px] pointer-events-none" style="color:var(--text-muted,#64748B);"><i data-lucide="user" class="w-[18px] h-[18px]"></i></span>
                        <input class="w-full py-[13px] px-[46px] rounded-xl text-sm font-medium outline-none transition-all duration-250 border"
                            type="text" id="username" name="username"
                            placeholder="Masukkan username atau email"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                            required autocomplete="username"
                            style="background:var(--bg-input,rgba(255,255,255,0.04)); border-color:var(--border-subtle,rgba(255,255,255,0.06)); color:var(--text-primary,#F1F5F9); font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="block text-[12px] font-semibold mb-[7px] tracking-[0.04em] uppercase" for="password" style="color:var(--text-secondary,#94A3B8);">Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-[15px] pointer-events-none" style="color:var(--text-muted,#64748B);"><i data-lucide="lock" class="w-[18px] h-[18px]"></i></span>
                        <input class="w-full py-[13px] px-[46px] rounded-xl text-sm font-medium outline-none transition-all duration-250 border"
                            type="password" id="password" name="password"
                            placeholder="Masukkan password" required autocomplete="current-password"
                            style="background:var(--bg-input,rgba(255,255,255,0.04)); border-color:var(--border-subtle,rgba(255,255,255,0.06)); color:var(--text-primary,#F1F5F9); font-family:'Plus Jakarta Sans',sans-serif;">
                        <button type="button" class="absolute right-[13px] bg-none border-none text-gray-500 cursor-pointer p-1 flex items-center hover:text-gray-400 transition-colors" id="togglePass" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password visibility">
                            <i data-lucide="eye" id="passIcon" class="w-[18px] h-[18px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label text-[13px] cursor-pointer" for="remember" style="color:var(--text-secondary,#94A3B8);">Ingat saya</label>
                    </div>
                    <a href="#" class="text-[13px] no-underline font-medium transition-colors duration-200" style="color:var(--brand-indigo,#6366F1);" onmouseover="this.style.color='#FACC15'" onmouseout="this.style.color='#6366F1'">Lupa password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" name="login" class="btn-shimmer relative w-full py-[15px] px-6 border-none rounded-[16px] text-[15px] font-bold font-jakarta cursor-pointer flex items-center justify-center gap-2 overflow-hidden transition-all duration-250"
                    style="background:linear-gradient(135deg, #FACC15, #EAB308); color:#0B0F19; box-shadow:0 0 25px rgba(250,204,21,0.12);">
                    <i data-lucide="log-in" class="w-[18px] h-[18px] relative z-1"></i>
                    <span class="relative z-1">Masuk</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="text-center mt-4 pt-4 border-t" style="border-color:var(--border-muted,rgba(255,255,255,0.04));">
                <p class="mb-0 text-[13px]" style="color:var(--text-muted,#64748B);">
                    Belum punya akun? <a href="register.php" class="text-yellow-500 no-underline font-semibold hover:text-amber-500 transition-colors">Daftar sekarang</a>
                </p>
            </div>

        </div>
    </div>



    <script>
        lucide.createIcons();

        // Apply inline focus/blur handlers to override focus styles with JS
        document.querySelectorAll('.form-input-auth, [class*="form-input"]').forEach(function(el) {
            el.addEventListener('focus', function(e) {
                e.target.style.borderColor = 'rgba(250,204,21,.3)';
                e.target.style.background = 'rgba(255,255,255,0.07)';
                e.target.style.boxShadow = '0 0 0 4px rgba(250,204,21,.06)';
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
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('passIcon');
            const isHidden = pass.type === 'password';
            pass.type = isHidden ? 'text' : 'password';
            icon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');
            lucide.createIcons();
        }

        // Hover effect untuk submit button via JS
        document.querySelector('button[name="login"]')?.addEventListener('mouseenter', function(e) {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 0 40px rgba(250,204,21,.28)';
        });
        document.querySelector('button[name="login"]')?.addEventListener('mouseleave', function(e) {
            this.style.transform = '';
            this.style.boxShadow = '0 0 25px rgba(250,204,21,.12)';
        });
        document.querySelector('button[name="login"]')?.addEventListener('mousedown', function(e) {
            this.style.transform = 'translateY(0)';
        });
    </script>
</body>

</html>
