<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Kamar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

        h2 {
            margin-bottom: 20px;
            color: #5a4430;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            width: 260px;
            background: #fffaf6;
            border: 1px solid #e0dcd5;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .photo-carousel {
            position: relative;
            width: 100%;
            height: 150px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .carousel-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: none;
        }

        .carousel-img.active {
            display: block;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.4);
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .carousel-btn.prev { left: 6px; }
        .carousel-btn.next { right: 6px; }

        .status {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
        }

        .status.available { background: #10b981; }
        .status.booked { background: #ef4444; }

        .button,
        button {
            margin-top: 6px;
            display: inline-block;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .button:hover,
        button:hover {
            background: #2563eb;
        }

        .delete-btn {
            background: #ef4444;
        }

        .delete-btn:hover {
            background: #b91c1c;
        }

        form {
            margin-top: 10px;
        }

        .info-token {
            font-size: 12px;
            margin-top: 8px;
            color: #5a4430;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 6px 8px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #d6ccc2;
            font-size: 14px;
        }

        p {
            margin: 4px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
@include('components.sidebar-landboard')

<div class="main-content">
    <h2>Daftar Kamar</h2>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @elseif (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <div class="card-container">
        @foreach ($rooms as $room)
            <div class="card">
                <!-- Carousel -->
                <div class="photo-carousel" data-room-id="{{ $room->id }}">
                    @forelse ($room->photos as $index => $photo)
                        <img src="{{ asset('storage/' . $photo->path) }}"
                             class="carousel-img {{ $index === 0 ? 'active' : '' }}"
                             data-index="{{ $index }}">
                    @empty
                        <img src="https://via.placeholder.com/240x140?text=No+Image" class="carousel-img active">
                    @endforelse

                    @if ($room->photos->count() > 1)
                        <button class="carousel-btn prev" onclick="prevPhoto({{ $room->id }})">&#10094;</button>
                        <button class="carousel-btn next" onclick="nextPhoto({{ $room->id }})">&#10095;</button>
                    @endif
                </div>

                <h4>{{ $room->room_number }}</h4>
                <p><strong>{{ $room->type }}</strong></p>
                <p>Rp{{ number_format($room->price, 0, ',', '.') }}</p>
                <p>Gender: {{ ucfirst($room->gender_type) }}</p>
                <p>Status: <span class="status {{ $room->status }}">{{ ucfirst($room->status) }}</span></p>

                @php
                    $token = $room->token()->where('used', false)->where('expires_at', '>', now())->first();
                @endphp

                @if ($token)
                    <div class="info-token">
                        Token: <strong>{{ $token->token }}</strong><br>
                        Expire dalam: <span id="timer-{{ $room->id }}" data-expires="{{ $token->expires_at->toIso8601String() }}"></span>
                    </div>

                    <form action="{{ route('tokens.use', $room->id) }}" method="POST">
                        @csrf
                        <input type="text" name="token" placeholder="Masukkan Token" maxlength="6" required>
                        <button type="submit">Gunakan Token</button>
                    </form>
                @endif

                <!-- Generate Token -->
                <form action="{{ route('tokens.generate', $room->id) }}" method="POST">
                    @csrf
                    <select name="rental_duration" required>
                        <option value="">Durasi</option>
                        <option value="1">1 bulan</option>
                        <option value="3">3 bulan</option>
                        <option value="6">6 bulan</option>
                        <option value="12">12 bulan</option>
                    </select>
                    <button type="submit" {{ $room->status === 'booked' ? 'disabled' : '' }}>Generate Token</button>
                </form>

                <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" class="button">Duplikat</a>
                <a href="{{ route('landboard.rooms.show', $room->id) }}" class="button">Detail</a>
                <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" class="button">Edit</a>

                <form action="{{ route('landboard.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<script>
    function nextPhoto(roomId) {
        const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
        const images = container.querySelectorAll('.carousel-img');
        let activeIndex = [...images].findIndex(img => img.classList.contains('active'));
        images[activeIndex].classList.remove('active');
        const nextIndex = (activeIndex + 1) % images.length;
        images[nextIndex].classList.add('active');
    }

    function prevPhoto(roomId) {
        const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
        const images = container.querySelectorAll('.carousel-img');
        let activeIndex = [...images].findIndex(img => img.classList.contains('active'));
        images[activeIndex].classList.remove('active');
        const prevIndex = (activeIndex - 1 + images.length) % images.length;
        images[prevIndex].classList.add('active');
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
