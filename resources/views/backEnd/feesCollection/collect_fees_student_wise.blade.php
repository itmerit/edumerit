@extends('backEnd.master')
@section('title') 
@lang('fees.collect_fees')
@endsection
@section('mainContent')
@php 
$setting = generalSetting(); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } 
$total_fees = 0;
$total_due = 0;
$total_paid = 0;
$total_disc = 0;
$total_balance = 0;
$record = $student;
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.fees_collection')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('fees.fees_collection')</a>
                <a href="{{route('collect_fees')}}">@lang('fees.collect_fees')</a>
                <a href="{{route('fees_collect_student_wise', [$student->id])}}">@lang('fees.student_wise')</a>
            </div>
        </div>
    </div>
</section>
<section class="student-details mb-40">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-30">@lang('fees.student_fees')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="student-meta-box">
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
                                                {{@$student->studentDetail->full_name}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(moduleStatusCheck('University'))
                                                @lang('university::un.semester_label')
                                                @else 
                                                @lang('student.father_name')
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @if(moduleStatusCheck('University'))
                                                {{@$student->unSemesterLabel->name }} 
                                                @else 
                                                {{@$student->studentDetail->parents != ""? @$student->studentDetail->parents->fathers_name:""}}
                                                @endif  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @lang('fees.mobile')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                {{@$student->studentDetail->mobile}}
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
                                                {{@$student->studentDetail->category !=""?@$student->studentDetail->category->category_name:""}}
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
                                                @if(moduleStatusCheck('University'))
                                                @lang('university::un.department')
                                                @else
                                               @lang('common.class_sec')
                                               @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name"> 
                                                @if(moduleStatusCheck('University'))
                                                    {{@$student->unDepartment->name}}
                                                @else 
                                                     {{@$student->class->class_name .'('.@$student->section->section_name.')'}}
                                                
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
                                                {{@$student->studentDetail->admission_no}}
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
                                                {{@$student->roll_no}}
                                  
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
</section>
<input type="hidden" id="url" value="{{URL::to('/')}}">
<input type="hidden" id="student_id" value="{{@$student->id}}">
<section class="">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="d-flex justify-content-between">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('fees.add_fees')</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                
                <div class="table-responsive">
                @if(moduleStatusCheck('University'))

                <x-table>
                    <table id="" class="table school-table-style-parent-fees" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <td class="text-right" colspan="14">
                                    <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-lg" title="@lang('fees.add_fees')" href="{{route('university.un-total-fees-modal', [$student->id])}}"0>
                                        <i class="ti-plus pr-2"></i> @lang('fees.add_fees') 
                                </a>
                                
                                    <a href="" id="fees_groups_invoice_print_button" class="primary-btn small fix-gr-bg" target="">
                                        <i class="ti-printer pr-2"></i>
                                        @lang('fees.invoice_print')
                                    </a>
                                </td>
                            </tr>
    
                            <tr>
                                {{-- <th class="nowrap">#</th> --}}
                                <th class="nowrap"># @lang('university::un.installment') </th>
                                <th class="nowrap">@lang('fees.amount') ({{@generalSetting()->currency_symbol}})</th>
                                <th class="nowrap">@lang('common.status')</th>
                                <th class="nowrap">@lang('fees.due_date') </th>
                                <th class="nowrap">@lang('fees.payment_ID')</th>
                                <th class="nowrap">@lang('fees.mode')</th>
                                <th class="nowrap">@lang('university::un.payment_date')</th>
                                <th class="nowrap">@lang('fees.discount') ({{@generalSetting()->currency_symbol}})</th>
                                <th class="nowrap">@lang('fees.paid') ({{@generalSetting()->currency_symbol}})</th>
                                <th class="nowrap">@lang('fees.balance')</th>
                                <th class="nowrap">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
    
                           
                              @foreach($feesInstallments as $key=> $feesInstallment )
    
                                @php 
                                $total_fees += discountFeesAmount($feesInstallment->id); 
                                $total_paid += $feesInstallment->paid_amount;
                                $total_disc += $feesInstallment->discount_amount;
                                $total_balance += discountFeesAmount($feesInstallment->id) - ( $feesInstallment->paid_amount );
                                @endphp 
    
                              <tr>
                                    <td>
                                        <input type="checkbox" id="fees_group.{{$feesInstallment->id}}" class="common-checkbox fees-groups-print" name="fees_group[]" value="{{$feesInstallment->id}}">
                                        <label for="fees_group.{{$feesInstallment->id}}"></label>
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        {{@$feesInstallment->installment->title}}
                                    </td>
                                    {{-- <td>{{@$feesInstallment->installment->title}}</td> --}}
                                    <td> 
                                        @if($feesInstallment->discount_amount > 0)
                                        <del>  {{$feesInstallment->amount}}  </del>
                                          {{$feesInstallment->amount - $feesInstallment->discount_amount}}
                                          @else 
                                           {{$feesInstallment->amount}}
                                        @endif 
                                      </td>
                                      <td>
                                          @if($feesInstallment->active_status == 1)
                                          <button class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
                                          @elseif( $feesInstallment->active_status == 2) 
                                          <button class="primary-btn small bg-warning text-white border-0">@lang('fees.partial')</button>
                                          @else 
                                          <button class="primary-btn small bg-danger text-white border-0">@lang('fees.unpaid')</button>
                                          @endif 
                                      </td>
                                    <td>{{@dateConvert($feesInstallment->due_date)}}</td>
                                    <td>
                                      @if($feesInstallment->active_status == 1)
                                        @if (moduleStatusCheck('University'))
                                            <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$feesInstallment->user->full_name}}">
                                                {{@universityFeesInvoice($feesInstallment->invoice_no)}}
                                            </a>
                                        @else
                                            <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$feesInstallment->user->full_name}}">
                                                {{@$feesInstallment->fees_type_id.'/'.@$feesInstallment->id}}
                                            </a>
                                        @endif
                                      @endif 
                                    </td>
                  
                                    <td>
                                        @if(is_null($feesInstallment->payment_mode))
                                          -- 
                                        @else
                                        {{ $feesInstallment->payment_mode}}
                                        @endif 
                                    </td>
                                    <td>{{@dateConvert($feesInstallment->payment_date)}}</td>
                  
                                    
                                    <td> {{$feesInstallment->discount_amount}}</td>
                                    <td>
                                        {{$feesInstallment->paid_amount}}
                  
                                      </td>
                                    <td>{{discountFeesAmount($feesInstallment->id) - ($feesInstallment->paid_amount) }} </td>
                                    <td>
                                        <div class="dropdown CRM_dropdown">
                                          <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                              @lang('common.select')
                                          </button>
                                          <div class="dropdown-menu dropdown-menu-right">
                                              @if($feesInstallment->active_status !=1)
                                                  @if (userPermission('university.editSubPaymentModal'))
                                                      <a data-toggle="modal" data-target="#editInstallment_{{$feesInstallment->id}}" class="dropdown-item">
                                                          @lang('common.edit')
                                                      </a>
                                                  @endif
                                              @endif 
                                              {{-- @if (userPermission(5001) && $feesInstallment->active_status !=1)
                                                  <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="{{@$feesInstallment->installment->title}}" href="{{route('university.fees-generate-modal', [$feesInstallment->amount, $feesInstallment->id, $feesInstallment->record_id])}}">
                                                      @lang('fees.add_fees')
                                                  </a>
                                              @endif --}}
                                          </div>
                                        </div>
                                    </td>
                              </tr>
                              @foreach($feesInstallment->payments as $payment)
                                <tr>
                                  {{-- <td></td> --}}
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                                  <td>
                                      @if($payment->active_status == 1)
                                       @if (moduleStatusCheck('University'))
                                            <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$payment->user->full_name}}">
                                                {{@universityFeesInvoice($feesInstallment->invoice_no)}}
                                            </a>
                                       @else
                                            <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$payment->user->full_name}}">
                                                {{@$payment->fees_type_id.'/'.@$payment->id}}
                                            </a>
                                       @endif
                                      @endif
                                  </td>
                                  <td>{{$payment->payment_mode}}</td>
                                  <td>{{@dateConvert($payment->payment_date)}}</td>
                                  <td>{{$payment->discount_amount}}</td>
                                  <td>{{$payment->paid_amount}}</td>
                                  <td>{{$payment->balance_amount}} </td>
                                  <td>
                                      <div class="dropdown CRM_dropdown">
                                          <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                              @lang('common.select')
                                          </button>
                                          <div class="dropdown-menu dropdown-menu-right">
                                              @if (userPermission('university.editSubPaymentModal'))
                                                  <a class="dropdown-item modalLink" data-modal-size="modal-md" title="{{@$feesInstallment->installment->title}} / {{@$payment->fees_type_id.'/'.@$payment->id}}" href="{{route('university.editSubPaymentModal',[$payment->id,$payment->paid_amount])}}">
                                                      @lang('common.edit')
                                                  </a>
                                              @endif
                                              @if (userPermission('directFees.deleteSubPayment'))
                                                  <a onclick="deletePayment({{$payment->id}});"  class="dropdown-item" href="#" data-toggle="modal">
                                                      @lang('common.delete')
                                                  </a>
                                              @endif
    
                                              <a class="dropdown-item" target="_blank"  href="{{route('university.viewPaymentReceipt',[$payment->id])}}"> 
                                                @lang('fees.receipt')                                      
                                            </a>
    
                                          </div>
                                        </div>
                                       </td>
                               </tr>  
                              @endforeach
                  
                            
                            @if(moduleStatusCheck('University'))
                              <div class="modal fade admin-query" id="editInstallment_{{$feesInstallment->id}}">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">
                                                @lang('university::un.fees_installment')
                                            </h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                    
                                        <div class="modal-body"> 
                                            {{ Form::open(['class' => 'form-horizontal','files' => true,'route' => 'university.feesInstallmentUpdate','method' => 'POST']) }}
                                            <div class="row">
                                                <input type="hidden" name="installment_id" value="{{$feesInstallment->id}}">
                                                <div class="col-lg-6">
                                                    <div class="primary_input ">
                                                        <input class="primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="amount" id="amount" value="{{$feesInstallment->amount}}" readonly>
                                                        <label class="primary_input_label" for="">@lang('fees.amount') <span class="text-danger"> *</span> </label>
                                                        
                                                        @if ($errors->has('amount'))
                                                        <span class="text-danger" >{{ @$errors->first('amount') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="primary_input ">
                                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                                     name="due_date" value="{{date('m/d/Y', strtotime($feesInstallment->due_date))}}" autocomplete="off">
                                                                    <label class="primary_input_label" for="">@lang('fees.due_date') <span class="text-danger"> *</span></label>
                                                                    
                                                                @if ($errors->has('due_date'))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first('due_date') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button class="" type="button">
                                                                <i class="ti-calendar" id="start-date-icon"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-5 text-center">
                                                <button type="submit" class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    @lang('common.update')
                                                </button>
                                            </div>
                    
                                            {{ Form::close() }}
                                           
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                            @endif 
    
                            @endforeach
    
                            @if($record->credit &&  $record->credit->amount > 0)
                            @include('university::include.fees_credit_view')
                            @endif 
                  
                        </tbody>
                  
                        <tfoot>
                          <tr>
                              {{-- <th></th> --}}
                              <th>@lang('fees.grand_total') ({{@$currency}})</th>
                              <th>{{currency_format($total_fees)}}</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th>{{currency_format($total_disc)}}</th>
                              <th>{{currency_format($total_paid)}} </th>
                              <th>
                                @if(@$record->credit->amount)
                                {{currency_format($total_balance + @$record->credit->amount )}}
                                @else
                                {{currency_format($total_balance)}}
                                @endif 
                            </th>
                              <th></th>
                          </tr>
                      </tfoot>
                  
                    </table>
                </x-table>

                @elseif(directFees())
                <x-table>
                    <table id="" class="table school-table-style-parent-fees" cellspacing="0" width="100%">
                                           
                        <thead>
                            <tr>
                                <td class="text-right" colspan="14">
                                    <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-lg" title="@lang('fees.add_fees')" href="{{route('direct-fees-total-payment', [$student->id])}}"0>
                                            <i class="ti-plus pr-2"></i> @lang('fees.add_fees') 
                                    </a>
    
                                    <a href="" id="fees_groups_invoice_print_button" class="primary-btn small fix-gr-bg" target="">
                                        <i class="ti-printer pr-2"></i>
                                        @lang('fees.invoice_print')
                                    </a>
                                   
                                </td>
                            </tr>
                            <tr>
                                <th class="nowrap">#</th>
                                <th class="nowrap">@lang('fees.installment') </th>
                                <th class="nowrap">@lang('fees.amount') ({{@generalSetting()->currency_symbol}})</th>
                                <th class="nowrap">@lang('common.status')</th>
                                <th class="nowrap">@lang('fees.due_date') </th>
                                <th class="nowrap">@lang('fees.payment_ID')</th>
                                <th class="nowrap">@lang('fees.mode')</th>
                                <th class="nowrap">@lang('fees.payment_date')</th>
                                <th class="nowrap">@lang('fees.discount') ({{@generalSetting()->currency_symbol}})</th>
                                <th class="nowrap">@lang('fees.paid') ({{@generalSetting()->currency_symbol}})</th>
                                <th class="nowrap">@lang('fees.balance')</th>
                                <th class="nowrap">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                              @foreach($feesInstallments as $key=> $feesInstallment)
                              @php 
                              $total_fees += discount_fees($feesInstallment->amount, $feesInstallment->discount_amount); 
                              $total_paid += $feesInstallment->paid_amount;
                              $total_disc += $feesInstallment->discount_amount;
                              @endphp 
                              <tr>
                                <td>
                                    <input type="checkbox" id="fees_group.{{$feesInstallment->id}}" class="common-checkbox fees-groups-print" name="fees_group[]" value="{{$feesInstallment->id}}">
                                    <label for="fees_group.{{$feesInstallment->id}}"></label>
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                </td>
                                    <td>{{@$feesInstallment->installment->title}}</td>
                                    <td> 
                                        @if($feesInstallment->discount_amount > 0)
                                          <del>  {{$feesInstallment->amount}}  </del>
                                          {{$feesInstallment->amount - $feesInstallment->discount_amount}}
                                        @else 
                                         {{$feesInstallment->amount}}
                                        @endif 
                                      </td>
                                      <td>
                                          @if($feesInstallment->active_status == 1)
                                          <button class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
                                          @elseif( $feesInstallment->active_status == 2) 
                                          <button class="primary-btn small bg-warning text-white border-0">@lang('fees.partial')</button>
                                          @else 
                                          <button class="primary-btn small bg-danger text-white border-0">@lang('fees.unpaid')</button>
                                          @endif 
                                      </td>
                                    <td>{{@dateConvert($feesInstallment->due_date)}}</td>
                                    <td>
                                      
                                    </td>
                                    
                                    <td>
                                        
                                    </td>
                                   
                                  <td>
                                      
                                  </td>
                                  <td> {{$feesInstallment->discount_amount}}</td>
                                  <td>
                                      {{$feesInstallment->paid_amount}}
                                  </td>
                                     
                                  <td>
                                      {{discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - ($feesInstallment->payments->sum('total_paid') + $feesInstallment->paid_amount) }} </td>
                                     
                                    <td>
                                        <div class="dropdown CRM_dropdown">
                                          <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                              @lang('common.select')
                                          </button>
                                          <div class="dropdown-menu dropdown-menu-right">
                                              @if($feesInstallment->active_status !=1)
                                              <a data-toggle="modal"
                                              data-target="#editInstallment_{{$feesInstallment->id}}" class="dropdown-item">@lang('common.edit')</a>
                                              @endif 
                  
                                              @if( $feesInstallment->active_status != 1)
                                              <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="{{@$feesInstallment->installment->title}}"
                                                  href="{{route('direct-fees-generate-modal',[$feesInstallment->amount,$feesInstallment->id,$feesInstallment->record_id])}}"> 
                                                  @lang('fees.add_fees')                                      
                                              </a>
                                              @endif               
                                          </div>
                                      </div>
                                  </td>
                                   
                              </tr>
                  
                  
                  
                              @foreach($feesInstallment->payments as $payment)
                             
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                                  <td>
                                    @if($payment->active_status == 1)
                                        <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$payment->user->full_name}}">
                                            {{@sm_fees_invoice($payment->invoice_no, $invoice_settings)}}
                                        </a>
                                    @endif 
                                  </td>
                                  <td>{{$payment->payment_mode}}</td>
                                  <td>{{@dateConvert($payment->payment_date)}}</td>
                                  <td>{{$payment->discount_amount}}</td>
                                  <td>{{$payment->paid_amount}}</td>
                                  <td>{{$payment->balance_amount}} </td>
                                  <td>
                                      <div class="dropdown CRM_dropdown">
                                          <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                              @lang('common.select')
                                          </button>
                                          <div class="dropdown-menu dropdown-menu-right">
    
                                              <a class="dropdown-item modalLink" data-modal-size="modal-md" 
                                              title="{{@$feesInstallment->installment->title}} / {{@$payment->fees_type_id.'/'.@$payment->id}}"  
                                              href="{{route('directFees.editSubPaymentModal',[$payment->id,$payment->paid_amount])}}" >@lang('common.edit') </a>
    
                                              <a onclick="deletePayment({{$payment->id}});"  class="dropdown-item" href="#" data-toggle="modal">@lang('common.delete')</a>
    
                                              <a class="dropdown-item" target="_blank"  href="{{route('directFees.viewPaymentReceipt',[$payment->id])}}"> 
                                                @lang('fees.receipt')                                      
                                            </a>
                                          </div>
                                        </div>
                                       </td>
                               </tr>  
                              @endforeach
                  
                  
                  
                              <div class="modal fade admin-query" id="editInstallment_{{$feesInstallment->id}}">
                                  <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <h4 class="modal-title">
                                                  @lang('fees.fees_installment')
                                              </h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>
                  
                                          <div class="modal-body"> 
                                              {{ Form::open(['class' => 'form-horizontal','files' => true,'route' => 'feesInstallmentUpdate','method' => 'POST']) }}
                                              <div class="row">
                                                  <input type="hidden" name="installment_id" value="{{$feesInstallment->id}}">
                                                  <div class="col-lg-6">
                                                      <div class="primary_input ">
                                                        <label class="primary_input_label" for="">@lang('fees.amount') <span class="text-danger"> *</span> </label>
                                                          <input class="primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="amount" id="amount" value="{{ $feesInstallment->amount}}" readonly>
                                                          
                                                          
                                                          @if ($errors->has('amount'))
                                                          <span class="text-danger" >
                                                              <strong>{{ @$errors->first('amount') }}
                                                          </span>
                                                          @endif
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                    <div class="primary_datepicker_input">
                                                      <div class="no-gutters input-right-icon">
                                                          <div class="col">
                                                              <div class="primary_input ">
                                                                <label class="primary_input_label" for="">@lang('fees.due_date') <span class="text-danger"> *</span></label>
                                                                  <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                                       name="due_date" value="{{date('m/d/Y', strtotime(@$feesInstallment->installment->due_date))}}" autocomplete="off">
                                                                       <button class="btn-date" style="top: 70% !important;" data-id="#date_of_birth" type="button">
                                                                        <label class="m-0 p-0" for="date_of_birth">
                                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                                        </label>
                                                                    </button>
                                                                      
                                                                      
                                                                  @if ($errors->has('due_date'))
                                                                  <span class="text-danger" >
                                                                      {{ $errors->first('due_date') }}
                                                                  </span>
                                                                  @endif
                                                              </div>
                                                          </div>
                                                          
                                                      </div>
                                                    </div>
                                                  </div>
                                              </div>
                                              <div class="col-lg-12 mt-5 text-center">
                                                  <button type="submit" class="primary-btn fix-gr-bg">
                                                      <span class="ti-check"></span>
                                                      @lang('common.update')
                                                  </button>
                                              </div>
                      
                                              {{ Form::close() }}
                                             
                                          </div>
                      
                                      </div>
                                  </div>
                              </div>
                              @endforeach
                              <tfoot>
                                  <tr>
                                      <th></th>
                                      <th>@lang('fees.grand_total') ({{@$currency}})</th>
                                      <th>{{currency_format($total_fees)}}</th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th>{{currency_format($total_disc)}}</th>
                                      <th>{{currency_format($total_paid)}} </th>
                                      <th>{{ currency_format($total_fees -  ($total_paid))}}</th>
                                      <th></th>
                                  </tr>
                              </tfoot>
                        </tbody>
                    </table>
                </x-table>
                @if(moduleStatusCheck('University'))
                <div class="modal fade admin-query" id="deletePaymentModal" >
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('fees.delete_fees_payment')  </h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                
                                    <div class="modal-body"> 
                                        {{ Form::open(['class' => 'form-horizontal','files' => true,'route' => 'university.feesInstallmentUpdate','method' => 'POST']) }}
                                        <div class="row">
                                            <input type="hidden" name="installment_id" value="{{$feesInstallment->id}}">
                                            <div class="col-lg-6">
                                                <div class="primary_input ">
                                                    <input class="primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="amount" id="amount" value="{{$feesInstallment->amount}}" readonly>
                                                    <label class="primary_input_label" for="">@lang('fees.amount') <span class="text-danger"> *</span> </label>
                                                    
                                                    @if ($errors->has('amount'))
                                                    <span class="text-danger" >{{ @$errors->first('amount') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="primary_input ">
                                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                                 name="due_date" value="{{date('m/d/Y', strtotime($feesInstallment->due_date))}}" autocomplete="off">
                                                                <label class="primary_input_label" for="">@lang('fees.due_date') <span class="text-danger"> *</span></label>
                                                                
                                                            @if ($errors->has('due_date'))
                                                            <span class="text-danger" >
                                                                {{ $errors->first('due_date') }}
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="" type="button">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-5 text-center">
                                            <button type="submit" class="primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                @lang('common.update')
                                            </button>
                                        </div>
                
                                        {{ Form::close() }}
                                       
                            <div class="modal-body">
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'directFees.deleteSubPayment',
                                        'method' => 'POST']) }}
                               
                                    <input type="hidden" name="sub_payment_id">   
                                    <div class="text-center">
                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                    </div>
                                                   
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">{{ __('common.cancel') }}</button>
                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete') </button>
                                    
                                </div>
                                {{ Form::close() }}
                            </div>
                
                        </div>
                    </div>
                </div>
                @endif 
                
                
                  <script>
                    function deletePayment(id) {
                        var modal = $('#deletePaymentModal');
                        modal.find('input[name=sub_payment_id]').val(id)
                        modal.modal('show');
                    }
                </script>

                @else 
                    <x-table>
                        <table class="table school-table-style" cellspacing="0" width="100%">
                            <thead>
                               
                                <tr>
                                    <td class="text-right" colspan="14">
                                        <a href="" id="fees_groups_invoice_print_button" class="primary-btn small fix-gr-bg" target="">
                                            <i class="ti-printer pr-2"></i>
                                            @lang('fees.invoice_print')
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('fees.fees')</th>
                                    <th>@lang('fees.due_date')</th>
                                    <th>@lang('common.status')</th>
                                    <th>@lang('fees.amount') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('fees.payment_id')</th>
                                    <th>@lang('fees.mode')</th>
                                    <th>@lang('common.date')</th>
                                    <th>@lang('fees.discount') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('fees.fine') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('fees.paid') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('fees.balance')</th>
                                    <th>@lang('common.action')</th>
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
                                    $paid = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'amount' ,$fees_assigned->record_id);
                                    $total_grand_paid += $paid;
                                    $fine = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'fine', $fees_assigned->record_id);
                                    $total_fine += $fine;
                                    $total_paid = $discount_amount + $paid;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" id="fees_group.{{$fees_assigned->id}}" class="common-checkbox fees-groups-print" name="fees_group[]" value="{{$fees_assigned->id}}">
                                        <label for="fees_group.{{$fees_assigned->id}}"></label>
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                    </td>
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
                                            <button class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
                                        @elseif($paid != 0)
                                            <button class="primary-btn small bg-warning text-white border-0">@lang('fees.partial')</button>
                                        @elseif($paid == 0)
                                            <button class="primary-btn small bg-danger text-white border-0">@lang('fees.unpaid')</button>
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
                                    <td>
                                        <div class="dropdown CRM_dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('common.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(userPermission('fees-generate-modal'))
                                                    @if($balance_amount != 0) 
                                                        <a class="dropdown-item modalLink" data-modal-size="modal-lg" 
                                                        title="{{@$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"  
                                                        href="{{route('fees-generate-modal', [$balance_amount, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id,$fees_assigned->fees_master_id,$fees_assigned->id,$fees_assigned->record_id])}}" >@lang('fees.add_fees') </a>
                                                    @else
                                                        <a class="dropdown-item"  target="_blank">Payment Done</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
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
                                        <td>
                                            {{$payment->amount}}
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>@lang('fees.grand_total') ({{generalSetting()->currency_symbol}})</th>
                                    <th></th>
                                    <th>{{ currency_format($grand_total) }}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>{{ currency_format($total_discount) }}</th>
                                    <th>{{ currency_format($total_fine) }}</th>
                                    <th>{{ currency_format($total_grand_paid) }}</th>
                                        @php
                                            $show_balance=$grand_total+$total_fine-$total_discount;
                                        @endphp
                                    <th>{{ currency_format($show_balance - $total_grand_paid)}}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </x-table>
                @endif 
            </div>
            </div>
            </div>
        </div>
    </div>
</section>

@if(moduleStatusCheck('University'))
<div class="modal fade admin-query" id="deletePaymentModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('fees.delete_fees_payment')  </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'university.deleteSubPayment',
                        'method' => 'POST']) }}
               
                    <input type="hidden" name="sub_payment_id">   
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>
                                   
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete') </button>
                    
                </div>
                {{ Form::close() }}
            </div>

        </div>
    </div>
</div>
@endif 

@if(directFees())
<div class="modal fade admin-query" id="deletePaymentModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('fees.delete_fees_payment')  </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'directFees.deleteSubPayment',
                        'method' => 'POST']) }}
               
                    <input type="hidden" name="sub_payment_id">   
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>
                                   
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete') </button>
                    
                </div>
                {{ Form::close() }}
            </div>

        </div>
    </div>
</div>
@endif

@endsection


@include('backEnd.partials.date_picker_css_js')
@push('script')
<script>
    function deletePayment(id) {
        var modal = $('#deletePaymentModal');
        modal.find('input[name=sub_payment_id]').val(id)
        modal.modal('show');
    }
</script>
@endpush



