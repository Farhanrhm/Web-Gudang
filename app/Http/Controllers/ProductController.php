<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; 
use App\Models\Transaction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function export()
    {
        $fileName = 'products.csv';
        $products = Product::with('category')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($products) {
            $out = fopen('php://output', 'w');
            // BOM for Excel to properly open UTF-8
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['SKU','Nama','Kategori','Harga Satuan','Stok','Satuan','Total Nilai']);

            foreach ($products as $p) {
                fputcsv($out, [
                    $p->sku,
                    $p->name,
                    $p->category->name ?? '-',
                    $p->price,
                    $p->stock,
                    $p->unit,
                    $p->stock * $p->price,
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $categories = Category::all(); 
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:products,sku',
            'name' => 'required|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'unit' => 'required|string|max:10', 
            'location' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Menggunakan array untuk menyertakan unit dan imagePath
        $productData = $request->all();
        $productData['image'] = $imagePath;

        $product = Product::create($productData);

        // Catat transaksi awal jika stok > 0
        if ($request->stock > 0) {
            Transaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $request->stock,
                'price' => $request->price,
                'total_price' => $request->stock * $request->price,
                'transaction_date' => now()
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'unit' => 'required|string|max:10', 
            'location' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'sku.unique' => 'Kode Barang (SKU) ini sudah digunakan oleh produk lain.',
            'name.unique' => 'Nama Barang ini sudah digunakan oleh produk lain.',
        ]);

        // Logika Upload Gambar
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $product->image;
        }

        // Logika Stok Opname (Pencatatan Transaksi Otomatis)
        $oldStock = $product->stock;
        $newStock = $request->stock;
        
        if ($newStock != $oldStock) {
            $diff = $newStock - $oldStock;
            $type = $diff > 0 ? 'in' : 'out';
            
            Transaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'quantity' => abs($diff),
                'price' => $request->price,
                'total_price' => abs($diff) * $request->price,
                'transaction_date' => now()
            ]);
        }

        $updateData = $request->all();
        $updateData['image'] = $imagePath;

        $product->update($updateData);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus (masuk sampah).');
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->latest()->paginate(10);
        return view('products.trash', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('products.trash')->with('success', 'Barang berhasil dipulihkan!');
    }

    public function kill($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->forceDelete();
        return redirect()->route('products.trash')->with('success', 'Barang dimusnahkan permanen!');
    }
}