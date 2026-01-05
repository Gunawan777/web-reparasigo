<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    /**
     * Display a listing of technicians with their payable balances.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all technicians
        $technicians = User::where('role', 'teknisi')->get();

        // Calculate the payable balance for each technician
        $payouts = $technicians->map(function ($technician) {
            $balance = Booking::where('technician_id', $technician->id)
                                ->where('payment_status', 'paid')
                                ->where('payout_status', 'unpaid')
                                ->sum(DB::raw('final_price - commission_amount'));
            
            $technician->balance = $balance;
            return $technician;
        });

        return view('admin.payouts.index', compact('payouts'));
    }

    /**
     * Mark all unpaid bookings for a technician as paid.
     *
     * @param  \App\Models\User  $technician
     * @return \Illuminate\Http\Response
     */
    public function store(User $technician)
    {
        // Find all paid but unsettled bookings for the technician
        $bookingsToPayout = Booking::where('technician_id', $technician->id)
                                    ->where('payment_status', 'paid')
                                    ->where('payout_status', 'unpaid');

        if ($bookingsToPayout->count() == 0) {
            return redirect()->route('admin.payouts.index')->with('error', 'Tidak ada saldo terutang untuk teknisi ini.');
        }

        // Mark them as paid
        $bookingsToPayout->update(['payout_status' => 'paid']);

        return redirect()->route('admin.payouts.index')->with('success', 'Payout untuk ' . $technician->name . ' berhasil dicatat.');
    }
}