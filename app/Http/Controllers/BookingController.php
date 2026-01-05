<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'pelanggan') {
            return redirect()->route('pelanggan.dashboard');
        } elseif (Auth::user()->role == 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        } else {
            return redirect()->route('home');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $service = Service::findOrFail($request->service_id);

        $booking = Booking::create([
            'customer_id' => Auth::id(),
            'technician_id' => $request->technician_id, // This will now be null if not passed
            'service_id' => $request->service_id,
            'booking_date' => $request->booking_date,
            'description' => $request->description,
            'estimated_price' => $service->price,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Booking berhasil dibuat!');
    }

    public function accept(Request $request, Booking $booking)
    {
        // Pastikan hanya teknisi yang dituju yang bisa menerima booking ini
        if ($booking->technician_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        $booking->status = 'accepted';
        $booking->save();

        return redirect()->back()->with('success', 'Booking berhasil diterima!');
    }

    public function reject(Request $request, Booking $booking)
    {
        // Pastikan hanya teknisi yang dituju yang bisa menolak booking ini
        if ($booking->technician_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        $booking->status = 'rejected';
        $booking->save();

        return redirect()->back()->with('success', 'Booking berhasil ditolak!');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:accepted,in_progress,completed,cancelled',
        ]);

        if ($booking->technician_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
    }

    public function requestPriceRevision(Request $request, Booking $booking)
    {
        $request->validate([
            'revised_price' => 'required|numeric|min:0',
        ]);

        if ($booking->technician_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        $booking->revised_price = $request->revised_price;
        $booking->save();

        return redirect()->back()->with('success', 'Pengajuan revisi harga berhasil dikirim!');
    }

    public function showPaymentForm(Request $request, Booking $booking)
    {
        // Pastikan hanya pelanggan yang bersangkutan yang bisa melihat form pembayaran
        if ($booking->customer_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        // Tampilkan view untuk pembayaran
        return view('pelanggan.bookings.pay', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        if ($booking->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Pembayaran untuk booking ini tidak dapat diproses.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the payment proof
        $path = $request->file('payment_proof')->store('public/proofs');

        $booking->payment_proof = $path;
        $booking->payment_status = 'verifying';
        $booking->save();

        return redirect()->route('pelanggan.dashboard')->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function confirmCompletion(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        if ($booking->status === 'completed') {
            return redirect()->back()->with('error', 'Layanan ini sudah dikonfirmasi selesai.');
        }

        $booking->status = 'completed';
        $booking->save();

        return redirect()->back()->with('success', 'Layanan berhasil dikonfirmasi selesai!');
    }
}
