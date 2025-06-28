<div class="fixed bottom-6 left-1/2 transform -translate-x-1/2
            bg-white rounded-lg shadow-[0px_0px_19px_3px_rgba(0,_0,_0,_0.1)] w-fit px-4 py-2 
            flex items-center space-x-4 z-50">
    @php
        $menu = [
            ['icon' => 'bi-house-door', 'label' => 'Dashboard', 'route' => route('landboard.dashboard.index')],
            ['icon' => 'bi-plus-circle', 'label' => 'Buat Kamar', 'route' => route('landboard.rooms.create-form')],
            ['icon' => 'bi-card-list', 'label' => 'Data Kamar', 'route' => route('landboard.rooms.index')],
            ['icon' => 'bi-person-vcard', 'label' => 'Tenant Menghuni', 'route' => route('landboard.current-tenants')],
            ['icon' => 'bi-journal-text', 'label' => 'Riwayat Sewa', 'route' => route('landboard.rental-history.index')],
            ['icon' => 'bi-exclamation-triangle', 'label' => 'Pengaturan Penalti', 'route' => route('penalty.edit')],
            ['icon' => 'bi-person', 'label' => 'Profil', 'route' => route('landboard.profile.update-form')],
        ];
    @endphp

    @foreach ($menu as $item)
        @php
            $isActive = request()->url() === $item['route'];
        @endphp
        <a href="{{ $item['route'] }}"
        class="group relative flex items-center justify-center w-10 h-10 rounded-lg transition-colors
                {{ $isActive ? 'bg-[#1a966d] text-white' : 'hover:bg-gray-300' }}">
            <i class="bi {{ $item['icon'] }} text-xl {{ $isActive ? 'text-white' : 'text-[#1a966d]' }}"></i>
            <span class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 
                        {{ $isActive ? 'bg-[#1a966d]' : 'bg-[#31c594]' }}
                        text-white text-xs rounded px-2 py-1 whitespace-nowrap 
                        transition-opacity duration-300 shadow-md z-10">
                {{ $item['label'] }}
            </span>
        </a>
    @endforeach


    <!-- <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
                class="group relative flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-700 text-white rounded-lg transition-colors">
            <i class="bi bi-box-arrow-right text-xl"></i>
            <span class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 bg-red-500 text-white text-xs rounded px-2 py-1 whitespace-nowrap transition-opacity duration-300 shadow-md z-10">
                Logout
            </span>
        </button>
    </form> -->
</div>
