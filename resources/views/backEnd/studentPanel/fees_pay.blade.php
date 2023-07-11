@extends('backEnd.master')
@section('title')
    @lang('fees.pay_fees')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.fees')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('fees.fees')</a>
                <a href="{{route('student_fees')}}">@lang('fees.pay_fees')</a>
            </div>
        </div>
    </div>
</section>

<section class="student-details">
    <div class="container-fluid p-0">
      
        <div class="row mt-40">
            <input type="hidden" id="url" value="{{URL::to('/')}}">
            <input type="hidden" id="student_id" value="{{@$student->id}}">
            <!-- Start Student Details -->
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                    @foreach($records as $key => $record)
                    <li class="nav-item">
                        <a class="nav-link @if($key== 0) active @endif " href="#tab{{$key}}" role="tab" data-toggle="tab">
                            @if(moduleStatusCheck('University'))
                            {{@$record->unSemesterLabel->name}} ({{@$record->unDepartment->name}} - {{@$record->unSection->section_name}})
                            @else
                            {{$record->class->class_name}} ({{$record->section->section_name}})
                            @endif
                        </a>
                    </li>
                    @endforeach

                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Start Fees Tab -->
                    @foreach($records as $key=> $record)
                        <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                            <div class="table-responsive">
                                @if(moduleStatusCheck('University'))
                                @includeIf('university::include.studentPanelFeesPay')
                                @elseif(directFees())
                                @includeIf('backEnd.feesCollection.directFees.studentDirectFeesPay')
                                @else
                                <x-table>
                                    <table id="" class="table school-table-style-parent-fees" cellspacing="0" width="100%">
                                   <thead>
                                       <tr>
                                           <th class="nowrap">@lang('fees.fees_group') </th>
                                           <th class="nowrap">@lang('fees.fees_code') </th>
                                           <th class="nowrap">@lang('fees.due_date') </th>
                                           <th class="nowrap">@lang('common.status')</th>
                                           <th class="nowrap">@lang('fees.amount') ({{@generalSetting()->currency_symbol}})</th>
                                           <th class="nowrap">@lang('fees.payment_id')</th>
                                           <th class="nowrap">@lang('fees.mode')</th>
                                           <th class="nowrap">@lang('common.date')</th>
                                           <th class="nowrap">@lang('fees.discount') ({{@generalSetting()->currency_symbol}})</th>
                                           <th class="nowrap">@lang('fees.fine') ({{@generalSetting()->currency_symbol}})</th>
                                           <th class="nowrap">@lang('fees.paid') ({{@generalSetting()->currency_symbol}})</th>
                                           <th class="nowrap">@lang('fees.balance')</th>
                                           <th class="nowrap">@lang('fees.payment')</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       @php
                                           @$grand_total = 0;
                                           @$total_fine = 0;
                                           @$total_discount = 0;
                                           @$total_paid = 0;
                                           @$total_grand_paid = 0;
                                           @$total_balance = 0;
                                           $count = 0
                                       @endphp
                                       @foreach($record->fees as $fees_assigned)
                                           @php
                                               $count++;

                                               @$grand_total += @$fees_assigned->feesGroupMaster->amount


                                           @endphp

                                           @php
                                               @$discount_amount = $fees_assigned->applied_discount;
                                               @$total_discount += @$discount_amount;
                                               @$student_id = @$fees_assigned->student_id
                                           @endphp
                                           @php
                                               //Sum of total paid amount of single fees type
                                               $paid = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id,$fees_assigned->record_id)->sum('amount');

                                               @$total_grand_paid += @$paid
                                           @endphp
                                           @php
                                               //Sum of total fine for single fees type
                                               $fine = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id,$fees_assigned->record_id)->sum('fine');

                                               @$total_fine += $fine
                                           @endphp

                                           @php
                                               @$total_paid = @$discount_amount + @$paid
                                           @endphp
                                           <tr>
                                               <td>
                                                   {{@$fees_assigned->feesGroupMaster->feesGroups != ""? @$fees_assigned->feesGroupMaster->feesGroups->name:""}}
                                               </td>
                                               <td>
                                                   {{@$fees_assigned->feesGroupMaster->feesTypes->name}}
                                               </td>
                                               <td class="nowrap">
                                                   {{@$fees_assigned->feesGroupMaster->date != ""? dateConvert(@$fees_assigned->feesGroupMaster->date):''}}
                                               </td>

                                               <td>
                                                   @php
                                                       // $total_payable_amount=$fees_assigned->feesGroupMaster->amount+$fine;
                                                       $total_payable_amount=$fees_assigned->feesGroupMaster->amount;
                                                       $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                                       $balance_amount=number_format($rest_amount+$fine, 2, '.', '');
                                                       $total_balance +=  $balance_amount
                                                   @endphp
                                                   @if($balance_amount ==0)
                                                       <button
                                                               class="primary-btn small bg-success text-white border-0">@lang('fees.paid')</button>
                                                   @elseif($paid != 0)
                                                       <button
                                                               class="primary-btn small bg-warning text-white border-0">@lang('fees.partial')</button>
                                                   @elseif($paid == 0)
                                                       <button
                                                               class="primary-btn small bg-danger text-white border-0">@lang('fees.unpaid')</button>
                                                   @endif
                                               </td>
                                               <td> {{currency_format(@$total_payable_amount)}}</td>
                                               <td></td>
                                               <td></td>
                                               <td></td>
                                               <td> {{currency_format(@$discount_amount)}} </td>
                                               <td>{{currency_format(@$fine)}}</td>
                                               <td>{{currency_format(@$paid)}}</td>
                                               <td>
                                                   @php
                                                       @$rest_amount = $fees_assigned->fees_amount;
                                                       echo currency_format(@$balance_amount)
                                                   @endphp
                                               </td>
                                               <td>
                                                   @if($rest_amount =! 0)
                                                       @php
                                                           $already_add = $student->bankSlips->where('fees_type_id', $fees_assigned->feesGroupMaster->fees_type_id)->first();
                                                       @endphp
                                                       <div class="dropdown CRM_dropdown">
                                                           <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                               @lang('common.select')
                                                           </button>
                                                           <div class="dropdown-menu dropdown-menu-right">
                                                               <!--  Start Xendit Payment -->
                                                                   @if( (moduleStatusCheck('XenditPayment') == TRUE) && ($balance_amount != 0) )
                                                                       <form action="{!!route('xenditpayment.feesPayment')!!}" method="POST" style="width: 100%; text-align: center">
                                                                           @csrf
                                                                           
                                                                           <input type="hidden" name="fees_type_id" id="fees_type_id" value="{{$fees_assigned->feesGroupMaster->fees_type_id}}">
                                                                           <input type="hidden" name="student_id" id="student_id" value="{{$student->id}}">
                                                                           <input type="hidden" name="payment_mode" id="payment_mode" value="{{$payment_gateway->id}}">
                                                                           <input type="hidden" name="amount" id="amount" value="{{$balance_amount * 1000}}"/>
                                                                           <input type="hidden" name="record_id" value="{{@$fees_assigned->recordDetail->id}}">
                                                                           <div class="pay">
                                                                               <button class="dropdown-item razorpay-payment-button btn filled small" 
                                                                               @if(serviceCharge('XenditPayment'))
                                                                                   data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ') }} {{ serviceCharge('XenditPayment')}}"
                                                                               @endif
                                                                               type="submit">
                                                                                   @lang('fees.pay_with_xendit') {{ serviceCharge('XenditPayment') ? '+'.serviceCharge('XenditPayment') : '' }}
                                                                               </button>
                                                                           </div>
                                                                       </form>
                                                                   @endif
                                                               <!--  End Xendit Payment -->

                                                               <!-- Start Khalti Payment  -->
                                                                   @if((moduleStatusCheck('KhaltiPayment') == TRUE)  && ($balance_amount > 0))
                                                                       @php
                                                                           $is_khalti = DB::table('sm_payment_gateway_settings')
                                                                                       ->where('gateway_name','Khalti')
                                                                                       ->where('school_id', Auth::user()->school_id)
                                                                                       ->first('gateway_publisher_key');
                                                                       @endphp
                                                                       <div class="pay">
                                                                           <button class="dropdown-item btn filled small khalti-payment-button" 
                                                                           data-amount="{{$balance_amount}}" data-assignid = "{{$fees_assigned->id}}" data-feestypeid = "{{$fees_assigned->feesGroupMaster->fees_type_id}}" 
                                                                           data-recordId = "{{@$fees_assigned->recordDetail->id}}"
                                                                           @if(serviceCharge('KhaltiPayment'))
                                                                               data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ')}} {{ serviceCharge('KhaltiPayment') }}"
                                                                           @endif
                                                                           >
                                                                               @lang('fees.pay_with_khalti')
                                                                               {{ serviceCharge('KhaltiPayment') ? '+'.serviceCharge('KhaltiPayment') : '' }}
                                                                           </button>
                                                                       </div>
                                                                   @endif
                                                               <!-- End Khalti Payment  -->

                                                                   @if($already_add=="" && $balance_amount !=0)
                                                                       @if(@$data['bank_info']->active_status == 1 || @$data['cheque_info']->active_status == 1 )
                                                                           <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                                               href="{{route('fees-generate-modal-child', [@$balance_amount, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id,$fees_assigned->id,$fees_assigned->record_id])}}">
                                                                               @lang('fees.add_bank_payment')
                                                                           </a>
                                                                       @endif
                                                                   @else
                                                                       @if($balance_amount !=0)
                                                                           <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                                               title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                                               href="{{route('fees-generate-modal-child', [@$balance_amount, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id,$fees_assigned->id, $fees_assigned->record_id])}}">
                                                                               @lang('fees.add_bank_payment')
                                                                           </a>
                                                                           @if($already_add!="")
                                                                               <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                                                   title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                                                   href="{{route('fees-generate-modal-child-view', [$fees_assigned->student_id,$fees_assigned->feesGroupMaster->fees_type_id,$fees_assigned->id])}}">
                                                                                   @lang('fees.view_bank_payment')
                                                                               </a>
                                                                               @if(@$already_add->approve_status == 0)
                                                                                   <a onclick="deleteId({{@$already_add->id}});" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="{{@$already_add->id}}">
                                                                                       @lang('fees.delete_bank_payment')
                                                                                   </a>
                                                                               @endif
                                                                           @endif
                                                                       @else
                                                                           @if($already_add!="")
                                                                               <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                                                   title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                                                   href="{{route('fees-generate-modal-child-view', [$fees_assigned->student_id,$fees_assigned->feesGroupMaster->fees_type_id,$fees_assigned->id])}}">
                                                                                   @lang('fees.view_bank_payment')
                                                                               </a>
                                                                           @else
                                                                               <a class="dropdown-item">@lang('fees.paid')</a>
                                                                           @endif
                                                                       @endif
                                                                   @endif

                                                               <!-- Start Paypal Payment  -->
                                                                   @php
                                                                       $is_paypal = DB::table('sm_payment_methhods')
                                                                                   ->where('method','PayPal')
                                                                                   ->where('school_id', Auth::user()->school_id)
                                                                                   ->where('active_status',1)
                                                                                   ->first();
                                                                   @endphp
                                                                   @if(!empty($is_paypal) && $balance_amount !=0)
                                                                       <form method="POST" action="{{ route('studentPayByPaypal') }}" accept-charset="UTF-8" class="form-horizontal" role="form">
                                                                           @csrf
                                                                           <input type="hidden" name="assign_id" id="assign_id" value="{{$fees_assigned->id}}">
                                                                           <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                                                           <input type="hidden" name="real_amount" id="real_amount" value="{{$balance_amount}}">
                                                                           <input type="hidden" name="student_id" value="{{$student->id}}">
                                                                           <input type="hidden" name="fees_type_id" value="{{$fees_assigned->feesGroupMaster->fees_type_id}}">
                                                                           <input type="hidden" name="record_id" value="{{@$fees_assigned->recordDetail->id}}">
                                                                           <button type="submit" class=" dropdown-item"
                                                                           @if(serviceCharge('PayPal'))
                                                                               data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ') }} {{ serviceCharge('PayPal') }}"
                                                                           @endif
                                                                           >
                                                                               @lang('fees.pay_with_paypal')
                                                                               {{ serviceCharge('PayPal') ? '+'.serviceCharge('PayPal') : '' }}
                                                                           </button>
                                                                       </form>
                                                                   @endif
                                                               <!-- End Paypal Payment  -->

                                                               <!-- Start Paystack Payment  -->
                                                                   @php
                                                                       $is_paystack = DB::table('sm_payment_methhods')
                                                                                   ->where('method','Paystack')
                                                                                   ->where('school_id', Auth::user()->school_id)
                                                                                   ->where('active_status',1)
                                                                                   ->first();
                                                                   @endphp
                                                                   @if(!empty($is_paystack) && $balance_amount !=0)
                                                                       <form method="POST" action="{{ route('pay-with-paystack') }}" accept-charset="UTF-8" class="form-horizontal" role="form">
                                                                           @csrf
                                                                           <input type="hidden" name="assign_id" id="assign_id" value="{{$fees_assigned->id}}">
                                                                           @if(($student->email == ""))
                                                                               <input type="hidden" name="email" value="{{ @$student->parents->guardians_email }}">
                                                                           @else
                                                                               <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                                                           @endif
                                                                           <input type="hidden" name="orderID" value="{{$fees_assigned->id}}">
                                                                           <input type="hidden" name="amount" value="{{$balance_amount * 100}}">
                                                                           <input type="hidden" name="quantity" value="1">
                                                                           <input type="hidden" name="fees_type_id" value="{{$fees_assigned->feesGroupMaster->fees_type_id}}">
                                                                           <input type="hidden" name="student_id" value="{{$student->id}}">
                                                                           <input type="hidden" name="payment_mode" value="{{@$payment_gateway->id}}">
                                                                           <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                                                                           <input type="hidden" name="key" value="{{ @$paystack_info->gateway_secret_key }}">
                                                                           <input type="hidden" name="record_id" value="{{@$fees_assigned->recordDetail->id}}">
                                                                           <button type="submit" class=" dropdown-item"
                                                                           @if(serviceCharge('Paystack'))
                                                                               data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ') }} {{ serviceCharge('Paystack') }}"
                                                                           @endif
                                                                           >
                                                                               @lang('fees.pay_via_paystack')
                                                                               {{ serviceCharge('Paystack') ? '+'.serviceCharge('Paystack') : '' }}
                                                                           </button>
                                                                       </form>
                                                                   @endif
                                                               <!-- End Paystack Payment  -->

                                                               <!-- Start Stripe Payment  -->
                                                                   @php
                                                                       $is_stripe = DB::table('sm_payment_methhods')
                                                                                   ->where('method','Stripe')
                                                                                   ->where('active_status',1)
                                                                                   ->where('school_id', Auth::user()->school_id)
                                                                                   ->first();
                                                                   @endphp
                                                                   @if(!empty($is_stripe) && $balance_amount !=0)
                                                                       <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('fees.pay_fees') "
                                                                           @if(serviceCharge('Stripe'))
                                                                               data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ') }} {{ serviceCharge('Stripe') }}"
                                                                           @endif
                                                                           href="{{route('fees-payment-stripe', [@$fees_assigned->feesGroupMaster->fees_type_id, $student->id, $balance_amount,$fees_assigned->id,@$fees_assigned->recordDetail->id])}}">
                                                                           @lang('fees.pay_with_stripe')
                                                                           {{ serviceCharge('Stripe') ? '+'.serviceCharge('Stripe') : '' }}
                                                                       </a>
                                                                   @endif
                                                               <!-- Start Stripe Payment  -->

                                                               {{-- Start Xendit Payment --}}

                                                                @if(moduleStatusCheck('CcAveune') && $balance_amount !=0)
                                                                <!-- start CcAveune Gateway  -->
                                                                    @php
                                                                       $is_CcAveune = DB::table('sm_payment_methhods')
                                                                                   ->where('method','CcAveune')
                                                                                   ->where('active_status',1)
                                                                                   ->where('school_id', Auth::user()->school_id)
                                                                                   ->first();
                                                                   @endphp
                                                                        <a type="submit" class="dropdown-item modalLink" data-modal-size="modal-md" title="@lang('fees.pay_fees') "
                                                                           @if(serviceCharge('CcAveune'))
                                                                               data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ') }} {{ serviceCharge('CcAveune') }}"
                                                                           @endif
                                                                           href="{{route('studentFeesPay-ccaveune',[$balance_amount, $fees_assigned->id,'oldFees'])}}" >
                                                                               @lang('fees.pay_with_CcAveune')
                                                                               {{ serviceCharge('CcAveune') ? '+'.serviceCharge('CcAveune') : '' }}
                                                                        </a>
                                                                        
                                                               <!--  end CcAveune Gateway  -->
                                                                    @endif 

                                                               <!-- Start Razorpay Payment -->
                                                                   @php
                                                                       $is_active = DB::table('sm_payment_methhods')
                                                                                   ->where('method','RazorPay')
                                                                                   ->where('active_status',1)
                                                                                   ->where('school_id', Auth::user()->school_id)
                                                                                   ->first();
                                                                   @endphp
                                                                   @if(moduleStatusCheck('RazorPay') == TRUE and !empty($is_active))
                                                                       
                                                                       <form id="rzp-footer-form_{{$count}}" action="{!!route('razorpay/dopayment')!!}" method="POST" style="width: 100%; text-align: center">
                                                                           @csrf
                                                                           <input type="hidden" name="assign_id" id="assign_id" value="{{$fees_assigned->id}}">
                                                                           <input type="hidden" name="amount" id="amount" value="{{ ($balance_amount + chargeAmount('RazorPay', $balance_amount))  * 100}}"/>
                                                                           <input type="hidden" name="fees_type_id" id="fees_type_id" value="{{$fees_assigned->feesGroupMaster->fees_type_id}}">
                                                                           <input type="hidden" name="student_id" id="student_id" value="{{$student->id}}">
                                                                           <input type="hidden" name="payment_mode" id="payment_mode" value="{{$payment_gateway->id}}">
                                                                           <input type="hidden" name="real_amount" id="real_amount" value="{{$balance_amount}}"/>
                                                                           <div class="pay">
                                                                               <button class="dropdown-item razorpay-payment-button btn filled small" id="paybtn_{{$count}}" type="button"
                                                                               @if(serviceCharge('RazorPay'))
                                                                               data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ')}}
                                                                               {{ serviceCharge('RazorPay') }}"
                                                                               @endif
                                                                               >
                                                                                   @lang('fees.pay_with_razorpay')
                                                                                   {{ serviceCharge('RazorPay') ? '+'.serviceCharge('RazorPay') : '' }}
                                                                               </button>
                                                                           </div>
                                                                       </form>

                                                                   <!-- start razorpay code -->
                                                                   <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                                                   <script>
                                                                       $('#rzp-footer-form_<?php echo $count; ?>').submit(function (e) {
                                                                           var button = $(this).find('button');
                                                                           var parent = $(this);
                                                                           button.attr('disabled', 'true').html('Please Wait...');
                                                                           $.ajax({
                                                                               method: 'get',
                                                                               url: this.action,
                                                                               data: $(this).serialize(),
                                                                               complete: function (r) {
                                                                                   console.log('complete');
                                                                                   console.log(r);
                                                                               }
                                                                           })
                                                                           return false;
                                                                       })
                                                                   </script>
                                                                   <script>
                                                                       function padStart(str) {
                                                                           return ('0' + str).slice(-2)
                                                                       }
                                                                       function demoSuccessHandler(transaction) {
                                                                           // You can write success code here. If you want to store some data in database.
                                                                           $("#paymentDetail").removeAttr('style');
                                                                           $('#paymentID').text(transaction.razorpay_payment_id);
                                                                           var paymentDate = new Date();
                                                                           $('#paymentDate').text(
                                                                               padStart(paymentDate.getDate()) + '.' + padStart(paymentDate.getMonth() + 1) + '.' + paymentDate.getFullYear() + ' ' + padStart(paymentDate.getHours()) + ':' + padStart(paymentDate.getMinutes())
                                                                           );

                                                                           $.ajax({
                                                                               method: 'post',
                                                                               url: "{!!url('razorpay/dopayment')!!}",
                                                                               data: {
                                                                                   "_token": "{{ csrf_token() }}",
                                                                                   "razorpay_payment_id": transaction.razorpay_payment_id,
                                                                                   "amount": <?php echo ($balance_amount + chargeAmount('RazorPay', $balance_amount)) *100; ?>,
                                                                                   "fees_type_id": <?php echo $fees_assigned->feesGroupMaster->fees_type_id; ?>,
                                                                                   "student_id": <?php echo $student->id; ?>
                                                                               },
                                                                               complete: function (r) {
                                                                                   console.log('complete');
                                                                                   console.log(r);

                                                                                   setTimeout(function () {
                                                                                       toastr.success('Operation successful', 'Success', {
                                                                                           "iconClass": 'customer-info'
                                                                                       }, {
                                                                                           timeOut: 2000
                                                                                       });
                                                                                   }, 500);

                                                                                   location.reload();
                                                                               }
                                                                           })
                                                                       }
                                                                   </script>
                                                                   <script>
                                                                       var options_<?php echo $count; ?> = {
                                                                           key: "{{ @$razorpay_info->gateway_secret_key }}",
                                                                           amount: <?php echo ($balance_amount + chargeAmount('RazorPay', $balance_amount))*100; ?>,
                                                                           name: 'Online fee payment',
                                                                           image: 'https://i.imgur.com/n5tjHFD.png',
                                                                           handler: demoSuccessHandler
                                                                       }
                                                                   </script>
                                                                   <script>
                                                                       window.r_<?php echo $count; ?> = new Razorpay(options_<?php echo $count; ?>);
                                                                       document.getElementById('paybtn_<?php echo $count; ?>').onclick = function () {
                                                                           r_<?php echo $count; ?>.open()
                                                                       }
                                                                   </script>
                                                                   <!-- end razorpay code -->
                                                                   @endif
                                                               <!-- End Razorpay Payment -->

                                                               <!-- Start Raudhahpay Payment  -->
                                                                   @if((moduleStatusCheck('Raudhahpay') == TRUE)  && ($balance_amount > 0))
                                                                       
                                                                       <form id="xend-footer-form_{{$count}}" action="{!!route('raudhahpay.feesPayment')!!}" method="POST" style="width: 100%; text-align: center">
                                                                           @csrf
                                                                           <input type="hidden" name="amount" id="amount" value="{{$balance_amount}}"/>
                                                                           <input type="hidden" name="assign_id" id="assign_id" value="{{$fees_assigned->id}}">
                                                                           <input type="hidden" name="fees_type_id" id="fees_type_id" value="{{$fees_assigned->feesGroupMaster->fees_type_id}}">
                                                                           <input type="hidden" name="student_id" id="student_id" value="{{$student->id}}">
                                                                           <input type="hidden" name="payment_method" id="payment_mode" value="5">
                                                                           <input type="hidden" name="amount" id="amount" value="{{$balance_amount}}"/>
                                                                           <div class="pay">
                                                                               <button class="dropdown-item razorpay-payment-button btn filled small" id="paybtn_{{$count}}"
                                                                               @if(serviceCharge('Raudhahpay'))
                                                                               data-toggle="tooltip" data-title = "{{ __('common.service charge for per transaction ')}} {{ serviceCharge('Raudhahpay') }}"
                                                                               @endif
                                                                               type="submit">
                                                                                   @lang('fees.pay_with_raudhahpay')
                                                                                   {{ serviceCharge('Raudhahpay') ? '+'.serviceCharge('Raudhahpay') : '' }}
                                                                               </button>
                                                                           </div>
                                                                       </form>
                                                                   @endif
                                                               <!-- End Raudhahpay Payment  -->

   
                                                           </div>
                                                       </div>
                                                   @endif
                                               </td>
                                           </tr>

                                           @php
                                               @$payments =$student->feesPayment->where('active_status', 1)
                                                           ->where('record_id',$fees_assigned->record_id)
                                                           ->where('fees_type_id',$fees_assigned->feesGroupMaster->feesTypes->id);
                                               $i = 0;
                                           @endphp
                                           @foreach($payments as $payment)
                                               <tr>
                                                   <td></td>
                                                   <td></td>
                                                   <td></td>
                                                   <td></td>
                                                   <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                                                   <td>
                                                       @php
                                                           @$created_by = App\User::find($payment->created_by)
                                                       @endphp
                                                       @if(@$created_by != "")
                                                           <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$created_by->full_name}}">
                                                               {{@$payment->fees_type_id.'/'.@$payment->id}}
                                                           </a>
                                                       @endif
                                                   </td>
                                                   <td>{{$payment->payment_mode}}</td>
                                                   <td class="nowrap"> {{@$payment->payment_date != ""? dateConvert(@$payment->payment_date):''}}</td>
                                                   <td>{{@$payment->discount_amount}}</td>
                                                   <td>
                                                       {{@$payment->fine}}
                                                       @if($payment->fine!=0)
                                                           @if(strlen($payment->fine_title) > 14)
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
                                                   <td>{{@$payment->amount}}</td>
                                                   <td></td>
                                                   <td></td>
                                               </tr>
                                           @endforeach
                                       @endforeach
                                       @foreach($record->feesDiscounts as $fees_discount)
                                           <tr>
                                               <td>@lang('fees.discount')</td>
                                               <td>{{@$fees_discount->feesDiscount!=""?@$fees_discount->feesDiscount->name:""}}</td>
                                               <td></td>
                                               <td>@if(in_array(@$fees_discount->id, @$applied_discount))
                                                       
                                                   @else
                                                       @lang('fees.discount_of')
                                                     {{currency_format(@$fees_discount->feesDiscount->amount)}}
                                                       @lang('fees.assigned')
                                                   @endif
                                               </td>
                                               <td>{{@$fees_discount->name}}</td>
                                               <td></td>
                                               <td></td>
                                               <td></td>
                                               <td></td>
                                               <td></td>
                                               <td></td>
                                               <td></td>
                                               <td></td>
                                           </tr>
                                       @endforeach
                                   </tbody>
                                   <tfoot>
                                       <tr>
                                           <th></th>
                                           <th></th>
                                           <th>
                                               @lang('fees.grand_total') ({{@generalSetting()->currency_symbol}})
                                           </th>
                                           <th></th>
                                           <th>{{currency_format(@$grand_total)}}</th>
                                           <th></th>
                                           <th></th>
                                           <th></th>
                                           <th>{{currency_format(@$total_discount)}}</th>
                                           <th> {{currency_format(@$total_fine)}}</th>
                                           <th>{{currency_format(@$total_grand_paid)}}</th>
                                           <th> {{currency_format($total_balance)}}</th>
                                           <th></th>
                                       </tr>
                                    </tfoot>
                                    </table>
                                </x-table>

                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>



<div class="modal fade admin-query" id="deleteFeesPayment">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('common.delete_item')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('fees.are_you_sure_to_detete_this_item')?</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.cancel')</button>
                    {{ Form::open(['route' => 'fees-payment-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="id" id="feep_payment_id">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade admin-query" id="deleteStudentModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('common.delete_item')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.cancel')</button>
                    {{ Form::open(['url' => 'child-bank-slip-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="id" value="" id="student_delete_i"> {{-- using js in main.js --}}
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>

@include('backEnd.partials.date_picker_css_js')

@endsection
@if(moduleStatusCheck('KhaltiPayment')==true)
    @push('script')
        <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
        <script>
            $(document).on('click', '.khalti-payment-button', function(){
                var feesTypeId = "fees_type_id_assign_id=" +  $(this).data("feestypeid");
                var assignId =  $(this).data("assignid");
                var recordId = $(this).data("recordId");
                var productinfo = feesTypeId + '_' + assignId + '_' + recordId;

                var config = {
                    "publicKey": "{{@$is_khalti->gateway_publisher_key}}",
                    "productIdentity":  productinfo,
                    "productName": "Fees Payment",
                    "productUrl": "{{url('/')}}",
                    "Cust" : "Pranta",
                    "paymentPreference": [
                        "KHALTI",
                        "EBANKING",
                        "MOBILE_BANKING",
                        "CONNECT_IPS",
                        "SCT",
                    ],
                    "eventHandler": {
                        onSuccess (payload) {
                            var url = "{{url('khaltipayment/successPayment?')}}" ;
                            var student = 'student' + '=' + "{{$student->id}}";
                            var trx = 'trx' + '=' + payload.idx;
                            var token = 'token' + '=' + payload.token;
                            var amount = 'amount' + '=' + payload.amount;
                            window.location.href = url + token + '&' + trx + '&'  + '&' + amount + '&' + student + '&' + payload.product_identity ;
                        },
                        onError (error) {
                            var url = "{{url('khaltipayment/cancelPayment?')}}" ;
                            window.location.href = url ;
                        },
                        onClose () {
                            console.log('widget is closing');
                        }
                    }
                };

                var checkout = new KhaltiCheckout(config);
                var pay_amount = $(this).data("amount");
                var feesTypeId = $(this).data("feesTypeId");
                var assignId = $(this).data("assignId");
                checkout.show({amount: pay_amount * 100 });
            })
        </script>

    @endpush
@endif

@push('script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endpush


@push('script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

@endpush

