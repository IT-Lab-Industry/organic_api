<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';

    public function category(){
        return $this->hasOne(Category::class, 'category_id','id');
    }

    public function brand(){
        return $this->hasOne(Brand::class, 'brand_id','id');
    }

}
