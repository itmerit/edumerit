<?php

namespace App\Http\Controllers;

use File;
use ZipArchive;
use App\SmBackup;
use App\SmLanguage;
use App\Traits\UploadTheme;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\ImportLanguageRequestForm;

class LanguageController extends Controller
{
    use UploadTheme;
    public function index(string $lang)
    {
        $files = $this->fileList($lang);
        return view('backEnd.systemSettings.language_export',compact('files', 'lang'));
    }
    public function export(Request $request)
    {
        if (config('app.app_sync')) {
            Toastr::error(trans('Prohibited in demo mode.'), trans('common.failed'));
            return redirect()->back();
        }
        try {
            if(empty($request->lang_files)) {
                Toastr::error(__('system_settings.Files Empty'), __('common.error'));
                return redirect()->route('lang-file-download', $request->lang);
            }    
           
            $fileName = $this->fileName($request->lang, $request->lang_files);
            if(file_exists($fileName)) {
                return response()->download($fileName);
            }
        } catch (\Throwable $th) {
            Toastr::error(__('common.Operation Failed'), __('common.error'));
            return redirect()->back();
        }
    }
    public function fileList(string $lang):array
    {
       
        $allActiveModules = Module::allEnabled();
        $fileList = glob('resources/lang/'.$lang.'/*.php');
        $moduleLangFileList = [];
        foreach ($allActiveModules as $key=>$module) {            
            $moduleLangFileList[] = glob('Modules/'.$key.'/Resources/lang/'.$lang.'/*.php');
        }
       $moduleLangFileList = array_reduce($moduleLangFileList, function($carry, $array) {
            return array_merge($carry, $array);
        }, []);

        $fileList = array_merge($fileList, $moduleLangFileList);

        return $fileList;
 
    }
    public function fileName(string $lang, array $fileList = [], string $fileName = null):string
    {
        $zip = new ZipArchive;
        $fileName =  $fileName ?? $lang.'_language_file'.'.zip';
        $filePath = public_path($fileName);
     
        if(file_exists($filePath)) {
            unlink($filePath);
        }
        if ($zip->open($filePath, ZipArchive::CREATE) === TRUE)
        {
            foreach ($fileList as $key => $filepath) {
                 $zip->addFile(base_path($filepath),$filepath);
            }             
            $zip->close();
        }
        
        return $filePath;
    }
    public function importLang(string $lang)
    {
        $backuplangs = SmBackup::whereNotNull('lang_type')
                        ->where('school_id', auth()->user()->school_id)->get();
        $language =  SmLanguage::where('language_universal', $lang)->first();                
        return view('backEnd.systemSettings.language_import',compact('backuplangs', 'language'));
    }
    public function import(ImportLanguageRequestForm $request)
    {
        if (config('app.app_sync')) {
            Toastr::error(trans('Prohibited in demo mode.'), trans('common.failed'));
            return redirect()->back();
        }
        ini_set('memory_limit', '-1');

        try {  

            if ($request->hasFile('language_file')) {
                $path = $request->language_file->store('language_file');
                $request->language_file->getClientOriginalName();
                $zip = new ZipArchive;
                $res = $zip->open(storage_path('app/' . $path));
                if ($res === true) {
                    $zip->extractTo(storage_path('app/tempLangUpdate'));
                    $zip->close();
                } else {
                    abort(500, 'Error! Could not open File');
                }                

                $src = storage_path('app/tempLangUpdate');
                $dst = base_path('/');    
                $this->recurse_copy($src, $dst);  
            }


            if (storage_path('app/language_file')) {
                $this->delete_directory(storage_path('app/language_file'));
            }
            if (storage_path('app/tempLangUpdate')) {
                $this->delete_directory(storage_path('app/tempLangUpdate'));
            }

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');

            Toastr::success("Language File updated", 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            if (storage_path('app/language_file')) {
                $this->delete_directory(storage_path('app/language_file'));
            }
            if (storage_path('app/tempLangUpdate')) {
                $this->delete_directory(storage_path('app/tempLangUpdate'));
            }    
            Toastr::error($e->getMessage(), trans('common.error'));
            return redirect()->back();
        }
        
    }

    private function recurse_copy($src, $dst)
    {
        
        try {
            $dir = opendir($src);           
            @mkdir($dst);           
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {                    
                    if (is_dir($src . '/' . $file)) {                       
                        $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function backupLanguage(string $lang)
    {
        if (config('app.app_sync')) {
            Toastr::error(trans('Prohibited in demo mode.'), trans('common.failed'));
            return redirect()->back();
        }
        $backup = SmBackup::latest()->first();
        if(!$backup) {
            $id = 1;
        }else {
            $id = $backup->id;
        }
        $uuid = date('Y-m-d').'_'.$id;
        $fileList = $this->fileList($lang);
        $fileName = $lang.'_backup_language_file_'.$uuid.'.zip';
        $this->fileName($lang, $fileList, $fileName);
        $this->backupLanguageStore($lang, $fileName);
        Toastr::success(__('common.Operation successful'), __('common.success'));
        return redirect()->route('lang-file-import', $lang);

    }
    private function backupLanguageStore($lang, $file_name)
    {        
        $store = new SmBackup();
        $store->file_name = $file_name;
        $store->source_link = $file_name;
        $store->active_status = 1;
        $store->lang_type = $lang ?? 'en';
        $store->created_by = auth()->user()->id;
        $result = $store->save();
    }

}
