<?php session_name('RoKenAI'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen AI | Home</title>
    <?php include 'partials/link.php'; ?>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column flex-grow-1">
        <main class="flex-grow-1 d-flex align-items-center justify-content-center p-3 p-md-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                        <div class="bg-warning rounded-4 p-4 p-md-5 text-center shadow">
                            <h2 class="fw-bold fst-italic fs-3 fs-md-2 text-dark mb-0">
                                RoKen Here<br>for help you
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- FAB Upload -->
        <div class="position-fixed bottom-0 end-0 p-4" style="z-index:30;">
            <button onclick="window.location.href='upload.php'"
                class="btn btn-secondary rounded-3 shadow-lg p-3"
                style="background:#d1d5db;border:none;"
                aria-label="Upload">
                <i data-lucide="plus" style="width:28px;height:28px;color:#1f2937;"></i>
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>