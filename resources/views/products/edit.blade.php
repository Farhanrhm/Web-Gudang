@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark mb-0">✏️ Edit Barang</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kode Barang (SKU)</label>
                        <input type="text" name="sku" class="form-control bg-light @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kategori Barang</label>
                        <select name="category_id" class="form-select bg-light" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Barang</label>
                        <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small">Harga Satuan (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light" value="{{ old('price', $product->price) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Stok Saat Ini</label>
                            <input type="number" name="stock" class="form-control bg-light" value="{{ old('stock', $product->stock) }}" required>
                            <div class="form-text text-warning style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle"></i> Catat sebagai penyesuaian.
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Satuan (Unit)</label>
                            <input type="text" name="unit" class="form-control bg-light" 
                                   list="unitOptions" placeholder="Pcs, Kg..." 
                                   value="{{ old('unit', $product->unit) }}" required>
                            <datalist id="unitOptions">
                                <option value="Pcs"><option value="Kg"><option value="Dus">
                                <option value="Karung"><option value="Pack"><option value="Liter">
                            </datalist>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Lokasi Rak</label>
                            <input type="text" name="location" class="form-control bg-light" 
                                   placeholder="Contoh: Rak A-01" value="{{ old('location', $product->location) }}" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Foto Barang</label>
                        
                        @if($product->image)
                            <div class="mb-2 text-center">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="Foto Produk" 
                                     class="img-thumbnail rounded shadow-sm" 
                                     style="max-height: 150px; width: auto;">
                                <p class="small text-muted mb-0 mt-1">Foto saat ini</p>
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control bg-light mt-2" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text small text-muted">Kosongkan jika tidak ingin mengganti gambar.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Deskripsi</label>
                        <textarea name="description" class="form-control bg-light" rows="5" placeholder="Tambahkan deskripsi...">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <hr>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-save me-2"></i>Update Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection