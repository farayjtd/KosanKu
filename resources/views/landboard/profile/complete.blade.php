<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Lengkapi Profil Landboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background: #f5f3f0;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .card {
            background: #fffaf6;
            padding: 25px;
            border-radius: 12px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #5a4430;
        }

        .section-title {
            margin-top: 26px;
            font-weight: bold;
            font-size: 18px;
            color: #5a4430;
            border-bottom: 1px solid #ddd0c1;
            padding-bottom: 6px;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: 600;
            color: #6b4e3d;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #cfc4b5;
            font-size: 14px;
            background: #fdfdfb;
            color: #3f3f3f;
            box-sizing: border-box;
        }

        button {
            margin-top: 28px;
            width: 100%;
            padding: 12px;
            background: #8d735b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        button:hover {
            background: #775d47;
        }

        .logout-btn {
            background: #c94e4e;
            margin-top: 16px;
        }

        .logout-btn:hover {
            background: #a43737;
        }

        @media (max-width: 768px) {
            .card {
                padding: 20px;
                margin: 20px;
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    {{-- Optional Sidebar --}}
    {{-- @include('components.sidebar-landboard') --}}

    <div class="main-content">
        <div class="card">
            <h2>Lengkapi Profil Anda</h2>

            <form action="{{ route('landboard.complete-profile.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="section-title">Data Pribadi</div>
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" required>

                <label for="phone">Nomor HP Utama</label>
                <input type="text" name="phone" id="phone" required>

                <label for="alt_phone">Nomor HP Alternatif (Opsional)</label>
                <input type="text" name="alt_phone" id="alt_phone">

                <label for="avatar">Foto Profil (Avatar)</label>
                <input type="file" name="avatar" id="avatar" accept="image/*">

                <div class="section-title">Data Kost</div>
                <label for="kost_name">Nama Kost</label>
                <input type="text" name="kost_name" id="kost_name" required>

                <label for="province">Provinsi</label>
                <input type="text" name="province" id="province" required>

                <label for="city">Kota/Kabupaten</label>
                <input type="text" name="city" id="city" required>

                <label for="district">Kecamatan</label>
                <input type="text" name="district" id="district" required>

                <label for="village">Kelurahan (Opsional)</label>
                <input type="text" name="village" id="village">

                <label for="postal_code">Kode Pos (Opsional)</label>
                <input type="text" name="postal_code" id="postal_code">

                <label for="full_address">Alamat Lengkap</label>
                <input type="text" name="full_address" id="full_address" required>

                <div class="section-title">Informasi Bank</div>
                <label for="bank_name">Nama Bank</label>
                <input type="text" name="bank_name" id="bank_name" required>

                <label for="bank_account">Nomor Rekening</label>
                <input type="text" name="bank_account" id="bank_account" required>

                <button type="submit">Simpan Profil</button>
            </form>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

</body>
</html>
