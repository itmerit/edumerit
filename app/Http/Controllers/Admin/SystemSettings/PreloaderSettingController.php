<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use App\SmGeneralSettings;
use App\Traits\ImageStore;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PreloaderSettingController extends Controller
{
    use ImageStore;

    public function index()
    {
        return view('backEnd.systemSettings.preloader');
    }


    public function store(Request $request)
    {
        if (config('app.app_sync')) {
            Toastr::error(trans('Prohibited in demo mode.'), trans('common.failed'));
            return redirect()->back();
        }
        $setting = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first();
        $setting->preloader_status = $request->preloader_status;
        $setting->preloader_style = $request->preloader_style;
        $setting->preloader_type = $request->preloader_type;


        if ($request->hasFile('preloader_image')) {
            $setting->preloader_image = fileUpload($request->preloader_image,'public/uploads/preloader/' );
        }

        $setting->save();
        session()->forget('generalSetting');
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

}
