<!DOCTYPE html>
<html>
<head>
    <title>@lang('fees.fees_groups_details')</title>
    <style>
      
        .school-table-style {
            padding: 10px 0px!important;
        }
        .school-table-style tr th {
            font-size: 8px!important;
            text-align: left!important;
        }
        .school-table-style tr td {
            font-size: 7px!important;
            text-align: left!important;
            padding: 10px 0px!important;
        }
        .logo-image {
            width: 10%;
        }
        h1, h2, h3, h4, h5{
            font-size: 9px;
        }
    </style>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
</head>
<body>
<table style="width: 100%; table-layout: fixed; margin:20px;">
    <tr>
        <td   style=" padding-right:30px; " width="33%">
            <table style="width: 100%;">
                <tr>
                    
                    <td style="width: 30%"> 
                        <img src="{{url($setting->logo)}}" alt="{{url($setting->logo)}}"> 
                    </td> 
                    <td  style="width: 70%">  
                        <h3>{{$setting->school_name}}</h3>
                        <h4>{{$setting->address}}</h4>
                    </td> 
                </tr> 
            </table>
            <hr>
            <table class="school-table school-table-style" style="width: 100%; table-layout: fixed">
                <tr>
                    <td>Student Name</td>
                    <td>{{$student->full_name}}</td>
                    <td>Roll Number</td>
                    <td>{{$student->roll_no}}</td>
                </tr>
                <tr>
                    <td> Father's Name</td>
                    <td>{{$student->parents->fathers_name}}</td>
                    <td>Class</td>
                    <td>{{$student->class->class_name}}</td>
                </tr>
                <tr>
                    <td> Section</td>
                    <td>{{$student->section->section_name}}</td>
                    <td>Admission Number</td>
                    <td>{{$student->admission_no}}</td>
                </tr>
            </table>


            <div class="text-center"> 
                <h4 class="text-center mt-1"><span>Fees Details</span></h4>
            </div>
            <table class="table school-table-style" style="width: 100%; table-layout: fixed">
                <thead>
                    <tr align="center">
                        <th>Fees Group</th>
                        <th>Fees Code</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Amount ({{generalSetting()->currency_symbol}})</th>
                        <th>Payment ID</th>
                        <th>Mode</th>
                        <th>Date</th>
                        <th>Discount ({{generalSetting()->currency_symbol}})</th>
                        <th>Fine ({{generalSetting()->currency_symbol}})</th>
                        <th>Paid ({{generalSetting()->currency_symbol}})</th>
                        <th>Balance</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $grand_total = 0;
                        $total_fine = 0;
                        $total_discount = 0;
                        $total_paid = 0;
                        $total_grand_paid = 0;
                        $total_balance = 0;
                    @endphp
                    @foreach($fees_assigneds as $fees_assigned)
                        @php
                               $grand_total += $fees_assigned->feesGroupMaster->amount;
                           
                            
                        @endphp

                        @php
                            $discount_amount = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'discount_amount');
                            $total_discount += $discount_amount;
                            $student_id = $fees_assigned->student_id;
                        @endphp
                        @php
                            $paid = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                            $total_grand_paid += $paid;
                        @endphp
                        @php
                            $fine = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                            $total_fine += $fine;
                        @endphp
                            
                        @php
                            $total_paid = $discount_amount + $paid;
                        @endphp
                    <tr align="center">
                        <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}}</td>
                        <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}</td>
                        <td>
                            @if($fees_assigned->feesGroupMaster!="") {{$fees_assigned->feesGroupMaster->date != ""? dateConvert($fees_assigned->feesGroupMaster->date):''}}

                            @endif
                        </td>
                        <td>
                               @if($fees_assigned->feesGroupMaster->amount == $total_paid)
                                <span class="text-success">Paid</span>
                                @elseif($total_paid != 0)
                                <span class="text-warning">Partial</span>
                                @elseif($total_paid == 0)
                                <span class="text-danger">Unpaid</span>
                                @endif
                           
                        </td>
                        <td>
                            @php
                                 echo $fees_assigned->feesGroupMaster->amount;
                               
                            @endphp
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td> {{$discount_amount}} </td>
                        <td>{{$fine}}</td>
                        <td>{{$paid}}</td>
                        <td>
                            @php 

                                    $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                

                                $total_balance +=  $rest_amount;
                                echo $rest_amount;
                            @endphp
                        </td>
                    </tr>
                        @php 
                            $payments = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id, $fees_assigned->student_id);
                            $i = 0;
                        @endphp

                        @foreach($payments as $payment)
                        <tr align="center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                            <td>
                                @php
                                    $created_by = App\User::find($payment->created_by);
                                @endphp
                                <span>{{$payment->fees_type_id.'/'.$payment->id}}</span>
                            </td>
                            <td>
                            @if($payment->payment_mode == "C")
                                    {{'Cash'}}
                            @elseif($payment->payment_mode == "Cq")
                                {{'Cheque'}}
                            @else
                                {{'DD'}}
                            @endif 
                            </td>
                            <td> 
                                {{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}

                            </td>
                            <td>{{$payment->discount_amount}}</td>
                            <td>{{$payment->fine}}</td>
                            <td>{{$payment->amount}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    @endforeach
                    
                </tbody>
                <tfoot>
                    <tr align="center">
                        <th></th>
                        <th></th>
                        <th>Grand Total ({{generalSetting()->currency_symbol}})</th>
                        <th></th>
                        <th>{{$grand_total}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{$total_discount}}</th>
                        <th>{{$total_fine}}</th>
                        <th>{{$total_grand_paid}}</th>
                        <th>{{$total_balance}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </td>    
        <td   style=" padding-right:30px; " width="33%">
            <table style="width: 100%;">
                    <tr>
                        
                        <td style="width: 30%"> 
                            <img src="{{url($setting->logo)}}" alt="{{url($setting->logo)}}"> 
                        </td> 
                        <td  style="width: 70%">  
                            <h3>{{$setting->school_name}}</h3>
                            <h4>{{$setting->address}}</h4>
                        </td> 
                    </tr> 
            </table>
            <hr>
            <table class="school-table school-table-style" style="width: 100%; table-layout: fixed">
                <tr>
                    <td>Student Name</td>
                    <td>{{$student->full_name}}</td>
                    <td>Roll Number</td>
                    <td>{{$student->roll_no}}</td>
                </tr>
                <tr>
                    <td> Father's Name</td>
                    <td>{{$student->parents->fathers_name}}</td>
                    <td>Class</td>
                    <td>{{$student->class->class_name}}</td>
                </tr>
                <tr>
                    <td> Section</td>
                    <td>{{$student->section->section_name}}</td>
                    <td>Admission Number</td>
                    <td>{{$student->admission_no}}</td>
                </tr>
            </table>
        
        
            <div class="text-center"> 
                <h4 class="text-center mt-1"><span>Fees Details</span></h4>
            </div>
            <table class="table school-table-style" style="width: 100%; table-layout: fixed">
                <thead>
                    <tr align="center">
                        <th>Fees Group</th>
                        <th>Fees Code</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Amount ({{generalSetting()->currency_symbol}})</th>
                        <th>Payment ID</th>
                        <th>Mode</th>
                        <th>Date</th>
                        <th>Discount ({{generalSetting()->currency_symbol}})</th>
                        <th>Fine ({{generalSetting()->currency_symbol}})</th>
                        <th>Paid ({{generalSetting()->currency_symbol}})</th>
                        <th>Balance</th>
                    </tr>
                </thead>
        
                <tbody>
                    @php
                        $grand_total = 0;
                        $total_fine = 0;
                        $total_discount = 0;
                        $total_paid = 0;
                        $total_grand_paid = 0;
                        $total_balance = 0;
                    @endphp
                    @foreach($fees_assigneds as $fees_assigned)
                        @php
                               $grand_total += $fees_assigned->feesGroupMaster->amount;
                           
                            
                        @endphp
        
                        @php
                            $discount_amount = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'discount_amount');
                            $total_discount += $discount_amount;
                            $student_id = $fees_assigned->student_id;
                        @endphp
                        @php
                            $paid = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                            $total_grand_paid += $paid;
                        @endphp
                        @php
                            $fine = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                            $total_fine += $fine;
                        @endphp
                            
                        @php
                            $total_paid = $discount_amount + $paid;
                        @endphp
                    <tr align="center">
                        <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}}</td>
                        <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}</td>
                        <td>
                            @if($fees_assigned->feesGroupMaster!="")
                            
                    {{$fees_assigned->feesGroupMaster->date != ""? dateConvert($fees_assigned->feesGroupMaster->date):''}}
        
                            @endif
                        </td>
                        <td>
                                @if($fees_assigned->feesGroupMaster->amount == $total_paid)
                                <span class="text-success">Paid</span>
                                @elseif($total_paid != 0)
                                <span class="text-warning">Partial</span>
                                @elseif($total_paid == 0)
                                <span class="text-danger">Unpaid</span>
                                @endif
                           
                        </td>
                        <td>
                            @php
                                    echo $fees_assigned->feesGroupMaster->amount;
                               
                                
                            @endphp
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td> {{$discount_amount}} </td>
                        <td>{{$fine}}</td>
                        <td>{{$paid}}</td>
                        <td>
                            @php 
        
                                   $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                               
        
                                $total_balance +=  $rest_amount;
                                echo $rest_amount;
                            @endphp
                        </td>
                    </tr>
                        @php 
                            $payments = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id, $fees_assigned->student_id);
                            $i = 0;
                        @endphp
        
                        @foreach($payments as $payment)
                        <tr align="center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                            <td>
                                @php
                                    $created_by = App\User::find($payment->created_by);
                                @endphp
                                <span>{{$payment->fees_type_id.'/'.$payment->id}}</span>
                            </td>
                            <td>
                            @if($payment->payment_mode == "C")
                                    {{'Cash'}}
                            @elseif($payment->payment_mode == "Cq")
                                {{'Cheque'}}
                            @else
                                {{'DD'}}
                            @endif 
                            </td>
                            <td> 
                                {{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}
        
                            </td>
                            <td>{{$payment->discount_amount}}</td>
                            <td>{{$payment->fine}}</td>
                            <td>{{$payment->amount}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    @endforeach
                    
                </tbody>
                <tfoot>
                    <tr align="center">
                        <th></th>
                        <th></th>
                        <th>Grand Total ({{generalSetting()->currency_symbol}})</th>
                        <th></th>
                        <th>{{$grand_total}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{$total_discount}}</th>
                        <th>{{$total_fine}}</th>
                        <th>{{$total_grand_paid}}</th>
                        <th>{{$total_balance}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </td>
        <td   style=" padding-right:30px; " width="33%">

            <table style="width: 100%;">
                    <tr>
                        
                        <td style="width: 30%"> 
                            <img src="{{url($setting->logo)}}" alt="{{url($setting->logo)}}"> 
                        </td> 
                        <td  style="width: 70%">  
                            <h3>{{$setting->school_name}}</h3>
                            <h4>{{$setting->address}}</h4>
                        </td> 
                    </tr> 
            </table>
            <hr>
            <table class="school-table school-table-style" style="width: 100%; table-layout: fixed">
                <tr>
                    <td>Student Name</td>
                    <td>{{$student->full_name}}</td>
                    <td>Roll Number</td>
                    <td>{{$student->roll_no}}</td>
                </tr>
                <tr>
                    <td> Father's Name</td>
                    <td>{{$student->parents->fathers_name}}</td>
                    <td>Class</td>
                    <td>{{$student->class->class_name}}</td>
                </tr>
                <tr>
                    <td> Section</td>
                    <td>{{$student->section->section_name}}</td>
                    <td>Admission Number</td>
                    <td>{{$student->admission_no}}</td>
                </tr>
            </table>
        
        
            <div class="text-center"> 
                <h4 class="text-center mt-1"><span>Fees Details</span></h4>
            </div>
            <table class="table school-table-style" style="width: 100%; table-layout: fixed">
                <thead>
                    <tr align="center">
                        <th>Fees Group</th>
                        <th>Fees Code</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Amount ({{generalSetting()->currency_symbol}})</th>
                        <th>Payment ID</th>
                        <th>Mode</th>
                        <th>Date</th>
                        <th>Discount ({{generalSetting()->currency_symbol}})</th>
                        <th>Fine ({{generalSetting()->currency_symbol}})</th>
                        <th>Paid ({{generalSetting()->currency_symbol}})</th>
                        <th>Balance</th>
                    </tr>
                </thead>
        
                <tbody>
                    @php
                        $grand_total = 0;
                        $total_fine = 0;
                        $total_discount = 0;
                        $total_paid = 0;
                        $total_grand_paid = 0;
                        $total_balance = 0;
                    @endphp
                    @foreach($fees_assigneds as $fees_assigned)
                        @php
                                $grand_total += $fees_assigned->feesGroupMaster->amount;
                           
                            
                        @endphp
        
                        @php
                            $discount_amount = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'discount_amount');
                            $total_discount += $discount_amount;
                            $student_id = $fees_assigned->student_id;
                        @endphp
                        @php
                            $paid = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                            $total_grand_paid += $paid;
                        @endphp
                        @php
                            $fine = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                            $total_fine += $fine;
                        @endphp
                            
                        @php
                            $total_paid = $discount_amount + $paid;
                        @endphp
                    <tr align="center">
                        <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}}</td>
                        <td>{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}</td>
                        <td>
                            @if($fees_assigned->feesGroupMaster!="")
                                
                        {{$fees_assigned->feesGroupMaster->date != ""? dateConvert($fees_assigned->feesGroupMaster->date):''}}
        
                            @endif
                        </td>
                        <td>
                              @if($fees_assigned->feesGroupMaster->amount == $total_paid)
                                <span class="text-success">Paid</span>
                                @elseif($total_paid != 0)
                                <span class="text-warning">Partial</span>
                                @elseif($total_paid == 0)
                                <span class="text-danger">Unpaid</span>
                                @endif
                            
                        </td>
                        <td>
                            @php
                                   echo $fees_assigned->feesGroupMaster->amount;
                               
                                
                            @endphp
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td> {{$discount_amount}} </td>
                        <td>{{$fine}}</td>
                        <td>{{$paid}}</td>
                        <td>
                            @php 
        
                                  $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                               
        
                                $total_balance +=  $rest_amount;
                                echo $rest_amount;
                            @endphp
                        </td>
                    </tr>
                        @php 
                            $payments = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id, $fees_assigned->student_id);
                            $i = 0;
                        @endphp
        
                        @foreach($payments as $payment)
                        <tr align="center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                            <td>
                                @php
                                    $created_by = App\User::find($payment->created_by);
                                @endphp
                                <span>{{$payment->fees_type_id.'/'.$payment->id}}</span>
                            </td>
                            <td>
                            @if($payment->payment_mode == "C")
                                    {{'Cash'}}
                            @elseif($payment->payment_mode == "Cq")
                                {{'Cheque'}}
                            @else
                                {{'DD'}}
                            @endif 
                            </td>
                            <td> 
                                {{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}
        
                            </td>
                            <td>{{$payment->discount_amount}}</td>
                            <td>{{$payment->fine}}</td>
                            <td>{{$payment->amount}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    @endforeach
                    
                </tbody>
                <tfoot>
                    <tr align="center">
                        <th></th>
                        <th></th>
                        <th>Grand Total ({{generalSetting()->currency_symbol}})</th>
                        <th></th>
                        <th>{{$grand_total}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{$total_discount}}</th>
                        <th>{{$total_fine}}</th>
                        <th>{{$total_grand_paid}}</th>
                        <th>{{$total_balance}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
    
</body>
</html>
