@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0">📊 Riwayat Transaksi</h2>
        <p class="text-muted small mt-1">Laporan arus keluar-masuk barang.</p>
    </div>
    <a href="{{ route('transactions.create') }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-bold text-dark">
        <i class="bi bi-plus-circle me-2"></i>Catat Transaksi Baru
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary small text-uppercase">Tanggal</th>
                        <th class="py-3 text-secondary small text-uppercase">Barang</th>
                        <th class="py-3 text-secondary small text-uppercase text-center">Jenis</th>
                        <th class="py-3 text-secondary small text-uppercase text-center">Jumlah</th>
                        <th class="py-3 text-secondary small text-uppercase">Total Nilai</th>
                        <th class="py-3 text-secondary small text-uppercase">Petugas</th>
                        <th class="py-3 text-secondary small text-uppercase">Ket.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td class="ps-4 text-secondary">
                            {{ date('d M Y', strtotime($trx->transaction_date)) }}
                        </td>

                        <td>
                            <div class="fw-bold text-dark">{{ $trx->product->name ?? 'Barang Dihapus' }}</div>
                            <small class="text-muted font-monospace">{{ $trx->product->sku ?? '-' }}</small>
                        </td>

                        <td class="text-center">
                            @if($trx->type == 'in')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 rounded-pill border border-success border-opacity-25">
                                    <i class="bi bi-arrow-down-circle me-1"></i> Masuk
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 rounded-pill border border-danger border-opacity-25">
                                    <i class="bi bi-arrow-up-circle me-1"></i> Keluar
                                </span>
                            @endif
                        </td>

                        <td class="text-center fw-bold text-dark">
                            {{ $trx->quantity }} <span class="text-muted fw-normal small">{{ $trx->product->unit ?? '' }}</span>
                        </td>

                        <td class="text-primary fw-bold">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 border" style="width: 30px; height: 30px;">
                                    <i class="bi bi-person text-secondary small"></i>
                                </div>
                                <span class="small text-dark">{{ $trx->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>

                        <td>
                            @if($trx->description)
                                <span class="text-muted small fst-italic" data-bs-toggle="tooltip" title="{{ $trx->description }}">
                                    {{ Str::limit($trx->description, 20) }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty" style="width: 60px; opacity: 0.5;" class="mb-3 d-block mx-auto">
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white py-3 border-0 rounded-bottom-4">
        <div class="d-flex justify-content-end">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection