<?php

namespace Database\Seeders;

use App\Facades\CustomURL;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(collect(range(1,50)) as $data){
            $brand = new Brand();
            $name = fake()->name;
            $brand->name = $name;
            $brand->slug = CustomURL::create(Brand::class, 'slug', $name);
            $brand->save();
        }
    }
}
