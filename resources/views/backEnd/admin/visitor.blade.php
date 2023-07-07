@extends('backEnd.master')
@push('css')
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/bootstrap-datetimepicker.min.css') }}" />
<style>
    .input-right-icon {
        z-index: inherit !important;
    }
</style>
@endpush
@section('title')
    @lang('admin.visitor_book')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('admin.visitor_book')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('admin.admin_section')</a>
                    <a href="#">@lang('admin.visitor_book')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($visitor))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{ route('visitor') }}" class="primary-btn small fix-gr-bg">
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
                                    @if (isset($visitor))
                                        @lang('admin.edit_visitor')
                                    @else
                                        @lang('admin.add_visitor')
                                    @endif

                                </h3>
                            </div>
                            @if (isset($visitor))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'visitor_update',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                                <input type="hidden" name="id" value="{{ $visitor->id }}">
                            @else
                                @if (userPermission('visitor_store'))
                                    {{ Form::open([
                                        'class' => 'form-horizontal',
                                        'files' => true,
                                        'route' => 'visitor_store',
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.purpose')<span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field {{ $errors->has('purpose') ? ' is-invalid' : '' }}"
                                                    type="text" placeholder="{{ __('admin.purpose') }}"
                                                    value="{{ isset($visitor) ? $visitor->purpose : old('purpose') }}"
                                                    name="purpose">
                                                <span class="text-danger">{{ $errors->first('purpose') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">{{ __('common.name') }}
                                                    <span class="text-danger"> *</span></label>
                                                <input name="name" class="primary_input_field name"
                                                    placeholder="{{ __('common.name') }}" type="text"
                                                    value="{{ isset($visitor) ? $visitor->name : old('name') }}">
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label">@lang('admin.phone')</label>
                                                <input oninput="phoneCheck(this)" placeholder="{{ __('admin.phone') }}"
                                                    class="primary_input_field" type="tel" name="phone"
                                                    value="{{ isset($visitor) ? $visitor->phone : old('phone') }}">

                                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label">@lang('admin.id') <span
                                                        class="text-danger">*</span></label>
                                                <input class="primary_input_field" type="text" name="visitor_id"
                                                    placeholder="{{ __('admin.id') }}"
                                                    value="{{ isset($visitor) ? $visitor->visitor_id : old('visitor_id') }}">
                                                <span class="text-danger">{{ $errors->first('visitor_id') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.no_of_person') <span
                                                        class="text-danger">*</span></label>
                                                <input class="primary_input_field"
                                                    placeholder="{{ __('admin.no_of_person') }}"
                                                    type="text"
                                                    onkeypress="return isNumberKey(event)" name="no_of_person"
                                                    value="{{ isset($visitor) ? $visitor->no_of_person : old('no_of_person') }}">


                                                @if ($errors->has('no_of_person'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('no_of_person') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-gutters input-right-icon mt-15">
                                        <div class="col">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.date')</label>
                                                <input class="primary_input_field  primary_input_field date form-control"
                                                    placeholder="{{ __('admin.date') }}" id="startDate" type="text"
                                                    name="date"
                                                    value="{{ isset($visitor) ? date('m/d/Y', strtotime($visitor->date)) : date('m/d/Y') }}">
                                                @if ($errors->has('date'))
                                                    <span class="text-danger d-block">
                                                        {{ $errors->first('date') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <button class="" type="button">
                                            <label class="m-0 pt-2" for="startDate">
                                                <i class="ti-calendar" id="admission-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>



                                    <div class="row mt-15">
                                        <div class="col-md-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.in_time') <span
                                                        class="text-danger">*</span></label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input class="primary_input_field primary_input_field time"
                                                                    type="text" name="in_time" placeholder="-" id="in_time"
                                                                    value="{{ isset($visitor) ? $visitor->in_time : old('in_time') }}">

                                                                @if ($errors->has('in_time'))
                                                                    <span class="text-danger d-block">
                                                                        {{ $errors->first('in_time') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <label class="m-0 p-0" for="in_time">
                                                                <i class="ti-alarm-clock " id="admission-date-icon"></i>
                                                            </label>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-md-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('admin.out_time') <span
                                                        class="text-danger">*</span></label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input placeholder="-"
                                                                    class="primary_input_field primary_input_field time"
                                                                    type="text" name="out_time" id="out_time"
                                                                    placeholder="{{ __('admin.out_time') }}"
                                                                    value="{{ isset($visitor) ? $visitor->out_time : old('out_time') }}">

                                                                @if ($errors->has('out_time'))
                                                                    <span class="text-danger d-block">
                                                                        {{ $errors->first('out_time') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <button class="" type="button">
                                                            <label class="m-0 p-0" for="out_time">
                                                                <i class="ti-alarm-clock " id="admission-date-icon"></i>
                                                            </label>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                    for="">{{ trans('common.file') }}</label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary_input_field form-control{{ $errors->has('upload_event_image') ? ' is-invalid' : '' }}" type="text" id="placeholderEventFile"
                                                        placeholder="{{ isset($visitor) ? ($visitor->file != '' ? getFilePath3($visitor->file) : trans('common.file')) : trans('common.file') }}"
                                                        readonly="">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="upload_event_image"><span
                                                                class="ripple rippleEffect"
                                                                style="width: 56.8125px; height: 56.8125px; top: -16.4062px; left: 10.4219px;"></span>@lang('common.browse')</label>
                                                        <input type="file" class="d-none" name="upload_event_image"
                                                            id="upload_event_image">
                                                    </button>
                                                </div>
                                                <code>(PDF,DOC,DOCX,JPG,JPEG,PNG,TXT are allowed for upload)</code>
                                                @if ($errors->has('upload_event_image'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('upload_event_image') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $tooltip = '';
                                        if (userPermission('visitor_store')) {
                                            $tooltip = '';
                                        } else {
                                            $tooltip = 'You have no permission to add';
                                        }
                                    @endphp

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                <span class="ti-check"></span>
                                                @if (isset($visitor))
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
                                <h3 class="mb-0">@lang('admin.visitor_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="Crm_table_active3 table data-table" cellspacing="0" width="100%">

                                    <thead>

                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('admin.no_of_person')</th>
                                            <th>@lang('admin.phone')</th>
                                            <th>@lang('admin.purpose')</th>
                                            <th>@lang('admin.date')</th>
                                            <th>@lang('admin.in_time')</th>
                                            <th>@lang('admin.out_time')</th>
                                            <th>@lang('common.actions')</th>
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
    <!-- Start Delete Add Modal -->
    <div class="modal fade admin-query" id="deleteVisitorModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('admin.delete_visitor') </h4>
                    <button type="button" class="close"
                        data-dismiss="modal">&times;
                    </button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        {{ Form::open(['route' => 'visitor_delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="">
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Delete Add Modal -->
@endsection
@include('backEnd.partials.date_picker_css_js')
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@section('script')
    <script>
        function deleteQueryModal(id) {
            var modal = $('#deleteVisitorModal');
            modal.find('input[name=id]').val(id)
            modal.modal('show');
        }

        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{url('visitor-datatable')}}",
                    data: {},
                    pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                } ),
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},               
                    {data: 'name', name: 'name'},
                    {data: 'no_of_person', name: 'no_of_person'},
                    {data: 'phone', name: 'phone'},
                    {data: 'purpose', name: 'purpose'},
                    {data: 'query_date', name: 'query_date'},
                    {data: 'in_time', name: 'in_time'},
                    {data: 'out_time', name: 'out_time'},
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
                buttons: [
                    {
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
                columnDefs: [
                    {
                        visible: false,
                    }, 
                ],
                responsive: true,
            });
        } );
    </script>
@endsection
