@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Konfirmasi Pembayaran') }}</div>

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

                    <h5 class="card-title">Booking #{{ $booking->id }}</h5>
                    <p class="card-text">Layanan: {{ $booking->service->name }}</p>
                    <p class="card-text">Teknisi: {{ $booking->technician->name }}</p>
                    
                    @php
                        $finalPrice = $booking->revised_price ?? $booking->estimated_price;
                    @endphp

                    <div class="alert alert-info">
                        <h4>Total Tagihan: <strong>Rp {{ number_format($finalPrice, 2, ',', '.') }}</strong></h4>
                    </div>

                    <hr>

                    <h5>Langkah Pembayaran:</h5>
                    <ol>
                        <li>Silakan lakukan transfer sejumlah total tagihan ke rekening berikut:</li>
                        <ul class="list-unstyled ml-4">
                            <li><strong>Bank BCA</strong></li>
                            <li>No. Rekening: <strong>123-456-7890</strong></li>
                            <li>Atas Nama: <strong>PT RepairGo Indonesia</strong></li>
                        </ul>
                        <li>Unggah bukti transfer Anda pada form di bawah ini.</li>
                        <li>Admin akan segera memverifikasi pembayaran Anda.</li>
                    </ol>

                    <hr>

                    <form method="POST" action="{{ route('bookings.pay.process', $booking->id) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group row mb-3">
                            <label for="payment_proof" class="col-md-4 col-form-label text-md-right">{{ __('Upload Bukti Pembayaran') }}</label>

                            <div class="col-md-6">
                                <input id="payment_proof" type="file" class="form-control @error('payment_proof') is-invalid @enderror" name="payment_proof" required>

                                @error('payment_proof')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Konfirmasi Pembayaran') }}
                                </button>
                                <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
