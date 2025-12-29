<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',               // in / out
        'quantity',
        'price',
        'total_price',
        'transaction_date',
        'description',
    ];

    /**
     * Relasi: Transaksi milik 1 Produk
     * Produk boleh di-soft delete
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Relasi: Transaksi dicatat oleh 1 User
     * Jika user dihapus, tampilkan default
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'User Terhapus',
        ]);
    }

    /**
     * OPSIONAL:
     * Otomatis hitung total harga saat simpan data
     */
    protected static function booted()
    {
        static::saving(function ($transaction) {
            if ($transaction->price !== null && $transaction->quantity !== null) {
                $transaction->total_price = $transaction->price * $transaction->quantity;
            }
        });
    }
}
