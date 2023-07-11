<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmAboutPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\FrontSettings\AboutPageRequest;

class AboutPageController extends Controller
{
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
            $about_us = SmAboutPage::first();
            return view('backEnd.frontSettings.about_us', compact('about_us'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit()
    {

        try {
            $about_us = SmAboutPage::first();
            $update = "";

            return view('backEnd.frontSettings.about_us', compact('about_us', 'update'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(AboutPageRequest $request)
    {    

        try {
     
            $about = SmAboutPage::first();
            $destination  = 'public/uploads/about_page/';

            if($about){
               
                $about->image     = fileUpdate($about->image,$request->image,$destination);
                $about->main_image = fileUpdate($about->main_image,$request->main_image,$destination); 
            }else{
                $about = new SmAboutPage();
                $about->image     = fileUpload($request->image,$destination);
                $about->main_image = fileUpload($request->main_image,$destination); 
                $about->school_id = app('school')->id;
            }
              
          
            $about->title = $request->title;
            $about->description = $request->description;
            $about->main_title = $request->main_title;
            $about->main_description = $request->main_description;
            $about->button_text = $request->button_text;
            $about->button_url = $request->button_url; 
            $result = $about->save();
           
            Toastr::success('Operation Successful', 'Success');
            return redirect('about-page');
           
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
