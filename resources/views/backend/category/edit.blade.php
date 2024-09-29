<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
</head>
<body>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-control">
            <input type="text" name="name" value="{{ old('name',$data->name) }}" placeholder="Name">
        </div>
        <div class="form-control">
            <input type="text" name="url" value="{{ old('url',$data->url) }}" placeholder="URL">
        </div>
        <div class="form-control">
            <select name="status" id="">
                <option value="1" {{ $data->status == 1 ? 'selected' : ''}}>Published</option>
                <option value="0" {{ $data->status == 0 ? 'selected' : ''}}>Unpublished</option>
            </select>
        </div>
        <div class="form-control">
            <select name="parent_id" id="parent_category">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @foreach($category->sub_categories as $subCategory)
                        <option value="{{ $subCategory->id }}">--{{ $subCategory->name }}</option>
                        @foreach($subCategory->sub_categories as $subSubCategory)
                            <option value="{{ $subSubCategory->id }}">----{{ $subSubCategory->name }}</option>
                        @endforeach
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-control">
            <label for="logo">{{ __('Logo')}}</label>
            <input type="file" name="logo" id="">
        </div>
        <div class="form-control">
            <label for="banner">Banner</label>
            <input type="file" name="banner" id="">
        </div>
        <div class="form-control">
            <label for="video">Video</label>
            <input type="file" name="video" id="">
        </div>
        <div class="form-control">
            <input type="checkbox" name="top_category" id="">
            <label for="top_category">Top Category</label>
        </div>
        <div class="form-control">
            <input type="checkbox" name="feature_category" id="">
            <label for="feature_category">Feature Category</label>
        </div>
        <div class="form-control">
            <input type="text" name="meta_title" value="{{ old('meta_title',$data->meta_title) }}" placeholder="Meta Title" id="">
        </div>
        <div class="form-control">
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords',$data->keywords) }}" placeholder="Meta Keywords" id="">
        </div>
        <div class="form-control">
            <input type="text" name="meta_description"  value="{{ old('meta_description',$data->meta_description) }}" placeholder="Meta Description" id="">
        </div>
        <div class="form-control">
            <input type="submit" value="Update Category" id="category">
        </div>
    </form>

   

    <script>
        const parent_category = document.getElementById('parent_category').value = "{{ $data->parent_id != null ? $data->parent_id : ''}}";
        const form = document.querySelector('form');
        const category = document.querySelector('#category').addEventListener('click',function(e){
            e.preventDefault();
            const name = form.name.value;
            const url = form.url.value;
            const status = form.status.value;
            const parent_id = form.parent_id.value;
            const logo = form.logo.files[0]
            const banner = form.banner.files[0]
            const video = form.video.files[0]
            const top_category = form.top_category.checked;
            const featured = form.feature_category.checked;
            const meta_title = form.meta_title.value;
            const meta_description = form.meta_description.value;
            const meta_keywords = form.meta_keywords.value;


            const formData = new FormData();
            formData.append('name',name)
            formData.append('url',url)
            formData.append('status',status)
            formData.append('parent_id',parent_id)
            formData.append('logo',logo != undefined ? logo : null)
            formData.append('banner',banner != undefined ? banner : null)
            formData.append('video',video != undefined ? video : null)
            formData.append('top_category',top_category)
            formData.append('featured',featured)
            formData.append('meta_title',meta_title)
            formData.append('meta_description',meta_description)
            formData.append('keywords',meta_keywords)
            formData.append('id',"{{ $data->id }}")

            

            fetch("{{ route('category.update') }}",{
                method: 'POST',
                body:formData,
                headers: {
                    accept: 'application/json'
                }
            }).then(res => res.json()).then(response => {
                console.log(response)
            })
        })

        function reloadCategory(){
            fetch("{{ route('category.all') }}").then(res => res.json()).then(response => {
                console.log(response)
            })
        }

        reloadCategory();
    </script>
</body>
</html>