<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('/')}}/public/backEnd/css/report/bootstrap.min.css">
    <title>@lang('fees.student_fees')</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body{
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
        }
        .student_marks_table{
            padding-top: 15px;
        }
        .student_marks_table{
            width: 95%;
            margin: 10px auto 0 auto;
        }
        .text_center{
            text-align: center;
        }
        p{
            margin: 0;
            font-size: 12px;
            text-transform: capitalize;
        }
        ul{
            margin: 0;
            padding: 0;
        }
        li{
            list-style: none;
        }
        td {
            border: 1px solid var(--border_color);
            padding: .3rem;
            text-align: center;
        }
        th{
            border: 1px solid var(--border_color);
            text-transform: capitalize;
            text-align: center;
            padding: .5rem;
        }
        thead{
            font-weight:bold;
            text-align:center;
            color: #222;
            font-size: 10px
        }
        .custom_table{
            width: 100%;
        }
        table.custom_table thead th {
            padding-right: 0;
            padding-left: 0;
        }
        table.custom_table thead tr > th {
            border: 0;
            padding: 0;
        }
        table.custom_table thead tr th .fees_title{
            font-size: 12px;
            font-weight: 600;
            border-top: 1px solid #726E6D;
            padding-top: 10px;
            margin-top: 10px;
        }
        .border-top{
            border-top: 0 !important;
        }
        .custom_table th ul li {
            display: flex;
            justify-content: space-between;
        }
        .custom_table th ul li p {
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 12px;
        }
        tbody td p{
            text-align: right;
        }
        tbody td{
            padding: 0.3rem;
        }
        table{
            border-spacing: 10px;
            width: 95%;
            margin: auto;
        }
        .fees_pay{
            text-align: center;
        }
        .border-0{
            border: 0 !important;
        }
        .copy_collect{
            text-align: center;
            font-weight: 500;
            color: #000;
        }

        .copyies_text{
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
        .copyies_text li{
            text-transform: capitalize;
            color: #000;
            font-weight: 500;
            border-top: 1px dashed #ddd;
        }
        .school_name{
            font-size: 14px;
            font-weight: 600;
        }
        .print_btn{
            float:right;
            padding: 20px;
            font-size: 12px;
        }
        .fees_book_title{
            display: inline-block;
            width: 100%;
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
            border-top: 1px solid #ddd;
            padding: 5px;
        }
        .footer{
            width: 100%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            padding-top: 2%;
            /* position: fixed; */
            bottom: 30px;
            grid-gap: 35px;
            margin: auto;
            left: 0;
            right: 0;
            padding: 30px 35px 0 35px;
        }

        .footer .footer_widget .copyies_text{
            justify-content: space-between;
        }
    </style>

    <style>
        .page {
            /* width: 21cm; */
            min-height: 29.7cm;
            /* padding: 1cm; */
            margin: 1cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .subpage {
            /* padding: 1cm; */
            /* border: 5px red solid; */
            height: 200mm;
            /* outline: 2cm #FFEAEA solid; */
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }
        @media print {
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
    @if($invoiceSettings->per_th==1)
        <style>
            .footer .footer_widget{
                width: 100%;
            }
        </style>
    @elseif($invoiceSettings->per_th==2)
        <style>
            .footer .footer_widget{
                width: 100%;
            }
        </style>
    @elseif($invoiceSettings->per_th==3)
        <style>
            .footer .footer_widget{
                width: 30%;
            }
        </style>
    @endif
    <style type="text/css" media="print">
        @page { size: A4 landscape; }
    </style>
</head>
<script>
    var is_chrome = function () { return Boolean(window.chrome); }
    if(is_chrome){
        window.print();
        //  setTimeout(function(){window.close();}, 10000);
        //  give them 10 seconds to print, then close
    }else{
        window.print();
    }
</script>
<body onLoad="loadHandler();">

@php
    $setting = generalSetting();
    $in_no=1;
@endphp
@foreach ($students as $student)
    @php
        $invoice_no= $invoiceSettings->prefix !=null ? $invoiceSettings->prefix.$student->admission_no.$student->id.$in_no++ : (string) date('Ymd').$student->admission_no.$student->id.$in_no++;
          $parent =$student->studentDetail->parents;
          $fees_assigneds=$student->fees;
    @endphp
    <div class="page">
        <div class="subpage">
            <div class="student_marks_table print" >
                <table class="custom_table">
                    <thead>
                    <tr>
                        <!-- first header  -->
                        @if($invoiceSettings->per_th==1 || $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <th colspan="2">
                                <div style="float:left; width:30%">
                                    @if (file_exists($setting->logo))
                                        <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                                    @endif
                                </div>
                                <div style="float:right; width:70%; text-aligh:left">
                                    <h4 class="school_name">{{$setting->school_name}}</h4>
                                    <p>{{$setting->address}}</p>
                                </div>
                                <h4 class="fees_book_title" style="display:inline-block"></h4>
                                <ul>
                                    <li>
                                        <p>
                                            @lang('student.admission_no'): {{@$student->studentDetail->admission_no}}
                                        </p>
                                        <p>
                                            @lang('common.date'): {{date('d/m/Y')}}
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            @lang('student.student_name'): {{@$student->studentDetail->full_name}}
                                        </p>
                                        <p>@lang('bulkprint::bulk.invoice_no') : {{$invoice_no}}</p>
                                    </li>
                                    <li>
                                        <p>
                                            @lang('common.class'): {{@$student->class->class_name}}
                                        </p>
                                        <p>
                                            @lang('student.roll'):{{@$student->roll_no}}
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            @lang('common.section'): {{@$student->section->section_name}}
                                        </p>
                                        <p>
                                            @lang('common.group'): ___
                                        </p>
                                    </li>
                                </ul>
                            </th>
                            <!-- space  -->
                            <th class="border-0" rowspan="9"></th>
                        @endif

                    <!-- 2nd header  -->
                        @if($invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <th colspan="2">
                                <div style="float:left; width:30%">
                                    @if (file_exists($setting->logo))
                                        <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                                    @endif
                                </div>
                                <div style="float:right; width:70%; text-aligh:left">
                                    <h4 class="school_name">{{$setting->school_name}}</h4>
                                    <p>{{$setting->address}}</p>
                                </div>
                                <h4 class="fees_book_title" style="display:inline-block"></h4>
                                <ul>
                                    <li>
                                        <p>
                                            @lang('student.admission_no'): {{@$student->studentDetail->admission_no}}
                                        </p>
                                        <p>
                                            @lang('common.date'): {{date('d/m/Y')}}
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            @lang('student.student_name'): {{@$student->studentDetail->full_name}}
                                        </p>
                                        <p>@lang('bulkprint::bulk.invoice_no') : {{$invoice_no}}</p>
                                    </li>
                                    <li>
                                        <p>
                                            @lang('common.class'): {{@$student->class->class_name}}
                                        </p>
                                        <p>
                                            @lang('student.roll'):{{@$student->roll_no}}
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            @lang('common.section'): {{@$student->section->section_name}}
                                        </p>
                                        <p>
                                            @lang('common.group'): ___
                                        </p>
                                    </li>
                                </ul>
                            </th>


                        @endif
                    <!-- space  -->

                        <!-- 3rd header  -->
                        @if($invoiceSettings->per_th==3)
                            <th class="border-0" rowspan="9"></th>
                            <th colspan="2">
                                <div style="float:left; width:30%">
                                    @if (file_exists($setting->logo))
                                        <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                                    @endif
                                </div>
                                <div style="float:right; width:70%; text-aligh:left">
                                    <h4 class="school_name">{{$setting->school_name}}</h4>
                                    <p>{{$setting->address}}</p>
                                </div>
                                <h4 class="fees_book_title" style="display:inline-block"></h4>
                                <ul>
                                    <li>
                                        <p>
                                            @lang('student.admission_no'): {{@$student->studentDetail->admission_no}}
                                        </p>
                                        <p>
                                            @lang('common.date'): {{date('d/m/Y')}}
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            @lang('student.student_name'): {{@$student->studentDetail->full_name}}
                                        </p>
                                        <p>@lang('bulkprint::bulk.invoice_no') : {{$invoice_no}}</p>
                                    </li>
                                    <li>
                                        <p>
                                            @if(moduleStatusCheck('University'))
                                                @lang('university::un.faculty_department'):  {{ @$student->unFaculty->name .'('. @$student->unDepartment->name .')' }}
                                            @else  
                                             @lang('common.class'): {{@$student->class->class_name}}
                                            @endif
                                            
                                        </p>
                                        <p>
                                            @lang('student.roll'):{{@$student->roll_no}}
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            @if(moduleStatusCheck('University'))
                                                @lang('university::un.semester(label)'): {{ @$student->unSemester->name
                                                .'('. @$student->unSemesterLabel->name .')'
                                                }}
                                            @else 
                                                @lang('common.section'): {{@$student->section->section_name}}
                                            @endif
                                        </p>
                                        <p>
                                            @lang('common.group'): ___
                                        </p>
                                    </li>
                                </ul>
                            </th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <!-- first header  -->
                        @if($invoiceSettings->per_th==1 || $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <th>@lang('fees.fees_details')</th>
                            <th>@lang('accounts.amount') ({{generalSetting()->currency_symbol}})</th>
                            <!-- space  -->
                            <th class="border-0" rowspan="{{5+count($fees_assigneds)}}" ></th>
                        @endif

                    <!-- 2nd header  -->
                        @if($invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <th>@lang('fees.fees_details')</th>
                            <th>@lang('accounts.amount') ({{generalSetting()->currency_symbol}})</th>

                        @endif

                    <!-- 3rd header  -->
                        @if( $invoiceSettings->per_th==3)
                            <th class="border-0" rowspan="{{5+count($fees_assigneds)}}" ></th>
                            <th>@lang('fees.fees_details')</th>
                            <th>@lang('accounts.amount') ({{generalSetting()->currency_symbol}})</th>
                        @endif
                    </tr>
                    @php
                        $grand_total = 0;
                        $total_fine = 0;
                        $total_discount = 0;
                        $total_paid = 0;
                        $total_grand_paid = 0;
                        $total_balance = 0;
                        $totalpayable=0;
                    @endphp
                    @foreach($fees_assigneds as $fees_assigned)
                        @php
                            $grand_total += $fees_assigned->feesGroupMaster->amount;
                            $discount_amount = $fees_assigned->applied_discount;
                              $total_discount += $discount_amount;
                              $student_id = $fees_assigned->student_id;
                              //Sum of total paid amount of single fees type
                              $paid = \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id, $student->id)->sum('amount');
                              $total_grand_paid += $paid;
                              //Sum of total fine for single fees type
                            $fine = \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id, $student->id)->sum('fine');
                            $total_fine += $fine;
                            $total_paid = $discount_amount + $paid;
                        @endphp
                        <tr>
                        @php
                            $assigned_main_fees=number_format((float)@$fees_assigned->feesGroupMaster->amount, 2, '.', '');
                            $p_amount= $assigned_main_fees-$paid + $fine-$discount_amount;
                            // $totalpayable+=number_format((float)@$fees_assigned->feesGroupMaster->amount, 2, '.', '');
                            $totalpayable+=$p_amount;
                        @endphp
                        <!-- first td wrap  -->
                            @if($invoiceSettings->per_th==1 || $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                                {{-- @if ($p_amount>0) --}}
                                <td class="border-top">
                                    <p>
                                        {{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}}
                                        [{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}]
                                    </p>
                                    @if ($discount_amount>0)
                                        <p>
                                            <strong>
                                                @lang('fees.discount')(-)
                                            </strong>
                                        </p>
                                    @endif
                                    @if ($fine>0)
                                        <p>
                                            <strong>
                                                @lang('fees.fine')(+)
                                            </strong>
                                        </p>
                                    @endif
                                    @if ($paid>0)
                                        <p>
                                            <strong>
                                                @lang('fees.paid')(+)
                                            </strong>
                                        </p>
                                @endif
                                <!--<p> -->
                                    <!--  <strong>-->
                                <!--    @lang('fees.unpaid')-->
                                    <!--  </strong> -->
                                    <!--</p>-->
                                </td>
                                <td class="border-top" style="text-align: right">
                                    {{@$assigned_main_fees}}
                                    @if ($discount_amount>0)
                                        <br>
                                        {{currency_format($discount_amount)}}
                                    @endif
                                    @if ($fine>0)
                                        <br>
                                        {{currency_format($fine)}}
                                    @endif
                                    @if ($paid>0)
                                        <br>
                                        {{currency_format($paid)}}
                                    @endif
                                    <br>
                                <!--{{currency_format(@$p_amount)}}-->
                                </td>
                            @endif
                            {{-- @endif --}}


                        <!-- 2nd td wrap  -->
                            @if($invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                                {{-- @if ($p_amount>0) --}}
                                <td class="border-top">
                                    <p>
                                        {{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}}
                                        [{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}]
                                    </p>
                                    @if ($discount_amount>0)
                                        <p>
                                            <strong>
                                                @lang('fees.discount')(-)
                                            </strong>
                                        </p>
                                    @endif
                                    @if ($fine>0)
                                        <p>
                                            <strong>
                                                @lang('fees.fine')(+)
                                            </strong>
                                        </p>
                                    @endif
                                    @if ($paid>0)
                                        <p>
                                            <strong>
                                                @lang('fees.paid')(+)
                                            </strong>
                                        </p>
                                @endif
                                <!--<p> -->
                                    <!--  <strong>-->
                                <!--    @lang('fees.unpaid')-->
                                    <!--  </strong> -->
                                    <!--</p>-->
                                </td>

                                <td class="border-top" style="text-align: right">
                                    {{@$assigned_main_fees}}
                                    @if ($discount_amount>0)
                                        <br>
                                        {{currency_format($discount_amount)}}
                                    @endif
                                    @if ($fine>0)
                                        <br>
                                        {{currency_format($fine)}}
                                    @endif
                                    @if ($paid>0)
                                        <br>
                                        {{currency_format($paid)}}
                                    @endif
                                    <br>
                                <!--{{currency_format(@$p_amount)}}-->
                                </td>
                            @endif

                            {{-- @endif --}}

                        <!-- 3rd td wrap  -->
                            {{-- @if ($p_amount>0) --}}
                            @if($invoiceSettings->per_th==3)
                                <td class="border-top">
                                    <p>
                                        {{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}}
                                        [{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}]
                                    </p>
                                    @if ($discount_amount>0)
                                        <p>
                                            <strong>
                                                @lang('fees.discount')(-)
                                            </strong>
                                        </p>
                                    @endif
                                    @if ($fine>0)
                                        <p>
                                            <strong>
                                                @lang('fees.fine')(+)
                                            </strong>
                                        </p>
                                    @endif
                                    @if ($paid>0)
                                        <p>
                                            <strong>
                                                @lang('fees.paid')(+)
                                            </strong>
                                        </p>
                                @endif
                                <!--<p> -->
                                    <!--  <strong>-->
                                <!--    @lang('fees.unpaid')-->
                                    <!--  </strong> -->
                                    <!--</p>-->
                                </td>


                                <td class="border-top" style="text-align: right">
                                    {{@$assigned_main_fees}}
                                    @if ($discount_amount>0)
                                        <br>
                                        {{currency_format($discount_amount)}}
                                    @endif
                                    @if ($fine>0)
                                        <br>
                                        {{currency_format($fine)}}
                                    @endif
                                    @if ($paid>0)
                                        <br>
                                        {{currency_format($paid)}}
                                    @endif
                                    <br>
                                <!--{{currency_format(@$p_amount)}}-->
                                </td>
                            @endif
                            {{-- @endif --}}
                        </tr>
                    @endforeach
                    @php
                        $totalpayable=$totalpayable;
                        if ($totalpayable<0) {
                            $totalpayable=0.00;
                        } else {
                          $totalpayable=$totalpayable;
                        }
                    @endphp
                    <tr>
                        @if($invoiceSettings->per_th==1 || $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <td>
                                <p>
                                    <strong>
                                        @lang('fees.total_payable_amount')
                                    </strong>
                                </p>
                            </td>
                            <td style="text-align: right">
                                {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
                                <strong> {{ currency_format((float) $totalpayable)}} </strong>
                            </td>
                        @endif
                        @if($invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <td>
                                <p>
                                    <strong>
                                        @lang('fees.total_payable_amount')
                                    </strong>
                                </p>
                            </td>
                            <td style="text-align: right">
                                {{-- {{ currency_format((float) $unapplied_discount_amount)}}<br> --}}
                                <strong> {{ currency_format((float) $totalpayable)}} </strong>
                            </td>
                        @endif
                    <!-- 3rd td wrap  -->
                        @if($invoiceSettings->per_th==3)
                            <td>
                                <p>
                                    <strong>
                                        @lang('fees.total_payable_amount')
                                    </strong>
                                </p>
                            </td>
                            <td style="text-align: right">
                                {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
                                <strong> {{ number_format((float) $totalpayable, 2, '.', '')}} </strong>
                            </td>
                        @endif
                    </tr>

                    <tr>
                    </tr>

                    <tr>
                        @if($invoiceSettings->per_th==1 || $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <td colspan="2" >
                                @lang('fees.if_unpaid_admission_will_be_cancelled_after')
                            </td>
                        @endif
                    <!-- 2nd td wrap  -->
                        @if( $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <td colspan="2" >
                                @lang('fees.if_unpaid_admission_will_be_cancelled_after')
                            </td>
                        @endif
                    <!-- 3rd td wrap  -->
                        @if($invoiceSettings->per_th==3)
                            <td colspan="2" >
                                @lang('fees.if_unpaid_admission_will_be_cancelled_after')
                            </td>
                        @endif
                    </tr>

                    <tr>
                        @if($invoiceSettings->per_th==1 || $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                            <td colspan="2">
                                <p class="parents_num text_center">
                                    @lang('fees.parents_phone_number') :
                                    <span>
                                    {{@$parent->guardians_mobile}}
                                  </span>
                                </p>
                            </td>
                        @endif
                        @if( $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                        <!-- 2nd td wrap  -->
                            <td colspan="2">
                                <p class="parents_num text_center">
                                    @lang('fees.parents_phone_number') :
                                    <span>
                                    {{@$parent->guardians_mobile}}
                                  </span>
                                </p>
                            </td>
                        @endif
                        <!-- 3nd td wrap  -->
                        @if($invoiceSettings->per_th==3)
                            <td colspan="2">
                                <p class="parents_num text_center">
                                    @lang('fees.parents_phone_number') :
                                    <span>
                                    {{@$parent->guardians_mobile}}
                                  </span>
                                </p>
                            </td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
            <footer class="footer" >
                @if($invoiceSettings->per_th==1 || $invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                    <div class="footer_widget">
                        <ul class="copyies_text">
                            @if(!empty($invoiceSettings->footer_1) && $invoiceSettings->signature_p==1)  <li> {{$invoiceSettings->footer_1 !='' ? $invoiceSettings->footer_1 :''}}</li> @endif
                            @if(!empty($invoiceSettings->footer_2) && $invoiceSettings->signature_c==1)  <li> {{$invoiceSettings->footer_2 !='' ? $invoiceSettings->footer_2 :''}}</li> @endif
                            @if(!empty($invoiceSettings->footer_3) && $invoiceSettings->signature_o==1)  <li> {{$invoiceSettings->footer_3 !='' ? $invoiceSettings->footer_3 :''}}</li> @endif

                        </ul>
                        <p class="copy_collect">


                            @if(!empty($invoiceSettings->copy_s) && $invoiceSettings->c_signature_p==1)
                                {{ $invoiceSettings->copy_s }} @lang('common.copy')
                            @elseif(!empty($invoiceSettings->copy_o) && $invoiceSettings->c_signature_o==1)
                                {{ $invoiceSettings->copy_o }} @lang('common.copy')
                            @elseif(!empty($invoiceSettings->copy_c) && $invoiceSettings->c_signature_c==1)
                                {{ $invoiceSettings->copy_c }} @lang('common.copy')
                            @else
                                @lang('fees.parent_student_copy')
                            @endif

                        </p>
                    </div>
                @endif
                @if($invoiceSettings->per_th==2 || $invoiceSettings->per_th==3)
                    <div class="footer_widget">
                        <ul class="copyies_text">
                            @if(!empty($invoiceSettings->footer_1) && $invoiceSettings->signature_p==1)  <li> {{$invoiceSettings->footer_1 !='' ? $invoiceSettings->footer_1 :''}}</li> @endif
                            @if(!empty($invoiceSettings->footer_2) && $invoiceSettings->signature_c==1)  <li> {{$invoiceSettings->footer_2 !='' ? $invoiceSettings->footer_2 :''}}</li> @endif
                            @if(!empty($invoiceSettings->footer_3) && $invoiceSettings->signature_o==1)  <li> {{$invoiceSettings->footer_3 !='' ? $invoiceSettings->footer_3 :''}}</li> @endif
                        </ul>
                        <p class="copy_collect">


                            @if(!empty($invoiceSettings->copy_o) && $invoiceSettings->c_signature_o==1)
                                {{ $invoiceSettings->copy_o }} @lang('common.copy')
                            @elseif(!empty($invoiceSettings->copy_c) && $invoiceSettings->c_signature_c==1)
                                {{ $invoiceSettings->copy_c }} @lang('common.copy')
                            @elseif(!empty($invoiceSettings->copy_p) && $invoiceSettings->c_signature_p==1)
                                {{ $invoiceSettings->copy_p }} @lang('common.copy')
                            @else
                                @lang('fees.office_copy')
                            @endif
                        </p>
                    </div>
                @endif
                @if($invoiceSettings->per_th==3)
                    <div class="footer_widget">
                        <ul class="copyies_text">
                            @if(!empty($invoiceSettings->footer_1) && $invoiceSettings->signature_p==1)  <li> {{$invoiceSettings->footer_1 !='' ? $invoiceSettings->footer_1 :''}}</li> @endif
                            @if(!empty($invoiceSettings->footer_2) && $invoiceSettings->signature_c==1)  <li> {{$invoiceSettings->footer_2 !='' ? $invoiceSettings->footer_2 :''}}</li> @endif
                            @if(!empty($invoiceSettings->footer_3) && $invoiceSettings->signature_o==1)  <li> {{$invoiceSettings->footer_3 !='' ? $invoiceSettings->footer_3 :''}}</li> @endif
                        </ul>
                        <p class="copy_collect">
                            @if(!empty($invoiceSettings->copy_c) && $invoiceSettings->c_signature_c==1)
                                {{ $invoiceSettings->copy_c }} @lang('common.copy')

                            @else

                            @endif
                        </p>
                    </div>
                @endif

            </footer>
        </div>
    </div>
@endforeach
<script>
    function printInvoice() {
        window.print();
    }
</script>
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/jquery-3.2.1.slim.min.js"></script>
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/popper.min.js"></script>
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/bootstrap.min.js"></script>
</body>
</html>
