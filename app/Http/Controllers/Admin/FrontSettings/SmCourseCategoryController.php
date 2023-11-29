<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmCourse;
use App\SmCourseCategory;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\FrontSettings\SmCourseCategoryRequest;

class SmCourseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }
    public function index()
    {
        try{
            $course_categories = SmCourseCategory::where('school_id', app('school')->id)->get();
            return view('backEnd.course.course_category',compact('course_categories'));
        }catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmCourseCategoryRequest $request)
    {
     
        try {
          
            $destination = 'public/uploads/course/';
            $image=fileUpload($request->category_image,$destination);

            SmCourseCategory::create([
                'category_name' => $request->category_name,
                'category_image' => $image,
                'school_id' => app('school')->id,
            ]);

            Toastr::success('Operation Successfull', 'Success');
            return redirect('course-category');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{
            $editData = SmCourseCategory::where('id',$id)
                                ->where('school_id', app('school')->id)
                                ->first();

            $course_categories = SmCourseCategory::where('school_id', app('school')->id)->get();

            return view('backEnd.course.course_category',compact('editData','course_categories'));
        }catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmCourseCategoryRequest $request)
    {
        
        try{
            
            $destination = 'public/uploads/course/';

            $data = SmCourseCategory::find($request->id);
            $data->category_name = $request->category_name;
            $data->school_id = app('school')->id;
          
            $data->category_image = fileUpdate($data->category_image,$request->category_image,$destination);
          
            $result = $data->save();

            Toastr::success('Operation Successfull', 'Success');
            return redirect('course-category');
           
        }catch(\Exception $e){

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try{
            $data = SmCourseCategory::find($request->id);
                if ($data->category_image != "") {
                    unlink($data->category_image);
                }
          $data->delete();
             
            Toastr::success('Operation Successfull', 'Success');
            return redirect('course-category');
            
        }catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function view($id)
    {
        try {
            $category_id = SmCourseCategory:: find($id);
            $courseCtaegories = SmCourse::where('category_id',$category_id->id)
                        ->where('school_id', app('school')->id)
                        ->get();
            return view('frontEnd.home.course_category', compact('category_id','courseCtaegories'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

}