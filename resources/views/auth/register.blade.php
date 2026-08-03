<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--text);
        }

        .auth-container {
            width: 100%;
            max-width: 380px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px;
        }

        .auth-container h1 {
            margin: 0 0 4px;
            font-size: 20px;
            font-weight: 600;
        }

        .auth-subtitle {
            margin: 0 0 24px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            color: var(--text);
            background: var(--surface);
        }

        .form-group input:focus {
            outline: none;
            border-color: #999999;
        }

        .error-text {
            margin: 6px 0 0;
            font-size: 12px;
            color: var(--error);
            min-height: 14px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 500;
            color: #ffffff;
            background: #1a1a1a;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #000000;
        }

        .auth-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--text);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Register</h1>
        <p class="auth-subtitle">Buat akun baru untuk mulai menggunakan layanan.</p>

        <div class="form-group">
            <label for="nameInput">Name</label>
            <input type="text" id="nameInput" placeholder="Nama lengkap" required>
            <p class="error-text" id="nameError"></p>
        </div>

        <div class="form-group">
            <label for="emailInput">Email</label>
            <input type="email" id="emailInput" placeholder="nama@email.com" required>
            <p class="error-text" id="emailError"></p>
        </div>

        <div class="form-group">
            <label for="passwordInput">Password</label>
            <input type="password" id="passwordInput" placeholder="Minimal 6 karakter" required>
            <p class="error-text" id="passwordError"></p>
        </div>

        <button type="button" class="btn-primary" onclick="prosesRegister()">Register</button>

        <div class="auth-footer">
            Sudah punya akun? <a href="/login">Login di sini</a>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('api_token');
        if (token) {
            window.location.href = '/dashboard';
        }

        function isValidEmail(value) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        }

        async function prosesRegister() {
            const nameField = document.getElementById('nameInput');
            const emailField = document.getElementById('emailInput');
            const passwordField = document.getElementById('passwordInput');

            const nameError = document.getElementById('nameError');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');

            nameError.textContent = '';
            emailError.textContent = '';
            passwordError.textContent = '';

            let isValid = true;

            if (!nameField.value.trim()) {
                nameError.textContent = 'Nama wajib diisi.';
                isValid = false;
            }

            if (!emailField.value.trim()) {
                emailError.textContent = 'Email wajib diisi.';
                isValid = false;
            } else if (!isValidEmail(emailField.value.trim())) {
                emailError.textContent = 'Format email tidak valid.';
                isValid = false;
            }

            if (!passwordField.value) {
                passwordError.textContent = 'Password wajib diisi.';
                isValid = false;
            } else if (passwordField.value.length < 6) {
                passwordError.textContent = 'Password minimal 6 karakter.';
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            const name = document.getElementById('nameInput').value;
            const email = document.getElementById('emailInput').value;
            const password = document.getElementById('passwordInput').value;

            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: name, email: email, password: password })
            });

            const data = await response.json();

            if (response.ok) {
                alert('Registrasi Berhasil! Silakan login.');
                window.location.href = '/login';
            } else {
                alert('Error: ' + data.message);
            }
        }
    </script>
</body>
</html>
