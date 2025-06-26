<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Landboard;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showAuthForm()
    {
        return view('auth');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:accounts,username|min:8|max:12',
            'password' => ['required', 'string', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()],
            'email' => 'required|string|unique:accounts,email',
            'role' => 'required|in:tenant,landboard',
        ]);

        $account = Account::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'is_first_login' => true,
        ]);

        if ($validated['role'] === 'tenant') {
            Tenant::create([
                'account_id' => $account->id,
            ]);
        }

        if ($validated['role'] === 'landboard') {
            Landboard::firstOrCreate([
                'account_id' => $account->id,
            ]);
        }

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $account = Auth::user();

            switch ($account->role) {
                case 'admin':
                    // return redirect()->route('admin.dashboard'); coming soon
                    break;

                case 'landboard':
                    return $account->is_first_login
                        ? redirect()->route('landboard.profile.complete-form')
                        : redirect()->route('landboard.dashboard.index');

                case 'tenant':
                    return $account->is_first_login
                        ? redirect()->route('tenant.profile.complete-form')
                        : redirect()->route('tenant.dashboard.index');

                default:
                    Auth::logout();
                    return redirect()->route('auth')->withErrors(['username' => 'Role tidak dikenali.']);
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth');
    }
}
