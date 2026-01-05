@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Detail Teknisi') }}</div>

                <div class="card-body">
                    <h3>{{ $teknisi->name }}</h3>
                    <p><strong>Email:</strong> {{ $teknisi->email }}</p>
                    <p><strong>Nomor Telepon:</strong> {{ $teknisi->phone_number }}</p>
                    <p><strong>Alamat:</strong> {{ $teknisi->address }}</p>
                    <p><strong>Status:</strong> {{ $teknisi->status }}</p>

                    <hr>

                    @if (Auth::user()->role !== 'admin')
                        <h4>Pesan Layanan</h4>
                        <form method="POST" action="{{ route('bookings.store') }}">
                            @csrf
                            <input type="hidden" id="technician_id" name="technician_id" value="{{ $teknisi->id }}">
                            @error('technician_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="mb-3">
                                <label for="service_id" class="form-label">Layanan</label>
                                <select class="form-control" id="service_id" name="service_id" required>
                                    <option value="">Pilih Layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->name }} (Rp{{ number_format($service->price, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="booking_date" class="form-label">Tanggal Booking</label>
                                <input type="datetime-local" class="form-control" id="booking_date" name="booking_date" required>
                                @error('booking_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="estimated_price" class="form-label">Estimasi Harga</label>
                                <input type="text" class="form-control" id="estimated_price" name="estimated_price" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">Buat Booking</button>
                        </form>
                    @else
                        <div class="alert alert-info">Admin tidak dapat memesan layanan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceSelect = document.getElementById('service_id');
        const estimatedPriceInput = document.getElementById('estimated_price');

        serviceSelect.addEventListener('change', function () {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            if (price) {
                estimatedPriceInput.value = 'Rp' + parseFloat(price).toLocaleString('id-ID');
            } else {
                estimatedPriceInput.value = '';
            }
        });
    });
</script>
@endsection