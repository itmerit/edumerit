<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmNewsPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\FrontSettings\NewsHeadingRequest;

class NewsHeadingController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }
    public function index()
    {

        try {
            $SmNewsPage = SmNewsPage::where('school_id', app('school')->id)->first();
            $update = "";

            return view('backEnd.frontSettings.news.newsHeadingUpdate', compact('SmNewsPage', 'update'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(NewsHeadingRequest $request)
    {
        try {
            $path = 'public/uploads/about_page/';
            $newsHeading = SmNewsPage::where('school_id', app('school')->id)->first();

            if ($newsHeading) {
                $newsHeading->image  = fileUpdate($newsHeading->image,$request->image,$path);
                $newsHeading->main_image  = fileUpdate($newsHeading->main_image,$request->main_image,$path);
            }else{
                $newsHeading = new SmNewsPage();
                $newsHeading->image  = fileUpload($request->image,$path);
                $newsHeading->main_image  = fileUpload($request->main_image,$path);
                $newsHeading->school_id = app('school')->id; 
            }

            $newsHeading->title = $request->title;
            $newsHeading->description = $request->description;
            $newsHeading->main_title = $request->main_title;
            $newsHeading->main_description = $request->main_description;
            $newsHeading->button_text = $request->button_text;
            $newsHeading->button_url = $request->button_url;                     
            $newsHeading->save();

           
            Toastr::success('Operation successful', 'Success');
            return redirect('news-heading-update');
           
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

}
