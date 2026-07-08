<?php session_name('RoKenAI'); session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | Tanya AI</title>
    <?php include 'partials/link.php'; ?>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="flex max-w-[1200px] mx-auto px-6 h-[calc(100vh-80px)] gap-4 animate-fade-in-up">

            <!-- Sidebar -->
            <aside class="w-[260px] min-w-[260px] bg-white rounded-xl border border-[#E2E8F0] flex flex-col p-4 gap-3 overflow-y-auto hidden lg:flex">
                <button class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-primary-light border border-[rgba(29,78,216,0.15)] text-[#1D4ED8] text-[13px] font-semibold cursor-pointer transition-all duration-200 hover:bg-[rgba(29,78,216,0.12)] w-full" onclick="clearChat()">
                    <i data-lucide="plus" style="width:16px;height:16px;"></i>
                    <span data-i18n="chat.newChat">Percakapan Baru</span>
                </button>
                <div class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[0.05em] font-heading mt-1" data-i18n="chat.today">Hari Ini</div>
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-150 hover:bg-[#F8FAFC] active">
                        <i data-lucide="message-square" style="width:14px;height:14px;color:#94A3B8;flex-shrink:0;"></i>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium text-[#0F172A] truncate">Analisis Jalan Ahmad Yani</div>
                            <div class="text-[11px] text-[#94A3B8]">2 jam lalu</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-150 hover:bg-[#F8FAFC]">
                        <i data-lucide="message-square" style="width:14px;height:14px;color:#94A3B8;flex-shrink:0;"></i>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium text-[#0F172A] truncate">Deteksi Lubang Jl. Diponegoro</div>
                            <div class="text-[11px] text-[#94A3B8]">5 jam lalu</div>
                        </div>
                    </div>
                </div>
                <div class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[0.05em] font-heading mt-2" data-i18n="chat.yesterday">Kemarin</div>
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-150 hover:bg-[#F8FAFC]">
                        <i data-lucide="message-square" style="width:14px;height:14px;color:#94A3B8;flex-shrink:0;"></i>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium text-[#0F172A] truncate">Cara melaporkan kerusakan</div>
                            <div class="text-[11px] text-[#94A3B8]">1 hari lalu</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-150 hover:bg-[#F8FAFC]">
                        <i data-lucide="message-square" style="width:14px;height:14px;color:#94A3B8;flex-shrink:0;"></i>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium text-[#0F172A] truncate">Jenis kerusakan yang dideteksi</div>
                            <div class="text-[11px] text-[#94A3B8]">1 hari lalu</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Chat Header -->
                <div class="flex items-center justify-between py-2 pb-3 border-b border-[#E2E8F0] shrink-0">
                    <div class="flex items-center gap-2 text-[13px] font-semibold text-[#475569]">
                        <i data-lucide="sparkles" style="width:18px;height:18px;color:#3B82F6;"></i>
                        <span data-i18n="chat.modelName">RoKenAI — Asisten Deteksi Jalan</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[12px] text-[#94A3B8]">
                        <span class="status-dot"></span> Online
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto py-5 px-0 flex flex-col gap-4" id="chatArea">
                    <div class="text-center py-8 px-5 flex flex-col items-center" id="welcomeMsg">
                        <div class="w-[160px] h-[160px] rounded-xl overflow-hidden border border-[#E2E8F0] shadow-lg mx-auto mb-5 shrink-0">
                            <img src="assets/Logo.png" alt="RoKenAI Chat" class="w-full h-full object-cover">
                        </div>
                        <h2 class="font-heading text-[20px] font-bold text-[#0F172A] mb-2" data-i18n="chat.welcomeTitle">Selamat datang di RoKenAI</h2>
                        <p class="text-[14px] text-[#475569] max-w-[440px] mx-auto mb-5.5 leading-relaxed" data-i18n="chat.welcomeDesc">Asisten AI untuk deteksi dan analisis kerusakan jalan.</p>
                        <div class="flex flex-wrap gap-2 justify-center max-w-[520px] mx-auto">
                            <span class="chip" data-i18n="chat.chip1" onclick="quickSend(this.textContent.trim())">📷 Cara melaporkan</span>
                            <span class="chip" data-i18n="chat.chip2" onclick="quickSend(this.textContent.trim())">📋 Status laporan saya</span>
                            <span class="chip" data-i18n="chat.chip3" onclick="quickSend(this.textContent.trim())">🔍 Jenis kerusakan</span>
                            <span class="chip" data-i18n="chat.chip4" onclick="quickSend(this.textContent.trim())">⏱ Waktu perbaikan</span>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="py-3 pb-4 shrink-0">
                    <div class="flex items-center gap-2 bg-white rounded-xl border border-[#E2E8F0] p-1 pl-4 transition-all duration-200 focus-within:border-[#3B82F6] focus-within:ring-[3px] focus-within:ring-[rgba(59,130,246,0.1)]">
                        <input type="file" id="fileInput" accept="image/*" class="hidden">
                        <button class="upload-btn-chat" id="uploadBtn" aria-label="Tambah gambar">
                            <i data-lucide="plus" style="width:20px;height:20px;"></i>
                        </button>
                        <input type="text" id="chatInput" data-i18n-placeholder="chat.placeholder" placeholder="Tanya RoKenAI..." autocomplete="off" class="flex-1 border-none outline-none text-[14px] text-[#0F172A] bg-transparent py-2.5">
                        <button class="w-[42px] h-[42px] min-w-[42px] rounded-lg border-none bg-[#1D4ED8] flex items-center justify-center cursor-pointer transition-all duration-200 hover:bg-[#3B82F6] text-white" id="sendBtn" aria-label="Kirim">
                            <i data-lucide="arrow-up" style="width:18px;height:18px;"></i>
                        </button>
                    </div>
                    <div class="text-[11px] text-[#94A3B8] text-center pt-1.5 border-t border-[#E2E8F0] mt-2" data-i18n="chat.hint">AI dapat melakukan kesalahan. Periksa informasi penting.</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chip {
            padding: 8px 18px; border-radius: 9999px;
            background: #fff; border: 1px solid #E2E8F0;
            font-size: 13px; color: #475569; cursor: pointer;
            transition: all 0.2s ease;
        }
        .chip:hover { border-color: #3B82F6; background: #DBEAFE; color: #1D4ED8; }

        .msg-row { display: flex; gap: 10px; max-width: 85%; animation: fadeInUp 0.3s ease; }
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

        .bubble-actions { display: flex; gap: 4px; margin-top: 8px; opacity: 0; transition: opacity 0.2s ease; }
        .msg-row:hover .bubble-actions { opacity: 1; }
        .bubble-actions button {
            width: 28px; height: 28px; border-radius: 6px; border: none;
            background: rgba(255,255,255,0.5); color: #94A3B8;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.15s ease;
        }
        .bubble-actions button:hover { background: rgba(29,78,216,0.1); color: #1D4ED8; }
        .bubble-actions button i { width: 14px; height: 14px; }

        .thinking-dots { display: flex; gap: 4px; padding: 4px 0; }
        .thinking-dots .dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #3B82F6; animation: typing 1.4s infinite ease-in-out;
        }
        .thinking-dots .dot:nth-child(2) { animation-delay: 0.2s; }
        .thinking-dots .dot:nth-child(3) { animation-delay: 0.4s; }

        .upload-btn-chat {
            background: none; border: none; cursor: pointer;
            color: #94A3B8; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
            padding: 4px; border-radius: 6px; transition: all 0.2s ease;
        }
        .upload-btn-chat:hover { background: #F1F5F9; color: #1D4ED8; }

        @media (max-width: 768px) {
            .chat-sidebar { display: none; }
            .px-6 { padding-left: 16px; padding-right: 16px; }
            .msg-row { max-width: 95%; }
        }
        @media (max-width: 480px) {
            .px-6 { padding-left: 12px; padding-right: 12px; }
        }
    </style>

    <script>
        lucide.createIcons();
        const chatArea = document.getElementById('chatArea');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const fileInput = document.getElementById('fileInput');
        const uploadBtn = document.getElementById('uploadBtn');
        const welcomeHTML = document.getElementById('welcomeMsg')?.outerHTML || '';

        const conversationHistory = [];
        const MAX_HISTORY = 20;

        const TEMPLATES = [
            { keywords: ['cara melapor', 'bagaimana cara', 'lapor kerusakan', 'upload foto', 'cara lapor', 'langkah', 'step', 'report damage', 'how to'],
              response: '📋 <b>Cara Melaporkan Kerusakan Jalan</b><br><br>Langkah-langkahnya sangat mudah:<br><br>1️⃣ <b>Ambil Foto</b> — Foto jalan rusak dengan HP.<br>2️⃣ <b>Upload</b> — Buka halaman <b>Lapor Kerusakan</b>.<br>3️⃣ <b>AI Deteksi</b> — YOLOv8 otomatis mendeteksi jenis & tingkat keparahan.<br>4️⃣ <b>Kirim Laporan</b> — Tambahkan catatan jika perlu.<br>5️⃣ <b>Pantau</b> — Cek status perbaikan di <b>Riwayat</b>.<br><br>👉 Coba langsung di menu <a href="upload.php" style="color:#1D4ED8;font-weight:600;">Lapor Kerusakan</a>!' },
            { keywords: ['status', 'laporan saya', 'cek laporan', 'progress', 'riwayat', 'perbaikan', 'track', 'my report', 'history'],
              response: '📊 <b>Cek Status Laporan</b><br><br>Login ke akun RoKenAI, klik menu <b>Riwayat</b> di navbar. Di sana kamu bisa lihat status:<br><br>🟡 <b>Dilaporkan</b> — Laporan baru masuk<br>🔵 <b>Diverifikasi</b> — Sedang diperiksa admin<br>🟠 <b>Diperbaiki</b> — Sedang dalam perbaikan<br>🟢 <b>Selesai</b> — Sudah diperbaiki' },
            { keywords: ['jenis kerusakan', 'tipe kerusakan', 'pothole', 'lubang', 'crack', 'retak', 'rutting', 'bergelombang', 'damage type'],
              response: '🔍 <b>Jenis Kerusakan Jalan</b><br><br>🕳️ <b>Pothole (Lubang)</b> — Lubang pada permukaan jalan.<br>〰️ <b>Crack (Retak)</b> — Retak memanjang/melebar di aspal.<br>〰️ <b>Rutting (Bergelombang)</b> — Jalan bergelombang akibat beban.<br><br>Tingkat keparahan: 🟢 Ringan — 🔵 Sedang — 🔴 Parah' },
            { keywords: ['waktu', 'berapa lama', 'timeline', 'estimasi', 'selesai', 'proses', 'durasi', 'how long', 'when'],
              response: '⏱ <b>Waktu Perbaikan</b><br><br>📥 <b>Pelaporan</b> — 1-2 jam<br>🔍 <b>Verifikasi</b> — 1-2 hari<br>🛠️ <b>Perbaikan</b> — 3-7 hari<br>✅ <b>Selesai</b> — Konfirmasi<br><br>⏳ Total estimasi: <b>5-10 hari kerja</b>' },
            { keywords: ['rokenai', 'tentang', 'apa itu', 'fitur', 'platform', 'about', 'what is'],
              response: '🤖 <b>Tentang RoKenAI</b><br><br>Platform deteksi kerusakan jalan berbasis AI (YOLOv8).<br><br>📸 Deteksi otomatis<br>💬 Tanya AI<br>📊 Tracking real-time<br>🌐 Multi bahasa' },
            { keywords: ['akurasi', 'keakuratan', 'seberapa akurat', 'presisi', 'accuracy'],
              response: '📈 <b>Akurasi Deteksi</b><br><br>🎯 Rata-rata: <b>>85%</b><br>⚡ Kecepatan: <b><500ms per gambar</b><br><br>Faktor: pencahayaan baik, foto jelas, sudut tepat.' },
            { keywords: ['halo', 'hai', 'siang', 'pagi', 'malam', 'helo', 'hello', 'hi', 'selamat', 'hey', 'p', 'test', 'tes'],
              response: 'Halo! 👋 Ada yang bisa saya bantu?<br><br>📷 Cara melaporkan kerusakan<br>🔍 Jenis kerusakan<br>📊 Status laporan<br>⏱ Waktu perbaikan' },
        ];
        const FALLBACK_RESPONSE = 'Maaf, saya belum bisa menjawab pertanyaan tersebut. 😊<br><br>Berikut yang bisa saya bantu:<br>📷 Cara melaporkan<br>🔍 Jenis kerusakan<br>📊 Status laporan<br>⏱ Waktu perbaikan';

        function matchTemplate(text) {
            var lower = text.toLowerCase().trim();
            var bestMatch = null;
            var bestScore = 0;
            for (var i = 0; i < TEMPLATES.length; i++) {
                var tpl = TEMPLATES[i];
                var score = 0;
                for (var j = 0; j < tpl.keywords.length; j++) {
                    if (lower.indexOf(tpl.keywords[j]) !== -1) score += tpl.keywords[j].length;
                }
                if (score > bestScore) { bestScore = score; bestMatch = tpl; }
            }
            return bestMatch ? bestMatch.response : FALLBACK_RESPONSE;
        }

        function escapeHtml(text) { const d = document.createElement('div'); d.textContent = text; return d.innerHTML; }

        function addToHistory(role, text) {
            conversationHistory.push({ role: role, content: text });
            if (conversationHistory.length > MAX_HISTORY) conversationHistory.splice(0, 2);
        }

        function addMessage(text, isUser, isHtml) {
            const welcome = document.getElementById('welcomeMsg');
            if (welcome && isUser) welcome.style.display = 'none';
            const row = document.createElement('div');
            row.className = 'msg-row ' + (isUser ? 'user' : 'bot');
            if (isUser) row.innerHTML = '<div class="msg-bubble">' + escapeHtml(text) + '</div>';
            else if (isHtml) row.innerHTML = '<div class="msg-avatar"><i data-lucide="bot"></i></div><div class="msg-bubble bot-html">' + text + '<div class="bubble-actions"><button onclick="copyText(this)" title="Salin"><i data-lucide="copy"></i></button></div></div>';
            else row.innerHTML = '<div class="msg-avatar"><i data-lucide="bot"></i></div><div class="msg-bubble">' + escapeHtml(text).replace(/\n/g, '<br>') + '<div class="bubble-actions"><button onclick="copyText(this)" title="Salin"><i data-lucide="copy"></i></button></div></div>';
            chatArea.appendChild(row);
            chatArea.scrollTop = chatArea.scrollHeight;
            lucide.createIcons();
        }

        function copyText(btn) {
            const text = btn.closest('.msg-bubble').innerText.trim();
            navigator.clipboard.writeText(text).then(function () {
                btn.innerHTML = '<i data-lucide="check" style="width:14px;height:14px;"></i>';
                setTimeout(function () { btn.innerHTML = '<i data-lucide="copy" style="width:14px;height:14px;"></i>'; lucide.createIcons(); }, 1500);
            });
        }

        function addThinking() {
            const div = document.createElement('div');
            div.className = 'msg-row bot';
            div.id = 'thinkingMsg';
            div.innerHTML = '<div class="msg-avatar"><i data-lucide="bot"></i></div><div class="msg-bubble"><div class="thinking-dots"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div></div>';
            chatArea.appendChild(div);
            chatArea.scrollTop = chatArea.scrollHeight;
            lucide.createIcons();
        }

        function removeThinking() { const el = document.getElementById('thinkingMsg'); if (el) el.remove(); }

        function kirimKeLLM(text) {
            addMessage(text, true, false);
            chatInput.value = '';
            var reply = matchTemplate(text);
            addMessage(reply, false, true);
        }

        function kirimGambarKeYOLO(file) {
            addMessage('[Mengirim Gambar: ' + file.name + ']', true, false);
            var formData = new FormData();
            formData.append('gambar', file);
            chatInput.value = '';
            if (fileInput) fileInput.value = '';
            addThinking();
            fetch('controller/proses_chat.php', { method: 'POST', body: formData })
            .then(function (resp) { return resp.json(); })
            .then(function (data) {
                removeThinking();
                if (data.status === 'success') { addMessage(data.pesan, false, true); addToHistory('assistant', '[Deteksi Gambar] ' + data.pesan.replace(/<[^>]*>/g, '').trim()); }
                else { addMessage('Gagal memproses gambar: ' + (data.error || 'Terjadi kesalahan.'), false, false); }
            })
            .catch(function () { removeThinking(); addMessage('Terjadi kesalahan sistem.', false, false); });
        }

        function prosesKirim(file) {
            if (file) kirimGambarKeYOLO(file);
            else { var text = chatInput.value.trim(); if (!text) return; kirimKeLLM(text); }
        }

        if (uploadBtn) uploadBtn.addEventListener('click', function () { if (fileInput) fileInput.click(); });
        if (fileInput) fileInput.addEventListener('change', function () { if (fileInput.files.length > 0) prosesKirim(fileInput.files[0]); });
        sendBtn.addEventListener('click', function () { prosesKirim(); });
        chatInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') prosesKirim(); });

        function quickSend(text) { chatInput.value = text; prosesKirim(); }

        function clearChat() {
            chatArea.innerHTML = welcomeHTML;
            conversationHistory.length = 0;
            lucide.createIcons();
            if (typeof i18n !== 'undefined' && i18n.apply) i18n.apply();
        }
    </script>
</body>
</html>
