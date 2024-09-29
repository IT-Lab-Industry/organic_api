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
            <input type="text" name="name" placeholder="Name">
        </div>
        <div class="form-control">
            <select name="status" id="">
                <option value="1">Published</option>
                <option value="0">Unpublished</option>
            </select>
        </div>
        <div class="form-control">
            <select name="parent_id" id="">
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
            <label for="logo">Logo</label>
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
            <input type="text" name="meta_title" placeholder="Meta Title" id="">
        </div>
        <div class="form-control">
            <input type="text" name="meta_keywords" placeholder="Meta Keywords" id="">
        </div>
        <div class="form-control">
            <input type="text" name="meta_description" placeholder="Meta Description" id="">
        </div>
        <div class="form-control">
            <input type="submit" value="Create Category" id="category">
        </div>
    </form>


    <div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($category_lists as $index => $category)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <a href="{{ Route('category.view.edit',$category->id) }}">Edit</a>
                            <a href="{{ Route('category.view.delete',$category->id) }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <script>
        const form = document.querySelector('form');
        const category = document.querySelector('#category').addEventListener('click',function(e){
            e.preventDefault();
            const name = form.name.value;
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

            

            fetch("{{ route('category.store') }}",{
                method: 'POST',
                body:formData,
                headers: {
                    accept: 'application/json'
                }
            }).then(res => res.json()).then(response => {
                if(response.status == 200){
                    alert('Data save successfully')
                }else{
                    alert('Something went wrong. Please try again.')
                }
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