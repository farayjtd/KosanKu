<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Sewa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background: #f4ebe3;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            background: #fffaf4;
        }

        .card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            max-width: 1080px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }

        h2 {
            color: #5e503f;
            margin-bottom: 24px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 18px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #eadbc8;
        }

        th {
            background: #f3e5d8;
            font-weight: bold;
            color: #5e503f;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status-ongoing {
            color: #1a7f5a;
            font-weight: bold;
        }

        .status-finished {
            color: #b45309;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #8b735c;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }
            th, td {
                font-size: 13px;
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>

@include('components.sidebar-landboard')

<div class="main-content">
    <div class="card">
        <h2>Riwayat Sewa Tenant</h2>

        <table>
            <thead>
                <tr>
                    <th>Nama Tenant</th>
                    <th>Kamar</th>
                    <th>Durasi</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $history)
                    @php
                        $start = \Carbon\Carbon::parse($history->start_date);
                        $end = $history->end_date
                            ? \Carbon\Carbon::parse($history->end_date)
                            : $start->copy()->addMonths($history->duration_months);
                        $isFinished = $history->end_date !== null || now()->gte($end);
                    @endphp
                    <tr>
                        <td>{{ $history->tenant->account->name ?? '-' }}</td>
                        <td>{{ $history->room->room_number ?? '-' }}</td>
                        <td>{{ $history->duration_months }} bln</td>
                        <td>{{ $start->format('d-m-Y') }}</td>
                        <td>{{ $end->format('d-m-Y') }}</td>
                        <td>
                            <span class="{{ $isFinished ? 'status-finished' : 'status-ongoing' }}">
                                {{ $isFinished ? 'Selesai' : 'Masih berjalan' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">Tidak ada riwayat sewa ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
