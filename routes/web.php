<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    ProductController,
    CategoryController,
    TransactionController,
    ProfileController,
    UserController,
    AuthController
};

/*
|--------------------------------------------------------------------------
| RUTE GUEST (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/', fn () => redirect()->route('login'));
    Route::post('/', fn () => redirect()->route('login'));

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| RUTE AUTH (SUDAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */

    // WAJIB: index dulu (sebelum create/store)
    Route::get('transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::get('transactions/create', [TransactionController::class, 'create'])
        ->name('transactions.create');

    Route::post('transactions', [TransactionController::class, 'store'])
        ->name('transactions.store');

    Route::get('transactions/{transaction}/print', [TransactionController::class, 'print'])
        ->name('transactions.print');

    /*
    |--------------------------------------------------------------------------
    | PRODUK
    |--------------------------------------------------------------------------
    */
    Route::get('products/export', [ProductController::class, 'export'])
        ->name('products.export');

    Route::get('products/trash', [ProductController::class, 'trash'])
        ->name('products.trash');

    Route::resource('products', ProductController::class);

    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    });
});
