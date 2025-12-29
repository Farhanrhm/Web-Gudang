@extends('layout')

@section('content')
<div class="container" style="max-width: 600px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">✨ Tambah Kategori</h3>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Nama Kategori</label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-lg bg-light @error('name') is-invalid @enderror" 
                           placeholder="Contoh: Sembako, Elektronik, Kebersihan..." 
                           value="{{ old('name') }}" 
                           required 
                           autofocus>
                    
                    @error('name') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                    
                    <div class="form-text text-muted mt-2">
                        Gunakan nama yang singkat dan jelas agar mudah dicari.
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i>Simpan Kategori
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection