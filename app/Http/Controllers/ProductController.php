<?php

namespace App\Http\Controllers;

use App\Facades\CustomURL;
use App\Facades\Upload;
use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Models\Gallery;
use App\Models\Category;
use App\Models\Brand;
class ProductController extends Controller
{
    private $folderPath = 'products';
    /**
     * Display a listing of the resource.
     */
    public function all()
    {
        $products = Product::all();
        return Response()->json([
            'status'    => 200,
            'products'  => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }


    public function category_lists(){
        $categories = Category::where('parent_id','=',null)->with(['sub_categories'=>function($query){
            $query->where('status',1);
        }])->get();
        return Response()->json([
            'status'        => 200,
            'categories'    => $categories
        ],200);
    }

    public function brand_lists(){
        $brands = Brand::all();
        return Response()->json([
            'status'        => 200,
            'categories'    => $brands
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'          => 'required|string|max:255',
            'category_id'      => 'required',
            'brand_id'         => 'required',
            'thumbnail_image'     => 'required|mimes:jpg,png,jpeg,webp,gif',
            'price'         => 'required'
        ]);

        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }

        

        $product = new Product();
        $product->name = $request->name;
        $product->slug = CustomURL::create(Product::class, 'slug', $request->name);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        if($request->hasFile('thumbnail_image')){
            $thumbnail_image = Upload::image($request,$this->folderPath, 'thumbnail_image');
            $product->thumbnail_image = $thumbnail_image;
        }
        
        $product->product_description = $request->product_description;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->discount_type = $request->discount_type;
        $product->quantity = $request->quantity;
        // $product->sku = $request->sku;
        $product->meta_title = $request->meta_title;
        $product->meta_keywords = $request->meta_keywords;
        $product->meta_description = $request->meta_description;

        if($request->hasFile('product_video')){
            $video_name = Upload::video($request,$this->folderPath,'product_video');
            $product->product_video = $video_name;
        }
        
        if($product->save()){
            $id = $product->id;
            $sku = 'SKU|' . env('APP_SHORT')  . '-' . rand(111111,999999) . $id;
            $updateSku = Product::select('sku','id')->where('id',$id)->get()->first();
            $updateSku->sku = $sku;
            $updateSku->update();
            if($request->hasFile('gallery')){
                foreach($request->gallery as $gallery){
                    $imageValidator = Validator::make($request->all(),[
                        'gallery'   => 'mimes:jpg,png,jpeg,webp,gif'
                    ]);
                    if($imageValidator->fails()){
                        return Response()->json([
                            'status'    => 401,
                            'errors'    => $validator->errors()->all()
                        ]);
                    }
                    $image_name = Upload::image($request,$this->folderPath,'gallery');
                    $newGallery = new Gallery();
                    $newGallery->name = $image_name;
                    $newGallery->product_id = $id;
                    $newGallery->save();
                }
            }

            foreach($request->tags as $tag){
                DB::table('tag_relation')->insert([
                    'tag_id'        => $tag['id'],
                    'product_id'    => $id
                ]);
            }

            return Response()->json([
                'status'    => 200,
                'message'   => 'Product Saved Successfully.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }






}
