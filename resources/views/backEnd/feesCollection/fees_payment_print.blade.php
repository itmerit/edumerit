<!DOCTYPE html>
<html>
<head>
    <title>@lang('fees.fees_payment')</title>
    <style>
    
        .school-table-style {
            padding: 10px 0px!important;
        }
        .school-table-style tr th {
            font-size: 8px!important;
            text-align: left!important;
        }
        .school-table-style tr td {
            font-size: 9px!important;
            text-align: left!important;
            padding: 10px 0px!important;
        }
        .logo-image {
            width: 10%;
        }
    </style>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
</head>
<body>
 
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
    <table class="school-table school-table-style" cellspacing="0" width="100%">
        <tr>
                <td>@lang('student.student_name')</td>
                <td>{{$student->full_name}}</td>
                <td>@lang('fees.roll_number')</td>
                <td>{{$student->roll_no}}</td>
        </tr>
        <tr>
                <td> @lang('student.father_name')</td>
                <td>{{$student->parents->fathers_name}}</td>
                <td>@lang('common.class')</td>
                <td>{{$student->class->class_name}}</td>
        </tr>
        <tr>
                <td> @lang('common.section')</td>
                <td>{{$student->section->section_name}}</td>
                <td>@lang('student.admission_no')</td>
                <td>{{$student->admission_no}}</td>
        </tr>
    </table>
    <div class="text-center"> 
        <h4 class="text-center mt-1"><span>@lang('fees.fees_payment')</span></h4>
    </div>
	<table class="school-table school-table-style" cellspacing="0" width="100%">
        <thead>
            <tr align="center">
                <th>@lang('common.date')</th>
                <th>@lang('fees.fees_group')</th>
                <th>@lang('fees.fees_code')</th>
                <th>@lang('fees.mode')</th>
                <th>@lang('accounts.amount') ({{generalSetting()->currency_symbol}})</th>
                <th>@lang('fees.discount') ({{generalSetting()->currency_symbol}})</th>
                <th>@lang('fees.fine')({{generalSetting()->currency_symbol}})</th>
            </tr>
        </thead>

        <tbody>
            
            <tr align="center">
                <td>
                   
{{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}

                </td>
                <td>{{$group}}</td>
                <td>{{$payment->feesType->code}}</td>
                <td>
                @if($payment->payment_mode == "C")
                        {{'Cash'}}
                @elseif($payment->payment_mode == "Cq")
                    {{'Cheque'}}
                @else
                    {{'DD'}}
                @endif 
                </td>
                <td>{{$payment->amount}}</td>
                <td>{{$payment->discount_amount}}</td>
                <td>{{$payment->fine}}</td>
                <td></td>
            </tr>
            
        </tbody>
    </table>
</body>
</html>
