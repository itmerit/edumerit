<?php

namespace Modules\Wallet\Http\Controllers;

use App\User;
use DataTables;
use App\SmBankAccount;
use App\SmNotification;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\SmPaymentGatewaySetting;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Wallet\Entities\WalletTransaction;
use Modules\CcAveune\Http\Controllers\CcAveuneController;

class WalletController extends Controller
{
    public function addWalletAmount(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'payment_method' => 'required',
            'bank' => 'required_if:payment_method,Bank',
            'file' => 'mimes:jpg,jpeg,png,pdf',
        ]);
      

            $url = '';
            if ($request->payment_method == "Cheque" || $request->payment_method == "Bank") {
                $uploadFile = "";
                if ($request->file('file') != "") {
                    $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                    $file = $request->file('file');
                    $fileSize = filesize($file);
                    $fileSizeKb = ($fileSize / 1000000);
                    if ($fileSizeKb >= $maxFileSize) {
                        Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                        return redirect()->back();
                    }
                    $file = $request->file('file');
                    $uploadFile = 'doc1-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('public/uploads/student/document/', $uploadFile);
                    $uploadFile = 'public/uploads/student/document/' . $uploadFile;
                }

                $addPayment = new WalletTransaction();
                $addPayment->amount = $request->amount;
                $addPayment->payment_method = $request->payment_method;
                $addPayment->bank_id = $request->bank;
                $addPayment->note = $request->note;
                $addPayment->file = $uploadFile;
                $addPayment->type = 'diposit';
                $addPayment->user_id = Auth::user()->id;
                $addPayment->school_id = Auth::user()->school_id;
                $addPayment->academic_id = getAcademicId();
                $addPayment->save();
            } else {
                $data = [];
                $data['payment_method'] = $request->payment_method;
                $data['amount'] = $request->amount;
                $data['service_charge'] = chargeAmount($request->payment_method, $request->amount);
                $data['user_id'] = Auth::user()->id;
                $data['type'] = "Wallet";
                $data['wallet_type'] = 'diposit';
                $data['description'] = "Wallet Amount Request";
                $data['stripeToken'] = $request->stripeToken;

                if($data['payment_method'] == 'CcAveune'){
                    $addPayment = new WalletTransaction();
                    $addPayment->amount= $data['amount'];
                    $addPayment->payment_method= $data['payment_method'];
                    $addPayment->user_id= $data['user_id'];
                    $addPayment->type= $data['wallet_type'];
                    $addPayment->school_id= auth()->user()->school_id;
                    $addPayment->academic_id= getAcademicId();
                    $addPayment->save();

                    $ccAvenewPaymentController = new CcAveuneController();
                    $ccAvenewPaymentController->studentFeesPay($data['amount'] , $addPayment->id, $data['type']);
                }else{
                    $classMap = config('paymentGateway.' . $data['payment_method']);
                    $make_payment = new $classMap();
                    $url = $make_payment->handle($data);
                    if (!$url) {
                        $url = 'wallet/my-wallet';
                    }
                    if ($request->wantsJson()) {
                        return response()->json(['goto' => $url]);
                    } else {
                        return redirect($url);
                    }
                }
            }

            // Notification Start
            $this->sendNotification(1, 1, "Wallet Request");

            $accounts_ids = User::where('role_id', 6)->get();
            foreach ($accounts_ids as $accounts_id) {
                $this->sendNotification($accounts_id->id, $accounts_id->role_id, "Wallet Request");
            }
            return response()->json(['success']);
            // Notification End

    }

    public function walletPendingDiposit()
    {
        $walletTotalAmounts = $this->getTotalWalletAmount('diposit','pending');
        return view('wallet::walletPending', compact('walletTotalAmounts'));
    }


    public function walletApprovePayment(Request $request)
    {
        try {
            $user = User::find($request->user_id);

            $currentamount = $user->wallet_balance;
            $addedAmount = $currentamount + $request->amount;
            $user->wallet_balance = $addedAmount;
            $user->update();

            $status = WalletTransaction::find($request->id);
            $status->status = 'approve';
            $status->updated_at = date('Y-m-d');
            $status->update();

            $compact['user_email'] = $user->email;
            $compact['method'] = $status->payment_method;
            $compact['create_date'] = $status->created_by;
            $compact['current_balance'] = $user->wallet_balance;
            $compact['add_balance'] = $request->amount;

            @send_mail($user->email, $user->full_name, "wallet_approve", $compact);

            $this->sendNotification($user->id, $user->role_id, "Wallet Approve");

            Toastr::success('Approve Successful', 'Success');
            return redirect()->route('wallet.pending-diposit');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function walletRejectPayment(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $status = WalletTransaction::find($request->id);
            $status->status = 'reject';
            $status->reject_note = $request->reject_note;
            $status->updated_at = date('Y-m-d');
            $status->update();

            $compact['user_email'] = $user->email;
            $compact['method'] = $status->payment_method;
            $compact['create_date'] = $status->created_at;
            $compact['current_balance'] = $user->wallet_balance;
            $compact['add_balance'] = $request->amount;
            $compact['reject_reason'] = $request->reject_note;

            @send_mail($user->email, $user->full_name, "wallet_reject", $compact);

            $this->sendNotification($user->id, $user->role_id, "Wallet Approve");

            Toastr::success('Reject Successful', 'Success');
            return redirect()->route('wallet.pending-diposit');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function walletApproveDiposit()
    {
        $walletTotalAmounts = $this->getTotalWalletAmount('diposit','pending');
        return view('wallet::walletApprove', compact('walletTotalAmounts'));
    }

    public function walletRejectDiposit()
    {
        $walletTotalAmounts = $this->getTotalWalletAmount('diposit','pending');
        return view('wallet::walletReject', compact('walletTotalAmounts'));
    }

    public function walletTransaction()
    {
        $walletAmounts = WalletTransaction::where('school_id', Auth::user()->school_id)
            ->get();
        return view('wallet::walletTransaction', compact('walletAmounts'));
    }

    public function walletTransactionAjax(Request $request )
    {
       if($request->ajax()){
        $walletAmounts = WalletTransaction::with('userName')->where('school_id', Auth::user()->school_id)
        ->get();

        return Datatables::of($walletAmounts)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return dateConvert(@$row->updated_at);
            })
            ->addColumn('t_status', function ($row) {
                if ($row->status == "pending") {
                    $btn = '<button class="primary-btn small bg-warning text-white border-0">' . app('translator')->get('common.pending') . '</button>';
                } elseif ($row->status == "approve") {
                    $btn = '<button class="primary-btn small bg-success text-white border-0">' . app('translator')->get('wallet::wallet.approve') . '</button>';
                } elseif ($row->status == "reject") {
                    $btn = '<button class="primary-btn small bg-danger text-white border-0">' . app('translator')->get('wallet::wallet.reject') . '</button>';
                }
                elseif ($row->status == "refund") {
                    $btn = '<button class="primary-btn small bg-primary text-white border-0">' . app('translator')->get('wallet::wallet.refund') . '</button>';
                }
                else {
                    $btn = '<button class="primary-btn small bg-primary text-white border-0">' . app('translator')->get('accounts.expense') . '</button>';
                }
                return $btn;
            })

            ->addColumn('pending_amount', function ($row) {
                if($row->status == 'pending'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                }  
            })
            ->addColumn('approve_amount', function ($row) {
                if($row->status == 'approve'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                }  
            })
            ->addColumn('reject_amount', function ($row) {
                if($row->status == 'reject'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                } 
               
            })
            
            ->addColumn('refund_amount', function ($row) {
                if($row->type == 'refund' && $row->status == 'approve'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                }  
            })
            ->addColumn('expense_amount', function ($row) {
                if($row->type == 'expense'){
                   
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                }  
            })
            ->addColumn('fees_refund_amount', function ($row) {
                if($row->type == 'fees_refund'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                }  
            })
            
            ->rawColumns(['action','date','pending_amount','approve_amount','reject_amount','refund_amount','expense_amount','fees_refund_amount','t_status'])
            ->make(true);
       }
    }

    public function walletRefundRequest()
    {
        $walletRefunds = WalletTransaction::where('type', 'refund')
            ->where('school_id', Auth::user()->school_id)
            ->get();

        return view('wallet::walletRefundRequest', compact('walletRefunds'));
    }

    public function walletRefundRequestAjax(Request $request)
    {

        if($request->ajax()){
            $walletRefunds = WalletTransaction::with('userName')->where('type', 'refund')
            ->where('school_id', Auth::user()->school_id)
            ->get();

            return Datatables::of($walletRefunds)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return dateConvert(@$row->created_at);
            })
            ->addColumn('t_status', function ($row) {
                if ($row->status == "pending") {
                    $btn = '<button class="primary-btn small bg-warning text-white border-0">' . app('translator')->get('common.pending') . '</button>';
                } elseif ($row->status == "approve") {
                    $btn = '<button class="primary-btn small bg-success text-white border-0">' . app('translator')->get('wallet::wallet.approve') . '</button>';
                } else {
                    $btn = '<button class="primary-btn small bg-danger text-white border-0">' . app('translator')->get('wallet::wallet.reject') . '</button>';
                }
                return $btn;
            })

            ->addColumn('pending_refund', function ($row) {
                if($row->type == 'refund' && $row->status == 'pending'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                }  
            })
            ->addColumn('approve_refund', function ($row) {
                if($row->type == 'refund' && $row->status == 'approve'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                }  
            })
            ->addColumn('reject_refund', function ($row) {
                if($row->type == 'refund' && $row->status == 'reject'){
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                } 
               
            })
            
            ->addColumn('file_view', function ($row) {
                if($row->file){
                    return '<a class="dropdown-item"  onclick="fileView(' . $row->id . ');" href="#">' . app('translator')->get('common.view') . '</a>' ;
                }
            })
            ->addColumn('refund_note', function ($row) {
                if($row->note){
                    return '<a class="dropdown-item note_'.$row->id.'" data-note="'.$row->note.'"  onclick="refundNote(' . $row->id . ');" href="#">' . app('translator')->get('common.view') . '</a>' ;
                }
            })
            ->addColumn('action', function ($row) {
                if ($row->status == 'pending'){
                    $btn = '<div class="dropdown CRM_dropdown">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                            <div class="dropdown-menu dropdown-menu-right">'
                                .(userPermission('wallet.approve-refund') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                                    '<a onclick="approveRefund(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#approveRefund" data-id="' . $row->id . '"  >' . app('translator')->get('wallet::wallet.approve') . '</a>') : '') .
                                    (userPermission('wallet.reject-refund') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                                    '<a onclick="rejectRefund(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#approveRefund" data-id="' . $row->id . '"  >' . app('translator')->get('wallet::wallet.reject') . '</a>') : '') .

                            '</div>
                        </div>';

                    return $btn;
                }

            })
            
            ->rawColumns(['pending_refund','approve_refund','reject_refund','date','t_status','action','file_view','refund_note'])
            ->make(true);

        }
        

    }

    public function walletRefundRequestStore(Request $request)
    {

        $request->validate([
            'refund_note' => 'required',
            'refund_file' => 'mimes:jpg,jpeg,png,pdf',
        ]);

        $existRefund = WalletTransaction::where('type', 'refund')
            ->where('user_id', $request->user_id)
            ->where('status', 'pending')
            ->where('school_id', Auth::user()->school_id)
            ->first();

        if ($existRefund) {
            throw ValidationException::withMessages(['exist' => 'You Already Request For Refund']);
        }

        try {
            $uploadFile = "";
            if ($request->file('refund_file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('refund_file');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('refund_file');
                $uploadFile = 'doc1-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/student/document/', $uploadFile);
                $uploadFile = 'public/uploads/student/document/' . $uploadFile;
            }

            $WalletRefund = new WalletTransaction();
            $WalletRefund->user_id = $request->user_id;
            $WalletRefund->amount = $request->refund_amount;
            $WalletRefund->type = 'refund';
            $WalletRefund->payment_method = 'Wallet';
            $WalletRefund->note = $request->refund_note;
            $WalletRefund->file = $uploadFile;
            $WalletRefund->school_id = Auth::user()->school_id;
            $WalletRefund->save();

            return response()->json(['success' => 'success']);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function walletApproveRefund(Request $request)
    {
        try {
            $status = WalletTransaction::find($request->id);
            $user = User::find($status->user_id);
            $user->wallet_balance = $user->wallet_balance - $status->amount;
            $user->update();

            $status->status = 'approve';
            $status->updated_at = date('Y-m-d');
            $status->update();

            $compact['user_email'] = $user->email;
            $compact['create_date'] = $status->created_at;
            $compact['current_balance'] = $user->wallet_balance;
            $compact['refund_amount'] = $request->amount;

            @send_mail($user->email, $user->full_name, "wallet_refund", $compact);

            $this->sendNotification($user->id, $user->role_id, "Wallet Approve");

            Toastr::success('Approve Successful', 'Success');
            return redirect()->route('wallet.wallet-refund-request');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function walletRejectRefund(Request $request)
    {
        try {
            $status = WalletTransaction::find($request->id);
            $user = User::find($status->user_id);
           
            $status->status = 'reject';
            $status->note = $request->reject_note;
            $status->updated_at = date('Y-m-d');
            $status->update();

            $compact['user_email'] = $user->full_name;
            $compact['method'] = $status->payment_method;
            $compact['create_date'] = $status->created_by;
            $compact['current_balance'] = $user->wallet_balance;
            $compact['add_balance'] = $request->amount;
            $compact['reject_reason'] = $request->reject_note;

            @send_mail($user->email, $user->full_name, "wallet_reject", $compact);

            $this->sendNotification($user->id, $user->role_id, "Wallet Reject");

            Toastr::success('Reject Successful', 'Success');
            return redirect()->route('wallet.wallet-refund-request');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function walletReport()
    {
        return view('wallet::walletReport');
        
    }

    public function walletReportSearch(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('wallet.wallet-report')->withErrors($validator)->withInput();
        }

        try {
            $rangeArr = $request->date_range ? explode('-', $request->date_range) : [date('Y/m/d'), date('Y/m/d')];


            $date_from = \Carbon\Carbon::parse($rangeArr[0])->format('Y-m-d');
            $date_to = \Carbon\Carbon::parse($rangeArr[1])->format('Y-m-d');


            $walletReports = WalletTransaction::whereDate('created_at', '>=', $date_from)
                ->whereDate('created_at', '<=', $date_to)
                ->where('school_id', Auth::user()->school_id);

            $walletReports = $walletReports->when($request->type, function ($q) use ($request) {
                return $q->where('type', $request->type);
            })->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->get();
            return view('wallet::walletReport', compact('walletReports'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
        return view('wallet::walletReport');
    }

    public function myWallet()
    {
        try {
            $paymentMethods = SmPaymentMethhod::whereNotIn('method', ["Cash", "Wallet"])
                ->where('school_id', Auth::user()->school_id)
                ->get();
            
            $bankAccounts = SmBankAccount::where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $walletAmounts = WalletTransaction::where('user_id', Auth::user()->id)
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $stripe_info = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')
                ->where('school_id', Auth::user()->school_id)
                ->first();
            $razorpay_info = null;
            if (moduleStatusCheck('RazorPay')) {
                $razorpay_info = SmPaymentGatewaySetting::where('gateway_name', 'RazorPay')
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
            }

            return view('wallet::myWallet', compact('paymentMethods', 'bankAccounts', 'walletAmounts', 'stripe_info', 'razorpay_info'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    // Private Function

    private function getTotalWalletAmount($type, $status)
    {
        return $walletTotalAmounts = WalletTransaction::where('type', $type)
            ->where('status', $status)
            ->where('school_id', Auth::user()->school_id)
            ->sum('amount');
    }
    private function walletAmounts($type, $status)
    {
        $walletAmounts = WalletTransaction::where('type', $type)
            ->where('status', $status)
            ->where('school_id', Auth::user()->school_id)
            ->get();
        return $walletAmounts;
    }

    private function sendNotification($user_id, $role_id, $message)
    {
        $notification = new SmNotification;
        $notification->user_id = $user_id;
        $notification->role_id = $role_id;
        $notification->date = date('Y-m-d');
        $notification->message = $message;
        $notification->school_id = Auth::user()->school_id;
        $notification->academic_id = getAcademicId();
        $notification->save();
    }

    public function walletDipositDatatable(Request $request)
    {
        try{
            if ($request->ajax()) {
                $walletAmounts = WalletTransaction::query();
                $walletAmounts->with('userName')->where('school_id', Auth::user()->school_id);
                if($request->status){
                    $walletAmounts->where('status', $request->status);
                }
                return DataTables::of($walletAmounts)
                        ->addIndexColumn()
                        ->addColumn('amount', function($row){
                            return currency_format($row->amount);
                        })
                        ->addColumn('walletStatus', function($row){
                            if ($row->status == 'pending'){
                                $btn = '<button class="primary-btn small bg-warning text-white border-0">' . __('common.pending') . '</button>';
                            } elseif ($row->status == 'approve'){
                                $btn = '<button class="primary-btn small bg-success text-white border-0">' . __('wallet::wallet.approve') . '</button>';
                            } else{
                                $btn = '<button class="primary-btn small bg-danger text-white border-0">' . __('wallet::wallet.reject') . '</button>';
                            }
                            return $btn;
                        })
                        ->addColumn('walletNote', function($row){
                            if ($row->note){
                                $btn = '<a class="text-color walletNote'.$row->id.'" data-note="' . $row->note . '" onclick="noteModal(' . $row->id . ');" data-toggle="modal" data-target="#noteModal' . $row->id . '"  href="#">' . __('common.view') . '</a>';
                            }
                            return $btn;
                        })
                        ->addColumn('walletRejectNote', function($row){
                                $btn= '<td>' . ( @$row->reject_note ? '<a class="text-color rejectNote'.$row->id.'" data-rejectnote="' . $row->reject_note . '" onclick="rejectNote(' . $row->id . ');" data-toggle="modal" data-target="#rejectNote' . $row->id . '"  href="#">' . __('common.view') . '</a>' : '' ) . '</td>';
                                return $btn;
                        })
                        ->addColumn('showFile', function($row){
                            if ($row->file){
                                $pdf = $row->file ? explode('.', @$row->file) : ""." . "."";
                                if($pdf[1] == 'pdf'){
                                    return $btn = '<a class="text-color" href="'. url(@$row->file) .'" download>' . __('common.download') . ' <span class="pl ti-download"></span></a>';
                                }else{
                                    if (file_exists($row->file)){
                                        return $btn = '<a class="text-color showFile'.$row->id.' asset'.$row->id.'" onclick="showFile(' . $row->id . ');" data-asset="' . asset($row->file) . '" data-file="' . url(@$row->file) . '" data-toggle="modal" data-target="#showFile' . $row->id . '"  href="#">' . __('common.view') . '</a>';
                                    }
                                }
                            }
                        })
                        ->addColumn('createdDate', function($row){
                            return dateConvert($row->created_at);
                        })
                        ->addColumn('updatedDate', function($row){
                                $btn= '<td>' . ( @$row->status == 'approve' || $row->status == 'reject' ? dateConvert($row->updated_at) : '' ) . '</td>';
                                return $btn;
                        })
                        ->addColumn('action', function ($row){
                            $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                    <div class="dropdown-menu dropdown-menu-right">'.

                                    (userPermission('wallet.approve-payment') === true ? '<a class="dropdown-item approvePayment'.$row->id.' amount'.$row->id.'" onclick="approvePayment(' . $row->id . ');" data-user="' . $row->user_id . '" data-amount="' . $row->amount . '" data-toggle="modal" data-target="#approvePayment' . $row->id . '" href="#">' . __('wallet::wallet.approve') . '</a>' : '') .

                                    (userPermission('wallet.reject-payment') === true ? '<a class="dropdown-item rejectwalletPayment'.$row->id.' amount'.$row->id.'" onclick="rejectwalletPayment(' . $row->id . ');" data-user="' . $row->user_id . '" data-amount="' . $row->amount . '" data-toggle="modal" data-target="#rejectwalletPayment' . $row->id . '" href="#">' . __('wallet::wallet.reject') . '</a>' : '') .
                                '</div>
                                </div>';
                            return $btn;
                        })
                        ->rawColumns(['walletStatus', 'walletNote', 'walletRejectNote', 'showFile', 'updatedDate', 'action', 'date'])
                        ->make(true);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
