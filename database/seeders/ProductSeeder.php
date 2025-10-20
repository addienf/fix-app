<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\General\Product;
use App\Models\General\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
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

        Product::create([
            'name' => 'Mecmesin',
            'slug' => Str::slug('Mecmesin'),
            'category_id' => $nonQlabCategoryId,
        ]);
    }
}
