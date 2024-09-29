<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

    if(!function_exists('image_upload')){
        function image_upload($request,$folderPath,$inputName){
            $image = $request->file($inputName);
            $name = md5(time() . uniqid()) . '_rh' . '.jpg';
            $path = $folderPath . '/';
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

    if(!function_exists('video_upload')){
        function video_upload($request,String $folderPath, $inputName = 'video'){
            $video = $request->file($inputName);
            $fileName = md5(time() . uniqid()) . '_rh.' . $video->getClientOriginalExtension();
            $path = $folderPath . '/';
            if(!File::exists($path)){
                File::makeDirectory($path);
            }
            $video->move($path, $fileName);
            return $fileName;
        }
    }