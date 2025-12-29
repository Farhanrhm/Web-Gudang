<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar transaksi
     */
    public function index()
    {
        $transactions = Transaction::with(['product', 'user'])
            ->latest('transaction_date') // Urutkan berdasarkan tanggal transaksi
            ->latest('created_at')       // Jika tanggal sama, urutkan waktu input
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Form input transaksi
     */
    public function create()
    {
        $products = Product::orderBy('name', 'asc')->get();
        return view('transactions.create', compact('products'));
    }

    /**
     * Simpan transaksi & update stok
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'type'             => 'required|in:in,out',
            'quantity'         => 'required|integer|min:1',
            'transaction_date' => 'required|date',
        ], [
            'product_id.required' => 'Pilih produk terlebih dahulu!',
            'quantity.min'        => 'Jumlah minimal adalah 1 unit.',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);

            // Validasi & update stok
            if ($request->type === 'out') {
                if ($product->stock < $request->quantity) {
                    throw new \Exception(
                        "Stok {$product->name} tidak mencukupi (Sisa: {$product->stock})"
                    );
                }
                $product->decrement('stock', $request->quantity);
            } else {
                $product->increment('stock', $request->quantity);
            }

            // Simpan transaksi
            Transaction::create([
                'product_id'       => $request->product_id,
                'user_id'          => Auth::id(),
                'type'             => $request->type,
                'quantity'         => $request->quantity,
                'price'            => $product->price,
                'total_price'      => $product->price * $request->quantity,
                'transaction_date' => $request->transaction_date,
                'description'      => $request->description,
            ]);

            // Catat aktivitas (activity log)
            ActivityLog::record('transaction', 'Mencatat transaksi ' . $request->type . ' untuk ' . $product->name . ' (' . $request->quantity . ' ' . $product->unit . ')');
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dicatat & stok diperbarui!');
    }

    /**
     * Cetak / detail transaksi
     */
    public function print(Transaction $transaction)
    {
        $transaction->load(['product', 'user']);
        return view('transactions.print', compact('transaction'));
    }
}
