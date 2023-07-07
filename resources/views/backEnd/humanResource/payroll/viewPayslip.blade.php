@php
$setting_info = generalSetting();

@endphp
<div class="container-fluid">
    <div class="student-details">
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center">
                <div>
                @if(! is_null($setting_info->logo))
                        <img class="logo-img" src="{{ asset($setting_info->logo)}}" alt="{{$setting_info->school_name}}"> 
                    @else
                            <img class="logo-img" src="{{ asset('public/uploads/settings/logo.png')}}" alt="logo"> 
                    @endif
                </div>
                <div class="ml-30">
                    <h2>@if(isset($schoolDetails)){{$schoolDetails->school_name}} @endif</h2>
                    <p class="mb-0">@if(isset($schoolDetails)){{$schoolDetails->address}} @endif</p>
                </div>
            </div>
            <h3 class="mt-3">@lang('hr.payslip_for_the_period_of') {{$payrollDetails->payroll_month}} {{$payrollDetails->payroll_year}}</h3>
        </div>

        <div class="single-meta d-flex justify-content-between mb-4">
            <div class="value text-left">
                @lang('hr.payslip') #@if(isset($payrollDetails)){{$payrollDetails->id}} @endif
            </div>
            <div class="name">
               
                @lang('fees.payment_date'): @if(isset($payrollDetails))

                {{dateConvert($payrollDetails->payment_date)}}
               
                @endif
            </div>
        </div>


        <div class="student-meta-box">
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('hr.staff_id')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffs->staff_no}} @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('common.name')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffDetails->full_name}} @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('hr.departments')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffDetails->departments->name}} @endif

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('hr.designation')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffDetails->designations->title}} @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('hr.payment_mode')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if($payrollDetails->payment_mode != "")
                            {{$payrollDetails->paymentMethods->method}}
                            @endif

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('hr.basic_salary')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->basic_salary}} @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('hr.gross_salary')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->gross_salary}} @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('hr.net_salary')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->net_salary}} @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-meta">
                <div class="row">
                    @if($payrollDetails->note)
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('common.note')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            {{$payrollDetails->note}}
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-12 mt-10">
                        <a href="{{route('print-payslip', $payrollDetails->id)}}" class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i> @lang('common.print')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>