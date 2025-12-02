<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\General\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = ['Product Qlab', 'Product Non Qlab'];

        foreach ($categories as $categorie) {
            Category::create([
                'name' => $categorie,
                'slug' => Str::slug($categorie)
            ]);
        }
    }
}
