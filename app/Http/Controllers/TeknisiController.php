<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeknisiController extends Controller
{
    /**
     * Display the main technician dashboard with navigation.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $technicianId = Auth::id();

        // Calculate current balance (paid by customer, not paid out by admin)
        $balance = Booking::where('technician_id', $technicianId)
                            ->where('payment_status', 'paid')
                            ->where('payout_status', 'unpaid')
                            ->sum(DB::raw('final_price - commission_amount'));

        // Calculate total amount paid out by admin
        $totalPaidOut = Booking::where('technician_id', $technicianId)
                                ->where('payout_status', 'paid')
                                ->sum(DB::raw('final_price - commission_amount'));

        // Calculate lifetime earnings
        $totalEarnings = $balance + $totalPaidOut;

        // Calculate percentages for the doughnut chart
        $paidOutPercentage = ($totalEarnings > 0) ? ($totalPaidOut / $totalEarnings) * 100 : 0;
        $balancePercentage = ($totalEarnings > 0) ? ($balance / $totalEarnings) * 100 : 0;


        return view('teknisi.dashboard', compact('balance', 'totalPaidOut', 'totalEarnings', 'paidOutPercentage', 'balancePercentage'));
    }

    /**
     * Display a listing of active bookings for the authenticated technician.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeBookings()
    {
        $activeBookings = Booking::where('technician_id', Auth::id())
                                ->whereIn('status', ['pending', 'accepted', 'in_progress'])
                                ->with(['customer', 'service'])
                                ->orderBy('created_at', 'desc')
                                ->get();
                                
        return view('teknisi.bookings.active', compact('activeBookings'));
    }

    /**
     * Display a listing of historical bookings for the authenticated technician.
     *
     * @return \Illuminate\Http\Response
     */
    public function historyBookings()
    {
        $historyBookings = Booking::where('technician_id', Auth::id())
                                ->whereIn('status', ['completed', 'cancelled', 'rejected'])
                                ->with(['customer', 'service', 'review'])
                                ->orderBy('created_at', 'desc')
                                ->get();
                                
        return view('teknisi.bookings.history', compact('historyBookings'));
    }
}
