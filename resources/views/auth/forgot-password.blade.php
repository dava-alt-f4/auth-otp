<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
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
        <h1>Lupa Password</h1>
        <p class="auth-subtitle">Masukkan email Anda, kami akan mengirimkan link untuk reset password.</p>

        <form action="/forgot-password" method="POST" id="forgotPasswordForm" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" required name="email" id="email" placeholder="nama@email.com">
                <p class="error-text" id="emailError"></p>
            </div>

            <button type="submit" class="btn-primary">Reset</button>
        </form>

        <div class="auth-footer">
            Ingat password Anda? <a href="/login">Kembali ke login</a>
        </div>
    </div>

    <script>
        function isValidEmail(value) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        }

        document.getElementById('forgotPasswordForm').addEventListener('submit', function (e) {
            const emailField = document.getElementById('email');
            const emailError = document.getElementById('emailError');

            emailError.textContent = '';

            if (!emailField.value.trim()) {
                emailError.textContent = 'Email wajib diisi.';
                e.preventDefault();
                return;
            }

            if (!isValidEmail(emailField.value.trim())) {
                emailError.textContent = 'Format email tidak valid.';
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
