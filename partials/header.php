<style>
    #sidebar {
        transition: transform 0.3s ease-in-out;
        transform: translateX(-100%);
    }

    #sidebar.active {
        transform: translateX(0);
    }

    #content-wrapper {
        transition: filter 0.3s ease-in-out;
    }

    #content-wrapper.blurred {
        filter: blur(5px);
        pointer-events: none;
        user-select: none;
    }

    #overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 40;
        display: none;
    }

    #overlay.active {
        display: block;
    }

    .roken-navbar {
        background: #d1d5db;
        /* gray-300 */
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    }

    .roken-navbar .logo-img {
        height: 64px;
        object-fit: contain;
    }

    @media (min-width: 768px) {
        .roken-navbar .logo-img {
            height: 80px;
        }
    }

    .nav-icon-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        color: #1f2937;
    }

    .nav-icon-btn:hover {
        color: #374151;
    }

    .profile-circle {
        background: #fff;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
    }
</style>

<!-- Overlay -->
<div id="overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 h-full bg-gray-800 text-white z-50 p-6 shadow-2xl" style="width:260px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-5 fw-bold mb-0">Lainnya</h2>
        <button class="nav-icon-btn text-white" onclick="toggleSidebar()" aria-label="Tutup">
            <i data-lucide="x"></i>
        </button>
    </div>
    <ul class="list-unstyled d-flex flex-column gap-4 mt-4">
        <li><a href="chat.php" class="text-white text-decoration-none d-flex align-items-center gap-2 hover-yellow">
                <i data-lucide="message-square"></i> Chat
            </a></li>
        <li><a href="#" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <i data-lucide="book-open"></i> Contoh
            </a></li>
        <li><a href="#" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <i data-lucide="newspaper"></i> Berita
            </a></li>
    </ul>
</div>

<!-- Navbar -->
<nav class="roken-navbar">
    <div class="container-fluid px-3 px-md-4">
        <div class="row align-items-center g-0">
            <div class="col-3 col-md-2">
                <button class="nav-icon-btn" onclick="toggleSidebar()" aria-label="Menu">
                    <i data-lucide="menu" style="width:24px;height:24px;"></i>
                </button>
            </div>
            <div class="col-6 col-md-8 text-center">
                <a href="index.php">
                    <img src="assets/Logo.png" alt="RoKenAI Logo" class="logo-img">
                </a>
            </div>
            <div class="col-3 col-md-2 d-flex justify-content-end">
                <button class="nav-icon-btn" aria-label="Profil">
                    <div class="profile-circle">
                        <i data-lucide="user" style="width:18px;height:18px;color:#374151;"></i>
                    </div>
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('overlay').classList.toggle('active');
        const cw = document.getElementById('content-wrapper');
        if (cw) cw.classList.toggle('blurred');
    }
</script>