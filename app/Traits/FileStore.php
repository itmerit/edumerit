<?php

namespace App\Traits;

use File;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait FileStore
{

    public static function saveFile(UploadedFile $file)
    {
        if(isset($file)){
            $current_date  = Carbon::now()->format('d-m-Y');

            if(!File::isDirectory('uploads/file/'.$current_date)){
                File::makeDirectory('uploads/file/'.$current_date, 0777, true, true);
            }

            $file_name = uniqid().'.'.$file->extension();
            // $file->storeAs('uploads/file/'.$current_date.'/', $file_name);
            // $s = Storage::disk('custom')->put('file/'.$current_date, $file);
            // return 'uploads/file/'.$current_date.'/'.$file_name;
            $s = Storage::disk('custom')->put('file/'.$current_date, $file);
            return 'uploads/'.$s;
        }else{
            return null ;
        }
    }
}
