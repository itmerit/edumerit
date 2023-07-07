<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmContactPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class SmContactUsController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }
    public function index()
    {
        try {
            $module_links = InfixPermissionAssign::where('role_id', Auth::user()->role_id)->where('school_id', Auth::user()->school_id)->pluck('module_id')->toArray();

            $contact_us = SmContactPage::where('school_id', app('school')->id)->first();
            return view('backEnd.frontSettings.contact_us', compact('contact_us', 'module_links'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit()
    {

        try {
            $contact_us = SmContactPage::where('school_id', app('school')->id)->first();
            $update = "";

            return view('backEnd.frontSettings.contact_us', compact('contact_us', 'update'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
     

        try {
          
            $destination='public/uploads/contactPage/';
            $fileName=fileUpload($request->image, $destination);
            $contact = SmContactPage::where('school_id', app('school')->id)->first();
            if ($contact == "") {
                $contact = new SmContactPage();
                $contact->school_id = app('school')->id;   
            }
            $contact->title = $request->title;
            $contact->description = $request->description;
            $contact->button_text = $request->button_text;
            $contact->button_url = $request->button_url;

            $contact->address = $request->address;
            $contact->address_text = $request->address_text;
            $contact->phone = $request->phone;
            $contact->phone_text = $request->phone_text;
            $contact->email = $request->email;
            $contact->email_text = $request->email_text;
            $contact->latitude = $request->latitude;
            $contact->longitude = $request->longitude;
            $contact->zoom_level = $request->zoom_level;
            $contact->school_id = Auth::user()->school_id;
            $contact->google_map_address = $request->google_map_address;
                    
            $contact->image = $fileName;
            $result = $contact->save();
           
            Toastr::success('Operation successful', 'Success');
            return redirect('contact-page');
           
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

}
