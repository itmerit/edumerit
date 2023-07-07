@php 
$total_fees = 0;
$total_due = 0;
$total_paid = 0;
$total_disc = 0;
$balance_fees = 0;
@endphp
<x-table>
<table id="" class="table school-table-style-parent-fees" cellspacing="0" width="100%">
      <thead>
          <tr>
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

            @foreach($record->directFeesInstallments as $key=> $feesInstallment)
            @php 
            $total_fees += discount_fees($feesInstallment->amount,$feesInstallment->discount_amount); 
            $total_paid += $feesInstallment->paid_amount;
            $total_disc += $feesInstallment->discount_amount;
            $balance_fees += discount_fees($feesInstallment->amount , $feesInstallment->discount_amount) - ( $feesInstallment->paid_amount );
            @endphp 
            <tr>
                
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
                        
                    <button class="primary-btn small {{fees_payment_status($feesInstallment->amount, $feesInstallment->discount_amount , $feesInstallment->paid_amount , $feesInstallment->active_status )[1]}} text-white border-0">{{fees_payment_status($feesInstallment->amount, $feesInstallment->discount_amount , $feesInstallment->paid_amount , $feesInstallment->active_status )[0]}}</button>
                       
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
                    {{discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - ( $feesInstallment->paid_amount ) }} </td>
                   
                  <td>
                      <div class="dropdown CRM_dropdown">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                            @lang('common.select')
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if($feesInstallment->active_status !=1)
                            <a data-toggle="modal"
                             data-target="#editInstallment_{{$feesInstallment->id}}" class="dropdown-item">@lang('common.edit')</a>
                            @endif 

                            @if ( discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - ( $feesInstallment->paid_amount ) !=0 )
                            <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="{{@$feesInstallment->installment->title}}"
                                href="{{route('direct-fees-generate-modal',[$feesInstallment->amount,$feesInstallment->id,$feesInstallment->record_id])}}"> 
                                @lang('fees.add_fees')                                      
                            </a>
                            @endif 

                        </div>
                    </div>
                </td>
                 
            </tr>


            @php $this_installment = discount_fees($feesInstallment->amount, $feesInstallment->discount_amount); @endphp
            @foreach($feesInstallment->payments as $payment)
                @php $this_installment = $this_installment - $payment->paid_amount; @endphp
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                <td>
                    @if($payment->active_status == 1)
                       
                    <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$payment->user->full_name}}">
                        {{@smFeesInvoice($payment->invoice_no)}}
                    </a>
                @endif
                </td>
                <td>{{$payment->payment_mode}}</td>
                <td>{{@dateConvert($payment->payment_date)}}</td>
                <td>{{$payment->discount_amount}}</td>
                <td>{{$payment->paid_amount}}</td>
                <td>{{$this_installment}} </td>
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
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input ">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                     name="due_date" value="{{date('m/d/Y', strtotime($feesInstallment->installment->due_date))}}" autocomplete="off">
                                                    <label class="primary_input_label" for="">@lang('fees.due_date') <span class="text-danger"> *</span></label>
                                                    <input class="primary_input_field  primary-input date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                        name="due_date" value="{{date('m/d/Y', strtotime($feesInstallment->installment->due_date))}}" autocomplete="off">
                                                    
                                                @if ($errors->has('due_date'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('due_date') }}
                                                </span>
                                                @endif
                                            </div>
                                            <button class="btn-date" data-id="#startDate" type="button">
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
            @endforeach
            <tfoot>
                <tr>
                    <th>@lang('fees.grand_total') ({{@$currency}})</th>
                    <th>{{currency_format($total_fees)}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{currency_format($total_disc)}}</th>
                    <th>{{currency_format($total_paid)}} </th>
                    <th>{{$total_fees - ( $total_paid) }}</th>
                    <th></th>
                </tr>
            </tfoot>
      </tbody>
</table>
</x-table>





  <div class="modal fade admin-query" id="deletePaymentModal" >
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
@include('backEnd.partials.date_picker_css_js')

  <script>
    function deletePayment(id) {
        var modal = $('#deletePaymentModal');
        modal.find('input[name=sub_payment_id]').val(id)
        modal.modal('show');
    }
</script>