@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-danger mb-0"><i class="bi bi-trash3-fill me-2"></i>Tempat Sampah</h2>
        <p class="text-muted small mt-1">Daftar barang yang telah dihapus sementara.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm border">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Barang</th>
                        <th>Kategori</th>
                        <th>Tgl Hapus</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->sku }}</small>
                        </td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td class="text-muted small">{{ $product->deleted_at->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            {{-- Kita bisa tambah fitur Restore nanti --}}
                            <span class="badge bg-secondary opacity-75">Hanya Baca</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Keranjang sampah kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection