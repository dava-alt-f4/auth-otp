<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Verification</title>
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
            font-size: 18px;
            letter-spacing: 4px;
            text-align: center;
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

        .btn-secondary {
            width: 100%;
            margin-top: 10px;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Verifikasi OTP</h1>
        <p class="auth-subtitle">Masukkan 6 digit kode OTP yang telah dikirim ke email Anda.</p>
        <p class="error-text" style="font-size: smaller; font-weight:600;" id="formError"></p>

        <div class="form-group">
            <label for="otpInput">Kode OTP</label>
            <input type="text" id="otpInput" maxlength="6" inputmode="numeric" placeholder="------" required>
            <p class="error-text" id="otpError"></p>
        </div>

        <button type="button" class="btn-primary" onclick="prosesVerifikasi()">Verifikasi</button>
        <button type="button" class="btn-secondary" onclick="window.location.href='/login'">Kembali</button>
    </div>

    <script>
        const token = localStorage.getItem('api_token');
        if (token) {
            window.location.href = '/dashboard';
        }

        async function prosesVerifikasi() {
            const otpField = document.getElementById('otpInput');
            const otpError = document.getElementById('otpError');
            const formError = document.getElementById('formError');

            otpError.textContent = '';
            formError.textContent = '';

            if (!otpField.value.trim()) {
                otpError.textContent = 'Kode OTP wajib diisi.';
                return;
            }

            if (!/^\d{6}$/.test(otpField.value.trim())) {
                otpError.textContent = 'Kode OTP harus 6 digit angka.';
                return;
            }

            const email = localStorage.getItem('temp_email');
            const otpCode = otpField.value.trim();

            if (!email) {
                formError.textContent = 'Sesi habis, silakan login ulang.';
                setTimeout(() => window.location.href = '/login', 1200);
                return;
            }

            try {
                const response = await fetch('/api/auth/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        otp_code: otpCode
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('api_token', data.access_token);
                    localStorage.removeItem('temp_email');
                    window.location.href = '/dashboard';
                    return;
                }

                if (data.errors?.otp_code) {
                    otpError.textContent = data.errors.otp_code[0];
                    return;
                }

                formError.textContent = data.message || 'Kode OTP tidak valid atau sudah kadaluwarsa.';
            } catch (error) {
                formError.textContent = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
            }
        }
    </script>
</body>
</html>
