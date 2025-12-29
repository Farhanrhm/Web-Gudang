<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Product extends Model
{
    use HasFactory;
    use SoftDeletes; 

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     * Pastikan semua kolom yang ada di database didaftarkan di sini.
     */
    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'price',
        'stock',
        'location',
        'unit',
        'description',
        'image'
    ];

    /**
     * Relasi ke Model Category.
     * Satu produk memiliki satu kategori (Belongs To).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Model Transaction.
     * Satu produk bisa memiliki banyak catatan transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}