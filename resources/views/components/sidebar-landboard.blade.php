<div style="background: #ede0d4; padding: 24px; height: 100vh; width: 240px; box-shadow: 2px 0 8px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center;">
    @php
        $user = auth()->user();
    @endphp

    {{-- Foto Profil --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
             alt="Avatar"
             style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #b08968;">
        <p style="margin-top: 10px; font-weight: 600; color: #5e503f;">{{ $user->username ?? 'User' }}</p>
    </div>

    {{-- Navigasi --}}
    <nav style="width: 100%;">
        <ul style="list-style: none; padding: 0; margin: 0;">
            @php
                $navStyle = 'display: block; padding: 10px 16px; border-radius: 8px; color: #5e503f; text-decoration: none; font-size: 14px; transition: background 0.2s ease;';
                $hoverScript = 'onmouseover="this.style.background=\'#ddb892\'" onmouseout="this.style.background=\'transparent\'"';
            @endphp

            <li style="margin-bottom: 8px;">
                <a href="{{ route('landboard.dashboard.index') }}" style="{{ $navStyle }}" {!! $hoverScript !!}>🏠 Dashboard</a>
            </li>
            <li style="margin-bottom: 8px;">
                <a href="{{ route('landboard.profile.update-form') }}" style="{{ $navStyle }}" {!! $hoverScript !!}>⚙️ Profil</a>
            </li>
            <li style="margin-bottom: 8px;">
                <a href="{{ route('landboard.rooms.create-form') }}" style="{{ $navStyle }}" {!! $hoverScript !!}>➕ Buat Kamar</a>
            </li>
            <li style="margin-bottom: 8px;">
                <a href="{{ route('landboard.rooms.index') }}" style="{{ $navStyle }}" {!! $hoverScript !!}>📋 Data Kamar</a>
            </li>
            <li style="margin-bottom: 8px;">
                <a href="{{ route('landboard.current-tenants') }}" style="{{ $navStyle }}" {!! $hoverScript !!}>👤 Tenant Menghuni</a>
            </li>
            <li style="margin-bottom: 8px;">
                <a href="{{ route('landboard.rental-history.index') }}" style="{{ $navStyle }}" {!! $hoverScript !!}>📜 Riwayat Sewa</a>
            </li>
            <li style="margin-bottom: 8px;">
                <a href="{{ route('penalty.edit') }}" style="{{ $navStyle }}" {!! $hoverScript !!}>⚖️ Pengaturan Penalti</a>
            </li>
            <li style="margin-top: 24px;">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit"
                            style="width: 100%; padding: 10px 16px; background: #b08968; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; transition: background 0.2s ease;"
                            onmouseover="this.style.background='#7f5539'" onmouseout="this.style.background='#b08968'">
                        🚪 Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>
