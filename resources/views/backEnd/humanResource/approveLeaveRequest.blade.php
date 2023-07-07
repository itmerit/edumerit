@extends('backEnd.master')
@php $pending = Illuminate\Support\Str::contains(Request::path(), 'pending');  @endphp
@section('title')
    @if ($pending)
        @lang('leave.pending_leave_request')
    @else
        @lang('leave.approve_leave_request')
    @endif
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid p-0">
            <div class="row justify-content-between">

                <h1>
                    @if ($pending)
                        @lang('leave.pending_leave_request')
                    @else
                        @lang('leave.approve_leave_request')
                    @endif
                </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('leave.leave')</a>
                    @php $pending = Illuminate\Support\Str::contains(Request::path(), 'pending');  @endphp

                    @if ($pending)
                        <a href="#">@lang('leave.pending_leave_request')</a>
                    @else
                        <a href="#">@lang('leave.approve_leave_request')</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        @php $pending = Illuminate\Support\Str::contains(Request::path(), 'pending');  @endphp

                        @if ($pending)
                            <h3>@lang('leave.pending_leave_request')
                                <h3>
                                @else
                                    <h3>@lang('leave.approve_leave_request')<h3>
                        @endif
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
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.type')</th>
                                    <th>@lang('common.from')</th>
                                    <th>@lang('common.to')</th>
                                    <th>@lang('leave.apply_date')</th>
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
    </section>

    <div class="modal fade admin-query" id="deleteApplyLeaveModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.delete_item')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;
                    </button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.cancel')</button>
                        {{ Form::open(['route' => ['delete-apply-leave'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="">
                        <button class="primary-btn fix-gr-bg"
                            type="submit">@lang('common.delete')</button>
                        {{ Form::close() }}
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
                "ajax": $.fn.dataTable.pipeline({
                    @if ($pending)
                        url: "{{ route('ajaxPendingLeave') }}",
                    @else
                        url: "{{ route('ajaxApproveLeave') }}",
                    @endif
                    data: {},
                    pages: "{{ generalSetting()->ss_page_load }}" // number of pages to cache

                }),
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'leave_type.type',
                        name: 'leave_type.type'
                    },
                    {
                        data: 'f_date',
                        name: 'f_date'
                    },
                    {
                        data: 't_date',
                        name: 't_date'
                    },
                    {
                        data: 'a_date',
                        name: 'a_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true
                    },
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
                                font: "DejaVuSans",
                            };
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
        });
    </script>
    <script>
        function deleteApplyLeave(id) {
            var modal = $('#deleteApplyLeaveModal');
            modal.find('input[name=id]').val(id)
            modal.modal('show');
        }
    </script>
@endpush
