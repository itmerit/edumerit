@extends('backEnd.master')

@section('title')
    @lang('admin.complaint')
@endsection

@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('admin.complaint')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('admin.admin_section')</a>
                    <a href="#">@lang('admin.complaint')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($complaint))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{ route('complaint') }}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if (isset($complaint))
                                        @lang('admin.edit_complaint')
                                    @else
                                        @lang('admin.add_complaint')
                                    @endif
                                </h3>
                            </div>
                            @if (isset($complaint))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'complaint/' . @$complaint->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                            @else
                                @if (userPermission('complaint_store'))
                                    {{ Form::open([
                                        'class' => 'form-horizontal',
                                        'files' => true,
                                        'url' => 'complaint',
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.complaint_by')
                                                    <span class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field"
                                                    id="apply_date" type="text" name="complaint_by"
                                                    value="{{ isset($complaint) ? $complaint->complaint_by : old('complaint_by') }}">
                                                

                                                @if ($errors->has('complaint_by'))
                                                    <span class="text-danger" >
                                                        {{ @$errors->first('complaint_by') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id"
                                        value="{{ isset($complaint) ? $complaint->id : '' }}">
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('admin.complaint_type')
                                                <span class="text-danger"> *</span></label>
                                            <select
                                                class="primary_select"
                                                name="complaint_type">
                                                <option data-display="@lang('admin.complaint_type') *" value="">
                                                    @lang('admin.type') *</option>
                                                @foreach ($complaint_types as $complaint_type)
                                                    @if (isset($complaint))
                                                        <option value="{{ @$complaint_type->id }}"
                                                            {{ @$complaint_type->id == @$complaint->complaint_type ? 'selected' : '' }}>
                                                            {{ @$complaint_type->name }}</option>
                                                    @else
                                                        <option value="{{ @$complaint_type->id }}"
                                                            {{ old('complaint_type') == @$complaint_type->id ? 'selected' : '' }}>
                                                            {{ @$complaint_type->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('complaint_type'))
                                                <span class="text-danger">
                                                   {{ @$errors->first('complaint_type') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('admin.complaint_source')
                                                <span class="text-danger"> *</span></label>
                                            <select
                                                class="primary_select"
                                                name="complaint_source">
                                                <option data-display="@lang('admin.complaint_source') *" value="">
                                                    @lang('admin.complaint_source') *
                                                </option>
                                                @foreach ($complaint_sources as $complaint_source)
                                                    @if (isset($complaint))
                                                        <option value="{{ @$complaint_source->id }}"
                                                            {{ @$complaint_source->id == @$complaint->complaint_source ? 'selected' : '' }}>
                                                            {{ @$complaint_source->name }}</option>
                                                    @else
                                                        <option value="{{ @$complaint_source->id }}">
                                                            {{ @$complaint_source->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('complaint_source'))
                                                <span class="text-danger">
                                                    {{ @$errors->first('complaint_source') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.phone')</label>
                                                <input oninput="phoneCheck(this)"
                                                    class="primary_input_field"
                                                    id="apply_date" type="text" name="phone"
                                                    value="{{ isset($complaint) ? $complaint->phone : '' }}">


                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.date')<span></span></label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input
                                                                class="primary_input_field  primary_input_field date form-control"
                                                                id="startDate" type="text" name="date"
                                                                value="{{ isset($complaint) ? date('m/d/Y', strtotime($complaint->date)) : (old('date') != '' ? old('date') : date('m/d/Y')) }}">
                                                            </div>
                                                        </div>
                                                        <button class="btn-date" data-id="#startDate" type="button">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('date') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="row mt-15">
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('admin.actions_taken')
                                                    </label>
                                                    <input
                                                        class="primary_input_field form-control{{ @$errors->has('action_taken') ? ' is-invalid' : '' }}"
                                                        id="apply_date" type="text" name="action_taken"
                                                        value="{{ isset($complaint) ? $complaint->action_taken : old('action_taken') }}">


                                                    @if ($errors->has('action_taken'))
                                                        <span class="text-danger" >
                                                            <strong>{{ @$errors->first('action_taken') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-15">
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label"
                                                        for="">@lang('admin.assigned')</label>
                                                    <input
                                                        class="primary_input_field form-control{{ @$errors->has('assigned') ? ' is-invalid' : '' }}"
                                                        id="apply_date" type="text" name="assigned"
                                                        value="{{ isset($complaint) ? $complaint->assigned : old('assigned') }}">


                                                    @if ($errors->has('assigned'))
                                                        <span class="text-danger" >
                                                            <strong>{{ @$errors->first('assigned') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-15">
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('admin.description')
                                                        <span></span></label>
                                                    @isset($complaint)
                                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="description">{{ @$complaint->description }}</textarea>
                                                    @else
                                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="description">{{ old('description') }}</textarea>
                                                        @endif
                                                    

                                                </div>
                                            </div>
                                        </div>
                                     
                                        <div class="row mt-15">
                                            <div class="col-lg-12 mt-15">
                                                <div class="primary_input">
                                                    <div class="primary_file_uploader">
                                                        <input class="primary_input_field" id="placeholderInput" type="text"
                                                        placeholder="{{ isset($complaint) ? ($complaint->file != '' ? getFilePath3($complaint->file) : trans('common.file')) : trans('common.file') }}"
                                                        readonly>
                                                        <button class="" type="button">
                                                            <label class="primary-btn small fix-gr-bg" for="browseFile">{{ __('common.browse') }}</label>
                                                            <input type="file" class="d-none" name="file" id="browseFile">
                                                        </button>
                                                    </div>
                                                </div>
                                                <code>(PDF,DOC,DOCX,JPG,JPEG,PNG,TXT are allowed for upload)</code>
                                            </div>
                                        </div>
                                        @php
                                            $tooltip = '';
                                            if (userPermission('complaint_store')) {
                                                $tooltip = '';
                                            } else {
                                                $tooltip = 'You have no permission to add';
                                            }
                                        @endphp
                                        <div class="row mt-40">
                                            <div class="col-lg-12 text-center">
                                                <button class="primary-btn fix-gr-bg now_wrap_lg submit" data-toggle="tooltip"
                                                    title="{{ $tooltip }}">
                                                    <span class="ti-check"></span>
                                                    @if (isset($complaint))
                                                        @lang('admin.update_complaint')
                                                    @else
                                                        @lang('admin.save_complaint')
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
                                    <h3 class="mb-0">@lang('admin.complaint') @lang('admin.list')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table data-table Crm_table_active3" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>
                                                <th>@lang('common.sl')</th>
                                                <th>@lang('admin.complaint_by')</th>
                                                <th>@lang('admin.complaint_type')</th>
                                                <th>@lang('admin.source')</th>
                                                <th>@lang('admin.phone')</th>
                                                <th>@lang('admin.date')</th>
                                                <th>@lang('admin.actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach (@$complaints as $key => $complaint)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ @$complaint->complaint_by }}</td>
                                                    <td>{{ isset($complaint->complaint_type) ? @$complaint->complaintType->name : '' }}
                                                    </td>
                                                    <td>{{ isset($complaint->complaint_source) ? @$complaint->complaintSource->name : '' }}
                                                    </td>

                                                    <td>{{ $complaint->phone }}</td>
                                                    <td data-sort="{{ strtotime(@$complaint->date) }}">
                                                        {{ !empty(@$complaint->date) ? dateConvert(@$complaint->date) : '' }} </td>
                                                    <td>

                                                        <div class="dropdown CRM_dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false"> {{ __('common.select') }}
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right"
                                                                aria-labelledby="dropdownMenu2">
                                                                @if (userPermission('complaint_show'))
                                                                    <a class="dropdown-item modalLink"
                                                                        title="{{ __('admin.complaint_details') }}"
                                                                        data-modal-size="large-modal"
                                                                        href="{{ url('complaint', [@$complaint->id]) }}">@lang('common.view')</a>
                                                                @endif
                                                                @if (userPermission('complaint_edit'))
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('complaint/' . @$complaint->id . '/edit') }}">@lang('common.edit')</a>
                                                                @endif
                                                                @if (userPermission('complaint_delete'))
                                                                    <a class="dropdown-item" data-toggle="modal"
                                                                        data-target="#deleteComplaintModal{{ $complaint->id }}"
                                                                        href="#">@lang('common.delete')</a>
                                                                @endif
                                                                @if (@$complaint->file != '')
                                                                    @if (userPermission('download-complaint-document'))
                                                                        @if (@file_exists($complaint->file))
                                                                            <a class="dropdown-item"
                                                                                href="{{ url(@$complaint->file) }}" download>
                                                                                @lang('common.download') <span
                                                                                    class="pl ti-download"></span>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <div class="modal fade admin-query"
                                                    id="deleteComplaintModal{{ @$complaint->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('admin.delete_complaint')</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;
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
                                                                    {{ Form::open(['url' => 'complaint/' . $complaint->id, 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                                    <button class="primary-btn fix-gr-bg"
                                                                        type="submit">@lang('common.delete')
                                                                    </button>
                                                                    {{ Form::close() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach --}}
                                        </tbody>
                                    </table>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    {{-- delete modal here  --}}
        <div class="modal fade admin-query" id="deleteComplaintModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('admin.delete_complaint')</h4>
                    <button type="button" class="close"
                        data-dismiss="modal">&times;
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
                        {{ Form::open(['route' => 'complaint_delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                         <input type="hidden" name="id" id="c_id" value="">
                        <button class="primary-btn fix-gr-bg"
                            type="submit">@lang('common.delete')
                        </button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
     {{-- delete modal end here  --}}
    @endsection
    @include('backEnd.partials.data_table_js')
    @include('backEnd.partials.server_side_datatable')
    
    @push('script')

        <script>

            function deleteComplaint(id){
                var modal = $('#deleteComplaintModal');
                modal.find('input[name=id]').val(id)
                modal.modal('show');
            }
            $(document).ready(function() {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    "ajax": $.fn.dataTable.pipeline( {
                        url: "{{route('complaint_list_datatable')}}",
                        data: {
                            
                        },
                        pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                    } ),
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'complaint_by', name: 'complaint_by'},                  
                        {data: 'complaint_type', name: 'complaint_type'},  
                        {data: 'complaint_source', name: 'complaint_source'},
                        {data: 'phone', name: 'phone'},
                        {data: 'c_date', name: 'c_date'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
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

    @include('backEnd.partials.date_picker_css_js')