<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KosanKu - Login & Daftar</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-200 font-sans min-h-screen">
  <div class="min-h-screen flex">
    <div class="lg:flex lg:w-1/2 relative overflow-hidden">
      <div class="bg-[url('/assets/login-pict.png')] bg-no-repeat bg-cover bg-center w-full h-full"></div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
      <div class="w-full max-w-md">
        <div id="register-form" class="bg-white p-8 rounded-2xl shadow-xl border-gray-200">
          <h2 class="text-[#31c594] mb-6 text-2xl font-bold text-center">Daftar Akun</h2>
          
          <form action="{{ route('signup.process') }}" method="POST">
            @csrf

            @if ($errors->any())
              <div class="bg-red-50 text-red-800 p-3 mb-4 rounded-lg text-sm">
                <ul class="list-disc pl-5 m-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="space-y-4">
              <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required 
                       class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 transition-all">
              </div>

              <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required 
                       class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 transition-all">
              </div>

              <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required 
                       class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 transition-all">
              </div>

              <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                       class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 transition-all">
              </div>

              <div>
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Daftar Sebagai</label>
                <select id="role" name="role" required 
                        class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 transition-all">
                  <option value="">-- Pilih --</option>
                  <option value="landboard" {{ old('role') == 'landboard' ? 'selected' : '' }}>Pemilik Kost</option>
                  <option value="tenant" {{ old('role') == 'tenant' ? 'selected' : '' }}>Pencari Kost</option>
                </select>
              </div>
            </div>

            <button type="submit" 
                    class="mt-6 p-3 w-full bg-[#31c594] text-white border-none rounded-lg text-base font-semibold cursor-pointer transition-all duration-300 hover:bg-[#1a966d] transform hover:scale-[1.02]">
              Daftar Sekarang
            </button>
          </form>

          <!-- Link ke Login -->
          <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
              Sudah punya akun? 
              <button onclick="showLogin()" class="text-[#31c594] hover:text-[#1a966d] font-semibold hover:underline transition-colors">
                Masuk di sini
              </button>
            </p>
          </div>
        </div>

        <div id="login-form" class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200 hidden">
          <h2 class="text-[#31c594] mb-6 text-2xl font-bold text-center">Masuk Akun</h2>
          
          <form action="{{ route('login.process') }}" method="POST">
            @csrf

            @if (session('success'))
              <div class="bg-green-50 text-green-800 p-3 mb-4 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            @if ($errors->has('username'))
              <div class="bg-red-50 text-red-800 p-3 mb-4 rounded-lg text-sm">{{ $errors->first('username') }}</div>
            @endif

            <div class="space-y-4">
              <div>
                <label for="login_username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                <input type="text" id="login_username" name="username" value="{{ old('username') }}" required 
                       class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none transition-all">
              </div>

              <div>
                <label for="login_password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" id="login_password" name="password" required 
                       class="w-full p-3 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none transition-all">
              </div>
            </div>

            <button type="submit" 
                    class="mt-6 p-3 w-full bg-[#31c594] text-white border-none rounded-lg text-base font-semibold cursor-pointer transition-all duration-300 hover:bg-[#1a966d] transform hover:scale-[1.02]">
              Masuk
            </button>
          </form>

          <!-- Link ke Register -->
          <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
              Belum punya akun? 
              <button onclick="showRegister()" class="text-[#31c594] hover:text-[#1a966d] font-semibold hover:underline transition-colors">
                Daftar di sini
              </button>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="/js/script.js"></script>
</html>