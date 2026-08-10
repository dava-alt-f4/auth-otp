<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Profile</title>
    <style>
        :root {
            --bg: #f5f5f5;
            --surface: #ffffff;
            --border: #dddddd;
            --text: #1a1a1a;
            --text-muted: #6b6b6b;
            --error: #c0392b;
            --success: #27ae60;
            --radius: 6px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--text);
            scrollbar-width: none;
        }


        .main-header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: 52px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--text);
        }

        .back-link svg {
            width: 14px;
            height: 14px;
        }

        .header-divider {
            width: 1px;
            height: 16px;
            background: var(--border);
        }

        .main-header h1 {
            font-size: 14px;
            font-weight: 600;
        }

        .page-wrapper {
            max-width: 1100px;
            margin: 3rem auto;
        }

        .page-title {
            margin: 0 0 24px;
            font-size: 20px;
            font-weight: 600;
        }

        .profile-container {
            display: flex;
            flex-wrap: wrap;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .profile-section {
            flex: 1 1 300px;
            padding: 28px;
            border-right: 1px solid var(--border);
        }

        .profile-section:last-child { border-right: none; }

        .section-title {
            margin: 0 0 20px;
            font-size: 15px;
            font-weight: 600;
        }

        .avatar-wrapper {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 16px;
        }

        .avatar-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #eaeaea;
            border: 1px solid var(--border);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-circle svg {
            width: 56px;
            height: 56px;
            color: #b3b3b3;
        }

        .avatar-edit-btn {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1a1a1a;
            border: 2px solid var(--surface);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
        }

        .avatar-edit-btn:hover { background: #000000; }

        .avatar-edit-btn svg {
            width: 14px;
            height: 14px;
        }

        .avatar-edit-btn input[type="file"] { display: none; }

        #uploadAvatarBtn {
            display: none;
            width: 110px;
            margin: 8px auto 20px;
            padding: 7px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #ffffff;
            background: #1a1a1a;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
        }

        #uploadAvatarBtn:hover { background: #000000; }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            color: var(--text);
            background: var(--surface);
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'><path d='M0 0l5 6 5-6z' fill='%236b6b6b'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 30px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #999999;
        }

        .error-text {
            margin: 6px 0 0;
            font-size: 12px;
            color: var(--error);
            min-height: 14px;
        }

        .success-text {
            margin: 6px 0 0;
            font-size: 12px;
            color: var(--success);
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
            margin-top: 4px;
        }

        .btn-primary:hover { background: #000000; }

        @media (max-width: 700px) {
            .profile-container { flex-direction: column; }

            .profile-section {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .profile-section:last-child { border-bottom: none; }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <a href="/dashboard" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Dashboard
            </a>
            <div class="header-divider"></div>
            <h1>Profil</h1>
        </div>
    </header>
    <div class="page-wrapper">

        <div class="profile-container">
            <!-- Section 1: Foto Profil & Info Akun -->
            <section class="profile-section">
                <h2 class="section-title">Foto & Info Profil</h2>

                <div class="avatar-wrapper">
                    <div class="avatar-circle" id="avatarCircle">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.4c-3.3 0-9.8 1.6-9.8 4.9v2.5h19.6v-2.5c0-3.3-6.5-4.9-9.8-4.9z"/>
                        </svg>
                    </div>
                    <button type="button" class="avatar-edit-btn" onclick="document.getElementById('pfpInput').click()" aria-label="Ganti foto profil">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                        </svg>
                        <input type="file" id="pfpInput" accept="image/*">
                    </button>
                </div>

                <button type="button" id="uploadAvatarBtn">Perbarui Foto</button>

                <form id="profileInfoForm" novalidate>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" placeholder="Nama lengkap">
                        <p class="error-text" id="nameError"></p>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="nama@email.com">
                        <p class="error-text" id="emailError"></p>
                    </div>

                    <p class="success-text" id="infoSuccess"></p>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </form>
            </section>

            <!-- Section 2: Ubah Password -->
            <section class="profile-section">
                <h2 class="section-title">Ubah Password</h2>

                <form id="passwordForm" novalidate>
                    <div class="form-group">
                        <label for="oldPassword">Password Lama</label>
                        <input type="password" id="oldPassword" name="old_password" placeholder="Password saat ini">
                        <p class="error-text" id="oldPasswordError"></p>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">Password Baru</label>
                        <input type="password" id="newPassword" name="new_password" placeholder="Minimal 8 karakter">
                        <p class="error-text" id="newPasswordError"></p>
                    </div>

                    <div class="form-group">
                        <label for="newPasswordConfirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="newPasswordConfirmation" name="new_password_confirmation" placeholder="Ulangi password baru">
                        <p class="error-text" id="newPasswordConfirmationError"></p>
                    </div>

                    <p class="success-text" id="passwordSuccess"></p>
                    <button type="submit" class="btn-primary">Ubah Password</button>
                </form>
            </section>

            <!-- Section 3: Alamat -->
            <section class="profile-section">
                <h2 class="section-title">Alamat</h2>

                <form id="addressForm" novalidate>
                    <div class="form-group">
                        <label for="postalCode">Kode Pos</label>
                        <input type="text" id="postalCode" name="postal_code" maxlength="5" inputmode="numeric" placeholder="Contoh: 60123">
                        <p class="error-text" id="postalCodeError"></p>
                    </div>

                    <div class="form-group">
                        <label for="country">Negara</label>
                        <select id="country" name="country">
                            <option value="" disabled>Pilih negara</option>
                            <option value="Indonesia">Indonesia</option>
                        </select>
                        <p class="error-text" id="countryError"></p>
                    </div>

                    <div class="form-group">
                        <label for="province">Provinsi</label>
                        <input type="text" id="province" name="province" placeholder="Contoh: Jawa Timur">
                        <p class="error-text" id="provinceError"></p>
                    </div>

                    <div class="form-group">
                        <label for="city">Kota/Kabupaten</label>
                        <input type="text" id="city" name="city" placeholder="Contoh: Surabaya">
                        <p class="error-text" id="cityError"></p>
                    </div>

                    <div class="form-group">
                        <label for="district">Kecamatan</label>
                        <input type="text" id="district" name="district" placeholder="Contoh: Gubeng">
                        <p class="error-text" id="districtError"></p>
                    </div>

                    <p class="success-text" id="addressSuccess"></p>
                    <button type="submit" class="btn-primary">Simpan Alamat</button>
                </form>
            </section>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('api_token');
        const userId = localStorage.getItem('userId');

        if (!token) {
            window.location.href = '/login';
        }

        const apiHeaders = {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json',
        };

        function isValidEmail(value) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        }

        // Isi semua field dengan data user dari API saat halaman dibuka
        fetch('/api/profile/' + userId, { headers: apiHeaders })
            .then(function (res) {
                if (res.status === 403) { window.location.href = '/login'; return null; }
                return res.json();
            })
            .then(function (data) {
                if (!data) return;

                const urlId = window.location.pathname.split('/').pop();
                if (String(data.id) !== String(urlId)) {
                    window.location.href = userId;
                    return;
                }

                document.getElementById('name').value = data.name ?? '';
                document.getElementById('email').value = data.email ?? '';
                document.getElementById('postalCode').value = data.postal_code ?? '';
                document.getElementById('province').value = data.province ?? '';
                document.getElementById('city').value = data.city ?? '';
                document.getElementById('district').value = data.district ?? '';

                if (data.country) {
                    document.getElementById('country').value = data.country;
                }

                if (data.avatar) {
                    document.getElementById('avatarCircle').innerHTML =
                        '<img src="' + data.avatar + '" alt="Foto profil">';
                }
            });

        // Tampilkan tombol "Perbarui Foto" setelah file dipilih
        document.getElementById('pfpInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('avatarCircle').innerHTML =
                    '<img src="' + event.target.result + '" alt="Foto profil">';
            };
            reader.readAsDataURL(file);

            document.getElementById('uploadAvatarBtn').style.display = 'block';
        });

        // Upload avatar terpisah dari form info
        document.getElementById('uploadAvatarBtn').addEventListener('click', function () {
            const file = document.getElementById('pfpInput').files[0];
            if (!file) return;

            const formData = new FormData();
            // Kirim name & email yang sudah ada agar validasi backend tidak gagal
            formData.append('name', document.getElementById('name').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('avatar', file);

            fetch('/api/profile/' + userId + '/info', {
                method: 'POST',
                headers: apiHeaders,
                body: formData,
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.message) {
                        document.getElementById('uploadAvatarBtn').style.display = 'none';
                        document.getElementById('pfpInput').value = '';
                    }
                });
        });

        // Section 1: Info profil
        document.getElementById('profileInfoForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const nameField = document.getElementById('name');
            const emailField = document.getElementById('email');
            const nameError = document.getElementById('nameError');
            const emailError = document.getElementById('emailError');
            const infoSuccess = document.getElementById('infoSuccess');

            nameError.textContent = '';
            emailError.textContent = '';
            infoSuccess.textContent = '';

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

            if (!isValid) return;

            const formData = new FormData();
            formData.append('name', nameField.value.trim());
            formData.append('email', emailField.value.trim());

            fetch('/api/profile/' + userId + '/info', {
                method: 'POST',
                headers: apiHeaders,
                body: formData,
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.errors) {
                        if (data.errors.name) nameError.textContent = data.errors.name[0];
                        if (data.errors.email) emailError.textContent = data.errors.email[0];
                    } else {
                        infoSuccess.textContent = data.message;
                    }
                });
        });

        // Section 2: Ubah password
        document.getElementById('passwordForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const oldPasswordField = document.getElementById('oldPassword');
            const newPasswordField = document.getElementById('newPassword');
            const confirmField = document.getElementById('newPasswordConfirmation');

            const oldPasswordError = document.getElementById('oldPasswordError');
            const newPasswordError = document.getElementById('newPasswordError');
            const confirmError = document.getElementById('newPasswordConfirmationError');
            const passwordSuccess = document.getElementById('passwordSuccess');

            oldPasswordError.textContent = '';
            newPasswordError.textContent = '';
            confirmError.textContent = '';
            passwordSuccess.textContent = '';

            let isValid = true;

            if (!oldPasswordField.value) {
                oldPasswordError.textContent = 'Password lama wajib diisi.';
                isValid = false;
            }

            if (!newPasswordField.value) {
                newPasswordError.textContent = 'Password baru wajib diisi.';
                isValid = false;
            } else if (newPasswordField.value.length < 8) {
                newPasswordError.textContent = 'Password baru minimal 8 karakter.';
                isValid = false;
            }

            if (!confirmField.value) {
                confirmError.textContent = 'Konfirmasi password wajib diisi.';
                isValid = false;
            } else if (confirmField.value !== newPasswordField.value) {
                confirmError.textContent = 'Konfirmasi password tidak cocok.';
                isValid = false;
            }

            if (!isValid) return;

            fetch('/api/profile/' + userId + '/password', {
                method: 'POST',
                headers: { ...apiHeaders, 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    old_password: oldPasswordField.value,
                    new_password: newPasswordField.value,
                    new_password_confirmation: confirmField.value,
                }),
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.errors) {
                        if (data.errors.old_password) oldPasswordError.textContent = data.errors.old_password[0];
                        if (data.errors.new_password) newPasswordError.textContent = data.errors.new_password[0];
                    } else if (data.message && data.message !== 'Password berhasil diubah.') {
                        // Pesan error dari controller (misal: password lama salah)
                        oldPasswordError.textContent = data.message;
                    } else {
                        passwordSuccess.textContent = data.message;
                        document.getElementById('passwordForm').reset();
                    }
                });
        });

        // Section 3: Alamat
        document.getElementById('addressForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const postalCodeField = document.getElementById('postalCode');
            const countryField = document.getElementById('country');
            const provinceField = document.getElementById('province');
            const cityField = document.getElementById('city');
            const districtField = document.getElementById('district');

            const postalCodeError = document.getElementById('postalCodeError');
            const countryError = document.getElementById('countryError');
            const provinceError = document.getElementById('provinceError');
            const cityError = document.getElementById('cityError');
            const districtError = document.getElementById('districtError');
            const addressSuccess = document.getElementById('addressSuccess');

            postalCodeError.textContent = '';
            countryError.textContent = '';
            provinceError.textContent = '';
            cityError.textContent = '';
            districtError.textContent = '';
            addressSuccess.textContent = '';

            // Validasi kode pos jika diisi (nullable di DB)
            if (postalCodeField.value.trim() && !/^\d{5}$/.test(postalCodeField.value.trim())) {
                postalCodeError.textContent = 'Kode pos harus 5 digit angka.';
                return;
            }

            fetch('/api/profile/' + userId + '/address', {
                method: 'POST',
                headers: { ...apiHeaders, 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    postal_code: postalCodeField.value.trim() || null,
                    country: countryField.value || null,
                    province: provinceField.value.trim() || null,
                    city: cityField.value.trim() || null,
                    district: districtField.value.trim() || null,
                }),
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.errors) {
                        if (data.errors.postal_code) postalCodeError.textContent = data.errors.postal_code[0];
                        if (data.errors.country) countryError.textContent = data.errors.country[0];
                        if (data.errors.province) provinceError.textContent = data.errors.province[0];
                        if (data.errors.city) cityError.textContent = data.errors.city[0];
                        if (data.errors.district) districtError.textContent = data.errors.district[0];
                    } else {
                        addressSuccess.textContent = data.message;
                    }
                });
        });
    </script>
</body>
</html>
