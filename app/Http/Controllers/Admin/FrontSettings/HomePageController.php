<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmGeneralSettings;
use App\SmHomePageSetting;
use Illuminate\Http\Request;
use App\SmFrontendPersmission;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\FrontSettings\HomePageRequest;

class HomePageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('PM');
        if (empty(Auth::user()->id)) {
            return redirect('login');
        }
    }


    public function index()
    {
        try {
            $links = SmHomePageSetting::where('school_id', app('school')->id)->first();
            $permisions = SmFrontendPersmission::where('parent_id', 1)->get();
            return view('backEnd.frontSettings.homePageBackend', compact('links', 'permisions'));
        } catch (\Exception $e) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(HomePageRequest $request)
    {

        try {
            $permisionsArray = $request->permisions ?? [];
            SmFrontendPersmission::where('parent_id', 1)->update(['is_published' => 0]);
            foreach ($permisionsArray as $value) {
                SmFrontendPersmission::where('id', $value)->update(['is_published' => 1]);
            }

            $path = 'public/uploads/homepageCreate/';
            

            //Update Home Page
            $update = SmHomePageSetting::where('school_id', app('school')->id)->first();
            $update->title = $request->title;
            $update->long_title = $request->long_title;
            $update->short_description = $request->short_description;
            $update->link_label = $request->link_label;
            $update->link_url = $request->link_url;
            $update->school_id = app('school')->id;            
            $update->image =fileUpdate($update->image,$request->image,$path);          
            $update->save();

            Toastr::success('Operation Successful', 'Success');
            return redirect()->route('admin-home-page');
           
        } catch (\Exception $e) {            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
