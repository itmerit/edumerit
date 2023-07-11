@extends('backEnd.master')
@section('title')
@lang('fees.fees_settings')
@endsection
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
    }

    input:focus+.slider {
        box-shadow: 0 0 1px linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

</style>
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('fees.fees_settings') </h1>

                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('fees.fees_collection')</a>
                    <a href="#">@lang('common.settings')</a>
                </div>
            </div>
        </div>
    </section>
   

    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="white_box_30px mt-5">
                                    <!-- SMTP form  -->
                                    <div class="main-title mb-25">
                                        <h3 class="mb-0">@lang('fees.fees_invoice_settings')</h3>
                                    </div>
                                    {{ Form::model($feesInvoice, ['class' => 'bg-white p-4 rounded', 'route' => ['directFees.feesInvoiceUpdate'], 'method' => 'post']) }}
                                    <div class="row">
                                          <input type="hidden" name="school_id" value="{{auth()->user()->school_id}}">
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between mt-25">
                                                <div class="primary_input">
                                                    {{ Form::text('prefix', null, ['autocomplete' => 'off', 'class' => 'primary_input_field form-control'.  ($errors->has('prefix') ? ' is-invalid' : '')]) }}
                                                    {{ Form::label('prefix', __('fees.prefix')."*") }}
                                                    
                                                    @error('prefix')
                                                        <span class="text-danger custom-error-message" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between mt-25">
                                                <div class="primary_input">
                                                    {{ Form::text('start_form', null, ['autocomplete' => 'off', 'class' => 'primary_input_field form-control'. ($errors->has('start_form') ? ' is-invalid' : '')]) }}
                                                    {{ Form::label('start_form', __('fees.start_form')."*") }}
                                                    
                                                    @error('start_form')
                                                        <span class="text-danger custom-error-message" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-20">
                                            <div class="col-lg-12 text-center">
                                                <button class="primary-btn small fix-gr-bg"><i class="ti-check"></i>
                                                    @lang('common.update')
                                                </button>
                                            </div>
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="white_box_30px mt-5">
                                    <!-- SMTP form  -->
                                    <div class="main-title mb-25">
                                        <h3 class="mb-0">@lang('fees.payment_reminder_settings')</h3>
                                    </div>

                                    <div class="row">
                                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                                            <a class="primary-btn small fix-gr-bg" data-toggle="modal" data-target="#commandModal"
                                                href="#">@lang('fees.cron_command')
                                            </a>
                                        </div>
                                    </div>

                                    <div class="modal fade admin-query" id="commandModal" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('fees.cron_jobs_command')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                            
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4><code>artisan absent_notification:sms</code> </h4>
                                                       
                                                    </div>
                            
                                                    <div class="mt-40 d-flex ">
                                                        @lang('fees.example'): <br>
                                                        <code>
                                                           {{ 'cd ' . base_path() . '/ && php artisan absent_notification:sms >> /dev/null 2>&1' }}
                                                        </code>
                                                    </div>
                                                </div>
                            
                                            </div>
                                        </div>
                                    </div>


                                    
                                    {{ Form::model($paymentReminder, ['class' => 'bg-white p-4 rounded', 'route' => ['directFees.paymentReminder'], 'method' => 'POST']) }}
                                        @php
                                            $data = json_decode($paymentReminder->notification_types);
                                        @endphp
                                        <div class="row">
                                          <input type="hidden" name="school_id" value="{{auth()->user()->school_id}}">
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between mt-25">
                                                <div class="primary_input">
                                                    {{ Form::text('due_date_before', null, ['autocomplete' => 'off', 'class' => 'primary_input_field form-control'. ($errors->has('due_date_before') ? ' is-invalid' : '')]) }}
                                                    {{ Form::label('due_date_before', __('fees.due_date_before')) }}
                                                    
                                                    @error('due_date_before')
                                                        <span class="text-danger custom-error-message" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between mt-25">
                                                <p class="text-uppercase mb-0">@lang('fees.notification_type')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="checkbox" name="notification_types[]" id="system" value="system" class="common-radio relationButton endDay" {{isset($data)? (in_array('system',$data) ? 'checked': ''):''}}>
                                                        <label for="system">@lang('fees.system')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="checkbox" name="notification_types[]" id="email" value="email" class="common-radio relationButton endDay" {{isset($data)? (in_array('email',$data) ? 'checked': ''):''}}>
                                                        <label for="email">@lang('common.email')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="checkbox" name="notification_types[]" id="sms" value="sms" class="common-radio relationButton endDay" {{isset($data)? (in_array('sms',$data) ? 'checked': ''):''}}>
                                                        <label for="sms">@lang('common.sms')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-20">
                                            <div class="col-lg-12 text-center">
                                                <button class="primary-btn small fix-gr-bg"><i class="ti-check"></i>
                                                    @lang('common.update')
                                                </button>
                                            </div>
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        $(document).ready(function(){
                hideShowDay({{ @$model->choose_subject }});
            $('.endDay').on('change', function(){               
               hideShowDay($(this).val());
            })
            function hideShowDay(endDay) {
                if(endDay == 1 ) {
                    $('#end_day').removeClass('d-none');
                } else {
                    $('#end_day').addClass('d-none');
                }
            }
        })
    </script>
@endpush
