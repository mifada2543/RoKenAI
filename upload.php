<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen | Upload</title>
    <?php include 'partials/link.php'; ?>
    <style>
        body {
            background: #e5e7eb;
        }

        .drop-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 48px 24px;
            text-align: center;
            cursor: pointer;
            background: #f9fafb;
            transition: border-color 0.2s, background 0.2s;
        }

        .drop-zone:hover,
        .drop-zone.dragover {
            border-color: #fbbf24;
            background: #fffbeb;
        }

        .drop-zone .upload-icon {
            color: #374151;
            margin-bottom: 12px;
        }

        .drop-zone p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .drop-zone .browse {
            color: #1f2937;
            font-weight: 600;
            text-decoration: underline;
        }

        #fileInput {
            display: none;
        }

        .preview-img {
            width: 100%;
            border-radius: 10px;
            object-fit: cover;
            max-height: 280px;
        }

        @media (min-width: 768px) {
            .preview-img {
                max-height: 380px;
            }
        }

        .btn-hapus {
            border: 1.5px solid #d1d5db;
            background: #fff;
            color: #6b7280;
            border-radius: 10px;
            padding: 10px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-hapus:hover {
            background: #f3f4f6;
        }

        .btn-analisa {
            background: #1f2937;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-analisa:hover {
            background: #374151;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper" class="flex-grow-1 py-3 py-md-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="bg-white rounded-4 p-3 shadow-sm">

                        <!-- Drop Zone -->
                        <div class="drop-zone" id="dropZone">
                            <div class="upload-icon">
                                <i data-lucide="cloud-upload" style="width:48px;height:48px;"></i>
                            </div>
                            <p>Drag &amp; drop an image here or
                                <span class="browse" id="browseBtn">browse</span>
                            </p>
                        </div>
                        <input type="file" id="fileInput" accept="image/*">

                        <!-- Preview -->
                        <div id="previewWrap" class="mt-3" style="display:none;">
                            <img class="preview-img" id="previewImg" src="" alt="Preview">
                            <div class="d-flex gap-2 mt-3">
                                <button class="btn-hapus flex-fill" id="clearBtn">Hapus</button>
                                <button class="btn-analisa flex-fill" id="uploadBtn">Analisa</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const browseBtn = document.getElementById('browseBtn');
        const previewWrap = document.getElementById('previewWrap');
        const previewImg = document.getElementById('previewImg');
        const clearBtn = document.getElementById('clearBtn');

        browseBtn.addEventListener('click', e => {
            e.stopPropagation();
            fileInput.click();
        });
        dropZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', e => {
            if (e.target.files[0]) showPreview(e.target.files[0]);
        });

        dropZone.addEventListener('dragover', e => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) showPreview(e.dataTransfer.files[0]);
        });

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewWrap.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        clearBtn.addEventListener('click', () => {
            previewWrap.style.display = 'none';
            previewImg.src = '';
            fileInput.value = '';
        });
    </script>
</body>

</html>