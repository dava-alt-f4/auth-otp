<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <script src="https://unpkg.com/feather-icons"></script>
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
            justify-content: space-between;
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

        .action {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .action-auth {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .action-chat {
            position: relative;
        }

        .notification {
            display: none;
            align-items: center;
            justify-content: center;
            position: absolute;
            color: white;
            font-size: small;
            font-weight: 500;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            background-color: red;
            right: -5px;
            top: -5px;
        }

        .btn-logout {
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 700;
            color: white;
            background-color: red;
            border: 1px solid var(--border);
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: rgb(196, 1, 1);
        }

        .register,
        .login,
        .pfp {
            padding: 8px;
            font-size: 13px;
            font-weight: 700;
            color: black;
            text-decoration: none;
            border: 1px solid var(--border);
            border-radius: 6px;
            cursor: pointer;
            width: 4.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .login {
            color: white;
            background-color: rgb(64, 64, 245);
        }

        .login:hover {
            background: blue;
        }

        .register:hover {
            background: whitesmoke;
        }

        .pfp:hover {
            background: whitesmoke;
        }
    </style>
</head>

<body>
    <header class="main-header">
        <h1>Selamat Datang di Dashboard</h1>
        <div class="action">
            <div class="action-chat">
                <a href="/chat"><i data-feather="message-square"></i></a>
                <span class="notification" id="notif"></span>
            </div>
            <div class="action-auth">
                <a id="btn-register" href="/register" class="register">Register</a>
                <a id="btn-login" href="/passwordLogin" class="login">Login</a>
                <button id="btn-logout" class="btn-logout" onclick="logout()">Logout</button>
                <button id="profile" class="pfp" onclick="profile()">Profile</button>
            </div>
        </div>
    </header>

    <script>
        feather.replace();

        const token = localStorage.getItem('api_token');
        const logoutBtn = document.getElementById('btn-logout');
        const registerBtn = document.getElementById('btn-register');
        const loginBtn = document.getElementById('btn-login');
        const profileBtn = document.getElementById('profile');
        const notif = document.getElementById('notif');

        if (token) {
            logoutBtn.style.display = 'block';
            profileBtn.style.display = 'inline-flex';
            registerBtn.style.display = 'none';
            loginBtn.style.display = 'none';
        } else {
            logoutBtn.style.display = 'none';
            profileBtn.style.display = 'none';
            registerBtn.style.display = 'inline-flex';
            loginBtn.style.display = 'inline-flex';
        }

        const headers = {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };

        function notify() {
            fetch('/api/notif', { headers })
            .then(res => {
                return res.json();
            })
            .then(data => {
                if (data.unread > 0) {
                    notif.innerHTML = data.unread;
                    notif.style.display = 'flex';
                } else {
                    notif.style.display = 'none';
                }
            }
            )
            .catch(err => {
                console.log(err);
            });
        }
        notify();


        function profile() {
            window.location.href = `/profile/${localStorage.getItem('userId')}`;
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
            window.location.href = '/passwordLogin';
        }
    </script>
</body>

</html>
