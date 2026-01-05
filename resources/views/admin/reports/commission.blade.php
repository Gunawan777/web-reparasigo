@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Laporan Komisi</h1>

            <!-- Summary Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Seluruh Komisi</h5>
                    <p class="card-text fs-2 fw-bold text-success">Rp {{ number_format($totalCommission, 2, ',', '.') }}</p>
                </div>
            </div>

            <!-- Commission Details Table -->
            <div class="card">
                <div class="card-header">
                    Rincian Transaksi
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID Booking</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Teknisi</th>
                                    <th>Layanan</th>
                                    <th>Harga Final</th>
                                    <th>Komisi (10%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookingsWithCommission as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->updated_at->format('d F Y H:i') }}</td>
                                        <td>{{ $booking->customer->name }}</td>
                                        <td>{{ $booking->technician->name }}</td>
                                        <td>{{ $booking->service->name }}</td>
                                        <td>Rp {{ number_format($booking->final_price, 2, ',', '.') }}</td>
                                        <td><strong>Rp {{ number_format($booking->commission_amount, 2, ',', '.') }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada transaksi dengan komisi.</td>
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
