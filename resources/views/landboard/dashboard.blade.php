<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosanKu - Dashboard Landboard</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            background: #f8f5f2;
        }

        .sidebar {
            width: 240px;
            background: #e6e1dc;
            padding: 20px;
            height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        h2 {
            margin-top: 0;
            color: #5b4636;
            font-size: 1.8em;
        }

        p {
            color: #6b5e53;
            font-size: 15px;
        }

        .section {
            background: #fff;
            padding: 24px;
            margin-top: 24px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.06);
            border: 1px solid #dcd3cc;
        }

        .section h3 {
            margin-top: 0;
            color: #5a4430;
            font-size: 1.4em;
        }

        .section p {
            margin: 12px 0;
            font-size: 15px;
        }

        .penalty-label {
            font-weight: bold;
            color: #4b3a2d;
        }

        .text-muted {
            color: #a6a29b;
            font-style: italic;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .main-content {
                padding: 20px;
            }

            .section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Konten --}}
    <div class="main-content">
        <h2>Selamat datang, {{ Auth::user()->name }}</h2>
        <p>Gunakan menu di samping untuk mengelola kost Anda dengan mudah.</p>

        <div class="section">
            <h3>Pengaturan Denda</h3>

            <p>💸 <span class="penalty-label">Keterlambatan:</span><br>
                @if(Auth::user()->landboard->is_penalty_enabled)
                    Rp {{ number_format(Auth::user()->landboard->late_fee_amount ?? 0) }} / hari<br>
                    <span class="text-muted">Didenda setelah {{ Auth::user()->landboard->late_fee_days ?? 0 }} hari keterlambatan</span>
                @else
                    <span class="text-muted">Tidak aktif</span>
                @endif
            </p>

            <p>🏠 <span class="penalty-label">Pindah Kamar:</span><br>
                @if(Auth::user()->landboard->is_penalty_on_room_change)
                    Rp {{ number_format(Auth::user()->landboard->room_change_penalty_amount ?? 0) }}
                @else
                    <span class="text-muted">Tidak aktif</span>
                @endif
            </p>

            <p>🚪 <span class="penalty-label">Keluar Tengah Jalan:</span><br>
                @if(Auth::user()->landboard->is_penalty_on_moveout)
                    Rp {{ number_format(Auth::user()->landboard->moveout_penalty_amount ?? 0) }}
                @else
                    <span class="text-muted">Tidak aktif</span>
                @endif
            </p>
        </div>
    </div>

</body>
</html>
