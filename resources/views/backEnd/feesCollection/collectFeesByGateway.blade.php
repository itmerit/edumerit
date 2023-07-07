@extends('backEnd.master')
@section('title') 
@lang('fees.all_fees')
@endsection
@section('mainContent')
<style type="text/css">
    .smtp_wrapper{
        display: none;
    }
    .smtp_wrapper_block{
        display: block;
    }
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.collect_fees_online')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('student_fees')}}">@lang('fees.all_fees')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('fees.collect_fees_online')</h3>
                </div>
            </div>
        </div>
     {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'route' => 'payByPaypal', 'id' => 'payment-form', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">              
              <div class="white-box">
                <div class="">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    <input type="hidden" name="real_amount" id="real_amount" value="{{$amount}}">
                    <input type="hidden" name="student_id" value="{{$student_id}}">
                    <input type="hidden" name="fees_type_id" value="{{$fees_type_id}}"> 
                     
                     <div class="row justify-content-center mb-30">
                        <div class="col-lg-4">
                            <div class="primary_input">
                                <input class="primary_input_field form-control{{ $errors->has('from_name') ? ' is-invalid' : '' }}"
                                type="text" name="amount" id="amount" autocomplete="off" value="{{isset($amount)? $amount : ''}}" readonly="readonly">
                                <label class="primary_input_label" for="">@lang('fees.fees_amount') <span class="text-danger"> *</span> </label>
                                
                                @if ($errors->has('from_name'))
                                <span class="text-danger" >
                                    {{ $errors->first('from_name') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                  </div>
                    
                </div>
                <div class="row mt-40">
                    <div class="col-lg-12 text-center">
                        <button class="primary-btn fix-gr-bg">
                            <span class="ti-check"></span>
                            @lang('fees.pay_with_paypal')
                        </button>
                    </div>
                </div>
            </div>
        </div> 
{{ Form::close() }}
    </div>
</div>

</div>
</section>
@endsection


