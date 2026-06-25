<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Tanya AI</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ================================================================
           RoKenAI — Halaman Tanya AI (desain.md 5.4)
           Chat klasik dengan bubble AI biru muda & bubble user biru tua
           ================================================================ */

        .chat-layout {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: calc(100vh - 80px);
            gap: 16px;
            animation: fadeInUp 0.4s ease;
        }

        /* ===== Sidebar (Left) — Riwayat Percakapan ===== */
        .chat-sidebar {
            width: 260px;
            min-width: 260px;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line-200);
            display: flex;
            flex-direction: column;
            padding: 16px;
            gap: 12px;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sidebar-header h3 {
            font-size: 12px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-family: var(--font-heading);
        }

        .new-chat-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            background: var(--primary-100);
            border: 1px solid rgba(29, 78, 216, 0.15);
            color: var(--primary-700);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: var(--font-body);
            width: 100%;
        }
        .new-chat-btn:hover {
            background: rgba(29, 78, 216, 0.12);
        }
        .new-chat-btn i { width: 16px; height: 16px; }

        .chat-history-list {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .history-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .history-item:hover {
            background: var(--surface-muted);
        }
        .history-item.active {
            background: var(--primary-100);
        }
        .history-item .h-icon {
            width: 14px; height: 14px;
            color: #94A3B8;
            flex-shrink: 0;
        }
        .history-item .h-info { flex: 1; min-width: 0; }
        .history-item .h-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink-900);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .history-item .h-meta {
            font-size: 11px;
            color: #94A3B8;
        }

        /* ===== Main Chat Area ===== */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* ===== Chat Header ===== */
        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0 12px;
            border-bottom: 1px solid var(--line-200);
            flex-shrink: 0;
        }
        .chat-header .chat-model {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-600);
        }
        .chat-header .chat-model i {
            width: 18px; height: 18px;
            color: var(--primary-500);
        }

        /* ===== Messages Area ===== */
        .chat-msgs {
            flex: 1;
            overflow-y: auto;
            padding: 24px 0 12px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ===== Welcome ===== */
        .welcome-msg {
            text-align: center;
            padding: 40px 20px 24px;
        }
        .welcome-msg .welcome-icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: var(--primary-100);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--primary-700);
        }
        .welcome-msg .welcome-icon i { width: 28px; height: 28px; }
        .welcome-msg h2 {
            font-family: var(--font-heading);
            font-size: 20px;
            font-weight: 700;
            color: var(--ink-900);
            margin-bottom: 8px;
        }
        .welcome-msg p {
            font-size: 14px;
            color: var(--ink-600);
            max-width: 440px;
            margin: 0 auto 24px;
            line-height: 1.6;
        }

        .welcome-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            max-width: 520px;
            margin: 0 auto;
        }
        .welcome-chips .chip {
            padding: 8px 18px;
            border-radius: var(--radius-full);
            background: var(--surface);
            border: 1px solid var(--line-200);
            font-size: 13px;
            color: var(--ink-600);
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: var(--font-body);
        }
        .welcome-chips .chip:hover {
            border-color: var(--primary-500);
            background: var(--primary-100);
            color: var(--primary-700);
        }

        /* ===== Chat Bubbles (sesuai desain.md) ===== */
        .msg-row {
            display: flex;
            gap: 10px;
            max-width: 85%;
            animation: fadeInUp 0.3s ease;
        }
        .msg-row.bot { align-self: flex-start; }
        .msg-row.user { align-self: flex-end; flex-direction: row-reverse; }

        /* Avatar AI */
        .msg-row .msg-avatar {
            width: 32px; height: 32px; min-width: 32px;
            border-radius: 50%;
            background: var(--primary-100);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--primary-700);
        }
        .msg-row .msg-avatar i { width: 16px; height: 16px; }

        /* Bubble AI: background biru muda (#DBEAFE), teks gelap */
        .msg-row.bot .msg-bubble {
            background: var(--primary-100); /* #DBEAFE */
            color: var(--ink-900);
            border-radius: 12px 12px 12px 4px;
            padding: 12px 16px;
            font-size: 14px;
            line-height: 1.7;
        }
        /* Bubble User: background biru utama (#1D4ED8), teks putih */
        .msg-row.user .msg-bubble {
            background: var(--primary-700); /* #1D4ED8 */
            color: #fff;
            border-radius: 12px 12px 4px 12px;
            padding: 12px 16px;
            font-size: 14px;
            line-height: 1.7;
        }

        .bubble-actions {
            display: flex;
            gap: 4px;
            margin-top: 8px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .msg-row:hover .bubble-actions { opacity: 1; }
        .bubble-actions button {
            width: 28px; height: 28px;
            border-radius: 6px;
            border: none;
            background: rgba(255,255,255,0.5);
            color: #94A3B8;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }
        .bubble-actions button:hover {
            background: rgba(29, 78, 216, 0.1);
            color: var(--primary-700);
        }
        .bubble-actions button i { width: 14px; height: 14px; }

        /* Thinking Animation */
        .thinking-dots {
            display: flex;
            gap: 4px;
            padding: 4px 0;
        }
        .thinking-dots .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--primary-500);
            animation: typing 1.4s infinite ease-in-out;
        }
        .thinking-dots .dot:nth-child(2) { animation-delay: 0.2s; }
        .thinking-dots .dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 80%, 100% { opacity: 0.2; transform: scale(0.8); }
            40% { opacity: 1; transform: scale(1); }
        }

        /* ===== Input Area ===== */
        .chat-input-area {
            padding: 12px 0 16px;
            flex-shrink: 0;
        }
        .chat-input-area .input-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1.5px solid var(--line-200);
            padding: 4px 4px 4px 16px;
            transition: all 0.2s ease;
        }
        .chat-input-area .input-wrap:focus-within {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .chat-input-area .input-wrap input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            color: var(--ink-900);
            background: transparent;
            padding: 10px 0;
            font-family: var(--font-body);
        }
        .chat-input-area .input-wrap input::placeholder { color: #94A3B8; }

        .chat-input-area .input-wrap .send-btn {
            width: 42px; height: 42px; min-width: 42px;
            border-radius: var(--radius-sm);
            border: none;
            background: var(--primary-700);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #fff;
        }
        .chat-input-area .input-wrap .send-btn:hover {
            background: var(--primary-500);
        }
        .chat-input-area .input-wrap .send-btn i { width: 18px; height: 18px; }

        .chat-footer-hint {
            font-size: 11px;
            color: #94A3B8;
            text-align: center;
            padding: 6px 0;
            border-top: 1px solid var(--line-200);
            margin-top: 8px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .chat-sidebar { display: none; }
            .chat-layout { padding: 0 16px; }
            .msg-row { max-width: 95%; }
        }
        @media (max-width: 480px) {
            .chat-layout { padding: 0 12px; }
            .welcome-msg { padding: 24px 12px 16px; }
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
                    Percakapan Baru
                </button>

                <div class="sidebar-header">
                    <h3>Hari Ini</h3>
                </div>
                <div class="chat-history-list">
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

                <div class="sidebar-header" style="margin-top:8px;">
                    <h3>Kemarin</h3>
                </div>
                <div class="chat-history-list">
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
                        <span>RoKenAI — Asisten Deteksi Jalan</span>
                    </div>
                </div>

                <!-- Messages -->
                <div class="chat-msgs" id="chatArea">
                    <!-- Welcome Message -->
                    <div class="welcome-msg" id="welcomeMsg">
                        <div class="welcome-icon">
                            <i data-lucide="bot"></i>
                        </div>
                        <h2>Selamat datang di RoKenAI</h2>
                        <p>Asisten AI untuk deteksi dan analisis kerusakan jalan. Tanya apa pun tentang pelaporan, jenis kerusakan, atau cara menggunakan platform.</p>
                        <div class="welcome-chips">
                            <span class="chip" onclick="quickSend('Bagaimana cara melaporkan kerusakan jalan?')">📷 Cara melaporkan</span>
                            <span class="chip" onclick="quickSend('Cek status laporan saya')">📋 Status laporan saya</span>
                            <span class="chip" onclick="quickSend('Jenis kerusakan apa yang bisa dideteksi?')">🔍 Jenis kerusakan</span>
                            <span class="chip" onclick="quickSend('Berapa lama proses perbaikan?')">⏱ Waktu perbaikan</span>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="chat-input-area">
                    <div class="input-wrap">
                        <input type="text" id="chatInput" placeholder="Tanya RoKenAI..." autocomplete="off">
                        <button class="send-btn" id="sendBtn" aria-label="Kirim">
                            <i data-lucide="arrow-up"></i>
                        </button>
                    </div>
                    <div class="chat-footer-hint">AI dapat melakukan kesalahan. Periksa informasi penting.</div>
                </div>

            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        // ================================================================
        // LOGIKA CHAT
        // Penjelasan: Fungsi addMessage untuk menambahkan bubble chat,
        // baik dari user maupun bot AI. Bubble AI pakai background biru
        // muda (#DBEAFE), bubble user pakai background biru tua (#1D4ED8).
        // ================================================================

        const chatArea = document.getElementById('chatArea');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const welcomeHTML = document.getElementById('welcomeMsg')?.outerHTML || '';

        // Fungsi menambahkan pesan ke area chat
        function addMessage(text, isUser) {
            // Sembunyikan welcome message jika user mengirim pesan
            const welcome = document.getElementById('welcomeMsg');
            if (welcome && isUser) welcome.style.display = 'none';

            const row = document.createElement('div');
            row.className = 'msg-row ' + (isUser ? 'user' : 'bot');

            if (isUser) {
                // Bubble user: biru tua (#1D4ED8), teks putih
                row.innerHTML = '<div class="msg-bubble">' + escapeHtml(text) + '</div>';
            } else {
                // Bubble AI: biru muda (#DBEAFE), teks gelap, ada avatar robot
                row.innerHTML = '<div class="msg-avatar"><i data-lucide="bot"></i></div>' +
                    '<div class="msg-bubble">' + escapeHtml(text) +
                    '<div class="bubble-actions">' +
                    '<button onclick="copyText(this)" title="Salin"><i data-lucide="copy"></i></button>' +
                    '</div></div>';
            }
            chatArea.appendChild(row);
            chatArea.scrollTop = chatArea.scrollHeight;
            lucide.createIcons();
        }

        // Escape HTML untuk keamanan (mencegah XSS)
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Fungsi copy teks bubble
        function copyText(btn) {
            const bubble = btn.closest('.msg-bubble');
            const text = bubble.childNodes[0].textContent.trim();
            navigator.clipboard.writeText(text).then(function() {
                btn.innerHTML = '<i data-lucide="check" style="width:14px;height:14px;"></i>';
                setTimeout(function() {
                    btn.innerHTML = '<i data-lucide="copy" style="width:14px;height:14px;"></i>';
                    lucide.createIcons();
                }, 1500);
            });
        }

        // Animasi "sedang mengetik"
        function addThinking() {
            const div = document.createElement('div');
            div.className = 'msg-row bot';
            div.id = 'thinkingMsg';
            div.innerHTML = '<div class="msg-avatar"><i data-lucide="bot"></i></div>' +
                '<div class="msg-bubble"><div class="thinking-dots"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div></div>';
            chatArea.appendChild(div);
            chatArea.scrollTop = chatArea.scrollHeight;
        }
        function removeThinking() {
            const el = document.getElementById('thinkingMsg');
            if (el) el.remove();
        }

        // Kirim pesan
        function sendMessage() {
            const text = chatInput.value.trim();
            if (!text) return;

            addMessage(text, true);
            chatInput.value = '';

            // Sembunyikan chips
            const chips = document.querySelector('.welcome-chips');
            if (chips) chips.style.display = 'none';

            // Tampilkan animasi typing, lalu balas
            addThinking();
            setTimeout(function() {
                removeThinking();
                const reply = 'Terima kasih atas pertanyaannya. Saya akan membantu Anda menjawab: "' +
                    text + '"\n\nUntuk informasi lebih lanjut, silakan cek menu Panduan atau buat laporan baru melalui menu Lapor Kerusakan.';
                addMessage(reply, false);
            }, 1200);
        }

        // Quick reply chip
        function quickSend(text) {
            chatInput.value = text;
            sendMessage();
        }

        // Hapus chat / mulai baru
        function clearChat() {
            chatArea.innerHTML = welcomeHTML;
            const chips = document.querySelector('.welcome-chips');
            if (chips) chips.style.display = 'flex';
            lucide.createIcons();
        }

        // Event listener
        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
    </script>
</body>
</html>
