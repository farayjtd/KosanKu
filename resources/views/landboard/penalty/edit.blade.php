<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Penalti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-100 font-sans pb-16">
    @include('components.sidebar-landboard')
    <div class="min-h-screen flex items-center justify-center p-6 md:p-10 bg-gray-100">
        <div class="bg-white shadow-md rounded-xl max-w-3xl w-full mx-auto p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Penalti</h2>

            <form method="POST" action="{{ route('penalty.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" id="is_penalty_enabled" name="is_penalty_enabled"
                               class="rounded text-[#31c594] focus:ring-[#31c594]"
                               {{ $landboard->is_penalty_enabled ? 'checked' : '' }}
                               onchange="toggleLatePenalty()">
                        <span class="text-gray-700 font-medium">Aktifkan Penalti Keterlambatan</span>
                    </label>
                </div>

                <div>
                    <label for="late_fee_amount" class="block text-sm font-semibold text-gray-700">Denda Keterlambatan (Rp):</label>
                    <input type="number" name="late_fee_amount" id="late_fee_amount" min="0"
                        value="{{ $landboard->late_fee_amount }}"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                focus:outline-none focus:ring-[#31c594] focus:border-[#31c594] bg-white transition" />

                </div>

                <div>
                    <label for="late_fee_days" class="block text-sm font-semibold text-gray-700">Toleransi Hari Keterlambatan:</label>
                    <input type="number" name="late_fee_days" id="late_fee_days" min="0"
                           value="{{ $landboard->late_fee_days }}"
                           class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#31c594] focus:border-[#31c594] bg-white">
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" id="is_penalty_on_moveout" name="is_penalty_on_moveout"
                               class="rounded text-[#31c594] focus:ring-[#31c594]"
                               {{ $landboard->is_penalty_on_moveout ? 'checked' : '' }}
                               onchange="toggleMoveoutPenalty()">
                        <span class="text-gray-700 font-medium">Penalti saat keluar sebelum waktu</span>
                    </label>
                </div>

                <div>
                    <label for="moveout_penalty_amount" class="block text-sm font-semibold text-gray-700">Denda Keluar Sebelum Waktu (Rp):</label>
                    <input type="number" name="moveout_penalty_amount" id="moveout_penalty_amount" min="0"
                           value="{{ $landboard->moveout_penalty_amount }}"
                           class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#31c594] focus:border-[#31c594] bg-white">
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" id="is_penalty_on_room_change" name="is_penalty_on_room_change"
                               class="rounded text-[#31c594] focus:ring-[#31c594]"
                               {{ $landboard->is_penalty_on_room_change ? 'checked' : '' }}
                               onchange="toggleRoomChangePenalty()">
                        <span class="text-gray-700 font-medium">Penalti saat pindah kamar</span>
                    </label>
                </div>

                <div>
                    <label for="room_change_penalty_amount" class="block text-sm font-semibold text-gray-700">Denda Pindah Kamar (Rp):</label>
                    <input type="number" name="room_change_penalty_amount" id="room_change_penalty_amount" min="0"
                           value="{{ $landboard->room_change_penalty_amount }}"
                           class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#31c594] focus:border-[#31c594] bg-white">
                </div>
                <div>
                    <label for="decision_days_before_end" class="block text-sm font-semibold text-gray-700">Hari Konfirmasi Sebelum Akhir Sewa:</label>
                    <input type="number" name="decision_days_before_end" id="decision_days_before_end" min="0"
                           value="{{ $landboard->decision_days_before_end }}" required
                           class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#31c594] focus:border-[#31c594] bg-white">
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="bg-[#31c594] text-white px-6 py-3 rounded-md font-medium hover:bg-[#2ca882] transition">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

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
        function setDisabledState(inputId, isDisabled) {
        const input = document.getElementById(inputId);
        input.disabled = isDisabled;

        if (isDisabled) {
            input.value = '';
            input.classList.add("bg-gray-100", "cursor-not-allowed");
            input.classList.remove("bg-white");
        } else {
            input.classList.remove("bg-gray-100", "cursor-not-allowed");
            input.classList.add("bg-white");
        }
    }

    function toggleLatePenalty() {
        const enabled = document.getElementById('is_penalty_enabled').checked;
        setDisabledState('late_fee_amount', !enabled);
        setDisabledState('late_fee_days', !enabled);
    }

    function toggleMoveoutPenalty() {
        const checked = document.getElementById('is_penalty_on_moveout').checked;
        setDisabledState('moveout_penalty_amount', !checked);
    }

    function toggleRoomChangePenalty() {
        const checked = document.getElementById('is_penalty_on_room_change').checked;
        setDisabledState('room_change_penalty_amount', !checked);
    }

    window.onload = function () {
        toggleLatePenalty();
        toggleMoveoutPenalty();
        toggleRoomChangePenalty();
    };

    </script>
</body>
</html>
