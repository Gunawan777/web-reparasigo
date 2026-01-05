<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class ReportController extends Controller
{
    /**
     * Display a commission report.
     *
     * @return \Illuminate\Http\Response
     */
    public function commissionReport()
    {
        $bookingsWithCommission = Booking::where('payment_status', 'paid')
                                        ->whereNotNull('commission_amount')
                                        ->with(['technician', 'service', 'customer'])
                                        ->latest()
                                        ->get();

        $totalCommission = $bookingsWithCommission->sum('commission_amount');

        return view('admin.reports.commission', compact('bookingsWithCommission', 'totalCommission'));
    }
}