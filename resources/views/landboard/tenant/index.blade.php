<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tenant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-200 font-sans pb-6 m-0 flex flex-col min-h-screen">
    @include('components.sidebar-landboard')
    <div class="flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto mb-6">
            <form action="{{ route('landboard.rooms.index') }}" method="GET" class="relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tenant..."
                    class="w-full pl-12 pr-4 py-3 rounded-xl shadow-md focus:outline-none focus:ring-2 focus:ring-[#31c594] bg-white">
            </form>
        </div>
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">
            <div class="w-full lg:w-1/3 grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center text-center">
                    <h3 class="text-lg font-semibold text-gray-700">Belum Bayar</h3>
                    <p class="text-2xl font-bold text-red-500 mt-2">{{ $belumBayar ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center text-center">
                    <h3 class="text-lg font-semibold text-gray-700">Sudah Lunas</h3>
                    <p class="text-2xl font-bold text-green-500 mt-2">{{ $sudahLunas ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center text-center">
                    <h3 class="text-lg font-semibold text-gray-700">Total Tenant</h3>
                    <p class="text-2xl font-bold text-blue-500 mt-2">{{ $currentTenants->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center text-center">
                    <h3 class="text-lg font-semibold text-gray-700">Pelanggaran</h3>
                    <p class="text-2xl font-bold text-yellow-500 mt-2">{{ $jumlahPelanggaran ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full lg:w-2/3 bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-2xl font-semibold text-black text-center mb-6">Daftar Tenant yang Sedang Menghuni</h2>
                @if ($currentTenants->isEmpty())
                    <div class="text-center text-red-500 italic py-6">Tidak ada tenant yang sedang menghuni saat ini.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse bg-white rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-[#31c594] text-black font-bold text-sm">
                                    <th class="px-5 py-3 text-left">Username</th>
                                    <th class="px-5 py-3 text-left">Nama</th>
                                    <th class="px-5 py-3 text-left">No. Kamar</th>
                                    <th class="px-5 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                @foreach ($currentTenants as $item)
                                    <tr class="border-b border-gray-200 last:border-b-0">
                                        <td class="px-5 py-3">{{ $item->tenant->account->username ?? '-' }}</td>
                                        <td class="px-5 py-3">{{ $item->tenant->account->name ?? '-' }}</td>
                                        <td class="px-5 py-3">{{ $item->room->room_number ?? '-' }}</td>
                                        <td class="px-5 py-3">
                                            <a href="{{ route('landboard.current-tenants.show', $item->id) }}" class="text-[#31c594] font-semibold hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
