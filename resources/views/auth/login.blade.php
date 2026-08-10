<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
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

        .back-link {
            display: flex;
            gap:6px;
            font-size:0.91rem;
            text-decoration: none;
            color:grey;
            margin-bottom: 10px;
        }

        .back-link:hover {
            color:black;
        }

        .back-link p {
            text-decoration: underline;
            margin:0;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <a href="/dashboard" class="back-link">< <p>Dashboard</p></a>
        <h1>Login</h1>
        <p class="auth-subtitle">Masuk menggunakan email terdaftar untuk menerima kode OTP.</p>
        <p class="error-text" style="font-size: smaller; font-weight:600;" id="formError"></p>

        <div class="form-group">
            <label for="emailInput">Email</label>
            <input type="email" id="emailInput" placeholder="nama@email.com" required>
            <p class="error-text" id="emailError"></p>
        </div>

        <button type="button" class="btn-primary" onclick="prosesLogin()">Kirim OTP</button>

        <div class="auth-footer">
            Login dengan password? <a href="/passwordLogin">Klik di sini</a><br>
            Belum punya akun? <a href="/register">Daftar di sini</a>
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

        async function prosesLogin() {
            const emailInput = document.getElementById('emailInput');
            const emailError = document.getElementById('emailError');
            const formError = document.getElementById('formError');

            emailError.textContent = '';
            formError.textContent = '';

            if (!emailInput.value.trim()) {
                emailError.textContent = 'Email wajib diisi.';
                return;
            }

            if (!isValidEmail(emailInput.value.trim())) {
                emailError.textContent = 'Format email tidak valid.';
                return;
            }

            const email = emailInput.value.trim();

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('temp_email', email);
                    window.location.href = '/verify-otp/' + data.hash;
                    return;
                }

                if (data.errors?.email) {
                    emailError.textContent = data.errors.email[0];
                    return;
                }

                formError.textContent = data.message || 'Terjadi kesalahan, silakan coba lagi.';
            } catch (error) {
                formError.textContent = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
            }
        }
    </script>
</body>
</html>
