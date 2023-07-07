<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\User;
use App\SmNews;
use App\SmNewsCategory;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\FrontSettings\SmNewsRequest;

class SmNewsController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index()
    {

        try{
            $news = SmNews::where('school_id', app('school')->id)->get();
            $news_category = SmNewsCategory::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.news.news_page', compact('news', 'news_category'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(SmNewsRequest $request)
    {
        try{
            $destination='public/uploads/news/';
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);

            $news = new SmNews(); 
            $news->news_title = $request->title;
            $news->category_id = $request->category_id;
            $news->publish_date = $newformat;
            $news->image = fileUpload($request->image,$destination);
            $news->news_body = $request->description;
            $news->school_id = app('school')->id;
            $result = $news->save();
           
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
            
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function edit($id)
    {

        try{
            $news = SmNews::where('school_id', app('school')->id)->get();
            $add_news = SmNews::find($id);
            $news_category = SmNewsCategory::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.news.news_page', compact('add_news', 'news', 'news_category'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request)
    {
  
        try{
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);
            $destination='public/uploads/news/'; 

            $news = SmNews::find($request->id);
            $news->news_title = $request->title;
            $news->category_id = $request->category_id;
            $news->publish_date = $newformat;           
            $news->image = fileUpdate($news->image,$request->image,$destination);           
            $news->news_body = $request->description;
            $news->school_id = app('school')->id;
            $result = $news->save();
           
            Toastr::success('Operation successful', 'Success');
            return redirect('news');
           
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function newsDetails($id)
    {

        try{
            $news = SmNews::find($id);
            return view('backEnd.frontSettings.news.news_details', compact('news'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function forDeleteNews($id)
    {

        try{
            return view('backEnd.frontSettings.news.delete_modal', compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function delete($id)
    {

        try{
             SmNews::destroy($id);       
           
           Toastr::success('Operation successful', 'Success');
           return redirect()->back();
            
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function viewNewsCategory($id)
    {
        try {
            $category_id = SmNewsCategory:: find($id);
            $newsCtaegories = SmNews::where('category_id',$category_id->id)
                        ->where('school_id', app('school')->id)
                        ->get();
            return view('frontEnd.home.category_news', compact('category_id','newsCtaegories'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

}
