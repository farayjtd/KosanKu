@php
    $user = auth()->user();
@endphp

<div style="background: #f1f5f9; padding: 20px; height: 100vh; width: 220px; box-shadow: 2px 0 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center;">
    {{-- Foto Profil --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
             alt="Avatar"
             style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #cbd5e1;">
        <p style="margin-top: 10px; font-weight: bold; color: #1e293b;">{{ $user->username ?? 'Tenant' }}</p>
    </div>

    {{-- Navigasi --}}
    <nav style="width: 100%;">
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 12px;">
                <a href="{{ route('tenant.dashboard.index') }}" style="text-decoration: none; color: #334155;">🏠 Dashboard</a>
            </li>
            <li style="margin-bottom: 12px;">
                <a href="{{ route('tenant.profile.update-form') }}" style="text-decoration: none; color: #334155;">⚙️ Profil</a>
            </li>
            <li style="margin-bottom: 12px;">
                <a href="{{ route('tenant.room-history.index') }}" style="text-decoration: none; color: #334155;">📜 Riwayat Sewa</a>
            </li>
            <li style="margin-top: 20px;">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: #ef4444; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; width: 100%;">🚪 Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</div>
