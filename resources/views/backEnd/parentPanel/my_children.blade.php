@extends('backEnd.master')
@push('css')
<style>
    .school-table-style-parent-fees {
        padding: 38px 2px !important;
    }

    #leaves table.dataTable thead .sorting_asc::after,
    #leaves table.dataTable thead .sorting::after,
    #leaves table.dataTable thead .sorting_desc::after {
        top: 10px !important;
        left: 15px !important;
    }

    table.dataTable thead th {
        padding-left: 30px !important;
    }

    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 20px 10px 20px 18px !important;
    }

    .input-right-icon button.primary-btn-small-input {
        top: 8px !important;
        right: 12px !important;
    }
</style>
@if (moduleStatusCheck('University'))
<style>
    .school-table-up-style tr td {
        padding: 8px 6px 8px 0px !important;
        font-size: 12px !important;
    }

    .school-table-style {
        padding: 0px !important;
    }
</style>
@endif
@endpush
@section('title')
@lang('student.student_profile')
@endsection

@section('mainContent')
@php $setting = generalSetting(); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{
$currency = '$'; } @endphp
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3 mb-30">
                <!-- Start Student Meta Information -->
                <div class="main-title">
                    <h3 class="mb-20">@lang('student.student_profile')</h3>
                </div>
                <div class="student-meta-box">
                    <div class="student-meta-top"></div>
                    @if(is_show('photo'))
                    <img class="student-meta-img img-100"
                        src="{{ file_exists(@$student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/student.jpg')}}"
                        alt="">
                    @endif
                    <div class="white-box radius-t-y-0">
                        <div class="single-meta mt-10">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('student.student_name')
                                </div>
                                <div class="value">
                                    {{$student_detail->first_name.' '.$student_detail->last_name}}
                                </div>
                            </div>
                        </div>
                        @if(is_show('admission_number'))
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('student.admission_no')
                                </div>
                                <div class="value">
                                    {{$student_detail->admission_no}}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(generalSetting()->multiple_roll==0)
                        @if(is_show('roll_number'))
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @if(moduleStatusCheck('Lead')==true)
                                    @lang('student.id_number')
                                    @else
                                    @lang('student.roll_number')
                                    @endif
                                </div>
                                <div class="value">
                                    {{$student_detail->roll_no}}
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('student.class')
                                </div>
                                <div class="value">
                                    @if($student_detail->defaultClass!="")
                                    {{@$student_detail->defaultClass->class->class_name}}
                                    {{-- ({{@$academic_year->year}}) --}}
                                    @elseif ($student_detail->studentRecord !="")
                                    {{@$student_detail->studentRecord->class->class_name}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('student.section')
                                </div>
                                <div class="value">

                                    @if($student_detail->defaultClass!="")
                                    {{@$student_detail->defaultClass->section->section_name}}

                                    @elseif ($student_detail->studentRecord !="")
                                    {{@$student_detail->studentRecord->section->section_name}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(is_show('gender'))
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('common.gender')
                                </div>
                                <div class="value">
                                    {{@$student_detail->gender !=""?@$student_detail->gender->base_setup_name:""}}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- End Student Meta Information -->
            </div>

            <!-- Start Student Details -->
            <div class="col-lg-9">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#studentProfile" role="tab"
                            data-toggle="tab">@lang('student.profile')</a>
                    </li>
                    @if(generalSetting()->fees_status == 0 && isMenuAllowToShow('fees'))
                    <li class="nav-item">
                        <a class="nav-link" href="#studentFees" role="tab"
                            data-toggle="tab">@lang('fees.fees')</a>
                    </li>
                    @endif
                    @if(isMenuAllowToShow('leave'))
                    <li class="nav-item">
                        <a class="nav-link" href="#leaves" role="tab" data-toggle="tab">@lang('leave.leave')</a>
                    </li>
                    @endif
                    @if(isMenuAllowToShow('examination'))
                    <li class="nav-item">
                        <a class="nav-link" href="#studentExam" role="tab"
                            data-toggle="tab">@lang('exam.exam')</a>
                    </li>
                    @endif
                    @if(moduleStatusCheck('University'))
                    <li class="nav-item">
                        <a class="nav-link" href="#parentPanelExamTranscript" role="tab"
                            data-toggle="tab">@lang('university::un.transcript')</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="#studentDocuments" role="tab"
                            data-toggle="tab">@lang('student.documents')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#studentTimeline" role="tab"
                            data-toggle="tab">@lang('student.student_record')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Session::get('studentAttendance') == 'active' ? 'active' : '' }} "
                            href="#studentAttendance" role="tab" data-toggle="tab">@lang('student.student_attendance')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Session::get('subjectAttendance') == 'active' ? 'active' : '' }} "
                            href="#subjectAttendance" role="tab" data-toggle="tab">@lang('student.subject_attendance')</a>
                    </li>
                    @if(moduleStatusCheck('Wallet'))
                    @if(userPermission("wallet.my-wallet"))
                    <li class="nav-item">
                        <a class="nav-link {{Session::get('parentWallet') == 'active'? 'active':''}} "
                            href="#parentWallet" role="tab"
                            data-toggle="tab">@lang('wallet::wallet.wallet')</a>
                    </li>
                    @endif
                    @endif
                    <li class="nav-item edit-button mb-2">
                        <a href="{{route('update-my-children',$student_detail->id)}}"
                            class="primary-btn small fix-gr-bg pull-right">{{__('Update Profile')}}</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Start Profile Tab -->
                    <div role="tabpanel" class="tab-pane fade  show active" id="studentProfile">
                        <div class="white-box">
                            <h4 class="stu-sub-head">@lang('student.personal_info')</h4>
                            @if(is_show('admission_date'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.admission_date')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{@$student_detail->admission_date != ""? dateConvert(@$student_detail->admission_date):''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('date_of_birth'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('common.date_of_birth')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->date_of_birth != ""? dateConvert(@$student_detail->date_of_birth ):''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('student_category_id'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('common.type')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->category->category_name}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('religion'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('student.religion')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            @if(!empty($student_detail->religion))
                                            {{@$student_detail->religion->base_setup_name}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('phone_number'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('common.phone_number')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{$student_detail->mobile}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('email_address'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('common.email_address')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{$student_detail->email}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{-- changes for lead module --abunayem--}}
                            @if(moduleStatusCheck('Lead')==true)
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lead::lead.city')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->leadCity->city_name}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lead::lead.source')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->source->source_name}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{-- end --}}
                            @if(is_show('current_address'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('student.present_address')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{$student_detail->current_address}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('permanent_address'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('student.permanent_address')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{$student_detail->permanent_address}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- Start Parent Part -->
                            <h4 class="stu-sub-head mt-40">@lang('student.Parent_Guardian_Details')</h4>
                            <div class="d-flex">
                                @if(is_show('fathers_photo'))
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100"
                                        src="{{ file_exists(@$student_detail->parents->fathers_photo) ? asset($student_detail->parents->fathers_photo) : asset('public/uploads/staff/demo/father.png') }}"
                                        alt="">
                                </div>
                                @endif
                                <div class="w-100">
                                    @if(is_show('fathers_name'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.father_name')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->fathers_name}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(is_show('fathers_occupation'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.occupation')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->fathers_occupation}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(is_show('fathers_phone'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('common.phone_number')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->fathers_mobile}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex">
                                @if(is_show('mothers_photo'))
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100"
                                        src="{{ file_exists(@$student_detail->parents->mothers_photo) ? asset($student_detail->parents->mothers_photo) : asset('public/uploads/staff/demo/mother.jpg')}}"
                                        alt="">
                                </div>
                                @endif
                                <div class="w-100">
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.mother_name')
                                                </div>
                                            </div>
                                            @if(is_show('mothers_name'))
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->mothers_name}}
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.occupation')
                                                </div>
                                            </div>
                                            @if(is_show('mothers_occupation'))
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->mothers_occupation}}
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if(is_show('mothers_phone'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('common.phone_number')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->mothers_mobile}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex">
                                @if(is_show('guardians_photo'))
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100"
                                        src="{{ file_exists(@$student_detail->parents->guardians_photo) ? asset($student_detail->parents->guardians_photo) : asset('public/uploads/staff/demo/guardian.jpg')}}"
                                        alt="">
                                </div>
                                @endif
                                <div class="w-100">
                                    @if(is_show('guardians_name'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.guardian_name')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->guardians_name}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(is_show('guardians_email'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('common.email_address')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->guardians_email}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(is_show('guardians_phone'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('common.phone_number')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->guardians_mobile}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.relation_with_guardian')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->guardians_relation}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(is_show('guardians_occupation'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.occupation')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->guardians_occupation}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(is_show('guardians_address'))
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('student.guardian_address')
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    @if(!empty($student_detail->parents))
                                                    {{$student_detail->parents->guardians_address}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <!-- End Parent Part -->
                            <!-- Start Transport Part -->
                            @if(isMenuAllowToShow('transport') || isMenuAllowToShow('dormitory'))
                            <h4 class="stu-sub-head mt-40">@lang('student.'.(isMenuAllowToShow('transport')? 'transport'
                                : ''). (isMenuAllowToShow('transport') && isMenuAllowToShow('dormitory')? '_and_' :
                                '').(isMenuAllowToShow('dormitory')? 'dormitory' : '').'_details')</h4>
                            @if(isMenuAllowToShow('transport'))
                            @if(is_show('route'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('common.route')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->route)? @$student_detail->route->title: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('vehicle'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('transport.vehicle_number')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->vechile_id)? $student_detail->vehicle->vehicle_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('transport.driver_name')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->vechile_id)? $driver->full_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('transport.driver_phone_number')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{$student_detail->vechile_id != ""? $driver->mobile: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('dormitory_name') && isMenuAllowToShow('dormitory'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('dormitory.dormitory_name')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{$student_detail->dormitory_id != ""? $student_detail->dormitory->dormitory_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                            <!-- End Transport Part -->
                            <!-- Start Other Information Part -->
                            <h4 class="stu-sub-head mt-40">@lang('student.other_information')</h4>
                            @if(is_show('blood_group'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.blood_group')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bloodgroup_id)? @$student_detail->bloodGroup->base_setup_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('height'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.height')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->height)? $student_detail->height: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('weight'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.weight')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->weight)? $student_detail->weight: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('previous_school_details'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.previous_school_details')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->previous_school_details)? $student_detail->previous_school_details: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('national_id_number'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.national_identification_number')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->national_id_no)? $student_detail->national_id_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('local_id_number'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.local_identification_number')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->local_id_no)? $student_detail->local_id_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('bank_account_number'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('accounts.bank_account_number')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bank_account_no)? $student_detail->bank_account_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('bank_name'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('accounts.bank_name')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bank_name)? $student_detail->bank_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('ifsc_code'))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.ifsc_code')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->ifsc_code)? $student_detail->ifsc_code: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(is_show('custom_field'))
                            {{-- Custom field start --}}
                            @include('backEnd.customField._coutom_field_show')
                            {{-- Custom field end --}}
                            @endif
                            <!-- End Other Information Part -->

                            {{-- Custom field start --}}
                            @include('backEnd.customField._coutom_field_show')
                            {{-- Custom field end --}}

                        </div>
                    </div>
                    <!-- End Profile Tab -->
                    <!-- Start Fees Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="studentFees">
                        @foreach ($records as $record)
                        <div class="white-box no-search no-paginate no-table-info mb-2">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="main-title">
                                        <h3 class="mb-10">
                                            @if (moduleStatusCheck('University'))
                                            {{ $record->semesterLabel->name }} ({{ $record->unSection->section_name }})
                                            -
                                            {{ @$record->unAcademic->name }}
                                            @else
                                            {{ $record->class->class_name }} ({{ $record->section->section_name }})
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-10">
                                    <table class="table school-table-style res_scrol school-table-up-style"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('fees.fees_type')</th>
                                                <th>@lang('fees.assigned_date')</th>
                                                <th>@lang('fees.amount')</th>
                                            </tr>
                                        </thead>
                                        @php $gt_fees = 0; @endphp
                                        <tbody>
                                            @foreach ($record->fees as $assign_fees)
                                            @php $gt_fees += @$assign_fees->feesGroupMaster->amount; @endphp
                                            <tr>
                                                <td>{{ @$assign_fees->feesGroupMaster->feesTypes->name }}</td>
                                                <td>{{ dateConvert($assign_fees->created_at) }}</td>
                                                <td>
                                                    {{ currency_format(@$assign_fees->feesGroupMaster->amount) }}</td>
                                            </tr>
                                            @endforeach
                                        <tfoot>
                                            <tr>
                                                <th>@lang('fees.grand_total') ({{ generalSetting()->currency_symbol }})
                                                </th>
                                                <th></th>
                                                <th>{{ currency_format($gt_fees) }}</th>
                                            </tr>
                                        </tfoot>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-3 mb-10">
                                    @if (moduleStatusCheck('University'))
                                    <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-lg"
                                        title="@lang('fees.add_fees')"
                                        href="{{ route('university.un-total-fees-modal', [$record->id]) }}"> <i
                                            class="ti-plus pr-2"> </i> @lang('fees.add_fees') </a>
                                    @elseif(directFees())
                                    <a class="primary-btn small fix-gr-bg modalLink" data-modal-size="modal-lg"
                                        title="@lang('fees.add_fees')"
                                        href="{{ route('direct-fees-total-payment', [$record->id]) }}">
                                        <i class="ti-plus pr-2"> </i> @lang('fees.add_fees') </a>
                                    @endif
                                </div>
                            </div>
                            <div class="table-responsive">
                                @if (moduleStatusCheck('University'))
                                @includeIf('university::include.studentFeesTableView')
                                @elseif(directFees())
                                @includeIf('backEnd.feesCollection.directFees.studentDirectFeesTableView')
                                @else
                                <table class="table school-table-style res_scrol" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('fees.fees_group')</th>
                                            <th>@lang('fees.fees_code')</th>
                                            <th>@lang('fees.due_date')</th>
                                            <th>@lang('fees.Status')</th>
                                            <th>@lang('fees.amount') ({{ @$currency }})</th>
                                            <th>@lang('fees.payment_ID')</th>
                                            <th>@lang('fees.mode')</th>
                                            <th>@lang('common.date')</th>
                                            <th>@lang('fees.discount') ({{ @$currency }})</th>
                                            <th>@lang('fees.fine') ({{ @$currency }})</th>
                                            <th>@lang('fees.paid') ({{ @$currency }})</th>
                                            <th>@lang('fees.balance') ({{ @$currency }})</th>
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
                                        @endphp
                                        @foreach ($record->fees as $fees_assigned)
                                        @if ($fees_assigned->record_id == $record->id)
                                        @php
                                        @$grand_total += @$fees_assigned->feesGroupMaster->amount;
                                        @endphp
                                        @php
                                        @$discount_amount = $fees_assigned->applied_discount;
                                        @$total_discount += @$discount_amount;
                                        @$student_id = @$fees_assigned->student_id;
                                        @endphp
                                        @php
                                        @$paid = App\SmFeesAssign::discountSum(@$fees_assigned->student_id,
                                        @$fees_assigned->feesGroupMaster->feesTypes->id, 'amount',
                                        $fees_assigned->record_id);
                                        @$total_grand_paid += @$paid;
                                        @endphp
                                        @php
                                        @$fine = App\SmFeesAssign::discountSum(@$fees_assigned->student_id,
                                        @$fees_assigned->feesGroupMaster->feesTypes->id, 'fine',
                                        $fees_assigned->record_id);
                                        @$total_fine += @$fine;
                                        @endphp

                                        @php
                                        @$total_paid = @$discount_amount + @$paid;
                                        @endphp
                                        <tr>
                                            <td>{{ @$fees_assigned->feesGroupMaster->feesGroups != '' ? @$fees_assigned->feesGroupMaster->feesGroups->name : '' }}
                                            </td>
                                            <td>{{ @$fees_assigned->feesGroupMaster->feesTypes != '' ? @$fees_assigned->feesGroupMaster->feesTypes->name : '' }}
                                            </td>
                                            <td>
                                                @if (!empty(@$fees_assigned->feesGroupMaster))
                                                {{ @$fees_assigned->feesGroupMaster->date != '' ? dateConvert(@$fees_assigned->feesGroupMaster->date) : '' }}
                                                @endif
                                            </td>
                                            @php
                                            $total_payable_amount = $fees_assigned->fees_amount;
                                            $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                            $balance_amount = number_format($rest_amount + $fine, 2, '.', '');
                                            $total_balance += $balance_amount;
                                            @endphp
                                            <td>
                                                @if ($balance_amount == 0)
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
                                            <td>
                                                @php
                                                echo number_format($fees_assigned->feesGroupMaster->amount, 2, '.', '');
                                                @endphp
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td> {{ @$discount_amount }} </td>
                                            <td>{{ @$fine }}</td>
                                            <td>{{ @$paid }}</td>
                                            <td>
                                                @php echo @$balance_amount; @endphp
                                            </td>
                                        </tr>
                                        @php
                                        @$payments =
                                        App\SmFeesAssign::feesPayment(@$fees_assigned->feesGroupMaster->feesTypes->id,
                                        @$fees_assigned->student_id, $fees_assigned->record_id);
                                        $i = 0;
                                        @endphp
                                        @foreach ($payments as $payment)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"><img
                                                    src="{{ asset('public/backEnd/img/table-arrow.png') }}"></td>
                                            <td>
                                                @php
                                                @$created_by = App\User::find(@$payment->created_by);
                                                @endphp
                                                @if (@$created_by != '')
                                                <a href="#" data-toggle="tooltip" data-placement="right"
                                                    title="{{ 'Collected By: ' . @$created_by->full_name }}">{{ @$payment->fees_type_id . '/' . @$payment->id }}</a>
                                            </td>
                                            @endif
                                            <td>{{ $payment->payment_mode }}</td>
                                            <td class="nowrap">
                                                {{ @$payment->payment_date != '' ? dateConvert(@$payment->payment_date) : '' }}
                                            </td>
                                            <td>{{ @$payment->discount_amount }}</td>
                                            <td>
                                                {{ $payment->fine }}
                                                @if ($payment->fine != 0)
                                                @if (strlen($payment->fine_title) > 14)
                                                <span class="text-danger nowrap" title="{{ $payment->fine_title }}">
                                                    ({{ substr($payment->fine_title, 0, 15) . '...' }})
                                                </span>
                                                @else
                                                @if ($payment->fine_title == '')
                                                {{ $payment->fine_title }}
                                                @else
                                                <span class="text-danger nowrap">
                                                    ({{ $payment->fine_title }})
                                                </span>
                                                @endif
                                                @endif
                                                @endif
                                            </td>
                                            <td>{{ @$payment->amount }}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>@lang('fees.grand_total') ({{ @$currency }})</th>
                                            <th></th>
                                            <th>{{ @$grand_total }}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>{{ @$total_discount }}</th>
                                            <th>{{ @$total_fine }}</th>
                                            <th>{{ @$total_grand_paid }}</th>
                                            <th>{{ number_format($total_balance, 2, '.', '') }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- End Profile Tab -->
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
                                            @if(count($leave_details)>0)
                                            @foreach($leave_details as $value)

                                            <tr>
                                                <td>{{@$value->leaveType->type}}</td>
                                                <td>

                                                    {{$value->leave_from != ""? dateConvert($value->leave_from):''}}

                                                </td>
                                                <td>

                                                    {{$value->leave_to != ""? dateConvert($value->leave_to):''}}

                                                </td>
                                                <td>

                                                    {{$value->apply_date != ""? dateConvert($value->apply_date):''}}

                                                </td>
                                                <td>

                                                    @if($value->approve_status == 'P')
                                                    <button class="primary-btn small bg-warning text-white border-0">
                                                        @lang('common.pending')</button>
                                                    @endif

                                                    @if($value->approve_status == 'A')
                                                    <button class="primary-btn small bg-success text-white border-0">
                                                        @lang('common.approved')</button>
                                                    @endif

                                                    @if($value->approve_status == 'C')
                                                    <button class="primary-btn small bg-danger text-white border-0">
                                                        @lang('common.cancelled')</button>
                                                    @endif

                                                </td>
                                                <td>
                                                    <a class="modalLink" data-modal-size="modal-md"
                                                        title="@lang('common.view_leave_details')"
                                                        href="{{url('view-leave-details-apply', $value->id)}}">
                                                        <button class="primary-btn small tr-bg"> @lang('common.view')
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>@lang('leave.not_leaves_data')</td>
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
                    <!-- Start Exam Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="studentExam">
                        @if(moduleStatusCheck('University'))
                        @includeIf('university::exam.student_exam_tab')
                        @else
                        @foreach ($student_detail->studentRecords as $record)
                        @php
                        $today = date('Y-m-d H:i:s');
                        $exam_count= count($exam_terms);
                        @endphp
                        @if($exam_count < 1)
                            <div class="white-box no-search no-paginate no-table-info mb-2">
                            <table class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.subject')</th>
                                        <th>@lang('exam.full_marks')</th>
                                        <th>@lang('exam.passing_marks')</th>
                                        <th>@lang('exam.obtained_marks')</th>
                                        <th>@lang('exam.results')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        @endif
                        <div class="white-box no-search no-paginate no-table-info mb-2">
                            @foreach($exam_terms as $exam)
                            @php
                            $get_results = App\SmStudent::getExamResult(@$exam->id, @$record);
                            @endphp
                            @if($get_results)
                            <div class="main-title">
                                <h3 class="mb-0">{{@$exam->title}}</h3>
                            </div>
                            @php
                            $grand_total = 0;
                            $grand_total_marks = 0;
                            $result = 0;
                            $temp_grade=[];
                            $total_gpa_point = 0;
                            $total_subject = count($get_results);
                            $optional_subject = 0;
                            $optional_gpa = 0;
                            @endphp
                            @isset($exam->examSettings->publish_date)
                            @if($exam->examSettings->publish_date <= $today)
                                <table id="table_id" class="display school-table"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.date')</th>
                                        <th>@lang('exam.subject_full_marks')</th>
                                        <th>@lang('exam.obtained_marks')</th>
                                        @if (@generalSetting()->result_type == 'mark')
                                        <th>@lang('exam.pass_fail')</th>
                                        @else
                                        <th>@lang('exam.grade')</th>
                                        <th>@lang('exam.gpa')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($get_results as $mark)
                                    @php
                                    if((!is_null($optional_subject_setup)) && (!is_null($student_optional_subject))){
                                    if($mark->subject_id != @$student_optional_subject->subject_id){
                                    $temp_grade[]=$mark->total_gpa_grade;
                                    }
                                    }else{
                                    $temp_grade[]=$mark->total_gpa_grade;
                                    }
                                    $total_gpa_point += $mark->total_gpa_point;
                                    if(! is_null(@$student_optional_subject)){
                                    if(@$student_optional_subject->subject_id == $mark->subject->id &&
                                    $mark->total_gpa_point < @$optional_subject_setup->gpa_above ){
                                        $total_gpa_point = $total_gpa_point - $mark->total_gpa_point;
                                        }
                                        }
                                        $temp_gpa[]=$mark->total_gpa_point;
                                        $get_subject_marks = subjectFullMark ($mark->exam_type_id, $mark->subject_id,
                                        $mark->studentRecord->class_id, $mark->studentRecord->section_id);

                                        $subject_marks = App\SmStudent::fullMarksBySubject($exam->id, $mark->subject_id);
                                        $schedule_by_subject = App\SmStudent::scheduleBySubject($exam->id,
                                        $mark->subject_id, @$record);
                                        $result_subject = 0;
                                        if(@generalSetting()->result_type == 'mark'){
                                        $grand_total_marks += subject100PercentMark();
                                        }else{
                                        $grand_total_marks += $get_subject_marks;
                                        }
                                        if(@$mark->is_absent == 0){
                                        if(@generalSetting()->result_type == 'mark'){
                                        $grand_total += @subjectPercentageMark(@$mark->total_marks,
                                        @subjectFullMark($mark->exam_type_id, $mark->subject_id,
                                        $mark->studentRecord->class_id, $mark->studentRecord->section_id));
                                        }else{
                                        $grand_total += @$mark->total_marks;
                                        }
                                        if($mark->marks < $subject_marks->pass_mark){
                                            $result_subject++;
                                            $result++;
                                            }
                                            }else{
                                            $result_subject++;
                                            $result++;
                                            }
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ !empty($schedule_by_subject->date)? dateConvert($schedule_by_subject->date):''}}
                                                </td>
                                                <td>
                                                    {{@$mark->subject->subject_name}}
                                                    @if (@generalSetting()->result_type == 'mark')
                                                    ({{subject100PercentMark()}})
                                                    @else
                                                    ({{ @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id) }})
                                                    @endif

                                                </td>
                                                <td>
                                                    @if (@generalSetting()->result_type == 'mark')
                                                    {{@subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id))}}
                                                    @else
                                                    {{@$mark->total_marks}}
                                                    @endif
                                                </td>
                                                @if(@generalSetting()->result_type == 'mark')
                                                <td>
                                                    @php
                                                    $totalMark = subjectPercentageMark(@$mark->total_marks,
                                                    @subjectFullMark($mark->exam_type_id, $mark->subject_id,
                                                    $mark->studentRecord->class_id, $mark->studentRecord->section_id));
                                                    $passMark = $mark->subject->pass_mark;
                                                    @endphp
                                                    @if ($passMark <= $totalMark)
                                                        @lang('exam.pass')
                                                        @else
                                                        @lang('exam.fail')
                                                        @endif
                                                        </td>
                                                        @else
                                                        <td>
                                                        {{@$mark->total_gpa_grade}}
                                                </td>
                                                <td>
                                                    {{number_format(@$mark->total_gpa_point, 2, '.', '')}}
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>@lang('exam.position')
                                            :
                                            {{getStudentMeritPosition($record->class_id, $record->section_id, $exam->id, $record->id) ?? "null"}}
                                        </th>
                                        <th>
                                            @lang('exam.grand_total'): {{$grand_total}}
                                            /{{$grand_total_marks}}
                                        </th>
                                        @if (@generalSetting()->result_type == 'mark')
                                        <th></th>
                                        @else
                                        <th>@lang('exam.grade'):
                                            @php
                                            if(in_array($failgpaname->grade_name,$temp_grade)){
                                            echo $failgpaname->grade_name;
                                            }else {
                                            $final_gpa_point = ($total_gpa_point- $optional_gpa) / ($total_subject -
                                            $optional_subject);
                                            $average_grade=0;
                                            $average_grade_max=0;
                                            if($result == 0 && $grand_total_marks != 0){
                                            $gpa_point=number_format($final_gpa_point, 2, '.', '');
                                            if($gpa_point >= $maxgpa){
                                            $average_grade_max =
                                            App\SmMarksGrade::where('school_id',Auth::user()->school_id)
                                            ->where('academic_id', getAcademicId() )
                                            ->where('from', '<=', $maxgpa )
                                                ->where('up', '>=', $maxgpa )
                                                ->first('grade_name');

                                                echo @$average_grade_max->grade_name;
                                                } else {
                                                $average_grade =
                                                App\SmMarksGrade::where('school_id',Auth::user()->school_id)
                                                ->where('academic_id', getAcademicId() )
                                                ->where('from', '<=', $final_gpa_point )
                                                    ->where('up', '>=', $final_gpa_point )
                                                    ->first('grade_name');
                                                    echo @$average_grade->grade_name;
                                                    }
                                                    }else{
                                                    echo $failgpaname->grade_name;
                                                    }
                                                    }
                                                    @endphp
                                        </th>
                                        <th>
                                            @lang('exam.gpa')
                                            @php
                                            $final_gpa_point = 0;
                                            $final_gpa_point = ($total_gpa_point - $optional_gpa)/ ($total_subject -
                                            $optional_subject);
                                            $float_final_gpa_point=number_format($final_gpa_point,2);
                                            if($float_final_gpa_point >= $maxgpa){
                                            echo $maxgpa;
                                            }else {
                                            echo $float_final_gpa_point;
                                            }
                                            @endphp
                                        </th>
                                        @endif
                                    </tr>
                                </tfoot>
                                </table>
                                @endif
                                @endisset
                                @endif
                                @endforeach
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <!-- End Exam Tab -->
                    @if(moduleStatusCheck('University'))
                    <div role="tabpanel" class="tab-pane fade" id="parentPanelExamTranscript">
                        @includeIf('university::exam.partials._examTabView')
                    </div>
                    <script src="{{url('Modules\University\Resources\assets\js\app.js')}}"></script>
                    @endif
                    <!-- Start Documents Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="studentDocuments">
                        <div class="white-box">
                            <table id="" class="table simple-table table-responsive school-table"
                                cellspacing="0">
                                <thead class="d-block">
                                    <tr class="d-flex">
                                        <th class="col-3">@lang('student.document_title')</th>
                                        <th class="col-6">@lang('common.name')</th>
                                        <th class="col-3">@lang('common.action')</th>
                                    </tr>
                                </thead>

                                <tbody class="d-block">
                                    @if(is_show('document_file_1'))
                                    @if($student_detail->document_file_1 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_1}} </td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_1)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            {{-- @if(userPermission(17)) --}}
                                            <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                <a href="{{asset($student_detail->document_file_1)}}"
                                                    download>@lang('common.download')</a>
                                                <span class="pl ti-download"></span>
                                            </button>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(is_show('document_file_2'))
                                    @if($student_detail->document_file_2 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_2}}</td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_2)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            {{-- @if(userPermission(17)) --}}
                                            <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                <a href="{{asset($student_detail->document_file_2)}}"
                                                    download>@lang('common.download')</a>
                                                <span class="pl ti-download"></span>
                                            </button>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(is_show('document_file_3'))
                                    @if($student_detail->document_file_3 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_3}}</td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_3)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            {{-- @if(userPermission(17)) --}}
                                            <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                <a href="{{asset($student_detail->document_file_3)}}"
                                                    download>@lang('common.download')</a>
                                                <span class="pl ti-download"></span>
                                            </button>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(is_show('document_file_4'))
                                    @if($student_detail->document_file_4 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_4}}</td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_4)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            {{-- @if(userPermission(17)) --}}
                                            <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                <a href="{{asset($student_detail->document_file_4)}}"
                                                    download>@lang('common.download')</a>
                                                <span class="pl ti-download"></span>
                                            </button>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif

                                    @foreach($documents as $document)
                                    <tr class="d-flex">
                                        <td class="col-3">{{@$document->title}}</td>
                                        <td class="col-6">{{showDocument(@$document->file)}}</td>
                                        <td class="col-3">
                                            {{-- @if(userPermission(17)) --}}
                                            <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                href="{{url(@$document->file)}}" download>
                                                @lang('common.download')<span class="pl ti-download"></span>
                                            </a>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Documents Tab -->
                    <!-- Add Document modal form start-->
                    <div class="modal fade admin-query" id="add_document_madal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('student.upload_documents')</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <div class="container-fluid">
                                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'upload_document',
                                                                    'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="hidden" name="student_id" value="{{$student_detail->id}}">
                                                <div class="row mt-25">
                                                    <div class="col-lg-12">
                                                        <div class="primary_input">
                                                            <input class="primary_input_field form-control{" type="text"
                                                                name="title" value="" id="title">
                                                            <label class="primary_input_label" for="">@lang('common.title')
                                                                <span class="text-danger"> *</span> </label>


                                                            <span class=" text-danger" role="alert"
                                                                id="amount_error">

                                                            </span>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-30">
                                                    <div class="row no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="primary_input">
                                                                <input class="primary_input_field" type="text"
                                                                    id="placeholderPhoto" placeholder="Document"
                                                                    disabled>

                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button class="primary-btn-small-input" type="button">
                                                                <label class="primary-btn small fix-gr-bg"
                                                                    for="photo">@lang('common.browse')</label>
                                                                <input type="file" class="d-none" name="photo" id="photo">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- <div class="col-lg-12 text-center mt-40">
                                                            <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                                                <span class="ti-check"></span>
                                                                save information
                                                            </button>
                                                        </div> -->
                                                <div class="col-lg-12 text-center mt-40">
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                            data-dismiss="modal">@lang('common.cancel')</button>

                                                        <button class="primary-btn fix-gr-bg submit"
                                                            type="submit">@lang('common.save')</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-30">
                                                <div class="row no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="input-effect">
                                                            <input class="primary-input" type="text"
                                                                id="placeholderPhoto" placeholder="Document"
                                                                disabled>
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="primary-btn-small-input" type="button">
                                                            <label class="primary-btn small fix-gr-bg"
                                                                for="photo">@lang('common.browse')</label>
                                                            <input type="file" class="d-none" name="photo"
                                                                id="photo">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- <div class="col-lg-12 text-center mt-40">
                                                        <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                                            <span class="ti-check"></span>
                                                            save information
                                                        </button>
                                                    </div> -->
                                            <div class="col-lg-12 text-center mt-40">
                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg"
                                                        data-dismiss="modal">@lang('common.cancel')</button>

                                                    <button class="primary-btn fix-gr-bg submit"
                                                        type="submit">@lang('common.save')</button>
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
                    <!-- Start Timeline Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="studentTimeline">
                        <div class="white-box">

                            <table id="" class="table simple-table table-responsive school-table"
                                cellspacing="0">
                                <thead class="d-block">
                                    <tr class="d-flex">
                                        <th class="col-4">@lang('common.class')</th>
                                        <th class="col-4">@lang('common.section')</th>
                                        <th class="col-4">@lang('student.id_number')</th>
                                    </tr>
                                </thead>

                                <tbody class="d-block">
                                    @foreach ($student_detail->studentRecords as $record)
                                    <tr class="d-flex">
                                        <td class="col-4">{{ $record->class->class_name }}</td>
                                        <td class="col-4">{{ $record->section->section_name }}</td>
                                        <td class="col-4">{{ $record->roll_no }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Timeline Tab -->
                    <!-- Start Attendance Tab -->
                    @include('backEnd.parentPanel.inc._student_attendance_tab')
                    <!-- End Attendance Tab -->
                    <!-- Start Attendance Tab -->
                    @include('backEnd.parentPanel.inc._subject_attendance_tab')
                    <!-- End Attendance Tab -->
                    @if(moduleStatusCheck('Wallet'))
                    <div role="tabpanel" class="tab-pane fade" id="parentWallet">
                        <div class="white-box">
                            @include('wallet::_addWallet',compact('walletAmounts', 'bankAccounts', 'paymentMethods'))
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- End Student Details -->
        </div>
    </div>
</section>

<!-- timeline form modal start-->
<div class="modal fade admin-query" id="add_timeline_madal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('student.add_timeline')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_timeline_store',
                                            'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" name="student_id" value="{{$student_detail->id}}">
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="primary_input">
                                        <input class="primary_input_field form-control{" type="text" name="title"
                                            value="" id="title">
                                        <label class="primary_input_label" for="">@lang('common.title') <span
                                                class="text-danger"> *</span> </label>


                                        <span class=" text-danger" role="alert" id="amount_error">

                                        </span>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input
                                                class="primary_input_field  primary_input_field date form-control form-control"
                                                id="startDate" type="text"
                                                name="date">
                                            <label class="primary_input_label" for="">@lang('common.date')</label>

                                        </div>
                                    </div>
                                    <button class="" type="button">
                                        <i class="ti-calendar" id="admission-date-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="primary_input">
                                    <textarea class="primary_input_field form-control" cols="0" rows="3"
                                        name="description" id="Description"></textarea>
                                    <label class="primary_input_label" for="">@lang('common.description')<span></span>
                                    </label>

                                </div>
                            </div>

                            <div class="col-lg-12 mt-30">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text" id="placeholderFileFourName"
                                                placeholder="Document"
                                                disabled>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="document_file_4">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_4"
                                                id="document_file_4">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">

                                <input type="checkbox" id="currentAddressCheck" class="common-checkbox"
                                    name="visible_to_student" value="1">
                                <label for="currentAddressCheck">@lang('student.visible_to_this_person')</label>
                            </div>


                            <!-- <div class="col-lg-12 text-center mt-40">
                                    <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                        <span class="ti-check"></span>
                                        save information
                                    </button>
                                </div> -->
                            <div class="col-lg-12 text-center mt-40">
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                        data-dismiss="modal">@lang('common.cancel')</button>

                                    <button class="primary-btn fix-gr-bg submit"
                                        type="submit">@lang('common.save')</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input date form-control" id="startDate" type="text"
                                            name="date">
                                        <label>@lang('common.date')</label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="" type="button">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">
                            <div class="input-effect">
                                <textarea class="primary-input form-control" cols="0" rows="3" name="description"
                                    id="Description"></textarea>
                                <label>@lang('common.description')<span></span> </label>
                                <span class="focus-border textarea"></span>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-30">
                            <div class="row no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input" type="text" id="placeholderFileFourName"
                                            placeholder="Document"
                                            disabled>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="primary-btn-small-input" type="button">
                                        <label class="primary-btn small fix-gr-bg"
                                            for="document_file_4">@lang('common.browse')</label>
                                        <input type="file" class="d-none" name="document_file_4"
                                            id="document_file_4">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">

                            <input type="checkbox" id="currentAddressCheck" class="common-checkbox"
                                name="visible_to_student" value="1">
                            <label for="currentAddressCheck">@lang('student.visible_to_this_person')</label>
                        </div>


                        <!-- <div class="col-lg-12 text-center mt-40">
                                <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                    <span class="ti-check"></span>
                                    save information
                                </button>
                            </div> -->
                        <div class="col-lg-12 text-center mt-40">
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg"
                                    data-dismiss="modal">@lang('common.cancel')</button>

                                <button class="primary-btn fix-gr-bg submit"
                                    type="submit">@lang('common.save')</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>
<!-- timeline form modal end-->




@endsection
{{-- @include('backEnd.partials.data_table_js') --}}
@include('backEnd.partials.date_picker_css_js')