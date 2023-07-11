@extends('backEnd.master')
@section('title')
@lang('reports.student_login_info')
@endsection
@section('mainContent')
<input type="text" hidden value="{{ @$clas->class_name }}" id="cls">
<input type="text" hidden value="{{ @$clas->section_name->sectionName->section_name }}" id="sec">
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.student_login_info')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.student_login_info')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria') </h3>
                </div>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_login_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        @if(moduleStatuscheck('University'))
                        @includeIf('university::common.session_faculty_depart_academic_semester_level',['required' =>
                        ['US'], 'hide' => ['USUB']])
                        @else
                        <div class="col-lg-6 mt-30-md col-md-6">
                            <label class="primary_input_label" for="">{{ __('common.class') }}
                                <span class="text-danger"> *</span>
                            </label>
                            <select
                                class="primary_select  form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                id="select_class" name="class">
                                <option data-display="@lang('common.select_class') *" value="">
                                    @lang('common.select_class') *</option>
                                @foreach($classes as $class)
                                <option value="{{$class->id}}"
                                    {{isset($class_id)? ($class->id == $class_id? 'selected':''): ''}}>
                                    {{$class->class_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('class'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('class') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-6 mt-30-md col-md-6" id="select_section_div">
                            <label class="primary_input_label" for="">{{ __('common.section') }}
                                <span></span>
                            </label>
                            <select
                                class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                id="select_section" name="section">
                                <option data-display="@lang('reports.select_current_section')" value="">
                                    @lang('reports.select_current_section')</option>
                                @if(isset($class_id))
                                @foreach ($class->classSection as $section)
                                <option value="{{ $section->sectionName->id }}"
                                    {{ old('section')==$section->sectionName->id ? 'selected' : '' }}>
                                    {{ $section->sectionName->section_name }}</option>
                                @endforeach
                                @endif
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
                </div>
            </div>
        </div>

        {{ Form::close() }}
        @if(isset($student_records))
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('reports.manage_login')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('student.student_name')</th>
                                        <th>@lang('reports.email_&_password')</th>
                                        <th>@lang('reports.parent_email_&_password')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $count=1;
                                    @endphp
                                    @foreach($student_records as $record)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td class="text-center">{{@$record->student->admission_no}}</td>
                                        <td>{{@$record->student->first_name.' '.@$record->student->last_name}}</td>
                                        <td>
                                            @if(@$record->student->user)
                                            {{@$record->student->user !=""? @$record->student->user->email:""}}
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                            <input type="hidden" name="id" value="{{@$record->student->user_id}}">
                                            <div class="row mt-10">
                                                <div class="col-lg-6">
                                                    <div class="primary_input md_mb_20">
                                                        <input class="primary_input_field read-only-input" type="text"
                                                            name="new_password" required="true" minlength="6">
                                                        <label class="primary_input_label"
                                                            for="">@lang('reports.reset_password')</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    @if(userPermission('student_login_update'))
                                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                                        @lang('reports.update')
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(@$record->student->parents->parent_user)
                                            {{@$record->student->parents->parent_user->email}}
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                            <input type="hidden" name="id"
                                                value="{{@$record->student->parents->parent_user->id}}">
                                            <div class="row mt-10">
                                                <div class="col-lg-6">
                                                    <div class="primary_input md_mb_20">
                                                        <input class="primary_input_field read-only-input" type="text"
                                                            name="new_password" required="true" minlength="6">
                                                        <label class="primary_input_label"
                                                            for="">@lang('reports.reset_password')</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                                        @lang('common.update')
                                                    </button>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
@include('backEnd.partials.data_table_js')

@push('script')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{url('student-list-datatable')}}",
                    data: {
                        academic_year: $('#academic_id').val(),
                        class: $('#class').val(),
                        section: $('#section').val(),
                        roll_no: $('#roll').val(),
                        name: $('#name').val(),
                        un_session_id: $('#un_session').val(),
                        un_academic_id: $('#un_academic').val(),
                        un_faculty_id: $('#un_faculty').val(),
                        un_department_id: $('#un_department').val(),
                        un_semester_label_id: $('#un_semester_label').val(),
                        un_section_id: $('#un_section').val(),
                    },
                    pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                } ),
                columns: [
                    {data: 'admission_no', name: 'admission_no'},                  
                    {data: 'full_name', name: 'full_name'},  
                    @if(!moduleStatusCheck('University') && generalSetting()->with_guardian)
                     {data: 'parents.fathers_name', name: 'parents.fathers_name'},
                    @endif
                    {data: 'dob', name: 'dob'},
                    @if(moduleStatusCheck('University'))
                        {data: 'semester_label', name: 'semester_label'},
                        {data: 'class_sec', name: 'class_sec'},
                    @else
                        {data: 'class_sec', name: 'class_sec'},
                    @endif
                    {data: 'gender.base_setup_name', name: 'gender.base_setup_name'},
                    {data: 'category.category_name', name: 'category.category_name'},
                    {data: 'mobile', name: 'sm_students.mobile'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'first_name', name: 'first_name', visible : false},
                    {data: 'last_name', name: 'last_name', visible : false},
                ],
                bLengthChange: false,
                bDestroy: true,
                language: {
                    search: "<i class='ti-search'></i>",
                    searchPlaceholder: window.jsLang('quick_search'),
                    paginate: {
                        next: "<i class='ti-arrow-right'></i>",
                        previous: "<i class='ti-arrow-left'></i>",
                    },
                },
                dom: "Bfrtip",
                buttons: [{
                    extend: "copyHtml5",
                    text: '<i class="fa fa-files-o"></i>',
                    title: $("#logo_title").val(),
                    titleAttr: window.jsLang('copy_table'),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                    {
                        extend: "excelHtml5",
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: window.jsLang('export_to_excel'),
                        title: $("#logo_title").val(),
                        margin: [10, 10, 10, 0],
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "csvHtml5",
                        text: '<i class="fa fa-file-text-o"></i>',
                        titleAttr: window.jsLang('export_to_csv'),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        title: $("#logo_title").val(),
                        titleAttr: window.jsLang('export_to_pdf'),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                        orientation: "landscape",
                        pageSize: "A4",
                        margin: [0, 0, 0, 12],
                        alignment: "center",
                        header: true,
                        customize: function(doc) {
                            doc.content[1].margin = [100, 0, 100, 0]; //left, top, right, bottom
                            doc.content.splice(1, 0, {
                                margin: [0, 0, 0, 12],
                                alignment: "center",
                                image: "data:image/png;base64," + $("#logo_img").val(),
                            });
                            doc.defaultStyle = {
                                font: 'DejaVuSans'
                            }
                        },
                    },
                    {
                        extend: "print",
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: window.jsLang('print'),
                        title: $("#logo_title").val(),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "colvis",
                        text: '<i class="fa fa-columns"></i>',
                        postfixButtons: ["colvisRestore"],
                    },
                ],
                columnDefs: [{
                    visible: false,
                }, ],
                responsive: true,
            });
        } );
    </script>


@endpush