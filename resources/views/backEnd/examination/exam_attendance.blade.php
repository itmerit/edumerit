@extends('backEnd.master')
@section('title')
@lang('exam.exam_attendance')
@endsection
@section('mainContent')
<style>
    table.dataTable thead .sorting_asc:after {
        top: 10px;
        left: 5px;
    }

    table.dataTable thead .sorting:after {
        top: 10px;
        left: 4px;
    }

</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_attendance') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="#">@lang('exam.exam_attendance')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row mb-20">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="main-title sm_mb_20">
                    <h3 class="mb-0">@lang('common.select_criteria') </h3>
                </div>
            </div>

            @if(userPermission('exam_attendance_create'))
            <div class="col-lg-6 text-right col-md-6 text_xs_left col-sm-6">
                <a href="{{route('exam_attendance_create')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('exam.attendance_create')
                </a>
            </div>
            @endif

        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'exam_attendance', 'method' => 'POST', 'id' => 'search_student']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                        @if(moduleStatusCheck('University'))
                        <div class="col-lg-12">
                            <div class="row">
                                @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                ['required' =>
                                ['USN', 'UD', 'UA', 'US', 'USL', 'USEC'],'hide'=> ['USUB']
                                ])

                                <div class="col-lg-3 mt-30" id="select_exam_typ_subject_div">
                                    {{ Form::select('exam_type',[""=>__('exam.select_exam').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('exam_type') ? ' is-invalid' : ''), 'id'=>'select_exam_typ_subject']) }}

                                    <div class="pull-right loader loader_style" id="select_exam_type_loader">
                                        <img class="loader_img_style"
                                            src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('exam_type'))
                                    <span class="text-danger custom-error-message" role="alert">
                                        {{ @$errors->first('exam_type') }}
                                    </span>
                                    @endif
                                </div>

                                <div class="col-lg-3 mt-30" id="select_un_exam_type_subject_div">
                                    {{ Form::select('subject_id',[""=>__('exam.select_subject').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('subject_id') ? ' is-invalid' : ''), 'id'=>'select_un_exam_type_subject']) }}

                                    <div class="pull-right loader loader_style" id="select_exam_subject_loader">
                                        <img class="loader_img_style"
                                            src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('subject_id'))
                                    <span class="text-danger custom-error-message" role="alert">
                                        {{ @$errors->first('subject_id') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-3 mt-30-md">
                            <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                name="exam">
                                <option data-display="@lang('exam.select_exam') *" value="">@lang('exam.select_exam') *
                                </option>
                                @foreach($exams as $exam)
                                <option value="{{@$exam->id}}">{{@$exam->title}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('exam'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('exam') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-3 mt-30-md">
                            <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                id="class_subject" name="class">
                                <option data-display="@lang('common.select_class') *" value="">
                                    @lang('common.select_class') *</option>
                                @foreach($classes as $class)
                                <option value="{{$class->id}}"
                                    {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>
                                    {{$class->class_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('class'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('class') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-3 mt-30-md" id="select_class_subject_div">
                            <select
                                class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_class_subject"
                                id="select_class_subject" name="subject">
                                <option data-display="@lang('common.select_subject') *" value="">
                                    @lang('common.select_subject') *</option>
                            </select>
                            <div class="pull-right loader loader_style" id="select_class_subject_loader">
                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}"
                                    alt="loader">
                            </div>
                            @if ($errors->has('subject'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('subject') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-3 mt-30-md" id="m_select_subject_section_div">
                            <select
                                class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} m_select_subject_section"
                                id="m_select_subject_section" name="section">
                                <option data-display="@lang('common.select_section') " value=" ">
                                    @lang('common.select_section') </option>
                            </select>
                            <div class="pull-right loader loader_style" id="select_section_loader">
                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}"
                                    alt="loader">
                            </div>
                            @if ($errors->has('section'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('section') }}
                            </span>
                            @endif
                        </div>

                        @endif

                        <div class="col-lg-12 mt-20 text-right">
                            <button type="submit" class="primary-btn small fix-gr-bg">
                                <span class="ti-search pr-2"></span>
                                @lang('common.search')
                            </button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        @if(isset($exam_attendance_childs))
        @if(moduleStatusCheck('University'))
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12 no-gutters mb-30">
                        <div class="main-title">
                            <h3>@lang('exam.exam_attendance') | <strong>@lang('exam.subject')</strong>:
                                {{$subjectName->subject_name}}</h3>
                            @includeIf('university::exam._university_info')
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="table_id_table" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="20%">@lang('student.admission_no')</th>
                                    <th width="20%">@lang('student.student_name')</th>
                                    <th width="20%">@lang('student.roll_no')</th>
                                    <th width="20%">@lang('exam.attendance')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($exam_attendance_childs as $student)
                                <tr>
                                    <td>{{@$student->studentInfo !=""?@$student->studentInfo->admission_no:""}}<input
                                            type="hidden" name="id[]" value="{{@$student->student_id}}"></td>
                                    <td>{{@$student->studentInfo !=""?@$student->studentInfo->first_name.' '.@$student->studentInfo->last_name:""}}
                                    </td>
                                    <td>{{@$student->studentInfo !=""?@$student->studentInfo->roll_no:""}}</td>
                                    <td>
                                        @if(@$student->attendance_type == 'P')
                                        <button
                                            class="primary-btn small bg-success text-white border-0">@lang('student.present')</button>
                                        @else
                                        <button
                                            class="primary-btn small bg-danger text-white border-0">@lang('student.absent')</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('exam.exam_attendance')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="20%">@lang('student.admission_no')</th>
                                    <th width="20%">@lang('student.student_name')</th>
                                    <th width="20%">@lang('common.class_Sec')</th>
                                    <th width="20%">@lang('student.roll_no')</th>
                                    <th width="20%">@lang('exam.attendance')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($exam_attendance_childs as $student)
                                <tr>
                                    <td class="pl-3">{{@$student->studentInfo !=""?@$student->studentInfo->admission_no:""}}<input
                                            type="hidden" name="id[]" value="{{@$student->student_id}}"></td>
                                    <td>{{@$student->studentInfo !=""?@$student->studentInfo->first_name.' '.@$student->studentInfo->last_name:""}}
                                    </td>
                                    <td class="pl-3">{{@$student->studentRecord !=""?@$student->studentRecord->class->class_name.'('.@$student->studentRecord->section->section_name.')':""}}
                                    </td>
                                    <td class="pl-3">{{@$student->studentInfo !=""?@$student->studentInfo->roll_no:""}}</td>
                                    <td>
                                        @if(@$student->attendance_type == 'P')
                                        <button
                                            class="primary-btn small bg-success text-white border-0">@lang('student.present')</button>
                                        @else
                                        <button
                                            class="primary-btn small bg-danger text-white border-0">@lang('student.absent')</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</section>

@endsection
@include('backEnd.partials.data_table_js')

@push('script')
<script>
    $( document ).ready( function () {
        $( '.data-table' ).DataTable( {
            processing: true,
            serverSide: true,
            "ajax": $.fn.dataTable.pipeline( {
                url: "{{url('student-list-datatable')}}",
                data: {
                    academic_year: $( '#academic_id' ).val(),
                    class: $( '#class' ).val(),
                    section: $( '#section' ).val(),
                    roll_no: $( '#roll' ).val(),
                    name: $( '#name' ).val(),
                    un_session_id: $( '#un_session' ).val(),
                    un_academic_id: $( '#un_academic' ).val(),
                    un_faculty_id: $( '#un_faculty' ).val(),
                    un_department_id: $( '#un_department' ).val(),
                    un_semester_label_id: $( '#un_semester_label' ).val(),
                    un_section_id: $( '#un_section' ).val(),
                },
                pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
            } ),
            columns: [ {
                    data: 'admission_no',
                    name: 'admission_no'
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                @if( !moduleStatusCheck( 'University' ) && generalSetting() -> with_guardian ) {
                    data: 'parents.fathers_name',
                    name: 'parents.fathers_name'
                },
                @endif {
                    data: 'dob',
                    name: 'dob'
                },
                @if( moduleStatusCheck( 'University' ) ) {
                    data: 'semester_label',
                    name: 'semester_label'
                },
                {
                    data: 'class_sec',
                    name: 'class_sec'
                },
                @else {
                    data: 'class_sec',
                    name: 'class_sec'
                },
                @endif {
                    data: 'gender.base_setup_name',
                    name: 'gender.base_setup_name'
                },
                {
                    data: 'category.category_name',
                    name: 'category.category_name'
                },
                {
                    data: 'mobile',
                    name: 'sm_students.mobile'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'first_name',
                    name: 'first_name',
                    visible: false
                },
                {
                    data: 'last_name',
                    name: 'last_name',
                    visible: false
                },
            ],
            bLengthChange: false,
            bDestroy: true,
            language: {
                search: "<i class='ti-search'></i>",
                searchPlaceholder: window.jsLang( 'quick_search' ),
                paginate: {
                    next: "<i class='ti-arrow-right'></i>",
                    previous: "<i class='ti-arrow-left'></i>",
                },
            },
            dom: "Bfrtip",
            buttons: [ {
                    extend: "copyHtml5",
                    text: '<i class="fa fa-files-o"></i>',
                    title: $( "#logo_title" ).val(),
                    titleAttr: window.jsLang( 'copy_table' ),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                {
                    extend: "excelHtml5",
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: window.jsLang( 'export_to_excel' ),
                    title: $( "#logo_title" ).val(),
                    margin: [ 10, 10, 10, 0 ],
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                {
                    extend: "csvHtml5",
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: window.jsLang( 'export_to_csv' ),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                {
                    extend: "pdfHtml5",
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    title: $( "#logo_title" ).val(),
                    titleAttr: window.jsLang( 'export_to_pdf' ),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                    orientation: "landscape",
                    pageSize: "A4",
                    margin: [ 0, 0, 0, 12 ],
                    alignment: "center",
                    header: true,
                    customize: function ( doc ) {
                        doc.content[ 1 ].margin = [ 100, 0, 100, 0 ]; //left, top, right, bottom
                        doc.content.splice( 1, 0, {
                            margin: [ 0, 0, 0, 12 ],
                            alignment: "center",
                            image: "data:image/png;base64," + $( "#logo_img" ).val(),
                        } );
                        doc.defaultStyle = {
                            font: 'DejaVuSans'
                        }
                    },
                },
                {
                    extend: "print",
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: window.jsLang( 'print' ),
                    title: $( "#logo_title" ).val(),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                {
                    extend: "colvis",
                    text: '<i class="fa fa-columns"></i>',
                    postfixButtons: [ "colvisRestore" ],
                },
            ],
            columnDefs: [ {
                visible: false,
            }, ],
            responsive: true,
        } );
    } );

</script>
@endpush
