<?php

namespace App\Http\Controllers\Admin\FrontSettings;


use App\User;
use App\SmNews;
use App\ApiBaseMethod;
use App\SmNewsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\FrontSettings\SmNewsCategorRequest;

class SmNewsCategoryController extends Controller
{
    
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index()
    {

        try{
            $newsCategories = SmNewsCategory::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.news.news_category', compact('newsCategories'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    
    public function store(SmNewsCategorRequest $request)
    {
       
        try{
            $news_category = new SmNewsCategory();
            $news_category->category_name = $request->category_name;
            $news_category->school_id = app('school')->id;
            $news_category->save();
           
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
            $newsCategories = SmNewsCategory::where('school_id', app('school')->id)->get();
            $editData = SmNewsCategory::find($id);
            return view('backEnd.frontSettings.news.news_category', compact('newsCategories', 'editData'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmNewsCategorRequest $request)
    {
    

        try{
            $news_category = SmNewsCategory::find($request->id);
            $news_category->category_name = $request->category_name;
            $news_category->school_id = app('school')->id;
            $news_category->save();
          
            Toastr::success('Operation successful', 'Success');
            return redirect('news-category');
            
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function deleteModalOpen($id)
    {

        try{
            return view('backEnd.frontSettings.news.category_delete_modal', compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {
        try{
        $fk_id = 'category_id';

        $tables = \App\tableList::getTableList($fk_id,$id);

        try {
            $delete_query = SmNewsCategory::destroy($id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($delete_query) {
                    return ApiBaseMethod::sendResponse(null, 'News Category has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($delete_query) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error('This item already used', 'Failed');
            return redirect()->back();
           }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
