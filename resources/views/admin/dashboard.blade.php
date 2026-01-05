@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Admin Dashboard</h1>

            <div class="row">
                <!-- Payments to Verify Card -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Pembayaran Perlu Verifikasi</h5>
                            <p class="card-text fs-2 fw-bold">{{ $paymentsToVerify }}</p>
                            <a href="{{ route('admin.payments.index') }}" class="text-white stretched-link">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                <!-- Total Commission Card -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Total Komisi Didapat</h5>
                            <p class="card-text fs-2 fw-bold">Rp {{ number_format($totalCommission, 2, ',', '.') }}</p>
                            <a href="{{ route('admin.reports.commission') }}" class="text-white stretched-link">Lihat Laporan</a>
                        </div>
                    </div>
                </div>

                <!-- Total Payout Owed Card -->
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Total Saldo Teknisi (Belum Dibayar)</h5>
                            <p class="card-text fs-2 fw-bold">Rp {{ number_format($totalPayoutOwed, 2, ',', '.') }}</p>
                            <a href="{{ route('admin.payouts.index') }}" class="text-white stretched-link">Proses Payout</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platform Income Composition Chart -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Komposisi Pendapatan Platform
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
                                        #0d6efd {{ $commissionPercentage }}%, 
                                        #adb5bd 0
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
                            @if($platformTotalRevenue > 0)
                                <div class="chart-wrapper">
                                    <div class="doughnut-chart">
                                        <div class="chart-center-text">
                                            <div class="total-label">Total</div>
                                            <div class="total-value">Rp{{ number_format($platformTotalRevenue / 1000, 0) }}k</div>
                                        </div>
                                    </div>
                                    <ul class="chart-legend">
                                        <li>
                                            <div class="legend-color-box" style="background-color: #0d6efd;"></div>
                                            <span>Komisi Platform ({{ number_format($commissionPercentage, 1) }}%)</span>
                                        </li>
                                        <li>
                                            <div class="legend-color-box" style="background-color: #adb5bd;"></div>
                                            <span>Pendapatan Teknisi ({{ number_format(100 - $commissionPercentage, 1) }}%)</span>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="text-center">
                                    <p>Belum ada pendapatan yang tercatat di platform.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
