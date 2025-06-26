<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Tenant</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            display: flex;
            background: #f1f5f9;
        }

        .sidebar {
            width: 250px;
            background: #e2e8f0;
            padding: 20px;
            height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            background: #f8fafc;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            max-width: 760px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }

        h2 {
            margin-top: 0;
            color: #1e293b;
        }

        label {
            display: block;
            margin-top: 16px;
            margin-bottom: 6px;
            font-weight: 600;
            color: #334155;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-sizing: border-box;
            background: #f9fafb;
            color: #1e293b;
        }

        input[type="file"] {
            background: #fff;
        }

        button {
            margin-top: 28px;
            padding: 12px 18px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
        }

        button:hover {
            background: #2563eb;
        }

        .message {
            margin-top: 10px;
            font-size: 14px;
        }

        .message.success {
            color: #15803d;
        }

        .message.error {
            color: #dc2626;
        }

        img {
            margin-top: 10px;
            border-radius: 8px;
            display: block;
            max-width: 120px;
            border: 1px solid #e2e8f0;
        }

        ul {
            padding-left: 18px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    @include('components.sidebar-tenant')

    <div class="main-content">
        <div class="card">
            <h2>Edit Profil</h2>

            @if(session('success'))
                <p class="message success">{{ session('success') }}</p>
            @endif

            @if($errors->any())
                <div class="message error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tenant.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label>Username</label>
                <input type="text" name="username" value="{{ old('username', $account->username) }}" required>

                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $account->email) }}">

                <label>Password Baru (kosongkan jika tidak diganti)</label>
                <input type="password" name="password">

                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation">

                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $account->name) }}" required>

                <label>No HP</label>
                <input type="text" name="phone" value="{{ old('phone', $account->phone) }}" required>

                <label>No HP Alternatif</label>
                <input type="text" name="alt_phone" value="{{ old('alt_phone', $account->alt_phone) }}">

                <label>Foto Profil (Avatar)</label>
                <input type="file" name="avatar" accept="image/*">
                @if ($account->avatar)
                    <img src="{{ asset('storage/' . $account->avatar) }}" alt="Foto Avatar">
                @endif

                <label>Foto Identitas (Tenant)</label>
                <input type="file" name="photo" accept="image/*">
                @if ($tenant->photo)
                    <img src="{{ asset('storage/' . $tenant->photo) }}" alt="Foto Diri Tenant">
                @endif

                <label>Alamat Asal</label>
                <input type="text" name="address" value="{{ old('address', $tenant->address) }}" required>

                <label>Jenis Kelamin</label>
                <select name="gender" required>
                    <option value="">-- Pilih --</option>
                    <option value="male" {{ old('gender', $tenant->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender', $tenant->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>

                <label>Jenis Aktivitas</label>
                <input type="text" name="activity_type" value="{{ old('activity_type', $tenant->activity_type) }}">

                <label>Nama Instansi</label>
                <input type="text" name="institution_name" value="{{ old('institution_name', $tenant->institution_name) }}">

                <label>Nama Bank</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" required>

                <label>Nomor Rekening</label>
                <input type="text" name="bank_account" value="{{ old('bank_account', $account->bank_account) }}" required>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
