<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\General\Category;
use App\Models\General\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        User::factory()->create([
            'name' => 'Sales',
            'email' => 'sales@mail.com',
            'password' => Hash::make('sales123'),
            'role' => 'sales',
            'email_verified_at' => Carbon::now(),
        ]);

        $categories = [
            'Product Qlab',
            'Product Non Qlab'
        ];

        foreach ($categories as $categorie) {
            Category::create([
                'name' => $categorie,
                'slug' => Str::slug($categorie)
            ]);
        }

        $qlabCategoryId = Category::where('name', 'Product Qlab')->first()->id;
        $nonQlabCategoryId = Category::where('name', 'Product Non Qlab')->first()->id;

        $qlabProducts = [
            'QLab Walk-in Test Chamber',
            'Qlab Climatic Test Chamber',
            'Qlab Precision Refrigerator',
            'Qlab Monitoring System',
            'Other Qlab Product',
        ];

        foreach ($qlabProducts as $name) {
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'category_id' => $qlabCategoryId,
            ]);
        }

        // Mecmesin → Product Non Qlab
        Product::create([
            'name' => 'Mecmesin',
            'slug' => Str::slug('Mecmesin'),
            'category_id' => $nonQlabCategoryId,
        ]);
    }
}
