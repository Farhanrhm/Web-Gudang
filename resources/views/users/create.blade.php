@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('users.index') }}" class="btn btn-light rounded-circle shadow-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Tambah User Baru</h2>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <label class="form-label d-block fw-bold">Foto Profil</label>
                            <div class="bg-light rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; border: 2px dashed #ccc;">
                                <i class="bi bi-person-bounding-box fs-1 text-muted"></i>
                            </div>
                            <input type="file" name="avatar" class="form-control form-control-sm @error('avatar') is-invalid @enderror">
                            @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Email</label>
                                <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@gudang.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Role / Akses</label>
                                    <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror">
                                        <option value="staff" selected>Staff (Hanya Stok)</option>
                                        <option value="admin">Admin (Akses Penuh)</option>
                                    </select>
                                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Password Awal</label>
                                    <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Min. 5 karakter">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>Daftarkan User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection