@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark mb-0">✨ Tambah Produk Baru</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">SKU / Kode Barang</label>
                        <input type="text" name="sku" class="form-control bg-light @error('sku') is-invalid @enderror" placeholder="Contoh: BRG-001" value="{{ old('sku') }}" required autofocus>
                        @error('sku')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kategori</label>
                        <select name="category_id" class="form-select bg-light" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Produk</label>
                        <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" placeholder="Nama barang..." value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small">Harga Jual (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light" placeholder="0" value="{{ old('price') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Stok Awal</label>
                            <input type="number" name="stock" class="form-control bg-light" placeholder="0" value="{{ old('stock') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Satuan (Unit)</label>
                            <input type="text" name="unit" class="form-control bg-light" 
                                   list="unitOptions" placeholder="Pcs, Kg..." 
                                   value="{{ old('unit') }}" required>
                            <datalist id="unitOptions">
                                <option value="Pcs"><option value="Kg"><option value="Dus">
                                <option value="Karung"><option value="Pack"><option value="Liter">
                            </datalist>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Lokasi Rak</label>
                            <input type="text" name="location" class="form-control bg-light @error('location') is-invalid @enderror" 
                                   placeholder="Contoh: Rak A-01" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Foto Barang</label>
                        <input type="file" name="image" class="form-control bg-light" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text small text-muted">Format: JPG, JPEG, PNG. Maks 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Deskripsi</label>
                        <textarea name="description" class="form-control bg-light" rows="3" placeholder="Tambahkan keterangan detail produk...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-save me-2"></i>Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection