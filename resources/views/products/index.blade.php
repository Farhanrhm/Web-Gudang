@extends('layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">📦 Stok Gudang</h2>
            <p class="text-muted small mt-1">Kelola daftar barang, harga, dan lokasi stok.</p>
        </div>

        <div class="d-flex gap-2">
            @if(Auth::user()->role == 'admin')
                <a href="{{ route('products.trash') }}" class="btn btn-outline-danger rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-trash me-2"></i>Sampah
                </a>
            @endif

            <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-circle-fill me-2"></i>Tambah Barang
            </a>

            <a href="{{ route('transactions.create') }}"
                class="btn btn-warning rounded-pill px-4 shadow-sm text-dark fw-bold">
                <i class="bi bi-arrow-left-right me-2"></i>Catat Transaksi
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 rounded-top-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold text-secondary">
                        <i class="bi bi-list-ul me-2"></i>Data Inventaris Gudang
                    </h5>
                </div>
                <div class="col-md-6">
                    <form method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-light border-start-0 rounded-end-pill"
                                placeholder="Cari nama atau SKU..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">SKU</th>
                            <th class="text-center">Gambar</th>
                            <th>Barang</th>
                            <th class="text-center">Stok</th>
                            <th>Satuan</th>
                            <th>Lokasi</th>
                            <th>Harga</th>
                            <th>Total Nilai</th>
                            @if(Auth::user()->role == 'admin')
                                <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4 text-muted font-monospace small">
                                    {{ $product->sku }}
                                </td>

                                {{-- Gambar --}}
                                <td class="text-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="rounded shadow-sm border"
                                            style="width:45px;height:45px;object-fit:cover;cursor:pointer" data-bs-toggle="modal"
                                            data-bs-target="#imageModal" data-bs-src="{{ asset('storage/' . $product->image) }}">
                                    @else
                                        <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center"
                                            style="width:45px;height:45px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- NAMA + SKU + DESKRIPSI --}}
                                <td>
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted font-monospace">{{ $product->sku }}</small>

                                        @if($product->description)
                                            <span class="text-primary" data-bs-toggle="tooltip" title="{{ $product->description }}"
                                                style="cursor: help;">
                                                <i class="bi bi-info-circle-fill small"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- STOK --}}
                                <td class="text-center fw-bold {{ $product->stock < 10 ? 'text-danger' : '' }}">
                                    {{ $product->stock }}
                                </td>

                                <td class="text-muted small">{{ $product->unit }}</td>

                                <td>
                                    <span class="badge bg-light border text-dark">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $product->location }}
                                    </span>
                                </td>

                                <td>{{ number_format($product->price, 0, ',', '.') }}</td>

                                <td class="fw-bold text-primary">
                                    {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                                </td>

                                @if(Auth::user()->role == 'admin')
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light border">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </a>

                                            <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                                id="delete-form-{{ $product->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')"
                                                    class="btn btn-sm btn-light border text-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-seam display-5 d-block mb-2"></i>
                                    Data barang belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white rounded-bottom-4 d-flex justify-content-between">
            <a href="{{ route('products.export') }}" class="btn btn-dark fw-bold">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export
            </a>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- MODAL GAMBAR --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center">
                    <img id="modalImage" class="rounded-4 shadow-lg" style="max-width:100%">
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            if (confirm('Hapus barang: ' + name + ' ?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        // Tooltip & Modal
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Tooltip(document.body, {
                selector: '[data-bs-toggle="tooltip"]'
            });

            document.getElementById('imageModal')?.addEventListener('show.bs.modal', e => {
                document.getElementById('modalImage').src =
                    e.relatedTarget.getAttribute('data-bs-src');
            });
        });
    </script>
@endsection