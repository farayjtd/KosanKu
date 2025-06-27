<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Daftar Kamar</title>
    <style>
        .dropdown { display: none; }
        .dropdown.show { display: block; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
@include('components.sidebar-landboard')

<div class="max-w-7xl mx-auto mt-6 px-4 pb-24">
    <div class="sticky top-0 z-40 p-2 mb-4 rounded-xl">
        <form action="{{ route('landboard.rooms.index') }}" method="GET" class="relative">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder= "Cari kamar..."
                class="w-full pl-10 py-2 rounded-xl shadow-md focus:outline-none focus:ring-2 focus:ring-[#31c594]">
        </form>
    </div>
    <div class="flex h-[calc(100vh-5rem)] gap-6 overflow-hidden">
        <div class="w-1/3 flex flex-col gap-4 self-stretch overflow-y-auto pr-1">
            @php
                $grouped = $rooms->groupBy('type');
            @endphp

            @foreach ($grouped as $type => $roomGroup)
                @php
                    $tersedia = $roomGroup->where('status', 'available')->count();
                    $terpakai = $roomGroup->where('status', 'booked')->count();
                @endphp
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-center text-lg font-bold text-gray-800 mb-3 border-b pb-2">{{ strtoupper($type) }}</div>
                    <div class="flex justify-between text-center text-sm font-semibold text-white">
                        <div class="bg-green-500 w-1/2 py-3 rounded-l-lg">
                            <p>Available</p>
                            <p class="text-xl">{{ $tersedia }}</p>
                        </div>
                        <div class="bg-red-500 w-1/2 py-3 rounded-r-lg">
                            <p>Booked</p>
                            <p class="text-xl">{{ $terpakai }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-2/3 bg-white rounded-xl shadow p-4 overflow-y-auto">
            @if (session('success'))
                <p class="text-green-600 font-semibold my-2">{{ session('success') }}</p>
            @elseif (session('error'))
                <p class="text-red-600 font-semibold my-2">{{ session('error') }}</p>
            @endif
            <div class="flex flex-wrap gap-4 mt-4" id="room-list">
                @foreach ($rooms as $index => $room)
                    <div class="w-full sm:w-[48%] bg-white rounded-xl p-6 shadow relative {{ $index >= 4 ? 'hidden extra-room' : '' }}">
                        <div class="absolute top-2 right-2">
                            <button class="text-xl kebab-toggle" data-room="{{ $room->id }}">⋮</button>
                            <div class="dropdown absolute right-0 mt-2 w-32 bg-white border rounded shadow z-10 p-1 kebab-menu" data-room="{{ $room->id }}">
                                <a href="{{ route('landboard.rooms.show', $room->id) }}" class="block px-2 py-1 text-sm hover:bg-gray-100"><i class="bi bi-info-circle mr-2"></i>Detail</a>
                                <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" class="block px-2 py-1 text-sm hover:bg-gray-100"><i class="bi bi-copy mr-2"></i>Duplikat</a>
                                <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" class="block px-2 py-1 text-sm hover:bg-gray-100"><i class="bi bi-house-gear mr-2"></i>Edit</a>
                                <button type="button"
                                    onclick="showDeleteModal('{{ $room->id }}')"
                                    class="block w-full text-left px-2 py-1 text-sm hover:bg-red-100 text-red-600">
                                    <i class="bi bi-trash mr-2"></i>Hapus
                                </button>
                            </div>
                        </div>
                        <div class="relative w-full h-36 overflow-hidden rounded-md mb-3 photo-carousel" data-room-id="{{ $room->id }}">
                            @forelse ($room->photos as $index => $photo)
                                <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-36 object-cover {{ $index === 0 ? 'block' : 'hidden' }} carousel-img">
                            @empty
                                <img src="/public/assets/room-sample.jpg" class="w-full h-36 object-cover block">
                            @endforelse

                            @if ($room->photos->count() > 1)
                                <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 rounded text-sm carousel-btn prev" data-room="{{ $room->id }}">‹</button>
                                <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 rounded text-sm carousel-btn next" data-room="{{ $room->id }}">›</button>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-bold">{{ $room->room_number }}</h4>
                            <span class="text-xs px-2 py-1 text-white rounded {{ $room->status === 'available' ? 'bg-green-500' : 'bg-red-500' }}">{{ ucfirst($room->status) }}</span>
                        </div>
                        <p class="text-sm font-semibold">{{ $room->type }}</p>
                        <p class="text-sm">Rp{{ number_format($room->price, 0, ',', '.') }}</p>
                        <p class="text-sm mb-2">Gender: {{ ucfirst($room->gender_type) }}</p>

                        @php
                            $token = $room->token()->where('used', false)->where('expires_at', '>', now())->first();
                        @endphp

                        @if ($token)
                            <div class="text-xs mt-2 text-black">
                                Token: <strong class="animate-pulse text-yellow-500">{{ $token->token }}</strong><br>
                                Expire in: <span class="text-red-500" id="timer-{{ $room->id }}" data-expires="{{ $token->expires_at->toIso8601String() }}"></span>
                            </div>
                            <form action="{{ route('tokens.use', $room->id) }}" method="POST" class="mt-2">
                                @csrf
                                <input type="text" name="token" placeholder="Masukkan Token" maxlength="6" required class="w-full mt-1 px-3 py-1 border rounded text-sm">
                                <button type="submit" class="mt-2 w-full bg-[#31c594] hover:bg-[#1a966d] text-white py-1 rounded text-sm">Gunakan Token</button>
                            </form>
                        @else
                            <form action="{{ route('tokens.generate', $room->id) }}" method="POST" class="mt-2">
                                @csrf
                                <select name="rental_duration" required class="w-full mt-1 px-2 py-1 border rounded text-sm">
                                    <option value="">Durasi</option>
                                    <option value="1">1 bulan</option>
                                    <option value="3">3 bulan</option>
                                    <option value="6">6 bulan</option>
                                    <option value="12">12 bulan</option>
                                </select>
                                <button type="submit" class="mt-2 w-full bg-[#31c594] hover:bg-[#1a966d] text-white py-1 rounded text-sm" {{ $room->status === 'booked' ? 'disabled' : '' }}>Generate Token</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <button onclick="toggleRooms()" id="toggle-btn" class="text-sm text-[#31c594] hover:underline">Tampilkan Semua</button>
            </div>
        </div>
    </div>
</div>
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-96 p-6">
        <h2 class="text-lg font-bold mb-4 text-gray-800">Hapus Kamar</h2>
        <p class="text-sm text-gray-700 mb-4">Apakah anda yakin ingin menghapus kamar ini<br>secara permanent?</p>
        <form id="fullDeleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded mb-2">
                Ya, saya yakin
            </button>
        </form>
        <button onclick="closeModal()" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 rounded">
            Batal
        </button>
    </div>
</div>

<script>
    document.querySelectorAll('.carousel-btn.next').forEach(btn => {
        btn.addEventListener('click', () => {
            const container = btn.closest('.photo-carousel');
            const images = container.querySelectorAll('.carousel-img');
            let current = [...images].findIndex(img => !img.classList.contains('hidden'));
            images[current].classList.add('hidden');
            images[(current + 1) % images.length].classList.remove('hidden');
        });
    });

    document.querySelectorAll('.carousel-btn.prev').forEach(btn => {
        btn.addEventListener('click', () => {
            const container = btn.closest('.photo-carousel');
            const images = container.querySelectorAll('.carousel-img');
            let current = [...images].findIndex(img => !img.classList.contains('hidden'));
            images[current].classList.add('hidden');
            images[(current - 1 + images.length) % images.length].classList.remove('hidden');
        });
    });

    function toggleRooms() {
        const extra = document.querySelectorAll('.extra-room');
        const btn = document.getElementById('toggle-btn');
        const hidden = [...extra].some(el => el.classList.contains('hidden'));
        extra.forEach(el => el.classList.toggle('hidden', !hidden));
        btn.innerText = hidden ? 'Sembunyikan' : 'Tampilkan Semua';
    }

    document.querySelectorAll('.kebab-toggle').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const roomId = btn.dataset.room;
            const menu = document.querySelector(`.kebab-menu[data-room="${roomId}"]`);
            document.querySelectorAll('.kebab-menu').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            menu.classList.toggle('show');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.kebab-menu').forEach(m => m.classList.remove('show'));
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[id^="timer-"]').forEach(el => {
            const expiresAt = new Date(el.dataset.expires).getTime();
            const interval = setInterval(() => {
                const now = Date.now();
                const distance = expiresAt - now;
                if (distance <= 0) {
                    el.textContent = 'Expired';
                    clearInterval(interval);
                } else {
                    const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((distance % (1000 * 60)) / 1000);
                    el.textContent = `${m}m ${s}s`;
                }
            }, 1000);
        });
    });
    function showDeleteModal(roomId) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');

        const fullDeleteForm = document.getElementById('fullDeleteForm');
        const archiveForm = document.getElementById('archiveForm');

        // Update action URL sesuai rute Laravel
        fullDeleteForm.action = `/landboard/rooms/${roomId}`;
        archiveForm.action = `/landboard/rooms/${roomId}/archive`;
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
</body>
</html>
