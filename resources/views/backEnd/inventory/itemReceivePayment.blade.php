
<div class="container-fluid">
   {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save-item-receive-payment',
   'method' => 'POST', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return validateForm()']) }}

   <div class="row">
    <div class="col-lg-12">
        <div class="row mt-25">
            <div class="col-lg-12" id="">
               
            </div>
        </div>
        <input type="hidden" name="item_receive_id" value="{{$id}}">
        <div class="row mt-25">
            <div class="col-lg-12">
                <div class="primary_input">
                    <select class="primary_select1 w-100 bb form-control{{ $errors->has('expense_head_id') ? ' is-invalid' : '' }}" name="expense_head_id" id="expense_head_id">
                        <option data-display="@lang('accounts.expense_head') *" value="">@lang('common.select')</option>
                            @foreach($expense_head as $key=>$value)
                            <option value="{{$value->id}}"
                            {{@$editData->expense_head_id == $value->id? 'selected': ''}}
                            >{{$value->head}}</option>
                            @endforeach
                        </select>
                        
                        @if ($errors->has('expense_head_id'))
                        <span class="text-danger invalid-select" role="alert">
                            {{ $errors->first('expense_head_id') }}
                        </span>
                        @endif
                        <span class="modal_input_validation red_alert"></span>
                    </div>
            </div>
        </div>
        <div class="row mt-25">
        <div class="col-lg-12">
                <div class="primary_input">
                    <select class="primary_select1 w-100 bb form-control{{ $errors->has('payment_mode') ? ' is-invalid' : '' }}" name="payment_method" id="item_receive_add_payment_method">
                        @if(@$editData->paymentMethodName->method =="Bank")
                            <option data-string="{{@$editData->paymentMethodName->method}}" value="{{@$editData->payment_method}}" selected>{{@$editData->paymentMethodName->method}}</option>
                        @else
                        @foreach($paymentMethhods as $key=>$value)
                        @if(isset($editData))
                        <option data-string="{{$value->method}}" value="{{$value->id}}"
                            {{@$editData->payment_method == $value->id? 'selected': ''}}>{{$value->method}}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                    <span class="modal_input_validation red_alert"></span>
                </div>
            </div>
            <input type="hidden" name="paymentMethodName" value="">
        </div>
        <div class="row mt-25">
            <div class="col-lg-12 d-none" id="add_payment_item_receive_bankAccount">
                <div class="primary_input">
                    <select class="primary_select1 w-100 bb form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}" name="bank_id" id="receive_account_id">
                        @if(isset($editData))
                            <option value="{{$editData->account_id}}" selected>{{@$editData->bankName->account_name}} ({{@$editData->bankName->bank_name}})</option>
                        @endif
                        </select>
                        
                        @if ($errors->has('bank_id'))
                        <span class="text-danger invalid-select" role="alert">
                            {{ $errors->first('bank_id') }}
                        </span>
                        @endif
                </div>
            </div>
        </div>
        <div class="row mt-25">
            <div class="col-lg-12">
                <div class="primary_input">
                    <input class="read-only-input primary_input_field form-control{{ $errors->has('reference_no') ? ' is-invalid' : '' }}" type="text" name="reference_no" id="reference_no" value="">
                    <label> @lang('inventory.reference_note') <span class="text-danger"> *</span> </label>
                    
                    @if ($errors->has('reference_no'))
                    <span class="text-danger" >
                        {{ $errors->first('reference_no') }}
                    </span>
                    @endif
                    <span class="modal_input_validation red_alert"></span>
                </div>
            </div>
        </div>

        <div class="row mt-25">
            <div class="col-lg-6">
                <div class="primary_input">
                    <input class="read-only-input primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="number" name="amount" value="{{$paymentDue->total_due}}" id="total_due" onkeyup="checkDue()">
                    <input type="hidden" id="total_due_value" value="{{$paymentDue->total_due}}">
                    <label class="primary_input_label" for="">@lang('accounts.payment_amount') <span class="text-danger"> *</span> </label>
                    
                    @if ($errors->has('amount'))
                    <span class="text-danger" >
                        {{ $errors->first('amount') }}
                    </span>
                    @endif
                    <span class="modal_input_validation red_alert"></span>
                </div>
            </div>
            <div class="col-lg-6" id="">
                <div class="primary_input">
                    <input class="read-only-input primary_input_field  primary_input_field date form-control form-control{{ $errors->has('apply_date') ? ' is-invalid' : '' }}" id="payment_date" type="text"
                    name="payment_date" value="{{date('m/d/Y')}}">
                    <label class="primary_input_label" for="">@lang('fees.payment_date') <span class="text-danger"> *</span> </label>
                    
                    @if ($errors->has('payment_date'))
                    <span class="text-danger" >
                        {{ $errors->first('payment_date') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mt-25">
            <div class="col-lg-12" id="sibling_name_div">
                <div class="primary_input mt-20">
                    <textarea class="primary_input_field form-control" cols="0" rows="3" name="notes" id="notes"></textarea>
                    <label class="primary_input_label" for="">@lang('common.note') </label>
                    

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
@include('backEnd.partials.date_picker_css_js')
<script>
    if ($(".niceSelect1").length) {
        $(".niceSelect1").niceSelect();
    }

    $(document).ready(function() {
        $("#item_receive_add_payment_method").on("change", function() {
            let methodName = $(this).find(':selected').data('string');
            if (methodName == "Bank") {
                $("#add_payment_item_receive_bankAccount").removeClass('d-none');
            } else {
                $("#add_payment_item_receive_bankAccount").addClass('d-none');
            }
        });

        let methodType = $('#item_receive_add_payment_method').find(':selected').data('string');
        if (methodType == "Bank") {
            $("#add_payment_item_receive_bankAccount").removeClass('d-none');
        } else {
            $("#add_payment_item_receive_bankAccount").addClass('d-none');
        }
    });
</script>