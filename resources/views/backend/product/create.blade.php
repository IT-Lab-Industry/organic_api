@extends('backend.layout')
@section('title')
    Create Product Page
@endsection
@section('content')


    <form action="">
        <div class="inputs">
            <input type="text" name="name" value="{{ old('name') }}" id="name">
        </div>
        <div class="inputs">
            <select name="category_id" id="">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @foreach($category->sub_categories as $subCategory)
                        <option value="{{ $subCategory->id }}">--{{ $subCategory->name }}</option>
                        @foreach($subCategory->sub_categories as $subSubCategory)
                            <option value="{{ $subSubCategory->id }}">--{{ $subSubCategory->name }}</option>
                        @endforeach
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="inputs">
        <select name="brand_id" id="">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="inputs">
            <input type="file" name="thumbnail_image" id="">
        </div>
        <div class="inputs">
            <input type="number" name="price" id="">
        </div>
        <div class="inputs">
            <input type="number" name="discount_price" id="">
        </div>
        <div class="inputs">
            <select name="discount_type" id="">
                <option value="flat">Flat</option>
                <option value="percent">Percenties</option>
            </select>
        </div>
        <div class="inputs">
            <textarea name="product_description" id="" rows="10"></textarea>
        </div>
        <div class="inputs">
            <input type="number" name="quantity" id="">
        </div>
        <div class="inputs">
            <input type="text" name="meta_title" id="">
        </div>
        <div class="inputs">
            <input type="text" name="meta_keywords" id="">
        </div>
        <div class="inputs">
            <input type="text" name="meta_description" id="">
        </div>
        <div class="inputs">
            <input type="submit" value="Add Product" id="add_product">
        </div>
    </form>

    <script>
        document.getElementById('add_product').addEventListener('click',function(e){
            e.preventDefault();
            console.log('created product')
        })
    </script>


@endsection