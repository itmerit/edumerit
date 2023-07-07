@extends('backEnd.master')
@section('title')
    @lang('examplan::exp.seat_plan_setting')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('examplan::exp.seat_plan_setting')</h1>

                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('examplan::exp.exam_plan')</a>
                    <a href="#">@lang('examplan::exp.seat_plan_setting')</a>
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
                                        <h3 class="mb-0">@lang('examplan::exp.seat_plan_setting')</h3>
                                    </div>
                                    <form action="{{ route('examplan.seatplan.settingUpdate') }}" method="post" class="bg-white p-4 rounded">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between">
                                                <p class="text-uppercase mb-0">@lang('examplan::exp.school_name')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="school_name" id="school_name_on" value="1" class="common-radio relationButton" @if($setting->school_name) checked @endif>
                                                        <label for="school_name_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="school_name"
                                                                        id="school_name" value="0"
                                                                        class="common-radio relationButton" @if($setting->school_name == 0) checked @endif>
                                                                    <label
                                                                        for="school_name">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between">
                                                <p class="text-uppercase mb-0">@lang('examplan::exp.student_photo')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="student_photo" id="student_photo_on" value="1" class="common-radio relationButton" @if($setting->student_photo) checked @endif>
                                                        <label for="student_photo_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="student_photo"
                                                                        id="student_photo" value="0"
                                                                        class="common-radio relationButton" @if($setting->student_photo == 0) checked @endif>
                                                                    <label
                                                                        for="student_photo">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0"> @lang('examplan::exp.student_name')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="student_name"
                                                                        id="student_name_on" value="1"
                                                                        class="common-radio relationButton" @if($setting->student_name) checked @endif>
                                                                    <label
                                                                        for="student_name_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="student_name"
                                                        id="student_name" value="0"
                                                        class="common-radio relationButton" @if($setting->student_name == 0) checked @endif>
                                                    <label
                                                        for="student_name">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0"> @lang('examplan::exp.admission_no')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="admission_no"
                                                        id="admission_no_on" value="1"
                                                        class="common-radio relationButton" @if($setting->admission_no) checked @endif>
                                                    <label
                                                        for="admission_no_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="admission_no" id="admission_no" value="0" class="common-radio relationButton" @if($setting->admission_no == 0) checked @endif>
                                                        <label for="admission_no">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0"> @lang('student.roll_no')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="roll_no"
                                                        id="roll_no_on" value="1"
                                                        class="common-radio relationButton" @if($setting->roll_no) checked @endif>
                                                    <label
                                                        for="roll_no_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="roll_no" id="roll_no" value="0" class="common-radio relationButton" @if($setting->roll_no == 0) checked @endif>
                                                        <label for="roll_no">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0"> @lang('examplan::exp.class_&_section')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="class_section"
                                                        id="class_section_on" value="1"
                                                        class="common-radio relationButton" @if($setting->class_section) checked @endif>
                                                    <label
                                                        for="class_section_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="class_section" id="class_section" value="0" class="common-radio relationButton" @if($setting->class_section == 0) checked @endif>
                                                        <label for="class_section">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0"> @lang('examplan::exp.exam_name')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="exam_name"
                                                        id="exam_name_on" value="1"
                                                        class="common-radio relationButton" @if($setting->exam_name) checked @endif>
                                                    <label
                                                        for="exam_name_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="exam_name" id="exam_name" value="0" class="common-radio relationButton" @if($setting->exam_name == 0) checked @endif>
                                                        <label for="exam_name">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0"> @lang('examplan::exp.academic_year')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="academic_year"
                                                        id="academic_year_on" value="1"
                                                        class="common-radio relationButton" @if($setting->academic_year) checked @endif>
                                                    <label
                                                        for="academic_year_on">@lang('examplan::exp.show')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="academic_year" id="academic_year" value="0" class="common-radio relationButton" @if($setting->academic_year == 0) checked @endif>
                                                        <label for="academic_year">@lang('examplan::exp.hide')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-20">
                                            <div class="col-lg-12 text-center">
                                                <button class="primary-btn small fix-gr-bg"><i class="ti-check"></i>@lang('common.update')</button>
                                            </div>
                                        </div>   
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
 
