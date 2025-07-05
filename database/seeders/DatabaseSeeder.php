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
        // Membuat pengguna admin
        User::factory()->create([
            'name' => 'Admin AquaFresh',
            'email' => 'admin@aquafresh.com',
            'password' => bcrypt('password') // Harap ganti 'password' dengan password yang lebih aman
        ]);

        // Membuat kategori
        // Kategori ID 1
        Category::create([
            'name' => 'Ikan Segar',
            'description' => 'Ikan segar langsung dari nelayan Danau Toba.',
            'image' => 'categories/ikan-segar/Ikan-Batak-Segar.jpg'
        ]);

        // Kategori ID 2
        Category::create([
            'name' => 'Olahan Ikan',
            'description' => 'Berbagai macam olahan ikan siap saji khas Batak.',
            'image' => 'categories/olahan-ikan/Gurame-bakar.jpg'
        ]);

        // --- DATA PRODUK BARU SESUAI GAMBAR ANDA ---
        $products = [
            // Produk untuk Kategori "Ikan Segar" (category_id = 1)
            [
                'category_id' => 1,
                'name' => 'Ikan Mas Segar',
                'description' => 'Ikan mas segar pilihan, cocok untuk diarsik atau digoreng.',
                'price' => 55000,
                'stock' => 30,
                'weight' => 1.0,
                'image' => 'products/Ikan Mas Segar.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'Ikan Lele Segar',
                'description' => 'Lele segar, dibudidayakan secara alami, bebas bau tanah.',
                'price' => 35000,
                'stock' => 50,
                'weight' => 1.0,
                'image' => 'products/Ikan Lele Segar.jpg'
            ],
            [
                'category_id' => 1,
                'name' => 'Ikan Batak (Ihan)',
                'description' => 'Ikan Batak asli dari Danau Toba, sangat langka dan berkhasiat.',
                'price' => 150000,
                'stock' => 10,
                'weight' => 1.0,
                'image' => 'categories/ikan-segar/Ikan-Batak-Segar.jpg' // Menggunakan gambar dari kategori
            ],
            [
                'category_id' => 1,
                'name' => 'Ikan Jair (Mujair) Segar',
                'description' => 'Ikan mujair segar, daging tebal dan gurih.',
                'price' => 45000,
                'stock' => 40,
                'weight' => 1.0,
                'image' => 'categories/ikan-segar/Ikan Jair Segar.jpg' // Menggunakan gambar dari kategori
            ],

            // Produk untuk Kategori "Olahan Ikan" (category_id = 2)
            [
                'category_id' => 2,
                'name' => 'Arsik Ikan Mas',
                'description' => 'Masakan khas Batak, arsik ikan mas dengan bumbu andaliman yang menggugah selera.',
                'price' => 85000,
                'stock' => 25,
                'weight' => 1.2,
                'image' => 'products/Arsik Ikan Mas.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Gurame Bakar',
                'description' => 'Ikan gurame pilihan dibakar dengan bumbu spesial, disajikan dengan sambal.',
                'price' => 75000,
                'stock' => 30,
                'weight' => 0.8,
                'image' => 'products/Gurame-bakar.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Ikan Bakar Sambal Matah',
                'description' => 'Ikan segar yang dibakar sempurna dan disiram dengan sambal matah pedas menyegarkan.',
                'price' => 65000,
                'stock' => 35,
                'weight' => 0.7,
                'image' => 'categories/olahan-ikan/Ikan bakar sambal matah.jpg'
            ],
             [
                'category_id' => 2,
                'name' => 'Naniura',
                'description' => 'Sashimi khas Batak, ikan mas yang dimatangkan dengan asam jungga tanpa dimasak.',
                'price' => 95000,
                'stock' => 15,
                'weight' => 0.9,
                'image' => 'categories/olahan-ikan/Ikan Naniura.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}