<?php

namespace App\Http\Controllers\Admin\FeesCollection; 


use App\SmClass;
use App\SmStudent;
use Carbon\Carbon;
use App\ApiBaseMethod;
use App\SmPaymentMethhod;
use App\SmFeesCarryForward;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\FeesCarryForwardLog;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Models\FeesCarryForwardSettings;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FeesCarryForwardSettingsStoreRequest;
use App\Http\Requests\Admin\FeesCollection\SmFeesForwardSearchRequest;

class SmFeesCarryForwardController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function feesForward(Request $request)
    {
        try {
            $classes = SmClass::get();
            return view('backEnd.feesCollection.fees_forward', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesForwardSearch(SmFeesForwardSearchRequest $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $classes = SmClass::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id',Auth::user()->school_id)
                        ->get();

            $students = StudentRecord::where('class_id', $request->class)
                        ->where('section_id', $request->section)
                        ->where('school_id',Auth::user()->school_id)
                        ->whereHas('student', function($q){
                            $q->where('active_status',1);
                        })
                        ->get();

            if ($students->count() != 0) {
                foreach ($students as $student) {
                    $fees_balance = SmFeesCarryForward::where('student_id', $student->student_id)->count();
                }
                $class_id = $request->class;
                if ($fees_balance == 0) {
                    return view('backEnd.feesCollection.fees_forward', compact('classes', 'students', 'class_id'));
                } else {
                    $update = "";
                    return view('backEnd.feesCollection.fees_forward', compact('classes', 'students', 'update', 'class_id'));
                }
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect('fees-forward');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesForwardStore(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->id as $student) {

                if ($request->update == 1) {

                    $fees_forward = SmFeesCarryForward::find($student);
                    $fees_forward->balance = $request->balance[$student] ?? 0;
                    $fees_forward->notes = $request->notes[$student];
                    $fees_forward->save();
                } else {
                    $fees_forward = new SmFeesCarryForward();
                    $fees_forward->student_id = $student;
                    $fees_forward->balance = $request->balance[$student] ?? 0;
                    $fees_forward->notes = $request->notes[$student];
                    $fees_forward->school_id = Auth::user()->school_id;
                    $fees_forward->academic_id = getAcademicId();
                    $fees_forward->save();
                }
            }
            DB::commit();

            
            Toastr::success('Operation successful', 'Success');
            return redirect('fees-forward');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesCarryForwardSettingsView()
    {
        try {
            $settings = FeesCarryForwardSettings::first();
            $paymeny_gateway = SmPaymentMethhod::query();
            $paymeny_gateway = $paymeny_gateway->where('school_id', auth()->user()->school_id);
            $paymeny_gateway->where('method', '!=', 'Bank');
            if(moduleStatusCheck('XenditPayment') == False){
                $paymeny_gateway->where('method','!=','Xendit');
            }
            if(moduleStatusCheck('RazorPay') == False){
                $paymeny_gateway->where('method','!=','RazorPay');
            }
            if(moduleStatusCheck('Raudhahpay') == False){
                $paymeny_gateway->where('method','!=','Raudhahpay');
            }
            if(moduleStatusCheck('KhaltiPayment') == False){
                $paymeny_gateway->where('method','!=','Khalti');
            }

            if(moduleStatusCheck('MercadoPago') == False){
                $paymeny_gateway->where('method','!=','MercadoPago');
            }
            if(moduleStatusCheck('CcAveune') == False){
                $paymeny_gateway->where('method','!=','CcAveune');
            }

            $paymeny_gateway = $paymeny_gateway->withoutGlobalScope(ActiveStatusSchoolScope::class);
            $paymeny_gateway = $paymeny_gateway->get();

            return view('backEnd.systemSettings.feesCarryForwardSettingsView',compact('settings', 'paymeny_gateway'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesCarryForwardSettings(FeesCarryForwardSettingsStoreRequest $request)
    {
        try {
            $settings = FeesCarryForwardSettings::first();
            if($request->title){
                $settings->title = $request->title;
            }
            if($request->fees_due_days){
                $settings->fees_due_days = $request->fees_due_days;
            }
            if($request->payment_gateway){
                $settings->payment_gateway = $request->payment_gateway;
            }
            $settings->save();
            
            Toastr::success('Operation successful', 'Success');
            if(generalSetting()->fees_status == 1){
                return redirect()->route('fees-carry-forward-settings-view');
            }else{
                return redirect()->route('fees-carry-forward-settings-view-fees-collection');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesCarryForward()
    {
        try {
            $classes = SmClass::get();
            return view('backEnd.systemSettings.feesCarryForwardView', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesCarryForwardSearch(SmFeesForwardSearchRequest $request)
    {
        try {
            $data['classes'] = SmClass::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id',Auth::user()->school_id)
                        ->get();

            $data['students'] = StudentRecord::with('studentDetail.forwardBalance')
                        ->where('class_id', $request->class)
                        ->where('section_id', $request->section)
                        ->where('school_id',Auth::user()->school_id)
                        ->whereHas('student', function($q){
                            $q->where('active_status',1);
                        })
                        ->get();

            $data['settings'] = FeesCarryForwardSettings::first();

            if ($data['students']->count() != 0) {
                foreach ($data['students'] as $student) {
                    $fees_balance = SmFeesCarryForward::where('student_id', $student->student_id)->count();
                }
                $data['class_id'] = $request->class;
                return view('backEnd.systemSettings.feesCarryForwardView', $data);
            }else{
                Toastr::error('No Student Found', 'Failed');
                if(generalSetting()->fees_status == 1){
                    return redirect()->route('fees-carry-forward-view');
                }else{
                    return redirect()->route('fees-carry-forward-view-fees-collection');
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesCarryForwardStore(Request $request)
    {
        try {
            foreach (gv($request, 'studentFeesInfo') as $studentInfo) {
                $type = 'add';
                if (preg_match('/[+,-]/i', gv($studentInfo, 'balance'), $match)) {
                    $data = $match[0];
                    if($data == "+"){
                        $type = 'add';
                    }else{
                        $type = 'due';
                    }
                }

                $deleteData = SmFeesCarryForward::where('student_id', gv($studentInfo, 'student_id'))->first();
                $studentBaseAmount = SmFeesCarryForward::where('student_id', gv($studentInfo, 'student_id'))->sum('balance');
                if($deleteData){
                    $transcationAmount = $studentBaseAmount - abs(gv($studentInfo, 'balance', 0));
                }else{
                    $transcationAmount = abs(gv($studentInfo, 'balance', 0));
                }
                if($deleteData){
                    $deleteData->delete();
                }
                
                $fees_forward = new SmFeesCarryForward();
                $fees_forward->student_id = gv($studentInfo, 'student_id');
                $fees_forward->balance = abs(gv($studentInfo, 'balance', 0));
                $fees_forward->balance_type = $type;
                $fees_forward->due_date = date('Y-m-d', strtotime(gv($studentInfo, 'due_date')));
                $fees_forward->notes = gv($studentInfo, 'notes');
                $fees_forward->school_id = auth()->user()->school_id;
                $fees_forward->academic_id = getAcademicId();
                $fees_forward->save();

                // Carry Forward Log Start
                $storeLog = new FeesCarryForwardLog();
                $storeLog->student_record_id = gv($studentInfo, 'student_id');
                $storeLog->amount = $transcationAmount;
                $storeLog->amount_type = $type;
                if(generalSetting()->fees_status == 1){
                    $storeLog->amount_type = 'fees';
                }else{
                    $storeLog->type = 'installment';
                }
                $storeLog->date = date("Y-m-d H:i:s");
                $storeLog->note = gv($studentInfo, 'notes');
                $storeLog->created_by = auth()->user()->id;
                $storeLog->school_id = auth()->user()->school_id;
                $storeLog->save();
                // Carry Forward Log End
            }
            Toastr::success('Operation successful', 'Success');
            if(generalSetting()->fees_status == 1){
                return redirect()->route('fees-carry-forward-view');
            }else{
                return redirect()->route('fees-carry-forward-view-fees-collection');
            }
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesCarryForwardLogView()
    {
        try {
            $classes = SmClass::get();
            return view('backEnd.systemSettings.feesCarryForwardLogView', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesCarryForwardLogSearch()
    {
        try {
            $logs = FeesCarryForwardLog::with('studentRecord', 'studentRecord.studentDetail')
            ->when(request()->student_id, function($s){
                $s->where('student_record_id', request()->student_id);
            })
            ->where('type', request()->fees_type);
            return DataTables::of($logs)
            ->addIndexColumn()
            ->editColumn('student_id', function ($log) {
                return $log->studentRecord->studentDetail->full_name ?? '';
            })
            ->addColumn('note', function ($log) {
                return $log->note ?? '';
            })
            ->addColumn('amount', function ($log) {
                return $log->amount ?? '';
            })
            ->editColumn('date', function ($log) {
                return dateConvert($log->date);
            })
            ->toJson();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
