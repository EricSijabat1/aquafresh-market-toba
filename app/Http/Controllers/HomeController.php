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
        // Mengambil kategori dari database
        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->get();

        // --- KODE PERBAIKAN ---
        // Mengambil produk unggulan ASLI dari database
        // Contoh: Ambil 8 produk terbaru yang aktif
        $featuredProducts = Product::where('is_active', true)
            ->latest() // Mengambil yang terbaru
            ->take(8)    // Batasi 8 produk
            ->get();
        // --- AKHIR PERBAIKAN ---

        return view('index', compact('categories', 'featuredProducts'));
    }
}
