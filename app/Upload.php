<?php 

    namespace App;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\File;
    use Intervention\Image\Drivers\Gd\Driver;
    use Intervention\Image\ImageManager;

class Upload{
    public function video($request, String $folderPath, $inputName = 'video'){
        $video = $request->file($inputName);
        $fileName = md5(time() . uniqid()) . '_rh.' . $video->getClientOriginalExtension();
        $path = $folderPath . '/';
        if(!File::exists($path)){
            File::makeDirectory($path);
        }
        $video->move($path, $fileName);
        return $fileName;
    }

    public function image($request, String $folderPath, $inputName = 'image'){
        $image = $request->file($inputName);
        $name = md5(time() . uniqid()) . '_rh' . '.jpg';
        $path = public_path() . '/' . $folderPath . '/';
        if(!File::exists($path)){
            File::makeDirectory($path);
        }
        $fullPath = $path . $name;
        $imageManager = new ImageManager(new Driver());
        $readData = $imageManager->read($image);
        $readData->toWebp(60)->save($fullPath);
        return $name;
    }

    
}