<?php

namespace App\Http\Controllers;

use App\Models\RentalHistory;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class TenantController extends Controller
{
    public function dashboard()
    {
        $accountId = Auth::id();

        $tenant = Tenant::with('account')->where('account_id', $accountId)->firstOrFail();

        $activeRental = $tenant->rentalHistories()
            ->with([
                'room.facilities',
                'room.rules',
                'room.photos',
                'room.landboard.account' 
            ])
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();

        return view('tenant.dashboard', compact('tenant', 'activeRental'));
    }

    public function createProfile()
    {
        return view('tenant.profile.complete');
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'alt_phone'        => 'nullable|string|max:20',
            'avatar'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address'          => 'required|string|max:255',
            'gender'           => 'required|in:male,female',
            'activity_type'    => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'bank_name'        => 'required|string|max:100',
            'bank_account'     => 'required|digits_between:10,16',
        ]);

        $user = Auth::user();

        $avatarPath = $request->hasFile('avatar')
            ? $request->file('avatar')->store('avatars', 'public')
            : $user->avatar;

        $user->update([
            'name'            => $request->name,
            'phone'           => $request->phone,
            'alt_phone'       => $request->alt_phone,
            'avatar'          => $avatarPath,
            'is_first_login'  => false,
            'bank_name'       => $request->bank_name,
            'bank_account'    => $request->bank_account,
        ]);

        Auth::setUser($user->fresh());

        $tenant = Tenant::where('account_id', $user->id)->firstOrFail();

        $tenantPhotoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('tenant_photos', 'public')
            : $tenant->photo;

        $tenant->update([
            'photo'             => $tenantPhotoPath,
            'address'           => $request->address,
            'gender'            => $request->gender,
            'activity_type'     => $request->activity_type,
            'institution_name'  => $request->institution_name,
        ]);

        return redirect()->route('tenant.dashboard.index')->with('success', 'Profil berhasil disimpan.');
    }

    public function editProfile()
    {
        $account = Auth::user();
        $tenant = $account->tenant;

        return view('tenant.profile.edit', compact('account', 'tenant'));
    }

    public function updateProfile(Request $request)
    {
        $account = Auth::user();
        $tenant = $account->tenant;

        $validated = $request->validate([
            'username'         => 'required|string|min:6|max:20|unique:accounts,username,' . $account->id,
            'email'            => 'nullable|email|unique:accounts,email,' . $account->id,
            'password'         => ['nullable', 'string', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()],
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'alt_phone'        => 'nullable|string|max:20',
            'avatar'           => 'nullable|image|max:2048',
            'photo'            => 'nullable|image|max:2048',
            'address'          => 'required|string|max:255',
            'gender'           => 'nullable|in:male,female',
            'activity_type'    => 'nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'bank_name'        => 'required|string|max:100',
            'bank_account'     => 'required|digits_between:10,16',
        ]);

        $account->fill([
            'username'       => $validated['username'],
            'email'          => $validated['email'] ?? null,
            'name'           => $validated['name'],
            'phone'          => $validated['phone'],
            'alt_phone'      => $validated['alt_phone'] ?? null,
            'bank_name'      => $validated['bank_name'],
            'bank_account'   => $validated['bank_account'],
        ]);

        if ($request->hasFile('avatar')) {
            $account->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $account->password = $validated['password'];
        }

        $account->save();

        $tenantUpdate = [
            'address'          => $validated['address'],
            'gender'           => $validated['gender'] ?? null,
            'activity_type'    => $validated['activity_type'] ?? null,
            'institution_name' => $validated['institution_name'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            $tenantUpdate['photo'] = $request->file('photo')->store('tenant_photos', 'public');
        }

        $tenant->update($tenantUpdate);

       return redirect()->back()->with([
            'success' => 'Sewa berhasil diperpanjang.',
            'show_invoice' => true,
            'rental_id' => $newRental->id,
        ]);
    }

    public function decide(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $activeRental = RentalHistory::where('tenant_id', $tenant->id)
            ->whereNull('end_date')
            ->latest()
            ->first();

        if (!$activeRental) {
            return redirect()->back()->with('error', 'Sewa aktif tidak ditemukan.');
        }

        $isContinue = $request->has('is_continue') && $request->input('is_continue') == '1';
        $activeRental->is_continue = $isContinue;
        $activeRental->next_duration_months = $isContinue ? $request->input('duration_months') : null;
        $activeRental->save();

        if ($isContinue && $request->has('duration_months')) {
            $currentStart = Carbon::parse($activeRental->start_date);
            $currentEnd = $currentStart->copy()->addMonths($activeRental->duration_months); 

            $nextDuration = (int) $request->input('duration_months');
            $nextStart = $currentEnd;
            $nextEnd = $nextStart->copy()->addMonths($nextDuration);

            RentalHistory::create([
                'tenant_id'        => $tenant->id,
                'room_id'          => $activeRental->room_id,
                'start_date'       => $nextStart,
                'end_date'         => $nextEnd,
                'duration_months'  => $nextDuration,
                'is_continue'      => false,
                'next_duration_months' => null,
                'status' => 'upcoming' 
            ]);
        }

        return redirect()->route('tenant.dashboard.index')->with('success', 'Keputusan sewa berhasil disimpan.');
    }

    public function leaveRoom(Request $request)
    {
        $tenant = Auth::user()->tenant;

        if (! $tenant) {
            return back()->with('error', 'Data tenant tidak ditemukan.');
        }

        $activeRental = RentalHistory::where('tenant_id', $tenant->id)
            ->whereNull('end_date')
            ->latest('start_date')
            ->first();

        if (! $activeRental) {
            return back()->with('error', 'Tidak ada sewa aktif yang bisa dihentikan.');
        }

        $activeRental->update([
            'end_date' => now(),
            'is_continue' => false,
        ]);

        if ($activeRental->room) {
            $activeRental->room->update(['status' => 'available']);
        }

        return back()->with('success', 'Anda telah berhasil keluar dari kamar.');
    }
}
