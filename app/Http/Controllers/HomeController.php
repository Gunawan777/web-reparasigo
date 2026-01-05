<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role == 'admin') {
            // Data for Stat Cards
            $paymentsToVerify = Booking::where('payment_status', 'verifying')->count();
            $totalCommission = Booking::where('payment_status', 'paid')->sum('commission_amount');
            $totalPayoutOwed = Booking::where('payment_status', 'paid')
                                  ->where('payout_status', 'unpaid')
                                  ->sum(DB::raw('final_price - commission_amount'));

            // Data for Doughnut Chart
            $platformTotalRevenue = Booking::where('payment_status', 'paid')->sum('final_price');
            $commissionPercentage = ($platformTotalRevenue > 0) ? ($totalCommission / $platformTotalRevenue) * 100 : 0;


            return view('admin.dashboard', compact(
                'paymentsToVerify', 
                'totalCommission', 
                'totalPayoutOwed', 
                'platformTotalRevenue',
                'commissionPercentage'
            ));
        } elseif ($user->role == 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        } elseif ($user->role == 'pelanggan') {
            return redirect()->route('pelanggan.dashboard');
        }

        return view('home');
    }
}
