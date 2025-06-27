<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Tambah Kamar</title>
</head>
<body class="bg-gray-200 font-sans pb-16">
    @include('components.sidebar-landboard')

    <div class="flex-1 p-6">
        @if(session('success'))
            <p class="max-w-3xl mx-auto text-green-600 font-semibold mb-4">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            <ul class="max-w-3xl mx-auto text-red-600 font-semibold mb-4 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="bg-white p-8 rounded-xl max-w-5xl mx-auto shadow-md">
            <h2 class="text-left text-2xl text-black font-semibold">Tambah Kamar</h2>

            <form action="{{ route('landboard.rooms.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf

                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-1">
                        <label class="block font-semibold text-black mt-4"><i class="bi bi-building-exclamation mr-2"></i>Tipe Kamar</label>
                        <input type="text" name="type" value="{{ old('type') }}" required class="w-full mt-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">

                        <label class="block font-semibold text-black mt-4"><i class="bi bi-door-open mr-2"></i>Jumlah Kamar</label>
                        <input type="number" name="room_quantity" min="1" value="{{ old('room_quantity', 1) }}" required class="w-full mt-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">

                        <label class="block font-semibold text-black mt-4"><i class="bi bi-gender-ambiguous mr-2"></i>Jenis Kelamin yang Diizinkan</label>
                        <select name="gender_type" required class="w-full mt-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                            <option value="mixed" {{ old('gender_type') == 'mixed' ? 'selected' : '' }}>Campuran</option>
                            <option value="male" {{ old('gender_type') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender_type') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <label class="block font-semibold text-black mt-4"><i class="bi bi-info-circle mr-2"></i>Fasilitas</label>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            @php
                                $facilitiesList = [
                                    'WiFi', 'AC', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja Belajar',
                                    'Listrik Token', 'Parkir Motor', 'Parkir Mobil', 'Dapur Umum', 'CCTV'
                                ];
                            @endphp

                            @foreach ($facilitiesList as $facility)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="facilities[]" value="{{ $facility }}"
                                        {{ is_array(old('facilities')) && in_array($facility, old('facilities')) ? 'checked' : '' }}
                                        class="accent-[#31c594] w-5 h-5 rounded" />
                                    <span class="text-sm text-black">{{ $facility }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex-1">
                        <label class="block font-semibold text-black mt-4 "><i class="bi bi-tags mr-2"></i>Harga per Bulan</label>
                        <input type="number" name="price" value="{{ old('price') }}" required class="w-full mt-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                        
                        <label class="block font-semibold text-black mt-6"><i class="bi bi-house-exclamation mr-2"></i>Aturan</label>
                        <div id="rule-container" class="space-y-2">
                            <div class="flex gap-2">
                                <input type="text" name="rules[]" required class="flex-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                                <button type="button" class="text-red-500 px-3 py-1 rounded-4xl font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>
                            </div>
                        </div>
                        <button type="button" class="mt-2 bg-[#31c594] text-white px-4 py-2 rounded-md hover:bg-[#1a966d]" onclick="addRule()">+ Tambah Aturan</button>

                        <label class="block font-semibold text-black mt-6"><i class="bi bi-image mr-2"></i>Foto Kamar</label>
                        <div id="photo-container" class="space-y-2">
                            <div class="flex gap-2">
                                <input type="file" name="photos[]" accept="image/*" required class="flex-1 p-3 border border-[#d6ccc2] rounded-md">
                                <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>
                            </div>
                        </div>
                        <button type="button" class="mt-2 bg-[#31c594] text-white px-4 py-2 rounded-md hover:bg-[#1a966d]" onclick="addPhoto()">+ Tambah Foto</button>
                        <button type="submit" class="mt-8 w-full bg-[#31c594] hover:bg-[#1a966d] text-white py-3 rounded-lg text-lg font-semibold">Simpan</button>
                    </div>
                </div>
                
            </form>
        </div>

    </div>

    <script>
        function removeField(btn) {
            const container = btn.closest('.flex').parentElement;
            if (container.querySelectorAll('.flex').length > 1) {
                btn.closest('.flex').remove();
            } else {
                alert('Minimal 1 input harus ada.');
            }
        }

        function addFacility() {
            const container = document.getElementById('facility-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `<input type="text" name="facilities[]" required class="flex-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                             <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-md font-bold" onclick="removeField(this)">X</button>`;
            container.appendChild(div);
        }

        function addRule() {
            const container = document.getElementById('rule-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `<input type="text" name="rules[]" required class="flex-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                             <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>`;
            container.appendChild(div);
        }

        function addPhoto() {
            const container = document.getElementById('photo-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `<input type="file" name="photos[]" accept="image/*" required class="flex-1 p-3 border border-[#d6ccc2] rounded-md">
                             <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>`;
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