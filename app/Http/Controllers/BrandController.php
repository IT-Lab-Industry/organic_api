<?php

namespace App\Http\Controllers;

use App\Facades\CustomURL;
use App\Facades\Upload;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{


    private $imagePath = 'brand';
    public function all(){
        $brands = Brand::latest()->get();
        return Response()->json([
            'status'    => 200,
            'brands'      => $brands
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:255'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }
        $brand = new Brand();
        $brand->name = $request->name;
        if($request->hasFile('logo')){
            $name = Upload::image($request,$this->imagePath,'logo');
            $brand->logo = $name;
        }
        $brand->slug = CustomURL::create($brand,'slug',$request->name);
        if($brand->save()){
            return Response()->json([
                'status'    => 200,
                'message'   => 'Tag Created Successfully'
            ]);
        }else{
            return Response()->json([
                'status'    => 402,
                'message'   => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function edit($id){
        $brand = Brand::find($id);
        if($brand){
            return Response()->json([
                'status'    => 200,
                'brand'       => $brand
            ]);
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Brand not found'
            ]);
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:255',
            'id'        => 'required',
            'slug'       => 'required'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }

        $brand = Brand::find($request->id);
        if($brand){
            $brand->name = $request->name;
            if($brand->slug == $request->slug){
                $brand->slug = $request->slug;
            }else{
                $slugValidator = Validator::make($request->all(),[
                    'slug'      => 'unique:tags,slug'
                ]);
                if($slugValidator->fails()){
                    return Response()->json([
                        'status'    => 401,
                        'errors'    => $slugValidator->errors()->all()
                    ]);
                }
                $brand->slug = CustomURL::create($brand,'slug',$request->slug);
            }

            if($brand->update()){
                return Response()->json([
                    'status'    => 200,
                    'message'   => 'Tag update successfully'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Tag not found'
            ]);
        }
    }

    public function delete($id){
        $brand = Brand::find($id);
        if($brand){
            if($brand->delete()){
                return Response()->json([
                    'status'    => 200,
                    'message'   => 'Tag Deleted Successfully'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Tag not found'
            ]);
        }
    }
}
