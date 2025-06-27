<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Duplikat Kamar</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background: #f5f3f0;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .card {
            background: #fffaf6;
            padding: 30px;
            border-radius: 14px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 6px 12px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            color: #5a4430;
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: 600;
            color: #6b4e3d;
        }

        input[type="number"] {
            padding: 12px;
            width: 100%;
            margin-top: 8px;
            border: 1px solid #d6ccc2;
            border-radius: 8px;
            font-size: 14px;
            background: #fdfdfb;
            box-sizing: border-box;
        }

        button[type="submit"] {
            margin-top: 28px;
            width: 100%;
            padding: 14px;
            background: #6e5947;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background: #5a4430;
        }

        .info-box {
            background: #fffbe6;
            border: 1px solid #e2c877;
            padding: 18px;
            border-radius: 10px;
            color: #5c4613;
            margin-bottom: 24px;
            font-size: 14.5px;
        }

        .info-box strong {
            color: #443422;
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

    @include('components.sidebar-landboard')

    <div class="main-content">
        <div class="card">
            <h2>Duplikat Kamar Tipe: {{ $room->type }}</h2>

            <div class="info-box">
                <p>Duplikasi akan menyalin semua data kamar seperti <strong>fasilitas, aturan, harga, gender, dan foto</strong>.</p>
                <p>Nomor terakhir: <strong>{{ $lastNumber }}</strong><br>
                   Kamar baru akan dimulai dari: <strong>{{ $room->type }}-{{ $lastNumber + 1 }}</strong></p>
            </div>

            <form method="POST" action="{{ route('landboard.rooms.duplicate', $room->id) }}">
                @csrf

                <label>Jumlah Kamar yang Akan Dibuat:</label>
                <input type="number" name="room_quantity" min="1" required>

                <button type="submit">Duplikat Sekarang</button>
            </form>
        </div>
    </div>

</body>
</html>
