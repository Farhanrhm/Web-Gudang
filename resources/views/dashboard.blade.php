@extends('layout')

@section('content')
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">👋 Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-muted">Ringkasan aktivitas gudang hari ini.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                📅 {{ date('d F Y') }}
            </span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 h-100 shadow-sm border-0 rounded-4">
                <div class="card-header fw-bold bg-primary border-0 rounded-top-4 bg-opacity-75">
                    📦 Total Jenis Barang
                </div>
                <div class="card-body">
                    <h1 class="card-title display-4 fw-bold">{{ $totalProducts }}</h1>
                    <p class="card-text opacity-75">Item berbeda terdaftar di sistem.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-sm text-primary fw-bold mt-2 rounded-pill px-3">Lihat Detail &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 h-100 shadow-sm border-0 rounded-4">
                <div class="card-header fw-bold bg-success border-0 rounded-top-4 bg-opacity-75">
                    📊 Total Stok Fisik
                </div>
                <div class="card-body">
                    <h1 class="card-title display-4 fw-bold">{{ number_format($totalStock, 0, ',', '.') }}</h1>
                    <p class="card-text opacity-75">Unit barang tersedia di gudang.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark bg-warning mb-3 h-100 shadow-sm border-0 rounded-4">
                <div class="card-header fw-bold bg-warning border-0 rounded-top-4 bg-opacity-50">
                    💰 Total Nilai Aset
                </div>
                <div class="card-body">
                    <h2 class="card-title fw-bold mt-2">Rp {{ number_format($totalAsset, 0, ',', '.') }}</h2>
                    <p class="card-text">Estimasi uang dalam bentuk stok.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-white py-3 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            
            <h5 class="mb-0 fw-bold text-secondary">
                <i class="bi bi-graph-up-arrow me-2"></i>Tren Arus Barang
            </h5>

            <div class="btn-group shadow-sm" role="group">
                <button onclick="updateChart('day', this)" class="btn btn-sm btn-outline-primary fw-bold filter-btn">
                   Hari Ini
                </button>
                <button onclick="updateChart('week', this)" class="btn btn-sm btn-outline-primary fw-bold active filter-btn">
                   1 Minggu
                </button>
                <button onclick="updateChart('month', this)" class="btn btn-sm btn-outline-primary fw-bold filter-btn">
                   1 Bulan
                </button>
                <button onclick="updateChart('year', this)" class="btn btn-sm btn-outline-primary fw-bold filter-btn">
                   1 Tahun
                </button>
            </div>

        </div>
        <div class="card-body">
            <div style="height: 300px;">
                <canvas id="transactionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 rounded-top-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold text-secondary">
                        <i class="bi bi-activity me-2"></i>Aktivitas Terakhir
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <form action="{{ route('dashboard') }}" method="GET" class="d-inline-block">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-sort-down"></i> Urutkan:
                            </span>
                            <select name="sort" class="form-select form-select-sm border-start-0 ps-0 fw-bold text-dark" onchange="this.form.submit()" style="cursor: pointer;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>🕒 Waktu Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>⏳ Waktu Terlama</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💰 Harga Tertinggi</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💸 Harga Terendah</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top" style="z-index: 1;"> 
                        <tr>
                            <th class="ps-4" style="width: 15%;">Waktu</th>
                            <th style="width: 25%;">Barang</th>
                            <th style="width: 20%;">Oleh (User)</th>
                            <th class="text-center" style="width: 15%;">Status</th>
                            <th class="text-center" style="width: 10%;">Jumlah</th>
                            <th class="text-end pe-4" style="width: 15%;">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $trx)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark small">{{ $trx->created_at->format('H:i') }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $trx->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="fw-bold text-dark">{{ $trx->product->name ?? 'Barang Dihapus' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <span class="d-block small fw-bold text-dark">{{ $trx->user->name ?? 'Sistem' }}</span>
                                        <span class="d-block text-muted" style="font-size: 10px;">{{ ucfirst($trx->user->role ?? '-') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($trx->type == 'in')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill shadow-sm border border-success border-opacity-25">
                                        <i class="bi bi-arrow-down-circle me-1"></i> Masuk
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill shadow-sm border border-danger border-opacity-25">
                                        <i class="bi bi-arrow-up-circle me-1"></i> Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="text-center fw-bold fs-6">{{ $trx->quantity }}</td>
                            <td class="text-end pe-4 fw-bold text-secondary">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-clipboard-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada aktivitas transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-end py-3 rounded-bottom-4">
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold">
                Lihat Semua Riwayat <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Inisialisasi Chart Awal
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['dates']) !!}, 
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: {!! json_encode($chartData['income']) !!}, 
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Barang Keluar',
                        data: {!! json_encode($chartData['expense']) !!}, 
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Agar grafik fleksibel
                plugins: { legend: { position: 'top' } },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        ticks: { precision: 0 } // Hindari angka desimal
                    } 
                }
            }
        });

        // 2. Fungsi Update Chart Tanpa Reload (AJAX)
        function updateChart(range, btnElement) {
            // Ubah tampilan tombol aktif (Visual saja)
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');

            // Panggil data baru dari server (Fetch API)
            fetch("{{ route('dashboard') }}?chart_range=" + range, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest" // Tanda bahwa ini request AJAX
                }
            })
            .then(response => response.json()) // Ubah respon jadi JSON
            .then(data => {
                // Masukkan data baru ke grafik
                transactionChart.data.labels = data.dates;
                transactionChart.data.datasets[0].data = data.income;
                transactionChart.data.datasets[1].data = data.expense;
                
                // Update tampilan grafik
                transactionChart.update();
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection