<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Edit Kamar</title>
    <style>
        .photo-wrapper:hover .delete-button {
            display: flex;
        }
        .photo-wrapper.marked {
            opacity: 0.3;
        }
        .photo-wrapper.marked .delete-button {
            background-color: rgba(255, 0, 0, 0.4);
        }
    </style>
</head>
<body class="bg-gray-200 font-sans pb-16">
@include('components.sidebar-landboard')

<div class="flex-1 p-6">
    @if ($errors->any())
        <ul class="max-w-3xl mx-auto text-red-600 font-semibold mb-4 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="bg-white p-8 rounded-xl max-w-5xl mx-auto shadow-md">
        <h2 class="text-left text-2xl text-black font-semibold">Edit Kamar</h2>

        <form action="{{ route('landboard.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
            @method('PUT')

            <div class="flex flex-col lg:flex-row gap-8">
                <div class="flex-1">
                    <label class="block font-semibold text-black mt-4"><i class="bi bi-building-exclamation mr-2"></i>Tipe Kamar</label>
                    <input type="text" name="type" value="{{ old('type', $room->type) }}" required class="w-full mt-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">

                    <label class="block font-semibold text-black mt-4"><i class="bi bi-tags mr-2"></i>Harga per Bulan</label>
                    <input type="number" name="price" value="{{ old('price', $room->price) }}" required class="w-full mt-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">

                    <label class="block font-semibold text-black mt-4"><i class="bi bi-gender-ambiguous mr-2"></i>Jenis Kelamin yang Diizinkan</label>
                    <select name="gender_type" required class="w-full mt-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                        <option value="mixed" {{ old('gender_type', $room->gender_type) === 'mixed' ? 'selected' : '' }}>Campuran</option>
                        <option value="male" {{ old('gender_type', $room->gender_type) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender_type', $room->gender_type) === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>

                    <label class="block font-semibold text-black mt-4"><i class="bi bi-info-circle mr-2"></i>Fasilitas</label>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        @php
                            $facilitiesList = ['WiFi', 'AC', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja Belajar', 'Listrik Token', 'Parkir Motor', 'Parkir Mobil', 'Dapur Umum', 'CCTV'];
                            $roomFacilities = $room->facilities->pluck('name')->toArray();
                        @endphp

                        @foreach ($facilitiesList as $facility)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="facilities[]" value="{{ $facility }}" {{ in_array($facility, old('facilities', $roomFacilities)) ? 'checked' : '' }} class="accent-[#31c594] w-5 h-5 rounded">
                                <span class="text-sm text-black">{{ $facility }}</span>
                            </label>
                        @endforeach
                    </div>

                    <label class="block font-semibold text-black mt-6"><i class="bi bi-house-exclamation mr-2"></i>Aturan</label>
                    <div id="rule-container">
                        @forelse ($room->rules as $rule)
                            <div class="flex gap-2 mt-2">
                                <input type="text" name="rules[]" value="{{ $rule->name }}" class="flex-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                                <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>
                            </div>
                        @empty
                            <div class="flex gap-2 mt-2">
                                <input type="text" name="rules[]" class="flex-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                                <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" class="mt-2 bg-[#31c594] text-white px-4 py-2 rounded-md hover:bg-[#1a966d]" onclick="addRule()">+ Tambah Aturan</button>
                </div>

                <div class="flex-1">
                    <label class="block font-semibold text-black mt-4"><i class="bi bi-image mr-2"></i>Foto Lama</label>
                    <div id="existing-photos" class="flex flex-wrap gap-3 mt-2 max-h-32 overflow-hidden" data-expanded="false">
                        @foreach ($room->photos as $photo)
                            <div class="photo-wrapper relative group">
                                <img src="{{ asset('storage/' . $photo->path) }}" class="w-24 h-24 object-cover rounded-md border">
                                <button type="button" class="delete-button absolute inset-0 hidden justify-center items-center bg-black/50 text-white rounded-md" onclick="toggleDeletePhoto(this, '{{ $photo->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <input type="hidden" name="delete_photos[]" value="" disabled>
                            </div>
                        @endforeach
                    </div>
                    @if (count($room->photos) > 2)
                        <button type="button" id="toggle-photos" class="text-blue-600 text-sm mt-1">Tampilkan Semua</button>
                    @endif

                    <label class="block font-semibold text-black mt-6"><i class="bi bi-image mr-2"></i>Tambah Foto Baru</label>
                    <div id="photo-container">
                        <div class="flex gap-2 mt-2">
                            <input type="file" name="photos[]" accept="image/*" class="flex-1 p-3 border border-[#d6ccc2] rounded-md">
                            <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>
                        </div>
                    </div>
                    <button type="button" class="mt-2 bg-[#31c594] text-white px-4 py-2 rounded-md hover:bg-[#1a966d]" onclick="addPhoto()">+ Tambah Foto</button>

                    <label class="block font-semibold text-black mt-6"><i class="bi bi-person-lines-fill mr-2"></i>Perbarui Untuk</label>
                    <div class="mt-2">
                        <label class="mr-4 text-black"><input type="radio" name="apply_all" value="0" checked class="mr-1"> Hanya kamar ini</label>
                        <label class="text-black"><input type="radio" name="apply_all" value="1" class="mr-1"> Semua kamar tipe ini ({{ $room->type }})</label>
                    </div>

                    <button type="submit" class="mt-8 w-full bg-[#31c594] hover:bg-[#1a966d] text-white py-3 rounded-lg text-lg font-semibold">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function removeField(button) {
        const container = button.closest('.flex');
        if (container.parentElement.querySelectorAll('.flex').length > 1) {
            container.remove();
        } else {
            alert("Minimal 1 input harus ada.");
        }
    }

    function addRule() {
        const container = document.getElementById('rule-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2 mt-2';
        div.innerHTML = `<input type="text" name="rules[]" class="flex-1 p-3 border border-[#d6ccc2] rounded-md bg-[#fdfdfb]">
                         <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>`;
        container.appendChild(div);
    }

    function addPhoto() {
        const container = document.getElementById('photo-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2 mt-2';
        div.innerHTML = `<input type="file" name="photos[]" accept="image/*" class="flex-1 p-3 border border-[#d6ccc2] rounded-md">
                         <button type="button" class="text-red-500 px-3 py-1 rounded-md font-bold" onclick="removeField(this)"><i class="bi bi-dash-lg"></i></button>`;
        container.appendChild(div);
    }

    function toggleDeletePhoto(button, photoId) {
        const wrapper = button.parentElement;
        const input = wrapper.querySelector('input[type="hidden"]');

        if (wrapper.classList.contains('marked')) {
            wrapper.classList.remove('marked');
            input.value = "";
            input.disabled = true;
        } else {
            wrapper.classList.add('marked');
            input.value = photoId;
            input.disabled = false;
        }
    }
</script>
</body>
</html>