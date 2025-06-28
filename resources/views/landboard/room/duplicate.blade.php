<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Duplikat Kamar</title>
</head>
<body class="bg-gray-200 font-sans pb-16">

@include('components.sidebar-landboard')

<div class="flex-1 p-6">
    <div class="bg-white p-8 rounded-xl max-w-xl mx-auto shadow-md">
        <h2 class="text-center text-2xl font-semibold text-black mb-6">Duplikat Kamar Tipe: {{ $room->type }}</h2>

        <div class="bg-[#31c594] border border-[#31c594] text-white p-4 rounded-lg mb-6 text-sm">
            <p>Duplikasi akan menyalin semua data kamar seperti <strong class="text-white">fasilitas, aturan, harga, gender, dan foto</strong>.</p>
            <p class="mt-2">Nomor terakhir: <strong class="text-white">{{ $room->type }}{{ $lastNumber }}</strong><br>
               Kamar baru akan dimulai dari: <strong class="text-white">{{ $room->type }}{{ $lastNumber + 1 }}</strong></p>
        </div>

        <form method="POST" action="{{ route('landboard.rooms.duplicate', $room->id) }}">
            @csrf

            <label class="block font-semibold text-black mt-4">Jumlah Kamar yang Akan Dibuat:</label>
            <input type="number" name="room_quantity" min="1" required class="w-full mt-1 p-3 border border-black rounded-md bg-[#fdfdfb]">

            <button type="submit" class="mt-6 w-full bg-[#31c594] hover:bg-[#1a966d] text-white py-3 rounded-lg text-lg font-semibold">Duplikat Sekarang</button>
        </form>
    </div>
</div>

</body>
</html>
