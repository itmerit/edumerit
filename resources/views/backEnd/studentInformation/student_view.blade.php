@extends('backEnd.master')
@push('css')
    <style>
        .school-table-up-style tr td {
            padding: 8px 6px 8px 10px !important;
            font-size: 12px !important;
        }

        .school-table-style {
            padding: 0px !important;
        }

        .badge {
            background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
            color: #fff;
            padding: 5px 10px;
            border-radius: 30px;
            display: inline-block;
            font-size: 8px;
        }

        table.dataTable thead th {
            padding-left: 25px !important;
        }

        table.dataTable thead th::after {
            left: 10px !important;
            top: 10px !important;
        }

        table.dataTable tbody td {
            padding-left: 13px !important;
        }
        .school-table-style tr th {
            padding: 10px 18px 10px 10px !important;
        }
        .school-table-style tr td {
            padding: 20px 10px 20px 10px !important;
        }
        .input-right-icon button.primary-btn-small-input {
            top: 8px !important;
            right: 11px !important;
        }
    </style>
@endpush

@section('title')
    @lang('student.student_details')
@endsection

@section('mainContent')

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
    @php
        $setting = app('school_info');
        if (!empty($setting->currency_symbol)) {
            $currency = $setting->currency_symbol;
        } else {
            $currency = '$';
        }
    @endphp

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('student.student_details')</h1>
                <div class="bc-pages">
                    <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="{{ route('student_list') }}">@lang('student.student_list')</a>
                    <a href="#">@lang('student.student_details')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">

                    @if (moduleStatusCheck('University'))
                        @includeIf('university::promote.inc.student_profile', [
                            'student_detail' => $student_detail->defaultClass,
                            'student' => $student_detail,
                        ])
                    @else
                        @includeIf('backEnd.studentInformation.inc.student_profile')
                    @endif

                </div>

                @php
                    $type = isset($type) ? $type : null;
                @endphp

                <!-- Start Student Details -->
                <div class="col-lg-9 student-details up_admin_visitor">
                    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $type == '' && Session::get('studentDocuments') == '' ? 'active' : '' }} "
                                href="#studentProfile" role="tab" data-toggle="tab">@lang('student.profile')</a>
                        </li>

                        @if (generalSetting()->fees_status == 0)
                            <li class="nav-item">
                                <a class="nav-link" href="#studentFees" role="tab"
                                    data-toggle="tab">@lang('fees.fees')</a>
                            </li>
                        @endif
                        @if (isMenuAllowToShow('leave'))
                            <li class="nav-item">
                                <a class="nav-link" href="#leaves" role="tab" data-toggle="tab">@lang('leave.leave')</a>
                            </li>
                        @endif
                        @if (isMenuAllowToShow('examination'))
                            <li class="nav-item">
                                <a class="nav-link" href="#studentExam" role="tab"
                                    data-toggle="tab">@lang('exam.exam')</a>
                            </li>
                        @endif
                        @if (moduleStatusCheck('University'))
                            <li class="nav-item">
                                <a class="nav-link" href="#studentExamTranscript" role="tab"
                                    data-toggle="tab">@lang('university::un.transcript')</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ Session::get('studentDocuments') == 'active' ? 'active' : '' }}"
                                href="#studentDocuments" role="tab" data-toggle="tab">@lang('student.document')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Session::get('studentRecord') == 'active' ? 'active' : '' }} "
                                href="#studentRecord" role="tab" data-toggle="tab">@lang('student.record')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $type == 'studentTimeline' ? 'active' : '' }} " href="#studentTimeline"
                                role="tab" data-toggle="tab">@lang('student.timeline')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Session::get('studentAttendance') == 'active' ? 'active' : '' }} "
                                href="#studentAttendance" role="tab" data-toggle="tab">@lang('student.student_attendance')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Session::get('subjectAttendance') == 'active' ? 'active' : '' }} "
                                href="#subjectAttendance" role="tab" data-toggle="tab">@lang('student.subject_attendance')</a>
                        </li>
                        @if (generalSetting()->result_type == 'mark')
                            <li class="nav-item">
                                <a class="nav-link {{ $type == 'mark' ? 'active' : '' }} " href="#mark" role="tab"
                                    data-toggle="tab">@lang('exam.marksheet')</a>
                            </li>
                        @endif

                        @if (moduleStatusCheck('University'))
                            <li class="nav-item">
                                <a class="nav-link {{ $type == 'assign_subject' ? 'active' : '' }} " href="#studentSubject"
                                    role="tab" data-toggle="tab">@lang('university::un.subject')</a>
                            </li>
                        @endif

                        <li class="nav-item edit-button">
                            @if (userPermission(66))
                                <a href="{{ route('student_edit', [@$student_detail->id]) }}"
                                    class="primary-btn small fix-gr-bg">@lang('common.edit')
                                </a>
                            @endif
                        </li>
                    </ul>


                    <!-- Tab panes -->
                    <div class="tab-content">

                        <!-- Start Profile Tab -->
                        @include('backEnd.studentInformation.inc._profile_tab')
                        <!-- End Profile Tab -->

                        <!-- Start Fees Tab -->
                        @include('backEnd.studentInformation.inc._fees_tab')
                        <!-- End Fees Tab -->

                        <!-- Start leave Tab -->
                        @include('backEnd.studentInformation.inc._leave_tab')
                        <!-- End leave Tab -->

                        <!-- Start Exam Tab -->
                        @include('backEnd.studentInformation.inc._exam_tab')
                        <!-- End Exam Tab -->

                        @if (moduleStatusCheck('University'))
                            <div role="tabpanel" class="tab-pane fade" id="studentExamTranscript">
                                @includeIf('university::exam.partials._examTabView')
                            </div>
                        @endif

                        <!-- Start Documents Tab -->
                        @include('backEnd.studentInformation.inc._document_tab')
                        <!-- End Documents Tab -->

                        <!-- Start reocrd Tab -->
                        <div role="tabpanel"
                            class="tab-pane fade {{ Session::get('studentRecord') == 'active' ? 'show active' : '' }}"
                            id="studentRecord">
                            <div class="white-box">
                                <div class="text-right mb-20">
                                    @if (userPermission(1201))
                                        <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button"
                                            data-toggle="modal" data-target="#assignClass"> <span
                                                class="ti-plus pr-2"></span> @lang('common.add')</button>
                                    @endif
                                </div>
                                <table id="" class="table simple-table table-responsive school-table"
                                    cellspacing="0">
                                    <thead class="d-block">
                                        <tr class="d-flex">
                                            @if (moduleStatusCheck('University'))
                                                <th class="col-2">@lang('university::un.session')</th>
                                                <th class="col-3">@lang('university::un.faculty_department')</th>
                                                <th class="col-3">@lang('university::un.semester(label)')</th>
                                            @else
                                                <th class="col-3">@lang('common.class')</th>
                                                <th class="col-3">@lang('common.section')</th>
                                            @endif
                                            @if ($setting->multiple_roll == 1)
                                                <th class="col-2">@lang('student.id_number')</th>
                                            @endif
                                            <th class="col-2">@lang('student.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="d-block">
                                        @foreach ($records as $record)
                                            <tr class="d-flex">
                                                @if (moduleStatusCheck('University'))
                                                    <td class="col-2">{{ $record->unSession->name }}</td>
                                                    <td class="col-3">
                                                        {{ $record->unFaculty->name . '(' . $record->unDepartment->name . ')' }}
                                                        @if ($record->is_default)
                                                            <span
                                                                class="badge fix-gr-bg">{{ __('common.default') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="col-3">
                                                        {{ $record->unSemester->name . '(' . $record->unSemesterLabel->name . ')' }}
                                                    </td>
                                                @else
                                                    <td class="col-3">
                                                        {{ $record->class->class_name }}
                                                        @if ($record->is_default)
                                                            <span
                                                                class="badge fix-gr-bg">{{ __('common.default') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="col-3">
                                                        {{ $record->section->section_name }}
                                                    </td>
                                                @endif

                                                @if ($setting->multiple_roll == 1)
                                                    <td class="col-2">{{ $record->roll_no }}</td>
                                                @endif
                                                <td class="col-2">
                                                    @if ($record->is_promote == 0)
                                                        <a class="primary-btn icon-only fix-gr-bg modalLink"
                                                            data-modal-size="small-modal"
                                                            title="@if (moduleStatusCheck('University')) @lang('university::un.assign_faculty_department')
                                                                @else 
                                                                    @lang('student.assign_class') @endif"
                                                            href="{{ route('student_assign_edit', [@$record->student_id, @$record->id]) }}"><span
                                                                class="ti-pencil"></span></a>
                                                        <a href="#" class="primary-btn icon-only fix-gr-bg"
                                                            data-toggle="modal"
                                                            data-target="#deleteRecord_{{ $record->id }}">
                                                            <span class="ti-trash"></span>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            {{-- Record delete --}}
                                            {{-- <div class="modal fade admin-query" id="deleteRecord_{{ $record->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('common.delete')</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>
                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                                <form action="{{ route('student.record.delete') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="student_id"
                                                                        value="{{ $record->student_id }}">
                                                                    <input type="hidden" name="record_id"
                                                                        value="{{ $record->id }}">
                                                                    <button type="submit"
                                                                        class="primary-btn fix-gr-bg">@lang('common.delete')</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="modal fade admin-query" id="deleteRecord_{{ $record->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('common.delete')</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form action="{{ route('student.record.delete') }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('student.Are you sure you want to move the following record to the trash?')</h4>
                                                                </div>
                                                                <input type="checkbox" id="record{{ @$record->id }}"
                                                                    class="common-checkbox form-control{{ @$errors->has('record') ? ' is-invalid' : '' }}"
                                                                    name="type">
                                                                <label
                                                                    for="record{{ @$record->id }}">{{ __('student.Skip the trash and permanently delete the record') }}</label>
                                                                <input type="hidden" name="student_id"
                                                                    value="{{ $record->student_id }}">
                                                                <input type="hidden" name="record_id"
                                                                    value="{{ $record->id }}">
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('common.cancel')</button>
                                                                    <button type="submit"
                                                                        class="primary-btn fix-gr-bg">@lang('common.delete')</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Record delete --}}
                                            {{-- edit record --}}
                                        @endforeach
                                        {{-- end edit record --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End reocrd Tab -->

                        <!-- Start Timeline Tab -->
                        <div role="tabpanel"
                            class="tab-pane fade"
                            id="studentTimeline">
                            <div class="white-box">
                                <div class="text-right mb-20">
                                    <button type="button" data-toggle="modal" data-target="#add_timeline_madal"
                                        class="primary-btn tr-bg text-uppercase bord-rad">
                                        @lang('common.add')
                                        <span class="pl ti-plus"></span>
                                    </button>
                                </div>
                                @foreach ($timelines as $timeline)
                                    <div class="student-activities">
                                        <div class="single-activity">
                                            <h4 class="title text-uppercase">
                                                {{ $timeline->date != '' ? dateConvert($timeline->date) : '' }}</h4>
                                            <div class="sub-activity-box d-flex">
                                                <h6 class="time text-uppercase">10.30 pm</h6>
                                                <div class="sub-activity">
                                                    <h5 class="subtitle text-uppercase"> {{ $timeline->title }}</h5>
                                                    <p>
                                                        {{ $timeline->description }}
                                                    </p>
                                                </div>

                                                <div class="close-activity">

                                                    <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                                        data-target="#deleteTimelineModal{{ $timeline->id }}"
                                                        href="#">
                                                        <span class="ti-trash text-white"></span>
                                                    </a>

                                                    @if (file_exists($timeline->file))
                                                        <a href="{{ url($timeline->file) }}"
                                                            class="primary-btn tr-bg text-uppercase bord-rad" download>
                                                            @lang('common.download')<span class="pl ti-download"></span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade admin-query" id="deleteTimelineModal{{ $timeline->id }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('common.delete')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>

                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">@lang('common.cancel')
                                                            </button>
                                                            <a class="primary-btn fix-gr-bg"
                                                                href="{{ route('delete_timeline', [$timeline->id]) }}">
                                                                @lang('common.delete')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- End Timeline Tab -->

                        <!-- Start Attendance Tab -->
                        @include('backEnd.studentInformation.inc._student_attendance_tab')
                        <!-- End Attendance Tab -->

                        <!-- Start Attendance Tab -->
                        @include('backEnd.studentInformation.inc._subject_attendance_tab')
                        <!-- End Attendance Tab -->
                    </div>

                    {{-- @include('backEnd.studentInformation.inc._record_tab') --}}
                    {{-- start marksheet tab  --}}
                    @if (generalSetting()->result_type == 'mark')
                        <div role="tabpanel"
                            class="tab-pane fade {{ Session::get('mark') == 'active' ? 'show active' : '' }}"
                            id="mark">
                            <div class="white-box">
                                @foreach ($records as $record)
                                    @includeIf('backend.studentInformation.inc.finalMarkSheet')
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- end marksheet tab  --}}

                    <!-- Start Timeline Tab -->
                    @if (moduleStatusCheck('University'))
                        <div role="tabpanel" class="tab-pane fade {{ $type == 'assign_subject' ? 'show active' : '' }}"
                            id="studentSubject">
                            @include('backEnd.studentInformation.inc.subject_list')
                        </div>
                    @endif
                    <!-- End Timeline Tab -->
                </div>
            </div>
            <!-- End Student Details -->
        </div>
        </div>
    </section>

    <!-- assign class form modal start-->
    <div class="modal fade admin-query" id="assignClass">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        @if (moduleStatusCheck('University'))
                            @lang('university::un.assign_faculty_department')
                        @else
                            @lang('student.assign_class')
                        @endif
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student.record.store', 'method' => 'POST']) }}


                        <input type="hidden" name="student_id" value="{{ $student_detail->id }}">
                        @if (!moduleStatusCheck('University'))
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="primary_input ">
                                        <select
                                            class="primary_select  form-control{{ $errors->has('session') ? ' is-invalid' : '' }}"
                                            name="session" id="academic_year">
                                            <option data-display="@lang('common.academic_year') *" value="">@lang('common.academic_year')
                                                *</option>
                                            @foreach ($sessions as $session)
                                                <option value="{{ $session->id }}"
                                                    {{ old('session') == $session->id ? 'selected' : '' }}>
                                                    {{ $session->year }}[{{ $session->title }}]</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('session'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('session') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="primary_input " id="class-div">
                                        <select
                                            class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                            name="class" id="classSelectStudent">
                                            <option data-display="@lang('common.class') *" value="">@lang('common.class')
                                                *</option>
                                        </select>
                                        <div class="pull-right loader loader_style" id="select_class_loader">
                                            <img class="loader_img_style"
                                                src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
                                        </div>

                                        @if ($errors->has('class'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('class') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="primary_input " id="sectionStudentDiv">
                                        <select
                                            class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                            name="section" id="sectionSelectStudent">
                                            <option data-display="@lang('common.section') *" value="">@lang('common.section')
                                                *</option>
                                        </select>
                                        <div class="pull-right loader loader_style" id="select_section_loader">
                                            <img class="loader_img_style"
                                                src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
                                        </div>

                                        @if ($errors->has('section'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('section') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            @includeIf('university::common.session_faculty_depart_academic_semester_level', [
                                'mt' => 'mt-0',
                                'required' => ['USN', 'UF', 'UD', 'UA', 'US', 'USL'],
                                'row' => 1,
                                'div' => 'col-lg-12',
                                'hide' => ['USUB'],
                            ])
                        @endif
                        @if (generalSetting()->multiple_roll == 1)
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="primary_input ">
                                        <input oninput="numberCheck(this)" class="primary_input_field" type="text"
                                            placeholder="{{ moduleStatusCheck('Lead') == true ? __('lead::lead.id_number') : __('student.roll') }}{{ is_required('roll_number') == true ? ' *' : '' }}"
                                            id="roll_number" name="roll_number" value="{{ old('roll_number') }}">
                                        <span class="text-danger" id="roll-error" role="alert">
                                            <strong></strong>
                                        </span>
                                        @if ($errors->has('roll_number'))
                                            <span class="text-danger">
                                                {{ $errors->first('roll_number') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row  mt-25">
                            <div class="col-lg-12">
                                <label for="is_default">@lang('student.is_default')</label>
                                <div class="d-flex radio-btn-flex mt-10">

                                    <div class="mr-30">
                                        <input type="radio" name="is_default" id="isDefaultYes" value="1"
                                            class="common-radio relationButton">
                                        <label for="isDefaultYes">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30">
                                        <input type="radio" name="is_default" id="isDefaultNo" value="0"
                                            class="common-radio relationButton" checked>
                                        <label for="isDefaultNo">@lang('common.no')</label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 text-center mt-20">
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg"
                                    data-dismiss="modal">@lang('admin.cancel')</button>
                                <button class="primary-btn fix-gr-bg submit" id="save_button_query"
                                    type="submit">@lang('admin.save')</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- assign class form modal end-->

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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_timeline_store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="student_id" value="{{ $student_detail->id }}">
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <label>@lang('student.title') <span>*</span> </label>
                                            <input class="primary_input_field form-control{" type="text"
                                                name="title" value="" id="title" maxlength="200">
                                            <span class="focus-border"></span>
                                            <span class=" text-danger" role="alert" id="amount_error">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="input-right-icon">
                                    <div class="input-effect">
                                        <label>@lang('common.date')</label>
                                        <div class="position-relative">
                                            <input class="primary_input_field date form-control" readonly id="startDate"
                                                type="text" name="date">
                                            <span class="focus-border"></span>
                                            <label class="primary_input-icon mr-2" for="startDate">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="input-effect">
                                    <label>@lang('common.description')<span></span> </label>
                                    <textarea class="primary_input_field form-control" cols="0" rows="3" name="description"
                                        id="Description"></textarea>
                                    <span class="focus-border textarea"></span>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-40">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="input-effect">
                                            <input class="primary_input_field" type="text"
                                                id="placeholderFileFourName" placeholder="Document" disabled>
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


    @include('backEnd.partials.data_table_js')
    @include('backEnd.partials.date_picker_css_js')
    <script>
        function deleteDoc(id, doc) {
            var modal = $('#delete-doc');
            modal.find('input[name=student_id]').val(id)
            modal.find('input[name=doc_id]').val(doc)
            modal.modal('show');
        }
    </script>

@endsection
