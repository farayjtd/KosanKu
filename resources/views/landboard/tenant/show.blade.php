<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tenant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            display: flex;
            background: #f0f2f5;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 1000px;
            margin: auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        h2 {
            color: #2d3748;
            text-align: center;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-block p {
            margin: 8px 0;
            font-size: 14px;
            color: #374151;
        }

        .info-block p strong {
            color: #111827;
        }

        .back-link {
            margin-top: 30px;
            display: inline-block;
            text-decoration: none;
            color: #3b82f6;
            font-weight: bold;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('components.sidebar-landboard')

<div class="main-content">
    <div class="card">
        <h2>Detail Tenant</h2>

        <div class="info-grid">
            <div class="info-block">
                <p><strong>Username:</strong> {{ $history->tenant->account->username }}</p>
                <p><strong>Nama:</strong> {{ $history->tenant->account->name }}</p>
                <p><strong>Email:</strong> {{ $history->tenant->account->email }}</p>
                <p><strong>No HP:</strong> {{ $history->tenant->account->phone }}</p>
                <p><strong>No HP Alternatif:</strong> {{ $history->tenant->account->alt_phone ?? '-' }}</p>
            </div>
            <div class="info-block">
                <p><strong>Jenis Kelamin:</strong> {{ ucfirst($history->tenant->gender) }}</p>
                <p><strong>Alamat:</strong> {{ $history->tenant->address }}</p>
                <p><strong>Aktivitas:</strong> {{ $history->tenant->activity_type }}</p>
                <p><strong>Institusi:</strong> {{ $history->tenant->institution_name }}</p>
                <p><strong>No. Kamar:</strong> {{ $history->room->room_number }}</p>
                <p><strong>Mulai Sewa:</strong> {{ \Carbon\Carbon::parse($history->start_date)->format('d-m-Y') }}</p>
            </div>
        </div>

        <a href="{{ route('landboard.current-tenants') }}" class="back-link">← Kembali ke daftar tenant</a>
    </div>
</div>

</body>
</html>
