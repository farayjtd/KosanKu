<?php

namespace App\Http\Controllers;

use App\Models\Landboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class LandboardController extends Controller
{
    public function dashboard()
    {
        return view('landboard.dashboard');
    }

    public function createProfile()
    {
        return view('landboard.profile.complete');
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kost_name' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'village' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'full_address' => 'required|string|max:255',
            'bank_name' => 'required|string|max:100',
            'bank_account' => 'required|digits_between:10,16',
        ]);

        $user = Auth::user();

        /** @var \App\Models\Account $user */
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'alt_phone' => $request->alt_phone,
            'avatar' => $request->file('avatar')
                ? $request->file('avatar')->store('avatars', 'public')
                : $user->avatar,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'is_first_login' => false,
        ]);

        Landboard::updateOrCreate(
            ['account_id' => $user->id],
            [
                'kost_name' => $request->kost_name,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'village' => $request->village,
                'postal_code' => $request->postal_code,
                'full_address' => $request->full_address,
            ]
        );

        return redirect()->route('landboard.dashboard.index')->with('success', 'Profil berhasil dilengkapi!');
    }

    public function editProfile()
    {
        $account = Auth::user();
        $landboard = $account->landboard;

        return view('landboard.profile.edit', compact('account', 'landboard'));
    }

    public function updateProfile(Request $request)
    {
        $account = Auth::user();
        $landboard = $account->landboard;

        $validated = $request->validate([
            'username' => 'required|string|min:8|max:12|unique:accounts,username,' . $account->id,
            'password' => ['nullable', 'string', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()],
            'email' => 'required|string|email|unique:accounts,email,' . $account->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kost_name' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'village' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'full_address' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'bank_account' => 'required|digits_between:10,16',
        ]);

        /** @var \App\Models\Account $account */
        $account->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'alt_phone' => $validated['alt_phone'],
            'avatar' => $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : $account->avatar,
            'password' => $validated['password'] ?? $account->password,
            'bank_name' => $validated['bank_name'],
            'bank_account' => $validated['bank_account'],
        ]);

        $landboard->update([
            'kost_name' => $validated['kost_name'],
            'province' => $validated['province'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'village' => $validated['village'],
            'postal_code' => $validated['postal_code'],
            'full_address' => $validated['full_address'],
        ]);

        return back()->with('success', 'Akun dan data kost berhasil diperbarui.');
    }
}
