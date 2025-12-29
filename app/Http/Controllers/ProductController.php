<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Daftar produk
     */
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Export produk ke CSV
     */
    public function export()
    {
        $fileName = 'products.csv';
        $products = Product::with('category')->get();

        return response()->stream(function () use ($products) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($out, [
                'SKU',
                'Nama',
                'Kategori',
                'Harga',
                'Stok',
                'Satuan',
                'Lokasi',
                'Total Nilai'
            ]);

            foreach ($products as $product) {
                fputcsv($out, [
                    $product->sku,
                    $product->name,
                    $product->category->name ?? '-',
                    $product->price,
                    $product->stock,
                    $product->unit,
                    $product->location,
                    $product->stock * $product->price
                ]);
            }

            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    /**
     * Form tambah produk
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        // 1️⃣ Validasi
        $data = $request->validate([
            'sku'         => 'required|unique:products,sku',
            'name'        => 'required|unique:products,name',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'unit'        => 'required|string|max:10',
            'location'    => 'required|string|max:50',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2️⃣ Upload gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // 3️⃣ Simpan produk
        $product = Product::create($data);

        // 4️⃣ Catat transaksi stok awal
        if ($data['stock'] > 0) {
            Transaction::create([
                'product_id'       => $product->id,
                'user_id'          => Auth::id(),
                'type'             => 'in',
                'quantity'         => $data['stock'],
                'price'            => $data['price'],
                'total_price'      => $data['stock'] * $data['price'],
                'transaction_date' => now(),
            ]);
        }

        // 5️⃣ Activity Log
        ActivityLog::record(
            'create_product',
            'Menambah barang baru: ' . $product->name
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku'         => 'required|unique:products,sku,' . $product->id,
            'name'        => 'required|unique:products,name,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'unit'        => 'required|string|max:10',
            'location'    => 'required|string|max:50',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload gambar baru
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Catat perubahan stok
        if ($product->stock != $data['stock']) {
            $diff = $data['stock'] - $product->stock;

            Transaction::create([
                'product_id'       => $product->id,
                'user_id'          => Auth::id(),
                'type'             => $diff > 0 ? 'in' : 'out',
                'quantity'         => abs($diff),
                'price'            => $data['price'],
                'total_price'      => abs($diff) * $data['price'],
                'transaction_date' => now(),
            ]);
        }

        $product->update($data);

        ActivityLog::record(
            'update_product',
            'Mengubah data barang: ' . $product->name
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Soft delete produk
     */
    public function destroy(Product $product)
    {
        $namaBarang = $product->name;
        $product->delete();

        ActivityLog::record(
            'delete_product',
            'Menghapus barang: ' . $namaBarang
        );

        return back()->with('success', 'Produk dipindahkan ke sampah.');
    }

    /**
     * Daftar produk terhapus
     */
    public function trash()
    {
        $products = Product::onlyTrashed()->latest()->paginate(10);
        return view('products.trash', compact('products'));
    }

    /**
     * Restore produk
     */
    public function restore($id)
    {
        Product::onlyTrashed()->findOrFail($id)->restore();

        ActivityLog::record(
            'restore_product',
            'Memulihkan produk dari sampah'
        );

        return back()->with('success', 'Produk berhasil dipulihkan.');
    }

    /**
     * Hapus permanen produk
     */
    public function kill($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->forceDelete();

        ActivityLog::record(
            'force_delete_product',
            'Menghapus produk secara permanen'
        );

        return back()->with('success', 'Produk dihapus permanen.');
    }
}
