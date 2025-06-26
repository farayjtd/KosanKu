<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tenant yang Menghuni</title>
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
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 960px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }

        h2 {
            color: #5e503f;
            text-align: center;
            margin-bottom: 24px;
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

        .action-link {
            color: #b08968;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .empty {
            text-align: center;
            padding: 24px;
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
            <h2>Daftar Tenant yang Sedang Menghuni</h2>

            @if ($currentTenants->isEmpty())
                <div class="empty">Tidak ada tenant yang sedang menghuni saat ini.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>No. Kamar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currentTenants as $item)
                            <tr>
                                <td>{{ $item->tenant->account->username ?? '-' }}</td>
                                <td>{{ $item->tenant->account->name ?? '-' }}</td>
                                <td>{{ $item->room->room_number ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('landboard.current-tenants.show', $item->id) }}" class="action-link">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</body>
</html>
