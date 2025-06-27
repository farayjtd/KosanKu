<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Detail Kamar</title>
    <style>
        .fade {
            opacity: 0;
            transition: opacity 0.7s ease-in-out;
        }
        .fade.active {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    @include('components.sidebar-landboard')

    <div class="flex-1 p-6">
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="w-full h-56 bg-gray-200 relative overflow-hidden">
                @if($room->photos->isNotEmpty())
                    <div id="slider" class="relative w-full h-full">
                        @foreach ($room->photos as $index => $photo)
                            <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out fade {{ $index === 0 ? 'active' : '' }}">
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center h-full text-gray-600">Tidak ada foto tersedia.</div>
                @endif
            </div>
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Kamar: {{ $room->room_number }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <p><strong class="text-gray-900">Tipe:</strong> {{ $room->type }}</p>
                    <p><strong class="text-gray-900">Harga:</strong> Rp{{ number_format($room->price, 0, ',', '.') }}</p>
                    <p><strong class="text-gray-900">Gender:</strong> {{ ucfirst($room->gender_type) }}</p>
                    <p><strong class="text-gray-900">Status:</strong> {{ ucfirst($room->status) }}</p>
                </div>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-2">Fasilitas</h3>
                        @if($room->facilities->isEmpty())
                            <p class="text-sm text-gray-600">Tidak ada fasilitas ditambahkan.</p>
                        @else
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                @foreach ($room->facilities as $facility)
                                    <li>{{ $facility->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-2">Aturan</h3>
                        @if($room->rules->isEmpty())
                            <p class="text-sm text-gray-600">Tidak ada aturan ditentukan.</p>
                        @else
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                @foreach ($room->rules as $rule)
                                    <li>{{ $rule->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="mt-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Semua Foto</h3>
                    @if($room->photos->isNotEmpty())
                        <div class="flex flex-wrap gap-3">
                            @foreach ($room->photos as $photo)
                                <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Tambahan" class="w-28 h-20 object-cover rounded-lg border">
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-600">Tidak ada foto tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('#slider img');
            let current = 0;
            if (images.length > 0) {
                setInterval(() => {
                    images[current].classList.remove('active');
                    current = (current + 1) % images.length;
                    images[current].classList.add('active');
                }, 5000);
            }
        });
    </script>
</body>
</html>
