<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KosanKu - Login & Daftar</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f1ee;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      width: 100%;
      max-width: 460px;
      padding: 20px;
    }

    .header {
      text-align: center;
      margin-top: 40px;
    }

    .header h1 {
      color: #6b4f3b;
      font-size: 2.4em;
      margin-bottom: 6px;
    }

    .header p {
      color: #888;
      font-size: 1em;
    }

    form {
      background: #fff;
      padding: 24px 28px;
      margin: 20px 0;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      border: 1px solid #ddd;
    }

    h2 {
      color: #4b3b2f;
      margin-bottom: 14px;
      font-size: 1.4em;
      text-align: center;
    }

    label {
      display: block;
      margin-top: 14px;
      font-weight: 600;
      color: #4a4a4a;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      background-color: #fdfaf8;
    }

    button {
      margin-top: 20px;
      padding: 10px;
      width: 100%;
      background: #8b5e3c;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #6b4f3b;
    }

    .alert {
      background: #fcebea;
      color: #8b0000;
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 6px;
      font-size: 14px;
    }

    .success {
      background: #edf7ed;
      color: #2f513c;
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 6px;
      font-size: 14px;
    }

    @media (max-width: 480px) {
      .container {
        padding: 16px;
      }

      form {
        padding: 20px;
      }

      h2 {
        font-size: 1.2em;
      }

      .header h1 {
        font-size: 2em;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>KosanKu</h1>
      <p>Sistem Manajemen Kost Modern</p>
    </div>

    {{-- Form Registrasi --}}
    <form action="{{ route('signup.process') }}" method="POST">
      <h2>Daftar Akun</h2>
      @csrf

      @if ($errors->any())
        <div class="alert">
          <ul style="padding-left: 20px; margin: 0;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="{{ old('username') }}" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="password_confirmation">Konfirmasi Password</label>
      <input type="password" id="password_confirmation" name="password_confirmation" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="{{ old('email') }}" required>

      <label for="role">Daftar Sebagai</label>
      <select id="role" name="role" required>
        <option value="">-- Pilih --</option>
        <option value="landboard" {{ old('role') == 'landboard' ? 'selected' : '' }}>Pemilik Kost</option>
        <option value="tenant" {{ old('role') == 'tenant' ? 'selected' : '' }}>Pencari Kost</option>
      </select>

      <button type="submit">Daftar</button>
    </form>

    {{-- Form Login --}}
    <form action="{{ route('login.process') }}" method="POST">
      <h2>Masuk Akun</h2>
      @csrf

      @if (session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif

      @if ($errors->has('username'))
        <div class="alert">{{ $errors->first('username') }}</div>
      @endif

      <label for="login_username">Username</label>
      <input type="text" id="login_username" name="username" value="{{ old('username') }}" required>

      <label for="login_password">Password</label>
      <input type="password" id="login_password" name="password" required>

      <button type="submit">Login</button>
    </form>
  </div>

</body>
</html>
