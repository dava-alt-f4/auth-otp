<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola User</title>
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

        /* Layout: sidebar + main */
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
        }

        .page-title {
            margin: 0 0 20px;
            font-size: 20px;
            font-weight: 600;
        }

        .panel {
            max-width: 1000px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
        }

        .panel-toolbar {
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid var(--border);
            padding: 10px 12px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #fafafa;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .btn {
            padding: 6px 14px;
            text-decoration: none;
            border-radius: var(--radius);
            font-size: 13px;
            display: inline-block;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-primary {
            background-color: var(--text);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #000000;
        }

        .btn-secondary {
            background-color: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: #f5f5f5;
        }

        .btn-danger {
            background-color: var(--error);
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #a5301f;
        }

        .alert {
            padding: 10px 14px;
            background-color: #eaf6ec;
            color: #2f6b3a;
            border-radius: var(--radius);
            margin-bottom: 15px;
            font-size: 13px;
            display: none;
            /* Disembunyikan secara default, diatur oleh JS nanti */
        }

        /* Modal konfirmasi hapus */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(26, 26, 26, 0.45);
            backdrop-filter: blur(3px);
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            width: 100%;
            max-width: 380px;
            background: var(--surface);
            border-radius: var(--radius);
            padding: 28px;
        }

        .modal-box h3 {
            margin: 0 0 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .modal-box p {
            margin: 0 0 24px;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-brand">Admin Panel</div>
            <nav class="sidebar-nav">
                <a href="/admin" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/inbox" class="nav-link {{ request()->is('admin/inbox') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Inbox</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <button type="button" class="btn-logout-sidebar" onclick="logout()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        <main class="admin-main">
            <h1 class="page-title">Daftar User</h1>

            <div class="panel">
                <!-- Notifikasi dari JS akan muncul di sini -->
                <div id="alert-message" class="alert"></div>

                <div class="panel-toolbar">
                    <!-- Pastikan URL href ini mengarah ke route web kamu yang menyajikan halaman form create -->
                    <a href="/admin/create" class="btn btn-primary">+ Tambah User Baru</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        <!-- Data akan dimuat melalui JavaScript -->
                        <tr>
                            <td colspan="4" style="text-align: center;">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal konfirmasi hapus user -->
    <div class="modal-overlay" id="deleteModalOverlay">
        <div class="modal-box">
            <h3>Hapus User</h3>
            <p>Apakah kamu yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('api_token');

        if (!token) {
            window.location.href = '/passwordLogin';
        }

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
            window.location.href = '/login';
        }

        fetch('/api/admin', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    window.location.href = '/dashboard';
                    throw new Error("Akses ditolak");
                }
                return res.json();
            })
            .then(users => {
                const tbody = document.getElementById('user-table-body');
                tbody.innerHTML = '';

                if (users.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">Tidak ada data user.</td></tr>';
                    return;
                }

                // Loop user
                users.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <a href="/admin/edit/${user.id}" class="btn btn-secondary">Edit</a>
                        <button class="btn btn-danger" onclick="deleteUser(${user.id})">Hapus</button>
                    </td>
                `;
                    tbody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });

        let userIdToDelete = null;

        function deleteUser(id) {
            userIdToDelete = id;
            document.getElementById('deleteModalOverlay').classList.add('active');
        }

        function closeDeleteModal() {
            userIdToDelete = null;
            document.getElementById('deleteModalOverlay').classList.remove('active');
        }

        function confirmDelete() {
            if (!userIdToDelete) return;
            const id = userIdToDelete;

            fetch(`/api/admin/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (res.ok) {
                        const alertBox = document.getElementById('alert-message');
                        alertBox.style.display = 'block';
                        alertBox.innerText = 'User berhasil dihapus.';

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        alert('Gagal menghapus user!');
                    }
                })
                .catch(err => console.error(err));

            closeDeleteModal();
        }
    </script>
</body>

</html>
