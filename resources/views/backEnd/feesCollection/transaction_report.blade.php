@extends('backEnd.master')
@section('title')
    @lang('fees.collection_report')
@endsection
@section('mainContent')
    <style>
        table.dataTable tfoot th, table.dataTable tfoot td {
            padding: 10px 30px 6px 30px;
        }
    </style>
    @php
        $setting = generalSetting();
        if(!empty($setting->currency_symbol)){
            $currency = $setting->currency_symbol;
        }else{
            $currency = '$';
        }
    @endphp
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('fees.collection_report')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('fees.fees_collection')</a>
                    <a href="#">@lang('fees.collection_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'transaction_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            @if(moduleStatusCheck('University'))
                                @includeIf('university::common.session_faculty_depart_academic_semester_level',  ['hide'=>['USUB'],'required'=> ['US','UF','UD','UA','USN','US','USL']])
                                <div class="col-md-3 mt-20">
                                    <input placeholder="" class="primary_input_field primary_input_field form-control"
                                           type="text" name="date_range" value="">
                                </div>
                            @else
                                <div class="col-lg-3 mt-20">
                                    <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                            id="select_class" name="class">
                                        <option data-display="@lang('common.select_class')"
                                                value="">@lang('common.select_class')</option>
                                        @foreach($classes as $class)
                                            <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-20" id="select_section_div">
                                    <select class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                            id="select_section" name="section">
                                        <option data-display="@lang('common.select_section')"
                                                value="">@lang('common.select_section')</option>
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style"
                                             src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    <!-- @if ($errors->has('section'))
                                        <span class="text-danger invalid-select" role="alert">
{{ $errors->first('section') }}
                                        </span>

                                    @endif -->
                                </div>
                                <div class="col-md-6 mt-20">
                                    <input placeholder="" class="primary_input_field primary_input_field form-control"
                                           type="text" name="date_range" value="">
                                </div>
                            @endif
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            @if(isset($fees_payments))
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('fees.fees_collection_details')|
                                        <strong>{{dateConvert($date_from). "-" . dateConvert($date_to)}} </strong></h3>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-35">
                            <div class="col-lg-12">
                                @if (moduleStatusCheck('University'))
                                    <x-table>
                                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th> @lang('student.admission_no')</th>
                                                <th> @lang('common.name')</th>
                                                <th> @lang('university::un.installment')</th>
                                                <th>@lang('fees.mode')</th>
                                                <th>@lang('fees.payment_date')</th>
                                                <th>@lang('fees.paid_amount') ({{generalSetting()->currency_symbol}})
                                                </th>
                                                <th>@lang('fees.discount') ({{generalSetting()->currency_symbol}})</th>
                                                <th>@lang('common.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $totalPaidAmountGrup = 0;
                                                $totalPaidAmount = 0;
                                                $totalDiscount = 0;
                                                $totalDiscountGrup = 0;
                                            @endphp
                                            @foreach($fees_payments as $fees_payment)
                                                @if (!count($fees_payment->payments))
                                                    <tr>
                                                        <td>{{$fees_payment->recordDetail->studentDetail !=""?$fees_payment->recordDetail->studentDetail->admission_no:""}}</td>
                                                        <td>{{$fees_payment->recordDetail->studentDetail !=""?$fees_payment->recordDetail->studentDetail->full_name:""}}</td>
                                                        <td>
                                                            {{@$fees_payment->installment->title}}
                                                        </td>
                                                        <td>
                                                            {{$fees_payment->payment_mode}}
                                                        </td>
                                                        <td>
                                                            {{@dateConvert($fees_payment->payment_date)}}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $totalPaidAmountGrup += $fees_payment->amount;
                                                            @endphp
                                                            {{$fees_payment->amount}}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $totalDiscountGrup += $fees_payment->discount_amount;
                                                            @endphp
                                                            {{$fees_payment->discount_amount}}
                                                        </td>

                                                        <td>
                                                            <div class="dropdown CRM_dropdown">
                                                                <button type="button" class="btn dropdown-toggle"
                                                                        data-toggle="dropdown">
                                                                    @lang('common.select')
                                                                </button>
                                                                @if(userPermission(117))
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item"
                                                                           href="{{route('fees_collect_student_wise', [$fees_payment->recordDetail->id])}}">@lang('common.view')</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @foreach($fees_payment->payments as $payment)
                                                    <tr>
                                                        <td>{{$fees_payment->recordDetail->studentDetail !=""?$fees_payment->recordDetail->studentDetail->admission_no:""}}</td>
                                                        <td>{{$fees_payment->recordDetail->studentDetail !=""?$fees_payment->recordDetail->studentDetail->full_name:""}}</td>
                                                        <td>
                                                            {{@$fees_payment->installment->title}}
                                                        </td>
                                                        <td>
                                                            {{$payment->payment_mode}}
                                                        </td>
                                                        <td>
                                                            {{@dateConvert($payment_date)}}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $totalPaidAmount += $payment->paid_amount;
                                                            @endphp
                                                            {{$payment->paid_amount}}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $totalDiscount += $payment->discount_amount;
                                                            @endphp
                                                            {{$payment->discount_amount}}
                                                        </td>

                                                        <td>
                                                            <div class="dropdown CRM_dropdown">
                                                                <button type="button" class="btn dropdown-toggle"
                                                                        data-toggle="dropdown">
                                                                    @lang('common.select')
                                                                </button>
                                                                @if(userPermission('fees_collect_student_wise'))
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item"
                                                                           href="{{route('fees_collect_student_wise', [$fees_payment->recordDetail->id])}}">@lang('common.view')</a>
                                                                        <a class="dropdown-item" target="_blank"
                                                                           href="{{route('university.viewPaymentReceipt',[$payment->id])}}">
                                                                            @lang('fees.receipt')
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>@lang('fees.grand_total') ({{generalSetting()->currency_symbol}})
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td> {{currency_format($totalPaidAmountGrup + $totalPaidAmount)}}</td>
                                                <td> {{currency_format($totalDiscountGrup + $totalDiscount)}}</td>
                                                <td></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </x-table>
                                @elseif(directFees())
                                    <x-table>
                                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th> @lang('student.admission_no')</th>
                                                <th> @lang('common.name')</th>
                                                <th> @lang('fees.installment')</th>
                                                <th>@lang('fees.mode')</th>
                                                <th>@lang('fees.payment_date')</th>
                                                <th>@lang('fees.paid_amount') ({{generalSetting()->currency_symbol}})
                                                </th>
                                                <th>@lang('fees.discount') ({{generalSetting()->currency_symbol}})</th>
                                                <th>@lang('common.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $totalPaidAmountGrup = 0;
                                                $totalPaidAmount = 0;
                                                $totalDiscount = 0;
                                                $totalDiscountGrup = 0;
                                            @endphp
                                            @foreach($fees_payments as $fees_payment)

                                                <tr>
                                                    <td>{{@$fees_payment->installmentAssign->recordDetail->studentDetail->admission_no}}</td>
                                                    <td>{{$fees_payment->installmentAssign->recordDetail->studentDetail->full_name}}</td>
                                                    <td>
                                                        {{@$fees_payment->installmentAssign->installment->title}}
                                                    </td>
                                                    <td>
                                                        {{$fees_payment->payment_mode}}
                                                    </td>
                                                    <td>
                                                        {{@dateConvert($fees_payment->payment_date)}}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $totalPaidAmountGrup += $fees_payment->amount;
                                                        @endphp
                                                        {{$fees_payment->amount}}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $totalDiscountGrup += $fees_payment->installmentAssign->discount_amount;
                                                        @endphp
                                                        {{$fees_payment->installmentAssign->discount_amount}}
                                                    </td>

                                                    <td>
                                                        <div class="dropdown CRM_dropdown">
                                                            <button type="button" class="btn dropdown-toggle"
                                                                    data-toggle="dropdown">
                                                                @lang('common.select')
                                                            </button>
                                                            @if(userPermission(117))
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item"
                                                                       href="{{route('fees_collect_student_wise', [$fees_payment->installmentAssign->recordDetail->id])}}">@lang('common.view')</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>

                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>@lang('fees.grand_total') ({{generalSetting()->currency_symbol}})
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td> {{currency_format($totalPaidAmountGrup + $totalPaidAmount)}}</td>
                                                <td> {{currency_format($totalDiscountGrup + $totalDiscount)}}</td>
                                                <td></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </x-table>
                                @else
                                    <x-table>
                                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>@lang('fees.payment_id')</th>
                                                <th>@lang('common.date')</th>
                                                <th>@lang('common.name')</th>
                                                <th>@lang('common.class')</th>
                                                <th>@lang('fees.fees_type')</th>
                                                <th>@lang('fees.mode')</th>
                                                <th>@lang('fees.amount')</th>
                                                <th>@lang('fees.fine')</th>
                                                <th>@lang('fees.total')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $grand_amount = 0;
                                                $grand_total = 0;
                                                $grand_discount = 0;
                                                $grand_fine = 0;
                                                $total = 0;
                                            @endphp
                                            @foreach($fees_payments as $students)
                                                @foreach($students as $key=>$fees_payment)
                                                    @php
                                                        if(is_array($fees_payment)){
                                                            $fees_payment = $fees_payment[$key];
                                                        }

                                                    @endphp
                                                    @php $total = 0; @endphp
                                                    @if($fees_payment->recordDetail)
                                                        <tr>
                                                            <td>
                                                                {{$fees_payment->fees_type_id.'/'.$fees_payment->id}}
                                                            </td>
                                                            <td data-sort="{{strtotime(@$fees_payment->payment_date)}}">
                                                                {{$fees_payment->payment_date != ""? dateConvert($fees_payment->payment_date):''}}

                                                            </td>
                                                            <td>{{@$fees_payment->recordDetail->studentDetail ? $fees_payment->recordDetail->studentDetail->full_name:""}}</td>
                                                            <td>
                                                                @if(@$fees_payment->recordDetail->studentDetail && @$fees_payment->recordDetail->class)
                                                                    {{$fees_payment->recordDetail->class->class_name}}
                                                                @endif
                                                            </td>
                                                            <td>{{$fees_payment->feesType!=""?$fees_payment->feesType->name:""}}</td>
                                                            <td>
                                                                {{@$fees_payment->payment_mode}}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $total =  $total + $fees_payment->amount;
                                                                    $grand_amount =  $grand_amount + $fees_payment->amount;
                                                                    echo currency_format($fees_payment->amount);
                                                                @endphp
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $total =  $total + $fees_payment->fine;
                                                                    $grand_fine =  $grand_fine + $fees_payment->fine;
                                                                    echo currency_format($fees_payment->fine);
                                                                @endphp
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $grand_total =  $grand_total + $total;
                                                                    echo currency_format($total);
                                                                @endphp
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <td>@lang('fees.grand_total') ({{generalSetting()->currency_symbol}})</td>
                                            <th>{{currency_format($grand_amount)}}</th>
                                            <th>{{currency_format($grand_fine)}}</th>
                                            <th>{{currency_format($grand_total)}}</th>
                                            </tfoot>
                                        </table>
                                    </x-table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_range_picker_css_js')

