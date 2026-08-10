<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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

        .form-shell {
            max-width: 600px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px;
        }

        .form-card h2 {
            margin: 0 0 4px;
            font-size: 18px;
            font-weight: 600;
        }

        .form-subtitle {
            margin: 0 0 24px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .section-divider {
            border: 0;
            border-top: 1px solid var(--border);
            margin: 24px 0;
        }

        .section-heading {
            margin: 0 0 16px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .form-group {
            margin-bottom: 16px;
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

        .form-hint {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
        }

        .btn {
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            border-radius: var(--radius);
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            display: inline-block;
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
    </style>
</head>
<body>
    <div class="form-shell">
        <div class="form-card">
            <h2>Edit User <span id="userNameTitle"></span></h2>
            <p class="form-subtitle">Perbarui data user di bawah ini.</p>

            <form id="editForm">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password">
                    <span class="form-hint">*Kosongkan jika tidak ingin mengubah password</span>
                </div>

                <hr class="section-divider">
                <p class="section-heading">Data Alamat</p>

                <div class="form-group">
                    <label>Negara</label>
                    <input type="text" name="country" id="country">
                </div>
                <div class="form-group">
                    <label>Provinsi</label>
                    <input type="text" name="province" id="province">
                </div>
                <div class="form-group">
                    <label>Kota/Kabupaten</label>
                    <input type="text" name="city" id="city">
                </div>
                <div class="form-group">
                    <label>Kecamatan</label>
                    <input type="text" name="district" id="district">
                </div>
                <div class="form-group">
                    <label>Kode Pos</label>
                    <input type="text" name="postal_code" id="postal_code" required>
                </div>

                <div class="form-actions">
                    <a href="/admin" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('api_token');
        if (!token) window.location.href = '/passwordLogin';

        // Ambil ID dari URL (asumsi URL formatnya: /admin/edit/1)
        const userId = window.location.pathname.split('/').pop();

        // Load data user saat ini
        fetch(`/api/admin/${userId}`, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(user => {
            document.getElementById('userNameTitle').innerText = `- ${user.name}`;
            document.getElementById('name').value = user.name || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('country').value = user.country || '';
            document.getElementById('province').value = user.province || '';
            document.getElementById('city').value = user.city || '';
            document.getElementById('district').value = user.district || '';
            document.getElementById('postal_code').value = user.postal_code || '';
        })
        .catch(err => alert('Gagal memuat data user.'));

        // Update data user
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Hapus password dari payload jika kosong agar tidak tertimpa
            if (!data.password) delete data.password;

            try {
                const res = await fetch(`/api/admin/${userId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (res.ok) {
                    alert('User berhasil diperbarui!');
                    window.location.href = '/admin';
                } else {
                    const err = await res.json();
                    alert(err.message || 'Gagal mengupdate data.');
                }
            } catch (error) {
                alert('Terjadi kesalahan koneksi.');
            }
        });
    </script>
</body>
</html>
