@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('users.index') }}" class="btn btn-light rounded-circle shadow-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Edit Data User</h2>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <label class="form-label d-block fw-bold">Foto Saat Ini</label>
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" class="rounded-4 mb-3 shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=150" class="rounded-4 mb-3 shadow-sm">
                            @endif
                            <input type="file" name="avatar" class="form-control form-control-sm @error('avatar') is-invalid @enderror">
                            <div class="form-text small text-muted mt-2">Biarkan kosong jika tidak ingin ganti foto.</div>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Email</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Role / Akses</label>
                                    <select name="role" class="form-select rounded-3">
                                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Ganti Password</label>
                                    <input type="password" name="password" class="form-control rounded-3" placeholder="Isi hanya jika ingin ganti">
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold text-white">
                                    <i class="bi bi-arrow-repeat me-2"></i>Update Data
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