@extends('layouts.app')

@section('sidebar')
    @include('teknisi.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard Teknisi</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Financial Stats -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Saldo Saat Ini (Belum Dibayar)</h5>
                    <p class="card-text fs-2 fw-bold">Rp {{ number_format($balance, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Telah Dibayarkan</h5>
                    <p class="card-text fs-2 fw-bold">Rp {{ number_format($totalPaidOut, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan Sejarah</h5>
                    <p class="card-text fs-2 fw-bold">Rp {{ number_format($totalEarnings, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Income Composition Chart -->
    <div class="card">
        <div class="card-header">
            Komposisi Pendapatan
        </div>
        <div class="card-body">
            <style>
                .chart-wrapper {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-wrap: wrap;
                }
                @keyframes rotate-chart {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .doughnut-chart {
                    position: relative;
                    width: 200px;
                    height: 200px;
                    border-radius: 50%;
                    background: conic-gradient(
                        #198754 {{ $paidOutPercentage }}%, 
                        #fd7e14 0
                    );
                    animation: rotate-chart 1s ease-out;
                    filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
                }
                .doughnut-chart::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 80%; /* Thinner ring */
                    height: 80%; /* Thinner ring */
                    background: white;
                    border-radius: 50%;
                }
                .chart-center-text {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    text-align: center;
                }
                .chart-center-text .total-label {
                    font-size: 0.8rem;
                    color: #6c757d;
                }
                .chart-center-text .total-value {
                    font-size: 1.5rem;
                    font-weight: bold;
                }
                .chart-legend {
                    list-style: none;
                    padding-left: 0;
                    margin-left: 2rem;
                }
                .chart-legend li {
                    display: flex;
                    align-items: center;
                    margin-bottom: 0.5rem;
                }
                .legend-color-box {
                    width: 20px;
                    height: 20px;
                    margin-right: 10px;
                    border-radius: 4px;
                }
            </style>
            @if($totalEarnings > 0)
                <div class="chart-wrapper">
                    <div class="doughnut-chart">
                        <div class="chart-center-text">
                            <div class="total-label">Total</div>
                            <div class="total-value">Rp{{ number_format($totalEarnings / 1000, 0) }}k</div>
                        </div>
                    </div>
                    <ul class="chart-legend">
                        <li>
                            <div class="legend-color-box" style="background-color: #198754;"></div>
                            <span>Telah Dibayarkan ({{ number_format($paidOutPercentage, 1) }}%)</span>
                        </li>
                        <li>
                            <div class="legend-color-box" style="background-color: #fd7e14;"></div>
                            <span>Saldo Saat Ini ({{ number_format($balancePercentage, 1) }}%)</span>
                        </li>
                    </ul>
                </div>
            @else
                <div class="text-center">
                    <p>Belum ada pendapatan yang tercatat.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
