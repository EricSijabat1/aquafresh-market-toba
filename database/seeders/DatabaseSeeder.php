<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin AquaFresh',
            'email' => 'admin@aquafresh.com',
            'password' => bcrypt('password')
        ]);

        // Create categories
        $categories = [
            [
                'name' => 'Ikan Segar',
                'description' => 'Ikan segar langsung dari nelayan',
                'image' => 'categories/ikan-segar.jpg'
            ],
            [
                'name' => 'Olahan Ikan',
                'description' => 'Berbagai macam olahan ikan siap saji',
                'image' => 'categories/olahan-ikan.jpg'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample products
        $products = [
            [
                'category_id' => 1,
                'name' => 'Ikan Salmon Segar',
                'description' => 'Ikan salmon segar impor dengan kualitas premium',
                'price' => 150000,
                'stock' => 20,
                'weight' => 1.0,
                'image' => 'products/salmon.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'Ikan Tuna Segar',
                'description' => 'Ikan tuna segar hasil tangkapan lokal',
                'price' => 80000,
                'stock' => 15,
                'weight' => 0.5,
                'image' => 'products/tuna.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Nugget Ikan',
                'description' => 'Nugget ikan berkualitas untuk keluarga',
                'price' => 25000,
                'stock' => 50,
                'weight' => 0.25,
                'image' => 'products/nugget.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Kerupuk Ikan',
                'description' => 'Kerupuk ikan renyah dan gurih',
                'price' => 15000,
                'stock' => 100,
                'weight' => 0.1,
                'image' => 'products/kerupuk.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
