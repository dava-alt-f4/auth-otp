<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <style>
        :root {
            --bg: #f5f5f5;
            --surface: #ffffff;
            --border: #dddddd;
            --text: #1a1a1a;
            --text-muted: #6b6b6b;
            --radius: 6px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* Header */
        .main-header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: 52px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--text);
        }

        .back-link svg {
            width: 14px;
            height: 14px;
        }

        .header-divider {
            width: 1px;
            height: 16px;
            background: var(--border);
        }

        .main-header h1 {
            font-size: 14px;
            font-weight: 600;
        }

        /* Chat */
        .chat-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            max-width: 720px;
            width: 100%;
            margin: 0 auto;
            padding: 0 24px;
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
            scrollbar-width: none;
        }

        /* Pesan */
        .msg {
            max-width: 68%;
            padding: 10px 14px;
            border-radius: var(--radius);
            font-size: 14px;
            line-height: 1.5;
            word-break: break-word;
        }

        .msg-admin {
            background: var(--surface);
            color: var(--text);
            align-self: flex-start;
            border: 1px solid var(--border);
        }

        .msg-user {
            background: var(--text);
            color: #ffffff;
            align-self: flex-end;
        }

        /* Form input */
        .chat-form {
            flex-shrink: 0;
            display: flex;
            gap: 10px;
            padding: 16px 0;
            border-top: 1px solid var(--border);
        }

        .chat-form input {
            flex: 1;
            padding: 10px 14px;
            font-size: 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            color: var(--text);
            background: var(--surface);
            outline: none;
        }

        .chat-form input:focus {
            border-color: #999999;
        }

        .chat-form button {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            background: var(--text);
            color: #ffffff;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-family: inherit;
            flex-shrink: 0;
        }

        .chat-form button:hover {
            background: #000000;
        }

        @media (max-width: 600px) {
            .chat-wrapper {
                padding: 0 16px;
            }

            .msg {
                max-width: 85%;
            }
        }
    </style>
</head>

<body>
    <header class="main-header">
        <div class="header-left">
            <a href="/dashboard" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Dashboard
            </a>
            <div class="header-divider"></div>
            <h1>Customer Service</h1>
        </div>
    </header>

    <div class="chat-wrapper">
        <div class="chat-box" id="chatBox">
            <div class="msg msg-admin">
                Halo! Selamat datang di layanan kami. Ada yang bisa kami bantu?
            </div>
        </div>

        <form class="chat-form" id="chatForm">
            <input type="text" id="messageInput" placeholder="Tulis pesan..." required autocomplete="off">
            <button type="submit">Kirim</button>
        </form>
    </div>

    <script>
        // Ini buat token
        let guestToken = localStorage.getItem('guest_token');
        if (!guestToken) {
            guestToken = 'guest_' + Math.random().toString(36).substring(2, 9) + Date.now();
            localStorage.setItem('guest_token', guestToken);
        }

        const apiToken = localStorage.getItem('api_token');
        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        function getHeaders() {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Guest-Token': guestToken
            };
            if (apiToken) {
                headers['Authorization'] = 'Bearer ' + apiToken;
            }
            return headers;
        }

        function fetchMessages() {
            fetch('/api/chat', {
                    headers: getHeaders()
                })
                .then(res => res.json())
                .then(data => {
                    if (data.messages) {
                        renderMessages(data.messages);
                    }
                })
                .catch(err => console.error("Gagal memuat pesan:", err));
        }


        function renderMessages(messages) {
            chatBox.innerHTML = `
            <div class="msg msg-admin">
                Halo! Selamat datang di layanan kami. Ada yang bisa kami bantu?
            </div>
        `;

            messages.forEach(msg => {
                const div = document.createElement('div');
                div.className = `msg ${msg.is_admin ? 'msg-admin' : 'msg-user'}`;
                div.textContent = msg.message;
                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // ini ngirim
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            fetch('/api/chat', {
                    method: 'POST',
                    headers: getHeaders(),
                    body: JSON.stringify({
                        message: text
                    })
                })
                .then(res => res.json())
                .then(() => {
                    messageInput.value = '';
                    fetchMessages();
                })
                .catch(err => console.error("Gagal mengirim pesan:", err));
        });

        fetchMessages();
        setInterval(fetchMessages, 3600000);
    </script>

</body>

</html>
