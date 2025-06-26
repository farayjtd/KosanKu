<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tenant</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            display: flex;
            background: #f0f2f5;
        }
        .sidebar {
            width: 250px;
            background: #e2e8f0;
            padding: 20px;
            height: 100vh;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        h2, h3 { margin-top: 0; }
        p { margin: 6px 0; }
        .label { font-weight: bold; }
        img {
            margin-top: 10px;
            border-radius: 8px;
        }
        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-pay {
            background: #3182ce;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        select {
            padding: 8px;
            border-radius: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    @include('components.sidebar-tenant')

    <div class="main-content">
        <div class="card">
            <h2>Selamat datang, {{ $tenant->account->name }}</h2>
            @if (session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @elseif (session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif
        </div>

        @if (is_null($activeRental))
            <div class="card">
                <h3>Masukkan Token untuk Klaim Kamar</h3>
                <form method="POST" action="{{ route('tokens.use') }}">
                    @csrf
                    <label for="token">Masukkan Token:</label>
                    <input type="text" name="token" id="token" maxlength="6" required>
                    <button type="submit">Gunakan Token</button>
                </form>
            </div>
        @endif

        @if ($activeRental)
            <form action="{{ route('tenant.leave-room') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari kamar sekarang?')">
                @csrf
                <button type="submit" class="btn-danger">Keluar Kamar</button>
            </form>
        @endif

        <div class="card">
            <h3>Profil Akun</h3>
            <p><span class="label">Nama:</span> {{ $tenant->account->name }}</p>
            <p><span class="label">Username:</span> {{ $tenant->account->username }}</p>
            <p><span class="label">Email:</span> {{ $tenant->account->email }}</p>
            <p><span class="label">No HP:</span> {{ $tenant->account->phone }}</p>
            <p><span class="label">No HP Alternatif:</span> {{ $tenant->account->alt_phone }}</p>
            @if ($tenant->account->avatar)
                <img src="{{ asset('storage/' . $tenant->account->avatar) }}" alt="Foto Akun" width="200">
            @endif
        </div>

        <div class="card">
            <h3>Profil Tenant</h3>
            <p><span class="label">Jenis Kelamin:</span> {{ ucfirst($tenant->gender) }}</p>
            <p><span class="label">Alamat:</span> {{ $tenant->address }}</p>
            <p><span class="label">Tipe Aktivitas:</span> {{ $tenant->activity_type }}</p>
            <p><span class="label">Institusi:</span> {{ $tenant->institution_name }}</p>
            @if ($tenant->profile_photo)
                <img src="{{ asset('storage/' . $tenant->profile_photo) }}" alt="Foto Profil Tenant" width="200">
            @endif
        </div>

        @if ($activeRental && $activeRental->room)
            <div class="card">
                <h3>Informasi Kamar</h3>
                <p><span class="label">Nomor Kamar:</span> {{ $activeRental->room->room_number }}</p>
                <p><span class="label">Tipe:</span> {{ $activeRental->room->type }}</p>
                <p><span class="label">Harga:</span> Rp{{ number_format($activeRental->room->price, 0, ',', '.') }}</p>
                <p><span class="label">Gender:</span> {{ ucfirst($activeRental->room->gender_type) }}</p>
                <p><span class="label">Fasilitas:</span>
                    @forelse ($activeRental->room->facilities as $facility)
                        {{ $facility->name }}@if (!$loop->last), @endif
                    @empty Tidak ada
                    @endforelse
                </p>
                <p><span class="label">Aturan:</span>
                    @forelse ($activeRental->room->rules as $rule)
                        {{ $rule->name }}@if (!$loop->last), @endif
                    @empty Tidak ada
                    @endforelse
                </p>
                <div style="margin-top: 10px;">
                    <span class="label">Foto Kamar:</span><br>
                    @forelse ($activeRental->room->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Kamar" width="200" style="margin: 5px;">
                    @empty
                        <p>Tidak ada foto kamar.</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <h3>Informasi Landboard</h3>
                <p><span class="label">Nama Landboard:</span> {{ $activeRental->room->landboard->account->name ?? '-' }}</p>
                <p><span class="label">Username:</span> {{ $activeRental->room->landboard->account->username ?? '-' }}</p>
                <p><span class="label">Email:</span> {{ $activeRental->room->landboard->account->email ?? '-' }}</p>
                <p><span class="label">No HP:</span> {{ $activeRental->room->landboard->account->phone ?? '-' }}</p>
            </div>

            <div class="card">
                <h3>Pembayaran Sewa</h3>
                @php
                    $payment = $activeRental->payment;
                    $startDate = $activeRental->start_date ? \Carbon\Carbon::parse($activeRental->start_date) : null;
                    $isLate = $startDate && ($activeRental->room->landboard->is_penalty_enabled ?? false)
                        ? now()->gt($startDate->clone()->addDays($activeRental->room->landboard->late_fee_days ?? 0))
                        : false;
                    $lateFee = $isLate ? ($activeRental->room->landboard->late_fee_amount ?? 0) : 0;
                    $totalAmount = $activeRental->duration_months * $activeRental->room->price + $lateFee;
                @endphp

                <p><strong>Durasi Sewa:</strong> {{ $activeRental->duration_months }} bulan</p>
                <p><strong>Denda Keterlambatan:</strong> Rp{{ number_format($lateFee, 0, ',', '.') }}</p>
                <p><strong>Total Tagihan:</strong> Rp{{ number_format($totalAmount, 0, ',', '.') }}</p>

                <p><strong>Status Pembayaran:</strong>
                    @if ($payment && $payment->status === 'paid')
                        <span style="color: green; font-weight: bold;">Lunas</span>
                    @else
                        <span style="color: red; font-weight: bold;">Belum Dibayar</span>
                    @endif
                </p>

                @if (!$payment || $payment->status !== 'paid')
                    <form method="POST" action="{{ route('tenant.invoice.pay', $activeRental->id) }}">
                        @csrf
                        <label for="payment_method">Pilih Metode Pembayaran:</label>
                        <select name="payment_method" required>
                            <option value="">-- Pilih --</option>
                            <option value="QRIS">QRIS</option>
                            <option value="BNIVA">BNI VA</option>
                            <option value="BRIVA">BRI VA</option>
                            <option value="MANDIRIVA">Mandiri VA</option>
                            <option value="BCAVA">BCA VA</option>
                            <option value="PERMATAVA">Permata VA</option>
                            <option value="MUAMALATVA">Muamalat VA</option>
                            <option value="OVO">OVO</option>
                            <option value="DANA">DANA</option>
                            <option value="SHOPEEPAY">ShopeePay</option>
                            <option value="ALFAMART">Alfamart</option>
                            <option value="INDOMARET">Indomaret</option>
                        </select>
                       <form method="POST" action="{{ route('tenant.invoice.pay', $activeRental->id) }}">
                            @csrf
                            <button type="submit" ...>Bayar Sekarang</button>
                        </form>

                    </form>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
