@extends('backEnd.master')
    @section('title') 
        @lang('wallet::wallet.wallet_transaction')
    @endsection
@section('mainContent')
@push('css')
    <style>
        table.dataTable tfoot th, table.dataTable tfoot td.walletTranscation{
            padding: 20px 10px 20px 30px !important;
        }
    </style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('wallet::wallet.wallet_transaction')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('wallet::wallet.wallet')</a>
                <a href="#">@lang('wallet::wallet.wallet_transaction')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor mt-20">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <x-table>
                    <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('common.sl')</th>
                                <th>@lang('common.name')</th>
                                <th>@lang('wallet::wallet.method')</th>
                                <th>@lang('common.pending')</th>
                                <th>@lang('wallet::wallet.approve')</th>
                                <th>@lang('wallet::wallet.reject')</th>
                                <th>@lang('wallet::wallet.refund')</th>
                                <th>@lang('accounts.expense')</th>
                                <th>@lang('fees::feesModule.fees_refund')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('common.date')</th>
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
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@push('script')
<script type="text/javascript">
    $(document).ready(function() {
     
   $('.data-table').DataTable({
                 processing: true,
                 serverSide: true,
                 "ajax": $.fn.dataTable.pipeline( {
                       url: "{{route('wallet.wallet-transaction-ajax')}}",
                       data: { 
                           
                        },
                       pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                       
                   } ),
                   columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'user_name.full_name', name: 'user_name.full_name'},
                        {data: 'payment_method', name: 'payment_method'},
                        {data: 'pending_amount', name: 'pending_amount'},
                        {data: 'approve_amount', name: 'approve_amount'},
                        {data: 'reject_amount', name: 'reject_amount'},
                        {data: 'refund_amount', name: 'refund_amount'},
                        {data: 'expense_amount', name: 'expense_amount'},
                        {data: 'fees_refund_amount', name: 'fees_refund_amount'},
                        {data: 't_status', name: 't_status'},
                        {data: 'date', name: 'date',orderable: false, searchable: true},
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



        function deleteExpense(id){
            var modal = $('#deleteExpenseModal');
            modal.find('input[name=id]').val(id);
            modal.modal('show');
        }
</script>
@endpush