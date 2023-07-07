@extends('backEnd.master')
@section('title') 
@lang('accounts.bank_account')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('accounts.bank_account')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('accounts.accounts')</a>
                <a href="#">@lang('accounts.bank_account')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($bank_account))
            @if(userPermission("bank-account-store"))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('bank-account')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
                    </div>
                </div>
            @endif
        @endif
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($bank_account))
                                    @lang('accounts.edit_bank_account')
                                @else
                                    @lang('accounts.add_bank_account')
                                @endif
                            </h3>
                        </div>
                        @if(isset($bank_account))
                            {{ Form::open(['class' => 'form-horizontal', 'route' => array('bank-account-update',@$bank_account->id), 'method' => 'PUT']) }}
                        @else
                            @if(userPermission("bank-account-store"))
                                {{ Form::open(['class' => 'form-horizontal', 'route' => 'bank-account', 'method' => 'POST']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.bank_name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('bank_name') ? ' is-invalid' : '' }}" type="text" name="bank_name" autocomplete="off" value="{{isset($bank_account)? $bank_account->bank_name : old('bank_name')}}">
                                           
                                            
                                            @if ($errors->has('bank_name'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('bank_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.account_name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('account_name') ? ' is-invalid' : '' }}" type="text" name="account_name" autocomplete="off" value="{{isset($bank_account)? $bank_account->account_name:old('account_name')}}">
                                            <input type="hidden" name="id" value="{{isset($add_income)? $add_income->id: ''}}">
                                            
                                            
                                            @if ($errors->has('account_name'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('account_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.account_number') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('account_number') ? ' is-invalid' : '' }}" type="tel" name="account_number" autocomplete="off" value="{{isset($bank_account)? $bank_account->account_number:old('account_number')}}">
                                           
                                            
                                            @if ($errors->has('account_number'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('account_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.account_type')</label>
                                            <input class="primary_input_field form-control{{ @$errors->has('account_type') ? ' is-invalid' : '' }}" type="text" name="account_type" autocomplete="off" value="{{isset($bank_account)? $bank_account->account_type:old('account_type')}}">
                                          
                                            
                                            @if ($errors->has('account_type'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('account_type') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.opening_balance')<span class="text-danger"> *</span></label>
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control{{ @$errors->has('opening_balance') ? ' is-invalid' : '' }}" type="text" step="0.1" name="opening_balance" autocomplete="off" value="{{isset($bank_account)? $bank_account->opening_balance:old('opening_balance')}}">
                                            <input type="hidden" name="id" value="{{isset($bank_account)? $bank_account->id: ''}}">
                                           
                                            
                                            @if ($errors->has('opening_balance'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('opening_balance') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.note') <span></span></label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="4" name="note">{{isset($bank_account)? $bank_account->note: old('note')}}</textarea>
                                           
                                            
                                        </div>
                                    </div>
                                </div>

                            	@php 
                                  $tooltip = "";
                                  if(userPermission("bank-account-store")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp

                                <div class="row mt-25">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($bank_account))
                                                @lang('accounts.update_account')
                                            @else
                                                @lang('accounts.save_account')
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
                            <h3 class="mb-0">@lang('accounts.bank_account_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('accounts.bank_name')</th>
                                    <th>@lang('accounts.account_name')</th>
                                    <th>@lang('accounts.opening_balance')</th>
                                    <th>@lang('accounts.current_balance')</th>
                                    <th>@lang('common.note')</th>
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
{{-- Start Delete Modal --}}
    <div class="modal fade admin-query" id="deleteBankAccountModal" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('accounts.delete_bank_account')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        {{ Form::open(['route' => 'bank-account-delete', 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="">
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Start Delete Modal --}}
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@section('script')
    <script>
        function deleteBankModal(id) {
            var modal = $('#deleteBankAccountModal');
            modal.find('input[name=id]').val(id)
            modal.modal('show');
        }

        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{url('bank-account-datatable')}}",
                    data: {},
                    pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                } ),
                columns: [            
                    {data: 'bank_name', name: 'bank_name'},
                    {data: 'account_name', name: 'account_name'},
                    {data: 'opening_balance', name: 'opening_balance'},
                    {data: 'current_balance', name: 'current_balance'},
                    {data: 'note', name: 'note'},
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