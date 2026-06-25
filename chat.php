<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Tanya AI</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Tanya AI (Chat)
           ================================================================ */

        .chat-layout {
            display: flex; max-width: 1200px; margin: 0 auto;
            padding: 0 24px; height: calc(100vh - 80px);
            gap: 16px; animation: fadeInUp 0.4s ease;
        }

        /* ===== Sidebar ===== */
        .chat-sidebar {
            width: 260px; min-width: 260px;
            background: #fff; border-radius: 12px;
            border: 1px solid #E2E8F0;
            display: flex; flex-direction: column;
            padding: 16px; gap: 12px; overflow-y: auto;
        }

        .new-chat-btn {
            display: flex; align-items: center; justify-content: center;
            gap: 8px; padding: 10px 16px; border-radius: 8px;
            background: #DBEAFE; border: 1px solid rgba(29,78,216,0.15);
            color: #1D4ED8; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
            font-family: var(--font-body); width: 100%;
        }
        .new-chat-btn:hover { background: rgba(29,78,216,0.12); }
        .new-chat-btn i { width: 16px; height: 16px; }

        .sidebar-section-label {
            font-size: 10px; font-weight: 700; color: #94A3B8;
            text-transform: uppercase; letter-spacing: 0.05em;
            font-family: var(--font-heading); margin-top: 4px;
        }

        .history-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            cursor: pointer; transition: all 0.15s ease;
        }
        .history-item:hover { background: #F8FAFC; }
        .history-item.active { background: #DBEAFE; }
        .history-item .h-icon { width: 14px; height: 14px; color: #94A3B8; flex-shrink: 0; }
        .history-item .h-info { flex: 1; min-width: 0; }
        .history-item .h-title {
            font-size: 13px; font-weight: 500; color: #0F172A;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .history-item .h-meta { font-size: 11px; color: #94A3B8; }

        /* ===== Main Chat Area ===== */
        .chat-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }

        .chat-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 0 12px; border-bottom: 1px solid #E2E8F0; flex-shrink: 0;
        }
        .chat-header .chat-model {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 600; color: #475569;
        }
        .chat-header .chat-model i { width: 18px; height: 18px; color: #3B82F6; }

        /* ===== Messages ===== */
        .chat-msgs {
            flex: 1; overflow-y: auto; padding: 20px 0 12px;
            display: flex; flex-direction: column; gap: 16px;
        }

        /* Welcome screen — menggunakan Chat.png */
        .welcome-msg {
            text-align: center; padding: 32px 20px 24px;
            display: flex; flex-direction: column; align-items: center;
        }
        .welcome-img-wrap {
            width: 160px; height: 160px; border-radius: 16px;
            overflow: hidden; border: 1px solid #E2E8F0;
            box-shadow: var(--shadow-lg); margin: 0 auto 20px;
            flex-shrink: 0;
        }
        .welcome-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .welcome-msg h2 {
            font-family: var(--font-heading); font-size: 20px; font-weight: 700;
            color: #0F172A; margin-bottom: 8px;
        }
        .welcome-msg p {
            font-size: 14px; color: #475569; max-width: 440px; margin: 0 auto 22px; line-height: 1.6;
        }

        .welcome-chips {
            display: flex; flex-wrap: wrap; gap: 8px;
            justify-content: center; max-width: 520px; margin: 0 auto;
        }
        .welcome-chips .chip {
            padding: 8px 18px; border-radius: 9999px;
            background: #fff; border: 1px solid #E2E8F0;
            font-size: 13px; color: #475569; cursor: pointer;
            transition: all 0.2s ease; font-family: var(--font-body);
        }
        .welcome-chips .chip:hover {
            border-color: #3B82F6; background: #DBEAFE; color: #1D4ED8;
        }

        /* ===== Chat Bubbles ===== */
        .msg-row {
            display: flex; gap: 10px; max-width: 85%;
            animation: fadeInUp 0.3s ease;
        }
        .msg-row.bot  { align-self: flex-start; }
        .msg-row.user { align-self: flex-end; flex-direction: row-reverse; }

        .msg-avatar {
            width: 32px; height: 32px; min-width: 32px; border-radius: 50%;
            background: #DBEAFE; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0; color: #1D4ED8;
        }
        .msg-avatar i { width: 16px; height: 16px; }

        .msg-row.bot .msg-bubble {
            background: #DBEAFE; color: #0F172A;
            border-radius: 12px 12px 12px 4px;
            padding: 12px 16px; font-size: 14px; line-height: 1.7;
        }
        .msg-row.user .msg-bubble {
            background: #1D4ED8; color: #fff;
            border-radius: 12px 12px 4px 12px;
            padding: 12px 16px; font-size: 14px; line-height: 1.7;
        }

        .bubble-actions {
            display: flex; gap: 4px; margin-top: 8px;
            opacity: 0; transition: opacity 0.2s ease;
        }
        .msg-row:hover .bubble-actions { opacity: 1; }
        .bubble-actions button {
            width: 28px; height: 28px; border-radius: 6px; border: none;
            background: rgba(255,255,255,0.5); color: #94A3B8;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.15s ease;
        }
        .bubble-actions button:hover { background: rgba(29,78,216,0.1); color: #1D4ED8; }
        .bubble-actions button i { width: 14px; height: 14px; }

        /* Thinking dots */
        .thinking-dots { display: flex; gap: 4px; padding: 4px 0; }
        .thinking-dots .dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #3B82F6; animation: typing 1.4s infinite ease-in-out;
        }
        .thinking-dots .dot:nth-child(2) { animation-delay: 0.2s; }
        .thinking-dots .dot:nth-child(3) { animation-delay: 0.4s; }

        /* ===== Input Area ===== */
        .chat-input-area { padding: 12px 0 16px; flex-shrink: 0; }
        .input-wrap {
            display: flex; align-items: center; gap: 8px;
            background: #fff; border-radius: 12px;
            border: 1.5px solid #E2E8F0;
            padding: 4px 4px 4px 16px; transition: all 0.2s ease;
        }
        .input-wrap:focus-within { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .input-wrap input {
            flex: 1; border: none; outline: none;
            font-size: 14px; color: #0F172A; background: transparent;
            padding: 10px 0; font-family: var(--font-body);
        }
        .input-wrap input::placeholder { color: #94A3B8; }
        .upload-btn {
            background: none; border: none; cursor: pointer;
            color: #94A3B8; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
            padding: 4px; border-radius: 6px;
            transition: all 0.2s ease;
        }
        .upload-btn:hover { background: #F1F5F9; color: #1D4ED8; }
        .upload-btn i { width: 20px; height: 20px; }

        .send-btn {
            width: 42px; height: 42px; min-width: 42px;
            border-radius: 8px; border: none; background: #1D4ED8;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease; color: #fff;
        }
        .send-btn:hover { background: #3B82F6; }
        .send-btn i { width: 18px; height: 18px; }

        .chat-footer-hint {
            font-size: 11px; color: #94A3B8; text-align: center;
            padding: 6px 0; border-top: 1px solid #E2E8F0; margin-top: 8px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .chat-sidebar { display: none; }
            .chat-layout { padding: 0 16px; }
            .msg-row { max-width: 95%; }
        }
        @media (max-width: 480px) {
            .chat-layout { padding: 0 12px; }
            .welcome-msg { padding: 20px 12px 16px; }
        }
    </style>
</head>
<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="chat-layout">

            <!-- ===== Sidebar Riwayat ===== -->
            <aside class="chat-sidebar">
                <button class="new-chat-btn" onclick="clearChat()">
                    <i data-lucide="plus"></i>
                    <span data-i18n="chat.newChat">Percakapan Baru</span>
                </button>

                <div class="sidebar-section-label" data-i18n="chat.today">Hari Ini</div>
                <div class="flex flex-col gap-0.5">
                    <div class="history-item active">
                        <i data-lucide="message-square" class="h-icon"></i>
                        <div class="h-info">
                            <div class="h-title">Analisis Jalan Ahmad Yani</div>
                            <div class="h-meta">2 jam lalu</div>
                        </div>
                    </div>
                    <div class="history-item">
                        <i data-lucide="message-square" class="h-icon"></i>
                        <div class="h-info">
                            <div class="h-title">Deteksi Lubang Jl. Diponegoro</div>
                            <div class="h-meta">5 jam lalu</div>
                        </div>
                    </div>
                </div>

                <div class="sidebar-section-label mt-2" data-i18n="chat.yesterday">Kemarin</div>
                <div class="flex flex-col gap-0.5">
                    <div class="history-item">
                        <i data-lucide="message-square" class="h-icon"></i>
                        <div class="h-info">
                            <div class="h-title">Cara melaporkan kerusakan</div>
                            <div class="h-meta">1 hari lalu</div>
                        </div>
                    </div>
                    <div class="history-item">
                        <i data-lucide="message-square" class="h-icon"></i>
                        <div class="h-info">
                            <div class="h-title">Jenis kerusakan yang dideteksi</div>
                            <div class="h-meta">1 hari lalu</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- ===== Main Chat ===== -->
            <div class="chat-main">

                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="chat-model">
                        <i data-lucide="sparkles"></i>
                        <span data-i18n="chat.modelName">RoKenAI — Asisten Deteksi Jalan</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[12px] text-[#94A3B8]">
                        <span class="status-dot"></span>
                        Online
                    </div>
                </div>

                <!-- Messages -->
                <div class="chat-msgs" id="chatArea">
                    <!-- Welcome — menggunakan Chat.png sebagai ilustrasi -->
                    <div class="welcome-msg" id="welcomeMsg">
                        <div class="welcome-img-wrap">
                            <img src="assets/img/Chat.png" alt="RoKenAI Chat">
                        </div>
                        <h2 data-i18n="chat.welcomeTitle">Selamat datang di RoKenAI</h2>
                        <p data-i18n="chat.welcomeDesc">Asisten AI untuk deteksi dan analisis kerusakan jalan. Tanya apa pun tentang pelaporan, jenis kerusakan, atau cara menggunakan platform.</p>
                        <div class="welcome-chips">
                            <span class="chip" id="chip1" data-i18n="chat.chip1" onclick="quickSend(this.textContent.trim())">📷 Cara melaporkan</span>
                            <span class="chip" id="chip2" data-i18n="chat.chip2" onclick="quickSend(this.textContent.trim())">📋 Status laporan saya</span>
                            <span class="chip" id="chip3" data-i18n="chat.chip3" onclick="quickSend(this.textContent.trim())">🔍 Jenis kerusakan</span>
                            <span class="chip" id="chip4" data-i18n="chat.chip4" onclick="quickSend(this.textContent.trim())">⏱ Waktu perbaikan</span>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="chat-input-area">
                    <div class="input-wrap">
                        <input type="file" id="fileInput" accept="image/*" style="display: none;">
                        <button class="upload-btn" id="uploadBtn" aria-label="Tambah gambar">
                            <i data-lucide="plus"></i>
                        </button>
                        <input type="text" id="chatInput" data-i18n-placeholder="chat.placeholder" placeholder="Tanya RoKenAI..." autocomplete="off">
                        <button class="send-btn" id="sendBtn" aria-label="Kirim">
                            <i data-lucide="arrow-up"></i>
                        </button>
                    </div>
                    <div class="chat-footer-hint" data-i18n="chat.hint">AI dapat melakukan kesalahan. Periksa informasi penting.</div>
                </div>

            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const chatArea    = document.getElementById('chatArea');
        const chatInput   = document.getElementById('chatInput');
        const sendBtn     = document.getElementById('sendBtn');
        const fileInput   = document.getElementById('fileInput');
        const uploadBtn   = document.getElementById('uploadBtn');
        const welcomeHTML = document.getElementById('welcomeMsg')?.outerHTML || '';

        function escapeHtml(text) {
            const d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        function addMessage(html, isUser) {
            const welcome = document.getElementById('welcomeMsg');
            if (welcome && isUser) welcome.style.display = 'none';

            const row = document.createElement('div');
            row.className = 'msg-row ' + (isUser ? 'user' : 'bot');

            if (isUser) {
                // Pesan user — selalu di-escape untuk keamanan
                row.innerHTML = '<div class="msg-bubble">' + escapeHtml(html) + '</div>';
            } else {
                // Pesan bot — HTML aman (dari backend), langsung di-render
                row.innerHTML =
                    '<div class="msg-avatar"><i data-lucide="bot"></i></div>' +
                    '<div class="msg-bubble bot-html">' + html +
                    '<div class="bubble-actions">' +
                    '<button onclick="copyText(this)" title="Salin"><i data-lucide="copy"></i></button>' +
                    '</div></div>';
            }
            chatArea.appendChild(row);
            chatArea.scrollTop = chatArea.scrollHeight;
            lucide.createIcons();
        }

        function copyText(btn) {
            const bubble = btn.closest('.msg-bubble');
            const text = bubble.innerText.trim();
            navigator.clipboard.writeText(text).then(function () {
                btn.innerHTML = '<i data-lucide="check" style="width:14px;height:14px;"></i>';
                setTimeout(function () {
                    btn.innerHTML = '<i data-lucide="copy" style="width:14px;height:14px;"></i>';
                    lucide.createIcons();
                }, 1500);
            });
        }

        function addThinking() {
            const div = document.createElement('div');
            div.className = 'msg-row bot';
            div.id = 'thinkingMsg';
            div.innerHTML =
                '<div class="msg-avatar"><i data-lucide="bot"></i></div>' +
                '<div class="msg-bubble"><div class="thinking-dots"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div></div>';
            chatArea.appendChild(div);
            chatArea.scrollTop = chatArea.scrollHeight;
            lucide.createIcons();
        }

        function removeThinking() {
            const el = document.getElementById('thinkingMsg');
            if (el) el.remove();
        }

        /**
         * Kirim pesan teks atau gambar ke backend proses_chat.php
         */
        function prosesKirim(file = null) {
            const text = chatInput.value.trim();
            if (!text && !file) return;

            let formData = new FormData();

            if (file) {
                // Tampilkan preview upload user
                addMessage('[Mengirim Gambar: ' + file.name + ']', true);
                formData.append('gambar', file);
            } else {
                addMessage(text, true);
                formData.append('pesan', text);
            }

            chatInput.value = '';
            if (fileInput) fileInput.value = '';

            // Tampilkan thinking dots
            addThinking();

            // Kirim ke backend via AJAX
            fetch('controller/proses_chat.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    removeThinking();
                    if (data.status === 'success') {
                        addMessage(data.pesan, false);
                    } else {
                        addMessage('Gagal memproses: ' + (data.error || 'Terjadi kesalahan.'), false);
                    }
                })
                .catch(err => {
                    removeThinking();
                    addMessage('Terjadi kesalahan sistem. Silakan coba lagi.', false);
                });
        }

        // Upload button: trigger hidden file input
        if (uploadBtn) {
            uploadBtn.addEventListener('click', function () {
                if (fileInput) fileInput.click();
            });
        }

        // File selected: langsung kirim gambar
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (fileInput.files.length > 0) {
                    prosesKirim(fileInput.files[0]);
                }
            });
        }

        // Send button & Enter key
        sendBtn.addEventListener('click', function () {
            prosesKirim();
        });
        chatInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') prosesKirim();
        });

        // Quick send dari welcome chips
        function quickSend(text) {
            chatInput.value = text;
            prosesKirim();
        }

        function clearChat() {
            chatArea.innerHTML = welcomeHTML;
            lucide.createIcons();
            if (typeof i18n !== 'undefined' && i18n.apply) {
                i18n.apply();
            }
        }
    </script>
</body>
</html>
