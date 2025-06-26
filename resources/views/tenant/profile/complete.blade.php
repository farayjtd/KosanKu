<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Lengkapi Profil</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            margin-bottom: 24px;
            color: #1e293b;
        }

        form {
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #334155;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
            background: #f9fafb;
            color: #1e293b;
        }

        input[type="file"] {
            padding: 8px;
            background: #ffffff;
        }

        button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            width: 100%;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s ease-in-out;
        }

        button:hover {
            background: #2563eb;
        }

        .logout {
            max-width: 540px;
            width: 100%;
        }

        .logout button {
            background: #ef4444;
        }

        .logout button:hover {
            background: #b91c1c;
        }

        @media (max-width: 600px) {
            form, .logout {
                padding: 20px;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>

    <h1>Lengkapi Profil Anda</h1>

    <form action="{{ route('tenant.profile.complete.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="name">Nama Lengkap</label>
        <input type="text" name="name" id="name" required>

        <label for="phone">Nomor HP Utama</label>
        <input type="text" name="phone" id="phone" required>

        <label for="alt_phone">Nomor HP Alternatif (Opsional)</label>
        <input type="text" name="alt_phone" id="alt_phone">

        <label for="avatar">Foto Profil (Avatar)</label>
        <input type="file" name="avatar" id="avatar" accept="image/*" required>

        <label for="photo">Foto Diri (Identitas)</label>
        <input type="file" name="photo" id="photo" accept="image/*" required>

        <label for="address">Alamat Asal</label>
        <input type="text" name="address" id="address" required>

        <label for="gender">Jenis Kelamin</label>
        <select name="gender" id="gender" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="male">Laki-laki</option>
            <option value="female">Perempuan</option>
        </select>

        <label for="activity_type">Jenis Aktivitas</label>
        <input type="text" name="activity_type" id="activity_type" placeholder="Contoh: Mahasiswa, Pegawai" required>

        <label for="institution_name">Nama Institusi</label>
        <input type="text" name="institution_name" id="institution_name" required>

        <label for="bank_name">Nama Bank</label>
        <input type="text" name="bank_name" id="bank_name" required>

        <label for="bank_account">Nomor Rekening</label>
        <input type="text" name="bank_account" id="bank_account" required>

        <button type="submit">Simpan Profil</button>
    </form>

    <form action="{{ route('logout') }}" method="POST" class="logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>
