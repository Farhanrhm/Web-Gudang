@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark mb-0">📜 Log Aktivitas</h2>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Waktu</th>
                        <th class="py-3">User</th>
                        <th class="py-3">Aksi</th>
                        <th class="py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td class="ps-4 text-muted small">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="fw-bold">
                            {{ $log->user->name ?? 'Sistem' }}
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="text-secondary">
                            {{ $log->description }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection