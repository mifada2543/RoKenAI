<?php
session_name('RoKenAI');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen AI | Home</title>
    <?php include 'partials/link.php'; ?>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: white;
            overflow-x: hidden;
        }

        /* Animasi Sidebar */
        #sidebar {
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }

        #sidebar.active {
            transform: translateX(0);
        }

        /* Efek Blur pada konten utama */
        #content-wrapper {
            transition: filter 0.3s ease-in-out;
        }

        #content-wrapper.blurred {
            filter: blur(5px);
            pointer-events: none;
        }

        /* Overlay untuk menutup sidebar */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.2);
            z-index: 40;
            display: none;
        }

        #overlay.active {
            display: block;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col relative">

    <!-- Overlay Blur -->
    <div id="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar Menu -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-gray-800 text-white z-50 p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold">Lainnya</h2>
            <i data-lucide="x" class="cursor-pointer" onclick="toggleSidebar()"></i>
        </div>
        <ul class="space-y-6">
            <li class="hover:text-yellow-400 cursor-pointer flex items-center gap-3"><i data-lucide="message-square"></i> Chat</li>
            <li class="hover:text-yellow-400 cursor-pointer flex items-center gap-3"><i data-lucide="book-open"></i> Contoh</li>
            <li class="hover:text-yellow-400 cursor-pointer flex items-center gap-3"><i data-lucide="newspaper"></i> Berita</li>
        </ul>
    </div>

    <!-- Konten Utama yang akan di-blur -->
    <div id="content-wrapper" class="flex flex-col flex-grow">
        <nav class="container-fluid bg-gray-200 py-3 px-4 shadow-sm">
            <div class="row align-items-center">
                <div class="col-4">
                    <i data-lucide="menu" class="text-black cursor-pointer" onclick="toggleSidebar()"></i>
                </div>
                <div class="col-4 text-center">
                    <img src="assets/Logo.png" alt="RoKenAI Logo" class="h-20 mx-auto">
                </div>
                <div class="col-4 text-end">
                    <div class="inline-block bg-white p-2 rounded-full shadow-sm">
                        <i data-lucide="user" class="text-black"></i>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow flex items-center justify-center p-4">
            <div class="bg-yellow-400 w-full max-w-md rounded-3xl p-8 text-center shadow-lg">
                <h2 class="text-2xl md:text-3xl font-bold text-black italic">
                    RoKen Here<br>for help you
                </h2>
            </div>
        </main>

        <div class="fixed bottom-6 right-6">
            <button class="bg-gray-300 p-4 rounded-2xl shadow-xl hover:bg-gray-400 transition-colors">
                <i data-lucide="plus" class="text-black" size="32"></i>
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const content = document.getElementById('content-wrapper');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            content.classList.toggle('blurred');
        }
    </script>
</body>

</html>