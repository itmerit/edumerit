@extends('backEnd.master')
@section('title') 
@lang('reports.fees_statement')
@endsection
@section('mainContent')
@php  $setting = generalSetting();  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp 

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.fees_statement')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.fees_statement')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria') </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
            

                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees_statement_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 mt-30-md md_mb_20">
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md md_mb_20" id="select_section_div">
                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section')*" value="">@lang('common.select_section') *</option>
                                        
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md md_mb_20" id="select_student_div">
                                    <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="select_student" name="student">
                                        <option data-display="@lang('reports.select_student') *" value="">@lang('reports.select_student') *</option>
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_student_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('student'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('student') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
            
    @if(isset($fees_assigneds))
    <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('reports.fees_statement')</h3>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="student-meta-box">
                    <div class="student-meta-top staff-meta-top"></div>
                    <img class="student-meta-img img-100" src="{{asset($student->student_photo)}}" alt="">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                <div class="single-meta mt-20">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('common.name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{$student->full_name}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('student.father_name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{$student->parents !=""?$student->parents->fathers_name:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('common.mobile')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{$student->mobile}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('student.category')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{$student->category!=""?$student->category->category_name:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="offset-lg-2 col-lg-5 col-md-6">
                                <div class="single-meta mt-20">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                               @lang('common.class_sec')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @if($student->class !="" && $student->section !="")
                                                {{$student->class->class_name .'('.$student->section->section_name.')'}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('student.admission_no')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{$student->admission_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                               @lang('student.roll_no')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{$student->roll_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>             
    </div>
</div>

@endif

    </div>
</section>

@if(isset($fees_assigneds))

<section class="mt-20">
    <div class="container-fluid p-0">

        <div class="row">
            <div class="col-lg-12">
                <table class="table school-table-style" cellspacing="0" width="100%">
                    <thead>
                       
                        <tr>
                            <th>@lang('fees.fees_group')</th>
                            <th>@lang('reports.due_date')</th>
                            <th>@lang('common.status')</th>
                            <th>@lang('reports.amount') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('reports.payment_id')</th>
                            <th>@lang('reports.mode')</th>
                            <th>@lang('common.date')</th>
                            <th>@lang('reports.discount') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('reports.fine') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('reports.paid') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('reports.balance')</th>
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
                            $discount_amount = $fees_assigned->applied_discount;
                            $total_discount += $discount_amount;
                            $student_id = $fees_assigned->student_id;
                            $paid = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'amount', $fees_assigned->record_id);
                            $total_grand_paid += $paid;
                            $fine = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'fine', $fees_assigned->record_id);
                            $total_fine += $fine;
                            $total_paid = $discount_amount + $paid;
                        @endphp
                        <tr>
                            <td>
                                {{@$fees_assigned->feesGroupMaster->feesGroups->name}} / {{@$fees_assigned->feesGroupMaster->feesTypes->name}}
                            </td>
                            <td>
                                @if($fees_assigned->feesGroupMaster !="")
                                    {{$fees_assigned->feesGroupMaster->date != ""? dateConvert($fees_assigned->feesGroupMaster->date):''}}
                                @endif
                            </td>
                            <td>
  
                                @php
                                    $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                    
                                    $total_balance +=  $rest_amount;
                                    
                                    $balance_amount = number_format($rest_amount+$fine, 2, '.', '');
                                   
                                @endphp
                                
                                @if($balance_amount == 0)
                                    <button class="primary-btn small bg-success text-white border-0">@lang('reports.paid')</button>
                                @elseif($paid != 0)
                                    <button class="primary-btn small bg-warning text-white border-0">@lang('reports.partial')</button>
                                @elseif($paid == 0)
                                    <button class="primary-btn small bg-danger text-white border-0">@lang('reports.unpaid')</button>
                                @endif
                            </td>
                            <td>{{$fees_assigned->feesGroupMaster->amount}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$discount_amount}}</td>
                            <td>{{$fine}}</td>
                            <td>{{$paid}}</td>
                            <td>
                            @php
                                    $rest_amount = $fees_assigned->fees_amount;
                                    $total_balance +=  $rest_amount;
                                    echo $balance_amount;
                                @endphp
                            </td>
                        </tr>
                            @php
                                $payments = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id, $fees_assigned->student_id, $fees_assigned->record_id);
                                $i = 0;
                            @endphp
                            @foreach($payments as $payment)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">
                                    <img src="{{asset('public/backEnd/img/table-arrow.png')}}">
                                </td>
                                <td>
                                    @php
                                        $created_by = App\User::find($payment->created_by);
                                    @endphp
                                    @if($created_by != "")
                                        <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.$created_by->full_name}}">{{$payment->fees_type_id.'/'.$payment->id}}</a>
                                </td>
                                    @endif
                                <td>{{$payment->payment_mode}}</td>
                                <td class="nowrap">{{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}</td>
                                <td class="text-center">{{$payment->discount_amount}}</td>
                                <td>
                                    {{$payment->fine}}
                                    @if($payment->fine!=0)
                                    @if (strlen($payment->fine_title) > 14)
                                    <spna class="text-danger nowrap" title="{{$payment->fine_title}}">
                                        ({{substr($payment->fine_title, 0, 15) . '...'}})
                                    </spna>
                                    @else
                                    @if ($payment->fine_title=='')
                                    {{$payment->fine_title}}
                                    @else
                                    <spna class="text-danger nowrap">
                                        ({{$payment->fine_title}})
                                    </spna>
                                    @endif
                                    @endif
                                    @endif
                                </td>
                                <td>{{$payment->amount}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                            <th></th>
                            <th></th>
                            <th>@lang('reports.grand_total') ({{generalSetting()->currency_symbol}})</th>
                            <th></th>
                            <th>{{ currency_format($grand_total) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{ currency_format($total_discount) }}</th>
                            <th>{{currency_format($total_fine) }}</th>
                            <th>{{ currency_format($total_grand_paid) }}</th>
                                @php
                                    $show_balance=$grand_total+$total_fine-$total_discount;
                                @endphp
                            <th>{{ currency_format($show_balance-$total_grand_paid) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

@endif


@endsection
