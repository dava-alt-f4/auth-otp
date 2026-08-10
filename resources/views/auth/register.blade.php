<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
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
        <h1>Register</h1>
        <p class="auth-subtitle">Buat akun baru untuk mulai menggunakan layanan.</p>
        <p class="error-text" style="font-size: smaller; font-weight:600;" id="formError"></p>

        <div id="step1">
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

            <button type="button" class="btn-primary" onclick="goToStep2()">Selanjutnya</button>
        </div>

        <div id="step2" style="display: none;">
            <div class="form-group">
                <label for="postalCodeInput">Kode Pos</label>
                <input type="text" id="postalCodeInput" placeholder="Masukkan kode pos">
                <p class="error-text" id="postalCodeError"></p>
            </div>

            <div class="form-group">
                <label for="countryInput">Negara</label>
                <select id="countryInput" class="select2" style="width: 100%;">
                    <option value="Indonesia" selected>Indonesia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="provinceInput">Provinsi</label>
                <select id="provinceInput" class="select2" style="width: 100%;">
                    <option value="">Pilih Provinsi</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cityInput">Kota/Kabupaten</label>
                <select id="cityInput" class="select2" style="width: 100%;">
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>

            <div class="form-group">
                <label for="districtInput">Kecamatan</label>
                <select id="districtInput" class="select2" style="width: 100%;">
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn-primary" style="background: #6b6b6b;" onclick="goToStep1()">Kembali</button>
                <button type="button" class="btn-primary" onclick="prosesRegister()">Register</button>
            </div>
        </div>

        <div class="auth-footer">
            Sudah punya akun? <a href="/login">Login di sini</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                .then(response => response.json())
                .then(provinces => {
                    let options = '<option value="">Pilih Provinsi</option>';
                    provinces.forEach(province => {
                        options += `<option value="${province.name}" data-id="${province.id}">${province.name}</option>`;
                    });
                    $('#provinceInput').html(options);
                });


            $('#provinceInput').on('change', function() {
                const provinceId = $(this).find(':selected').data('id');
                $('#cityInput').html('<option value="">Pilih Kota/Kabupaten</option>');
                $('#districtInput').html('<option value="">Pilih Kecamatan</option>');

                if (provinceId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                        .then(response => response.json())
                        .then(cities => {
                            let options = '<option value="">Pilih Kota/Kabupaten</option>';
                            cities.forEach(city => {
                                options += `<option value="${city.name}" data-id="${city.id}">${city.name}</option>`;
                            });
                            $('#cityInput').html(options);
                        });
                }
            });


            $('#cityInput').on('change', function() {
                const cityId = $(this).find(':selected').data('id');
                $('#districtInput').html('<option value="">Pilih Kecamatan</option>');

                if (cityId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`)
                        .then(response => response.json())
                        .then(districts => {
                            let options = '<option value="">Pilih Kecamatan</option>';
                            districts.forEach(district => {
                                options += `<option value="${district.name}" data-id="${district.id}">${district.name}</option>`;
                            });
                            $('#districtInput').html(options);
                        });
                }
            });

            // Kode pos auto-fill
            $('#postalCodeInput').on('input', function() {
                const postalCode = $(this).val().trim();
                if (postalCode.length >= 5) {
                    $('#postalCodeError').text('Mencari data wilayah...');
                    fetch(`https://kodepos.vercel.app/search?q=${postalCode}`)
                        .then(response => response.json())
                        .then(res => {
                            if (res.data && res.data.length > 0) {
                                $('#postalCodeError').text('');
                                const data = res.data[0];

                                // Set provinsi
                                const provinceName = data.province.toUpperCase();
                                if ($(`#provinceInput option[value="${provinceName}"]`).length === 0) {
                                    $('#provinceInput').append(new Option(provinceName, provinceName, true, true));
                                } else {
                                    $('#provinceInput').val(provinceName).trigger('change.select2');
                                }

                                // Set kota
                                const cityName = data.regency.toUpperCase();
                                if ($(`#cityInput option[value="${cityName}"]`).length === 0) {
                                    $('#cityInput').append(new Option(cityName, cityName, true, true));
                                } else {
                                    $('#cityInput').val(cityName).trigger('change.select2');
                                }

                                // Set kecamatan
                                const districtName = data.district.toUpperCase();
                                if ($(`#districtInput option[value="${districtName}"]`).length === 0) {
                                    $('#districtInput').append(new Option(districtName, districtName, true, true));
                                } else {
                                    $('#districtInput').val(districtName).trigger('change.select2');
                                }
                            } else {
                                $('#postalCodeError').text('Kode pos tidak ditemukan.');
                            }
                        })
                        .catch(() => {
                            $('#postalCodeError').text('Gagal mengambil data kode pos.');
                        });
                } else {
                    $('#postalCodeError').text('');
                }
            });
        });

        const token = localStorage.getItem('api_token');
        if (token) {
            window.location.href = '/dashboard';
        }

        function isValidEmail(value) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        }

        function validateStep1() {
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

            return isValid;
        }

        function goToStep2() {
            if (validateStep1()) {
                document.getElementById('step1').style.display = 'none';
                document.getElementById('step2').style.display = 'block';
            }
        }

        function goToStep1() {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
        }

        async function prosesRegister() {
            if (!validateStep1()) {
                goToStep1();
                return;
            }

            const name = document.getElementById('nameInput').value.trim();
            const email = document.getElementById('emailInput').value.trim();
            const password = document.getElementById('passwordInput').value;
            const country = document.getElementById('countryInput').value;
            const province = document.getElementById('provinceInput').value;
            const city = document.getElementById('cityInput').value;
            const district = document.getElementById('districtInput').value;
            const postal_code = document.getElementById('postalCodeInput').value;


            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        country: country,
                        province: province,
                        city: city,
                        district: district,
                        postal_code: postal_code
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = '/login';
                    return;
                }

                if (data.errors?.name || data.errors?.email || data.errors?.password) {
                    goToStep1();
                    if (data.errors?.name) document.getElementById('nameError').textContent = data.errors.name[0];
                    if (data.errors?.email) document.getElementById('emailError').textContent = data.errors.email[0];
                    if (data.errors?.password) document.getElementById('passwordError').textContent = data.errors.password[0];
                }

                if (!document.getElementById('formError').textContent) {
                    document.getElementById('formError').textContent = data.message || 'Terjadi kesalahan saat registrasi.';
                }
            } catch (error) {
                document.getElementById('formError').textContent = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
            }
        }
    </script>
</body>
</html>
