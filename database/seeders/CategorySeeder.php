<?php

namespace Database\Seeders;

use App\Facades\CustomURL;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(collect(range(1,50)) as $category){
            $category = new Category();
            $name = fake()->name;
            $category->name = $name;
            $category->url = CustomURL::create(Category::class, 'url',$name);
            $category->status = rand(0,1);
            // $category->logo = fake()->image('public/category/',fullPath: false);
            $category->save();
        }
    }
}
