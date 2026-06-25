<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen | Upload</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ===== Upload Page Layout ===== */
        .upload-page {
            max-width: 980px;
            margin: 0 auto;
            padding: 40px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        /* ===== Page Header ===== */
        .upload-page-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .upload-page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .upload-page-header p {
            font-size: 14px;
            color: var(--text-secondary);
            max-width: 480px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* ===== Dropzone ===== */
        .dropzone-wrapper {
            position: relative;
        }

        .drop-zone {
            position: relative;
            width: 100%;
            min-height: 360px;
            border-radius: 28px;
            background: var(--bg-light);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 2px dashed rgba(99, 102, 241, 0.25);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            cursor: pointer;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        /* ===== Neon Dash Border Animation (breathing) ===== */
        .drop-zone::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 28px;
            padding: 2px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.4), rgba(250, 204, 21, 0.15), rgba(99, 102, 241, 0.4));
            background-size: 200% 200%;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: borderBreathe 3s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes borderBreathe {
            0%, 100% { opacity: 0.4; background-position: 0% 50%; }
            50% { opacity: 0.9; background-position: 100% 50%; }
        }

        /* ===== Dropzone Hover / Dragover ===== */
        .drop-zone:hover {
            border-color: rgba(250, 204, 21, 0.3);
            background: var(--bg-subtle);
        }

        .drop-zone.dragover {
            border-color: rgba(250, 204, 21, 0.5);
            background: rgba(250, 204, 21, 0.06);
            transform: scale(1.015);
            box-shadow: 0 0 60px rgba(250, 204, 21, 0.08);
        }

        .drop-zone.dragover::before {
            opacity: 1;
            animation-duration: 1.5s;
        }

        /* ===== Page Dim ===== */
        body.dimmed .upload-page > *:not(.dropzone-wrapper) {
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }

        body.dimmed .drop-zone {
            transform: scale(1.02);
            border-color: rgba(250, 204, 21, 0.6);
            background: rgba(250, 204, 21, 0.08);
        }

        /* ===== Dropzone Icon ===== */
        .drop-icon {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: var(--brand-indigo);
            transition: all 0.3s ease;
        }

        .drop-zone.dragover .drop-icon {
            background: rgba(250, 204, 21, 0.12);
            border-color: rgba(250, 204, 21, 0.25);
            color: var(--brand-yellow);
            transform: translateY(-4px);
        }

        .drop-icon i {
            width: 36px;
            height: 36px;
            stroke-width: 1.5;
        }

        .drop-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .drop-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .drop-browse {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 14px;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .drop-browse:hover {
            background: rgba(250, 204, 21, 0.08);
            border-color: rgba(250, 204, 21, 0.2);
            color: var(--brand-yellow);
        }

        .drop-browse i {
            width: 16px;
            height: 16px;
        }

        .drop-hint {
            margin-top: 16px;
            font-size: 11px;
            color: var(--text-muted);
        }

        #fileInput {
            display: none;
        }

        /* ===== Supported Formats Bar ===== */
        .formats-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .format-tag {
            padding: 4px 12px;
            border-radius: 8px;
            background: var(--bg-input);
            border: 1px solid var(--border-muted);
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            font-family: 'SF Mono', 'JetBrains Mono', monospace;
        }

        /* ===== Preview Section ===== */
        #previewWrap {
            animation: fadeInUp 0.4s ease;
        }

        .preview-card {
            background: var(--bg-subtle);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 20px;
            margin-top: 24px;
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .preview-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .preview-header .file-size {
            font-size: 12px;
            color: var(--text-muted);
            font-family: 'SF Mono', 'JetBrains Mono', monospace;
        }

        .preview-img-wrap {
            border-radius: 16px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.3);
            max-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
        }

        .preview-actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .preview-actions button {
            flex: 1;
            padding: 14px 20px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-clear {
            background: var(--bg-input);
            border: 1px solid var(--border-color) !important;
            color: var(--text-secondary);
        }

        .btn-clear:hover {
            background: rgba(239, 68, 68, 0.08);
            border-color: rgba(239, 68, 68, 0.2) !important;
            color: #EF4444;
        }

        .btn-analyze {
            background: linear-gradient(135deg, #FACC15, #EAB308);
            color: #0B0F19;
            box-shadow: 0 0 25px rgba(250, 204, 21, 0.12);
        }

        .btn-analyze:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 35px rgba(250, 204, 21, 0.25);
        }

        .btn-analyze:active {
            transform: translateY(0);
        }

        .btn-analyze i, .btn-clear i {
            width: 18px;
            height: 18px;
        }
    </style>
</head>

<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="upload-page">

            <!-- Header -->
            <div class="upload-page-header">
                <h1 data-i18n="upload.title">Upload Gambar Jalan</h1>
                <p data-i18n="upload.desc">Unggah foto dokumentasi jalan raya untuk analisis kerusakan menggunakan model AI YOLOv8.</p>
            </div>

            <!-- Dropzone -->
            <div class="dropzone-wrapper">
                <div class="drop-zone" id="dropZone">
                    <div class="drop-icon">
                        <i data-lucide="cloud-upload"></i>
                    </div>
                    <div class="drop-title" data-i18n="upload.dropTitle">Drag & drop your image here</div>
                    <div class="drop-subtitle" data-i18n="upload.dropSubtitle">Supported formats: JPG, PNG, WEBP • Max 10MB</div>
                    <button class="drop-browse" id="browseBtn">
                        <i data-lucide="folder-open"></i>
                        <span data-i18n="upload.browse">Browse Files</span>
                    </button>
                    <div class="drop-hint" data-i18n="upload.orClick">or click anywhere in this area</div>
                </div>
                <input type="file" id="fileInput" accept="image/*">

                <!-- Formats bar -->
                <div class="formats-bar">
                    <span class="format-tag">JPG</span>
                    <span class="format-tag">PNG</span>
                    <span class="format-tag">WEBP</span>
                    <span style="color:var(--text-muted);font-size:11px;">•</span>
                    <span class="format-tag">Max 10MB</span>
                </div>
            </div>

            <!-- Preview -->
            <div id="previewWrap" style="display:none;">
                <div class="preview-card">
                    <div class="preview-header">
                        <h3 data-i18n="upload.previewTitle">Image Preview</h3>
                        <span class="file-size" id="fileSize">0 KB</span>
                    </div>
                    <div class="preview-img-wrap">
                        <img class="preview-img" id="previewImg" src="" alt="Preview">
                    </div>
                    <div class="preview-actions">
                        <button class="btn-clear" id="clearBtn">
                            <i data-lucide="trash-2"></i> <span data-i18n="upload.delete">Hapus</span>
                        </button>
                        <button class="btn-analyze" id="uploadBtn">
                            <i data-lucide="sparkles"></i> <span data-i18n="upload.analyze">Analisa Gambar</span>
                        </button>
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
        const fileSize = document.getElementById('fileSize');
        const clearBtn = document.getElementById('clearBtn');
        const body = document.body;

        browseBtn.addEventListener('click', e => {
            e.stopPropagation();
            fileInput.click();
        });

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', e => {
            if (e.target.files[0]) showPreview(e.target.files[0]);
        });

        // ===== Ultra-Responsive Drag Interactions =====
        dropZone.addEventListener('dragenter', e => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('dragover');
            body.classList.add('dimmed');
        });

        dropZone.addEventListener('dragover', e => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('dragover');
            body.classList.add('dimmed');
        });

        dropZone.addEventListener('dragleave', e => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('dragover');
            body.classList.remove('dimmed');
        });

        // Also listen on document to catch dragleave outside
        document.addEventListener('dragenter', e => {
            // Only dim when dragging over the main area
        });

        document.addEventListener('dragover', e => {
            e.preventDefault();
        });

        document.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            body.classList.remove('dimmed');
            if (e.dataTransfer.files[0]) {
                const file = e.dataTransfer.files[0];
                if (file.type.startsWith('image/')) {
                    showPreview(file);
                }
            }
        });

        document.addEventListener('dragend', () => {
            dropZone.classList.remove('dragover');
            body.classList.remove('dimmed');
        });

        // Prevent browser from opening dropped files
        window.addEventListener('dragover', e => e.preventDefault());
        window.addEventListener('drop', e => e.preventDefault());

        function showPreview(file) {
            if (file.size > 10 * 1024 * 1024) {
                alert('File too large. Maximum size is 10MB.');
                return;
            }
            const sizeKB = (file.size / 1024).toFixed(1);
            fileSize.textContent = sizeKB + ' KB';

            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewWrap.style.display = 'block';
                previewWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
