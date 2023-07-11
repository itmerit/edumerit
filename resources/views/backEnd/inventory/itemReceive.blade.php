 @extends('backEnd.master')
 @section('title')
     @lang('inventory.item_receive')
 @endsection
 @section('mainContent')
     <style type="text/css">
         #productTable tbody tr {
             border-bottom: 1px solid #FFFFFF !important;
         }
     </style>
     <section class="sms-breadcrumb mb-40 white-box">
         <div class="container-fluid">
             <div class="row justify-content-between">
                 <h1>@lang('inventory.item_receive')</h1>
                 <div class="bc-pages">
                     <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                     <a href="#">@lang('inventory.inventory')</a>
                     <a href="#">@lang('inventory.item_receive')</a>
                 </div>
             </div>
         </div>
     </section>
     <section class="admin-visitor-area">
         <div class="container-fluid p-0">
             @if (isset($editData))
                 {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['item-list-update', $editData->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
             @else
                 @if (userPermission('save-item-receive-data'))
                     {{ Form::open([
                         'class' => 'form-horizontal',
                         'files' => true,
                         'route' => 'save-item-receive-data',
                         'method' => 'POST',
                         'enctype' => 'multipart/form-data',
                         'id' => 'item-receive-form',
                     ]) }}
                 @endif
             @endif
             <div class="row">

                 <div class="col-lg-3">
                     <div class="row">
                         <div class="col-lg-12">
                             <div class="main-title">
                                 <h3 class="mb-30">
                                     @if (isset($editData))
                                         @lang('common.edit_receive_details')
                                     @else
                                         @lang('inventory.receive_details')
                                     @endif

                                 </h3>
                             </div>

                             <div class="white-box">
                                 <div class="add-visitor">
                                     <div class="row">
                                         <div class="col-lg-12 mb-15">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('inventory.expense_head') <span
                                                         class="text-danger"> *</span> </label>
                                                 <select
                                                     class="primary_select  form-control{{ $errors->has('expense_head_id') ? ' is-invalid' : '' }}"
                                                     name="expense_head_id" id="expense_head_id">
                                                     <option data-display="@lang('inventory.expense_head') *" value="">
                                                         @lang('common.select')</option>
                                                     @if (isset($expense_head))
                                                         @foreach ($expense_head as $key => $value)
                                                             <option value="{{ $value->id }}">{{ $value->head }}
                                                             </option>
                                                         @endforeach
                                                     @endif
                                                 </select>
                                                 <div class="text-danger" id="expenseError"></div>

                                                 @if ($errors->has('expense_head_id'))
                                                     <span class="text-danger invalid-select" role="alert">
                                                         {{ $errors->first('expense_head_id') }}
                                                     </span>
                                                 @endif
                                             </div>
                                         </div>
                                         <div class="col-lg-12 mb-15">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('inventory.payment_method') <span
                                                         class="text-danger"> *</span> </label>
                                                 <select
                                                     class="primary_select  form-control{{ $errors->has('payment_method') ? ' is-invalid' : '' }}"
                                                     name="payment_method"
                                                     id="item_receive_payment_method">
                                                     <option data-display="@lang('inventory.payment_method')*" value="">
                                                         @lang('inventory.payment_method')*</option>
                                                     @foreach ($paymentMethhods as $key => $value)
                                                         <option data-string="{{ $value->method }}"
                                                             value="{{ $value->id }}">{{ $value->method }}</option>
                                                     @endforeach
                                                 </select>
                                                 <div class="text-danger" id="paymentError"></div>

                                                 @if ($errors->has('payment_method'))
                                                     <span class="text-danger invalid-select" role="alert">
                                                         {{ $errors->first('payment_method') }}
                                                     </span>
                                                 @endif
                                             </div>
                                         </div>
                                         <div class="col-lg-12 mb-15 d-none" id="itemReceivebankAccount">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('inventory.bank')
                                                     <span></span> </label>
                                                 <select
                                                     class="primary_select  form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}"
                                                     name="bank_id" id="account_id">
                                                     @if (isset($account_id))
                                                         @foreach ($account_id as $key => $value)
                                                             <option value="{{ $value->id }}">
                                                                 {{ $value->account_name }} ({{ $value->bank_name }})
                                                             </option>
                                                         @endforeach
                                                     @endif
                                                 </select>

                                                 @if ($errors->has('bank_id'))
                                                     <span class="text-danger invalid-select" role="alert">
                                                         <strong>{{ $errors->first('bank_id') }}</strong>
                                                     </span>
                                                 @endif
                                             </div>
                                         </div>
                                         <div class="col-lg-12 mb-15">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('inventory.supplier') <span
                                                         class="text-danger"> *</span> </label>
                                                 <select
                                                     class="primary_select  form-control{{ $errors->has('supplier_id') ? ' is-invalid' : '' }}"
                                                     name="supplier_id" id="supplier_id">
                                                     <option data-display=" @lang('inventory.select_supplier') *" value="">
                                                         @lang('common.select')</option>
                                                     @if (isset($suppliers))
                                                         @foreach ($suppliers as $key => $value)
                                                             <option value="{{ $value->id }}"
                                                                 @if (isset($editData)) @if ($editData->category_name == $value->id)
                                                    @lang('inventory.selected') @endif
                                                                 @endif
                                                                 >{{ $value->company_name }}</option>
                                                         @endforeach
                                                     @endif
                                                 </select>
                                                 <div class="text-danger" id="supplierError"></div>

                                                 @if ($errors->has('supplier_id'))
                                                     <span class="text-danger invalid-select" role="alert">
                                                         {{ $errors->first('supplier_id') }}
                                                     </span>
                                                 @endif
                                             </div>
                                         </div>

                                         <div class="col-lg-12 mb-15">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('inventory.store_warehouse') <span
                                                         class="text-danger"> *</span> </label>
                                                 <select
                                                     class="primary_select  form-control{{ $errors->has('store_id') ? ' is-invalid' : '' }}"
                                                     name="store_id" id="store_id">
                                                     <option data-display="@lang('inventory.select_store_warehouse') *" value="">
                                                         @lang('common.select')</option>
                                                     @if (isset($itemStores))
                                                         @foreach ($itemStores as $key => $value)
                                                             <option value="{{ $value->id }}"
                                                                 @if (isset($editData)) @if ($editData->category_name == $value->id)
                                                        @lang('inventory.selected') @endif
                                                                 @endif
                                                                 >{{ $value->store_name }}</option>
                                                         @endforeach
                                                     @endif
                                                 </select>
                                                 <div class="text-danger" id="storeError"></div>

                                                 @if ($errors->has('store_id'))
                                                     <span class="text-danger invalid-select" role="alert">
                                                         {{ $errors->first('store_id') }}
                                                     </span>
                                                 @endif
                                             </div>
                                         </div>

                                         <div class="col-lg-12 mb-15">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('inventory.reference_no')
                                                     <span></span> </label>
                                                 <input
                                                     class="primary_input_field form-control{{ $errors->has('reference_no') ? ' is-invalid' : '' }}"
                                                     type="text" name="reference_no" autocomplete="off"
                                                     value="{{ isset($editData) ? $editData->reference_no : '' }}">


                                                 @if ($errors->has('reference_no'))
                                                     <span class="text-danger">
                                                         {{ $errors->first('reference_no') }}
                                                     </span>
                                                 @endif
                                             </div>
                                         </div>

                                         <div class="col-lg-12 mb-15">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('inventory.receive_date')
                                                     <span></span> </label>
                                                 <div class="primary_datepicker_input">
                                                     <div class="no-gutters input-right-icon">
                                                         <div class="col">
                                                             <div class="">
                                                                 <input
                                                                     class="primary_input_field primary_input_field date form-control"
                                                                     id="receive_date" type="text"
                                                                     name="receive_date"
                                                                     value="{{ isset($editData) ? date('m/d/Y', strtotime($editData->receive_date)) : date('m/d/Y') }}"
                                                                     autocomplete="off">
                                                             </div>
                                                         </div>
                                                         <button class="btn-date" data-id="#receive_date" type="button">
                                                             <label class="m-0 p-0" for="receive_date">
                                                                 <i class="ti-calendar" id="start-date-icon"></i>
                                                             </label>
                                                         </button>
                                                     </div>
                                                 </div>
                                                 <span class="text-danger">{{ $errors->first('receive_date') }}</span>
                                             </div>
                                         </div>

                                         <div class="col-lg-12 mb-20">
                                             <div class="primary_input">
                                                 <label class="primary_input_label" for="">@lang('common.description')
                                                     <span></span> </label>
                                                 <textarea class="primary_input_field form-control" cols="0" rows="4" name="description"
                                                     id="description">{{ isset($editData) ? $editData->description : '' }}</textarea>

                                             </div>
                                         </div>
                                     </div>

                                 </div>
                             </div>

                         </div>
                     </div>
                 </div>

                 <div class="col-lg-9">
                     <div class="row xm_3">
                         <div class="col-lg-4 no-gutters col-7 xs_mt_0">
                             <div class="main-title">
                                 <h3 class="mb-30">@lang('inventory.item_receive')</h3>
                             </div>
                         </div>

                         <div class="offset-lg-6 col-lg-2 text-right col-md-6 col-5">
                             <button type="button" class="primary-btn small fix-gr-bg" onclick="addRow();"
                                 id="addRowBtn">
                                 <span class="ti-plus pr-2"></span>
                                 @lang('common.add')
                             </button>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-lg-12">
                             <div class="white-box">
                                 <div class="alert alert-danger" id="errorMessage2">
                                     <div id="itemError"></div>
                                     <div id="priceError"></div>
                                     <div id="quantityError"></div>
                                 </div>
                                 <table class="table" id="productTable">
                                     <thead>
                                         <tr>
                                             <th> @lang('inventory.product_name')* </th>
                                             <th> @lang('inventory.unit_price')* </th>
                                             <th> @lang('inventory.quantity')* </th>
                                             <th>@lang('inventory.sub_total')</th>
                                             <th>@lang('common.action')</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <tr id="row1" class="0">
                                             <td class="border-top-0">
                                                 <input type="hidden" name="url" id="url"
                                                     value="{{ URL::to('/') }}">
                                                 <div class="primary_input">
                                                     <select
                                                         class="primary_select  form-control{{ $errors->has('category_name') ? ' is-invalid' : '' }}"
                                                         name="item_id[]" id="productName1">
                                                         <option data-display="@lang('common.select_item')*" value="">
                                                             @lang('common.select')*</option>
                                                         @foreach ($items as $key => $value)
                                                             <option value="{{ $value->id }}"
                                                                 @if (isset($editData)) @if ($editData->category_name == $value->id)
                                            @lang('inventory.selected') @endif
                                                                 @endif
                                                                 >{{ $value->item_name }}</option>
                                                         @endforeach
                                                     </select>

                                                     @if ($errors->has('item_id'))
                                                         <span class="text-danger invalid-select" role="alert">
                                                             {{ $errors->first('item_id') }}
                                                         </span>
                                                     @endif
                                                 </div>
                                             </td>
                                             <td class="border-top-0">
                                                 <div class="primary_input">
                                                     <input oninput="numberCheckWithDot(this)"
                                                         class="primary_input_field form-control{{ $errors->has('unit_price') ? ' is-invalid' : '' }}"
                                                         type="text" step="0.1" id="unit_price1"
                                                         name="unit_price[]" autocomplete="off"
                                                         value="{{ isset($editData) ? $editData->unit_price : '' }}"
                                                         onkeyup="getTotalByPrice(1)">


                                                     @if ($errors->has('unit_price'))
                                                         <span class="text-danger">
                                                             {{ $errors->first('unit_price') }}
                                                         </span>
                                                     @endif
                                                 </div>
                                             </td>
                                             <td class="border-top-0">
                                                 <div class="primary_input">
                                                     <input oninput="numberCheckWithDot(this)"
                                                         class="primary_input_field form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                                         type="text" id="quantity1" name="quantity[]"
                                                         autocomplete="off" onkeyup="getTotal(1);"
                                                         value="{{ isset($editData) ? $editData->quantity : '' }}">


                                                     @if ($errors->has('quantity'))
                                                         <span class="text-danger">
                                                             {{ $errors->first('quantity') }}
                                                         </span>
                                                     @endif
                                                 </div>
                                             </td>
                                             <td class="border-top-0">
                                                 <div class="primary_input">
                                                     <input oninput="numberCheckWithDot(this)"
                                                         class="primary_input_field form-control{{ $errors->has('sub_total') ? ' is-invalid' : '' }}"
                                                         type="text" name="total[]" id="total1" autocomplete="off"
                                                         value="{{ isset($editData) ? $editData->sub_total : '0.00' }}">


                                                     @if ($errors->has('sub_total'))
                                                         <span class="text-danger">
                                                             {{ $errors->first('sub_total') }}
                                                         </span>
                                                     @endif
                                                 </div>
                                                 <input type="hidden" name="totalValue[]" id="totalValue1"
                                                     autocomplete="off" class="form-control" />
                                             </td>
                                             <td>
                                                 <button class="primary-btn icon-only fix-gr-bg" type="button">
                                                     <span class="ti-trash"></span>
                                                 </button>

                                             </td>
                                         </tr>
                                     <tfoot>
                                         <tr>
                                             <th class="border-top-0" colspan="2">@lang('inventory.total')</th>
                                             <th class="border-top-0">
                                                 <input type="text" class="primary_input_field form-control"
                                                     readonly="" id="subTotalQuantity" name="subTotalQuantity"
                                                     placeholder="0.00" />

                                                 <input type="hidden" class="form-control" id="subTotalQuantityValue"
                                                     name="subTotalQuantityValue" />

                                             </th>

                                             <th class="border-top-0">
                                                 <input type="text" class="primary_input_field form-control"
                                                     id="subTotal" name="subTotal" placeholder="0.00" readonly="" />

                                                 <input type="hidden" class="form-control" id="subTotalValue"
                                                     name="subTotalValue" />

                                             </th>
                                             <th class="border-top-0"></th>
                                         </tr>
                                     </tfoot>

                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     <div class="row mt-30">
                         <div class="col-lg-12">
                             <div class="white-box">

                                 <div class="row">
                                     <div class="col-lg-4 mt-30-md">
                                         <div class="col-lg-12 mt-30">
                                             <div class="primary_input">
                                                 <input type="checkbox" id="full_paid"
                                                     class="common-checkbox form-control{{ $errors->has('full_paid') ? ' is-invalid' : '' }}"
                                                     name="full_paid" value="1">
                                                 <label for="full_paid">@lang('inventory.full_paid')</label>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-lg-4 mt-30-md">
                                         <div class="col-lg-12">
                                             <div class="primary_input md_mb_20">
                                                 <label class="primary_input_label"
                                                     for="">@lang('inventory.total_paid')</label>
                                                 <input class="primary_input_field" type="number" step="0.1"
                                                     value="0" name="totalPaid" id="totalPaid"
                                                     onkeyup="paidAmount();">
                                                 <input type="hidden" id="totalPaidValue" name="totalPaidValue">


                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-lg-4 mt-30-md">
                                         <div class="col-lg-12">
                                             <div class="primary_input md_mb_20">
                                                 <label class="primary_input_label"
                                                     for="">@lang('inventory.total_due')</label>
                                                 <input class="primary_input_field" type="text" value="0.00"
                                                     id="totalDue" readonly>
                                                 <input type="hidden" id="totalDueValue" name="totalDueValue">


                                             </div>
                                         </div>
                                     </div>
                                     @php
                                         $tooltip = '';
                                         if (userPermission('save-item-receive-data')) {
                                             $tooltip = '';
                                         } else {
                                             $tooltip = 'You have no permission to add';
                                         }
                                     @endphp
                                     <div class="col-lg-12 mt-20 text-center">
                                         <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                             title="{{ $tooltip }}">
                                             <span class="ti-check"></span>
                                             @lang('inventory.receive')
                                         </button>
                                     </div>
                                 </div>


                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             {{ Form::close() }}
         </div>
     </section>
 @endsection
 @include('backEnd.partials.date_picker_css_js')
