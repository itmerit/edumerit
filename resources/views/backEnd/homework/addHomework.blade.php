@extends('backEnd.master')
@section('title')
    @lang('homework.add_homework')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('homework.add_homework')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('homework.home_work')</a>
                    <a href="#">@lang('homework.add_homework')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">

        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('homework.add_homework')</h3>
                    </div>
                </div>
            </div>
            @if (userPermission('saveHomeworkData'))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'saveHomeworkData', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            @if (moduleStatusCheck('University'))
                                <div class="row mb-30">
                                    @includeIf(
                                        'university::common.session_faculty_depart_academic_semester_level',
                                        ['subject' => true, 'dept_mt' => 'mt-0']
                                    )
                                </div>
                            @else
                                <div class="row mb-15">
                                    <div class="col-lg-4">
                                        <div class="primary_input ">
                                            <label class="primary_input_label" for="">@lang('common.class')<span class="text-danger"> *</span></label>
                                            <select
                                                class="primary_select  form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                                                name="class_id" id="classSelectStudentHomeWork">
                                                <option data-display="@lang('common.select_class') *"
                                                    value="">@lang('common.select')</option>
                                                @foreach ($classes as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('class_id') != '' ? 'selected' : '' }}>
                                                        {{ $value->class_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('class_id'))
                                                <span class="text-danger">{{ $errors->first('class_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input " id="subjectSelecttHomeworkDiv">
                                            <label class="primary_input_label" for="">@lang('common.subject') <span class="text-danger"> *</span></label>
                                            <select class="primary_select  form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }}" name="subject_id" id="subjectSelect">
                                                <option data-display="@lang('common.select_subjects') *" value="">@lang('common.subject') *</option>
                                            </select>
                                            <div class="pull-right loader loader_style" id="select_subject_loader">
                                                <img class="loader_img_style"
                                                    src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
                                            </div>
                                            @if ($errors->has('subject_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('subject_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4" id="selectSectionsDiv">
                                        <label for="checkbox" class="mb-2">@lang('common.section') <span class="text-danger"> *</span></label>
                                        <select id="selectSectionss" name="section_id[]" multiple="multiple"
                                            class="multypol_check_select active position-relative form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}">
                                        </select>
                                        @if ($errors->has('section_id'))
                                            <span class="text-danger">
                                                {{ $errors->first('section_id') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-15">
                                <div class="col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for="">@lang('homework.home_work_date')
                                            <span class="text-danger"> *</span></label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input
                                                            class="primary_input_field primary_input_field date form-control"
                                                            id="homework_date" type="text" name="homework_date"
                                                            value="{{ old('homework_date') != '' ? old('homework_date') : date('m/d/Y') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <button class="btn-date" data-id="#homework_date" type="button">
                                                    <label class="m-0 p-0" for="homework_date">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('homework_date') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for="">@lang('homework.submission_date')
                                            <span class="text-danger"> *</span></label>
                                        <div class="primary_datepicker_input">
                                            <div class="no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="">
                                                        <input
                                                            class="primary_input_field primary_input_field date form-control"
                                                            id="submission_date" type="text" name="submission_date"
                                                            value="{{ old('submission_date') != '' ? old('submission_date') : date('m/d/Y') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <button class="btn-date" data-id="#submission_date" type="button">
                                                    <label class="m-0 p-0" for="submission_date">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('submission_date') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('homework.marks') <span
                                                        class="text-danger"> *</span></label>
                                                <input oninput="numberCheckWithDot(this)"
                                                    class="primary_input_field form-control{{ $errors->has('marks') ? ' is-invalid' : '' }}"
                                                    type="text" name="marks" value="{{ old('marks') }}">
                                                @if ($errors->has('marks'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('marks') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('homework.attach_file')</label>
                                        <div class="primary_file_uploader">
                                            <input class="primary_input_field" type="text"
                                                id="placeholderHomeworkName"
                                                placeholder="@lang('homework.attach_file')"
                                                disabled>
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                    for="homework_file">{{ __('common.browse') }}</label>
                                                <input type="file" class="d-none" name="homework_file"
                                                    id="homework_file">
                                            </button>
                                            @if ($errors->has('homework_file'))
                                                <span class="text-danger">{{ $errors->first('homework_file') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row md-20">
                                <div class="col-lg-12">
                                    <div class="primary_input ">
                                        <label class="primary_input_label" for="">@lang('common.description') <span
                                                class="text-danger"> *</span> </label>
                                        <textarea
                                            class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                            cols="0" rows="4" name="description"
                                            id="description *">{{ old('description') }}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="text-danger">
                                                {{ $errors->first('description') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $tooltip = '';
                            if (userPermission('saveHomeworkData')) {
                                $tooltip = '';
                            } else {
                                $tooltip = 'You have no permission to add';
                            }
                        @endphp
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                    title="{{ $tooltip }}">
                                    <span class="ti-check"></span>
                                    @lang('homework.save_homework')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>
@endsection

@include('backEnd.partials.multi_select_js')
@include('backEnd.partials.date_picker_css_js')
