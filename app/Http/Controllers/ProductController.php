<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. READ: Menampilkan daftar produk
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    // 2. CREATE: Menampilkan form tambah produk
    public function create()
    {
        // Ambil semua data kategori
        $categories = CategoryProduct::all();
        
        // Kirim (passing) data variabel $categories ke view
        return view('admin.products.create', compact('categories'));
    }

    // 3. STORE: Menyimpan data produk baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:category_product,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Menyimpan gambar ke folder public/storage/products
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // 4. EDIT: Menampilkan form edit produk
    public function edit(Product $product)
    {
        // Ambil semua data kategori untuk dropdown
        $categories = CategoryProduct::all();
        
        // Kirim (passing) $product dan $categories ke view
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 5. UPDATE: Memperbarui data produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:category_product,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 6. DELETE: Menghapus produk
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}