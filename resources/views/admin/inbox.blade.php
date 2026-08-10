<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Inbox Obrolan</title>
    <style>
        :root {
            --bg: #f5f5f5;
            --surface: #ffffff;
            --border: #dddddd;
            --text: #1a1a1a;
            --text-muted: #6b6b6b;
            --error: #c0392b;
            --radius: 6px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--text);
        }

        /* Layout: sidebar admin + main */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 220px;
            flex-shrink: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 20px 24px;
            font-size: 15px;
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-link:hover {
            background: #f5f5f5;
            color: var(--text);
        }

        .nav-link.active {
            background: #f0f0f0;
            color: var(--text);
            font-weight: 500;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }

        .btn-logout-sidebar {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 14px;
            border-radius: var(--radius);
            cursor: pointer;
            font-family: inherit;
        }

        .btn-logout-sidebar svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .btn-logout-sidebar:hover {
            background: #f5f5f5;
            color: var(--error);
        }

        .admin-main {
            flex: 1;
            padding: 40px 32px;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .page-title {
            margin: 0 0 20px;
            font-size: 20px;
            font-weight: 600;
        }

        /* Panel Chat */
        .container {
            max-width: 1000px;
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: flex;
            height: 600px;
            overflow: hidden;
        }

        /* Panel Kiri (Daftar Inbox) */
        .sidebar {
            width: 35%;
            border-right: 1px solid var(--border);
            background: var(--surface);
            display: flex;
            flex-direction: column;
        }
        .sidebar-header { padding: 16px 20px; background: var(--surface); border-bottom: 1px solid var(--border); color: var(--text); font-weight: 600; font-size: 14px; }
        .inbox-list { flex: 1; overflow-y: auto; }
        .inbox-item { padding: 14px 20px; border-bottom: 1px solid var(--border); cursor: pointer; }
        .inbox-item:hover { background: #f7f7f7; }
        .inbox-item.active { background: #f0f0f0; }
        .inbox-name { font-weight: 500; font-size: 14px; margin-bottom: 4px; display: flex; justify-content: space-between; gap: 8px; }
        .inbox-msg { font-size: 12px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .badge { background: var(--error); color: #ffffff; padding: 2px 7px; border-radius: 10px; font-size: 11px; font-weight: 600; }

        /* Panel Kanan (Layar Obrolan) */
        .chat-area { width: 65%; display: flex; flex-direction: column; background: var(--surface); }
        .chat-header { padding: 16px 20px; background: var(--surface); border-bottom: 1px solid var(--border); font-weight: 600; font-size: 14px; }
        .chat-box { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: #fafafa; }

        /* Gelembung Pesan di layar Admin */
        .msg { max-width: 75%; padding: 10px 14px; border-radius: var(--radius); font-size: 14px; word-break: break-word; }
        .msg-user { background: #ffffff; color: var(--text); align-self: flex-start; border: 1px solid var(--border); } /* Pesan dari User di kiri */
        .msg-admin { background: var(--text); color: #ffffff; align-self: flex-end; } /* Pesan dari Admin di kanan */

        .chat-form { display: flex; gap: 10px; padding: 16px 20px; background: var(--surface); border-top: 1px solid var(--border); }
        .chat-form input { flex: 1; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius); outline: none; font-family: inherit; font-size: 14px; }
        .chat-form input:focus { border-color: #999999; }
        .chat-form button { padding: 10px 18px; background: var(--text); color: #ffffff; border: none; border-radius: var(--radius); cursor: pointer; font-family: inherit; font-size: 14px; }
        .chat-form button:hover { background: #000000; }

        .empty-state { flex: 1; display: flex; align-items: center; justify-content: center; color: var(--text-muted); flex-direction: column; text-align: center; gap: 4px; }
        .empty-state h3 { margin: 0; font-size: 15px; color: var(--text); }
        .empty-state p { margin: 0; font-size: 13px; }

        @media (max-width: 800px) {
            .container { flex-direction: column; height: auto; }
            .sidebar, .chat-area { width: 100%; }
            .sidebar { border-right: none; border-bottom: 1px solid var(--border); max-height: 260px; }
            .chat-area { height: 480px; }
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">Admin Panel</div>
        <nav class="sidebar-nav">
            <a href="/admin" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="/admin/inbox" class="nav-link {{ request()->is('admin/inbox') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span>Inbox</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <button type="button" class="btn-logout-sidebar" onclick="logout()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout</span>
            </button>
        </div>
    </aside>

    <main class="admin-main">
        <h1 class="page-title">Pesan Masuk</h1>

        <div class="container">
            <!-- Panel Kiri: Daftar Chat -->
            <div class="sidebar">
                <div class="sidebar-header">Pesan Masuk</div>
                <div class="inbox-list" id="inboxList">
                    <div style="padding: 15px; text-align: center; color: #888;">Memuat pesan...</div>
                </div>
            </div>

            <!-- Panel Kanan: Area Chat -->
            <div class="chat-area" id="chatArea">
                <div class="empty-state">
                    <h3>Pilih obrolan</h3>
                    <p>Klik salah satu obrolan di sebelah kiri untuk mulai membalas.</p>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const token = localStorage.getItem('api_token');
    if (!token) window.location.href = '/passwordLogin';

    async function logout() {
        const response = await fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        localStorage.removeItem('api_token');
        localStorage.removeItem('userId');
        localStorage.removeItem('role');
        window.location.href = '/dashboard';
    }

    const headers = {
        'Authorization': 'Bearer ' + token,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };

    let activeConversationId = null;

    function fetchInbox() {
        fetch('/api/admin/inbox', { headers })
            .then(res => {
                if (!res.ok) throw new Error("Akses ditolak");
                return res.json();
            })
            .then(data => renderInbox(data))
            .catch(err => {
                console.error(err);
                if (err.message === "Akses ditolak") window.location.href = '/dashboard';
            });
    }

    function renderInbox(conversations) {
        const inboxList = document.getElementById('inboxList');
        inboxList.innerHTML = '';

        if (conversations.length === 0) {
            inboxList.innerHTML = '<div style="padding: 15px; text-align: center; color: #888;">Belum ada pesan masuk.</div>';
            return;
        }

        conversations.forEach(conv => {
            const div = document.createElement('div');
            div.className = `inbox-item ${activeConversationId === conv.id ? 'active' : ''}`;
            div.onclick = () => openChat(conv.id, conv.guest_name);

            const badge = conv.unread_count_admin > 0
                ? `<span class="badge">${conv.unread_count_admin}</span>`
                : '';

            div.innerHTML = `
                <div class="inbox-name">
                    ${conv.guest_name} ${badge}
                </div>
                <div class="inbox-msg">${conv.last_message || 'Belum ada pesan...'}</div>
            `;
            inboxList.appendChild(div);
        });
    }

    function openChat(id, name) {
        activeConversationId = id;

        document.getElementById('chatArea').innerHTML = `
            <div class="chat-header">Chat dengan: ${name}</div>
            <div class="chat-box" id="adminChatBox">
                <div style="text-align: center; color: #888; padding: 20px;">Memuat obrolan...</div>
            </div>
            <form class="chat-form" id="adminChatForm">
                <input type="text" id="adminMessageInput" placeholder="Ketik balasan..." required autocomplete="off">
                <button type="submit">Kirim</button>
            </form>
        `;

        fetchChatData();

        document.getElementById('adminChatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('adminMessageInput');
            const text = input.value.trim();
            if (!text) return;

            fetch(`/api/admin/inbox/${activeConversationId}`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ message: text })
            })
            .then(res => res.json())
            .then(() => {
                input.value = '';
                fetchChatData();
                fetchInbox();
            })
            .catch(err => alert("Gagal mengirim balasan."));
        });
    }

    function fetchChatData() {
        if (!activeConversationId) return;

        fetch(`/api/admin/inbox/${activeConversationId}`, { headers })
            .then(res => res.json())
            .then(data => {
                const box = document.getElementById('adminChatBox');
                box.innerHTML = '';

                data.messages.forEach(msg => {
                    const div = document.createElement('div');
                    div.className = `msg ${msg.is_admin ? 'msg-admin' : 'msg-user'}`;
                    div.textContent = msg.message;
                    box.appendChild(div);
                });

                box.scrollTop = box.scrollHeight;

                fetchInbox();
            })
            .catch(err => console.error(err));
    }

    fetchInbox();
    setInterval(() => {
        fetchInbox();
        if (activeConversationId) fetchChatData();
    }, 3000);
</script>

</body>
</html>
