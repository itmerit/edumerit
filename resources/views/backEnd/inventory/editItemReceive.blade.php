@extends('backEnd.master')
@section('title')
    @lang('common.edit_receive_details')
@endsection
@section('mainContent')
    <style type="text/css">
        #productTable tbody tr {
            border-bottom: 1px solid #FFFFFF !important;
        }

        .ti-calendar:before {
            position: relative !important;
            bottom: 47px !important;
            left: 312px !important;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.edit_receive_details')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('inventory.inventory')</a>
                    <a href="{{ route('item-receive-list') }}">@lang('inventory.item_receive_list')</a>
                    <a href="#">@lang('common.edit_receive_details')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            @if (isset($editData))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['update-edit-item-receive-data', $editData->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'item-receive-form']) }}
            @else
                {{ Form::open([
                    'class' => 'form-horizontal',
                    'files' => true,
                    'route' => 'save-item-receive-data',
                    'method' => 'POST',
                    'enctype' => 'multipart/form-data',
                ]) }}
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
                                        <div class="col-lg-12 mb-30">
                                            <div class="primary_input">
                                                <select
                                                    class="primary_select  form-control{{ $errors->has('expense_head_id') ? ' is-invalid' : '' }}"
                                                    name="expense_head_id" id="expense_head_id">
                                                    <option data-display="@lang('accounts.expense_head') *" value="">
                                                        @lang('common.select')</option>
                                                    @if (isset($expense_head))
                                                        @foreach ($expense_head as $key => $value)
                                                            <option value="{{ $value->id }}"
                                                                @if (isset($editData)) @if ($editData->expense_head_id == $value->id)
                                                    @lang('inventory.selected') @endif
                                                                @endif
                                                                >{{ $value->head }}</option>
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
                                        <div class="col-lg-12 mb-30">
                                            <div class="primary_input">
                                                <select class="primary_select  form-control" name="payment_method"
                                                    id="edit_payment_method">
                                                    @if (@$editData->paymentMethodName->method == 'Bank')
                                                        <option data-string="{{ @$editData->paymentMethodName->method }}"
                                                            value="{{ @$editData->payment_method }}" selected>
                                                            {{ @$editData->paymentMethodName->method }}</option>
                                                    @else
                                                        @foreach ($paymentMethhods as $key => $value)
                                                            @if (isset($editData))
                                                                <option data-string="{{ $value->method }}"
                                                                    value="{{ $value->id }}"
                                                                    {{ @$editData->payment_method == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->method }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="text-danger" id="paymentError"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-30 d-none" id="edit_item_receive_bankAccount">
                                            <div class="primary_input">
                                                <select
                                                    class="primary_select  form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}"
                                                    name="bank_id" id="account_id">
                                                    @if (isset($editData))
                                                        <option value="{{ $editData->account_id }}" selected>
                                                            {{ @$editData->bankName->account_name }}
                                                            ({{ @$editData->bankName->bank_name }})</option>
                                                    @endif
                                                </select>

                                                @if ($errors->has('bank_id'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('bank_id') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-20">
                                            <div class="primary_input">
                                                <select
                                                    class="primary_select  form-control{{ $errors->has('supplier_id') ? ' is-invalid' : '' }}"
                                                    name="supplier_id" id="supplier_id">
                                                    <option data-display="@lang('common.select_supplier') *" value="">
                                                        @lang('common.select')</option>
                                                    @if (isset($suppliers))
                                                        @foreach ($suppliers as $key => $value)
                                                            <option value="{{ $value->id }}"
                                                                @if (isset($editData)) @if ($editData->supplier_id == $value->id)
                                                    selected @endif
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

                                        <div class="col-lg-12 mb-20">
                                            <div class="primary_input">
                                                <select
                                                    class="primary_select  form-control{{ $errors->has('store_id') ? ' is-invalid' : '' }}"
                                                    name="store_id" id="store_id">
                                                    <option
                                                        data-display="@lang('common.select_store')/@lang('inventory.warehouse') *"
                                                        value="">@lang('common.select')</option>
                                                    @if (isset($itemStores))
                                                        @foreach ($itemStores as $key => $value)
                                                            <option value="{{ $value->id }}"
                                                                @if (isset($editData)) @if ($editData->store_id == $value->id)
                                                    selected @endif
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

                                        <div class="col-lg-12 mb-20">
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

                                        <div class="col-lg-12 no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <label class="primary_input_label"
                                                        for="">@lang('inventory.receive_date')
                                                        <span></span> </label>
                                                    <input
                                                        class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('from_date') ? ' is-invalid' : '' }}"
                                                        id="receive_date" type="text"
                                                        name="receive_date"
                                                        value="{{ isset($editData) ? date('m/d/Y', strtotime($editData->receive_date)) : '' }}"
                                                        autocomplete="off">

                                                    @if ($errors->has('receive_date'))
                                                        <span class="text-danger">
                                                            {{ $errors->first('receive_date') }}</span>
                                                    @endif
                                                    <button class="" type="button">
                                                        <i class="ti-calendar" id="receive_date_icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-20">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('common.description')
                                                    <span></span> </label>
                                                <textarea class="primary_input_field form-control" cols="0" rows="4"
                                                    name="description"
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

                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('inventory.item_receive')</h3>
                            </div>
                        </div>

                        <div class="offset-lg-6 col-lg-2 text-right col-md-6">
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
                                            <th>@lang('inventory.product_name')</th>
                                            <th>@lang('inventory.unit_price')</th>
                                            <th>@lang('inventory.quantity')</th>
                                            <th>@lang('inventory.sub_total')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                            $j = 0;
                                            $total_quantity = 0;
                                            $grand_total = 0;
                                        @endphp
                                        @if (isset($editDataChildren))

                                            @foreach ($editDataChildren as $editDataValue)
                                                <tr id="row{{ ++$i }}" class="{{ $j++ }}">
                                                    <td>
                                                        <input type="hidden" name="url" id="url"
                                                            value="{{ URL::to('/') }}">
                                                        <div class="primary_input">
                                                            <select
                                                                class="primary_select  form-control{{ $errors->has('item_id') ? ' is-invalid' : '' }}"
                                                                name="item_id[]" id="productName{{ $i }}">
                                                                <option data-display="@lang('common.select_item') " value="">
                                                                    @lang('common.select')</option>

                                                                @foreach ($items as $key => $value)
                                                                    <option value="{{ $value->id }}"
                                                                        @if (isset($editDataChildren)) @if ($editDataValue->item_id == $value->id)
                                                        selected @endif
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
                                                    <td>
                                                        <div class="primary_input">
                                                            <input
                                                                class="primary_input_field form-control{{ $errors->has('unit_price') ? ' is-invalid' : '' }}"
                                                                type="text" id="unit_price{{ $i }}"
                                                                name="unit_price[]" autocomplete="off"
                                                                value="{{ isset($editDataChildren) ? $editDataValue->unit_price : '' }}"
                                                                onkeyup="getTotalByPrice({{ $i }})">


                                                            @if ($errors->has('unit_price'))
                                                                <span class="text-danger">
                                                                    {{ $errors->first('unit_price') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="primary_input">
                                                            <input
                                                                class="primary_input_field form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                                                type="text" id="quantity{{ $i }}"
                                                                name="quantity[]" autocomplete="off"
                                                                onkeyup="getTotal({{ $i }});"
                                                                value="{{ isset($editDataChildren) ? $editDataValue->quantity : '' }}">


                                                            @if ($errors->has('quantity'))
                                                                <span class="text-danger">
                                                                    {{ $errors->first('quantity') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="primary_input">
                                                            <input
                                                                class="primary_input_field form-control{{ $errors->has('sub_total') ? ' is-invalid' : '' }}"
                                                                type="text" name="total[]"
                                                                id="total{{ $i }}" autocomplete="off"
                                                                value="{{ isset($editDataChildren) ? number_format((float) $editDataValue->sub_total, 2, '.', '') : '' }}">


                                                            @if ($errors->has('sub_total'))
                                                                <span class="text-danger">
                                                                    {{ $errors->first('sub_total') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <input type="hidden" name="totalValue[]"
                                                            id="totalValue{{ $i }}" autocomplete="off"
                                                            class="form-control"
                                                            value="{{ isset($editDataChildren) ? $editDataValue->sub_total : '' }}" />
                                                    </td>
                                                    <td>
                                                        <button class="primary-btn icon-only fix-gr-bg" type="button">
                                                            <span class="ti-trash"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @php
                                                    $total_quantity += $editDataValue->quantity;
                                                    $grand_total += $editDataValue->sub_total;
                                                @endphp
                                            @endforeach
                                        @endif
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">@lang('exam.result')</th>
                                            <th>

                                                <input type="text" class="primary_input_field form-control"
                                                    id="subTotalQuantity" name="subTotalQuantity" placeholder="0"
                                                    readonly=""
                                                    value="{{ isset($editDataChildren) ? $total_quantity : '' }}" />

                                                <input type="hidden" class="form-control" id="subTotalQuantityValue"
                                                    value="{{ isset($editDataChildren) ? $total_quantity : '' }}"
                                                    name="subTotalQuantityValue" />

                                            </th>

                                            <th>
                                                <input type="text" class="primary_input_field form-control"
                                                    id="subTotal" name="subTotal" placeholder="0.00" readonly=""
                                                    value="{{ number_format((float) $grand_total, 2, '.', '') }}" />

                                                <input type="hidden" class="form-control" id="subTotalValue"
                                                    name="subTotalValue"
                                                    value="{{ number_format((float) $grand_total, 2, '.', '') }}" />

                                            </th>
                                            <th></th>
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
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <!-- <input class="primary_input_field" id="full_paid" type="checkbox" value="1" name="full_paid"
                            @if ($editData->paid_status == 'P') checked @endif
                            > Full Paid
                             -->

                                                <input type="checkbox" id="full_paid"
                                                    class="common-checkbox form-control" name="full_paid" value="1"
                                                    @if ($editData->paid_status == 'P') checked @endif>
                                                <label for="full_paid">@lang('inventory.full_paid')</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mt-30-md">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input class="primary_input_field" type="text" name="totalPaid"
                                                    id="totalPaid" onkeyup="paidAmount();"
                                                    value="{{ isset($editData) ? $editData->total_paid : '' }}">
                                                <input type="hidden" id="totalPaidValue" name="totalPaidValue">
                                                <label class="primary_input_label"
                                                    for="">@lang('inventory.total_paid')</label>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-30-md">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <input class="primary_input_field" type="text"
                                                    value="{{ isset($editData) ? number_format((float) $editData->total_due, 2, '.', '') : '' }}"
                                                    id="totalDue" readonly>
                                                <input type="hidden" id="totalDueValue" name="totalDueValue"
                                                    value="{{ isset($editData) ? $editData->total_due : '' }}">
                                                <label class="primary_input_label"
                                                    for="">@lang('inventory.total_due')</label>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-20 text-center">
                                        <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>
                                            @lang('common.update')
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
