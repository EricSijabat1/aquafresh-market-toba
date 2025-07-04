<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // --- AWAL MODIFIKASI SEMENTARA ---

        // 1. Ambil semua file dari direktori 'products' di disk 'public'
        $imageFiles = Storage::disk('public')->allFiles('categories');

        $products = [];
        foreach ($imageFiles as $key => $file) {
            // 2. Buat nama produk dari nama file
            $productName = pathinfo($file, PATHINFO_FILENAME); // contoh: 'ikan-salmon-segar'
            $formattedName = ucfirst(str_replace(['-', '_'], ' ', $productName)); // contoh: 'Ikan salmon segar'

            // 3. Buat objek 'produk' palsu untuk ditampilkan di view
            $tempProduct = new \stdClass();
            $tempProduct->id = $key; // ID unik sementara untuk Alpine.js
            $tempProduct->name = $formattedName;
            $tempProduct->image = $file; // Path file, contoh: 'products/ikan-salmon-segar.jpg'
            $tempProduct->description = 'Deskripsi untuk ' . $formattedName;
            $tempProduct->price = 50000; // Harga dummy
            $tempProduct->price_formatted = 'Rp ' . number_format(50000, 0, ',', '.'); // Harga dummy
            $tempProduct->weight = 0.5; // Berat dummy

            $products[] = $tempProduct;
        }

        // Ambil kategori untuk filter (meskipun filter tidak berfungsi di mode ini)
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));

        // --- AKHIR MODIFIKASI SEMENTARA ---

        /*
        // KODE ASLI (Jangan dihapus, cukup dikomentari)
        $query = Product::where('is_active', true)->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
        */
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        // --- AWAL MODIFIKASI SEMENTARA UNTUK TAMPILAN DINAMIS PER KATEGORI ---

        // 1. Ubah nama kategori menjadi "slug" (contoh: "Ikan Segar" -> "ikan-segar")
        $categorySlug = Str::slug($category->name);

        // 2. Buat path direktori yang dituju secara dinamis
        $directoryPath = 'categories/' . $categorySlug;

        // 3. Ambil semua file dari direktori spesifik tersebut
        $imageFiles = Storage::disk('public')->files($directoryPath);

        $products = [];
        foreach ($imageFiles as $key => $file) {
            // Hindari file tersembunyi
            if (strpos(basename($file), '.') === 0) {
                continue;
            }

            // 4. Buat nama produk dari nama file
            $productName = pathinfo($file, PATHINFO_FILENAME);
            $formattedName = ucfirst(str_replace(['-', '_'], ' ', $productName));

            // 5. Buat objek produk palsu
            $tempProduct = new \stdClass();
            $tempProduct->id = $key;
            $tempProduct->name = $formattedName;
            $tempProduct->image = $file;
            $tempProduct->description = 'Produk dari kategori ' . $category->name;
            $tempProduct->price = rand(20000, 150000); // Harga acak untuk variasi
            $tempProduct->price_formatted = 'Rp ' . number_format($tempProduct->price, 0, ',', '.');
            $tempProduct->weight = 1.0;

            $products[] = $tempProduct;
        }

        // Kita tetap mengirimkan $category dan $products ke view
        return view('products.category', compact('category', 'products'));

        // --- AKHIR MODIFIKASI SEMENTARA ---


        /*
    // KODE ASLI (Jangan dihapus, komentari saja)
    $products = $category->activeProducts()->paginate(12);
    return view('products.category', compact('category', 'products'));
    */
    }
}
