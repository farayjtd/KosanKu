<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tambah Kamar</title>
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
            max-width: 800px;
            margin: auto;
            box-shadow: 0 6px 12px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            color: #5a4430;
            margin-bottom: 24px;
        }

        label {
            font-weight: 600;
            margin-top: 18px;
            display: block;
            color: #6b4e3d;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #d6ccc2;
            border-radius: 8px;
            font-size: 14px;
            background: #fdfdfb;
            box-sizing: border-box;
        }

        .group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .group input[type="text"],
        .group input[type="file"] {
            flex: 1;
        }

        .remove-btn {
            background: #c84c43;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .add-btn {
            margin-top: 10px;
            background: #a18064;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .add-btn:hover {
            background: #80644c;
        }

        button[type="submit"] {
            margin-top: 30px;
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

        .message {
            max-width: 800px;
            margin: 0 auto 20px auto;
            color: green;
            font-weight: bold;
        }

        .error-list {
            color: red;
            max-width: 800px;
            margin: 0 auto 20px auto;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .card {
                padding: 20px;
                margin: 20px;
            }

            .group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    @include('components.sidebar-landboard')

    <div class="main-content">
        @if(session('success'))
            <p class="message">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="card">
            <h2>Tambah Kamar</h2>

            <form action="{{ route('landboard.rooms.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf

                <label>Tipe Kamar</label>
                <input type="text" name="type" value="{{ old('type') }}" required>

                <label>Jumlah Kamar</label>
                <input type="number" name="room_quantity" min="1" value="{{ old('room_quantity', 1) }}" required>

                <label>Harga per Bulan</label>
                <input type="number" name="price" value="{{ old('price') }}" required>

                <label>Jenis Kelamin yang Diizinkan</label>
                <select name="gender_type" required>
                    <option value="mixed" {{ old('gender_type') == 'mixed' ? 'selected' : '' }}>Campuran</option>
                    <option value="male" {{ old('gender_type') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender_type') == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>

                <label>Fasilitas</label>
                <div id="facility-container">
                    <div class="group">
                        <input type="text" name="facilities[]" required>
                        <button type="button" class="remove-btn" onclick="removeField(this)">X</button>
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addFacility()">+ Tambah Fasilitas</button>

                <label>Aturan</label>
                <div id="rule-container">
                    <div class="group">
                        <input type="text" name="rules[]" required>
                        <button type="button" class="remove-btn" onclick="removeField(this)">X</button>
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addRule()">+ Tambah Aturan</button>

                <label>Foto Kamar</label>
                <div id="photo-container">
                    <div class="group">
                        <input type="file" name="photos[]" accept="image/*" required>
                        <button type="button" class="remove-btn" onclick="removeField(this)">X</button>
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addPhoto()">+ Tambah Foto</button>

                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        function removeField(btn) {
            const container = btn.closest('.group').parentElement;
            if (container.querySelectorAll('.group').length > 1) {
                btn.closest('.group').remove();
            } else {
                alert('Minimal 1 input harus ada.');
            }
        }

        function addFacility() {
            const container = document.getElementById('facility-container');
            const div = document.createElement('div');
            div.className = 'group';
            div.innerHTML = `<input type="text" name="facilities[]" required>
                             <button type="button" class="remove-btn" onclick="removeField(this)">X</button>`;
            container.appendChild(div);
        }

        function addRule() {
            const container = document.getElementById('rule-container');
            const div = document.createElement('div');
            div.className = 'group';
            div.innerHTML = `<input type="text" name="rules[]" required>
                             <button type="button" class="remove-btn" onclick="removeField(this)">X</button>`;
            container.appendChild(div);
        }

        function addPhoto() {
            const container = document.getElementById('photo-container');
            const div = document.createElement('div');
            div.className = 'group';
            div.innerHTML = `<input type="file" name="photos[]" accept="image/*" required>
                             <button type="button" class="remove-btn" onclick="removeField(this)">X</button>`;
            container.appendChild(div);
        }

        function validateForm() {
            const facilityInputs = document.querySelectorAll('input[name="facilities[]"]');
            const ruleInputs = document.querySelectorAll('input[name="rules[]"]');
            const photoInputs = document.querySelectorAll('input[name="photos[]"]');

            if (![...facilityInputs].some(input => input.value.trim() !== '')) {
                alert("Minimal 1 fasilitas harus diisi.");
                return false;
            }

            if (![...ruleInputs].some(input => input.value.trim() !== '')) {
                alert("Minimal 1 aturan harus diisi.");
                return false;
            }

            if (![...photoInputs].some(input => input.value !== '')) {
                alert("Minimal 1 foto harus dipilih.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
