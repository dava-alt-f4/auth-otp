<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
        :root {
            --bg: #f5f5f5;
            --surface: #ffffff;
            --border: #dddddd;
            --text: #1a1a1a;
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

        .main-header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 24px;
            padding: 16px 24px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .main-header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-logout {
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <button class="btn-logout" onclick="logout()">Logout</button>
        <h1>Selamat Datang di Dashboard</h1>
    </header>

    <script>
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = '/login';
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
            window.location.href = '/login';
        }
    </script>
</body>
</html>
