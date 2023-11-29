<?php

namespace Modules\Fees\Http\Controllers\api;

use App\SmSection;
use App\SmAddIncome;
use App\SmBankAccount;
use App\SmClassSection;
use App\SmAssignSubject;
use App\SmPaymentMethhod;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesInvoice;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\Fees\Entities\FmFeesTransaction;

class AjaxController extends Controller
{
    public function feesViewPayment(Request $request)
    {
        $feesinvoice = FmFeesInvoice::find($request->invoiceId);
        $feesTranscations = FmFeesTransaction::where('fees_invoice_id', $request->invoiceId)
                        ->where('paid_status', 'approve')
                        ->where('school_id', auth()->user()->school_id)
                        ->get()->map(function ($value){
                            return [
                                'date'=>dateConvert($value->created_at),
                                'payment_method'=>$value->payment_method,
                                'change_method'=>$value->payment_method,
                                'paid_amount'=>$value->paid_amount,
                                'waiver'=>$value->weaver,
                                'fine'=>$value->fine,
                            ];
                        });
        $paymentMethods = SmPaymentMethhod::whereIn('method', ['Cash','Cheque','Bank'])->get();
        $banks = SmBankAccount::where('school_id', auth()->user()->school_id)->get();
        return response()->json(compact('feesinvoice', 'feesTranscations','paymentMethods','banks'));
    }

    public function ajaxSelectStudent(Request $request)
    {
        try {
            $allStudents = StudentRecord::with('studentDetail','section')
                        ->where('class_id', $request->classId)
                        ->where('school_id', auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();
            return response()->json([$allStudents]);
        } catch (\Exception $e) {
            return response()->json(['Message'=>'Error']);
        }
    }

    public function ajaxSelectFeesType(Request $request)
    {
        try{
            $type = substr($request->type,0,3);
            if($type == "grp"){
                $groupId = substr($request->type,3);
                $feesGroups = FmFeesType::where('fees_group_id',$groupId)
                            ->where('type','fees')
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->get();
                return  response()->json(compact('feesGroups'));
            }else{
                $typeId = substr($request->type,3);
                $feesType = FmFeesType::where('id',$typeId)
                            ->where('type','fees')
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->first();
            return  response()->json(compact('feesType'));
            }   
        }catch(\Exception $e) {
            return response()->json(['Message'=>'Error']);
        }
    }

    public function ajaxGetAllSection(Request $request)
    {
        try{
            if (teacherAccess()) {
                $sectionIds = SmAssignSubject::where('class_id', '=', $request->class_id)
                            ->where('teacher_id', auth()->user()->staff->id)
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->distinct(['class_id','section_id'])
                            ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                            ->get();
            } else {
                $sectionIds = SmClassSection::where('class_id', '=', $request->class_id)
                                ->where('school_id', auth()->user()->school_id)
                                ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                                ->get();
            }
            $promote_sections = [];
            foreach ($sectionIds as $sectionId) {
                $promote_sections[] = SmSection::where('id', $sectionId->section_id)
                                    ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                                    ->first(['id','section_name']);
            }
            return response()->json([$promote_sections]);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function ajaxSectionAllStudent(Request $request)
    {
        try{
            $allStudents = StudentRecord::with('studentDetail','section')
                            ->where('class_id', $request->class_id)
                            ->where('section_id', $request->section_id)
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->get();
            return response()->json([$allStudents]);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function ajaxGetAllStudent(Request $request)
    {
        try{
            $allStudents = StudentRecord::with('studentDetail','section')
                            ->where('class_id', $request->class_id)
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->get();
            return response()->json([$allStudents]);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function changeMethod(Request $request)
    {
        try{
            $transcation = FmFeesTransaction::find($request->feesInvoiceId);
            $transcation->payment_method= $request->methodName;
            $transcation->update();
            
            $income = SmAddIncome::where('fees_collection_id', $request->feesInvoiceId)
                    ->first();

            $updateIncome = SmAddIncome::find($income->id);
            $updateIncome->fees_collection_id = $transcation->id;
            $updateIncome->update();

            return response()->json(['success']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }
}
