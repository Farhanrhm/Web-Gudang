@extends('layout')

@section('content')
<div class="container" style="max-width: 900px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">🗂️ Kategori Barang</h2>
            <p class="text-muted small mt-1">Kelompokkan barang agar lebih rapi.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Kategori Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase small">Nama Kategori</th>
                            <th class="text-center py-3 text-secondary text-uppercase small">Jumlah Barang</th>
                            <th class="text-center py-3 text-secondary text-uppercase small">Tanggal Dibuat</th>
                            <th class="text-end pe-4 py-3 text-secondary text-uppercase small">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $cat->name }}</td>
                            
                            <td class="text-center">
                                @if($cat->products_count > 0)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 border border-primary border-opacity-25">
                                        {{ $cat->products_count }} Item
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                        Kosong
                                    </span>
                                @endif
                            </td>

                            <td class="text-center text-muted small">
                                {{ $cat->created_at->format('d M Y') }}
                            </td>

                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-3" role="group">
                                    <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-light border text-primary" title="Edit Nama">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ PERINGATAN:\n\nMenghapus kategori ini tidak akan menghapus barangnya, tapi barang tersebut akan kehilangan label kategorinya.\n\nYakin hapus?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border border-start-0 text-danger" title="Hapus Kategori">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada kategori yang dibuat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection