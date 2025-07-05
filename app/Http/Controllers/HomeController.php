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

        // --- LOGIKA BARU UNTUK PRODUK UNGGULAN ---

        // 1. Ambil 3 produk acak dari kategori 'Ikan Segar'
        $freshFish = Product::where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('name', 'Ikan Segar');
            })
            ->inRandomOrder() // Ambil secara acak agar bervariasi
            ->take(3)
            ->get();

        // 2. Ambil 3 produk acak dari kategori 'Olahan Ikan'
        $processedFish = Product::where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->where('name', 'Olahan Ikan');
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        // 3. Gabungkan kedua hasil query menjadi satu collection
        $featuredProducts = $freshFish->merge($processedFish);


        return view('index', compact('categories', 'featuredProducts'));
    }
}
