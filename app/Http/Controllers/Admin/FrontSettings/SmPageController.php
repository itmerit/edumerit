<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\FrontSettings\SmPageRequest;

class SmPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }
    public function index()
    {
        try {
            $pages = SmPage::where('school_id', app('school')->id)->where('is_dynamic', 1)->get();
            return view('backEnd.frontSettings.pageList', compact('pages'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function create()
    {
        return view('backEnd.frontSettings.createPage');
    }

    public function store(SmPageRequest $request)
    {
        
        try {
           
            $destination='public/uploads/pages/';
            $fileName = fileUpload($request->header_image,$destination);
            $data = new SmPage();
            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->slug = $request->slug;
            $data->details = $request->details;
            $data->header_image = $fileName;
            $data->school_id = app('school')->id;
            $data->save();
            Toastr::success('Operation successfull', 'Success');
            return redirect('create-page');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $editData = SmPage::find($id);
            return view('backEnd.frontSettings.createPage', compact('editData'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmPageRequest $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'slug' => 'required|unique:sm_pages,slug,' . $request->id,
        //     'sub_title'=>'nullable',
        //     'details' => 'required',
        // ]);

        try {

           
            $destination='public/uploads/pages/';          

            $data = SmPage::find($request->id);
            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->slug = $request->slug;
            $data->details = $request->details;
            $data->school_id = app('school')->id;            
            $data->header_image = fileUpload($data->header_image,$request->header_image,$destination);         
            $data->save();
            
            Toastr::success('Operation successfull', 'Success');
            return redirect('page-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
