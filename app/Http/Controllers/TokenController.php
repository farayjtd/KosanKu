<?php

namespace App\Http\Controllers;

use App\Models\RentalHistory;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function generate(Request $request, $roomId)
    {
        $room = Room::findOrFail($roomId);

        if ($room->status !== 'available') {
            return back()->with('error', 'Kamar tidak tersedia.');
        }

        $request->validate([
            'rental_duration' => 'required|in:1,3,6,12',
        ]);

        $existing = Token::where('room_id', $roomId)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($existing) {
            return back()->with('error', 'Token aktif sudah ada untuk kamar ini.');
        }

        $token = strtoupper(Str::random(6));

        Token::create([
            'room_id'    => $roomId,
            'token'      => $token,
            'used'       => false,
            'expires_at' => now()->addMinutes(10),
            'duration'   => $request->rental_duration,
        ]);

        return back()->with('success', "Token berhasil dibuat: $token (berlaku 10 menit)");
    }

    public function use(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:6|exists:tokens,token',
        ]);

        $tokenInput = strtoupper($request->token);

        $token = Token::where('token', $tokenInput)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $token) {
            return back()->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }

        $room = Room::findOrFail($token->room_id);

        if ($room->status !== 'available') {
            return back()->with('error', 'Kamar sudah tidak tersedia.');
        }

        $account = Auth::user();
        $tenant = Tenant::where('account_id', $account->id)->first();

        if (! $tenant) {
            return back()->with('error', 'Data tenant tidak ditemukan.');
        }

        RentalHistory::create([
            'tenant_id'       => $tenant->id,
            'room_id'         => $room->id,
            'start_date'      => now(),
            'end_date'        => null,
            'duration_months' => $token->duration,
        ]);

        $room->update(['status' => 'booked']);

        $token->update(['used' => true]);

        $tenant->update(['room_id' => $room->id]);

        return redirect()->route('tenant.dashboard.index')->with('success', 'Token berhasil digunakan. Anda resmi menghuni kamar ini.');
    }
}
