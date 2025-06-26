<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Penalti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background: #f5f3f0;
        }

        .sidebar {
            width: 250px;
            background: #e6e1dc;
            padding: 20px;
            height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .card {
            background: #fffaf6;
            padding: 25px;
            border-radius: 12px;
            max-width: 720px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }

        h2 {
            margin-top: 0;
            color: #5a4430;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: 600;
            color: #6b4e3d;
        }

        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #d6ccc2;
            font-size: 14px;
            background: #fdfdfb;
            box-sizing: border-box;
        }

        .form-check {
            margin-top: 16px;
        }

        .form-check input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.2);
        }

        .form-check label {
            font-weight: 500;
            color: #4e3c2a;
        }

        button {
            margin-top: 30px;
            padding: 12px 20px;
            background: #8d735b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        button:hover {
            background: #6e5947;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .card {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Konten --}}
    <div class="main-content">
        <div class="card">
            <h2>Pengaturan Penalti</h2>

            <form method="POST" action="{{ route('penalty.update') }}">
                @csrf
                @method('PATCH')

                {{-- Penalti Keterlambatan --}}
                <div class="form-check">
                    <input type="checkbox" id="is_penalty_enabled" name="is_penalty_enabled"
                           {{ $landboard->is_penalty_enabled ? 'checked' : '' }}
                           onchange="toggleLatePenalty()">
                    <label for="is_penalty_enabled">Aktifkan Penalti Keterlambatan</label>
                </div>

                <label for="late_fee_amount">Denda Keterlambatan (Rp):</label>
                <input type="number" name="late_fee_amount" id="late_fee_amount"
                       value="{{ $landboard->late_fee_amount }}" min="0">

                <label for="late_fee_days">Toleransi Hari Keterlambatan:</label>
                <input type="number" name="late_fee_days" id="late_fee_days"
                       value="{{ $landboard->late_fee_days }}" min="0">

                {{-- Penalti Keluar Tengah Jalan --}}
                <div class="form-check">
                    <input type="checkbox" id="is_penalty_on_moveout" name="is_penalty_on_moveout"
                           {{ $landboard->is_penalty_on_moveout ? 'checked' : '' }}
                           onchange="toggleMoveoutPenalty()">
                    <label for="is_penalty_on_moveout">Penalti saat keluar sebelum waktu</label>
                </div>

                <label for="moveout_penalty_amount">Denda Keluar Sebelum Waktu (Rp):</label>
                <input type="number" name="moveout_penalty_amount" id="moveout_penalty_amount"
                       value="{{ $landboard->moveout_penalty_amount }}" min="0">

                {{-- Penalti Pindah Kamar --}}
                <div class="form-check">
                    <input type="checkbox" id="is_penalty_on_room_change" name="is_penalty_on_room_change"
                           {{ $landboard->is_penalty_on_room_change ? 'checked' : '' }}
                           onchange="toggleRoomChangePenalty()">
                    <label for="is_penalty_on_room_change">Penalti saat pindah kamar</label>
                </div>

                <label for="room_change_penalty_amount">Denda Pindah Kamar (Rp):</label>
                <input type="number" name="room_change_penalty_amount" id="room_change_penalty_amount"
                       value="{{ $landboard->room_change_penalty_amount }}" min="0">

                {{-- Konfirmasi Sebelum Sewa Berakhir --}}
                <label for="decision_days_before_end">Hari Konfirmasi Sebelum Akhir Sewa:</label>
                <input type="number" name="decision_days_before_end" id="decision_days_before_end"
                       value="{{ $landboard->decision_days_before_end }}" min="0" required>

                <button type="submit">Simpan Pengaturan</button>
            </form>
        </div>
    </div>

    {{-- Script --}}
    <script>
        function toggleLatePenalty() {
            const enabled = document.getElementById('is_penalty_enabled').checked;
            document.getElementById('late_fee_amount').disabled = !enabled;
            document.getElementById('late_fee_days').disabled = !enabled;
        }

        function toggleMoveoutPenalty() {
            const checked = document.getElementById('is_penalty_on_moveout').checked;
            document.getElementById('moveout_penalty_amount').disabled = !checked;
        }

        function toggleRoomChangePenalty() {
            const checked = document.getElementById('is_penalty_on_room_change').checked;
            document.getElementById('room_change_penalty_amount').disabled = !checked;
        }

        window.onload = function () {
            toggleLatePenalty();
            toggleMoveoutPenalty();
            toggleRoomChangePenalty();
        };
    </script>
</body>
</html>
