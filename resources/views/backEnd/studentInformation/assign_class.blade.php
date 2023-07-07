@extends('backEnd.master')
@section('title')
    @if (moduleStatusCheck('University'))
        @lang('university::un.assign_faculty_department')
    @else
        @lang('student.assign_class')
    @endif
@endsection
@push('css')
    <style>
        .badge {
            background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
            color: #fff;
            padding: 5px 10px;
            border-radius: 30px;
            display: inline-block;
            font-size: 8px;
        }
    </style>
@endpush
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
                <h1>
                    @if (moduleStatusCheck('University'))
                        @lang('university::un.assign_faculty_department')
                    @else
                        @lang('student.assign_class')
                    @endif
                </h1>
                @if (moduleStatusCheck('University'))
                    <div class="bc-pages">
                        <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                        <a href="{{ route('student_list') }}">@lang('student.student_list')</a>
                        <a href="#">
                            @if (moduleStatusCheck('University'))
                                @lang('university::un.assign_faculty_department')
                            @else
                                @lang('student.assign_class')
                            @endif
                        </a>
                @endif

                <div class="bc-pages">
                    <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="{{ route('student_list') }}">@lang('student.student_list')</a>
                    <a href="#">@lang('student.assign_class')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    @includeIf('backEnd.studentInformation.inc.student_profile')
                </div>

                <!-- Start Student Details -->
                <div class="col-lg-9 student-details up_admin_visitor">
                    <div class="white-box mt-40">
                        <div class="text-right mb-20">
                            <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button"
                                data-toggle="modal" data-target="#assignClass"> <span class="ti-plus pr-2"></span>
                                @lang('common.add')</button>
                        </div>
                        <table id="" class="table simple-table table-responsive school-table"
                            cellspacing="0">
                            <thead class="d-block">
                                <tr class="d-flex">
                                    @php
                                        $div = generalSetting()->multiple_roll == 1 ? 'col-3' : 'col-4';
                                    @endphp
                                    @if (moduleStatusCheck('University'))
                                        <th class="col-3">@lang('university::un.faculty') (@lang('university::un.department'))</th>
                                        <th class="col-3">@lang('university::un.semester_label')</th>
                                        <th class="col-3">@lang('common.section')</th>
                                    @else
                                        <th class="{{ $div }}">@lang('common.class')</th>
                                        <th class="{{ $div }}">@lang('common.section')</th>
                                    @endif
                                    @if (generalSetting()->multiple_roll == 1)
                                        <th class="{{ $div }}">@lang('student.id_number')</th>
                                    @endif
                                    <th class="{{ $div }}">@lang('student.action')</th>
                                </tr>
                            </thead>

                            <tbody class="d-block">
                                @foreach ($student_records as $record)
                                    <tr class="d-flex">
                                        <td class="{{ $div }}">
                                            {{ moduleStatusCheck('University') ? $record->unFaculty->name : $record->class->class_name }}
                                            @if ($record->is_default)
                                                <span class="badge fix-gr-bg">
                                                    {{ __('common.default') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="{{ $div }}">
                                            {{ moduleStatusCheck('University') ? $record->unDepartment->name : $record->section->section_name }}
                                        </td>
                                        {{-- <td class="col-3">
                                            {{ @$record->unSection->section_name }}
                                        </td> --}}
                                        {{-- @else --}}
                                        {{-- <td class="col-3">
                                            {{ @$record->class->class_name}} 
                                            @if ($record->is_default) 
                                                <span class="badge fix-gr-bg">
                                                    {{ __('common.default') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="col-3">
                                            {{ @$record->section->section_name }}
                                        </td> --}}

                                        {{-- @endif  --}}

                                        @if (generalSetting()->multiple_roll == 1)
                                            <td class="{{ $div }}">{{ $record->roll_no }}</td>
                                        @endif
                                        <td class="{{ $div }}">

                                            <a class="primary-btn icon-only fix-gr-bg modalLink"
                                                data-modal-size="small-modal"
                                                title=" @if (moduleStatusCheck('University')) @lang('university::un.assign_faculty_department')
                                        @else
                                           @lang('student.edit_assign_class') @endif"
                                                href="{{ route('student_assign_edit', [@$record->student_id, @$record->id]) }}"><span
                                                    class="ti-pencil"></span></a>
                                            <a href="#" class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                                data-target="#deleteRecord_{{ $record->id }}">
                                                <span class="ti-trash"></span>
                                            </a>
                                        </td>
                                    </tr>




                                    {{-- record delete --}}

                                    <div class="modal fade admin-query" id="deleteRecord_{{ $record->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('common.delete')</h4>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <form action="{{ route('student.record.delete') }}" method="POST">
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
                                                        <input type="hidden" name="record_id" value="{{ $record->id }}">
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
                                    {{-- record delete --}}

                                    {{-- edit record --}}
                                @endforeach
                                {{-- end edit record --}}
                            </tbody>
                        </table>
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
                                            id="roll_number" name="roll_number" value="{{ old('roll_number') }}">
                                        <label>
                                            {{ moduleStatusCheck('Lead') == true ? __('lead::lead.id_number') : __('student.roll') }}
                                            @if (is_required('roll_number') == true)
                                                <span class="text-danger"> *</span>
                                            @endif
                                        </label>

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


@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $("#assign_class_academic_year").on(
                "change",
                function() {
                    var url = $("#url").val();
                    var i = 0;
                    var formData = {
                        id: $(this).val(),
                    };

                    alert($(this).val());
                    // get section for student
                    $.ajax({
                        type: "GET",
                        data: formData,
                        dataType: "json",
                        url: url + "/" + "academic-year-get-class",

                        beforeSend: function() {
                            $('#select_class_loader').addClass('pre_loader');
                            $('#select_class_loader').removeClass('loader');
                        },

                        success: function(data) {
                            $("#classSelectStudent").empty().append(
                                $("<option>", {
                                    value: '',
                                    text: window.jsLang('select_class') + ' *',
                                })
                            );

                            if (data[0].length) {
                                $.each(data[0], function(i, className) {
                                    $("#classSelectStudent").append(
                                        $("<option>", {
                                            value: className.id,
                                            text: className.class_name,
                                        })
                                    );
                                });
                            }
                            $('#classSelectStudent').niceSelect('update');
                            $('#classSelectStudent').trigger('change');
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        },
                        complete: function() {
                            i--;
                            if (i <= 0) {
                                $('#select_class_loader').removeClass('pre_loader');
                                $('#select_class_loader').addClass('loader');
                            }
                        }
                    });
                }
            );
        });
    </script>
@endpush
