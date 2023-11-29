<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\User;
use App\SmTestimonial;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\FrontSettings\SmTestimonialRequest;

class SmTestimonialController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index()
    {
        try{
            $testimonial = SmTestimonial::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.testimonial.testimonial_page', compact('testimonial'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }

    }

    public function store(SmTestimonialRequest $request)
    {
        try{
            $destination =  'public/uploads/testimonial/';
            $image=fileUpload($request->image,$destination);
            $testimonial = new SmTestimonial();            
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->institution_name = $request->institution_name;
            $testimonial->image = $image;
            $testimonial->description = $request->description;
            $testimonial->school_id = app('school')->id;
            $result = $testimonial->save();
         
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
            $testimonial = SmTestimonial::where('school_id', app('school')->id)->get();
            $add_testimonial = SmTestimonial::find($id);
            return view('backEnd.frontSettings.testimonial.testimonial_page', compact('add_testimonial', 'testimonial'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(SmTestimonialRequest $request)
    {
     

        try{
       
            $destination =  'public/uploads/testimonial/'; 
            $testimonial = SmTestimonial::find($request->id);
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->institution_name = $request->institution_name;
            $testimonial->school_id = app('school')->id;          
            $testimonial->image = fileUpdate($testimonial->image,$request->image,$destination);       
            $testimonial->description = $request->description;
            $result = $testimonial->save();
      
            Toastr::success('Operation successful', 'Success');
            return redirect('testimonial');
          
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function testimonialDetails($id)
    {

        try{
            $testimonial = SmTestimonial::find($id);
            return view('backEnd.frontSettings.testimonial.testimonial_details', compact('testimonial'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function forDeleteTestimonial($id)
    {

        try{
            return view('backEnd.frontSettings.testimonial.delete_modal', compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function delete($id)
    {

        try{
            $testimonial = SmTestimonial::find($id);
            if(!empty($testimonial->image) && file_exists($testimonial->image)){
                unlink($testimonial->image);
            }
            $testimonial->delete();
           
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
            
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
}
