<?php 

    namespace App;
    use Illuminate\Support\Str;
class CustomURL{
    public function create($class, $column,String $name){
        $url = Str::slug($name);
        $category = $class::where($column,$url)->get();
        if($category->count() > 0){
            $url = $url . '-' . $class::count();
        }
        return $url;
    }
}