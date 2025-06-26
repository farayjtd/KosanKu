<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kamar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
            max-width: 820px;
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
            margin-top: 18px;
            font-weight: 600;
            color: #6b4e3d;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #d6ccc2;
            border-radius: 8px;
            font-size: 14px;
            background: #fdfdfb;
            box-sizing: border-box;
        }

        .group {
            display: flex;
            gap: 12px;
            margin-top: 10px;
            align-items: center;
        }

        .remove-btn {
            background: #b91c1c;
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .add-btn {
            margin-top: 12px;
            background: #4299e1;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
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

        img.preview {
            width: 100px;
            border-radius: 8px;
            margin-top: 6px;
            border: 1px solid #ddd;
        }

        .radio-group {
            margin-top: 14px;
        }

        .radio-group label {
            margin-right: 18px;
            font-weight: normal;
            color: #4a4a4a;
        }

        ul.error-list {
            color: red;
            margin-top: 12px;
            list-style: disc;
            padding-left: 20px;
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
        <h2>Edit Kamar</h2>

        @if ($errors->any())
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('landboard.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
            @method('PUT')

            <label>Tipe Kamar</label>
            <input type="text" name="type" value="{{ old('type', $room->type) }}" required>

            <label>Harga per Bulan</label>
            <input type="number" name="price" value="{{ old('price', $room->price) }}" required>

            <label>Jenis Kelamin yang Diizinkan</label>
            <select name="gender_type" required>
                <option value="male" {{ old('gender_type', $room->gender_type) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender_type', $room->gender_type) === 'female' ? 'selected' : '' }}>Perempuan</option>
                <option value="mixed" {{ old('gender_type', $room->gender_type) === 'mixed' ? 'selected' : '' }}>Campuran</option>
            </select>

            <label>Foto Lama</label>
            <div id="existing-photos">
                @foreach ($room->photos as $photo)
                    <div class="group">
                        <img src="{{ asset('storage/' . $photo->path) }}" class="preview">
                        <label><input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}"> Hapus</label>
                    </div>
                @endforeach
            </div>

            <label>Tambah Foto Baru</label>
            <div id="photo-container">
                <div class="group"><input type="file" name="photos[]" accept="image/*"></div>
            </div>
            <button type="button" class="add-btn" onclick="addPhoto()">+ Tambah Foto</button>

            <label>Fasilitas</label>
            <div id="facility-container">
                @forelse ($room->facilities as $facility)
                    <div class="group">
                        <input type="text" name="facilities[]" value="{{ $facility->name }}">
                        <button type="button" class="remove-btn" onclick="removeField(this)">Hapus</button>
                    </div>
                @empty
                    <div class="group"><input type="text" name="facilities[]"></div>
                @endforelse
            </div>
            <button type="button" class="add-btn" onclick="addFacility()">+ Tambah Fasilitas</button>

            <label>Aturan</label>
            <div id="rule-container">
                @forelse ($room->rules as $rule)
                    <div class="group">
                        <input type="text" name="rules[]" value="{{ $rule->name }}">
                        <button type="button" class="remove-btn" onclick="removeField(this)">Hapus</button>
                    </div>
                @empty
                    <div class="group"><input type="text" name="rules[]"></div>
                @endforelse
            </div>
            <button type="button" class="add-btn" onclick="addRule()">+ Tambah Aturan</button>

            <label>Perbarui Untuk</label>
            <div class="radio-group">
                <label><input type="radio" name="apply_all" value="0" checked> Hanya kamar ini</label>
                <label><input type="radio" name="apply_all" value="1"> Semua kamar tipe ini ({{ $room->type }})</label>
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    function addPhoto() {
        const container = document.getElementById('photo-container');
        const div = document.createElement('div');
        div.classList.add('group');
        div.innerHTML = '<input type="file" name="photos[]" accept="image/*"> <button type="button" class="remove-btn" onclick="removeField(this)">Hapus</button>';
        container.appendChild(div);
    }

    function addFacility() {
        const container = document.getElementById('facility-container');
        const div = document.createElement('div');
        div.classList.add('group');
        div.innerHTML = '<input type="text" name="facilities[]"> <button type="button" class="remove-btn" onclick="removeField(this)">Hapus</button>';
        container.appendChild(div);
    }

    function addRule() {
        const container = document.getElementById('rule-container');
        const div = document.createElement('div');
        div.classList.add('group');
        div.innerHTML = '<input type="text" name="rules[]"> <button type="button" class="remove-btn" onclick="removeField(this)">Hapus</button>';
        container.appendChild(div);
    }

    function removeField(button) {
        const group = button.closest('.group');
        const container = group.parentElement;
        if (container.querySelectorAll('.group').length > 1) {
            group.remove();
        } else {
            alert("Minimal 1 input harus ada.");
        }
    }

    function validateForm() {
        const facilityInputs = document.querySelectorAll('input[name="facilities[]"]');
        const ruleInputs = document.querySelectorAll('input[name="rules[]"]');
        const photoInputs = document.querySelectorAll('input[name="photos[]"]');
        const existingChecked = document.querySelectorAll('input[name="delete_photos[]"]:checked');
        const totalOldPhotos = document.querySelectorAll('#existing-photos input[type="checkbox"]').length;
        const hasOldPhotoRemaining = totalOldPhotos > existingChecked.length;

        const hasFacility = [...facilityInputs].some(input => input.value.trim() !== '');
        const hasRule = [...ruleInputs].some(input => input.value.trim() !== '');
        const hasNewPhoto = [...photoInputs].some(input => input.value !== '');

        if (!hasFacility) {
            alert("Minimal 1 fasilitas harus diisi.");
            return false;
        }

        if (!hasRule) {
            alert("Minimal 1 aturan harus diisi.");
            return false;
        }

        if (!hasNewPhoto && !hasOldPhotoRemaining) {
            alert("Minimal harus ada 1 foto (lama atau baru).");
            return false;
        }

        return true;
    }
</script>
</body>
</html>
