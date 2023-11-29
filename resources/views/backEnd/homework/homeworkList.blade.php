@extends('backEnd.master')
@section('title')
@lang('homework.homework_list')
@endsection
@section('mainContent')
@php
    $DATE_FORMAT = systemDateFormat();   
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('homework.homework_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('homework.home_work')</a>
                <a href="#">@lang('homework.homework_list')</a>
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
            <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                <a href="{{route('add-homeworks')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('homework.add_homework')
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'homework-list', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    @if(moduleStatusCheck('University'))
                    <div class="row">
                        @includeIf('university::common.session_faculty_depart_academic_semester_level',['subject'=>true])
                    </div>
                    @else
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.class') }}
                                            <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select  form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}" name="class_id"  id="class_subject">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select')</option>
                                        @foreach($classes as $key=>$value)
                                            <option value="{{$value->id}}" @if(isset($class) && $class == $value->id) selected  @endif >{{$value->class_name}}</option>
                                        @endforeach
                                    </select>
                                    
                                    @if ($errors->has('class_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class_id') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="primary_input" id="select_class_subject_div">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.subject') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select  form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }} select_class_subject" name="subject_id" id="select_class_subject">
                                        <option data-display="@lang('common.select_subjects')" value="">@lang('common.subject')</option>
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_subject_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                
                                    
                                    @if ($errors->has('subject_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('subject_id') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="primary_input" id="m_select_subject_section_div">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.section') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select  form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }} m_select_subject_section" name="section_id" id="m_select_subject_section">
                                        <option data-display="@lang('common.select_section')" value="">@lang('common.section')</option>
                                        @isset($subjectSections)
                                            @foreach($subjectSections as $key=>$subjectSection)
                                            <option value="{{$subjectSection->id}}" {{ isset($search_info['section_id']) ? ($search_info['section_id'] == $subjectSection->id ? 'selected':'') :'' }}>{{$subjectSection->section_name}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    
                                    @if ($errors->has('section_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section_id') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        
                           
                        </div>
                    @endif  

                    <input type="hidden" name="class" id="class" value="{{@$class}}">
                    <input type="hidden" name="subject" id="subject" value="{{@$subject}}">
                    <input type="hidden" name="section" id="section" value="{{@$section}}">
                    
                    <div class="col-lg-12 mt-20 text-right">
                        <button type="submit" class="primary-btn small fix-gr-bg">
                            <span class="ti-search pr-2"></span>
                            @lang('common.search')
                        </button>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
                                  
    <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('homework.homework_list')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <x-table>
                    <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Si</th>
                                @if(moduleStatusCheck('University'))
                                <th>@lang('university::un.semester_label')</th>
                                <th>@lang('university::un.department')</th>
                                @else
                                    <th>@lang('common.class')</th>
                                    <th>@lang('common.section')</th>
                                @endif
                                
                                <th>@lang('homework.subject')</th>
                                <th>@lang('homework.marks')</th>
                                <th>@lang('homework.home_work_date')</th>
                                <th>@lang('homework.submission_date')</th>
                                <th>@lang('homework.evaluation_date')</th>
                                <th>@lang('homework.created_by')</th>
                                <th>@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    </x-table>
                </div>
            </div>           
            </div>
        </div>
    
    </div>
</section>

{{-- delete homework  --}}
<div class="modal fade admin-query" id="deleteHomeWorkModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('common.delete')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            {{ Form::open(['route' => 'homework-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="modal-body">
                <input type="hidden" name="id" value="">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                    
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@push('script')  

<script>
   $(document).ready(function() {
       $('.data-table').DataTable({
                     processing: true,
                     serverSide: true,
                     "ajax": $.fn.dataTable.pipeline( {
                           url: "{{route('homework-list-ajax')}}",
                           data: { 
                                class: $("#class").val(),
                                subject: $("#subject").val(),
                                section: $("#section").val(),
                            },
                           pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                           
                       } ),
                       columns: [
                           {data: 'DT_RowIndex', name: 'id'},
                           @if(moduleStatusCheck('University'))
                            {data: 'un_session.name', name: 'sections.name'},
                            {data: 'un_semester.name', name: 'un_semester.name'},
                           @else 
                            {data: 'classes.class_name', name: 'classes.class_name'},
                            {data: 'sections.section_name', name: 'sections.section_name'},
                           @endif 
                           {data: 'subjects.subject_name', name: 'subjects.subject_name'},
                           {data: 'marks', name: 'marks'},
                           {data: 'homework_date', name: 'homework_date'},
                           {data: 'submission_date', name: 'submission_date'},
                           {data: 'evaluation_date', name: 'evaluation_date'},
                           {data: 'users.full_name', name: 'users.full_name'},
                           {data: 'action', name: 'action', orderable: false, searchable: true},
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
<script>
    function deleteHomeWork(id){
        var modal = $('#deleteHomeWorkModal');
        modal.find('input[name=id]').val(id)
        modal.modal('show');
    }
</script>
@endpush