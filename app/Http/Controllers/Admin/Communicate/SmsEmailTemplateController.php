<?php

namespace App\Http\Controllers\Admin\Communicate;

use App\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SmsEmailTemplateController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}
    public function SmsTemplate()
    {
        try {
            $template = SmsTemplate::first();
            return view('backEnd.communicate.sms_template', compact('template'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function SmsTemplateStore(Request $request, $id)
    {
        try {
            $data = SmsTemplate::first();
            $data->admission_pro = $request->admission_pro;
            $data->student_admit = $request->student_admit;
            $data->login_disable = $request->login_disable;
            $data->exam_schedule = $request->exam_schedule;
            $data->exam_publish = $request->exam_publish;
            $data->due_fees = $request->due_fees;
            $data->collect_fees = $request->collect_fees;
            $data->stu_promote = $request->stu_promote;
            $data->attendance_sms = $request->attendance_sms;
            $data->absent = $request->absent;
            $data->late_sms = $request->late_sms;
            $data->er_checkout = $request->er_checkout;
            $data->st_checkout = $request->st_checkout;
            $data->st_credentials = $request->st_credentials;
            $data->staff_credentials = $request->staff_credentials;
            $data->holiday = $request->holiday;
            $data->leave_app = $request->leave_app;
            $data->approve_sms = $request->approve_sms;
            $data->birth_st = $request->birth_st;
            $data->birth_staff = $request->birth_staff;
            $data->cheque_bounce = $request->cheque_bounce;
            $data->l_issue_b = $request->l_issue_b;
            $data->re_issue_book = $request->re_issue_book;
            $data->save();
            Toastr::success('Operation success', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function SmsTemplateNew()
    {
        try {
            $template = SmsTemplate::first();          
            return view('backEnd.communicate.sms_template_new', compact('template'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function SmsTemplateNewStore(Request $request)
    {
        try {
            $template = SmsTemplate::find(1);
            $template->student_approve_message_sms = $request->student_approve_message_sms;
            $template->student_approve_message_sms_status = $request->student_approve_message_sms_status;

            $template->student_registration_message_sms = $request->student_registration_message_sms;
            $template->student_registration_message_sms_status = $request->student_registration_message_sms_status;

            $template->student_admission_message_sms = $request->student_admission_message_sms;
            $template->student_admission_message_sms_status = $request->student_admission_message_sms_status;

            $template->exam_schedule_message_sms = $request->exam_schedule_message_sms;
            $template->exam_schedule_message_sms_status = $request->exam_schedule_message_sms_status;

            $template->dues_fees_message_sms = $request->dues_fees_message_sms;
            $template->dues_fees_message_sms_status = $request->dues_fees_message_sms_status;

            $template->student_absent_notification_sms = $request->student_absent_notification_sms;
            $template->student_absent_notification_sms_status = $request->student_absent_notification_sms_status;

            $template->save();

            Toastr::success('Operation success', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
