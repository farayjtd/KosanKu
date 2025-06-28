<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Lengkapi Profil Tenant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 font-sans m-0 w-screen h-screen">

    <div class="flex flex-col md:flex-row w-full h-full">
        <!-- Kiri: Form -->
        <div class="w-full md:w-1/3 h-full p-6 md:p-10 overflow-y-auto bg-white rounded-r-2xl">
            <div id="header-section">
                <h2 class="text-2xl font-semibold mb-6 text-black text-center">Lengkapi Profil Anda</h2>
                <div class="flex justify-center mb-6">
                    <label for="avatar" class="relative group cursor-pointer">
                        <img src="/assets/default-avatar.png" alt="" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md group-hover:brightness-75 transition" />
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white">
                            <i class="bi bi-pen text-xl"></i>
                        </div>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" />
                    </label>
                </div>
            </div>

            <form id="profile-form" action="{{ route('tenant.profile.complete.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="form-steps">
                    <div class="form-step" id="step-1">
                        <div class="text-lg font-bold text-black border-b border-[#ddd0c1] pb-1 mb-4"><i class="bi bi-person-lines-fill mr-3"></i>Data Pribadi</div>

                        <label for="name" class="block mt-2 font-semibold text-black">Nama Lengkap</label>
                        <input type="text" name="name" id="name" required class="w-full p-2 rounded-md border border-gray-300" />

                        <label for="phone" class="block mt-4 font-semibold text-black">Nomor HP Utama</label>
                        <input type="text" name="phone" id="phone" required class="w-full p-2 rounded-md border border-gray-300" />

                        <label for="alt_phone" class="block mt-4 font-semibold text-black">Nomor HP Alternatif</label>
                        <input type="text" name="alt_phone" id="alt_phone" class="w-full p-2 rounded-md border border-gray-300" />

                        <label for="photo" class="block mt-4 font-semibold text-black">Foto Kartu Identitas</label>
                        <input type="file" name="photo" id="photo" accept="image/*" required class="w-full mt-1 text-sm" />
                    </div>
                    <div class="form-step hidden" id="step-2">
                        <div class="text-lg font-bold text-black border-b border-[#ddd0c1] pb-1 mb-4"><i class="bi bi-info-circle-fill mr-3"></i>Informasi Tambahan</div>

                        <label for="address" class="block mt-2 font-semibold text-black">Alamat Asal</label>
                        <input type="text" name="address" id="address" required class="w-full p-2 rounded-md border border-gray-300" />

                        <label for="gender" class="block mt-4 font-semibold text-black">Jenis Kelamin</label>
                        <select name="gender" id="gender" required class="w-full p-2 rounded-md border border-gray-300">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>

                        <label for="activity_type" class="block mt-4 font-semibold text-black">Pekerjaan</label>
                        <input type="text" name="activity_type" id="activity_type" placeholder="Contoh: Mahasiswa, Pegawai" required class="w-full p-2 rounded-md border border-gray-300" />

                        <label for="institution_name" class="block mt-4 font-semibold text-black">Nama Institusi</label>
                        <input type="text" name="institution_name" id="institution_name" required class="w-full p-2 rounded-md border border-gray-300" />
                    </div>
                    <div class="form-step hidden" id="step-3">
                        <div class="text-lg font-bold text-black border-b border-[#ddd0c1] pb-1 mb-4"><i class="bi bi-bank2 mr-3"></i>Informasi Bank</div>

                        <label for="bank_name" class="block mt-2 font-semibold text-black">Nama Bank</label>
                        <input type="text" name="bank_name" id="bank_name" required class="w-full p-2 rounded-md border border-gray-300" />

                        <label for="bank_account" class="block mt-4 font-semibold text-black">Nomor Rekening</label>
                        <input type="text" name="bank_account" id="bank_account" required class="w-full p-2 rounded-md border border-gray-300" />

                        <button type="submit" class="mt-6 w-full py-3 bg-[#31c594] hover:bg-[#1a966d] text-white rounded-md text-base">Simpan Profil</button>

                        <button type="submit" formaction="{{ route('logout') }}" formmethod="POST" class="mt-4 w-full py-3 bg-[#c94e4e] hover:bg-[#a43737] text-white rounded-md text-base">Logout</button>
                    </div>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" id="prev-btn" class="px-4 py-2 bg-gray-300 rounded-md text-sm hidden">Sebelumnya</button>
                    <button type="button" id="next-btn" class="px-4 py-2 bg-[#31c594] hover:bg-[#1a966d] text-white rounded-md text-sm">Selanjutnya</button>
                </div>
            </form>
        </div>
        <div class="hidden md:block md:w-1/2 h-full bg-cover bg-center" style="background-image: url('/assets/tenant-profile-illustration.png');">
        </div>
    </div>

    <script>
        const steps = document.querySelectorAll('.form-step');
        let currentStep = 0;

        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const headerSection = document.getElementById('header-section');

        function updateStep() {
            steps.forEach((step, index) => {
                step.classList.toggle('hidden', index !== currentStep);
            });
            prevBtn.classList.toggle('hidden', currentStep === 0);
            nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);
            headerSection.classList.toggle('hidden', currentStep !== 0);
        }

        nextBtn.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateStep();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                updateStep();
            }
        });

        updateStep();
    </script>
</body>
</html>
