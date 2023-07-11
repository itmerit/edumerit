@extends('backEnd.master')
@section('title')
    @lang('study.upload_content_list')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('study.upload_content_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('study.study_material')</a>
                    <a href="#">@lang('study.upload_content_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if (isset($editData))
                                        @lang('study.edit_upload_content')
                                    @else
                                        @lang('study.upload_content')
                                    @endif

                                </h3>
                            </div>
                            @if (isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-upload-content', @$editData->id, 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{ @$editData->id }}">
                            @else
                                @if (userPermission('save-upload-content'))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save-upload-content', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row mb-25">
                                        <div class="col-lg-12 mb-30">
                                            <div class="primary_input">
                                                <label> @lang('study.content_title') <span class="text-danger"> *</span> </label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('content_title') ? ' is-invalid' : '' }}"
                                                    type="text" name="content_title" autocomplete="off"
                                                    value="{{ isset($editData) ? @$editData->content_title : '' }}">


                                                @if ($errors->has('content_title'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('content_title') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-30">
                                            <label class="primary_input_label" for="">
                                                {{ __('study.content_type') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <select
                                                class="primary_select  form-control{{ $errors->has('content_type') ? ' is-invalid' : '' }}"
                                                name="content_type" id="content_type">
                                                <option data-display="@lang('study.content_type') *" value="">@lang('study.content_type')
                                                    *</option>
                                                <option value="as"
                                                    {{ isset($editData) && @$editData->content_type == 'as' ? 'selected' : '' }}>
                                                    @lang('study.assignment')</option>
                                                {{-- <option value="st">@lang('study.study_material')</option> --}}
                                                <option value="sy"
                                                    {{ isset($editData) && @$editData->content_type == 'sy' ? 'selected' : '' }}>
                                                    @lang('study.syllabus')</option>
                                                <option value="ot"
                                                    {{ isset($editData) && @$editData->content_type == 'ot' ? 'selected' : '' }}>
                                                    @lang('study.other_download')</option>
                                            </select>
                                            @if ($errors->has('content_type'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('content_type') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('study.available_for')<span
                                                    class="text-danger"> *</span></label><br>
                                            <div class="">
                                                <input type="checkbox" id="all_admin"
                                                    class="common-checkbox form-control"
                                                    name="available_for[]" value="admin"
                                                    {{ isset($editData) && @$editData->available_for_admin == '1' ? 'checked' : '' }}>
                                                <label style="top: 50% !important;"
                                                    for="all_admin">@lang('study.all_admin')</label>
                                            </div>
                                            <div class="">
                                                <input type="checkbox" id="student"
                                                    class="common-checkbox form-control"
                                                    name="available_for[]" value="student"
                                                    {{ (isset($editData) && @$editData->available_for_all_classes == '1') || @$editData->un_semester_label_id != '' || @$editData->class != '' || @$editData->section != '' ? 'checked' : '' }}>
                                                <label for="student">@lang('common.student')</label>
                                            </div>
                                            @if ($errors->has('available_for'))
                                                <span class="text-danger validate-textarea-checkbox" role="alert">
                                                    {{ $errors->first('available_for') }}
                                                </span>
                                            @endif
                                        </div>
                                        @php
                                            // if( @$editData->available_for_all_classes == "1" || @$editData->class != "" || @$editData->section != ""){
                                            if (@$editData->available_for_all_classes == '1') {
                                                $show = '';
                                                $show1 = 'disabledbutton';
                                            } elseif (@$editData->class != '' || @$editData->section != '') {
                                                $show = 'disabledbutton';
                                                $show1 = '';
                                            } else {
                                                $show = 'disabledbutton';
                                                $show1 = 'disabledbutton';
                                            }
                                        @endphp
                                        @if (!moduleStatusCheck('University'))
                                            <div class="col-lg-12 {{ @$show }} mb-30" id="availableClassesDiv">

                                                <div class="">
                                                    <input type="checkbox" id="all_classes"
                                                        class="common-checkbox form-control" name="all_classes"
                                                        {{ isset($editData) && @$editData->available_for_all_classes == '1' ? 'checked' : '' }}>
                                                    <label for="all_classes">@lang('study.available_for_all_classes')</label>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="forStudentWrapper col-lg-12 mb-20 {{ $errors->has('class') || $errors->has('section') ? '' : @$show1 }}"
                                            id="contentDisabledDiv">
                                            @if(moduleStatusCheck('University'))
                                            @includeIf('university::common.session_faculty_depart_academic_semester_level',['required' => ['USN','UF', 'UD', 'US', 'USL'] , 'hide' => ['USUB','UA'],'row' => 1, 'div' => 'col-lg-12', 'mt' =>'mt-0'])
                                            <input type="hidden" name="un_academic_id" id="select_academic" value="{{getAcademicId()}}">
                                            @else 

                                            <div class="row">
                                                <div class="col-lg-12 mb-20">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">
                                                            {{ __('common.class') }}
                                                                
                                                        </label>
                                                        <select
                                                            class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                                            name="class" id="classSelectStudent">
                                                            <option data-display="@lang('common.select_class') "
                                                                    value="">@lang('common.select')</option>
                                                                @foreach ($classes as $class)
                                                                    <option value="{{ @$class->id }}"
                                                                        {{ isset($editData) && $editData->class == $class->id ? 'selected' : '' }}>
                                                                        {{ @$class->class_name }}</option>
                                                                @endforeach
                                                            </select>

                                                            @if ($errors->has('class'))
                                                                <span class="text-danger invalid-select" role="alert">
                                                                    {{ $errors->first('class') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 mb-20">
                                                        <div class="primary_input" id="sectionStudentDiv">
                                                            <label class="primary_input_label" for="">
                                                                {{ __('common.section') }}
                                                                <span class="text-danger"> </span>
                                                            </label>
                                                            <select
                                                                class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                                                name="section" id="sectionSelectStudent">
                                                                <option data-display="@lang('common.select_section') "
                                                                    value="">@lang('common.section')
                                                                </option>
                                                                @if (isset($editData->section))
                                                                    @foreach ($sections as $section)
                                                                        <option value="{{ $section->id }}"
                                                                            {{ $editData->section == $section->id ? 'selected' : '' }}>
                                                                            {{ $section->section_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <div class="pull-right loader loader_style"
                                                                id="select_section_loader">
                                                                <img class="loader_img_style"
                                                                    src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                                    alt="loader">
                                                            </div>

                                                            @if ($errors->has('section'))
                                                                <span class="text-danger invalid-select" role="alert">
                                                                    {{ $errors->first('section') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif

                                        </div>
                                        <input type="hidden" name="url" id="url"
                                            value="{{ URL::to('/') }}">
                                    </div>
                                    <div class="row  mb-20">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('common.date')
                                                    <span></span> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input
                                                                    class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('upload_date') ? ' is-invalid' : '' }}"
                                                                    id="upload_date" type="text"
                                                                    name="upload_date"
                                                                    value="{{ isset($editData) ? date('m/d/Y', strtotime(@$editData->upload_date)) : date('m/d/Y') }}">
                                                            </div>
                                                        </div>
                                                        <button class="btn-date" data-id="#upload_date" type="button">
                                                            <label class="m-0 p-0" for="upload_date">
                                                                <i class="ti-calendar" id="start-date-icon"></i>
                                                            </label>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('upload_date') }}</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row mb-20">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('study.description')
                                                    <span></span> </label>
                                                <textarea class="primary_input_field form-control" cols="0" rows="3" name="description"
                                                    id="description">{{ @$editData->description }}</textarea>


                                            </div>
                                        </div>
                                    </div>


                                    <div class="row no-gutters input-right-icon mb-20">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label> @lang('study.source_url')</label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('source_url') ? ' is-invalid' : '' }}"
                                                    type="text" name="source_url" autocomplete="off"
                                                    value="{{ isset($editData) ? @$editData->source_url : '' }}">


                                                @if ($errors->has('source_url'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('source_url') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-lg-12 mt-15">
                                            <div class="primary_input">
                                                <div class="primary_file_uploader">
                                                    <input
                                                        class="primary_input_field form-control {{ $errors->has('content_file') ? ' is-invalid' : '' }}"
                                                        readonly="true" type="text"
                                                        placeholder="{{ isset($editData->upload_file) && @$editData->upload_file != '' ? getFilePath3(@$editData->upload_file) : trans('study.file') . '' }}"
                                                        id="placeholderUploadContent">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                            for="upload_content_file">{{ __('common.browse') }}</label>
                                                        <input type="file" class="d-none" name="content_file"
                                                            id="upload_content_file">
                                                    </button>
                                                    <code>(jpg,png,jpeg,pdf,doc,docx,mp4,mp3,txt are allowed for
                                                        upload)</code>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $tooltip = '';
                                        if (userPermission('save-upload-content')) {
                                            @$tooltip = '';
                                        } else {
                                            @$tooltip = 'You have no permission to add';
                                        }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                <span class="ti-check"></span>
                                                @if(isset($editData))
                                                @lang('common.update')  
                                                @else 
                                                @lang('common.save')
                                                @endif 
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0"> @lang('study.upload_content_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th> @lang('study.content_title')</th>
                                            <th> @lang('common.type')</th>
                                            <th> @lang('common.date')</th>
                                            <th> @lang('study.available_for')</th>
                                            @if (moduleStatusCheck('University'))
                                                <th> @lang('university::un.semester_label')</th>
                                                <th> @lang('common.section')</th>
                                            @else
                                                <th> @lang('study.classSec')</th>
                                            @endif
                                            <th> @lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @if (isset($uploadContents))
                                            @foreach ($uploadContents as $key => $value)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{@$value->content_title}}</td>
                                                    <td>
                                                        @if (@$value->content_type == 'as')
                                                            @lang('study.assignment')
                                                        @elseif(@$value->content_type == 'st')
                                                            @lang('study.study_material')
                                                        @elseif(@$value->content_type == 'sy')
                                                            @lang('study.syllabus')
                                                        @else
                                                            @lang('study.other_download')
                                                        @endif
                                                    </td>
                                                    <td  data-sort="{{strtotime(@$value->upload_date)}}" >
                                                        {{@$value->upload_date != ""? dateConvert(@$value->upload_date):''}} 
                                                    </td>
                                                    <td>
                                                        @if (moduleStatusCheck('University'))
                                                            @if (@$value->available_for_admin == 1)
                                                                @lang('study.all_admins')
                                                            @else
                                                                @lang('study.all_students_of') {{ @$value->semesterLabel->name  . '(' . @$value->undepartment->name . ')' }}
                                                            @endif
                                                        @else 
                                                            @if (@$value->available_for_admin == 1)
                                                                @lang('study.all_admins')<br>
                                                            @endif
                                                            @if (@$value->available_for_all_classes == 1)
                                                                @lang('study.all_classes_student')
                                                            @endif
                                                            @if (@$value->classes != '' && $value->sections != '')
                                                                @lang('study.all_students_of') ({{@$value->classes->class_name.'->'.@$value->sections->section_name}})
                                                            @endif
                                                            @if (@$value->classes != '' && $value->section == null)
                                                                @lang('study.all_students_of') ({{@$value->classes->class_name.'->'}} @lang('study.all_sections'))
                                                            @endif
                                                        @endif 
                                                    </td>
                                                    <td>
                                                        @if (moduleStatusCheck('University'))
                                                            @if (!@$value->available_for_admin == 1)
                                                                {{ $value->semesterLabel->name }} -({{@$value->unAcademic->name}})
                                                            @endif
                                                        @else 
                                                            @if (@$value->classes != '')
                                                                {{@$value->classes->class_name}}
                                                            @endif
                                                            @if ($value->sections != '')
                                                                ({{@$value->sections->section_name}})
                                                            @endif
                                                            @if ($value->section == null)
                                                                ( @lang('study.all_sections') )
                                                            @endif
                                                        @endif
                                                    </td>
                                                    @if (moduleStatusCheck('University'))
                                                        <td>{{@$value->unSection->section_name}}</td>
                                                    @endif
                                                    <td>
                                                        
                                                        @php
                                                        $routeList = [

                                                            '<a data-modal-size="modal-lg" title="'. __('study.view_content_details').'" class="dropdown-item modalLink" href="'.route('upload-content-view', $value->id).'">'.__('common.view').'</a>',
                                                                moduleStatusCheck('VideoWatch')== TRUE ?
                                                                    '<a class="dropdown-item" href="'.url('videowatch/view-log/'.$value->id).'">'.__('study.seen').'</a>' : null,
                                                                
                                                                userPermission("upload-content-edit") ? 
                                                                    '<a class="dropdown-item" href="'.route('upload-content-edit',$value->id).'">'.__('common.edit').'</a>':null,

                                                                userPermission("delete-upload-content") ?
                                                                    '<a class="dropdown-item" data-toggle="modal" data-target="#deleteApplyLeaveModal'.$value->id.'" href="#">'.__('common.delete').'</a>':null,

                                                                userPermission("download-content-document") && $value->upload_file != "" ?
                                                                        '<a class="dropdown-item" href="'.url($value->upload_file).'" download>
                                                                            '.__('common.download').' 
                                                                            <span class="pl ti-download"></span></a>'
                                                                    :null
                                                        ]
                                                    @endphp
                                                        <x-drop-down-action-component :routeList="$routeList" />
                                                    </td>
                                                </tr>
                                                <div class="modal fade admin-query" id="deleteApplyLeaveModal{{@$value->id}}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('study.delete_upload_content')</h4>
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
                                                                            data-dismiss="modal">@lang('common.cancel')</button>
                                                                            {{ Form::open(['route' =>'delete-upload-content', 'method' => 'POST']) }}
                                                                                <input type="hidden" name="id" value="{{@$value->id}}">
                                                                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                                            {{ Form::close() }}
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif --}}
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- delete content modal start  --}}
    <div class="modal fade admin-query" id="deleteUpContentModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('study.delete_upload_content')</h4>
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
                            data-dismiss="modal">@lang('common.cancel')</button>
                        {{ Form::open(['route' => 'delete-upload-content', 'method' => 'POST']) }}
                        <input type="hidden" name="id" value="">
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                        {{ Form::close() }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- delete content modal end  --}}
@endsection

@include('backEnd.partials.date_picker_css_js')

@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@push('script')  
<script>
   $(document).ready(function() {
       $('.data-table').DataTable({
                     processing: true,
                     serverSide: true,
                     "ajax": $.fn.dataTable.pipeline( {
                           url: "{{route('upload-content-list-datatable')}}",
                           data: { 
                            },
                           pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                           
                       } ),
                       columns: [
                           {data: 'DT_RowIndex', name: 'id'},
                           {data: 'content_title', name: 'content_title'},
                           {data: 'type', name: 'type'},
                           {data: 'date', name: 'date'},
                           {data: 'avaiable', name: 'avaiable'},
                           {data: 'class_sections', name: 'class_sections'},
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
    <script>
        function deleteUpContent(id) {
            var modal = $('#deleteUpContentModal');
            modal.find('input[name=id]').val(id)
            modal.modal('show');
        }
    </script>
@endpush
