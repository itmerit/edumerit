<?php
namespace App\Http\Controllers\Admin\FeesCollection;
use App\SmClass;
use App\SmStudent;
use App\tableList;
use App\SmBaseSetup;
use App\SmFeesAssign;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use App\SmFeesDiscount;
use App\SmStudentGroup;
use App\SmStudentCategory;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmFeesAssignDiscount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\FeesCollection\SmFeesDiscountRequest;
use App\Models\DirectFeesInstallmentAssign;
use App\Traits\DirectFeesAssignTrait;
use Modules\University\Entities\UnFeesInstallmentAssign;

class SmFeesDiscountController extends Controller
{
    use DirectFeesAssignTrait;
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index(Request $request)
    {
        try{
            $fees_discounts = SmFeesDiscount::where('active_status',1)->get();

            return view('backEnd.feesCollection.fees_discount', compact('fees_discounts'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(SmFeesDiscountRequest $request)
    {
        try{
            $fees_discount = new SmFeesDiscount();
            $fees_discount->name = $request->name;
            $fees_discount->code = $request->code;
            $fees_discount->type = $request->type;
            $fees_discount->amount = $request->amount;
            $fees_discount->description = $request->description;
            $fees_discount->school_id = Auth::user()->school_id;
            if(moduleStatusCheck('University')){
                $fees_discount->un_academic_id = getAcademicId();
            }else{
                $fees_discount->academic_id = getAcademicId();
            }
           
            $result = $fees_discount->save(); 
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {

        try{
            // $fees_discount = SmFeesDiscount::find($id);
            $fees_discount = SmFeesDiscount::find($id);
            $fees_discounts = SmFeesDiscount::get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['fees_discount'] = $fees_discount->toArray();
                $data['fees_discounts'] = $fees_discounts->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.feesCollection.fees_discount', compact('fees_discounts', 'fees_discount'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(SmFeesDiscountRequest $request)
    {
        try{

            $fees_discount = SmFeesDiscount::find($request->id);

            $fees_discount->name = $request->name;
            $fees_discount->code = $request->code;
            $fees_discount->type = $request->type;
            $fees_discount->amount = $request->amount;
            $fees_discount->description = $request->description;
            $fees_discount->academic_id = getAcademicId();
            $result = $fees_discount->save();

            if ($fees_discount->type=='once') {
                $fees_assigns=SmFeesAssign::where('fees_discount_id',$request->id)->where('school_id',Auth::user()->school_id)->get();
                foreach($fees_assigns as $key => $fees_assign){
                    $fees_assign_total=$fees_assign->fees_amount+$fees_assign->applied_discount;
                    if ($fees_assign->feesGroupMaster->amount==$fees_assign_total) {
                        if ($fees_assign->feesGroupMaster->amount>=$fees_discount->amount) {
                            $discount=$fees_discount->amount;
                            $payable_fees=$fees_assign->feesGroupMaster->amount-$fees_discount->amount;
                        }else{
                            $discount=$fees_assign->fees_amount;
                            $payable_fees=0.00;
                        }
                        $student_fees_assign=SmFeesAssign::find($fees_assign->id);
                        $student_fees_assign->fees_amount=$payable_fees;
                        $student_fees_assign->applied_discount=$discount;
                        $student_fees_assign->save();
                    }
                }
            } else {
                $fees_assigns=SmFeesAssign::where('fees_discount_id',$request->id)->where('school_id',Auth::user()->school_id)->get();
                foreach($fees_assigns as $key => $fees_assign){
                    $fees_assign_total=$fees_assign->fees_amount+$fees_assign->applied_discount;
                    if ($fees_assign->feesGroupMaster->amount==$fees_assign_total) {
                        if ($fees_assign->feesGroupMaster->amount>=$fees_discount->amount) {
                            $discount=$fees_discount->amount;
                            $payable_fees=$fees_assign->feesGroupMaster->amount-$fees_discount->amount;
                        }else{
                            $discount=$fees_assign->fees_amount;
                            $payable_fees=0.00;
                        }
                        $student_fees_assign=SmFeesAssign::find($fees_assign->id);
                        $student_fees_assign->fees_amount=$payable_fees;
                        $student_fees_assign->applied_discount=$discount;
                        $student_fees_assign->save();
                    }
                }
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Fees discount has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } 
            Toastr::success('Operation successful', 'Success');
            return redirect('fees-discount');
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {
        try{
            $id_key = 'fees_discount_id';
            $tables = tableList::getTableList($id_key,$id);
            if (empty($tables)) {
          

                // $delete_query = SmFeesDiscount::destroy($request->id);
                $delete_query = SmFeesDiscount::destroy($request->id);
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($delete_query) {
                        return ApiBaseMethod::sendResponse(null, 'Fees Discount has been deleted successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } 
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                $msg = 'This data already used in : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesDiscountAssign(Request $request, $id)
    {

        try{
            $fees_discount_id = $id;
            $classes = SmClass::get();
            $groups = SmStudentGroup::get();
            $categories = SmStudentCategory::get();
            if(moduleStatusCheck('University')){
                return view('university::un_fees_discount_assign', compact('classes', 'categories', 'groups', 'fees_discount_id'));
            }else{
                return view('backEnd.feesCollection.fees_discount_assign', compact('classes', 'categories', 'groups', 'fees_discount_id'));
            }
           
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function feesDiscountAssignSearch(Request $request)
    {
        try {
            $genders = SmBaseSetup::where('base_group_id', '=', '1')->get();
            $classes = SmClass::get();
            $groups = SmStudentGroup::get();
            $categories = SmStudentCategory::where('school_id', Auth::user()->school_id)->get();
            $fees_discount_id = $request->fees_discount_id;
            $students = StudentRecord::query();

            if(moduleStatusCheck('University')){
                $students = universityFilter($students, $request);
                $students = $students->with('studentDetail.parents','studentDetail.category', 'studentDetail.gender')
                ->whereHas('studentDetail', function ($q)  {
                    $q->where('active_status', 1);
                })->get();
            }
            else {
                if ($request->class != "") {
                    $students->where('class_id', $request->class);
                }
                if ($request->section != "") {
                    $students->where('section_id', $request->section);
                }
                $students = $students->with('studentDetail.parents', 'class', 'section', 'studentDetail.category', 'studentDetail.gender')->where('school_id', Auth::user()->school_id)
                                        ->whereHas('studentDetail', function ($q)  {
                                            $q->where('active_status', 1);
                                        })->get();
            }
          
            $fees_discount = SmFeesDiscount::find($request->fees_discount_id);

            $pre_assigned = [];
            $already_paid = [];
            foreach ($students as $student) {
                $assigned_student = SmFeesAssignDiscount::select('student_id')
                                    ->where('student_id', $student->student_id)
                                    ->where('record_id',$student->id)
                                    ->where('fees_discount_id', $request->fees_discount_id)
                                    ->where('school_id',Auth::user()->school_id)
                                    ->first();

                if ($assigned_student != "") {
                    if (!in_array($assigned_student->student_id, $pre_assigned)) {
                        $pre_assigned[] = $assigned_student->student_id;
                    }
                }

                $already_paid_student = SmFeesPayment::where('active_status', 1)
                                        ->where('record_id',$student->id)
                                        ->where('student_id', $student->student_id)
                                        ->where('fees_discount_id', $request->fees_discount_id)
                                        ->first();
                if ($already_paid_student != "") {
                    if (!in_array($already_paid_student->student_id, $already_paid)) {
                        $already_paid[] = $already_paid_student->student_id;
                    }
                }
            }

            $class_id = $request->class;
            $category_id = $request->category;
            $group_id = $request->group;
            $gender_id = $request->gender;

            // $fees_assign_groups = SmFeesMaster::where('fees_group_id', $request->fees_group_id)->whereBetween('created_at', [YearCheck::AcStartDate(), YearCheck::AcEndDate()])->where('school_id',Auth::user()->school_id)->get();
            $assigned_fees_types=[];
            $assigned_fees_groups=[];
            foreach ($students as $key => $student) {
                $assigned_fees_type=SmFeesAssign::where('student_id',$student->student_id)
                        ->where('record_id',$student->id)
                        ->join('sm_fees_masters','sm_fees_masters.id','=','sm_fees_assigns.fees_master_id')
                        ->join('sm_fees_types','sm_fees_types.id','=','sm_fees_masters.fees_type_id')
                        ->where('sm_fees_assigns.applied_discount','=',null)
                        ->select('sm_fees_masters.id','sm_fees_types.id as fees_type_id','name','amount','sm_fees_assigns.student_id','applied_discount','sm_fees_masters.fees_group_id')
                        ->where('sm_fees_assigns.school_id',Auth::user()->school_id)
                        ->get();
                $assigned_fees_types[$student->id]= $assigned_fees_type;

                $assigned_fees_group=DB::table('sm_fees_assigns')
                            ->where('student_id',$student->student_id)
                            ->where('record_id',$student->id)
                            ->join('sm_fees_masters','sm_fees_masters.id','=','sm_fees_assigns.fees_master_id')
                            ->join('sm_fees_groups','sm_fees_groups.id','=','sm_fees_masters.fees_group_id')
                            ->where('sm_fees_assigns.applied_discount','=',null)
                            ->distinct ('fees_group_id')
                            ->select('sm_fees_masters.id','sm_fees_groups.id as group_id','name','amount','sm_fees_assigns.student_id')
                            ->get();
                $assigned_fees_groups[$student->id]= $assigned_fees_group;
            }
            if(moduleStatusCheck('University')){
                $already_assigned = UnFeesInstallmentAssign::where('fees_discount_id', $fees_discount_id)->pluck('record_id')->toArray();
                return view('university::un_fees_discount_assign', compact('assigned_fees_types','assigned_fees_groups','classes','groups', 'categories', 'students', 'fees_discount', 'genders','fees_discount_id', 'already_assigned', 'already_paid' ,'class_id', 'category_id', 'gender_id'));
            }

            if(directFees()){
                $already_assigned = DirectFeesInstallmentAssign::where('fees_discount_id', $fees_discount_id)->pluck('record_id')->toArray();
                return view('backEnd.feesCollection.directFees.assign_fees_discount', compact('assigned_fees_types','assigned_fees_groups','classes','groups', 'categories', 'students', 'fees_discount', 'genders','fees_discount_id', 'already_assigned', 'already_paid' ,'class_id', 'category_id', 'gender_id'));
            }

            else{
                return view('backEnd.feesCollection.fees_discount_assign', compact('assigned_fees_types','assigned_fees_groups','classes','groups', 'categories', 'students', 'fees_discount', 'genders','fees_discount_id', 'pre_assigned', 'already_paid' ,'class_id', 'category_id', 'gender_id'));
            }
    
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function feesDiscountAssignStore(Request $request)
    {
        $datas= collect($request->data);
        try{
            $discount_id=intval($request->fees_discount_id);
            $discount_info=SmFeesDiscount::find($discount_id);

            foreach ($datas as $data) {
                $studentId= gv($data,'student_id');
                $recordId= gv($data,'record_id');
                $feesMasterId= gv($data,'fees_master_id');

                $assign_discount = SmFeesAssignDiscount::where('fees_discount_id', $discount_id)
                                    ->where('student_id', $studentId)
                                    ->where('record_id', $recordId)
                                    ->delete();

                $fees_assigns = SmFeesAssign::where('fees_discount_id', $discount_id)
                                ->where('student_id', $studentId)
                                ->where('record_id', $recordId)
                                ->where('school_id',Auth::user()->school_id)
                                ->get();
                            
                foreach ($fees_assigns as $key => $fees_assign) {
                    $set_fees_amount=$fees_assign->fees_amount+$fees_assign->applied_discount;
                    $fees_assign->fees_amount=$set_fees_amount;
                    $fees_assign->applied_discount=null;
                    $fees_assign->fees_discount_id=null;
                    $fees_assign->save();
                }

                if (!gbv($data, 'checked') || !gv($data, 'fees_master_id')){
                    continue;
                }
                    $assign_discount = new SmFeesAssignDiscount();
                    $assign_discount->student_id = $studentId;
                    $assign_discount->fees_discount_id = $discount_id;
                    $assign_discount->applied_amount = $discount_info->amount;
                    $assign_discount->record_id = $recordId;
                    if ($discount_info->type=='once') {
                        $assign_discount->fees_type_id = $feesMasterId;
                    } else {
                        $assign_discount->fees_group_id = $feesMasterId;
                    }
                    $assign_discount->school_id = Auth::user()->school_id;
                    $assign_discount->academic_id = getAcademicId();
                    $assign_discount->save();

                    if ($discount_info->type=='once') {
                        $fees_assign = SmFeesAssign::where('fees_master_id',$feesMasterId)
                                        ->where('student_id',$studentId)
                                        ->where('record_id',$recordId)
                                        ->where('applied_discount','=',null)
                                        ->where('school_id',Auth::user()->school_id)
                                        ->first();
                        if ($fees_assign) {
                            if ($fees_assign->fees_amount >= $discount_info->amount) {
                                $discount=$discount_info->amount;
                                $payable_fees=$fees_assign->fees_amount - $discount;
                                $assign_discount->applied_amount = $discount_info->amount;
                                $assign_discount->unapplied_amount = 0.00;
                                $assign_discount->save();
                            }else{
                                $discount=$fees_assign->fees_amount;
                                $payable_fees=0.00;
                                $assign_discount->applied_amount = $fees_assign->fees_amount;
                                $assign_discount->unapplied_amount =$discount_info->amount-$fees_assign->fees_amount;
                                $assign_discount->save();
                            }
                            $fees_assign->applied_discount+=$discount;
                            $fees_assign->fees_discount_id = $discount_id;
                            $fees_assign->fees_amount = $payable_fees;
                            $fees_assign->save();
                        }
                    }else{
                        $get_masters=DB::table('sm_fees_masters')->where('fees_group_id',$feesMasterId)->get();
                        foreach ($get_masters as $key => $master) {
                            $fees_assign = SmFeesAssign::where('fees_master_id',$master->id)
                                            ->where('student_id',$studentId)
                                            ->where('record_id',$recordId)
                                            ->where('applied_discount','=',null)
                                            ->where('school_id',Auth::user()->school_id)
                                            ->first();
                            if ($fees_assign) {
                                if ($fees_assign->fees_amount>=$discount_info->amount) {
                                    $discount=$discount_info->amount;
                                    $payable_fees=$fees_assign->fees_amount-$discount_info->amount;
                                    $assign_discount->applied_amount = $discount_info->amount;
                                    $assign_discount->unapplied_amount = 0.00;
                                    $assign_discount->save();
                                }else{
                                    $discount=$fees_assign->fees_amount;
                                    $payable_fees=0.00;
                                    $assign_discount->applied_amount = $fees_assign->fees_amount;
                                    $assign_discount->unapplied_amount =$discount_info->amount-$fees_assign->fees_amount;
                                    $assign_discount->save();
                                }
                                $fees_assign->applied_discount+=$discount;
                                $fees_assign->fees_discount_id = $discount_id;
                                $fees_assign->fees_amount=$payable_fees;
                                $fees_assign->save();
                            }
                        }
                    }
            
            }
            Toastr::success('Operation Sucessful', 'Success');
            return redirect()->route('fees_discount_assign',$discount_id);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }


    public function feesDiscountAmountSearch(Request $request)
    {
        $discount_unapplied_amount=DB::table('sm_fees_assign_discounts')->where('fees_discount_id',$request->fees_discount_id)->where('student_id',$request->student_id)->first();
        if (intval($request->fees_amount) > $discount_unapplied_amount->unapplied_amount ) {
            $html = $discount_unapplied_amount->unapplied_amount;
        } else {
            $html=$request->fees_amount;
        }
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($html, null);
        }
        return response()->json([$html]);


    }



    public function directFeesDiscountAssignStore(Request $request)
    {
        $datas= collect($request->data);
        $fees_discount_id = $request->fees_discount_id;
        try{
            foreach ($datas as $data) {
                $studentId= gv($data,'student_id');
                $recordId= gv($data,'record_id');
                if(gbv($data, 'checked')){
                    $this->assignFeesDiscount($fees_discount_id,$recordId);
                }
            }
            Toastr::success('Operation Successfull', 'Success');
            return redirect()->route('fees_discount_assign',$fees_discount_id);
        }
        catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

   
    
}