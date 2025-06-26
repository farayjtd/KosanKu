<?php

namespace App\Http\Controllers;

use App\Models\RentalHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalHistoryController extends Controller
{
    public function tenantHistory()
    {
        $tenant = Auth::user()->tenant;

        // Ambil histori dengan relasi room, landboard, dan payment
        $histories = RentalHistory::with([
            'room.landboard',  // ← ini penting agar bisa akses nama_kos
            'payment'
        ])
        ->where('tenant_id', $tenant->id)
        ->orderByDesc('start_date')
        ->get();

        // Cari sewa aktif
        $activeRental = $histories->first(function ($history) {
            $start = Carbon::parse($history->start_date);
            $end = $history->end_date 
                ? Carbon::parse($history->end_date) 
                : $start->copy()->addMonths($history->duration_months);

            return now()->between($start, $end);
        });

        $showDecisionForm = false;

        if ($activeRental && $activeRental->room && $activeRental->room->landboard) {
            $start = Carbon::parse($activeRental->start_date);
            $end = $activeRental->end_date 
                ? Carbon::parse($activeRental->end_date) 
                : $start->copy()->addMonths($activeRental->duration_months);

            $paymentStatus = $activeRental->payment?->status;

            // Ambil jumlah hari dari Landboard untuk memunculkan form keputusan
            $landboard = $activeRental->room->landboard;
            $daysBefore = $landboard->decision_days_before_end ?? 5;

            // Tampilkan form jika tinggal 'x hari lagi' dan sudah dibayar
            $showDecisionForm = now()->diffInDays($end, false) <= $daysBefore && $paymentStatus === 'paid';
        }

        return view('tenant.room-history.index', [
            'histories' => $histories,
            'activeRental' => $activeRental,
            'showDecisionForm' => $showDecisionForm,
        ]);
    }


    public function landboardHistory()
    {
        $landboard = Auth::user()->landboard;

        if (! $landboard) {
            return back()->with('error', 'Data landboard tidak ditemukan.');
        }

        $histories = RentalHistory::with(['room', 'tenant.account'])
            ->whereHas('room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })
            ->orderByDesc('start_date')
            ->get();

        return view('landboard.room-history.index', compact('histories'));
    }

    public function currentTenants()
    {
        $landboard = Auth::user()->landboard;

        if (! $landboard) {
            return back()->with('error', 'Data landboard tidak ditemukan.');
        }

        $currentTenants = RentalHistory::with(['tenant.account', 'room'])
            ->whereNull('end_date')
            ->whereHas('room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })
            ->get();

        return view('landboard.tenant.index', compact('currentTenants'));
    }

    public function showCurrentTenant($id)
    {
        $history = RentalHistory::with(['tenant.account', 'room'])->findOrFail($id);

        $landboard = Auth::user()->landboard;

        if ($history->room->landboard_id !== $landboard->id) {
            abort(403);
        }

        return view('landboard.tenant.show', compact('history'));
    }
}
