<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen | Chat</title>
    <?php include 'partials/link.php'; ?>
    <style>
        body {
            background: #e5e7eb;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* Chat wrapper: full height minus navbar */
        #content-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
            /* Center on large screens */
            max-width: 720px;
            width: 100%;
            margin: 0 auto;
        }

        .chat-area {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px 8px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @media (min-width: 576px) {
            .chat-area {
                padding: 20px 20px 8px;
            }
        }

        .chat-area::-webkit-scrollbar {
            width: 4px;
        }

        .chat-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Bubbles */
        .bot-message {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }

        .bot-avatar {
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 50%;
            overflow: hidden;
            background: #fff;
            border: 1.5px solid #e2e8f0;
        }

        .bot-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .bot-bubble {
            background: #fff;
            border-radius: 18px 18px 18px 4px;
            padding: 10px 16px;
            font-size: 14px;
            color: #1f2937;
            max-width: min(75%, 480px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            line-height: 1.55;
        }

        .user-message {
            display: flex;
            justify-content: flex-end;
        }

        .user-bubble {
            background: #1f2937;
            color: #fff;
            border-radius: 18px 18px 4px 18px;
            padding: 10px 16px;
            font-size: 14px;
            max-width: min(75%, 480px);
            line-height: 1.55;
        }

        /* Input bar */
        .input-bar {
            background: #e5e7eb;
            padding: 10px 12px 16px;
        }

        @media (min-width: 576px) {
            .input-bar {
                padding: 12px 20px 20px;
            }
        }

        .input-inner {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border-radius: 28px;
            padding: 8px 8px 8px 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.09);
            max-width: 720px;
            margin: 0 auto;
        }

        .add-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .chat-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            color: #1f2937;
            background: transparent;
        }

        .chat-input::placeholder {
            color: #9ca3af;
        }

        .send-btn {
            background: #1f2937;
            border: none;
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .send-btn:hover {
            background: #374151;
        }
    </style>
</head>

<body>

    <?php include 'partials/header.php'; ?>

    <div id="content-wrapper">
        <div class="chat-container">
            <div class="chat-area" id="chatArea">
                <div class="bot-message">
                    <div class="bot-avatar"><img src="assets/Logo.png" alt="RoKen"></div>
                    <div class="bot-bubble">Apa yang bisa saya bantu?</div>
                </div>
            </div>

            <div class="input-bar">
                <div class="input-inner">
                    <button class="add-btn" aria-label="Tambah">
                        <i data-lucide="plus" style="width:20px;height:20px;"></i>
                    </button>
                    <input class="chat-input" type="text" id="chatInput" placeholder="Tanya RokenAI" autocomplete="off">
                    <button class="send-btn" id="sendBtn" aria-label="Kirim">
                        <i data-lucide="arrow-up" style="width:16px;height:16px;color:#fff;"></i>
                    </button>
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

        function sendMessage() {
            const text = chatInput.value.trim();
            if (!text) return;
            addMessage(text, true);
            chatInput.value = '';
            setTimeout(() => addMessage('Sedang memproses...', false), 600);
        }

        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') sendMessage();
        });
    </script>
</body>

</html>