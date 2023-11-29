<?php

namespace Modules\Fees\Http\Controllers\api;

use App\Models\StudentRecord;
use App\SmClass;
use App\SmSchool;
use App\SmStudent;
use App\Models\User;
use App\SmAddIncome;
use App\SmBankAccount;
use App\SmBankStatement;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\SmPaymentGatewaySetting;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesGroup;
use Modules\Fees\Entities\FmFeesWeaver;
use Modules\Fees\Entities\FmFeesInvoice;
use Modules\Fees\Entities\FmFeesTransaction;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Modules\Wallet\Entities\WalletTransaction;
use Modules\Fees\Http\Requests\BankFeesPayment;
use Modules\Fees\Entities\FmFeesInvoiceSettings;
use Modules\Fees\Entities\FmFeesTransactionChield;
use Modules\Fees\Http\Requests\FeesGroupRequest;
use Modules\Fees\Http\Requests\FeesPaymentRequest;
use Modules\Fees\Http\Requests\FeesTypeRequest;
use Modules\Fees\Http\Requests\InvoiceStoreRequest;

class FeesController extends Controller
{
    public function feesGroup()
    {
        $feesGroups = FmFeesGroup::where('school_id', auth()->user()->school_id)
            ->where('academic_id', getAcademicId())
            ->get();
        return response()->json(compact('feesGroups'));
    }

    public function feesGroupStore(FeesGroupRequest $request)
    {
        try {
            $feesGroup = new FmFeesGroup();
            $feesGroup->name = $request->name;
            $feesGroup->description = $request->description;
            $feesGroup->school_id = auth()->user()->school_id;
            $feesGroup->academic_id = getAcademicId();
            $feesGroup->save();
            return response()->json(['message'=>'Save Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesGroupEdit($id)
    {
        try {
            if (checkAdmin()) {
                $feesGroup = FmFeesGroup::find($id);
            } else {
                $feesGroup = FmFeesGroup::where('id', $id)
                            ->where('school_id', auth()->user()->school_id)
                            ->first();
            }
            $feesGroups = FmFeesGroup::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();
            return response()->json(compact('feesGroup', 'feesGroups'));
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesGroupUpdate(FeesGroupRequest $request)
    {
        try {
            if (checkAdmin()) {
                $feesGroup = FmFeesGroup::find($request->id);
            } else {
                $feesGroup = FmFeesGroup::where('id', $request->id)
                    ->where('school_id', auth()->user()->school_id)
                    ->first();
            }
            $feesGroup->name = $request->name;
            $feesGroup->description = $request->description;
            $feesGroup->academic_id = getAcademicId();
            $feesGroup->save();

            return response()->json(['message'=>'Update Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesGroupDelete(Request $request)
    {
        try {
            if (checkAdmin()) {
                FmFeesGroup::destroy($request->id);
            } else {
                FmFeesGroup::where('id', $request->id)
                    ->where('school_id', auth()->user()->school_id)
                    ->delete();
            }
            return response()->json(['message'=>'Delete Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesType()
    {
        $feesGroups = FmFeesGroup::where('school_id', auth()->user()->school_id)
            ->where('academic_id', getAcademicId())
            ->get();

        $feesTypes = FmFeesType::where('type','fees')
                    ->where('school_id',auth()->user()->school_id)
                    ->where('academic_id', getAcademicId())
                    ->get();
        return response()->json(compact('feesGroups','feesTypes'));
    }

    public function feesTypeStore(FeesTypeRequest $request)
    {
        try {
            $feesType = new FmFeesType();
            $feesType->name = $request->name;
            $feesType->fees_group_id = $request->fees_group;
            $feesType->description = $request->description;
            $feesType->school_id = auth()->user()->school_id;
            $feesType->academic_id = getAcademicId();
            $feesType->save();
            return response()->json(['message'=>'Save Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesTypeEdit($id)
    {
        try {
            if (checkAdmin()) {
                $feesType = FmFeesType::find($id);
            } else {
                $feesType = FmFeesType::where('id', $id)
                    ->where('school_id', auth()->user()->school_id)
                    ->first();
            }
            $feesGroups = FmFeesGroup::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesTypes = FmFeesType::where('type','fees')
                        ->where('school_id',auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();
            return response()->json(compact('feesGroups', 'feesTypes', 'feesType'));
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesTypeUpdate(FeesTypeRequest $request)
    {
        try {
            if (checkAdmin()) {
                $feesType = FmFeesType::find($request->id);
            }else{
                $feesType = FmFeesType::where('type','fees')
                            ->where('id',$request->id)
                            ->where('school_id',auth()->user()->school_id)
                            ->first();
            }
            $feesType->name = $request->name;
            $feesType->fees_group_id = $request->fees_group;
            $feesType->description = $request->description;
            $feesType->save();

            return response()->json(['message'=>'Save Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesTypeDelete(Request $request)
    {
        try {
            $feesGroupId = FmFeesType::find($request->id);
            $checkExistsData = FmFeesGroup::where('id', $feesGroupId->fees_group_id)->first();

            if (!$checkExistsData) {
                if (checkAdmin()) {
                    FmFeesType::find($request->id)->delete();
                }else{
                    FmFeesType::find($request->id)->delete();
                }
                return response()->json(['message'=>'Delete Sucessfully']);
            } else {
                return response()->json(['message'=>'This Data Already Used In Fees Group Please Remove Those Data First']);
            }
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesInvoiceList()
    {
        $studentInvoices = FmFeesInvoice::where('type','fees')
                            ->where('school_id',auth()->user()->school_id)
                            ->where('academic_id',getAcademicId())
                            ->get()->map(function ($value) {
                                $amount = $value->Tamount;
                                $weaver = $value->Tweaver;
                                $fine = $value->Tfine;
                                $paid_amount = $value->Tpaidamount;
                                $sub_total = $value->Tsubtotal;
                                $balance = ($amount + $fine) - ($paid_amount + $weaver);
                                return [
                                    'id' => $value->id,
                                    'amount' => $amount,
                                    'weaver' => $weaver,
                                    'fine' => $fine,
                                    'paid_amount' => $paid_amount,
                                    'sub_total' => $sub_total,
                                    'balance' => $balance,
                                    'student' => $value->studentInfo->full_name ? $value->studentInfo->full_name : '',
                                    'class' => $value->recordDetail->class->class_name ? $value->recordDetail->class->class_name : '',
                                    'section' => $value->recordDetail->section->section_name ? $value->recordDetail->section->section_name : '',
                                    'status' => $balance == 0 ? 'paid' : ($value->Tpaidamount > 0 ? 'partial': 'unpaid'),
                                    'date' => dateConvert($value->create_date),
                                ];
                            });
        return response()->json(compact('studentInvoices'));
    }

    public function feesInvoice()
    {
        try {
            $classes = SmClass::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesGroups = FmFeesGroup::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesTypes = FmFeesType::where('type','fees')
                        ->where('school_id',auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();

            $paymentMethods = SmPaymentMethhod::whereIn('method', ["Cash", "Cheque", "Bank"])
                ->where('school_id', auth()->user()->school_id)
                ->get();

            $bankAccounts = SmBankAccount::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $invoiceSettings = FmFeesInvoiceSettings::where('school_id', auth()->user()->school_id)->first();

            return response()->json(compact('classes', 'feesGroups', 'feesTypes', 'paymentMethods', 'bankAccounts', 'invoiceSettings'));
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }


    public function feesInvoiceStore(InvoiceStoreRequest $request)
    {
        try {
            if ($request->student != "all_student") {
                $student = StudentRecord::find($request->student);
                if ($request->groups) {
                    if(empty($request->singleInvoice)){
                        $feesType = [];
                        $amount = [];
                        $weaver = [];
                        $sub_total = [];
                        $note = [];
                    }
                    foreach ($request->groups as $group) {
                        if($request->singleInvoice == 1){
                            $feesType = [];
                            $amount = [];
                            $weaver = [];
                            $sub_total = [];
                            $note = [];
                        }

                        $feesType[] = gv($group, 'feesType');
                        $amount[] = gv($group, 'amount');
                        $weaver[] = gv($group, 'weaver');
                        $sub_total[] = gv($group, 'sub_total');
                        $note[] = gv($group, 'note');

                        if($request->singleInvoice == 1){
                            $this->invStore($request->merge(['student' => $student->student_id,
                                'record_id' => $student->id,
                                'feesType' => $feesType,
                                'amount' => $amount,
                                'weaver' => $weaver,
                                'sub_total' => $sub_total,
                                'note' => $note,
                            ]));
                        }
                    }
                    if(empty($request->singleInvoice)){
                        $this->invStore($request->merge(['student' => $student->student_id,
                            'record_id' => $student->id,
                            'feesType' => $feesType,
                            'amount' => $amount,
                            'weaver' => $weaver,
                            'sub_total' => $sub_total,
                            'note' => $note,
                        ]));
                    }
                }

                if ($request->types) {
                    foreach ($request->types as $type) {
                        $tfeesType = [];
                        $tamount = [];
                        $tweaver = [];
                        $tsub_total = [];
                        $tnote = [];

                        $tfeesType[] = gv($type, 'feesType');
                        $tamount[] = gv($type, 'amount');
                        $tweaver[] = gv($type, 'weaver');
                        $tsub_total[] = gv($type, 'sub_total');
                        $tnote[] = gv($type, 'note');

                        $this->invStore($request->merge(['student' => $student->student_id,
                            'record_id' => $student->id,
                            'feesType' => $tfeesType,
                            'amount' => $tamount,
                            'weaver' => $tweaver,
                            'sub_total' => $tsub_total,
                            'note' => $tnote,
                        ]));
                    }
                }
                //Notification
                $students = SmStudent::with('parents')->find($student->student_id);
                sendNotification("Fees Assign", null, $students->user_id, 2);
                sendNotification("Fees Assign", null, $students->parents->user_id, 3);
            } else {
                $allStudents = StudentRecord::with('studentDetail', 'studentDetail.parents')
                    ->where('class_id', $request->class)
                    ->where('school_id', Auth::user()->school_id)
                    ->where('academic_id', getAcademicId())
                    ->get();

                foreach ($allStudents as $key => $student) {
                    if ($request->groups) {
                        if(empty($request->singleInvoice)){
                            $feesType = [];
                            $amount = [];
                            $weaver = [];
                            $sub_total = [];
                            $note = [];
                        }
                            foreach ($request->groups as $group) {
                                if($request->singleInvoice == 1){
                                    $feesType = [];
                                    $amount = [];
                                    $weaver = [];
                                    $sub_total = [];
                                    $note = [];
                                }

                                $feesType[] = gv($group, 'feesType');
                                $amount[] = gv($group, 'amount',0);
                                $weaver[] = gv($group, 'weaver',0);
                                $sub_total[] = gv($group, 'sub_total',0);
                                $note[] = gv($group, 'note');

                                if($request->singleInvoice == 1){
                                    $this->invStore($request->merge(['student' => $student->student_id,
                                        'record_id' => $student->id,
                                        'feesType' => $feesType,
                                        'amount' => $amount,
                                        'weaver' => $weaver,
                                        'sub_total' => $sub_total,
                                        'note' => $note,
                                    ]));
                                }

                            }
                        if(empty($request->singleInvoice)){
                            $this->invStore($request->merge(['student' => $student->student_id,
                                'record_id' => $student->id,
                                'feesType' => $feesType,
                                'amount' => $amount,
                                'weaver' => $weaver,
                                'sub_total' => $sub_total,
                                'note' => $note,
                            ]));
                        }
                    }
                    
                    if ($request->types) {
                        foreach ($request->types as $type) {
                            $tfeesType = [];
                            $tamount = [];
                            $tweaver = [];
                            $tsub_total = [];
                            $tnote = [];
    
                            $tfeesType[] = gv($type, 'feesType');
                            $tamount[] = gv($type, 'amount');
                            $tweaver[] = gv($type, 'weaver');
                            $tsub_total[] = gv($type, 'sub_total');
                            $tnote[] = gv($type, 'note');
    
                            $this->invStore($request->merge(['student' => $student->student_id,
                                'record_id' => $student->id,
                                'feesType' => $tfeesType,
                                'amount' => $tamount,
                                'weaver' => $tweaver,
                                'sub_total' => $tsub_total,
                                'note' => $tnote,
                            ]));
                        }
                    }
                    //Notification
                    sendNotification("Fees Assign", null, $student->studentDetail->user_id, 2);
                    sendNotification("Fees Assign", null, $student->studentDetail->parents->user_id, 3);
                }
            }
            return response()->json(['message'=>'Save Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['Error'=>'Error']);
        }
    }

    public function feesInvoiceEdit($id)
    {
        try {
            // View Start
            $classes = SmClass::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesGroups = FmFeesGroup::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesTypes = FmFeesType::where('type','fees')
                        ->where('school_id',auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();

            $paymentMethods = SmPaymentMethhod::whereIn('method', ["Cash", "Cheque", "Bank"])
                ->where('school_id', auth()->user()->school_id)
                ->get();

            $bankAccounts = SmBankAccount::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();
            // View End

            $invoiceSettings = FmFeesInvoiceSettings::where('school_id', auth()->user()->school_id)->first();

            $invoiceInfo = FmFeesInvoice::find($id);
            $invoiceDetails = FmFeesInvoiceChield::where('fees_invoice_id', $invoiceInfo->id)
                        ->where('school_id', auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();

            $students = StudentRecord::where('id',$invoiceInfo->record_id)
                    ->where('class_id', $invoiceInfo->class_id)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('academic_id', getAcademicId())
                    ->get();

            return response()->json(compact('classes', 'feesGroups', 'feesTypes', 'paymentMethods', 'bankAccounts', 'invoiceSettings', 'invoiceInfo', 'invoiceDetails', 'students'));
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesInvoiceUpdate(InvoiceStoreRequest $request)
    {
        try {
            $student= StudentRecord::find($request->student);
            $storeFeesInvoice = FmFeesInvoice::find($request->id);
            $storeFeesInvoice->class_id = $request->class;
            $storeFeesInvoice->create_date = date('Y-m-d', strtotime($request->create_date));
            $storeFeesInvoice->due_date = date('Y-m-d', strtotime($request->due_date));
            $storeFeesInvoice->payment_status = $request->payment_status;
            $storeFeesInvoice->bank_id = $request->bank;
            $storeFeesInvoice->student_id = $student->student_id;
            $storeFeesInvoice->record_id = $request->student;
            $storeFeesInvoice->school_id = auth()->user()->school_id;
            $storeFeesInvoice->academic_id = getAcademicId();
            $storeFeesInvoice->update();

            FmFeesInvoiceChield::where('fees_invoice_id', $request->id)->delete();
            FmFeesWeaver::where('fees_invoice_id', $storeFeesInvoice->id)->delete();

            foreach ($request->feesType as $key => $type) {
                $storeFeesInvoiceChield = new FmFeesInvoiceChield();
                $storeFeesInvoiceChield->fees_invoice_id = $storeFeesInvoice->id;
                $storeFeesInvoiceChield->fees_type = $type;
                $storeFeesInvoiceChield->amount = $request->amount[$key];
                $storeFeesInvoiceChield->weaver = $request->weaver ? $request->weaver[$key]:null;
                $storeFeesInvoiceChield->sub_total = $request->sub_total[$key];
                $storeFeesInvoiceChield->due_amount = $request->sub_total[$key];
                $storeFeesInvoiceChield->note = $request->note ? $request->note[$key]:null;

                if ($request->paid_amount) {
                    $storeFeesInvoiceChield->paid_amount = $request->paid_amount[$key];
                }

                $storeFeesInvoiceChield->school_id = auth()->user()->school_id;
                $storeFeesInvoiceChield->academic_id = getAcademicId();
                $storeFeesInvoiceChield->save();

                $storeWeaver = new FmFeesWeaver();
                $storeWeaver->fees_invoice_id = $storeFeesInvoice->id;
                $storeWeaver->fees_type = $type;
                $storeWeaver->student_id = $request->student;
                $storeWeaver->weaver = $request->weaver ? $request->weaver[$key]:null;
                $storeWeaver->note = $request->note ? $request->note[$key]:null;
                $storeWeaver->school_id = auth()->user()->school_id;
                $storeWeaver->academic_id = getAcademicId();
                $storeWeaver->save();
            }

            //Notification
            $student = SmStudent::with('parents')->find($storeFeesInvoice->student_id);
            sendNotification("Fees Assign Update", null, $student->user_id, 2);
            sendNotification("Fees Assign Update", null, $student->parents->user_id, 3);

            return response()->json(['message'=>'Update Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesInvoiceView($id, $state)
    {
        $invoiceInfo = FmFeesInvoice::find($id);
        $invoiceDetails = FmFeesInvoiceChield::where('fees_invoice_id',$invoiceInfo->id)
                        ->where('school_id', auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get()->map(function ($value) {
                            $total = ($value->amount+ $value->fine) - ($value->paid_amount + $value->weaver);
                            return [
                                'typeName' => $value->feesType ? $value->feesType->name : '',
                                'amount' => $value->amount ? (float)$value->amount : 0,
                                'weaver' => $value->weaver ? $value->weaver : 0,
                                'fine' => $value->fine? $value->fine : 0,
                                'sub_total' => $value->paid_amount ? $value->paid_amount : 0,
                                'total' => $total,
                            ];
                        });

        $totalAmount = $invoiceDetails->sum('amount');
        $totalWeaver = $invoiceDetails->sum('weaver');
        $totalPaidAmount = $invoiceDetails->sum('paid_amount');
        $totalFine = $invoiceDetails->sum('fine');

        $banks = SmBankAccount::where('active_status', '=', 1)
                ->where('school_id', auth()->user()->school_id)
                ->get()->map(function ($value){
                    return [
                        'bank_name'=>$value->bank_name,
                        'account_name'=>$value->account_name,
                        'account_number'=>$value->account_number,
                        'account_type'=>$value->account_type
                    ];
                });

        if($state == 'view'){
            return response()->json(compact('invoiceInfo','invoiceDetails',
            'banks','totalAmount','totalWeaver',
            'totalPaidAmount','totalFine'));
        }else{
            return response()->json(compact('invoiceInfo','invoiceDetails','banks'));
        }
    }

    public function feesInvoiceDelete(Request $request)
    {
        try {
            $invoiceDelete = FmFeesInvoice::find($request->id)->delete();
            if ($invoiceDelete) {
                FmFeesInvoiceChield::where('fees_invoice_id', $request->id)->delete();
            }
            return response()->json(['message'=>'Delete Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function addFeesPayment($id)
    {
        try {
            $classes = SmClass::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesGroups = FmFeesGroup::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesTypes = FmFeesType::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $feesTypes = FmFeesType::where('type','fees')
                        ->where('school_id',auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();

            $paymentMethods = SmPaymentMethhod::whereIn('method', ["Cash", "Cheque", "Bank"])
                ->where('active_status', 1)
                ->where('school_id', auth()->user()->school_id)
                ->get();

            $bankAccounts = SmBankAccount::where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $invoiceInfo = FmFeesInvoice::with('studentInfo')->find($id);
            $walletBalance = $invoiceInfo->studentInfo->user->wallet_balance;

            $invoiceDetails = FmFeesInvoiceChield::where('fees_invoice_id', $invoiceInfo->id)
                ->where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            $stripe_info = SmPaymentGatewaySetting::where('gateway_name', 'stripe')
                ->where('school_id', auth()->user()->school_id)
                ->first();

            return response()->json(compact('classes', 'feesGroups', 'feesTypes', 'paymentMethods', 'bankAccounts', 'invoiceInfo', 'invoiceDetails', 'stripe_info','walletBalance'));
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }

    }

    public function feesPaymentStore(FeesPaymentRequest $request)
    {
        // if ($request->total_paid_amount == null) {
        //     Toastr::warning('Paid Amount Can Not Be Blank', 'Failed');
        //     return redirect()->back();
        // }

        try {
            $destination = 'public/uploads/student/document/';
            $file = fileUpload($request->file('file'), $destination);

            $record = StudentRecord::find($request->student_id);

            $student = SmStudent::with('parents')->find($record->student_id);

            if ($request->add_wallet > 0) {
                $user = User::find($student->user_id);
                $walletBalance = $user->wallet_balance;
                $user->wallet_balance = $walletBalance + $request->add_wallet;
                $user->update();

                $addPayment = new WalletTransaction();
                $addPayment->amount = $request->add_wallet;
                $addPayment->payment_method = $request->payment_method;
                $addPayment->user_id = $user->id;
                $addPayment->type = 'diposit';
                $addPayment->status = 'approve';
                $addPayment->note = 'Fees Extra Payment Add';
                $addPayment->school_id = auth()->user()->school_id;
                $addPayment->academic_id = getAcademicId();
                $addPayment->save();

                $school = SmSchool::find($user->school_id);
                
                $compact['user_email'] = $user->email;
                $compact['full_name'] = $user->full_name;
                $compact['method'] = $request->payment_method;
                $compact['create_date'] = date('Y-m-d');
                $compact['school_name'] = $school->school_name;
                $compact['current_balance'] = $user->wallet_balance;
                $compact['add_balance'] = $request->total_paid_amount;
                $compact['previous_balance'] = $user->wallet_balance - $request->add_wallet;

                @send_mail($user->email, $user->full_name, "fees_extra_amount_add", $compact);

                //Notification
                sendNotification("Fees Xtra Amount Add", null, $student->user_id, 2);
            }

            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $request->invoice_id;
            $storeTransaction->payment_note = $request->payment_note;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->bank_id = $request->bank;
            $storeTransaction->student_id = $student->id;
            $storeTransaction->student_id = $request->student_id;
            $storeTransaction->user_id = auth()->user()->id;
            $storeTransaction->file = $file;
            $storeTransaction->paid_status = 'approve';
            $storeTransaction->school_id = auth()->user()->school_id;
            $storeTransaction->academic_id = getAcademicId();
            $storeTransaction->save();

            foreach ($request->fees_type as $key => $type) {
                $id = FmFeesInvoiceChield::where('fees_invoice_id', $request->invoice_id)->where('fees_type', $type)->first('id')->id;
                $storeFeesInvoiceChield = FmFeesInvoiceChield::find($id);
                $storeFeesInvoiceChield->weaver = $request->weaver ? $request->weaver[$key]:null;
                $storeFeesInvoiceChield->due_amount = $request->due[$key];
                $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount + ($request->paid_amount[$key] - $request->extraAmount[$key]);
                $storeFeesInvoiceChield->fine = $storeFeesInvoiceChield->fine + $request->fine[$key];
                $storeFeesInvoiceChield->update();

                $storeWeaver = new FmFeesWeaver();
                $storeWeaver->fees_invoice_id = $request->invoice_id;
                $storeWeaver->fees_type = $type;
                $storeWeaver->student_id = $student->id;
                $storeWeaver->weaver = $request->weaver ? $request->weaver[$key]:null;
                $storeWeaver->note = $request->note ? $request->note[$key]:null;
                $storeWeaver->school_id = auth()->user()->school_id;
                $storeWeaver->academic_id = getAcademicId();
                $storeWeaver->save();

                if ($request->paid_amount[$key] > 0) {
                    $storeTransactionChield = new FmFeesTransactionChield();
                    $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                    $storeTransactionChield->fees_type = $type;
                    $storeTransactionChield->weaver = $request->weaver ? $request->weaver[$key]:null;
                    $storeTransactionChield->fine = $request->fine[$key];
                    $storeTransactionChield->paid_amount = $request->paid_amount[$key];
                    $storeTransactionChield->note = $request->note ? $request->note[$key]:null;
                    $storeTransactionChield->school_id = auth()->user()->school_id;
                    $storeTransactionChield->academic_id = getAcademicId();
                    $storeTransactionChield->save();
                }

                // Income
                $payment_method = SmPaymentMethhod::where('method', $request->payment_method)->first();
                $income_head = generalSetting();

                $add_income = new SmAddIncome();
                $add_income->name = 'Fees Collect';
                $add_income->date = date('Y-m-d');
                $add_income->amount = $request->paid_amount[$key];
                $add_income->fees_collection_id = $storeTransaction->id;
                $add_income->active_status = 1;
                $add_income->income_head_id = $income_head->income_head_id;
                $add_income->payment_method_id = $payment_method->id;
                $add_income->created_by = Auth()->user()->id;
                $add_income->school_id = auth()->user()->school_id;
                $add_income->academic_id = getAcademicId();
                $add_income->save();

                // Bank
                if ($request->payment_method == "Bank") {
                    $payment_method = SmPaymentMethhod::where('method', $request->payment_method)->first();
                    $bank = SmBankAccount::where('id', $request->bank)
                        ->where('school_id', auth()->user()->school_id)
                        ->first();
                    $after_balance = $bank->current_balance + $request->paid_amount[$key];

                    $bank_statement = new SmBankStatement();
                    $bank_statement->amount = $request->paid_amount[$key];
                    $bank_statement->after_balance = $after_balance;
                    $bank_statement->type = 1;
                    $bank_statement->details = "Fees Payment";
                    $bank_statement->item_sell_id = $storeTransaction->id;
                    $bank_statement->payment_date = date('Y-m-d');
                    $bank_statement->bank_id = $request->bank;
                    $bank_statement->school_id = auth()->user()->school_id;
                    $bank_statement->payment_method = $payment_method->id;
                    $bank_statement->save();

                    $current_balance = SmBankAccount::find($request->bank);
                    $current_balance->current_balance = $after_balance;
                    $current_balance->update();
                }
            }
            //Notification
            sendNotification("Add Fees Payment", null, $student->user_id, 2);
            sendNotification("Add Fees Payment", null, $student->parents->user_id, 3);

            return response()->json(['message'=>'Payment Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function singlePaymentView($id){
        $generalSetting = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first();
        $transcationInfo = FmFeesTransaction::find($id);
        $transcationDetails = FmFeesTransactionChield::where('fees_transaction_id',$transcationInfo->id)
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', getAcademicId())
                            ->get();
        $invoiceInfo = FmFeesInvoice::find($transcationInfo->fees_invoice_id);
        return response()->json(compact('generalSetting','invoiceInfo','transcationDetails'));
    }

    public function deleteSingleFeesTranscation($id)
    {
        try {
            $total_amount = 0;
            $transcation = FmFeesTransaction::find($id);
            $allTranscations = FmFeesTransactionChield::where('fees_transaction_id', $transcation->id)->get();
            foreach ($allTranscations as $key => $allTranscation) {
                $total_amount += $allTranscation->paid_amount;

                $transcationId = FmFeesTransaction::find($allTranscation->fees_transaction_id);

                $fesInvoiceId = FmFeesInvoiceChield::where('fees_invoice_id', $transcationId->fees_invoice_id)
                    ->where('fees_type', $allTranscation->fees_type)
                    ->first();

                $storeFeesInvoiceChield = FmFeesInvoiceChield::find($fesInvoiceId->id);
                $storeFeesInvoiceChield->due_amount = $storeFeesInvoiceChield->due_amount + $allTranscation->paid_amount;
                $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount - $allTranscation->paid_amount;
                $storeFeesInvoiceChield->update();
            }

            if ($transcation->payment_method == "Wallet") {
                $user = User::find($transcation->user_id);
                $user->wallet_balance = $user->wallet_balance + $total_amount;
                $user->update();

                $addPayment = new WalletTransaction();
                $addPayment->amount = $total_amount;
                $addPayment->payment_method = $transcation->payment_method;
                $addPayment->user_id = $user->id;
                $addPayment->type = 'fees_refund';
                $addPayment->status = 'approve';
                $addPayment->note = 'Fees Payment';
                $addPayment->school_id = auth()->user()->school_id;
                $addPayment->academic_id = getAcademicId();
                $addPayment->save();
            }

            SmAddIncome::where('fees_collection_id', $id)->delete();
            $transcation->delete();

            //Notification
            $student = SmStudent::with('parents')->find($transcation->student_id);
            sendNotification("Delete Fees Payment", null, 1, 1);
            sendNotification("Delete Fees Payment", null, $student->user_id, 2);
            sendNotification("Delete Fees Payment", null, $student->parents->user_id, 3);

            return response()->json(['message'=>'Delete Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function bankPayment()
    {
        $classes = SmClass::where('active_status', 1)
            ->where('academic_id', getAcademicId())
            ->where('school_id', auth()->user()->school_id)
            ->get();

        $feesPayments = FmFeesTransaction::with('feeStudentInfo', 'transcationDetails', 'transcationDetails.transcationFeesType')
            ->where('paid_status', 0)
            ->whereIn('payment_method', ['Bank', 'Cheque'])
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', getAcademicId())
            ->get();

        return response()->json(compact('classes', 'feesPayments'));
    }

    public function searchBankPayment(BankFeesPayment $request)
    {
        try {
            $rangeArr = $request->payment_date ? explode('-', $request->payment_date) : [date('m/d/Y'), date('m/d/Y')];

            if ($request->payment_date) {
                $date_from = date('Y-m-d', strtotime(trim($rangeArr[0])));
                $date_to = date('Y-m-d', strtotime(trim($rangeArr[1])));
            }

            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', auth()->user()->school_id)
                ->get();

            $class_id= $request->class;
            $section_id= $request->section;
            $class= SmClass::with('classSections')->where('id',$request->class)->first();

            $student_ids = StudentRecord::when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                })
                ->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                })
                ->where('school_id', auth()->user()->school_id)
                ->pluck('student_id')
                ->unique();


            $feesPayments = FmFeesTransaction::with('feeStudentInfo', 'transcationDetails', 'transcationDetails.transcationFeesType')
                ->when($request->approve_status, function ($query) use ($request) {
                    $query->where('paid_status', $request->approve_status);
                })
                ->when($request->class, function ($query) use ($request) {
                    $query->whereHas('recordDetail', function($q) use($request){
                        return $q->where('class_id', $request->class);
                    });
                })
                ->when($request->section, function ($query) use ($request) {
                    $query->whereHas('recordDetail', function($q) use($request){
                        return $q->where('section_id', $request->section);
                    });
                })
                ->when($request->payment_date, function ($query) use ($date_from, $date_to) {
                    $query->whereDate('created_at', '>=', $date_from)
                        ->whereDate('created_at', '<=', $date_to);
                })
                ->whereIn('student_id', $student_ids)
                ->whereIn('payment_method', ['Bank', 'Cheque'])
                ->where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->get();

            return response()->json(compact('classes', 'feesPayments','class_id','section_id','class'));
        } catch (\Exception $e) {
            return response()->json(['Message'=>'Error']);
        }
    }

    public function approveBankPayment(Request $request)
    {
        try {
            $transcation = $request->transcation_id;
            if($request->total_paid_amount){
                $total_paid_amount = $request->total_paid_amount;
            }else{
                $total_paid_amount = null;
            }
            $transcationInfo = FmFeesTransaction::find($transcation);

            $this->addFeesAmount($transcation , $total_paid_amount);

            //Notification
            $student = SmStudent::with('parents')->find($transcationInfo->student_id);
            sendNotification("Approve Bank Payment", null, 1, 1);
            sendNotification("Approve Bank Payment", null, $student->user_id, 2);
            sendNotification("Approve Bank Payment", null, $student->parents->user_id, 3);

            return response()->json(['message'=>'Approve Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function rejectBankPayment(Request $request)
    {
        try {
            $transcation = FmFeesTransaction::where('id', $request->transcation_id)->first();
            $fees_transcation = FmFeesTransaction::find($transcation->id);
            $fees_transcation->paid_status = 'reject';
            $fees_transcation->update();

            //Notification
            $student = SmStudent::with('parents')->find($transcation->student_id);
            sendNotification("Approve Bank Payment", null, 1, 1);
            sendNotification("Approve Bank Payment", null, $student->user_id, 2);
            sendNotification("Approve Bank Payment", null, $student->parents->user_id, 3);

            return response()->json(['message'=>'Rejected Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function feesInvoiceSettings()
    {
        try {
            $invoiceSettings = FmFeesInvoiceSettings:: where('school_id', auth()->user()->school_id)->first();
            return response()->json(compact('invoiceSettings'));
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function ajaxFeesInvoiceSettingsUpdate(Request $request)
    {
        try {
            $updateData = FmFeesInvoiceSettings::find($request->id);
            $updateData->invoice_positions = $request->invoicePositions;
            $updateData->uniq_id_start = $request->uniqIdStart;
            $updateData->prefix = $request->prefix;
            $updateData->class_limit = $request->classLimit;
            $updateData->section_limit = $request->sectionLimit;
            $updateData->admission_limit = $request->admissionLimit;
            $updateData->weaver = $request->weaver;
            $updateData->school_id = auth()->user()->school_id;
            $updateData->update();
            return response()->json(['message'=>'Update Sucessfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function addFeesAmount($transcation_id , $total_paid_amount){
        $transcation = FmFeesTransaction::find($transcation_id);
        $allTranscations = FmFeesTransactionChield::where('fees_transaction_id', $transcation->id)->get();
        foreach ($allTranscations as $key => $allTranscation) {
            $transcationId = FmFeesTransaction::find($allTranscation->fees_transaction_id);

            $fesInvoiceId = FmFeesInvoiceChield::where('fees_invoice_id', $transcationId->fees_invoice_id)
                ->where('fees_type', $allTranscation->fees_type)
                ->first();

            $storeFeesInvoiceChield = FmFeesInvoiceChield::find($fesInvoiceId->id);
            $storeFeesInvoiceChield->due_amount = $storeFeesInvoiceChield->due_amount - $allTranscation->paid_amount;
            $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount + $allTranscation->paid_amount;
            $storeFeesInvoiceChield->update();

            // Income
            $payment_method = SmPaymentMethhod::where('method', $transcation->payment_method)->first();
            $income_head = generalSetting();

            $add_income = new SmAddIncome();
            $add_income->name = 'Fees Collect';
            $add_income->date = date('Y-m-d');
            $add_income->amount = $allTranscation->paid_amount;
            $add_income->fees_collection_id = $transcation->fees_invoice_id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $income_head->income_head_id;
            $add_income->payment_method_id = $payment_method->id;
            if ($payment_method->id == 3) {
                $add_income->account_id = $transcation->bank_id;
            }
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = auth()->user()->school_id;
            $add_income->academic_id = getAcademicId();
            $add_income->save();

            if ($transcation->payment_method == "Bank") {
                $bank = SmBankAccount::where('id', $transcation->bank_id)
                    ->where('school_id', auth()->user()->school_id)
                    ->first();

                $after_balance = $bank->current_balance + $total_paid_amount;

                $bank_statement = new SmBankStatement();
                $bank_statement->amount = $allTranscation->paid_amount;
                $bank_statement->after_balance = $after_balance;
                $bank_statement->type = 1;
                $bank_statement->details = "Fees Payment";
                $bank_statement->payment_date = date('Y-m-d');
                $bank_statement->item_sell_id = $transcation->id;
                $bank_statement->bank_id = $transcation->bank_id;
                $bank_statement->school_id = auth()->user()->school_id;
                $bank_statement->payment_method = $payment_method->id;
                $bank_statement->save();

                $current_balance = SmBankAccount::find($transcation->bank_id);
                $current_balance->current_balance = $after_balance;
                $current_balance->update();
            }
            $fees_transcation = FmFeesTransaction::find($transcation->id);
            $fees_transcation->paid_status = 'approve';
            $fees_transcation->update();
        }

        if ($transcation->add_wallet_money > 0) {
            $user = User::find($transcation->user_id);
            $walletBalance = $user->wallet_balance;
            $user->wallet_balance = $walletBalance + $transcation->add_wallet_money;
            $user->update();
    
            $addPayment = new WalletTransaction();
            $addPayment->amount = $transcation->add_wallet_money;
            $addPayment->payment_method = $transcation->payment_method;
            $addPayment->user_id = $user->id;
            $addPayment->type = 'diposit';
            $addPayment->status = 'approve';
            $addPayment->note = 'Fees Extra Payment Add';
            $addPayment->school_id = auth()->user()->school_id;
            $addPayment->academic_id = getAcademicId();
            $addPayment->save();
    
            $school = SmSchool::find($user->school_id);
            $compact['full_name'] = $user->full_name;
            $compact['method'] = $transcation->payment_method;
            $compact['create_date'] = date('Y-m-d');
            $compact['school_name'] = $school->school_name;
            $compact['current_balance'] = $user->wallet_balance;
            $compact['add_balance'] = $transcation->add_wallet_money;
            $compact['previous_balance'] = $user->wallet_balance - $transcation->add_wallet_money;
    
            @send_mail($user->email, $user->full_name, "fees_extra_amount_add", $compact);
    
            sendNotification($user->id, null, null, $user->role_id, "Fees Xtra Amount Add");
        }
    }

    private function invStore($request){
        $storeFeesInvoice = new FmFeesInvoice();
        $storeFeesInvoice->class_id = $request->class;
        $storeFeesInvoice->create_date = date('Y-m-d', strtotime($request->create_date));
        $storeFeesInvoice->due_date = date('Y-m-d', strtotime($request->due_date));
        $storeFeesInvoice->payment_status = $request->payment_status;
        $storeFeesInvoice->payment_method = $request->payment_method;
        $storeFeesInvoice->bank_id = $request->bank;
        $storeFeesInvoice->student_id = $request->student;
        $storeFeesInvoice->record_id = $request->record_id;
        $storeFeesInvoice->school_id = auth()->user()->school_id;
        $storeFeesInvoice->academic_id = getAcademicId();
        $storeFeesInvoice->save();
        $storeFeesInvoice->invoice_id = feesInvoiceNumber($storeFeesInvoice);
        $storeFeesInvoice->save();

        if ($request->paid_amount > 0) {
            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $storeFeesInvoice->id;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->bank_id = $request->bank;
            $storeTransaction->student_id = $request->student;
            $storeTransaction->record_id = $request->record_id;
            $storeTransaction->user_id = auth()->user()->id;
            $storeTransaction->paid_status = 'approve';
            $storeTransaction->school_id = auth()->user()->school_id;
            $storeTransaction->academic_id = getAcademicId();
            $storeTransaction->save();
        }

        foreach ($request->feesType as $key => $type) {
            $storeFeesInvoiceChield = new FmFeesInvoiceChield();
            $storeFeesInvoiceChield->fees_invoice_id = $storeFeesInvoice->id;
            $storeFeesInvoiceChield->fees_type = $type;
            $storeFeesInvoiceChield->amount = $request->amount[$key];
            $storeFeesInvoiceChield->weaver = $request->weaver ? $request->weaver[$key]: null;
            $storeFeesInvoiceChield->sub_total = $request->sub_total[$key];
            $storeFeesInvoiceChield->note = $request->note? $request->note[$key]:null;
            if ($request->paid_amount > 0) {
                $storeFeesInvoiceChield->paid_amount = $request->paid_amount[$key];
                $storeFeesInvoiceChield->due_amount = $request->sub_total[$key] - $request->paid_amount[$key];
            } else {
                $storeFeesInvoiceChield->due_amount = $request->sub_total[$key];
            }
            $storeFeesInvoiceChield->school_id = auth()->user()->school_id;
            $storeFeesInvoiceChield->academic_id = getAcademicId();
            $storeFeesInvoiceChield->save();

            if ($request->paid_amount > 0) {
                $storeTransactionChield = new FmFeesTransactionChield();
                $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                $storeTransactionChield->fees_type = $type;
                $storeTransactionChield->weaver = $request->weaver[$key];
                $storeTransactionChield->paid_amount = $request->paid_amount[$key];
                $storeTransactionChield->note = $request->note[$key];
                $storeTransactionChield->school_id = auth()->user()->school_id;
                $storeTransactionChield->academic_id = getAcademicId();
                $storeTransactionChield->save();

                // Income
                addIncome($request->payment_method, 'Fees Collect', $request->paid_amount[$key], $storeTransaction->id, auth()->user()->id, null);
            
                // Bank
                if ($request->payment_method == "Bank") {
                    $payment_method = SmPaymentMethhod::where('method', $request->payment_method)->first();
                    $bank = SmBankAccount::where('id', $request->bank)
                            ->where('school_id', auth()->user()->school_id)
                            ->first();
                    $after_balance = $bank->current_balance + $request->paid_amount[$key];

                    $bank_statement = new SmBankStatement();
                    $bank_statement->amount = $request->paid_amount[$key];
                    $bank_statement->after_balance = $after_balance;
                    $bank_statement->type = 1;
                    $bank_statement->details = "Fees Payment";
                    $bank_statement->item_sell_id = $storeTransaction->id;
                    $bank_statement->payment_date = date('Y-m-d');
                    $bank_statement->bank_id = $request->bank;
                    $bank_statement->school_id = auth()->user()->school_id;
                    $bank_statement->payment_method = $payment_method->id;
                    $bank_statement->save();

                    $current_balance = SmBankAccount::find($request->bank);
                    $current_balance->current_balance = $after_balance;
                    $current_balance->update();
                }
            }

            $storeWeaver = new FmFeesWeaver();
            $storeWeaver->fees_invoice_id = $storeFeesInvoice->id;
            $storeWeaver->fees_type = $type;
            $storeWeaver->student_id = $request->student;
            $storeWeaver->weaver = $request->weaver ? $request->weaver[$key]:null;
            $storeWeaver->note = $request->note? $request->note[$key]:null;
            $storeWeaver->school_id = auth()->user()->school_id;
            $storeWeaver->academic_id = getAcademicId();
            $storeWeaver->save();
        }
    }
}
