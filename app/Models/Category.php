<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'status'
    ];

    public function sub_categories(){
        return $this->hasMany(Category::class, 'parent_id','id')->with('sub_categories');
    }

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
