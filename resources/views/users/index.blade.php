@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark">👥 Manajemen User</h2>
        <p class="text-muted small">Kelola hak akses Admin dan Staff.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
        <i class="bi bi-person-plus-fill me-2"></i>Tambah User
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-3">{{ session('error') }}</div>
@endif

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($u->avatar)
                                    <img src="{{ asset('storage/'.$u->avatar) }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=random&color=fff" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                                @endif
                                <div class="fw-bold text-dark">{{ $u->name }} @if($u->id === auth()->id()) <span class="badge bg-info small" style="font-size: 10px;">Anda</span> @endif</div>
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge {{ $u->role == 'admin' ? 'bg-primary' : 'bg-secondary' }} rounded-pill px-3">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-light border text-primary me-1"><i class="bi bi-pencil"></i></a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Yakin hapus user ini?')"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection