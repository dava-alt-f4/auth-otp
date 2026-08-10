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
            font-weight: 500;
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
        <p class="error-text" style="font-size: smaller; font-weight:600;" id="formError"></p>

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
            <input type="password" id="passwordInput" placeholder="Minimal 8 karakter" required>
            <p class="error-text" id="passwordError"></p>
        </div>

        <div class="form-group">
            <label for="confirmPasswordInput">Confirm Password</label>
            <input type="password" id="confirmPasswordInput" placeholder="Konfirmasi password" required>
            <p class="error-text" id="confirmPasswordError"></p>
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
            const confirmPasswordField = document.getElementById('confirmPasswordInput');
            const nameError = document.getElementById('nameError');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            const formError = document.getElementById('formError');

            nameError.textContent = '';
            emailError.textContent = '';
            passwordError.textContent = '';
            confirmPasswordError.textContent = '';
            formError.textContent = '';

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
            } else if (passwordField.value.length < 8) {
                passwordError.textContent = 'Password minimal 8 karakter.';
                isValid = false;
            }

            if (!confirmPasswordField.value) {
                confirmPasswordError.textContent = 'Konfirmasi password wajib diisi.';
                isValid = false;
            } else if (passwordField.value !== confirmPasswordField.value) {
                confirmPasswordError.textContent = 'Password dan konfirmasi password tidak cocok.';
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            const name = nameField.value.trim();
            const email = emailField.value.trim();
            const password = passwordField.value;

            try {
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
                    window.location.href = '/login';
                    return;
                }

                if (data.errors?.name) {
                    nameError.textContent = data.errors.name[0];
                }

                if (data.errors?.email) {
                    emailError.textContent = data.errors.email[0];
                }

                if (data.errors?.password) {
                    passwordError.textContent = data.errors.password[0];
                }

                if (!formError.textContent) {
                    formError.textContent = data.message || 'Terjadi kesalahan saat registrasi.';
                }
            } catch (error) {
                formError.textContent = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
            }
        }
    </script>
</body>
</html>
