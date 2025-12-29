<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. Tampilkan Daftar Kategori
    public function index()
    {
        $categories = Category::withCount('products')->get(); // withCount untuk hitung jumlah barang per kategori
        return view('categories.index', compact('categories'));
    }

    // 2. Form Tambah
    public function create()
    {
        return view('categories.create');
    }

    // 3. Simpan Kategori Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:50',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat!');
    }

    // 4. Form Edit
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // 5. Update Kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,'.$category->id.'|max:50',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Nama kategori diperbarui!');
    }

    // 6. Hapus Kategori
    public function destroy(Category $category)
    {
        // Cek dulu, kalau masih ada barangnya, jangan dihapus sembarangan (Opsional, tapi aman)
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori dihapus.');
    }
}