<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Edit Profil Landboard</title>
</head>
<body class="mt-4 bg-gray-200 font-sans min-h-screen flex items-center justify-center pb-16">
@include('components.sidebar-landboard')
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg p-6 md:p-10 overflow-y-auto">
        <div id="header-section" class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-black mb-4">Lengkapi Profil Anda</h2>
            <label for="avatar" class="relative group cursor-pointer inline-block">
                <img src="/assets/default-avatar.png" alt="" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md group-hover:brightness-75 transition" />
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white">
                    <i class="bi bi-pen text-xl"></i>
                </div>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" />
            </label>
        </div>

        <form id="profile-form" action="{{ route('landboard.complete-profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Step Container -->
            <div id="form-steps">
                <!-- Step 1 -->
                <div class="form-step" id="step-1">
                    <div class="text-lg font-bold text-black border-b border-black pb-1 mb-4">
                        <i class="bi bi-person-lines-fill mr-2"></i>Data Pribadi
                    </div>

                    <label for="name" class="block mt-2 font-semibold text-black">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required class="w-full p-2 rounded-md border border-black" />

                    <label for="phone" class="block mt-4 font-semibold text-black">Nomor HP Utama</label>
                    <input type="text" name="phone" id="phone" required class="w-full p-2 rounded-md border border-black" />

                    <label for="alt_phone" class="block mt-4 font-semibold text-black">Nomor HP Alternatif (Opsional)</label>
                    <input type="text" name="alt_phone" id="alt_phone" class="w-full p-2 rounded-md border border-black" />
                </div>

                <!-- Step 2 -->
                <div class="form-step hidden" id="step-2">
                    <div class="text-lg font-bold text-black border-b border-black pb-1 mb-4">
                        <i class="bi bi-house-exclamation-fill mr-2"></i>Data Kost
                    </div>

                    <label for="kost_name" class="block mt-2 font-semibold text-black">Nama Kost</label>
                    <input type="text" name="kost_name" id="kost_name" required class="w-full p-2 rounded-md border border-black" />

                    <label for="province" class="block mt-4 font-semibold text-black">Provinsi</label>
                    <input type="text" name="province" id="province" required class="w-full p-2 rounded-md border border-black" />

                    <label for="city" class="block mt-4 font-semibold text-black">Kota/Kabupaten</label>
                    <input type="text" name="city" id="city" required class="w-full p-2 rounded-md border border-black" />

                    <label for="district" class="block mt-4 font-semibold text-black">Kecamatan</label>
                    <input type="text" name="district" id="district" required class="w-full p-2 rounded-md border border-black" />

                    <label for="village" class="block mt-4 font-semibold text-black">Kelurahan (Opsional)</label>
                    <input type="text" name="village" id="village" class="w-full p-2 rounded-md border border-black" />

                    <label for="postal_code" class="block mt-4 font-semibold text-black">Kode Pos (Opsional)</label>
                    <input type="text" name="postal_code" id="postal_code" class="w-full p-2 rounded-md border border-black" />

                    <label for="full_address" class="block mt-4 font-semibold text-black">Alamat Lengkap</label>
                    <input type="text" name="full_address" id="full_address" required class="w-full p-2 rounded-md border border-black" />
                </div>

                <!-- Step 3 -->
                <div class="form-step hidden" id="step-3">
                    <div class="text-lg font-bold text-black border-b border-[#ddd0c1] pb-1 mb-4">
                        <i class="bi bi-bank2 mr-2"></i>Informasi Bank
                    </div>

                    <label for="bank_name" class="block mt-2 font-semibold text-black">Nama Bank</label>
                    <input type="text" name="bank_name" id="bank_name" required class="w-full p-2 rounded-md border border-black" />

                    <label for="bank_account" class="block mt-4 font-semibold text-black">Nomor Rekening</label>
                    <input type="text" name="bank_account" id="bank_account" required class="w-full p-2 rounded-md border border-black" />

                    <button type="submit" class="mt-6 w-full py-3 bg-[#31c594] hover:bg-[#1a966d] text-white rounded-md text-base">
                        Simpan Profil
                    </button>

                    <button type="submit" formaction="{{ route('logout') }}" formmethod="POST"
                            class="mt-4 w-full py-3 bg-[#c94e4e] hover:bg-[#a43737] text-white rounded-md text-base">
                        Logout
                    </button>
                </div>
            </div>

            <!-- Navigasi -->
            <div class="mt-6 flex justify-between">
                <button type="button" id="prev-btn" class="px-4 py-2 bg-gray-300 rounded-md text-sm hidden">Sebelumnya</button>
                <button type="button" id="next-btn" class="px-4 py-2 bg-[#31c594] hover:bg-[#1a966d] text-white rounded-md text-sm">
                    Selanjutnya
                </button>
            </div>
        </form>
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
