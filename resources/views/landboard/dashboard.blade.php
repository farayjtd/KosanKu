<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KosanKu - Dashboard Landboard</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-grey-200 font-sans m-0 flex flex-col md:flex-row min-h-screen">

  {{-- Sidebar --}}
  @include('components.sidebar-landboard')

  {{-- Main Content --}}
  <div class="flex-1 p-6 md:p-8">
    <h2 class="text-2xl font-semibold text-[#5b4636] mb-2">Selamat datang, {{ Auth::user()->name }}</h2>
    <p class="text-[#6b5e53] text-sm mb-6">Gunakan menu di samping untuk mengelola kost Anda dengan mudah.</p>

    <div class="bg-white p-6 rounded-xl border border-[#dcd3cc] shadow-md">
      <h3 class="text-xl font-semibold text-[#5a4430] mb-4">Pengaturan Denda</h3>

      <!-- Denda Keterlambatan -->
      <p class="mb-4 text-sm text-[#6b5e53]">
        💸 <span class="font-bold text-[#4b3a2d]">Keterlambatan:</span><br />
        @if(Auth::user()->landboard->is_penalty_enabled)
          Rp {{ number_format(Auth::user()->landboard->late_fee_amount ?? 0) }} / hari<br />
          <span class="text-[#a6a29b] italic">Didenda setelah {{ Auth::user()->landboard->late_fee_days ?? 0 }} hari keterlambatan</span>
        @else
          <span class="text-[#a6a29b] italic">Tidak aktif</span>
        @endif
      </p>

      <!-- Denda Pindah Kamar -->
      <p class="mb-4 text-sm text-[#6b5e53]">
        🏠 <span class="font-bold text-[#4b3a2d]">Pindah Kamar:</span><br />
        @if(Auth::user()->landboard->is_penalty_on_room_change)
          Rp {{ number_format(Auth::user()->landboard->room_change_penalty_amount ?? 0) }}
        @else
          <span class="text-[#a6a29b] italic">Tidak aktif</span>
        @endif
      </p>

      <!-- Denda Keluar Tengah Jalan -->
      <p class="text-sm text-[#6b5e53]">
        🚪 <span class="font-bold text-[#4b3a2d]">Keluar Tengah Jalan:</span><br />
        @if(Auth::user()->landboard->is_penalty_on_moveout)
          Rp {{ number_format(Auth::user()->landboard->moveout_penalty_amount ?? 0) }}
        @else
          <span class="text-[#a6a29b] italic">Tidak aktif</span>
        @endif
      </p>
    </div>
  </div>
</body>
</html>
