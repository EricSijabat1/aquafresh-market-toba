<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Bagian untuk Kategori, biarkan seperti semula karena ini dari database
        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->get();


        // --- AWAL MODIFIKASI UNTUK PRODUK UNGGULAN ---
        // Ganti logika pengambilan produk unggulan dari database menjadi dari storage

        // 1. Ambil semua file dari direktori 'products'
        $imageFiles = Storage::disk('public')->files('products');

        $featuredProducts = [];
        foreach ($imageFiles as $key => $file) {
            // Hindari file tersembunyi
            if (strpos(basename($file), '.') === 0) {
                continue;
            }

            // 2. Buat nama produk dari nama file
            $productName = pathinfo($file, PATHINFO_FILENAME);
            $formattedName = ucfirst(str_replace(['-', '_'], ' ', $productName));

            // 3. Buat objek produk palsu
            $tempProduct = new \stdClass();
            $tempProduct->id = $key;
            $tempProduct->name = $formattedName;
            $tempProduct->image = $file;
            $tempProduct->description = 'Deskripsi unggulan untuk ' . $formattedName;
            $tempProduct->price = rand(25000, 200000); // Harga acak
            $tempProduct->price_formatted = 'Rp ' . number_format($tempProduct->price, 0, ',', '.');
            $tempProduct->weight = 1.0;

            // PENTING: Tambahkan kategori palsu agar tidak error di view
            // View Anda (home.blade.php) menggunakan $product->category->id untuk warna tombol
            $tempProduct->category = new \stdClass();
            $tempProduct->category->id = ($key % 2 == 0) ? 1 : 2; // Ganti kategori secara selang-seling (biru/oranye)

            $featuredProducts[] = $tempProduct;
        }

        // Batasi hanya 8 produk untuk ditampilkan
        $featuredProducts = array_slice($featuredProducts, 0, 8);

        // --- AKHIR MODIFIKASI ---


        return view('index', compact('categories', 'featuredProducts'));
    }
}
