@extends('backEnd.master')
    @section('title')
        @lang('system_settings.payment_method_settings')
    @endsection
@section('mainContent')
@push('css')
    <style>
        table.dataTable thead th {
            padding: 10px 30px !important;
        }

        table.dataTable tbody th, table.dataTable tbody td {
            padding: 20px 30px 20px 30px !important;
        }

        table.dataTable tfoot th, table.dataTable tfoot td {
            padding: 10px 30px 6px 30px;
        }
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
        .CustomPaymentMethod{
            padding: 5px 0px 0px 0px !important;
            border-top: 0px !important;
        }
    </style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.payment_method_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.payment_method_settings')</a>
            </div>
        </div>
    </div>
</section>
<section class="mb-40 student-details">
    <div class="container-fluid p-0">
        <div class="row pt-20">
            <div class="col-lg-3">
                <div class="main-title pt-30">
                    <h3 class="mb-30">@lang('system_settings.select_a_payment_gateway')   </h3>
                </div>
                @if(userPermission('is-active-payment'))
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'is-active-payment']) }}
                @endif
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table">
                                @foreach($paymeny_gateway as $value)
                                    @if(moduleStatusCheck('RazorPay') == FALSE && $value->method =="RazorPay")
                                @else
                                    <tr>
                                        <td class="CustomPaymentMethod">
                                            <div class="primary_input">
                                                <input type="checkbox" id="gateway_{{@$value->method}}" class="common-checkbox class-checkbox" name="gateways[{{@$value->id}}]"
                                                value="{{@$value->id}}" {{@$value->active_status == 1? 'checked':''}}>
                                                <label for="gateway_{{@$value->method}}">{{@$value->method}}</label>
                                            </div>
                                        </td>
                                        <td class="CustomPaymentMethod"></td>
                                    </tr>
                                @endif
                                @endforeach
                            </table>
                            @if($errors->has('gateways'))
                                <span class="text-danger validate-textarea-checkbox" role="alert">
                                    {{ $errors->first('gateways') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @php
                        $tooltip = "";
                        if(userPermission('is-active-payment')){ $tooltip = ""; }else{  $tooltip = "You have no permission to Update"; }
                    @endphp
                    <div class="row mt-15">
                        <div class="col-lg-12 text-center">
                            @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('common.update') </button></span>
                            @else
                                <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                    <span class="ti-check"></span>
                                    @lang('common.update')
                                </button>
                          @endif
                        </div>
                    </div>
                </div>
                    {{ Form::close() }}
            </div>

            <div class="col-lg-9">
                 <div class="row pt-20">
                    <div class="main-title pt-10">
                        <h3 class="mb-30">@lang('system_settings.gateway_setting')</h3>
                    </div>
                    <ul class="nav nav-tabs justify-content-end mt-sm-md-20 mb-30" role="tablist">
                        @foreach($paymeny_gateway_settings as $row)
                        @if(moduleStatusCheck('RazorPay') == FALSE && $row->gateway_name =="RazorPay")
                        @else
                            <li class="nav-item">
                                <a class="nav-link
                                @if(!empty(Session::get('gateway_name')) && !empty(Session::get('active_status')))
                                    @if(Session::get('gateway_name') == @$row->gateway_name && Session::get('active_status') == "active") active show
                                    @endif                               
                                @else
                                    @if(@$row->gateway_name=='PayPal') active show @endif
                                @endif "
                                 href="#{{@$row->gateway_name}}" role="tab" data-toggle="tab">{{@$row->gateway_name}}</a>
                            </li>
                        @endif
                        @endforeach
                    </ul>
                 </div>
                <!-- Tab panes -->

                <div class="tab-content">
                    @php
                        $forServiceCharge = ['service_charge', 'charge'];
                    @endphp
                    @foreach ($paymeny_gateway_settings as $row)
                        <div role="tabpanel"
                            class="tab-pane fade   
                            @if(!empty(Session::get('gateway_name')) && !empty(Session::get('active_status')))
                            @if(Session::get('gateway_name') == @$row->gateway_name && Session::get('active_status') == "active") active show
                            @endif                               
                        @else
                            @if(@$row->gateway_name=='PayPal') active show @endif
                        @endif "
                            "
                            id="{{ @$row->gateway_name }}">
                            @if (userPermission('update-payment-gateway'))
                                <form class="form-horizontal" action="{{ route('update-payment-gateway') }}"
                                    method="POST">
                            @endif
                            @csrf
                            <div class="white-box">
                                <div class="">
                                    <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                                    <input type="hidden" name="gateway_name" id="gateway_{{ @$row->gateway_name }}"
                                        value="{{ @$row->gateway_name }}">
                                    <div class="row mb-15">
                                        <div class="col-md-12">
                                            <?php
                                            if(@$row->gateway_name=="PayPal")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_username','gateway_password','gateway_signature','gateway_client_id','gateway_mode','gateway_secret_key', 'service_charge', 'charge'];
                                            }
                                            else if(@$row->gateway_name=="Stripe")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_username','gateway_secret_key','gateway_publisher_key', 'service_charge', 'charge'];
                                            }
                                            else if(@$row->gateway_name=="Paystack")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_username','gateway_secret_key','gateway_publisher_key', 'service_charge', 'charge'];
                                            }

                                            else if(@$row->gateway_name=="Khalti")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_publisher_key','gateway_secret_key', 'service_charge', 'charge'];
                                            }
                                            else if(@$row->gateway_name=="Khalti")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_publisher_key','gateway_secret_key', 'service_charge', 'charge'];
                                            }

                                            else if(@$row->gateway_name=="RazorPay")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_secret_key','gateway_publisher_key', 'service_charge', 'charge'];

                                            }
                                            else if(@$row->gateway_name=="MercadoPago")
                                            {
                                                @$paymeny_gateway = ['gateway_name','mercado_pago_public_key','mercado_pago_acces_token', 'service_charge', 'charge'];

                                            }

                                            else if(@$row->gateway_name=="CcAveune")
                                            {
                                                @$paymeny_gateway = ['gateway_name','cca_merchant_id','cca_working_key', 'cca_access_code', 'service_charge', 'charge'];

                                            }

                                            else if(@$row->gateway_name=="Xendit")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_secret_key','gateway_username', 'service_charge', 'charge'];

                                            }

                                            else if(@$row->gateway_name=="Raudhahpay")
                                            {
                                                @$paymeny_gateway = ['gateway_name','gateway_password','gateway_username', 'service_charge', 'charge'];

                                            }

                                            else if(@$row->gateway_name=="Bank"){
                                                @$paymeny_gateway = ['gateway_name', 'bank_details'];

                                            }else if(@$row->gateway_name=="Cheque"){
                                                @$paymeny_gateway = ['gateway_name','cheque_details'];

                                            }
                                                if(@$row->gateway_name=="Stripe" || @$row->gateway_name=="Paystack" || @$row->gateway_name=="RazorPay" || @$row->gateway_name=="Xendit" || @$row->gateway_name=="Raudhahpay" || @$row->gateway_name=="PayPal" || @$row->gateway_name=="Khalti" || @$row->gateway_name=="MercadoPago" || @$row->gateway_name=="CcAveune"){
                                                $count=0;
                                                foreach ($paymeny_gateway as $key=>$input_field) {
                                                    if(@$row->gateway_name=="RazorPay"){
                                                        if($input_field == 'gateway_publisher_key'){
                                                            @$newStr = 'gateway_secret_key';
                                                        }
                                                        elseif($input_field == 'gateway_secret_key'){
                                                            @$newStr = 'gateway_publisher_key';
                                                        }
                                                        else{
                                                            @$newStr = 'gateway_publisher_key';
                                                        }

                                                    }else{
                                                        @$newStr = @$input_field;
                                                    }

                                                    @$label_name = str_replace('_', ' ', @$newStr);
                                                    @$value= @$row->$input_field; ?>
                                            @if (!in_array($input_field, $forServiceCharge))
                                                <div class="row">
                                                    <div class="col-lg-12 mb-15">
                                                        <div class="primary_input">
                                                            <label>{{ @$label_name }}
                                                                <p class="text-lowercase d-inline-block">
                                                                    @php
                                                                        if ($input_field == 'gateway_mode') {
                                                                            echo strtolower('(sandbox or live)');
                                                                        }
                                                                    @endphp
                                                                </p>
                                                                <span></span>
                                                            </label>
                                                            <input
                                                                class="primary_input_field form-control{{ $errors->has($input_field) ? ' is-invalid' : '' }}"
                                                                type="text" name="{{ $input_field }}"
                                                                id="gateway_{{ $input_field }}" autocomplete="off"
                                                                value="{{ isset($value) ? $value : '' }}"
                                                                @if (@$count == 0) readonly="" @endif>
                                                            
                                                            
                                                            @if ($errors->has($input_field))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first($input_field) }}</strong>
                                                                </span>
                                                            @endif
                                                            {{-- <span class="modal_input_validation red_alert"></span> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($input_field == 'service_charge')
                                                @php
                                                    $d_none = $row->service_charge == 0 ? 'd-none' : '';
                                                @endphp
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="checkbox"
                                                            id="service_charge_{{ $row->gateway_name }}"
                                                            class="common-checkbox form-control{{ @$errors->has('service_charge') ? ' is-invalid' : '' }} service_charge"
                                                            name="service_charge" value="1"
                                                            data-gateway_name="{{ $row->gateway_name }}"
                                                            {{ $row->service_charge == 1 ? 'checked' : '' }}>
                                                        <label
                                                            for="service_charge_{{ $row->gateway_name }}">{{ __('common.service_charge') }}</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row {{ $d_none }}" id="charge_type_{{ $row->gateway_name }}">
                                                    <div class="col-lg-6 ">
                                                        <label for=""> </label>
                                                        <div class="d-flex radio-btn-flex mt-10">

                                                            <div class="mr-30">
                                                                <input type="radio" name="charge_type"
                                                                    id="p_{{ $row->gateway_name }}" value="P"
                                                                    class="common-radio relationButton type_{{ $row->gateway_name }}"
                                                                    {{ old('charge_type', $row->charge_type ?? 'P') == 'P' ? 'checked' : '' }}>
                                                                <label
                                                                    for="p_{{ $row->gateway_name }}">@lang('common.Percentage')</label>
                                                            </div>
                                                            <div class="mr-30">
                                                                <input type="radio" name="charge_type"
                                                                    id="f_{{ $row->gateway_name }}" value="F"
                                                                    class="common-radio relationButton type_{{ $row->gateway_name }}"
                                                                    {{ $row->charge_type == 'F' ? 'checked' : '' }}>
                                                                <label
                                                                    for="f_{{ $row->gateway_name }}">@lang('common.Flat')</label>
                                                            </div>
                                                            
                                                        </div>
                                                        @if ($errors->has('charge_type') && old('gateway_name') == $row->gateway_name)
                                                                <span class="text-danger d-block" >
                                                                    {{ $errors->first('charge_type') }}
                                                                </span>
                                                            @endif
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="primary_input">
                                                            <input type="number"
                                                                class="primary_input_field form-control chargeValue {{ $errors->has('charge') ? ' is-invalid' : '' }}"
                                                                type="text" name="charge" data-gateway_name="{{ $row->gateway_name }}"
                                                                id="charge_{{ $row->gateway_name }}" autocomplete="off"
                                                                value="{{ $row->charge }}">

                                                            
                                                            @if ($errors->has('charge'))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first('charge') }}
                                                                </span>
                                                            @endif
                                                            {{-- <span class="modal_input_validation red_alert"></span> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <?php $count++; } ?>
                                            <?php  }elseif(@$row->gateway_name=="Bank"){?>
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-4 no-gutters">
                                                        <div class="main-title">
                                                            <h3 class="mb-0">@lang('system_settings.bank_account_list')</h3>
                                                            <strong>@lang('common.note'):
                                                            </strong><small>@lang('system_settings.Available_for_students_and_parents')</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table id="noSearch" class="table shadow-none"
                                                            cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('system_settings.value')</th>
                                                                    <th>@lang('accounts.bank_name')</th>
                                                                    <th>@lang('accounts.account_name')</th>
                                                                    <th>@lang('accounts.account_number')</th>
                                                                    <th>@lang('accounts.account_type')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($bank_accounts as $bank_account)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="primary_input">
                                                                                <input type="checkbox"
                                                                                    data-id="{{ @$bank_account->id }}"
                                                                                    id="bank{{ @$bank_account->id }}"
                                                                                    class="common-checkbox class-checkbox accountStatus"
                                                                                    name="account_status"
                                                                                    value="{{ @$bank_account->id }}"
                                                                                    {{ @$bank_account->active_status == 1 ? 'checked' : '' }}>
                                                                                <label
                                                                                    for="bank{{ @$bank_account->id }}">{{ @$value->method }}</label>
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ @$bank_account->bank_name }}</td>
                                                                        <td>{{ @$bank_account->account_name }}</td>
                                                                        <td>{{ @$bank_account->account_number }}</td>
                                                                        <td>{{ @$bank_account->account_type }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }elseif(@$row->gateway_name=="Cheque") {
                                            $count=0;
                                                foreach ($paymeny_gateway as $input_field) {
                                                    @$newStr = @$input_field;
                                                    @$label_name = str_replace('_', ' ', @$newStr);
                                                    @$value= @$row->$input_field; ?>
                                            @if ($count == 0)
                                                <div class="row">
                                                    <div class="col-lg-12 mb-15">
                                                        <div class="primary_input">
                                                            <label>{{ @$label_name }} <span></span> </label>
                                                            <input
                                                                class="primary_input_field form-control{{ $errors->has($input_field) ? ' is-invalid' : '' }}"
                                                                type="text" name="{{ $input_field }}"
                                                                id="gateway_{{ $input_field }}" autocomplete="off"
                                                                value="{{ isset($value) ? $value : '' }}"
                                                                @if (@$count == 0) readonly="" @endif>
                                                           
                                                            
                                                            <span class="modal_input_validation red_alert"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-lg-12 ">
                                                        <label class="textarea-label"> @lang('common' . '.' . $input_field)
                                                            <span></span> </label>
                                                        <div class="primary_input sm2_mb_20">
                                                            <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
                                                            <textarea class="primary_input_field article-ckeditor form-control" cols="0" rows="3"
                                                                name="{{ $input_field }}" id="article-ckeditor">{{ @$value }}</textarea>

                                                            <script>
                                                                CKEDITOR.replace("<?php echo $input_field; ?>");
                                                            </script>
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <?php $count++; }
                                          }
                                          ?>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row justify-content-center">
                                                @if (!empty(@$row->logo))
                                                    <img class="logo" src="{{ URL::asset(@$row->logo) }}"
                                                        style="width: auto; height: 100px; ">
                                                @endif
                                            </div>
                                            <div class="row justify-content-center">
                                                @if (session()->has('message-success'))
                                                    <p class=" text-success">
                                                        {{ session()->get('message-success') }}
                                                    </p>
                                                @elseif(session()->has('message-danger'))
                                                    <p class=" text-danger">
                                                        {{ session()->get('message-danger') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $tooltip = '';
                                    if (userPermission('update-payment-gateway')) {
                                        $tooltip = '';
                                    } else {
                                        $tooltip = 'You have no permission to add';
                                    }
                                @endphp
                                @if (@$row->gateway_name != 'Bank')
                                    @if (@$row->gateway_name == 'Paystack')
                                        <strong class="main-title"> N.B: Please Set This url <a class="disabled"
                                                href=" {{ route('payment.success','Paystack') }}" disable>
                                                @if (directFees() ||  generalSetting()->fees_status )
                                                {{ route('payment.success','Paystack') }} 
                                                @else
                                                    {{ route('handleGatewayCallback') }} 
                                                @endif
                                            </a> As Paystack Callback Url </strong>
                                    @endif

                                    @if (@$row->gateway_name == 'Raudhahpay')
                                        <strong class="main-title"> N.B: Please Set This url <a class="disabled"
                                                href="{{ url('raudhahpay/payment_success_callback') }}"
                                                disable>{{ url('raudhahpay/payment_success_callback') }}</a> As
                                            Raudhahpay WebHook Url </strong>
                                    @endif

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                    title="Disabled For Demo "> <button
                                                        class="primary-btn fix-gr-bg  demo_view"
                                                        style="pointer-events: none;"
                                                        type="button">@lang('common.update') </button></span>
                                            @else
                                                <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                    title="{{ @$tooltip }}">
                                                    <span class="ti-check"></span>
                                                    @lang('common.update')
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                            </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
    <script>
        $(document).on('change','.accountStatus',function (){
            let account_id = $(this).data('id');
            let account_status =0;
            if ($(this).is(':checked'))
            {
                account_status = 1;
            }
            $.ajax({
                url : "{{route('bank-status')}}",
                method : "POST",
                data : {
                    account_id : account_id,
                    account_status : account_status,
                },
                success : function (result){
                    toastr.success('Operation successful', 'Successful', {
                        timeOut: 5000
                    })
                }
            })
        })
        $(document).on('click', '.service_charge', function(){
            let gateway_name = $(this).data('gateway_name');
            let service_charge = $(this).data('gateway_name');
            let status = $(this).is(':checked') ? 1 : 0;
            let type = $('.type_'+gateway_name).val();
            if(status == 1) {
                $('#charge_type_'+gateway_name).removeClass('d-none');        
            }else {
                $('#charge_type_'+gateway_name).addClass('d-none');
            }            
        });  
        $(document).on('keyup', '.chargeValue', function(){
            let gateway_name = $(this).data('gateway_name');
            let className = $('.type_'+gateway_name);
            let type = $("input.type_"+gateway_name+"[name='charge_type']:checked").val();
            console.log(type);
            let charge = $(this).val();
            console.log(typeof charge);            
            if (type == 'P' && charge > 100) {
                toastr.error("Value Can't be more than 100 ");
                $(this).val('');
            }
        });                                                                                                                                                                                                                                              
    </script>                                              
    @endpush
