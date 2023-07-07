<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Module;
use File;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {


        if (Storage::exists('.app_installed') && Storage::get('.app_installed') && DB::connection()->getDatabaseName() != '') {
            if (Schema::hasTable('sm_languages')) {
                Cache::remember('translations', Carbon::now()->addHours(6), function () {
                    return $this->getTranslations();
                });
            }
        }
    }


    public function getTranslations()
    {
        $translations = collect();

        $ln = [
            'en',
        ];
        $userLang = getUserLanguage();


        if($userLang != 'en'){
            $ln[] = $userLang;
        }
        foreach ($ln as $locale) {
            $translations[$locale] = [
                'json' => $this->jsonTranslations($locale),
                'php' => $this->phpTranslations($locale),
            ];
        }
        return $translations;
    }


    private function jsonTranslations($locale)
    {
        $files = glob(resource_path('lang/' . $locale . '/*.php'));

        $modules = \Nwidart\Modules\Facades\Module::all();
        foreach ($modules as $module) {
            if (moduleStatusCheck($module->getName())) {
                $module_files = glob(module_path($module->getName()) . '/Resources/lang/' . $locale . '/*.php');
                foreach ($module_files as $module_file) {
                    $files[] = $module_file;
                }
            }
        }

        $lang = [];

        foreach ($files as $file) {
            $file_name = basename($file, '.php');
            if ($file_name !=  'lang' && file_exists($file) && is_array(include($file))) {
                $lang = array_merge($lang, include($file));
            }
        }

        return json_encode($lang, true);

    }

    private function phpTranslations($locale)
    {
        $path = resource_path("lang/$locale");
        if(file_exists($path)){
            return collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
                $key = ($translation = $file->getBasename('.php'));
    
                return [$key => trans($translation, [], $locale)];
            });
        }

        
    }
}