<?php

namespace App\Http\Controllers\Admin\Communicate;

use App\User;
use Carbon\Carbon;
use App\SmNoticeBoard;
use App\SmNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NoticeRequestForm;
use Modules\RolePermission\Entities\InfixRole;
use Modules\Saas\Entities\SmAdministratorNotice;

class SmNoticeController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
	}

    public function sendMessage(Request $request)
    {
        try {
            $roles = InfixRole::when((generalSetting()->with_guardian !=1), function ($query) {
                $query->where('id', '!=', 3);
            })->where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            return view('backEnd.communicate.sendMessage', compact('roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveNoticeData(NoticeRequestForm $request)
    {
        try {
            $roles_array = array();
            if (empty($request->role)) {
                $roles_array = '';
            } else {
                $roles_array = implode(',', $request->role);
            }

            $noticeData = new SmNoticeBoard();
            if (isset($request->is_published)) {
                $noticeData->is_published = $request->is_published;
            }
            $noticeData->notice_title = $request->notice_title;
            $noticeData->notice_message = $request->notice_message;
            $noticeData->notice_date = date('Y-m-d', strtotime($request->notice_date));
            $noticeData->publish_on = date('Y-m-d', strtotime($request->publish_on));
            $noticeData->inform_to = $roles_array;
            $noticeData->created_by = Auth::user()->id;
            $noticeData->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $noticeData->un_academic_id = getAcademicId();
            }else{
                $noticeData->academic_id = getAcademicId();
            }
            $noticeData->save();
            if ($request->role != null) {
                foreach ($request->role as $key => $role) {
                    $users = User::where('role_id', $role)->where('active_status', 1)->get();
                    foreach ($users as $key => $user) {
                        $notification = new SmNotification();
                        $notification->role_id = $role;
                        $notification->message = "Notice for you";
                        $notification->date = $noticeData->notice_date;
                        $notification->user_id = $user->id;
                        $notification->url = "notice-list";
                        $notification->school_id = Auth::user()->school_id;
                        if(moduleStatusCheck('University')){
                            $notification->un_academic_id = getAcademicId();
                        }else{
                            $notification->academic_id = getAcademicId();
                        }
                        $notification->save();
                    }
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('notice-list');

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function noticeList(Request $request)
    {
        try {
            $allNotices = SmNoticeBoard::with('users')
                                        ->orderBy('id', 'DESC')
                                        ->get();
            return view('backEnd.communicate.noticeList', compact('allNotices'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function administratorNotice(Request $request)
    {
        try {
            $allNotices = SmAdministratorNotice::where('inform_to', Auth::user()->school_id)
                        ->where('active_status', 1)
                        ->get();
          
            return view('backEnd.communicate.administratorNotice', compact('allNotices'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editNotice(Request $request, $notice_id)
    {

        try {
            $roles = InfixRole::where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $noticeDataDetails = SmNoticeBoard::find($notice_id);
            return view('backEnd.communicate.editSendMessage', compact('noticeDataDetails', 'roles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateNoticeData(NoticeRequestForm $request)
    {
        try {
            $roles_array = array();
            if (empty($request->role)) {
                $roles_array = '';
            } else {
                $roles_array = implode(',', $request->role);
            }
            $noticeData = SmNoticeBoard::find($request->notice_id);

            if (isset($request->is_published)) {
                $noticeData->is_published = $request->is_published;
            }
            $noticeData->notice_title = $request->notice_title;
            $noticeData->notice_message = $request->notice_message;

            $noticeData->notice_date = date('Y-m-d', strtotime($request->notice_date));
            $noticeData->publish_on = date('Y-m-d', strtotime($request->publish_on));
            $noticeData->notice_date = Carbon::createFromFormat('m/d/Y', $request->notice_date)->format('Y-m-d');
            $noticeData->publish_on = Carbon::createFromFormat('m/d/Y', $request->publish_on)->format('Y-m-d');
            $noticeData->inform_to = $roles_array;
            $noticeData->updated_by = auth()->user()->id;
            if ($request->is_published) {
               $noticeData->is_published = 1;
            } else {
               $noticeData->is_published = 0;
            }
            $noticeData->update();

            if ($request->role != null) {

                foreach ($request->role as $key => $role) {
                    $users = User::where('role_id', $role)->get();
                    foreach ($users as $key => $user) {
                        $notification = new SmNotification();
                        $notification->role_id = $role;
                        $notification->message = $request->notice_title;
                        $notification->date = $noticeData->notice_date;
                        $notification->user_id = $user->id;
                        $notification->url = "notice-list";
                        $notification->school_id = Auth::user()->school_id;
                        if(moduleStatusCheck('University')){
                            $notification->un_academic_id = getAcademicId();
                        }else{
                            $notification->academic_id = getAcademicId();
                        }
                        $notification->save();
                    }
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect('notice-list');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteNoticeView(Request $request, $id)
    {
        try {
            return view('backEnd.communicate.deleteNoticeView', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteNotice(Request $request, $id)
    {
        try {
            SmNoticeBoard::destroy($id);
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
