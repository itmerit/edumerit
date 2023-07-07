@extends('backEnd.master')
    @section('title') 
        @lang('wallet::wallet.refund_request')
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
            <h1>@lang('wallet::wallet.refund_request')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('wallet::wallet.wallet')</a>
                <a href="#">@lang('wallet::wallet.refund_request')</a>
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
                                <th>@lang('common.note')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('common.file')</th>
                                <th>@lang('wallet::wallet.create_date')</th>
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


    {{-- Approve Modal Start --}}
    <div class="modal fade admin-query" id="approveRefundModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('wallet::wallet.approve_refund')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('wallet::wallet.are_you_sure_to_approve')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        {{ Form::open(['method' => 'POST','route' =>'wallet.approve-refund']) }}
                            <input type="hidden" name="id" value="">
                            <button class="primary-btn fix-gr-bg" type="submit">@lang('wallet::wallet.approve')</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Approve Modal End --}}

    <div class="modal fade admin-query" id="rejectRefundModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('wallet::wallet.reject_refund')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                            <h4>@lang('wallet::wallet.are_you_sure_to_reject')</h4>
                        </div>
                    {{ Form::open(['route' => 'wallet.reject-refund', 'method' => 'POST']) }}
                            <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label><strong>@lang('wallet::wallet.reject_note')</strong></label>
                            <textarea name="reject_note" class="form-control" rows="6"></textarea>
                        </div>
        
                        <div class="mt-40 d-flex justify-content-between">
                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.close')</button>
                            <button class="primary-btn fix-gr-bg" type="submit">@lang('common.submit')</button>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade admin-query" id="refundNoteModal" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('wallet::wallet.view_bank_payment_reject_note')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>@lang('wallet::wallet.reject_note')</strong></label>
                        <textarea readonly id="noteArea" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">Close</button>
                    </div>
                </div>
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
                       url: "{{route('wallet.wallet-refund-request-ajax')}}",
                       data: { 
                           
                        },
                       pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                       
                   } ),
                   columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'user_name.full_name', name: 'user_name.full_name'},
                        {data: 'payment_method', name: 'payment_method'},
                        {data: 'pending_refund', name: 'pending_refund'},
                        {data: 'approve_refund', name: 'approve_refund'},
                        {data: 'reject_refund', name: 'reject_refund'},
                        {data: 'refund_note', name: 'refund_note'},
                        {data: 't_status', name: 't_status'},
                        {data: 'file_view', name: 'file_view'},
                        {data: 'date', name: 'date'},
                        {data: 'action', name: 'action',orderable: false, searchable: true},
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

        function approveRefund(id){
            var modal = $('#approveRefundModal');
            modal.find('input[name=id]').val(id);
            modal.modal('show');
        }
        function refundNote(id){
            var modal = $('#refundNoteModal');
            var note = $('.note_'+ id).data('note');
            modal.find('textarea').val(note)
            modal.modal('show');
        }

        function rejectRefund(id){
            var modal = $('#rejectRefundModal');
            modal.find('input[name=id]').val(id);
            modal.modal('show');
        }
</script>
@endpush