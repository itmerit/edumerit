<style>
    .primary_datepicker_input button i {
        top: 15px !important;
    }
</style>
<div class="container-fluid">
    {{ Form::open([
        'class' => 'form-horizontal',
        'files' => true,
        'route' => 'savePayrollPaymentData',
        'method' => 'POST',
        'enctype' => 'multipart/form-data',
        'onsubmit' => 'return validateForm()',
    ]) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="row mt-25">
                <div class="col-lg-6" id="sibling_class_div">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">@lang('hr.staff_name') (@lang('hr.staff_id') ) <span></span> </label>
                        <input readonly
                            class="read-only-input primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}"
                            type="text" name="amount"
                            value="{{ $payrollDetails->staffs->full_name }} ({{ $payrollDetails->staffs->staff_no }})">
                        <input type="hidden" name="payroll_generate_id" value="{{ $payrollDetails->id }}">
                        <input type="hidden" name="role_id" value="{{ $role_id }}">
                        <input type="hidden" name="payroll_month" value="{{ $payrollDetails->payroll_month }}">
                        <input type="hidden" name="payroll_year" value="{{ $payrollDetails->payroll_year }}">

                        @if ($errors->has('amount'))
                            <span class="text-danger">
                                {{ $errors->first('amount') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6" id="">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">@lang('accounts.expense_head') <span></span> </label>
                        <select
                            class="primary_select form-control{{ $errors->has('expense_head_id') ? ' is-invalid' : '' }}"
                            name="expense_head_id" id="expense_head_id">
                            <option data-display="Expense Head*" value="">@lang('accounts.expense_head') *</option>
                            @if (isset($chart_of_accounts))
                                @foreach ($chart_of_accounts as $value)
                                    <option value="{{ $value->id }}">{{ $value->head }}</option>
                                @endforeach
                            @endif
                        </select>
                        @if ($errors->has('expense_head_id'))
                            <span class="text-danger">
                                {{ $errors->first('expense_head_id') }}
                            </span>
                        @endif

                    </div>
                </div>
            </div>

            <div class="row mt-25">
                <div class="col-lg-6" id="">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">@lang('hr.month_year') <span></span> </label>
                        <input readonly
                            class="read-only-input primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}"
                            type="text" name="amount"
                            value="{{ $payrollDetails->payroll_month }} - {{ $payrollDetails->payroll_year }}">
                        

                        @if ($errors->has('amount'))
                            <span class="text-danger">
                                {{ $errors->first('amount') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="primary_input">
                        <div class="primary_datepicker_input">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="">
                                        <label class="primary_input_label" for="payment_date">@lang('fees.payment_date') <span class="text-danger">*</span></label>
                                        <input
                                            class="primary_input_field primary_input_field date form-control"
                                            id="payment_date" type="text" name="payment_date"
                                            value="{{ old('admission_date') != '' ? old('admission_date') : date('m/d/Y') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <button class="btn-date" data-id="#payment_date" type="button">
                                    <label class="m-0 p-0" for="payment_date">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </label>
                                </button>
                            </div>
                        </div>
                        <span class="text-danger">{{ $errors->first('payment_date') }}</span>
                    </div>
                </div>
                {{-- <div class="col-lg-6" id="">
                    <div class="primary_input">
                        <input
                            class="read-only-input primary_input_field  primary_input_field date form-control form-control{{ $errors->has('apply_date') ? ' is-invalid' : '' }}"
                            id="payment_date" type="text" name="payment_date" value="{{ date('m/d/Y') }}">
                        <label class="primary_input_label" for="">@lang('fees.payment_date') <span class="text-danger">
                                *</span> </label>

                        @if ($errors->has('payment_date'))
                            <span class="text-danger">
                                {{ $errors->first('payment_date') }}
                            </span>
                        @endif
                    </div>
                </div> --}}


            </div>

            <div class="row mt-25">
                <div class="col-lg-6">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">@lang('accounts.payment_amount')</label>
                        <input class="read-only-input primary_input_field form-control{{ $errors->has('discount') ? ' is-invalid' : '' }}" type="text" name="submit_amount" value="{{ $payrollDetails->net_salary - $payrollDetails->payrollPayments->sum('amount') }}">

                        @if ($errors->has('discount'))
                            <span class="text-danger">
                                {{ $errors->first('discount') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="primary_input">
                        <label class="primary_input_label">@lang('accounts.payment_method') <span class="text-danger">*</span></label>
                        <select
                            class="primary_select form-control{{ $errors->has('payment_mode') ? ' is-invalid' : '' }}"
                            name="payment_mode" id="payment_mode">
                            <option data-display="Payment Method *" value="">@lang('accounts.payment_method') *</option>
                            @if (isset($paymentMethods))
                                @foreach ($paymentMethods as $value)
                                    <option value="{{ $value->id }}" data-mode="{{$value->method}}">{{ $value->method }}</option>
                                @endforeach
                            @endif
                        </select>
                        @if ($errors->has('payment_mode'))
                            <span class="text-danger">
                                {{ $errors->first('payment_mode') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mt-25" id="bankOption">
                <div class="col-lg-12">
                    <div class="primary_input">
                        <select
                            class="primary_select form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}"
                            name="bank_id" id="account_id">
                            @if (isset($account_id))
                                @foreach ($account_id as $key => $value)
                                    <option value="{{ $value->id }}" data-account="{{$value->bank_name}}">{{ $value->bank_name }}</option>
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
            </div>

            <div class="row mt-25">
                <div class="col-lg-12" id="sibling_name_div">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">@lang('common.note') </label>
                        <textarea class="primary_input_field form-control" cols="0" rows="3" name="note" id="note"></textarea>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 text-center mt-40">
            <div class="mt-40 d-flex justify-content-between">
                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                <input class="primary-btn fix-gr-bg" type="submit" value="save information">
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
<script>
    if ($(".primary_select").length) {
        $(".primary_select").niceSelect();
    }
    //Payroll proceed to pay
    $(document).ready(function() {
        $('#bankOption').hide();
    });

    $(document).ready(function() {
        $("#payment_mode").on("change", function() {
            var mode = $(this).find(':selected').data('mode');
            if (mode == "Bank") {
                $('#bankOption').show();
            } else {
                $('#bankOption').hide();
            }
        });
    });

    $("#search-icon").on("click", function() {
        $("#search").focus();
    });

    $("#start-date-icon").on("click", function() {
        $("#startDate").focus();
    });

    $("#end-date-icon").on("click", function() {
        $("#endDate").focus();
    });

    $(".primary_input_field.date").datepicker({
        autoclose: true,
        setDate: new Date(),
    });
    $(".primary_input_field.date").on("changeDate", function(ev) {
        // $(this).datepicker('hide');
        $(this).focus();
    });

    $(".primary_input_field.time").datetimepicker({
        format: "LT",
    });
</script>
