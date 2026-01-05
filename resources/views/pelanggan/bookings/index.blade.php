@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Daftar Pesanan Anda') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($bookings->isEmpty())
                        <p>Anda belum memiliki pesanan.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($bookings as $booking)
                                <li class="list-group-item">
                                    <h5>Layanan: {{ $booking->service->name }}</h5>
                                    <p>Teknisi: {{ $booking->technician->name }}</p>
                                    <p>Harga: Rp{{ number_format($booking->revised_price ?? $booking->estimated_price, 0, ',', '.') }}</p>
                                    <p>Tanggal Booking: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y H:i') }}</p>
                                    <p>Deskripsi: {{ $booking->description }}</p>
                                    <p>Status Pengerjaan: {{ ucfirst($booking->status) }}</p>
                                    <p>Status Pembayaran: {{ ucfirst($booking->payment_status) }}</p>
                                    <div class="mt-2">
                                        @if ($booking->payment_status == 'pending' && !in_array($booking->status, ['rejected', 'cancelled', 'pending']))
                                            <a href="{{ route('bookings.pay.form', $booking->id) }}" class="btn btn-success btn-sm">Bayar Sekarang</a>
                                        @endif
                                        @if ($booking->status == 'in_progress' && $booking->payment_status == 'paid')
                                            <form action="{{ route('bookings.confirmCompletion', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Konfirmasi Selesai</button>
                                            </form>
                                        @endif
                                        @if ($booking->status == 'completed' && $booking->payment_status == 'paid' && !$booking->review)
                                            <div class="mt-3">
                                                <h6>Berikan Ulasan</h6>
                                                <form action="{{ route('reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                    <div class="mb-2">
                                                        <label for="rating-{{ $booking->id }}" class="form-label">Rating</label>
                                                        <select name="rating" id="rating-{{ $booking->id }}" class="form-select form-select-sm w-auto">
                                                            <option value="5">5 Bintang</option>
                                                            <option value="4">4 Bintang</option>
                                                            <option value="3">3 Bintang</option>
                                                            <option value="2">2 Bintang</option>
                                                            <option value="1">1 Bintang</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="comment-{{ $booking->id }}" class="form-label">Komentar</label>
                                                        <textarea name="comment" id="comment-{{ $booking->id }}" class="form-control" rows="2"></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-info btn-sm">Kirim Ulasan</button>
                                                </form>
                                            </div>
                                        @elseif ($booking->review)
                                            <div class="mt-3">
                                                <h6>Ulasan Anda:</h6>
                                                <p>Rating: {{ $booking->review->rating }} Bintang</p>
                                                <p>Komentar: {{ $booking->review->comment }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection