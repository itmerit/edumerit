<?php

namespace App\Http\Controllers;

use App\SmUserLog;
use App\YearCheck;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(){
        try{
            return view('backEnd.systemSettings.user.user');
        }catch (\Exception $e) {
              Toastr::error('Operation Failed', 'Failed');
               return redirect()->back();
        }

    }
    public function create(){
        try{
			return view('backEnd.systemSettings.user.user_create');
		}catch (\Exception $e) {
		      Toastr::error('Operation Failed', 'Failed');
		       return redirect()->back();
		}
    }
    public function userLog(Request $request){
        try{
            $user_logs = SmUserLog::where('academic_id', getAcademicId())
            ->where('school_id',Auth::user()->school_id)
            ->orderBy('id', 'desc')
            ->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($user_logs, null);
            }
        return view('backEnd.reports.user_log', compact('user_logs'));
		}catch (\Exception $e) {
		      Toastr::error('Operation Failed', 'Failed');
		       return redirect()->back();
		}
    }
}
