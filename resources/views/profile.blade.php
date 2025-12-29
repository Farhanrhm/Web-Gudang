@extends('layout')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">👤 Pengaturan Profil</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-lines-fill me-2"></i>Edit Biodata</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle border border-3 border-light shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-circle border d-inline-flex align-items-center justify-content-center text-secondary shadow-sm" style="width: 120px; height: 120px; font-size: 50px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                
                                <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-white border shadow-sm rounded-circle p-2" style="cursor: pointer;" title="Ganti Foto">
                                    <i class="bi bi-camera-fill text-primary"></i>
                                </label>
                            </div>
                            
                            <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="this.form.submit()">
                            <div class="form-text mt-2 small">Klik ikon kamera untuk mengganti foto.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Role / Jabatan</label>
                            <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled readonly>
                            <div class="form-text">Role hanya bisa diubah oleh database administrator.</div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                                <i class="bi bi-save me-2"></i>Simpan Biodata
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-shield-lock-fill me-2"></i>Ganti Password</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Lama</label>
                            <input type="password" name="current_password" class="form-control bg-light @error('current_password') is-invalid @enderror">
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <input type="password" name="new_password" class="form-control bg-light @error('new_password') is-invalid @enderror">
                            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control bg-light">
                            <div class="form-text">Ketik ulang password baru untuk verifikasi.</div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                                <i class="bi bi-key-fill me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection