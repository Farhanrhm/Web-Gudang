<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0;
        }
        .login-card { 
            width: 100%; 
            max-width: 400px; 
            border-radius: 20px; 
            border: none; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            background: white;
        }
        .btn-login { 
            background-color: #0d6efd; 
            color: white; 
            padding: 12px; 
            font-weight: bold; 
            transition: 0.3s;
            border: none;
        }
        .btn-login:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
            color: white;
        }
        .form-control {
            padding: 12px;
            border-radius: 10px;
        }
        .form-control:focus {
            box-shadow: none;
            border: 1px solid #0d6efd;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">📦 Gudang Login</h3>
                <p class="text-muted small">Masuk untuk mengelola stok</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger border-0 text-center small p-2 rounded-3 mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email</label>
                    <input type="email" name="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                           placeholder="user@gudang.com" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small">Password</label>
                    <input type="password" name="password" class="form-control bg-light border-0" 
                           placeholder="••••••••" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-login rounded-pill shadow-sm">
                        MASUK APLIKASI
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer bg-white text-center py-3 border-0 rounded-bottom-4">
            <small class="text-muted">Sistem Manajemen Stok v1.0 &copy; {{ date('Y') }}</small>
        </div>
    </div>

</body>
</html>