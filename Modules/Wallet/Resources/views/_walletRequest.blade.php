
<style>
    table.dataTable tfoot th, table.dataTable tfoot td.walletAmount{
        padding: 20px 10px 20px 30px !important;
    }
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>
                @if (isset($status) && $status =='pending')
                    @lang('common.pending') 
                @elseif (isset($status) && $status =='approve')
                    @lang('wallet::wallet.approve_deposit')
                @else
                    @lang('wallet::wallet.reject_deposit')
                @endif
               
            </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('wallet::wallet.wallet')</a>
                <a href="#">
                    @if (isset($status) && $status =='pending')
                        @lang('common.pending') 
                    @elseif (isset($status) && $status =='approve')
                        @lang('wallet::wallet.approve_deposit')
                    @else
                        @lang('wallet::wallet.reject_deposit') 
                    @endif
                   
                </a>
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
                                <th>@lang('wallet::wallet.amount')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('wallet::wallet.note')</th>
                                @if (isset($status) && $status =='reject')
                                    <th>@lang('wallet::wallet.reject_note')</th>
                                @endif
                                <th>@lang('common.file')</th>
                                <th>@lang('common.date')</th>
                                @if (isset($status) && $status =='approve')
                                    <th>@lang('wallet::wallet.approve_date')</th>
                                @elseif (isset($status) && $status =='reject')
                                    <th>@lang('wallet::wallet.reject_date')</th>
                                @elseif (isset($status) && $status =='pending')
                                    <th>@lang('common.action')</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="walletAmount"></td>
                                <td class="walletAmount"></td>
                                <td class="walletAmount">@lang('exam.result')</td>
                                <td class="walletAmount">{{currency_format($walletTotalAmounts)}}</td>
                                <td class="walletAmount"></td>
                                <td class="walletAmount"></td>
                                <td class="walletAmount"></td>
                                <td class="walletAmount"></td>
                                <td class="walletAmount"></td>
                                @if (isset($status) && $status =='reject')
                                    <td class="walletAmount"></td>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </x-table>
            </div>
        </div>
    </div>
</section>
{{-- Note Start  --}}
    <div class="modal fade admin-query" id="noteModal">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.view_note')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-0 mt-30">
                    <div class="container student-certificate">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 text-center">
                                <input type="hidden" name="noteId" value="">
                                <p name="note"></p>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Note End  --}}

{{-- Reject Note Start  --}}
    <div class="modal fade admin-query" id="rejectNote">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.view_reject_note')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-0 mt-30">
                    <div class="container student-certificate">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 text-center">
                                <input type="hidden" name="rejectNoteId" value="">
                                <p name="note"></p>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Reject Note End  --}}

{{-- File View and Download Modal Start  --}}
    <div class="modal fade admin-query" id="showFile">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.view_file')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-0 mt-30">
                    <div class="container student-certificate">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 text-center">
                                <input type="hidden" name="fileId" value="">
                                    <div class="mb-5">
                                        <img class="img-fluid" src="">
                                    </div>
                                    <br>
                                    <div class="mb-5">
                                        @if (isset($status) && $status =='approve')
                                        @if(userPermission("wallet.approve-diposit"))
                                                <a class="file" href="" download>@lang('common.download') <span class="pl ti-download"></span></a>
                                            @endif
                                        @endif
                                        @if (isset($status) && $status =='reject')
                                            @if(userPermission("wallet.download"))
                                                <a class="file" href="" download>@lang('common.download') <span class="pl ti-download"></span></a>
                                            @endif
                                        @endif
                                        @if (isset($status) && $status =='pending')
                                            @if(userPermission("wallet.download"))
                                                <a class="file" href="" download>@lang('common.download') <span class="pl ti-download"></span></a>
                                            @endif
                                        @endif
                                    </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- File View and Download Modal End  --}}

{{-- Status Modal Start  --}}
    @if (isset($status) && $status =='pending')
        {{-- Approve Modal Start --}}
        <div class="modal fade admin-query" id="approvePayment">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('wallet::wallet.approve') @lang('wallet::wallet.payment')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center">
                            <h4>@lang('wallet::wallet.are_you_sure_to_approve')</h4>
                        </div>

                        <div class="mt-40 d-flex justify-content-between">
                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                            {{ Form::open(['method' => 'POST','route' =>'wallet.approve-payment']) }}
                                <input type="hidden" name="approveId" value="">
                                <input type="hidden" name="user_id" value="">
                                <input type="hidden" name="amount" value="">
                                <button class="primary-btn fix-gr-bg" type="submit">@lang('wallet::wallet.approve')</button>
                            {{ Form::close() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- Approve Modal End --}}
        {{-- Reject Modal Start --}}
        <div class="modal fade admin-query" id="rejectwalletPayment" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('wallet::wallet.reject') @lang('wallet::wallet.payment')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                                <h4>@lang('wallet::wallet.are_you_sure_to_reject')</h4>
                            </div>
                        {{ Form::open(['route' => 'wallet.reject-payment', 'method' => 'POST']) }}
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="user_id" value="">
                                <input type="hidden" name="amount" value="">
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
        {{-- Reject Modal End --}}
    @endif
{{-- Status Modal End  --}}
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@section('script')
    <script>
        function noteModal(id) {
            var note = $('.walletNote'+ id).data('note');
            var modal = $('#noteModal');
            modal.find('input[name=noteId]').val(id)
            modal.find('p[name=note]').text(note)
            modal.modal('show');
        }

        function rejectNote(id) {
            var note = $('.rejectNote'+ id).data('rejectnote');
            var modal = $('#rejectNote');
            modal.find('input[name=rejectNoteId]').val(id)
            modal.find('p[name=note]').text(note)
            modal.modal('show');
        }

        function showFile(id) {
            var file = $('.showFile'+ id).data('file');
            var asset = $('.asset'+ id).data('asset');
            var modal = $('#showFile');
            modal.find('input[name=fileId]').val(id)
            modal.find('a[class=file]').attr("href", file)
            modal.find('img[class=img-fluid]').attr("src", asset)
            modal.modal('show');
        }

        function approvePayment(id) {
            var user = $('.approvePayment'+ id).data('user');
            var amount = $('.amount'+ id).data('amount');
            var modal = $('#approvePayment');
            modal.find('input[name=approveId]').val(id)
            modal.find('input[name=user_id]').val(user)
            modal.find('input[name=amount]').val(amount)
            modal.modal('show');
        }

        function rejectwalletPayment(id) {
            var user = $('.rejectwalletPayment'+ id).data('user');
            var amount = $('.amount'+ id).data('amount');
            var modal = $('#rejectwalletPayment');
            modal.find('input[name=approveId]').val(id)
            modal.find('input[name=user_id]').val(user)
            modal.find('input[name=amount]').val(amount)
            modal.modal('show');
        }

        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{url('wallet/wallet-diposit-datatable')}}",
                    data: {
                        'status' : '{{ @$status }}'
                    },
                    pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                } ),
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},  
                    {data: 'user_name.full_name', name: 'user_name.full_name'},
                    {data: 'payment_method', name: 'payment_method'},
                    {data: 'amount', name: 'amount'},
                    {data: 'walletStatus', name: 'walletStatus'},
                    {data: 'walletNote', name: 'walletNote'},
                    @if(isset($status) && $status =='reject')
                    {data: 'walletRejectNote', name: 'walletRejectNote'},
                    @endif
                    {data: 'showFile', name: 'showFile'},
                    {data: 'createdDate', name: 'createdDate'},
                    @if (isset($status) && $status =='approve')
                    {data: 'updatedDate', name: 'updatedDate'},
                    @elseif (isset($status) && $status !='pending')
                    {data: 'updatedDate', name: 'updatedDate'},
                    @elseif (isset($status) && $status =='pending')
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    @endif
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