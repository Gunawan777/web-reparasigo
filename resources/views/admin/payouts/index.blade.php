@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Payout Teknisi') }}</div>

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
                                    <th>ID Teknisi</th>
                                    <th>Nama Teknisi</th>
                                    <th>Saldo Terutang (Nett)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payouts as $payout)
                                    <tr>
                                        <td>{{ $payout->id }}</td>
                                        <td>{{ $payout->name }}</td>
                                        <td><strong>Rp {{ number_format($payout->balance, 2, ',', '.') }}</strong></td>
                                        <td>
                                            @if ($payout->balance > 0)
                                                <form action="{{ route('admin.payouts.store', $payout->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm">Bayarkan Saldo</button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>Tidak ada saldo</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data teknisi.</td>
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
