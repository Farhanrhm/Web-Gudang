<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            
            // --- KOLOM BARU YANG DIBUTUHKAN ---
            $table->decimal('price', 15, 2);       // Harga satuan saat transaksi
            $table->decimal('total_price', 15, 2); // Total harga (qty * price)
            $table->date('transaction_date');
            $table->text('description')->nullable(); // Ini yang menyebabkan error tadi
            // ----------------------------------

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};