@extends('backEnd.master')
@section('title') 
@lang('accounts.add_income')
@endsection
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('accounts.add_income') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('accounts.accounts')</a>
                <a href="#">@lang('accounts.add_income')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($add_income))
        @if(userPermission("add_income_store"))

        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('add_income')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($add_income))
                                    @lang('accounts.edit_income')
                                @else
                                    @lang('accounts.add_income')
                                @endif
                                
                            </h3>
                        </div>
                        @if(isset($add_income))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'add_income_update',
                        'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'add-income-update']) }}
                        @else
                         @if(userPermission("add_income_store"))

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'add_income_store',
                        'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'add-income']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off" value="{{isset($add_income)? $add_income->name: old('name')}}">
                                            <input type="hidden" name="id" value="{{isset($add_income)? $add_income->id: ''}}">
                                            
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('accounts.a_c_Head') <span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ @$errors->has('income_head') ? ' is-invalid' : '' }}" name="income_head">
                                            <option data-display="@lang('accounts.a_c_Head') *" value="">@lang('accounts.a_c_Head') *</option>
                                            @foreach($income_heads as $income_head)
                                                @if(isset($add_income))
                                                <option value="{{@$income_head->id}}"
                                                    {{@$add_income->income_head_id == @$income_head->id? 'selected': ''}}>{{@$income_head->head}}</option>
                                                @else
                                                <option value="{{@$income_head->id}}" {{old('income_head') == @$income_head->id? 'selected' : ''}}>{{@$income_head->head}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if (@$errors->has('income_head'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('income_head') }}
                                        </span>
                                        @endif 
                                    </div>
                                </div>
                                
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('accounts.payment_method') <span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ @$errors->has('payment_method') ? ' is-invalid' : '' }}" name="payment_method" id="payment_method">
                                            <option data-display="@lang('accounts.payment_method') *" value="">@lang('accounts.payment_method') *</option>
                                            @foreach($payment_methods as $payment_method)
                                            @if(isset($add_income))
                                            <option data-string="{{$payment_method->method}}" value="{{@$payment_method->id}}"{{@$add_income->payment_method_id == @$payment_method->id? 'selected': ''}}>
                                                {{@$payment_method->method}}
                                            </option>
                                            @else
                                            <option data-string="{{$payment_method->method}}" value="{{@$payment_method->id}}">{{@$payment_method->method}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @if (@$errors->has('payment_method'))
                                        <span class="text-danger invalid-select" role="alert">
                                           {{ @$errors->first('payment_method') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-15 d-none" id="bankAccount">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('accounts.bank_accounts') <span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ @$errors->has('accounts') ? ' is-invalid' : '' }}" name="accounts">
                                            <option data-display="@lang('accounts.bank_accounts') *" value="">@lang('accounts.bank_accounts') *</option>
                                            @foreach($bank_accounts as $bank_account)
                                            @if(isset($add_income))
                                            <option value="{{@$bank_account->id}}"
                                                {{@$add_income->account_id == @$bank_account->id? 'selected': ''}}>{{@$bank_account->account_name}} ({{@$bank_account->bank_name}})</option>
                                            @else
                                            <option value="{{@$bank_account->id}}">{{@$bank_account->account_name}} ({{@$bank_account->bank_name}})</option>
                                            @endif
                                            @endforeach
                                        </select>
                                         @if ($errors->has('accounts'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('accounts') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('admin.date')<span></span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ @$errors->has('date') ? ' is-invalid' : '' }}"
                                                id="startDate" type="text" placeholder="@lang('common.date') *" name="date" value="{{isset($add_income)? date('m/d/Y', strtotime($add_income->date)): date('m/d/Y')}}">
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#startDate" type="button">
                                                        <label class="m-0 p-0" for="startDate">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('date') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.amount') ({{generalSetting()->currency_symbol}}) <span class="text-danger"> *</span></label>
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control{{ @$errors->has('amount') ? ' is-invalid' : '' }}"
                                                type="text" step="0.1" name="amount" value="{{isset($add_income)? $add_income->amount: old('amount')}}">
                                        
                                            
                                            @if ($errors->has('amount'))
                                            <span class="text-danger" >
                                                {{ @$errors->first('amount') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">                                     
                                    <div class="col-lg-12 mt-15">
                                        <div class="primary_input">
                                            <div class="primary_file_uploader">
                                                <input class="primary_input_field" type="text" id="placeholderInput" placeholder="{{isset($add_income)? ($add_income->file != ""? getFilePath3($add_income->file):trans('common.file')):trans('common.file') }}" readonly
                                                        >
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="browseFile">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="file" id="browseFile">
                                                </button>
                                            </div>
                                        </div>
                                        <code>(PDF,DOC,DOCX,JPG,JPEG,PNG,TXT are allowed for upload)</code>
                                        @if ($errors->has('file'))
                                        <span class="text-danger d-block">
                                            {{ $errors->first('file') }}
                                        </span>
                                        @endif

                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description') <span></span></label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="4" name="description">{{isset($add_income)? $add_income->description: old('description')}}</textarea>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                 				@php 
                                  $tooltip = "";
                                  if(userPermission("add_income_store") || userPermission("add_income_edit")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if (@$add_income)
                                                 @lang('accounts.update_income')
                                            @else                                               
                                                @lang('accounts.save_income')
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
                            <h3 class="mb-0">@lang('accounts.income_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>si</th>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('accounts.payment_method')</th>
                                    <th>@lang('common.date')</th>
                                    <th>
                                        @lang('accounts.a_c_Head') 
                                    </th>
                                    <th>@lang('accounts.amount')</th>
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


 {{-- delete income modal here  --}}

 <div class="modal fade admin-query" id="deleteIncomeModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('common.delete_income')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                    {{ Form::open(['route' => 'add_income_delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="id" value="">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')

@include('backEnd.partials.date_picker_css_js')

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
   $('.data-table').DataTable({
                 processing: true,
                 serverSide: true,
                 "ajax": $.fn.dataTable.pipeline( {
                       url: "{{route('ajaxIncomeList')}}",
                       data: { 
                            un_semester_label_id: $('#un_semester_label_id').val(), 
                            class: $('#class').val(), 
                            section: $('#section').val(), 
                            payment_date: $('#p_date').val(), 
                            approve_status: $('#status').val()
                        },
                       pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                       
                   } ),
                   columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'payment_method.method', name: 'payment_method.method'},
                        {data: 'date', name: 'date'},
                        {data: 'a_c_head.head', name: 'head'},
                        {data: 'amount', name: 'amount'},
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
        } );



        function deleteIncome(id){
            var modal = $('#deleteIncomeModal');
            modal.find('input[name=id]').val(id);
            modal.modal('show');
        }
</script>
@endpush