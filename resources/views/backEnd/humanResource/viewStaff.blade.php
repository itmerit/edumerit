@extends('backEnd.master')
@section('title')
    @lang('hr.staff_details')
@endsection
@section('mainContent')
    <style>
        table.dataTable thead .sorting_asc:after {
            left: 5px;
            top: 11px;
        }

        table.dataTable thead .sorting:after {
            left: 5px;
            top: 11px;
        }

        #table_id_wrapper {
            margin-top: 10px;
        }
        .table.dataTable {
            padding: 0;
            box-shadow: 0 0 0 !important;
        }

        table.dataTable thead th {
            padding-left: 24px;
        }

        table.dataTable thead .sorting_desc:after {
            left: 5px;
            top: 10px;
        }
        .input-right-icon button.primary-btn-small-input {
            top: 8px !important;
            right: 11px !important;
        }
    </style>
    @php
        function showTimelineDocName($data)
        {
            $name = explode('/', $data);
            $number = count($name);
            return $name[$number - 1];
        }
        function showDocumentName($data)
        {
            $name = explode('/', $data);
            $number = count($name);
            return $name[$number - 1];
        }
    @endphp
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.human_resource')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="">@lang('hr.human_resource')</a>
                    <a href="{{ route('staff_directory') }}">@lang('hr.staff_details')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-40 student-details">

        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3 mb-30">
                    <!-- Start Student Meta Information -->
                    <div class="main-title">
                        <h3 class="mb-20">@lang('hr.staff_details')</h3>
                    </div>
                    <div class="student-meta-box">
                        <div class="student-meta-top"></div>

                        <img class="student-meta-img img-100"
                            src="{{ file_exists(@$staffDetails->staff_photo) ? asset($staffDetails->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                            alt="">
                        <div class="white-box">
                            <div class="single-meta mt-10">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.staff_name')
                                    </div>
                                    <div class="value">

                                        @if (isset($staffDetails))
                                            {{ $staffDetails->full_name }}
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.role')
                                    </div>
                                    <div class="value">
                                        @if (isset($staffDetails))
                                            {{ $staffDetails->roles->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.designation')
                                    </div>
                                    <div class="value">
                                        @if (isset($staffDetails))
                                            {{ !empty($staffDetails->designations) ? $staffDetails->designations->title : '' }}
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.department')
                                    </div>
                                    <div class="value">

                                        @if (isset($staffDetails))
                                            {{ !empty($staffDetails->departments) ? $staffDetails->departments->name : '' }}
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.epf_no')
                                    </div>
                                    <div class="value">
                                        @if (isset($staffDetails))
                                            {{ $staffDetails->epf_no }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.basic_salary')
                                    </div>
                                    <div class="value">
                                        @if (isset($staffDetails))
                                            {{ currency_format($staffDetails->basic_salary) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.contract_type')
                                    </div>
                                    <div class="value">
                                        @if (isset($staffDetails))
                                            {{ $staffDetails->contract_type }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.date_of_joining')
                                    </div>
                                    <div class="value">
                                        @if (isset($staffDetails))
                                            {{ $staffDetails->date_of_joining != '' ? dateConvert($staffDetails->date_of_joining) : '' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Student Meta Information -->
                </div>
                <!-- Start Student Details -->
                <div class="col-lg-9 staff-details">
                    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if (Session::get('staffDocuments') != 'active' && Session::get('staffTimeline') != 'active') active @endif" href="#studentProfile"
                                role="tab" data-toggle="tab">@lang('hr.profile')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#payroll" role="tab" data-toggle="tab">@lang('hr.payroll')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#leaves" role="tab" data-toggle="tab">@lang('hr.leave')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Session::get('staffDocuments') == 'active' ? 'active' : '' }}"
                                href="#staffDocuments" role="tab" data-toggle="tab">@lang('hr.documents')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Session::get('staffTimeline') == 'active' ? 'active' : '' }}"
                                href="#staffTimeline"
                                role="tab" data-toggle="tab">@lang('hr.timeline')</a>
                        </li>
                        <li class="nav-item edit-button d-flex align-items-center justify-content-end">
                            <a href="{{ route('editStaff', $staffDetails->id) }}"
                                class="primary-btn small fix-gr-bg">@lang('common.edit')
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Start Profile Tab -->
                        <div role="tabpanel" class="tab-pane fade @if (Session::get('staffDocuments') != 'active' && Session::get('staffTimeline') != 'active') show active @endif"
                            id="studentProfile">
                            <div class="white-box">
                                <h4 class="stu-sub-head">@lang('hr.personal_info')</h4>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('common.mobile_no')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->mobile }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('hr.emergency_mobile')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->emergency_mobile }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('common.email')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->email }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('hr.driving_license')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ @$staffDetails->driving_license }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('common.gender')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ @$staffDetails->genders->base_setup_name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('common.date_of_birth')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->date_of_birth != '' ? dateConvert($staffDetails->date_of_birth) : '' }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('hr.marital_status')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->marital_status }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('student.father_name')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->fathers_name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('hr.mother_name')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->mothers_name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('hr.qualifications')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->qualification }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-6">
                                            <div class="">
                                                @lang('hr.work_experience')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-7">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->experience }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Start Parent Part -->
                                <h4 class="stu-sub-head mt-40">@lang('hr.address')</h4>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('hr.current_address')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->current_address }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('hr.permanent_address')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->permanent_address }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Parent Part -->

                                <!-- Start Transport Part -->
                                <h4 class="stu-sub-head mt-40">@lang('hr.bank_account_details')</h4>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('accounts.account_name')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->bank_account_name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('accounts.bank_account_number')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->bank_account_no }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('accounts.bank_name')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->bank_name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('accounts.branch_name')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                @if (isset($staffDetails))
                                                    {{ $staffDetails->bank_brach }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- End Transport Part -->

                                <!-- Start Other Information Part -->
                                <h4 class="stu-sub-head mt-40">@lang('hr.social_links_details')</h4>
                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('hr.facebook_url')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                <a href="{{ $staffDetails->facebook_url }}" target="_blank">
                                                    @if (isset($staffDetails))
                                                        {{ $staffDetails->facebook_url }}
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('hr.twitter_url')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                <a href="{{ $staffDetails->twiteer_url }}" target="_blank">
                                                    @if (isset($staffDetails))
                                                        {{ $staffDetails->twiteer_url }}
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('hr.linkedin_url')
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                <a href="{{ $staffDetails->linkedin_url }}" target="_blank">
                                                    @if (isset($staffDetails))
                                                        {{ $staffDetails->linkedin_url }}
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-info">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="">
                                                @lang('hr.instragram_url')
                                            </div>
                                        </div>

                                        <div class="col-lg-7 col-md-6">
                                            <div class="">
                                                <a href="{{ $staffDetails->instragram_url }}" target="_blank">
                                                    @if (isset($staffDetails))
                                                        {{ $staffDetails->instragram_url }}
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Other Information Part -->
                                {{-- Custom field start --}}
                                @include('backEnd.customField._coutom_field_show')
                                {{-- Custom field end --}}

                            </div>
                        </div>
                        <!-- End Profile Tab -->

                        <!-- Start payroll Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="payroll">
                            <div class="white-box">
                                <table id="" class="table simple-table table-responsive school-table"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">@lang('hr.payslip_id')</th>
                                            <th width="20%">@lang('hr.month_year')</th>
                                            <th width="15%">@lang('common.date')</th>
                                            <th width="15%">@lang('hr.mode_of_payment')</th>
                                            <th width="15%">@lang('hr.net_salary')({{ generalSetting()->currency_symbol }})
                                            </th>
                                            <th width="12%">@lang('common.status')</th>
                                            <th width="20%">@lang('common.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (count($staffPayrollDetails) > 0)
                                            @foreach ($staffPayrollDetails as $value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->payroll_month }} - {{ $value->payroll_year }}</td>
                                                    <td>

                                                        {{ $value->created_at != '' ? dateConvert($value->created_at) : '' }}

                                                    </td>
                                                    <td><?php $payment_mode = '';
                                                    if (!empty($value->payment_mode)) {
                                                        $payment_mode = App\SmHrPayrollGenerate::getPaymentMode($value->payment_mode);
                                                    } else {
                                                        $payment_mode = '';
                                                    }
                                                    ?>
                                                        {{ $payment_mode }}
                                                    </td>
                                                    <td>{{ $value->net_salary }}</td>
                                                    <td>
                                                        @if ($value->payroll_status == 'G')
                                                            <button
                                                                class="primary-btn small bg-warning text-white border-0">
                                                                @lang('hr.generated')</button>
                                                        @endif

                                                        @if ($value->payroll_status == 'P')
                                                            <button
                                                                class="primary-btn small bg-success text-white border-0">
                                                                @lang('hr.paid') </button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($value->payroll_status == 'P')
                                                            <a class="modalLink" data-modal-size="modal-lg"
                                                                title="@lang('hr.view_payslip_details')"
                                                                href="{{ route('view-payslip', $value->id) }}"><button
                                                                    class="primary-btn small tr-bg">
                                                                    @lang('common.view_payslip')</button></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="justify-content-center">
                                                <td colspan="7" class="justify-content-center text-center">
                                                    @lang('hr.no_payroll_data')
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End payroll Tab -->

                        <!-- Start leave Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="leaves">
                            <div class="white-box">
                                <div class="row mt-30">
                                    <div class="col-lg-12">
                                        <table id="table_id" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>@lang('leave.leave_type')</th>
                                                    <th>@lang('leave.leave_from') </th>
                                                    <th>@lang('leave.leave_to')</th>
                                                    <th>@lang('leave.apply_date')</th>
                                                    <th>@lang('common.status')</th>
                                                    <th>@lang('common.action')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $diff = ''; @endphp
                                                @if (count($staffLeaveDetails) > 0)
                                                    @foreach ($staffLeaveDetails as $value)
                                                        <tr>
                                                            <td>{{ @$value->leaveDefine->leaveType->type }}</td>
                                                            <td>{{ $value->leave_from != '' ? dateConvert($value->leave_from) : '' }}
                                                            </td>
                                                            <td>{{ $value->leave_to != '' ? dateConvert($value->leave_to) : '' }}
                                                            </td>
                                                            <td>{{ $value->apply_date != '' ? dateConvert($value->apply_date) : '' }}
                                                            </td>
                                                            <td>
                                                                @if ($value->approve_status == 'P')
                                                                    <button
                                                                        class="primary-btn small bg-warning text-white border-0">
                                                                        @lang('common.pending')</button>
                                                                @endif

                                                                @if ($value->approve_status == 'A')
                                                                    <button
                                                                        class="primary-btn small bg-success text-white border-0">
                                                                        @lang('common.approved')</button>
                                                                @endif

                                                                @if ($value->approve_status == 'C')
                                                                    <button
                                                                        class="primary-btn small bg-danger text-white border-0">
                                                                        @lang('hr.cancelled')</button>
                                                                @endif

                                                            </td>
                                                            <td>
                                                                <a class="modalLink" data-modal-size="modal-md"
                                                                    title="@lang('common.view_leave_details')"
                                                                    href="{{ url('view-leave-details-apply', $value->id) }}"><button
                                                                        class="primary-btn small tr-bg"> @lang('common.view')
                                                                    </button></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td>@lang('hr.not_leaves_data')</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End leave Tab -->

                        <!-- Start Documents Tab -->
                        <div role="tabpanel" class="tab-pane fade {{ Session::get('staffDocuments') == 'active' ? 'show active' : '' }}" id="staffDocuments">
                            <div class="white-box">
                                <div class="text-right mb-20">
                                    <button type="button" data-toggle="modal" data-target="#add_document_madal"
                                        class="primary-btn tr-bg text-uppercase bord-rad">
                                        @lang('hr.upload_document')
                                        <span class="pl ti-upload"></span>
                                    </button>
                                </div>
                                <table id="" class="table simple-table table-responsive school-table"
                                    cellspacing="0">
                                    <thead class="d-block">
                                        <tr class="d-flex">
                                            <th class="col-7">@lang('hr.document_title')</th>
                                            <th class="col-5">@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="d-block">
                                        @if ($staffDetails->joining_letter != '')
                                            <tr class="d-flex">
                                                <td class="col-7">@lang('hr.joining_letter')</td>
                                                <td class="col-5 d-flex align-itemd-center">
                                                    <a href="{{ url($staffDetails->joining_letter) }}" download>
                                                        <button class="primary-btn tr-bg text-uppercase bord-rad">
                                                            @lang('common.download')
                                                            <span class="pl ti-download"></span>
                                                        </button>
                                                    </a>
                                                    <a class="primary-btn icon-only fix-gr-bg ml-2"
                                                        onclick="deleteStaffDoc({{ $staffDetails->id }},1)"
                                                        data-id="1"
                                                        href="#">
                                                        <span class="ti-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($staffDetails->resume != '')
                                            <tr class="d-flex">
                                                <td class="col-7">@lang('hr.resume')</td>
                                                <td class="col-5 d-flex align-itemd-center">
                                                    <a href="{{ url($staffDetails->resume) }}" download>
                                                        <button class="primary-btn tr-bg text-uppercase bord-rad">
                                                            @lang('common.download')
                                                            <span class="pl ti-download"></span>
                                                        </button>
                                                    </a>
                                                    <a class="primary-btn icon-only fix-gr-bg ml-2"
                                                        onclick="deleteStaffDoc({{ $staffDetails->id }},2)"
                                                        data-id="2"
                                                        href="#">
                                                        <span class="ti-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($staffDetails->other_document != '')
                                            <tr class="d-flex">
                                                <td class="col-7">@lang('hr.other_documents')</td>
                                                <td class="col-5 d-flex align-itemd-center">
                                                    <a href="{{ url($staffDetails->other_document) }}" download>
                                                        <button class="primary-btn tr-bg text-uppercase bord-rad">
                                                            @lang('common.download')
                                                            <span class="pl ti-download"></span>
                                                        </button>
                                                    </a>
                                                    <a class="primary-btn icon-only fix-gr-bg ml-2"
                                                        onclick="deleteStaffDoc({{ $staffDetails->id }},3)"
                                                        data-id="3"
                                                        href="#">
                                                        <span class="ti-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if (isset($staffDocumentsDetails))
                                            @foreach ($staffDocumentsDetails as $key => $value)
                                                <tr class="d-flex">
                                                    <td class="col-7">{{ $value->title }}</td>
                                                    <td class="col-5 d-flex align-itemd-center">
                                                        <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                            href="{{ url($value->file) }}" download>
                                                            @lang('common.download')
                                                            <span class="pl ti-download"></span>
                                                        </a>
                                                        <a class="primary-btn icon-only fix-gr-bg modalLink ml-2"
                                                            title="{{ __('hr.delete_document') }}"
                                                            data-modal-size="modal-md"
                                                            href="{{ route('delete-staff-document-view', $value->student_staff_id) }}">
                                                            <span class="ti-trash pt-30"></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Documents Tab -->

                        {{-- Start Timeline Tab --}}
                        <div role="tabpanel" class="tab-pane fade {{ Session::get('staffTimeline') == 'active' ? 'show active' : '' }}" id="staffTimeline">
                                <div class="white-box">
                                    <div class="text-right mb-20">
                                        <button type="button" data-toggle="modal"
                                            data-target="#add_timeline_madal"
                                            class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('common.add')
                                            <span class="pl ti-plus"></span>
                                        </button>
                                    </div>
                                    @if (isset($timelines))
                                        @foreach ($timelines as $timeline)
                                            <div class="student-activities">
                                                <div class="single-activity">
                                                    <h4 class="title text-uppercase">
                                                        {{ $timeline->date != '' ? dateConvert($timeline->date) : '' }}
                                                    </h4>
                                                    <div class="sub-activity-box d-flex">
                                                        <h6 class="time text-uppercase">
                                                            {{ date('h:i a', strtotime($timeline->created_at)) }}
                                                        </h6>
                                                        <div class="sub-activity">
                                                            <h5 class="subtitle text-uppercase">
                                                                {{ $timeline->title }}</h5>
                                                            <p>
                                                                {{ $timeline->description }}
                                                            </p>
                                                        </div>
                                                        <div class="close-activity">
                                                            <a class="primary-btn icon-only fix-gr-bg modalLink"
                                                                title="{{ __('hr.delete_timeline') }}"
                                                                data-modal-size="modal-md"
                                                                href="{{ route('delete-staff-timeline-view', $timeline->id) }}">
                                                                <span class="ti-trash"></span>
                                                            </a>
                                                            @if ($timeline->file != '')
                                                                <a href="{{ url($timeline->file) }}"
                                                                    class="primary-btn tr-bg text-uppercase bord-rad"
                                                                    download>
                                                                    @lang('common.download')
                                                                    <span
                                                                        class="pl ti-download"></span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- End Timeline Tab --}}

                        {{-- delete staff doc  delete modal --}}
                        <div class="modal fade admin-query" id="delete-staff-doc">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('common.delete')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="text-center">
                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                        </div>

                                        <div class="mt-40 d-flex justify-content-between">
                                            <form action="{{ route('staff-document-delete') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="staff_id">
                                                <input type="hidden" name="doc_id">
                                                <button type="button" class="primary-btn tr-bg"
                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                <button type="submit"
                                                    class="primary-btn fix-gr-bg">@lang('common.delete')</button>

                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- delete staff doc delete end --}}

                        <!-- Add Document modal form start-->
                        <div class="modal fade admin-query" id="add_document_madal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('hr.upload_document')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            {{ Form::open([
                                                'class' => 'form-horizontal',
                                                'files' => true,
                                                'route' => 'save_upload_document',
                                                'method' => 'POST',
                                                'enctype' => 'multipart/form-data',
                                                'name' => 'document_upload',
                                            ]) }}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="staff_id"
                                                        value="{{ $staffDetails->id }}">
                                                    <div class="row mt-25">
                                                        <div class="col-lg-12">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">@lang('hr.title') <span class="text-danger"> *</span> </label>
                                                                <input class="primary_input_field form-control" type="text" name="title" id="title" required>
                                                                <span class="text-danger" role="alert" id="amount_error">
                                                                </span>
                                                            </div>
                                                            
                                                                <div class="row no-gutters input-right-icon mt-40">
                                                                    <div class="col">
                                                                        <div class="primary_input">
                                                                            <input class="primary_input_field" id="placeholderInput" type="text" placeholder="@lang('hr.new_document')*" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button class="primary-btn-small-input" type="button">
                                                                            <label class="primary-btn small fix-gr-bg" for="browseFile">@lang('common.browse')</label>
                                                                            <input type="file" class="d-none" id="browseFile" name="staff_upload_document" required>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            <div class="col-lg-12 text-center mt-40">
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                                    <button class="primary-btn fix-gr-bg submit" type="submit">@lang('common.save')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add Document modal form end-->
                        
                        <!-- timeline form modal start-->
                        <div class="modal fade admin-query" id="add_timeline_madal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('common.add_timeline')</h4>
                                        <button type="button" class="close"
                                            data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">

                                            {{ Form::open([
                                                'class' => 'form-horizontal',
                                                'files' => true,
                                                'route' => 'staff_timeline_store',
                                                'method' => 'POST',
                                                'enctype' => 'multipart/form-data',
                                                'name' => 'document_upload',
                                            ]) }}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="staff_student_id"
                                                        value="{{ $staffDetails->id }}">
                                                    <div class="row mt-25">
                                                        <div class="col-lg-12">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('common.title') <span
                                                                        class="text-danger"> *</span>
                                                                </label>
                                                                <input
                                                                    class="primary_input_field form-control{"
                                                                    type="text" name="title"
                                                                    value=""
                                                                    id="title" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-30">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="primary_input">                                                          
                                                            <label class="primary_input_label"
                                                                for="">@lang('common.date') <span
                                                                    class="text-danger"> *</span>
                                                            </label>
                                                            <div class="position-relative">
                                                                <input
                                                                    class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}"
                                                                    id="startDate" type="text"
                                                                    name="date" autocomplete="off"
                                                                    value="{{ date('m/d/Y') }}" required>
                                                                <label for="startDate" class="primary_input-icon pr-2">
                                                                    <i class="ti-calendar"
                                                                    id="admission-date-icon"></i>
                                                                </label>
                                                            </div>
                                                            @if ($errors->has('date_of_birth'))
                                                                <span class="text-danger">
                                                                    {{ $errors->first('date_of_birth') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-30">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label"
                                                            for="">@lang('common.description') <span
                                                                class="text-danger"> *</span> </label>
                                                        <textarea class="primary_input_field form-control" cols="0" rows="3" name="description"
                                                            id="Description" required></textarea>

                                                    </div>
                                                </div>

                                                <div class="col-lg-12 mt-30">
                                                    <div class="d-flex justify-content-between">
                                                        <button class="primary-btn tr-bg" type="button" data-dismiss="modal">Cancel</button>
                                                        <button class="primary-btn fix-gr-bg submit" type="submit">Save</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Timeline Tab -->
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function deleteStaffDoc(id, doc) {
            var modal = $('#delete-staff-doc');
            modal.find('input[name=staff_id]').val(id)
            modal.find('input[name=doc_id]').val(doc)
            modal.modal('show');
        }
    </script>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
