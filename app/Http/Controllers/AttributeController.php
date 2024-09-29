<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function all(){
        $attributes = Attribute::all();
        return Response()->json([
            'status'    => 200,
            'attributes'=> $attributes
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'  => 'required|string|max:255'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }

        $attribute = new Attribute();
        $attribute->name = $request->name;
        if($attribute->save()){
            return Response()->json([
                'status'    => 200,
                'message'   => 'Attribute created successfully'
            ]);
        }else{
            return Response()->json([
                'status'    => 402,
                'message'   => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function edit($id){
        $attribute = Attribute::where('id',$id)->with('attribute_values')->first();
        if($attribute){
            return Response()->json([
                'status'    => 200,
                'attribute'       => $attribute
            ]);
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Attribute not found'
            ]);
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:255',
            'id'        => 'required'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'message'   => $validator->errors()->all()
            ]);
        }
        $attribute = Attribute::find($request->id);
        $attribute->name = $request->name;
        if($attribute->update()){
            return Response()->json([
                'status'    => 200,
                'message'   => 'Attribute created successfully'
            ]);
        }else{
            return Response()->json([
                'status'    => 402,
                'message'   => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function delete($id){
        $attribute = Attribute::find($id);
        if($attribute){
            if($attribute->delete()){
                return Response()->json([
                    'status'    => 200,
                    'message'   => 'Attribute update successfully'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Attribute not found'
            ]);
        }
    }




    // Attribute Value

    public function valueAll(){
        $attribute_values = AttributeValue::all();
        return Response()->json([
            'status'            => 200,
            'attributes_values' => $attribute_values
        ]);
    }

    public function valueStore(Request $request){
        $validator = Validator::make($request->all(),[
            'name'          => 'required|string|max:255',
            'attribute_id'  => 'required'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }

            $attribute_value = new AttributeValue();
            $attribute_value->name = $request->name;
            $attribute_value->attribute_id = $request->attribute_id;
            $attribute_value->save();
        return Response()->json([
            'status'    => 200,
            'message'   => 'Attribute Value created successfully'
        ]);
    }

    public function valueEdit($id){
        $attribute_value = AttributeValue::find($id);
        if($attribute_value){
            return Response()->json([
                'status'                => 200,
                'attribute_value'       => $attribute_value
            ]);
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Attribute Value not found'
            ]);
        }
    }

    public function valueUpdate(Request $request){
        $validator = Validator::make($request->all(),[
            'name'          => 'required|string|max:255',
            'id'            => 'required',
            'parent_id'     => 'required'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'message'   => $validator->errors()->all()
            ]);
        }
        $attribute_value = AttributeValue::find($request->id);
        $attribute_value->name = $request->name;
        $attribute_value->attribute_id = $request->attribute_id;
        if($attribute_value->update()){
            return Response()->json([
                'status'    => 200,
                'message'   => 'Attribute updated successfully'
            ]);
        }else{
            return Response()->json([
                'status'    => 402,
                'message'   => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function valueDelete($id){
        $attribute_value = AttributeValue::find($id);
        if($attribute_value){
            if($attribute_value->delete()){
                return Response()->json([
                    'status'    => 200,
                    'message'   => 'Attribute Value Deleted successfully'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 404,
                'message'   => 'Attribute Value not found'
            ]);
        }
    }
    
}
