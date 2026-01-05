@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Verifikasi Pembayaran') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Booking</th>
                                    <th>Pelanggan</th>
                                    <th>Teknisi</th>
                                    <th>Tgl. Booking</th>
                                    <th>Harga Estimasi/Revisi</th>
                                    <th>Bukti Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->technician->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->booking_date }}</td>
                                        <td>Rp {{ number_format($booking->revised_price ?? $booking->estimated_price, 2, ',', '.') }}</td>
                                        <td>
                                            @if ($booking->payment_proof)
                                                <a href="{{ Storage::url($booking->payment_proof) }}" target="_blank">Lihat Bukti</a>
                                            @else
                                                Tidak ada
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.payments.approve', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('admin.payments.reject', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada pembayaran yang perlu diverifikasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
