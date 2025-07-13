@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <!-- Header -->
        <div class="section-header">
            <h1>Dashboard</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Main Dashboard</div>
            </div>
        </div>

        <!-- Statistik Ringkasan Tiket -->
        <div class="row">
            <!-- Tiket Baru -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tiket Baru</h4>
                        </div>
                        <div class="card-body">
                            {{ \App\Models\Ticket::where('status', 'Baru')->count() }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tiket Diproses -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tiket Diproses</h4>
                        </div>
                        <div class="card-body">
                            {{ \App\Models\Ticket::where('status', 'Diproses')->count() }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tiket Disposisi -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tiket Disposisi</h4>
                        </div>
                        <div class="card-body">
                            {{ \App\Models\Ticket::where('status', 'Disposisi')->count() }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tiket Selesai -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tiket Selesai</h4>
                        </div>
                        <div class="card-body">
                            {{ \App\Models\Ticket::where('status', 'Selesai')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visualisasi & Kategori -->
        <div class="row">
            <!-- Top 5 Kategori (Topik) -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Top 5 Topik</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="topCategoriesChart" height="220"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Statistik Urgensi Tiket -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistik Urgensi</h4>
                    </div>
                    <div class="card-body">
                        <!-- Ganti progress bar dengan canvas untuk pie chart -->
                        <canvas id="urgencyPieChart" height="240"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail & Informasi -->
        <div class="row">
            <!-- Tiket Terbaru -->
            <div class="col-md-8">
                <div class="card">
                </div>
            </div>

            <!-- Statistik Tambahan -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Tiket</h4>
                    </div>
                    <div class="card-body">
                        <div class="statistic-details">
                            <!-- Total Tiket -->
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ \App\Models\Ticket::count() }}</div>
                                <div class="detail-name">Total Tiket</div>
                            </div>
                            
                            <!-- Tiket Hari Ini -->
                            <div class="statistic-details-item">
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $todayTickets = \App\Models\Ticket::whereDate('created_at', $today)->count();
                                @endphp
                                <div class="detail-value">{{ $todayTickets }}</div>
                                <div class="detail-name">Tiket Hari Ini</div>
                            </div>
                            
                            <!-- Tingkat Penyelesaian -->
                            <div class="statistic-details-item">
                                @php
                                    $totalClosed = \App\Models\Ticket::where('status', 'Selesai')->count();
                                    $totalTickets = \App\Models\Ticket::count();
                                    $completionRate = $totalTickets > 0 ? round(($totalClosed / $totalTickets) * 100) : 0;
                                @endphp
                                <div class="detail-value">{{ $completionRate }}%</div>
                                <div class="detail-name">Tingkat Penyelesaian</div>
                            </div>
                            
                            <!-- Rating Rata-rata -->
                            <div class="statistic-details-item">
                                @php
                                    $avgRating = \App\Models\Feedback::avg('rating');
                                    $formattedRating = number_format($avgRating ?? 0, 1);
                                @endphp
                                <div class="detail-value">{{ $formattedRating }}</div>
                                <div class="detail-name">Rating Rata-rata</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ======= CHART 1: TOP 5 KATEGORI (BATANG HORIZONTAL) =======
        const topCategoriesCtx = document.getElementById('topCategoriesChart').getContext('2d');
        
        // Dapatkan data top 5 kategori dari PHP
        const categoryData = @json(
            \App\Models\Kategori::withCount('tickets')
                ->orderByDesc('tickets_count')
                ->limit(5)
                ->get()
                ->map(function($kategori) {
                    return [
                        'name' => $kategori->name,
                        'count' => $kategori->tickets_count
                    ];
                })
        );
        
        // Siapkan data untuk chart
        const categoryLabels = categoryData.map(item => item.name);
        const categoryCounts = categoryData.map(item => item.count);
        
        // Buat array warna untuk bar chart
        const categoryColors = [
            'rgba(108, 170, 242, 0.7)',  // Biru muda
            'rgba(255, 205, 86, 0.7)',   // Kuning
            'rgba(115, 230, 115, 0.7)',  // Hijau muda
            'rgba(255, 153, 102, 0.7)',  // Oranye
            'rgba(153, 102, 255, 0.7)',  // Ungu
        ];
        
        const categoryBorders = [
            'rgba(108, 170, 242, 1)',
            'rgba(255, 205, 86, 1)',
            'rgba(115, 230, 115, 1)',
            'rgba(255, 153, 102, 1)',
            'rgba(153, 102, 255, 1)',
        ];
        
        // Bar Chart untuk Top 5 Topik
        new Chart(topCategoriesCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Jumlah Tiket',
                    data: categoryCounts,
                    backgroundColor: categoryColors,
                    borderColor: categoryBorders,
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',  // Membuat bar menjadi horizontal
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' tiket';
                            }
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });

        // ======= CHART 2: STATISTIK URGENSI (PIE) =======
        const urgencyPieCtx = document.getElementById('urgencyPieChart').getContext('2d');
        
        // Data untuk pie chart urgensi
        const urgencyLabels = ['Rendah', 'Sedang', 'Tinggi', 'Mendesak'];
        const urgencyCounts = [
            {{ \App\Models\Ticket::where('urgensi', 'Rendah')->count() }},
            {{ \App\Models\Ticket::where('urgensi', 'Sedang')->count() }},
            {{ \App\Models\Ticket::where('urgensi', 'Tinggi')->count() }},
            {{ \App\Models\Ticket::where('urgensi', 'Mendesak')->count() }}
        ];
        
        // Warna untuk pie chart urgensi
        const urgencyColors = [
            'rgba(23, 162, 184, 0.8)',    // Info (Rendah)
            'rgba(40, 167, 69, 0.8)',     // Success (Sedang)
            'rgba(255, 193, 7, 0.8)',     // Warning (Tinggi)
            'rgba(220, 53, 69, 0.8)'      // Danger (Mendesak)
        ];
        
        // Pie chart untuk Statistik Urgensi
        new Chart(urgencyPieCtx, {
            type: 'pie',
            data: {
                labels: urgencyLabels,
                datasets: [{
                    data: urgencyCounts,
                    backgroundColor: urgencyColors,
                    borderWidth: 1,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value * 100) / total) + '%';
                                return `${label}: ${value} (${percentage})`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush