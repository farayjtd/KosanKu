<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Daftar Kamar</title>
</head>
<body class="bg-[#f5f3f0] min-h-screen">
@include('components.sidebar-landboard')

<div class="main-content p-8">
    <h2 class="text-2xl font-bold text-[#5a4430] mb-6">Daftar Kamar</h2>

    @if (session('success'))
        <p class="text-green-600 font-semibold">{{ session('success') }}</p>
    @elseif (session('error'))
        <p class="text-red-600 font-semibold">{{ session('error') }}</p>
    @endif

    <div class="flex flex-wrap gap-6">
        @foreach ($rooms as $room)
            <div class="w-64 bg-[#fffaf6] border border-[#e0dcd5] rounded-xl p-4 shadow-md">
                <!-- Carousel -->
                <div class="relative w-full h-36 overflow-hidden rounded-md mb-3" data-room-id="{{ $room->id }}">
                    @forelse ($room->photos as $index => $photo)
                        <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-36 object-cover {{ $index === 0 ? 'block' : 'hidden' }} carousel-img" data-index="{{ $index }}">
                    @empty
                        <img src="https://via.placeholder.com/240x140?text=No+Image" class="w-full h-36 object-cover block carousel-img">
                    @endforelse

                    @if ($room->photos->count() > 1)
                        <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 rounded-md text-sm carousel-btn prev" data-room="{{ $room->id }}">‹</button>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-2 rounded-md text-sm carousel-btn next" data-room="{{ $room->id }}">›</button>
                    @endif
                </div>

                <h4 class="text-lg font-bold">{{ $room->room_number }}</h4>
                <p class="text-sm font-semibold text-gray-600">{{ $room->type }}</p>
                <p class="text-sm">Rp{{ number_format($room->price, 0, ',', '.') }}</p>
                <p class="text-sm">Gender: {{ ucfirst($room->gender_type) }}</p>
                <p class="text-sm">Status: <span class="px-2 py-1 text-white rounded text-xs {{ $room->status === 'available' ? 'bg-green-500' : 'bg-red-500' }}">{{ ucfirst($room->status) }}</span></p>

                @php
                    $token = $room->token()->where('used', false)->where('expires_at', '>', now())->first();
                @endphp

                @if ($token)
                    <div class="text-xs mt-2 text-[#5a4430]">
                        Token: <strong>{{ $token->token }}</strong><br>
                        Expire dalam: <span id="timer-{{ $room->id }}" data-expires="{{ $token->expires_at->toIso8601String() }}"></span>
                    </div>

                    <form action="{{ route('tokens.use', $room->id) }}" method="POST" class="mt-2">
                        @csrf
                        <input type="text" name="token" placeholder="Masukkan Token" maxlength="6" required class="w-full mt-1 px-3 py-1 border rounded text-sm">
                        <button type="submit" class="mt-2 w-full bg-blue-500 hover:bg-blue-600 text-white py-1 rounded text-sm">Gunakan Token</button>
                    </form>
                @endif

                <form action="{{ route('tokens.generate', $room->id) }}" method="POST" class="mt-2">
                    @csrf
                    <select name="rental_duration" required class="w-full mt-1 px-2 py-1 border rounded text-sm">
                        <option value="">Durasi</option>
                        <option value="1">1 bulan</option>
                        <option value="3">3 bulan</option>
                        <option value="6">6 bulan</option>
                        <option value="12">12 bulan</option>
                    </select>
                    <button type="submit" class="mt-2 w-full bg-blue-500 hover:bg-blue-600 text-white py-1 rounded text-sm" {{ $room->status === 'booked' ? 'disabled' : '' }}>Generate Token</button>
                </form>

                <div class="flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm">Duplikat</a>
                    <a href="{{ route('landboard.rooms.show', $room->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm">Detail</a>
                    <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm">Edit</a>
                </div>

                <form action="{{ route('landboard.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white py-1 rounded text-sm">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.querySelectorAll('.carousel-btn.next').forEach(btn => {
    btn.addEventListener('click', () => {
        const roomId = btn.dataset.room;
        nextPhoto(roomId);
    });
});

document.querySelectorAll('.carousel-btn.prev').forEach(btn => {
    btn.addEventListener('click', () => {
        const roomId = btn.dataset.room;
        prevPhoto(roomId);
    });
});

    function nextPhoto(roomId) {
        const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
        const images = container.querySelectorAll('.carousel-img');
        let activeIndex = [...images].findIndex(img => !img.classList.contains('hidden'));
        images[activeIndex].classList.add('hidden');
        const nextIndex = (activeIndex + 1) % images.length;
        images[nextIndex].classList.remove('hidden');
    }

    function prevPhoto(roomId) {
        const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
        const images = container.querySelectorAll('.carousel-img');
        let activeIndex = [...images].findIndex(img => !img.classList.contains('hidden'));
        images[activeIndex].classList.add('hidden');
        const prevIndex = (activeIndex - 1 + images.length) % images.length;
        images[prevIndex].classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[id^="timer-"]').forEach(function (el) {
            const expiresAt = new Date(el.dataset.expires).getTime();
            const updateTimer = () => {
                const now = Date.now();
                const distance = expiresAt - now;
                if (distance <= 0) {
                    el.textContent = 'Expired';
                    clearInterval(interval);
                } else {
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    el.textContent = `${minutes}m ${seconds}s`;
                }
            };
            updateTimer();
            const interval = setInterval(updateTimer, 1000);
        });
    });
</script>
</body>
</html>
