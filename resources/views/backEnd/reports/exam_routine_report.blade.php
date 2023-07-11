@extends('backEnd.master')
@section('title')
@lang('reports.exam_routine_report')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.exam_routine_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.exam_routine_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria') </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_routine_report', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="col-lg-4 mt-30-md">
                            <label class="primary_input_label" for="">{{ __('exam.exam') }}<span class="text-danger">
                                    *</span></label>
                            <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                name="exam">
                                <option data-display="@lang('reports.select_exam') *" value="">
                                    @lang('reports.select_exam') *</option>
                                @foreach($exam_types as $exam)
                                <option value="{{$exam->id}}"
                                    {{isset($exam_term_id)? ($exam->id == $exam_term_id? 'selected':''):''}}>
                                    {{$exam->title}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('exam'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('exam') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-4 mt-30-md">
                            <label class="primary_input_label" for="">{{ __('common.class') }}<span class="text-danger">
                                    *</span></label>
                            <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                id="select_class" name="class">
                                <option data-display="@lang('common.select_class') *" value="">
                                    @lang('common.select_class') *
                                </option>
                                @foreach($classes as $class)
                                <option value="{{@$class->id}}"
                                    {{isset($class_id) ? ($class_id == $class->id? 'selected':''):''}}>
                                    {{@$class->class_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('class'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('class') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-4 mt-30-md" id="select_section_div">
                            <label class="primary_input_label" for="">{{ __('common.section') }}<span></span></label>
                            <select
                                class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                id="select_section" name="section">
                                <option data-display="@lang('common.select_section') " value="">
                                    @lang('common.select_section')
                                </option>
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
    </div>
</section>
@if(isset($exam_schedules))
<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('reports.exam_routine')</h3>
                </div>
            </div>
            <div class="col-lg-6 mb-30  col-md-6">
                <a href="{{route('exam-routine-print', [$class_id, $section_id,$exam_type_id])}}"
                    class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>
                    @lang('common.print')</a>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <x-table>
                    <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    @lang('reports.date_&_day')
                                </th>
                                <th>@lang('common.subject')</th>
                                <th>@lang('common.class_Sec')</th>
                                <th>@lang('common.teacher')</th>
                                <th>@lang('common.time')</th>
                                <th>@lang('common.duration')</th>
                                <th>@lang('common.room')</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exam_schedules as $date => $exam_routine)
                            <tr>
                                <td>{{ dateConvert($exam_routine->date) }}
                                    <br>{{ Carbon::createFromFormat('Y-m-d', $exam_routine->date)->format('l'); }}</td>
                                <td>
                                    <strong> {{ $exam_routine->subject ? $exam_routine->subject->subject_name :'' }}
                                    </strong>
                                    {{ $exam_routine->subject ? '('.$exam_routine->subject->subject_code .')':'' }}
                                </td>
                                <td>{{ $exam_routine->class ? $exam_routine->class->class_name :'' }}
                                    {{ $exam_routine->section ? '('. $exam_routine->section->section_name .')':'' }}
                                </td>
                                <td>{{ $exam_routine->teacher ? $exam_routine->teacher->full_name :'' }}</td>

                                <td> {{ date('h:i A', strtotime(@$exam_routine->start_time))  }} -
                                    {{ date('h:i A', strtotime(@$exam_routine->end_time))  }} </td>
                                <td>
                                    @php
                                    $duration=strtotime($exam_routine->end_time)-strtotime($exam_routine->start_time);
                                    @endphp

                                    {{ timeCalculation($duration)}}
                                </td>

                                <td>{{ $exam_routine->classRoom ? $exam_routine->classRoom->room_no :''  }}</td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </x-table>
            </div>
        </div>
    </div>
</section>

@endif



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
