@extends('backEnd.master')
@section('title')
    @lang('hr.staff_list')
@endsection
@section('mainContent')
    @push('css')
        <style type="text/css">
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }

            input:checked+.slider {
                background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
            }

            input:focus+.slider {
                box-shadow: 0 0 1px linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
            }

            input:checked+.slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }

            /* Rounded sliders */
            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }

            /* th,td{
                            font-size: 9px !important;
                            padding: 5px !important

                        } */
        </style>
    @endpush
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.staff_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('hr.human_resource')</a>
                    <a href="#">@lang('hr.staff_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6 col-6">
                    <div class="main-title xs_mt_0 mt_0_sm">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>

                @if (userPermission('addStaff'))
                    <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg col-6 text_sm_right">
                        <a href="{{ route('addStaff') }}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('hr.add_staff')
                        </a>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'searchStaff', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="role_id" id="role_id" value="{{ @$data['role_id'] }}">
                            <input type="hidden" name="staff_no" id="staff_no" value="{{ @$data['staff_no'] }}">
                            <input type="hidden" name="staff_name" id="staff_name" value="{{ @$data['staff_name'] }}">
                            <div class="col-lg-4">
                                <label class="primary_input_label" for="">
                                    {{ __('common.role') }}
                                    <span class="text-danger"> </span>
                                </label>
                                <select class="primary_select  form-control" name="role_id" id="role_id">
                                    <option data-display="@lang('hr.role')" value=""> @lang('common.select') </option>
                                    @foreach ($roles as $key => $value)
                                        <option value="{{ $value->id }}"
                                            @if (isset($data['role_id']) && $value->id == $data['role_id']) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mt-30-md">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">
                                        {{ __('hr.search_by_staff_id') }}
                                        <span class="text-danger"> </span>
                                    </label>
                                    <input class="primary_input_field" type="text" placeholder=" @lang('hr.search_by_staff_id')"
                                        name="staff_no" value="{{ @$data['staff_no'] }}">

                                </div>
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.search_by_name') }}
                                        <span class="text-danger"> </span>
                                    </label>
                                    <input class="primary_input_field" type="text" placeholder="@lang('common.search_by_name')"
                                        name="staff_name" value="{{ @$data['staff_name'] }}">

                                </div>
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
                <div class="row mt-40 full_wide_table">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('hr.staff_list')</h3>
                                </div>
                            </div>
                        </div>
                        <x-table>
                            <table id="table_id" class="table data-table no-footer dtr-inline collapsed" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('hr.staff_no')</th>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('hr.role')</th>
                                        <th>@lang('hr.department')</th>
                                        <th>@lang('hr.designation')</th>
                                        <th>@lang('common.mobile')</th>
                                        <th>@lang('common.email')</th>
                                        <th>@lang('common.status')</th>
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
    </section>
    {{-- deleteStaffModal --}}
    <div class="modal fade admin-query" id="deleteStaffModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('hr.Confirmation Required') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">  
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>
                    <div class="mt-40 d-flex justify-content-between">

                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
    
                        {{ Form::open(['route' => 'delete_staff', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="">
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
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
                           url: "{{route('staff_directory_ajax')}}",
                           data: { 
                            },
                           pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                           
                       } ),
                       columns: [
                           {data: 'DT_RowIndex', name: 'id'},
                           {data: 'full_name', name: 'full_name'},
                           {data: 'roles.name', name: 'roles.name'},
                           {data: 'departments.name', name: 'departments.name'},
                           {data: 'designations.title', name: 'designations.title'},
                           {data: 'mobile', name: 'mobile'},
                           {data: 'email', name: 'email'},
                           {data: 'switch', name: 'switch'},
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
    function deleteStaff(id){
        var modal = $('#deleteStaffModal');
        modal.find('input[name=id]').val(id)
        modal.modal('show');
    }
</script>
@endpush
