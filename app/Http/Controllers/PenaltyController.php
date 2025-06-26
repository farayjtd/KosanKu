<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenaltyController extends Controller
{
    public function edit()
    {
        $landboard = Auth::user()->landboard;
        return view('landboard.penalty.edit', compact('landboard'));
    }

    public function update(Request $request)
    {
        $landboard = Auth::user()->landboard;

        $data = $request->validate([
            'late_fee_amount' => 'nullable|integer|min:0',
            'late_fee_days' => 'nullable|integer|min:0',
            'moveout_penalty_amount' => 'nullable|integer|min:0',
            'room_change_penalty_amount' => 'nullable|integer|min:0',
            'decision_days_before_end' => 'required|integer|min:0',
        ]);

        $isPenaltyEnabled = $request->has('is_penalty_enabled');
        $isMoveoutPenalty = $request->has('is_penalty_on_moveout');
        $isRoomChangePenalty = $request->has('is_penalty_on_room_change');

        $landboard->is_penalty_enabled = $isPenaltyEnabled;
        $landboard->is_penalty_on_moveout = $isMoveoutPenalty;
        $landboard->is_penalty_on_room_change = $isRoomChangePenalty;

        $landboard->late_fee_amount = $isPenaltyEnabled ? $data['late_fee_amount'] ?? 0 : 0;
        $landboard->late_fee_days = $isPenaltyEnabled ? $data['late_fee_days'] ?? 0 : 0;

        $landboard->moveout_penalty_amount = $isMoveoutPenalty ? $data['moveout_penalty_amount'] ?? 0 : 0;
        $landboard->room_change_penalty_amount = $isRoomChangePenalty ? $data['room_change_penalty_amount'] ?? 0 : 0;

        $landboard->decision_days_before_end = $data['decision_days_before_end'];

        $landboard->save();

        return redirect()->back()->with('success', 'Pengaturan penalti berhasil diperbarui.');
    }
}
