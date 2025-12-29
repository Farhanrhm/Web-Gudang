@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark mb-0">🔄 Catat Transaksi</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        {{-- Card --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                {{-- Error Validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf

                    {{-- Produk & Tanggal --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small">Pilih Barang</label>
                            <select name="product_id" id="productSelect" class="form-select form-select-lg bg-light" required>
                                <option value="" selected disabled>-- Cari Nama Barang --</option>
                                @foreach($products as $product)
                                    <option 
                                        value="{{ $product->id }}"
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}"
                                        data-unit="{{ $product->unit ?? 'unit' }}"
                                    >
                                        {{ $product->sku }} - {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="stockInfo" class="form-text fw-bold text-primary mt-2" style="display:none;">
                                <i class="bi bi-box-seam me-1"></i>
                                Stok: <span id="currentStock">0</span> <span id="currentUnit"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date"
                                   class="form-control form-control-lg bg-light"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- Jenis & Quantity --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small d-block mb-3">Jenis Transaksi</label>
                            <div class="d-flex gap-3">
                                <div class="flex-grow-1">
                                    <input type="radio" class="btn-check" name="type" id="typeIn" value="in" checked>
                                    <label class="btn btn-outline-success w-100 py-3 rounded-3 fw-bold border-2" for="typeIn">
                                        <i class="bi bi-arrow-down-circle me-2"></i>Barang Masuk
                                    </label>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="radio" class="btn-check" name="type" id="typeOut" value="out">
                                    <label class="btn btn-outline-danger w-100 py-3 rounded-3 fw-bold border-2" for="typeOut">
                                        <i class="bi bi-arrow-up-circle me-2"></i>Barang Keluar
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small">Jumlah (Qty)</label>
                            <input type="number" name="quantity"
                                   class="form-control form-control-lg bg-light"
                                   min="1" placeholder="0" required>
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small">Harga Satuan</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" id="priceInput"
                                       class="form-control bg-light"
                                       min="0" required>
                            </div>
                            <small class="text-muted">
                                Harga otomatis dari produk, bisa diubah bila perlu.
                            </small>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Catatan (Opsional)</label>
                        <textarea name="description" class="form-control bg-light" rows="3"
                                  placeholder="Contoh: Restock Supplier A, Rusak, Pengambilan Gudang..."></textarea>
                    </div>

                    <hr>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i>Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    const productSelect = document.getElementById('productSelect');
    const priceInput    = document.getElementById('priceInput');
    const stockInfo     = document.getElementById('stockInfo');
    const currentStock  = document.getElementById('currentStock');
    const currentUnit   = document.getElementById('currentUnit');

    productSelect.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];

        priceInput.value   = option.getAttribute('data-price') ?? '';
        currentStock.textContent = option.getAttribute('data-stock') ?? 0;
        currentUnit.textContent  = option.getAttribute('data-unit') ?? '';
        stockInfo.style.display  = 'block';
    });
</script>
@endsection
