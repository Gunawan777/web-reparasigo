<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentVerificationController extends Controller
{
    /**
     * Display a listing of bookings awaiting payment verification.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::with(['customer', 'technician'])->where('payment_status', 'verifying')->latest()->get();
        return view('admin.payments.index', compact('bookings'));
    }

    /**
     * Approve the payment for a booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function approve(Booking $booking)
    {
        if ($booking->payment_status !== 'verifying') {
            return redirect()->route('admin.payments.index')->with('error', 'Booking ini tidak dalam status verifikasi.');
        }

        // Set final price
        $finalPrice = $booking->revised_price ?? $booking->estimated_price;
        
        // Update booking status and calculate commission
        $booking->payment_status = 'paid';
        $booking->final_price = $finalPrice;
        $booking->commission_amount = $finalPrice * 0.10; // Commission logic is here
        $booking->save();

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran untuk Booking #' . $booking->id . ' telah disetujui.');
    }

    /**
     * Reject the payment for a booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function reject(Booking $booking)
    {
        if ($booking->payment_status !== 'verifying') {
            return redirect()->route('admin.payments.index')->with('error', 'Booking ini tidak dalam status verifikasi.');
        }

        // Delete the payment proof file
        if ($booking->payment_proof) {
            Storage::delete($booking->payment_proof);
        }

        // Revert booking status
        $booking->payment_status = 'pending';
        $booking->payment_proof = null;
        $booking->save();

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran untuk Booking #' . $booking->id . ' telah ditolak.');
    }
}