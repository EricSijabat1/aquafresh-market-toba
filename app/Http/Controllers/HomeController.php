<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->get();

        $featuredProducts = Product::where('is_active', true)
            ->with('category')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
