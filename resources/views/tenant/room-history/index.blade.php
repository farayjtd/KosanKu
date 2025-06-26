<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Sewa Tenant</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            display: flex;
            background: #f1f5f9;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            background: #f8fafc;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        h2 {
            margin-top: 0;
            color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #e2e8f0;
            color: #334155;
            font-weight: bold;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .empty {
            color: #64748b;
            font-style: italic;
            margin-top: 16px;
        }

        .status-upcoming {
            color: #2563eb;
            font-weight: bold;
        }

        .status-ongoing {
            color: #16a34a;
            font-weight: bold;
        }

        .status-finished {
            color: #dc2626;
            font-weight: bold;
        }

        .decision-box {
            background: #e0f2fe;
            border-left: 5px solid #0284c7;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .decision-box form {
            margin-top: 12px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        select, button {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
        }

        .btn-success {
            background-color: #16a34a;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
        }

        .btn-success:hover {
            background-color: #15803d;
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-warning {
            background-color: #fef9c3;
            color: #92400e;
        }
    </style>
</head>
<body>
    @include('components.sidebar-tenant')

    <div class="main-content">
        <div class="card">
            <h2>Riwayat Sewa Kamar</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(isset($showDecisionForm) && $showDecisionForm && $activeRental)
                @php
                    $endDate = $activeRental->end_date
                        ? \Carbon\Carbon::parse($activeRental->end_date)
                        : \Carbon\Carbon::parse($activeRental->start_date)->addMonths($activeRental->duration_months);
                    $paymentStatus = optional($activeRental->payment)->status;
                @endphp

                <div class="decision-box">
                    <strong>Sewa Anda akan berakhir pada {{ $endDate->format('d M Y') }}</strong>
                    <p>Silakan pilih apakah Anda ingin melanjutkan sewa atau keluar dari kamar.</p>

                    @if($paymentStatus !== 'paid')
                        <div class="alert alert-warning">
                            Anda belum menyelesaikan pembayaran sewa saat ini. Anda hanya bisa melanjutkan sewa jika tagihan sudah lunas.
                        </div>
                    @else
                        <form method="POST" action="{{ route('tenant.decide') }}">
                            @csrf
                            <input type="hidden" name="is_continue" value="1">
                            <label for="duration_months">Durasi:</label>
                            <select name="duration_months" required>
                                <option value="1">1 Bulan</option>
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">12 Bulan</option>
                            </select>
                            <button type="submit" class="btn-success">Lanjutkan</button>
                        </form>
                    @endif
                </div>
            @endif

            @if($activeRental)
                <form method="POST" action="{{ route('tenant.leave-room') }}" style="margin-bottom: 20px;">
                    @csrf
                    <button type="submit" class="btn-danger"
                        onclick="return confirm('Yakin ingin berhenti sewa dan keluar dari kamar?')">
                        Keluar dari Kamar
                    </button>
                </form>
            @endif

            @if ($histories->isEmpty())
                <p class="empty">Belum ada riwayat sewa.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kos</th>
                            <th>Nomor Kamar</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Durasi</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status Sewa</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $history)
                            @php
                                $start = \Carbon\Carbon::parse($history->start_date);
                                $end = $history->end_date
                                    ? \Carbon\Carbon::parse($history->end_date)
                                    : $start->copy()->addMonths($history->duration_months);

                                $now = now();
                                $statusSewa = 'Selesai';
                                $statusClass = 'status-finished';

                                if ($now->lt($start)) {
                                    $statusSewa = 'Mendatang';
                                    $statusClass = 'status-upcoming';
                                } elseif ($now->between($start, $end)) {
                                    $statusSewa = 'Berjalan';
                                    $statusClass = 'status-ongoing';
                                }

                                $paymentStatus = $history->payment?->status === 'paid' ? 'Lunas' : 'Belum Lunas';
                                $paymentClass = $history->payment?->status === 'paid' ? 'status-ongoing' : 'status-finished';
                            @endphp
                            <tr>
                                <td>{{ $history->room->landboard->kost_name ?? '-' }}</td>
                                <td>{{ $history->room->room_number ?? '-' }}</td>
                                <td>{{ $history->room->type ?? '-' }}</td>
                                <td>Rp{{ number_format($history->room->price ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $history->duration_months }} bln</td>
                                <td>{{ $start->format('d-m-Y') }}</td>
                                <td>{{ $end->format('d-m-Y') }}</td>
                                <td><span class="{{ $statusClass }}">{{ $statusSewa }}</span></td>
                                <td><span class="{{ $paymentClass }}">{{ $paymentStatus }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if(session('show_invoice') && session('rental_id'))
                @php
                    $rental = \App\Models\RentalHistory::with('room')->find(session('rental_id'));
                @endphp

                @if($rental)
                    <div class="alert alert-warning" style="margin-top: 24px;">
                        <strong>Tagihan Baru</strong><br>
                        Kamar: {{ $rental->room->room_number }}<br>
                        Harga: Rp{{ number_format($rental->room->price, 0, ',', '.') }}<br>
                        Durasi: {{ $rental->duration_months }} bulan<br>
                        Total: <strong>Rp{{ number_format($rental->room->price * $rental->duration_months, 0, ',', '.') }}</strong><br><br>

                        <form action="{{ route('tenant.invoice.pay', ['rentalId' => $rental->id]) }}" method="POST">
                            @csrf
                            <button class="btn-success" type="submit">Bayar Sekarang</button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
</body>
</html>
