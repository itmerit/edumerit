@extends('backEnd.master')
@section('title')
    @lang('fees::feesModule.fees_due')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('fees::feesModule.fees_due')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('fees::feesModule.fees')</a>
                    <a href="#">@lang('fees::feesModule.fees_due')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'route' => 'fees.search-due-fees', 'method' => 'POST']) }}
                        @include('fees::report._searchForm')
                        <input type="hidden" id="dateFrom" value="{{ @$date_from }}">
                        <input type="hidden" id="dateTo" value="{{ @$date_to }}">
                        <input type="hidden" id="class" value="{{ @$class }}">
                        <input type="hidden" id="section" value="{{ @$section }}">
                        <input type="hidden" id="student" value="{{ @$student }}">
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="row mt-80">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12 search_hide_md">
                            <x-table>
                                <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('student.admission_no')</th>
                                            <th>@lang('student.roll_no')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('fees::feesModule.due_date')</th>
                                            <th>@lang('fees::feesModule.amount') ({{ generalSetting()->currency_symbol }})</th>
                                            <th>@lang('fees::feesModule.paid') ({{ generalSetting()->currency_symbol }})</th>
                                            <th>@lang('fees::feesModule.waiver') ({{ generalSetting()->currency_symbol }})</th>
                                            <th>@lang('fees::feesModule.fine') ({{ generalSetting()->currency_symbol }})</th>
                                            <th>@lang('fees::feesModule.balance') ({{ generalSetting()->currency_symbol }})</th>
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
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
@include('backEnd.partials.date_range_picker_css_js')
@include('backEnd.partials.server_side_datatable')
@push('script')
    <script type="text/javascript" src="{{ url('Modules\Fees\Resources\assets\js\app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                // serverSide: true,
                "ajax": $.fn.dataTable.pipeline({
                    url: "{{ url('fees/fees-report-datatable') }}",
                    data: {
                        date_from: $('#dateFrom').val(),
                        date_to: $('#dateTo').val(),
                        class: $('#class').val(),
                        section: $('#section').val(),
                        student: $('#student').val(),
                    },
                    pages: "{{ generalSetting()->ss_page_load }}" // number of pages to cache
                }),
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };
                    total = api
                        .column(5)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    pageTotal = api
                        .column(5, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    // $(api.column(5).footer()).html('$' + pageTotal + ' ( $' + total + ' total)');
                    $(api.column(5).footer()).html(total);
                },
                columns: [{
                        data: 'student_info.admission_no',
                        name: 'student_info.admission_no'
                    },
                    {
                        data: 'record_detail.roll_no',
                        name: 'record_detail.roll_no'
                    },
                    {
                        data: 'student_info.full_name',
                        name: 'student_info.full_name'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount'
                    },
                    {
                        data: 'weaver',
                        name: 'weaver'
                    },
                    {
                        data: 'fine',
                        name: 'fine'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
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
        });
    </script>
@endpush
