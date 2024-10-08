<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Upload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class CategoryController extends Controller
{
    private $imagePath = 'category';
    public function all(){
        $categories = Category::where('parent_id',null)->with('sub_categories')->get();
        return Response()->json([
            'status'    => 200,
            'categories'=> $categories
        ]);
    }


    protected function createURL(String $name){
        $url = Str::slug($name);
        $category = Category::where('url',$url)->get();
        if($category->count() > 0){
            $url = $url . '-' . Category::count();
        }
        return $url;
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:255',
            'status'    => 'required',
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }
        $category = new Category();
        $category->name = $request->name;
        $category->url = $this->createURL($request->name);
        $category->parent_id = $request->parent_id;

        if($request->hasFile('logo')){
            $logo = Upload::image($request,$this->imagePath,'logo');
            $category->logo = $logo;
        }

        if($request->hasFile('banner')){
            $banner = Upload::image($request,$this->imagePath,'banner');
            $category->banner = $banner;
        }

        // if($request->hasFile('video')){
        //     $video = Upload::video($request, $this->imagePath,'video');
        //     $category->video = $video;
        // }

        $category->featured = $request->featured == 'true' ? 1 : 0;

        $category->meta_title = $request->meta_title;
        $category->keywords = $request->keywords;
        $category->meta_description = $request->meta_description;

        

        if($category->save()){
            return Response()->json([
                'status'    => 200,
                'message'   => 'Category saved successfully'
            ]);
        }else{
            return Response()->json([
                'status'    => 402,
                'message'   => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function edit($id){
        $category = Category::find($id);
        if($category){
            return Response()->json([
                'status'    => 200,
                'category'  => $category
            ]);
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Category not found.'
            ]);
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:255',
            'status'    => 'required',
            'id'        => 'required',
            'url'       => 'required|string|max:255'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }
        $category = Category::find($request->id);
        if($category){
            $category->name = $request->name;
            $category->parent_id = $request->parent_id;
            if($category->url == $request->url){
                $category->url = Str::slug($request->url);
            }else{
                $urlValidator = Validator::make($request->all(),[
                    'url'   => 'unique:categories.url'
                ]);
                if($urlValidator->fails()){
                    return Response()->json([
                        'status'    => 401,
                        'errors'    => $urlValidator->errors()->all()
                    ]);
                }
                $category->url = $this->createURL($request->name);
            }

            
            if($request->hasFile('banner')){
                if(File::exists($this->imagePath . '/' . $category->banner)){
                    unlink($this->imagePath . '/' . $category->banner);
                }
                $banner = Upload::image($request,$this->imagePath,'banner');
                $category->banner = $banner;
            }

            if($request->hasFile('video')){
                if(File::exists($this->imagePath . '/' . $category->video)){
                    unlink($this->imagePath . '/' . $category->video);
                }
                $video = Upload::video($request,$this->imagePath,'video');
                $category->video = $video;
            }

            $category->top_category = $request->top_category == 'true' ? 1 : 0;
            $category->featured = $request->featured == 'true' ? 1 : 0;

            $category->status = $request->status;
            if($request->hasFile('logo')){
                if(File::exists($this->imagePath . '/' . $category->logo)){
                    unlink($this->imagePath . '/' . $category->logo);
                }
                $logo = Upload::image($request,$this->imagePath,'logo');
                $category->logo = $logo;
            }

            $category->meta_title = $request->meta_title;
            $category->keywords = $request->keywords;
            $category->meta_description = $request->meta_description;

            if($category->update()){
                return Response()->json([
                    'status'    => 200,
                    'message'   => 'Category update successfully'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Category not found.'
            ]);
        }
    }

    public function delete($id){
        $category = Category::find($id);
        if($category){
            if(File::exists($this->imagePath . '/' . $category->logo) && $category->logo != null){
                unlink($this->imagePath . '/' . $category->logo);
            }
            if(File::exists($this->imagePath . '/' . $category->banner) && $category->banner != null){
                unlink($this->imagePath . '/' . $category->banner);
            }
            if(File::exists($this->imagePath . '/' . $category->video) && $category->video != null){
                unlink($this->imagePath . '/' . $category->video);
            }
            if($category->delete()){
                return Response()->json([
                    'status'   => 200,
                    'message'   => 'Category Deleted Successfully'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Category not found.'
            ]);
        }
    }










    // View Function
    
    // public function create(){
    //     $categories = Category::where('parent_id',null)->with('sub_categories')->get();
    //     return view('backend.category.create',[
    //         'categories'    => $categories,
    //         'category_lists'    => Category::all()
    //     ]);
    // }

    // public function viewEdit($id){
    //     $categories = Category::where('parent_id',null)->with('sub_categories')->get();
    //     $category = Category::findOrFail($id);
    //     return view('backend.category.edit',[
    //         'data'  => $category,
    //         'categories'=> $categories
    //     ]);
    // }

    // public function viewDelete($id){
    //     $category = Category::findOrFail($id);
    //     if(File::exists(public_path() .'/'. $this->imagePath . '/' . $category->logo)){
    //         unlink(public_path() .'/'. $this->imagePath . '/' . $category->logo);
    //     }
    //     if(File::exists(public_path() .'/'. $this->imagePath . '/' . $category->banner)){
    //         unlink(public_path() .'/'. $this->imagePath . '/' . $category->banner);
    //     }
    //     if(File::exists(public_path() . $this->imagePath . '/' . $category->video)){
    //         unlink(public_path() .'/'. $this->imagePath . '/' . $category->video);
    //     }
    //     if($category->delete()){
    //         return back();
    //     }
    // }


}
