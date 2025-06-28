<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Sewa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="font-sans pb-6 m-0 flex flex-col min-h-screen bg-gray-200">

@include('components.sidebar-landboard')

<div class="flex-1 p-6 md:p-8">
    <div class="max-w-6xl mx-auto mb-6">
        <form action="{{ route('landboard.rooms.index') }}" method="GET" class="relative">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tenant..."
                class="w-full pl-12 pr-4 py-3 rounded-xl shadow-md focus:outline-none focus:ring-2 focus:ring-[#31c594] bg-white">
        </form>
    </div>
    <div class="bg-white rounded-xl shadow-lg max-w-6xl mx-auto p-8">
        <h2 class="text-2xl font-bold text-black text-center mb-6">Riwayat Sewa Tenant</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-[#31c594] text-white">
                        <th class="py-3 px-4 font-bold">Nama Tenant</th>
                        <th class="py-3 px-4 font-bold">Kamar</th>
                        <th class="py-3 px-4 font-bold">Durasi</th>
                        <th class="py-3 px-4 font-bold">Mulai</th>
                        <th class="py-3 px-4 font-bold">Selesai</th>
                        <th class="py-3 px-4 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-700">
                    @forelse ($histories as $history)
                        @php
                            $start = \Carbon\Carbon::parse($history->start_date);
                            $end = $history->end_date
                                ? \Carbon\Carbon::parse($history->end_date)
                                : $start->copy()->addMonths($history->duration_months);
                            $isFinished = $history->end_date !== null || now()->gte($end);
                        @endphp
                        <tr class="border-b border-[#eadbc8] last:border-b-0">
                            <td class="py-3 px-4">{{ $history->tenant->account->name ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $history->room->room_number ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $history->duration_months }} bln</td>
                            <td class="py-3 px-4">{{ $start->format('d-m-Y') }}</td>
                            <td class="py-3 px-4">{{ $end->format('d-m-Y') }}</td>
                            <td class="py-3 px-4 font-semibold">
                                <span class="{{ $isFinished ? 'text-yellow-600' : 'text-green-700' }}">
                                    {{ $isFinished ? 'Selesai' : 'Masih berjalan' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 italic text-[#8b735c]">Tidak ada riwayat sewa ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
