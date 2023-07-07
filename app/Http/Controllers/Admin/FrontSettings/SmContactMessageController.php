<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmContactPage;
use App\SmContactMessage;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmContactMessageController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
       
    }
    public function deleteMessage($id)
    {
        try {
            SmContactMessage::find($id)->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect('contact-message');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
