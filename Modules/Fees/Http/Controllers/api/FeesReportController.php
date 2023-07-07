<?php

namespace Modules\Fees\Http\Controllers\api;

use App\SmClass;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Fees\Entities\FmFeesInvoice;
use Illuminate\Contracts\Support\Renderable;

class FeesReportController extends Controller
{
    public function dueFeesView()
    {
        try {
            $data = $this->allClass();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function dueFeesSearch(Request $request)
    {
        try {
            $data = $this->feesSearch($request->merge(['due'=>true]));
            $classes = $data['classes'];
            $feesDues = $data['fees_dues']->map(function ($value) {
                $amount = $value->Tamount;
                $waiver = $value->Tweaver;
                $fine = $value->Tfine;
                $paid_amount = $value->Tpaidamount;
                $sub_total = $value->Tsubtotal;
                $balance = $sub_total - $paid_amount + $fine;
                if($balance != 0){
                    return [
                        'admission_no' => $value->studentInfo->admission_no ? $value->studentInfo->admission_no : null,
                        'roll_no' => $value->recordDetail->roll_no ? $value->recordDetail->roll_no: null,
                        'name' => $value->studentInfo->full_name ? $value->studentInfo->full_name : null,
                        'due_date' => dateConvert($value->due_date),
                        'amount' => $amount,
                        'paid' => $paid_amount,
                        'waiver' => $waiver,
                        'fine' => $fine,
                        'balance' => $balance,
                    ];
                }
            });
            return response()->json(['feesDues'=>$feesDues,'classes'=> $classes]);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Error']);
        }
    }

    public function fineReportView()
    {
        try {
            $data = $this->allClass();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    public function fineReportSearch(Request $request)
    {
        try {
            $data = $this->feesSearch($request->merge(['all'=>true]));
            $classes = $data['classes'];
            $fineReport = $data['fees_dues']->where('Tfine','!=', 0)
            ->map(function ($value) {
                return [
                    'admission_no' => $value->studentInfo->admission_no ? $value->studentInfo->admission_no: null,
                    'roll_no' => $value->recordDetail->roll_no ? $value->recordDetail->roll_no: null,
                    'name' => $value->studentInfo->full_name ? $value->studentInfo->full_name : null,
                    'due_date' => dateConvert($value->due_date),
                    'fine' => $value->Tfine,
                ];
            });
            $totalFine = $fineReport->sum('fine');
            return response()->json(['fineReport'=>$fineReport, 'totalFine'=>$totalFine, 'classes'=> $classes]);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    public function paymentReportView()
    {
        try {
            $data = $this->allClass();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    public function paymentReportSearch(Request $request)
    {
        try {
            $data = $this->feesSearch($request->merge(['all'=>true]));
            $classes = $data['classes'];
            $paymentReport = $data['fees_dues']->where('Tpaidamount','!=', 0)
            ->map(function ($value) {
                return [
                    'admission_no' => $value->studentInfo->admission_no ? $value->studentInfo->admission_no: null,
                    'roll_no' => $value->recordDetail->roll_no ? $value->recordDetail->roll_no: null,
                    'name' => $value->studentInfo->full_name ? $value->studentInfo->full_name : null,
                    'due_date' => dateConvert($value->due_date),
                    'paid' => $value->Tpaidamount,
                ];
            });
            $totalPayment = $paymentReport->sum('paid_amount');
            return response()->json(['paymentReport'=>$paymentReport, 'totalPayment'=>$totalPayment, 'classes'=> $classes]);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    public function balanceReportView(Request $request)
    {
        try {
            $data = $this->allClass();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    public function balanceReportSearch(Request $request)
    {
        try {
            $data = $this->feesSearch($request->merge(['all'=>true]));
            $classes = $data['classes'];
            $balanceReport = $data['fees_dues']->map(function ($value) {
                $fine = $value->Tfine;
                $paid_amount = $value->Tpaidamount;
                $sub_total = $value->Tsubtotal;
                $balance = $sub_total - $paid_amount + $fine;
                if($balance != 0){
                    return [
                        'admission_no' => $value->studentInfo->admission_no ? $value->studentInfo->admission_no: null,
                        'roll_no' => $value->recordDetail->roll_no ? $value->recordDetail->roll_no: null,
                        'name' => $value->studentInfo->full_name ? $value->studentInfo->full_name : null,
                        'due_date' => dateConvert($value->due_date),
                        'balance' => $balance,
                    ];
                }
            });
            $totalBalance = $balanceReport->sum('paid_amount');
            return response()->json(['balanceReport'=>$balanceReport, 'totalBalance'=>$totalBalance, 'classes'=> $classes]);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    public function waiverReportView(Request $request)
    {
        try {
            $data = $this->allClass();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    public function waiverReportSearch(Request $request)
    {
        try {
            $data = $this->feesSearch($request->merge(['all'=>true]));
            $classes = $data['classes'];
            $waiverReport = $data['fees_dues']->where('Tweaver','!=',0)->map(function ($value) {
                return [
                    'admission_no' => $value->studentInfo->admission_no ? $value->studentInfo->admission_no: null,
                    'roll_no' => $value->recordDetail->roll_no ? $value->recordDetail->roll_no: null,
                    'name' => $value->studentInfo->full_name ? $value->studentInfo->full_name : null,
                    'due_date' => dateConvert($value->due_date),
                    'waiver' => $value->Tweaver,
                ];
            });
            $totalWaiver = $waiverReport->sum('weaver');
            return response()->json(['waiverReport'=>$waiverReport, 'totalWaiver'=>$totalWaiver, 'classes'=> $classes]);
        } catch (\Exception $e) {
            return response()->json(['mesage'=>'Error']);
        }
    }

    private function allClass()
    {
            $data['classes'] = SmClass::where('school_id', auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();
            return $data;
    }

    private function feesSearch($request)
    {
            $data['classes'] = SmClass::where('school_id', auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get();
            $rangeArr = $request->date_range ? explode('-', $request->date_range) : [date('m/d/Y'), date('m/d/Y')];
            if ($request->date_range) {
                $date_from = date('Y-m-d', strtotime(trim($rangeArr[0])));
                $date_to = date('Y-m-d', strtotime(trim($rangeArr[1])));
            }

            $data['fees_dues']  = FmFeesInvoice::when($request->class, function ($query) use ($request) {
                        $query->where('class_id', $request->class);
                    })
                    ->when($request->section, function ($query) use ($request) {
                        $query->whereHas('recordDetail', function($q) use($request){
                            return $q->where('section_id', $request->section);
                        });
                    })
                    ->when($request->student, function ($query) use ($request) {
                        $query->whereHas('recordDetail', function($q) use($request){
                            return $q->where('id', $request->student);
                        });
                    })
                    ->when($request->due, function ($query) use ($date_from, $date_to) {
                        $query->whereBetween('due_date', [$date_from, $date_to]);
                    })
                    ->when($request->all, function ($query) use ($date_from, $date_to) {
                        $query->whereDate('created_at', '>=', $date_from)
                            ->whereDate('created_at', '<=', $date_to);
                    })
                    ->where('school_id', auth()->user()->school_id)
                    ->where('academic_id', getAcademicId())
                    ->get();

            return $data;
    }
}