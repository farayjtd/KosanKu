<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Pemilik Kos</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

</head>
<body class="bg-gray-200 font-sans min-h-screen p-6">
@include('components.sidebar-landboard')
  <div class="grid grid-cols-12 gap-6 h-[800px] overflow-hidden">
    <div class="col-span-3 bg-white p-6 rounded-xl shadow h-[360px]">
      <h2 class="text-xl text-center font-semibold mb-6"><i class="bi bi-person-vcard mr-2"></i>Profil Pemilik</h2>
      <img src="{{ Auth::user()->avatar_url }}" class="w-20 h-20 rounded-full mx-auto mb-3 object-cover">
      <p class="text-center font-bold">{{ Auth::user()->name }}</p>
      <p class="text-center text-sm text-gray-500">{{ Auth::user()->email }}</p>
      <div class="mt-2 text-center text-sm text-gray-600 space-y-1">
        <p><i class="bi bi-house-door text-[#31c594] mr-1"></i>{{ Auth::user()->landboard->kost_name }}</p>
        <p><i class="bi bi-telephone text-[#31c594] mr-1"></i>{{ Auth::user()->phone }}</p>
        <p><i class="bi bi-geo-alt text-[#31c594] mr-1"></i>{{ Auth::user()->landboard->full_address }}</p>
        </div>
      </div>
      <div class="col-span-5 bg-white p-6 rounded-xl shadow h-[360px]">
        <h2 class="text-xl font-semibold mb-4"><i class="bi bi-cash-coin mr-2"></i> Pengaturan Denda</h2>
        <div class="space-y-3 text-sm text-black">
          <div>
            <span class="font-bold"><i class="bi bi-building-exclamation mr-2"></i>Keterlambatan:</span><br>
            @if(Auth::user()->landboard->is_penalty_enabled)
              Rp {{ number_format(Auth::user()->landboard->late_fee_amount ?? 0) }} / hari
              <br><span class="italic">Setelah {{ Auth::user()->landboard->late_fee_days }} hari</span>
            @else
              <span class="italic">Tidak aktif</span>
            @endif
          </div>
          <div>
            <span class="font-bold"><i class="bi bi-houses mr-2"></i>Pindah Kamar:</span><br>
            @if(Auth::user()->landboard->is_penalty_on_room_change)
              Rp {{ number_format(Auth::user()->landboard->room_change_penalty_amount ?? 0) }}
            @else
              <span class="italic">Tidak aktif</span>
            @endif
          </div>
          <div>
            <span class="font-bold"><i class="bi bi-box-arrow-left mr-2"></i>Keluar Tengah Jalan:</span><br>
            @if(Auth::user()->landboard->is_penalty_on_moveout)
              Rp {{ number_format(Auth::user()->landboard->moveout_penalty_amount ?? 0) }}
            @else
              <span class="italic">Tidak aktif</span>
            @endif
          </div>
        </div>
      </div>
      <div class="col-span-4 bg-white p-6 rounded-xl shadow h-[360px] overflow-hidden">
        <h2 class="text-xl font-semibold mb-4"><i class="bi bi-calendar3 mr-2"></i> Kalender</h2>
        <div id="calendar" class="w-full h-[280px]"></div>
      </div>

      <div class="col-span-4 bg-white p-6 rounded-xl shadow h-[360px]">
        <h2 class="text-xl font-semibold mb-4"><i class="bi bi-house-exclamation mr-2"></i> Informasi Kamar</h2>
        <div class="space-y-3">
          <div class="flex justify-between bg-gray-100 p-3 rounded">
            <span>Total Kamar</span><span class="font-bold"></span>
          </div>
          <div class="flex justify-between bg-green-50 p-3 rounded">
            <span>Tersedia</span><span class="font-bold text-green-600"></span>
          </div>
          <div class="flex justify-between bg-orange-50 p-3 rounded">
            <span>Terpakai</span><span class="font-bold text-orange-500"></span>
          </div>
        </div>
      </div>
      <div class="col-span-8 bg-white p-6 rounded-xl shadow h-[360px] overflow-y-auto">
        <h2 class="text-xl font-semibold mb-4"><i class="bi bi-bell mr-2"></i> Notifikasi Terbaru</h2>
        <ul class="space-y-2 text-sm">
            <li class="p-3 bg-gray-50 rounded shadow-sm">
              <i class="bi bi-bell-fill text-[#31c594] mr-1"></i>
              <div class="text-xs text-gray-400"></div>
            </li>
            <li class="italic text-gray-400">Belum ada notifikasi.</li>
        </ul>
      </div>
    </div>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 280,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: ''
      },
      events: [
        {
          title: 'Cek kamar A1',
          start: '{{ now()->toDateString() }}',
          color: '#31c594',
        },
        {
          title: 'Tagihan keluar',
          start: '{{ now()->addDays(3)->toDateString() }}',
          color: '#f97316',
        },
        {
          title: 'Batas Bayar',
          start: '{{ now()->addDays(5)->toDateString() }}',
          color: '#ef4444',
        }
      ]
    });

    calendar.render();
  });
</script>


</html>
