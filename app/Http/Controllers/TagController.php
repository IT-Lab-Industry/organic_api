<?php

namespace App\Http\Controllers;

use App\Facades\CustomURL;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function all(){
        $tags = Tag::all();
        return Response()->json([
            'status'    => 200,
            'tags'      => $tags
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
        $tag = new Tag();
        $tag->name = $request->name;
        $tag->slug = CustomURL::create($tag,'slug',$request->name);
        if($tag->save()){
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
        $tag = Tag::find($id);
        if($tag){
            return Response()->json([
                'status'    => 200,
                'tag'       => $tag
            ]);
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Tag not found'
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

        $tag = Tag::find($request->id);
        if($tag){
            $tag->name = $request->name;
            if($tag->slug == $request->slug){
                $tag->slug = $request->slug;
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
                $tag->slug = CustomURL::create($tag,'slug',$request->slug);
            }

            if($tag->update()){
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
        $tag = Tag::find($id);
        if($tag){
            if($tag->delete()){
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
