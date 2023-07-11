<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use File;

trait ImageStore
{
    public static function saveImage($image, $height = null ,$lenght = null)
    {
        if(isset($image)){
            $current_date  = Carbon::now()->format('d-m-Y');

            if(!File::isDirectory('uploads/images/'.$current_date)){
                File::makeDirectory('uploads/images/'.$current_date, 0777, true, true);
            }

           $image_extention = str_replace('image/','',Image::make($image)->mime());

           if($height != null && $lenght != null ){
               $img = Image::make($image)->resize($height, $lenght);
           }else{
               $img = Image::make($image);
           }

           $img_name = 'uploads/images/'.$current_date.'/'.uniqid().'.'.$image_extention;
           $img->save($img_name);
           return $img_name;
        }else{
            return null ;
        }
    }

    public static function saveFile(UploadedFile $file)
    {
        if(isset($file)){
            $current_date  = Carbon::now()->format('d-m-Y');

            if(!File::isDirectory('uploads/file/'.$current_date)){
                File::makeDirectory('uploads/file/'.$current_date, 0777, true, true);
            }
            $file_name = uniqid().'.'.$file->extension();
//            $file->storeAs('/uploads/file/'.$current_date.'/', $file_name);
            $s = Storage::disk('custom')->put('file/'.$current_date, $file);
            return 'uploads/'.$s;
        }else{
            return null ;
        }
    }


    public static function saveSettingsImage($image, $height = null ,$lenght = null)
    {
        if(isset($image)){
           $current_date = Carbon::now()->format('d-m-Y');
           $image_extention = str_replace('image/','',Image::make($image)->mime());

           if($height != null && $lenght != null ){
               $img = Image::make($image)->resize($height, $lenght);
           }else{
               $img = Image::make($image);
           }

           $img_name = 'uploads/settings'.'/'.uniqid().'.'.$image_extention;
           $img->save($img_name);
           return $img_name;
        }else{
            return null ;
        }
    }

    public static function saveAvatarImage($image, $height = null ,$lenght = null)
    {
        if(isset($image)){
            $current_date  = Carbon::now()->format('d-m-Y');
            $image_extention = str_replace('image/','',Image::make($image)->mime());

            if($height != null && $lenght != null ){
                $img = Image::make($image)->resize($height, $lenght);
            }else{
                $img = Image::make($image);
            }

            $img_name = 'uploads/avatar'.'/'.uniqid().'.'.$image_extention;
            $img->save($img_name);
            return $img_name;
        }else{
            return null ;
        }
    }


    public static function deleteImage($url)
    {
        if(isset($url)){
            if (File::exists($url)) {
                File::delete($url);
                return true;
            }else{
                return false;
            }
        }else{
            return null ;
        }
    }

    public function saveAvatar($image, $height = null ,$lenght = null)
    {
        if(isset($image)){
            $current_date  = Carbon::now()->format('d-m-Y');

            if(!File::isDirectory('uploads/avatar/'.$current_date)){

                File::makeDirectory('uploads/avatar/'.$current_date, 0777, true, true);

            }

           $image_extention = str_replace('image/','',Image::make($image)->mime());

           if($height != null && $lenght != null ){
               $img = Image::make($image)->resize($height, $lenght);
           }else{
               $img = Image::make($image);
           }

           $img_name = 'uploads/avatar/'.$current_date.'/'.uniqid().'.'.$image_extention;
           $img->save($img_name);
           return $img_name;
        }else{
            return null ;
        }
    }
}
