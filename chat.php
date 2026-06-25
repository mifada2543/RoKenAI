<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen | AI Chat</title>
    <?php include 'partials/link.php'; ?>
    <style>
        /* ===== Workspace Layout ===== */
        .chat-workspace {
            display: flex;
            height: calc(100vh - 68px);
            max-width: 1440px;
            margin: 0 auto;
            padding: 16px;
            gap: 16px;
        }

        /* ===== Left Panel: Chat History ===== */
        .chat-history-panel {
            width: 300px;
            min-width: 280px;
            background: var(--bg-subtle);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: fadeInUp 0.4s ease;
        }

        @media (max-width: 768px) {
            .chat-history-panel {
                display: none;
            }
        }

        .history-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 0 4px;
        }

        .history-header h3 {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .history-new-btn {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s ease;
        }

        .history-new-btn:hover {
            background: rgba(250, 204, 21, 0.1);
            border-color: rgba(250, 204, 21, 0.2);
            color: var(--brand-yellow);
        }

        .history-new-btn i {
            width: 16px;
            height: 16px;
        }

        .history-search {
            padding: 10px 14px;
            border-radius: 12px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            font-size: 13px;
            outline: none;
            width: 100%;
            margin-bottom: 16px;
            transition: all 0.2s ease;
        }

        .history-search::placeholder {
            color: var(--text-muted);
        }

        .history-search:focus {
            border-color: rgba(250, 204, 21, 0.3);
            background: var(--bg-hover);
        }

        .history-groups {
            flex: 1;
            overflow-y: auto;
            margin: 0 -4px;
            padding: 0 4px;
        }

        .history-group-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 8px 12px 6px;
        }

        .history-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .history-item:hover {
            background: var(--bg-hover);
        }

        .history-item.active {
            background: var(--bg-active);
            border: 1px solid rgba(250, 204, 21, 0.1);
        }

        .history-item-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(99, 102, 241, 0.1);
            color: var(--brand-indigo);
        }

        .history-item-icon i {
            width: 16px;
            height: 16px;
        }

        .history-item-content {
            flex: 1;
            min-width: 0;
        }

        .history-item-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-item-meta {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ===== Right Panel: Chat Area ===== */
        .chat-main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0 8px;
            min-width: 0;
            animation: fadeInUp 0.4s ease;
            animation-delay: 0.1s;
            animation-fill-mode: both;
        }

        /* ===== Chat Header ===== */
        .chat-main-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 4px 16px;
            border-bottom: 1px solid var(--border-muted);
        }

        .chat-model-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.12);
            font-size: 12px;
            font-weight: 500;
            color: var(--brand-indigo);
        }

        .chat-model-badge i {
            width: 14px;
            height: 14px;
        }

        .chat-actions {
            margin-left: auto;
            display: flex;
            gap: 8px;
        }

        .chat-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s ease;
        }

        .chat-action-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .chat-action-btn i {
            width: 18px;
            height: 18px;
        }

        /* ===== Chat Messages Area ===== */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px 4px 12px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ===== Welcome Message ===== */
        .welcome-message {
            text-align: center;
            padding: 40px 20px 20px;
        }

        .welcome-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(250, 204, 21, 0.15), rgba(99, 102, 241, 0.15));
            border: 2px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            padding: 12px;
        }

        .welcome-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .welcome-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .welcome-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            max-width: 400px;
            margin: 0 auto 24px;
            line-height: 1.6;
        }

        .suggestion-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            max-width: 480px;
            margin: 0 auto;
        }

        .suggestion-chip {
            padding: 8px 16px;
            border-radius: 100px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            font-size: 12px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .suggestion-chip:hover {
            background: rgba(250, 204, 21, 0.08);
            border-color: rgba(250, 204, 21, 0.2);
            color: var(--brand-yellow);
        }

        /* ===== Chat Bubbles ===== */
        .bot-message {
            display: flex;
            gap: 12px;
            animation: fadeInUp 0.3s ease;
            max-width: 85%;
        }

        .bot-avatar {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(250, 204, 21, 0.15), rgba(99, 102, 241, 0.15));
            border: 2px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
        }

        .bot-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .bot-bubble {
            background: var(--bg-bubble);
            backdrop-filter: blur(8px);
            border: 1px solid var(--border-subtle);
            border-radius: 18px 18px 18px 4px;
            padding: 14px 18px;
            font-size: 14px;
            color: var(--text-primary);
            line-height: 1.7;
            box-shadow: var(--shadow-sm);
        }

        .user-message {
            display: flex;
            justify-content: flex-end;
            animation: fadeInUp 0.3s ease;
        }

        .user-bubble {
            background: linear-gradient(135deg, rgba(250, 204, 21, 0.12), rgba(99, 102, 241, 0.08));
            border: 1px solid rgba(250, 204, 21, 0.1);
            border-radius: 18px 18px 4px 18px;
            padding: 14px 18px;
            font-size: 14px;
            color: var(--text-primary);
            max-width: min(75%, 480px);
            line-height: 1.7;
        }

        /* ===== Thinking indicator ===== */
        .thinking-indicator {
            display: flex;
            gap: 4px;
            padding: 4px 0;
        }

        .thinking-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--text-muted);
            animation: typing 1.4s infinite ease-in-out;
        }

        .thinking-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        .thinking-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 80%, 100% { opacity: 0.3; transform: scale(0.8); }
            40% { opacity: 1; transform: scale(1); }
        }

        /* ===== Input Area (Frosted Glass) ===== */
        .chat-input-area {
            padding: 12px 0 4px;
            position: sticky;
            bottom: 0;
        }

        .chat-input-container {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg-elevated);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-color);
            border-radius: 100px;
            padding: 6px 6px 6px 20px;
            transition: all 0.25s ease;
        }

        .chat-input-container:focus-within {
            border-color: rgba(250, 204, 21, 0.3);
            box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.06), 0 0 20px rgba(250, 204, 21, 0.05);
        }

        .chat-input-container .add-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            flex-shrink: 0;
            padding: 6px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .chat-input-container .add-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
        }

        .chat-input-container .add-btn i {
            width: 20px;
            height: 20px;
        }

        .chat-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            color: var(--text-primary);
            background: transparent;
            padding: 10px 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .chat-input::placeholder {
            color: var(--text-muted);
        }

        .send-btn {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #FACC15, #EAB308);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 12px rgba(250, 204, 21, 0.2);
            color: #0B0F19;
        }

        .send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(250, 204, 21, 0.35);
        }

        .send-btn:active {
            transform: scale(0.95);
        }

        .send-btn i {
            width: 20px;
            height: 20px;
        }

        /* ===== Mobile: show history toggle ===== */
        .mobile-history-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-history-toggle {
                display: flex;
            }
            .chat-workspace {
                padding: 8px;
            }
        }
    </style>
</head>

<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="chat-workspace">

            <!-- Left: Chat History -->
            <div class="chat-history-panel">
                <div class="history-header">
                    <h3 data-i18n="chat.history">Chat History</h3>
                    <button class="history-new-btn" aria-label="New Chat">
                        <i data-lucide="plus"></i>
                    </button>
                </div>

                <input class="history-search" type="text" data-i18n="chat.searchPlaceholder" placeholder="Search conversations..." />

                <div class="history-groups">
                    <div class="history-group-label" data-i18n="chat.today">Today</div>
                    <div class="history-item active">
                        <div class="history-item-icon"><i data-lucide="message-circle"></i></div>
                        <div class="history-item-content">
                            <div class="history-item-title">Road damage analysis</div>
                            <div class="history-item-meta">2 hours ago</div>
                        </div>
                    </div>
                    <div class="history-item">
                        <div class="history-item-icon"><i data-lucide="message-circle"></i></div>
                        <div class="history-item-content">
                            <div class="history-item-title">YOLOv8 configuration</div>
                            <div class="history-item-meta">5 hours ago</div>
                        </div>
                    </div>

                    <div class="history-group-label" data-i18n="chat.yesterday">Yesterday</div>
                    <div class="history-item">
                        <div class="history-item-icon"><i data-lucide="message-circle"></i></div>
                        <div class="history-item-content">
                            <div class="history-item-title">Dataset preprocessing</div>
                            <div class="history-item-meta">1 day ago</div>
                        </div>
                    </div>
                    <div class="history-item">
                        <div class="history-item-icon"><i data-lucide="message-circle"></i></div>
                        <div class="history-item-content">
                            <div class="history-item-title">Model training results</div>
                            <div class="history-item-meta">1 day ago</div>
                        </div>
                    </div>

                    <div class="history-group-label" data-i18n="chat.lastWeek">Last Week</div>
                    <div class="history-item">
                        <div class="history-item-icon"><i data-lucide="message-circle"></i></div>
                        <div class="history-item-content">
                            <div class="history-item-title">Export inspection report</div>
                            <div class="history-item-meta">3 days ago</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Chat Area -->
            <div class="chat-main-panel">
                <!-- Chat Header -->
                <div class="chat-main-header">
                    <button class="history-new-btn mobile-history-toggle" aria-label="History">
                        <i data-lucide="panel-left-open"></i>
                    </button>
                    <div class="chat-model-badge">
                        <i data-lucide="sparkles"></i>
                        <span data-i18n="chat.modelBadge">RoKenAI • YOLOv8</span>
                    </div>
                    <div class="chat-actions">
                        <button class="chat-action-btn" aria-label="Clear">
                            <i data-lucide="eraser"></i>
                        </button>
                        <button class="chat-action-btn" aria-label="More">
                            <i data-lucide="more-vertical"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="chat-messages" id="chatArea">
                    <!-- Welcome Section -->
                    <div class="welcome-message">
                        <div class="welcome-avatar">
                            <img src="assets/Logo.png" alt="RoKen">
                        </div>
                        <div class="welcome-title" data-i18n="chat.welcomeTitle">Selamat datang di RoKenAI</div>
                        <div class="welcome-subtitle" data-i18n="chat.welcomeDesc">
                            Your AI assistant for road infrastructure damage detection and analysis. Ask me anything about road inspection data.
                        </div>
                        <div class="suggestion-chips">
                            <span class="suggestion-chip" onclick="quickSend('How to analyze road damage images?')" data-i18n="chat.suggestionAnalyze">🔍 Analyze road damage</span>
                            <span class="suggestion-chip" onclick="quickSend('Explain YOLOv8 metrics')" data-i18n="chat.suggestionMetrics">📊 YOLOv8 metrics</span>
                            <span class="suggestion-chip" onclick="quickSend('Upload format requirements')" data-i18n="chat.suggestionUpload">📁 Upload guide</span>
                            <span class="suggestion-chip" onclick="quickSend('Show recent detections')" data-i18n="chat.suggestionRecent">📋 Recent results</span>
                        </div>
                    </div>


                </div>

                <!-- Input Bar -->
                <div class="chat-input-area">
                    <div class="chat-input-container">
                        <button class="add-btn" aria-label="Add">
                            <i data-lucide="paperclip"></i>
                        </button>
                        <input class="chat-input" type="text" id="chatInput" data-i18n="chat.inputPlaceholder" placeholder="Tanya RoKenAI..." autocomplete="off">
                        <button class="send-btn" id="sendBtn" aria-label="Send">
                            <i data-lucide="arrow-up"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        const chatArea = document.getElementById('chatArea');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');

        function addMessage(text, isUser) {
            const div = document.createElement('div');
            if (isUser) {
                div.className = 'user-message';
                div.innerHTML = `<div class="user-bubble">${text}</div>`;
            } else {
                div.className = 'bot-message';
                div.innerHTML = `<div class="bot-avatar"><img src="assets/Logo.png" alt="RoKen"></div><div class="bot-bubble">${text}</div>`;
            }
            chatArea.appendChild(div);
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        function addThinking() {
            const div = document.createElement('div');
            div.className = 'bot-message';
            div.id = 'thinkingMsg';
            div.innerHTML = `
                <div class="bot-avatar"><img src="assets/Logo.png" alt="RoKen"></div>
                <div class="bot-bubble">
                    <div class="thinking-indicator">
                        <div class="thinking-dot"></div>
                        <div class="thinking-dot"></div>
                        <div class="thinking-dot"></div>
                    </div>
                </div>`;
            chatArea.appendChild(div);
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        function removeThinking() {
            const el = document.getElementById('thinkingMsg');
            if (el) el.remove();
        }

        function sendMessage() {
            const text = chatInput.value.trim();
            if (!text) return;
            addMessage(text, true);
            chatInput.value = '';
            addThinking();
            setTimeout(() => {
                removeThinking();
                const reply = 'Terima kasih atas pertanyaannya. Saya sedang memproses analisis untuk: "' + text + '". Hasil akan segera tersedia.';
                addMessage(reply, false);
                // Trigger notification
                if (typeof addNotification === 'function') {
                    const notifTitle = (typeof i18n !== 'undefined') ? i18n.t('notif.aiResponse') : 'AI Response Ready';
                    addNotification(
                        notifTitle,
                        'RoKenAI has replied to: "' + text.substring(0, 60) + (text.length > 60 ? '..."' : '"'),
                        'ai',
                        window.location.href
                    );
                }
            }, 1200);
        }

        function quickSend(text) {
            chatInput.value = text;
            sendMessage();
        }

        // Auto-populate prompt from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const promptParam = urlParams.get('prompt');
        if (promptParam) {
            chatInput.value = decodeURIComponent(promptParam.replace(/\+/g, ' '));
            setTimeout(sendMessage, 500);
        }

        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') sendMessage();
        });
    </script>
</body>
</html>
