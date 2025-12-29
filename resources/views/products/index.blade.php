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
            
            <a href="{{ route('transactions.create') }}" class="btn btn-warning rounded-pill px-4 shadow-sm text-dark fw-bold">
                <i class="bi bi-arrow-left-right me-2"></i>Catat Transaksi
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 rounded-top-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-list-ul me-2"></i>Data Inventaris Gudang Sembako</h5>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 rounded-end-pill bg-light" placeholder="Cari nama barang atau SKU..." value="{{ request('search') }}">
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
                            <th class="ps-4 py-3 text-secondary text-uppercase small">ID Produk</th>
                            <th class="py-3 text-secondary text-uppercase small text-center">Gambar</th>
                            <th class="py-3 text-secondary text-uppercase small">Nama Barang</th>
                            <th class="py-3 text-secondary text-uppercase small text-center">Stok</th>
                            <th class="py-3 text-secondary text-uppercase small">Satuan</th>
                            <th class="py-3 text-secondary text-uppercase small">Lokasi</th>
                            <th class="py-3 text-secondary text-uppercase small">Harga Satuan (IDR)</th>
                            <th class="py-3 text-secondary text-uppercase small">Total Nilai (IDR)</th>
                            @if(Auth::user()->role == 'admin')
                                <th class="py-3 text-secondary text-uppercase small text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4 font-monospace text-secondary small">{{ $product->sku }}</td>
                            
                            <td class="text-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         class="rounded shadow-sm border" 
                                         style="width: 45px; height: 45px; object-fit: cover; cursor: pointer;"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal"
                                         data-bs-src="{{ asset('storage/' . $product->image) }}">
                                @else
                                    <div class="bg-light rounded d-inline-flex align-items-center justify-content-center text-secondary border" style="width: 45px; height: 45px;">
                                        <i class="bi bi-image small"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="fw-bold text-dark">{{ $product->name }}</td>
                            
                            <td class="text-center">
                                <span class="fw-bold {{ $product->stock < 10 ? 'text-danger' : 'text-dark' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>

                            <td class="text-secondary small">{{ $product->unit }}</td>

                            <td>
                                <span class="badge bg-light text-dark border px-2 fw-normal">
                                    <i class="bi bi-geo-alt-fill text-danger me-1 small"></i>
                                    {{ $product->location ?? '-' }}
                                </span>
                            </td>
                            
                            <td class="text-dark">
                                {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            
                            <td class="fw-bold text-primary">
                                {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                            </td>
                            
                            @if(Auth::user()->role == 'admin')
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-3">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" id="delete-form-{{ $product->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger" title="Hapus" onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role == 'admin' ? 9 : 8 }}" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-box-seam display-4 mb-3 opacity-25"></i>
                                    <p>Data Inventaris Tidak Ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3 rounded-bottom-4 border-top-0">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('products.export') }}" class="btn btn-dark rounded-3 px-4 shadow-sm fw-bold">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Ekspor ke Spreadsheet
                </a>
                <div>
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 z-3 p-2 bg-dark bg-opacity-50 rounded-circle" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img src="" id="modalImage" class="d-block mx-auto rounded-4 shadow-lg"
                    style="max-width: 100%; max-height: 90vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            if (confirm("⚠️ Apakah Anda yakin ingin menghapus barang: " + name + "?")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        document.addEventListener("DOMContentLoaded", function(){
            var imageModal = document.getElementById('imageModal');
            if(imageModal) {
                imageModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var imgSrc = button.getAttribute('data-bs-src');
                    var modalImg = imageModal.querySelector('#modalImage');
                    modalImg.src = imgSrc;
                });
            }
        });
    </script>
@endsection