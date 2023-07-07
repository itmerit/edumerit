@extends('backEnd.master')
@section('title')
    @lang('examplan::exp.admit_card_setting')
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/editor/summernote-bs4.css') }}">
    <style>
        .img_prevView {
            height: 78px;
            width: 110px;
        }

        .input-right-icon button i {
            position: relative;
            top: 0px !important;
        }
        .dropdown-toggle::after {
            display: none !important;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('examplan::exp.admit_card_setting')</h1>

                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('examplan::exp.exam_plan')</a>
                    <a href="#">@lang('examplan::exp.admit_card_setting')</a>
                </div>
            </div>
        </div>
    </section>

    {{-- new design for admit setting  --}}

    <section class="mb-40 student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <!-- Start Sms Details -->
                <div class="col-lg-12">
                    <ul class="nav nav-tabs tab_column" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" href="#select_layout" role="tab"
                               data-toggle="tab">@lang('system_settings.select_a_layout')</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if ($setting->admit_layout == 1) active @endif" href="#layout_one"
                               role="tab" data-toggle="tab">@lang('examplan::exp.layout_one')</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if ($setting->admit_layout == 2) show active @endif" href="#layout_two"
                               role="tab" data-toggle="tab">@lang('examplan::exp.layout_two') </a>
                        </li>
                    </ul>


                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane fade" id="select_layout">
                            <div class="white-box mt-2">
                                <div class="row">
                                    <div class="col-lg-4 select_sms_services">
                                        <div class="primary_input">
                                            <select
                                                    class="primary_select  form-control{{ $errors->has('layout') ? ' is-invalid' : '' }}"
                                                    name="layout" id="layout">
                                                <option data-display="@lang('system_settings.select_a_SMS_service')"
                                                        value="">@lang('examplan::exp.select_layout')
                                                </option>
                                                <option value="1" {{ $setting->admit_layout == 1 ? 'selected' : '' }}>
                                                    @lang('examplan::exp.layout_one')</option>
                                                <option value="2" {{ $setting->admit_layout == 2 ? 'selected' : '' }}>
                                                    @lang('examplan::exp.layout_two')</option>
                                            </select>

                                            @if ($errors->has('book_category_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('book_category_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div role="tabpanel" class="tab-pane fade @if ($setting->admit_layout == 1) show active @endif "
                             id="layout_one">
                            <div class="white-box">
                                <div class="main-title mb-25">
                                    <h3 class="mb-0">@lang('examplan::exp.layout_one') @lang('examplan::exp.admit_card_setting')</h3>
                                </div>
                                <form action="{{ route('examplan.admitcard.settingUpdate') }}" method="post"
                                      enctype="multipart/form-data"
                                      class="bg-white p-4 rounded">
                                    <input type="hidden" name="tab_layout" value="1">
                                    @csrf
                                    <div class="row">
                                        <div
                                                class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between">
                                            <p class="text-uppercase mb-0">@lang('examplan::exp.student_photo')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_photo" id="student_photo_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->student_photo) checked @endif>
                                                    <label for="student_photo_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_photo" id="student_photo"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->student_photo == 0) checked @endif>
                                                    <label for="student_photo">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.student_name')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_name" id="student_name_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->student_name) checked @endif>
                                                    <label for="student_name_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_name" id="student_name"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->student_name == 0) checked @endif>
                                                    <label for="student_name">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.gaurdian_name')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="gaurdian_name" id="gaurdian_name_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->gaurdian_name) checked @endif>
                                                    <label for="gaurdian_name_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="gaurdian_name" id="gaurdian_name"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->gaurdian_name == 0) checked @endif>
                                                    <label for="gaurdian_name">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.admission_no')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="admission_no" id="admission_no_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->admission_no) checked @endif>
                                                    <label for="admission_no_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="admission_no" id="admission_no"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->admission_no == 0) checked @endif>
                                                    <label for="admission_no">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.class_&_section')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="class_section" id="class_section_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->class_section) checked @endif>
                                                    <label for="class_section_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="class_section" id="class_section"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->class_section == 0) checked @endif>
                                                    <label for="class_section">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.exam_name')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="exam_name" id="exam_name_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->exam_name) checked @endif>
                                                    <label for="exam_name_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="exam_name" id="exam_name" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->exam_name == 0) checked @endif>
                                                    <label for="exam_name">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.academic_year')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="academic_year" id="academic_year_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->academic_year) checked @endif>
                                                    <label for="academic_year_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="academic_year" id="academic_year"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->academic_year == 0) checked @endif>
                                                    <label for="academic_year">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.school_address')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="school_address" id="school_address_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->school_address) checked @endif>
                                                    <label for="school_address_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="school_address" id="school_address"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->school_address == 0) checked @endif>
                                                    <label for="school_address">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- new added --}}

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.student_can_download')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_download"
                                                           id="student_download_on" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->student_download) checked @endif>
                                                    <label for="student_download_on">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_download" id="student_download"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->student_download == 0) checked @endif>
                                                    <label for="student_download">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.parent_can_download')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_download" id="parent_download_on"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->parent_download) checked @endif>
                                                    <label for="parent_download_on">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_download" id="parent_download"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->parent_download == 0) checked @endif>
                                                    <label for="parent_download">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.student_notification')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_notification"
                                                           id="student_notification_on" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->student_notification) checked @endif>
                                                    <label for="student_notification_on">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_notification"
                                                           id="student_notification" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->student_notification == 0) checked @endif>
                                                    <label for="student_notification">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.parent_notification')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_notification"
                                                           id="parent_notification_on" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->parent_notification) checked @endif>
                                                    <label for="parent_notification_on">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_notification"
                                                           id="parent_notification" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->parent_notification == 0) checked @endif>
                                                    <label for="parent_notification">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 teacher_signature"
                                             id="teacher_signature">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.class_teacher_signature')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="class_teacher_signature"
                                                           id="class_teacher_signature_on" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->class_teacher_signature) checked @endif>
                                                    <label for="class_teacher_signature_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="class_teacher_signature"
                                                           id="class_teacher_signature" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->class_teacher_signature == 0) checked @endif>
                                                    <label for="class_teacher_signature">@lang('examplan::exp.hide')</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 principal_signature"
                                             id="principal_signature">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.principal_signature')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="principal_signature"
                                                           id="principal_signature_on" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->principal_signature == 1) checked @endif>
                                                    <label for="principal_signature_on">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="principal_signature"
                                                           id="principal_signature_off" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->principal_signature == 0) checked @endif>
                                                    <label for="principal_signature_off">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 relation-button  mb-3 teacher_signature">
                                            <div class="row no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="primary_input ">
                                                        <input class="primary_input_field" type="text"
                                                               id="teacher_signature_photo_placeholder"
                                                               placeholder=" {{ $setting->teacher_signature_photo != '' ? getFileName($setting->teacher_signature_photo) : trans('examplan::exp.class_teacher_signature') }} "
                                                               readonly="">


                                                        @if ($errors->has('teacher_signature_photo'))
                                                            <span class="text-danger d-block">
                                                                {{ @$errors->first('teacher_signature_photo') }}
                                                            </span>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button style="position: relative; top: 8px; right: 12px;"
                                                            class="primary-btn-small-input browse_file" type="button">

                                                        <label class="primary-btn small fix-gr-bg"
                                                               for="teacher_signature_photo">@lang('common.browse')</label>
                                                        <input type="file" class="d-none"
                                                               value="{{ old('teacher_signature_photo') }}"
                                                               name="teacher_signature_photo"
                                                               id="teacher_signature_photo">
                                                    </button>
                                                </div>
                                                @if ($setting->teacher_signature_photo)
                                                    <div class="col-auto">
                                                        <div class="img_prevView">
                                                            <img class="img-fluid"
                                                                 src="{{ asset($setting->teacher_signature_photo) }}"
                                                                 alt="@lang('examplan::exp.class_teacher_signature')">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 relation-button justify-content-between">

                                            <div class="row no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="primary_input ">
                                                        <input class="primary_input_field" type="text"
                                                               id="principal_signature_photo_placeholder"
                                                               placeholder=" {{ $setting->principal_signature_photo != '' ? getFileName($setting->principal_signature_photo) : trans('examplan::exp.principal_signature') }}"
                                                               readonly="">


                                                        @if ($errors->has('principal_signature_photo'))
                                                            <span class="text-danger d-block">
                                                                {{ @$errors->first('principal_signature_photo') }}
                                                            </span>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button style="position: relative; top: 8px; right: 12px;"
                                                            class="primary-btn-small-input" type="button">

                                                        <label class="primary-btn small fix-gr-bg"
                                                               for="principal_signature_photo">@lang('common.browse')</label>
                                                        <input type="file" class="d-none"
                                                               name="principal_signature_photo"
                                                               id="principal_signature_photo">
                                                    </button>
                                                </div>
                                                @if ($setting->principal_signature_photo)
                                                    <div class="col-auto">
                                                        <div class="img_prevView">
                                                            <img class="img-fluid Img-100"
                                                                 src="{{ asset($setting->principal_signature_photo) }}"
                                                                 alt="@lang('examplan::exp.principal_signature')">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn small fix-gr-bg"><i
                                                        class="ti-check"></i>@lang('common.update')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade @if ($setting->admit_layout == 2) show active @endif"
                             id="layout_two">
                            <div class="white-box">
                                <div class="main-title mb-25">
                                    <h3 class="mb-0"> @lang('examplan::exp.layout_two') @lang('examplan::exp.admit_card_setting')</h3>
                                </div>
                                <form action="{{ route('examplan.admitcard.settingUpdatetwo') }}" method="post"
                                      enctype="multipart/form-data"
                                      class="bg-white p-4 rounded">
                                    <input type="hidden" name="tab_layout" value="2">
                                    @csrf
                                    <div class="row">
                                        <div
                                                class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between">
                                            <p class="text-uppercase mb-0">@lang('examplan::exp.student_photo')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_photo" id="student_photo_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->student_photo) checked @endif>
                                                    <label for="student_photo_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_photo" id="student_photo2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->student_photo == 0) checked @endif>
                                                    <label for="student_photo2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.student_name')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_name" id="student_name_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->student_name) checked @endif>
                                                    <label for="student_name_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_name" id="student_name2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->student_name == 0) checked @endif>
                                                    <label for="student_name2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('student.father_names')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="gaurdian_name" id="gaurdian_name_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->gaurdian_name) checked @endif>
                                                    <label for="gaurdian_name_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="gaurdian_name" id="gaurdian_name2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->gaurdian_name == 0) checked @endif>
                                                    <label for="gaurdian_name2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.admission_no')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="admission_no" id="admission_no_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->admission_no) checked @endif>
                                                    <label for="admission_no_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="admission_no" id="admission_no2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->admission_no == 0) checked @endif>
                                                    <label for="admission_no2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.class_&_section')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="class_section" id="class_section_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->class_section) checked @endif>
                                                    <label for="class_section_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="class_section" id="class_section2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->class_section == 0) checked @endif>
                                                    <label for="class_section2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.exam_name')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="exam_name" id="exam_name_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->exam_name) checked @endif>
                                                    <label for="exam_name_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="exam_name" id="exam_name2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->exam_name == 0) checked @endif>
                                                    <label for="exam_name2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.academic_year')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="academic_year" id="academic_year_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->academic_year) checked @endif>
                                                    <label for="academic_year_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="academic_year" id="academic_year2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->academic_year == 0) checked @endif>
                                                    <label for="academic_year2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.school_address')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="school_address" id="school_address_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->school_address) checked @endif>
                                                    <label for="school_address_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="school_address" id="school_address2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->school_address == 0) checked @endif>
                                                    <label for="school_address2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- new added --}}

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.student_can_download')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_download"
                                                           id="student_download_on2" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->student_download) checked @endif>
                                                    <label for="student_download_on2">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_download" id="student_download2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->student_download == 0) checked @endif>
                                                    <label for="student_download2">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.parent_can_download')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_download" id="parent_download_on2"
                                                           value="1" class="common-radio relationButton"
                                                           @if ($setting->parent_download) checked @endif>
                                                    <label for="parent_download_on2">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_download" id="parent_download2"
                                                           value="0" class="common-radio relationButton"
                                                           @if ($setting->parent_download == 0) checked @endif>
                                                    <label for="parent_download2">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.student_notification')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="student_notification"
                                                           id="student_notification_on2" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->student_notification) checked @endif>
                                                    <label for="student_notification_on2">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="student_notification"
                                                           id="student_notification2" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->student_notification == 0) checked @endif>
                                                    <label for="student_notification2">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.parent_notification')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_notification"
                                                           id="parent_notification_on2" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->parent_notification) checked @endif>
                                                    <label for="parent_notification_on2">@lang('examplan::exp.yes')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="parent_notification"
                                                           id="parent_notification2" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->parent_notification == 0) checked @endif>
                                                    <label for="parent_notification2">@lang('examplan::exp.no')</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 principal_signature"
                                             id="principal_signature">
                                            <p class="text-uppercase mb-0"> @lang('examplan::exp.exam_controller_sign')</p>
                                            <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                <div class="mr-20">
                                                    <input type="radio" name="principal_signature"
                                                           id="principal_signature_on2" value="1"
                                                           class="common-radio relationButton"
                                                           @if ($setting->principal_signature == 1) checked @endif>
                                                    <label for="principal_signature_on2">@lang('examplan::exp.show')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" name="principal_signature"
                                                           id="principal_signature_off2" value="0"
                                                           class="common-radio relationButton"
                                                           @if ($setting->principal_signature == 0) checked @endif>
                                                    <label for="principal_signature_off2">@lang('examplan::exp.hide')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 relation-button  mb-3 admit_sub_title">
                                            <div class="primary_input ">
                                                <label class="primary_input_label"
                                                       for="">@lang('examplan::exp.admit_sub_title')</label>
                                                <input class="primary_input_field form-control" type="text"
                                                       name="admit_sub_title" value="{{ @$setting->admit_sub_title }}">
                                            </div>

                                        </div>
                                        <div class="col-lg-6 relation-button justify-content-between mb-3">
                                            <div class="row no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="primary_input ">
                                                        <input class="primary_input_field" type="text"
                                                               id="principal_signature_photo_2_placeholder"
                                                               placeholder=" {{ $setting->principal_signature_photo != '' ? getFileName($setting->principal_signature_photo) : trans('examplan::exp.exam_controller_sign') }}"
                                                               readonly="">


                                                        @if ($errors->has('principal_signature_photo_2'))
                                                            <span class="text-danger d-block">
                                                                {{ @$errors->first('principal_signature_photo_2') }}
                                                            </span>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button style="position: relative; top: 8px; right: 12px;"
                                                            class="primary-btn-small-input" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                               for="principal_signature_photo_2">@lang('common.browse')</label>
                                                        <input type="file" class="d-none"
                                                               name="principal_signature_photo_2"
                                                               id="principal_signature_photo_2">
                                                    </button>
                                                </div>
                                                @if ($setting->principal_signature_photo)
                                                    <div class="col-auto">
                                                        <div class="img_prevView">
                                                            <img class="img-fluid Img-100"
                                                                 src="{{ asset($setting->principal_signature_photo) }}"
                                                                 alt="@lang('examplan::exp.principal_signature')">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-12 relation-button justify-content-between mb-3">
                                            <div class="row no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label"
                                                               for="">@lang('examplan::exp.short_description')
                                                        </label>
                                                        <textarea
                                                                class="primary_input_field summer_note form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                                cols="2" rows="2" name="description">{{ @$setting->description }}
                                                        </textarea>
                                                        @if ($errors->has('description'))
                                                            <span class="text-danger"
                                                                  role="alert">{{ $errors->first('description') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn small fix-gr-bg"><i
                                                        class="ti-check"></i>@lang('common.update')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- new design end here  --}}

@endsection

@push('script')
    <script src="{{asset('public/backEnd/vendors/editor/summernote-bs4.js')}}"></script>

    <script>

        $("#teacher_signature").click(function () {
            var teacher = $("input[name='class_teacher_signature']:checked").val();
            if (teacher == 0) {
                console.log(teacher);
                $('.teacher_signature').css('display', 'none !important');

            }
        });

        $("#principal_signature").click(function () {
            var principal = $("input[name='principal_signature']:checked").val();
            if (principal == 0) {
                $('.principal_signature').css('display', 'none');
            }
        });

        // select a service
        $("#layout").on("change", function (e) {
            e.preventDefault();
            layout = $("#layout").val();
            url = $("#url").val();
            $.ajax({
                type: "get",
                data: {
                    layout: layout,
                },
                url: url + "/examplan/changeAdmitCardLayout",
                success: function (data) {
                    if (data == "success") {
                        toastr.success("Operation Success", "Successful", {
                            timeOut: 5000,
                        });
                    } else {
                        toastr.error("You Got Error", "Inconceivable!", {
                            timeOut: 5000,
                        });
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                },
            });
        });

        let principal_signature_photo_2 = document.getElementById("principal_signature_photo_2");
        if (principal_signature_photo_2) {
            principal_signature_photo_2.addEventListener("change", function (event) {
                let fileInput = event.srcElement;
                document.getElementById(
                    "principal_signature_photo_2_placeholder"
                ).placeholder = fileInput.files[0].name;
            });
        }

        let principal_signature_photo = document.getElementById("principal_signature_photo");
        if (principal_signature_photo) {
            principal_signature_photo.addEventListener("change", function (event) {
                let fileInput = event.srcElement;
                document.getElementById(
                    "principal_signature_photo_placeholder"
                ).placeholder = fileInput.files[0].name;
            });

        }
        var class_teacher_signature = document.getElementById("teacher_signature_photo");
        if (class_teacher_signature) {
            class_teacher_signature.addEventListener("change", function (event) {
                let fileInput = event.srcElement;
                document.getElementById(
                    "teacher_signature_photo_placeholder"
                ).placeholder = fileInput.files[0].name;
            });
        }
    </script>
@endpush
