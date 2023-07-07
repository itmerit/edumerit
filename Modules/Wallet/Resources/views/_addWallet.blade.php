    <div class="col-12">
        <div class="d-flex mb-25 align-items-center justify-content-between">
            @if (Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                <button class="primary-btn small fix-gr-bg">
                    @lang('wallet::wallet.balance'):
                    {{ Auth::user()->wallet_balance != null ? currency_format(Auth::user()->wallet_balance) : currency_format(0.0) }}
                </button>


                @if (userPermission('add-wallet-amount') || userPermission('wallet.my-wallet'))
                    <button class="primary-btn small fix-gr-bg mr-2 ml-auto" data-toggle="modal"
                        data-target="#addWalletPayment">
                        <span class="ti-plus pr-2"></span>
                        @lang('wallet::wallet.add_balance')
                    </button>
                @endif
                @if (userPermission('refund-wallet') || userPermission('wallet.my-wallet'))
                    <button class="primary-btn small fix-gr-bg" data-toggle="modal" data-target="#refundRequest">
                        @lang('wallet::wallet.refund_request')
                    </button>
                @endif
            @endif
        </div>
    </div>
    </div>
    <div class="row mt-30">
        <div class="col-lg-12">
            <x-table>
                <table id="table_id" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="sort_wallet">@lang('common.sl')</th>
                            <th class="sort_wallet">@lang('wallet::wallet.method') </th>
                            <th class="sort_wallet">@lang('wallet::wallet.amount')</th>
                            <th class="sort_wallet">@lang('common.status')</th>
                            <th class="sort_wallet">@lang('wallet::wallet.issue_date')</th>
                            <th class="sort_wallet">@lang('wallet::wallet.note')</th>
                            <th class="sort_wallet">@lang('common.file')</th>
                            <th class="sort_wallet">@lang('wallet::wallet.approve_date')</th>
                            <th class="sort_wallet">@lang('wallet::wallet.feedback')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($walletAmounts as $key => $walletAmount)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $walletAmount->payment_method }}</td>
                                <td>{{ currency_format(@$walletAmount->amount) }}</td>
                                <td>
                                    @if ($walletAmount->status == 'pending')
                                        <button
                                            class="primary-btn small bg-warning text-white border-0">@lang('common.pending')</button>
                                    @elseif ($walletAmount->type == 'diposit' && $walletAmount->status == 'approve')
                                        <button
                                            class="primary-btn small bg-success text-white border-0">@lang('wallet::wallet.approve')</button>
                                    @elseif ($walletAmount->status == 'reject')
                                        <button
                                            class="primary-btn small bg-danger text-white border-0">@lang('wallet::wallet.reject')</button>
                                    @elseif ($walletAmount->type == 'refund' && $walletAmount->status == 'approve')
                                        <button
                                            class="primary-btn small bg-primary text-white border-0">@lang('wallet::wallet.refund')</button>
                                    @endif
                                </td>
                                <td>{{ dateConvert($walletAmount->created_at) }}</td>
                                <td>
                                    @if ($walletAmount->note)
                                        <a class="text-color" data-toggle="modal"
                                            data-target="#showNote{{ $walletAmount->id }}"
                                            href="#">@lang('common.view')</a>
                                    @endif
                                </td>
                                <td>
                                    @if (file_exists($walletAmount->file))
                                        <a class="text-color" data-toggle="modal"
                                            data-target="#showFile{{ $walletAmount->id }}"
                                            href="#">@lang('common.view')</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($walletAmount->status == 'approve' || $walletAmount->status == 'reject')
                                        {{ dateConvert($walletAmount->updated_at) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($walletAmount->reject_note)
                                        <a class="text-color" data-toggle="modal"
                                            data-target="#feedBack{{ $walletAmount->id }}"
                                            href="#">@lang('common.view')</a>
                                    @endif
                                </td>
                            </tr>

                            {{-- Note Start  --}}
                            <div class="modal fade admin-query" id="showNote{{ $walletAmount->id }}">
                                <div class="modal-dialog modal-dialog-centered large-modal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('wallet::wallet.view_note')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body p-0 mt-30">
                                            <div class="container student-certificate">
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-12 text-center">
                                                        <p>{{ $walletAmount->note }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Note End  --}}

                            {{-- File View and Download Modal Start  --}}
                            <div class="modal fade admin-query" id="showFile{{ $walletAmount->id }}">
                                <div class="modal-dialog modal-dialog-centered large-modal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('wallet::wallet.view_file')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body p-0 mt-30">
                                            <div class="container student-certificate">
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-12 text-center">
                                                        @php
                                                            $pdf = $walletAmount->file ? explode('.', @$walletAmount->file) : '' . ' . ' . '';
                                                            $for_pdf = $pdf[1];
                                                        @endphp
                                                        @if (@$for_pdf == 'pdf')
                                                            <div class="mb-5">
                                                                <a href="{{ url(@$walletAmount->file) }}"
                                                                    download>@lang('common.download') <span
                                                                        class="pl ti-download"></span></a>
                                                            </div>
                                                        @else
                                                            @if (file_exists($walletAmount->file))
                                                                <div class="mb-5">
                                                                    <img class="img-fluid"
                                                                        src="{{ asset($walletAmount->file) }}">
                                                                </div>
                                                                <br>
                                                                <div class="mb-5">
                                                                    <a href="{{ url(@$walletAmount->file) }}"
                                                                        download>@lang('common.download') <span
                                                                            class="pl ti-download"></span></a>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- File View and Download Modal End  --}}

                            {{-- Feedback View Start  --}}
                            <div class="modal fade admin-query" id="feedBack{{ $walletAmount->id }}">
                                <div class="modal-dialog modal-dialog-centered large-modal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('wallet::wallet.view_feedback')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body p-0 mt-30">
                                            <div class="container student-certificate">
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-12 text-center">
                                                        <p>{{ $walletAmount->reject_note }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Feedback View End  --}}
                        @endforeach
                    </tbody>
                </table>
            </x-table>
        </div>
    </div>
    <div class="modal fade admin-query" id="addWalletPayment">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('wallet::wallet.add_amount')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'wallet.add-wallet-amount', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'addWalletAmount']) }}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('wallet::wallet.amount') <span class="text-danger"> *</span> </label>
                                <input class="primary_input_field form-control" type="text" name="amount" id="walletAmount">
                                
                                <span class="walletError" id="walletAmountError"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <label class="primary_input_label" for="">@lang('fees.payment_method') <span class="text-danger"> *</span> </label>
                            <select class="primary_select  form-control" name="payment_method"
                                id="addWalletPaymentMethod">
                                <option data-display="@lang('fees.payment_method') *" value="">@lang('fees.payment_method') *
                                </option>

                                @foreach ($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->method }}">{{ $paymentMethod->method }}
                                        {{ service_charge(@$paymentMethod->gatewayDetail->charge_type, @$paymentMethod->gatewayDetail->charge) ? '+ ' . __('common.service_charge') . '(' . service_charge(@$paymentMethod->gatewayDetail->charge_type, @$paymentMethod->gatewayDetail->charge) . ')' : null }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="walletError" id="paymentMethodError"></span>
                        </div>
                    </div>

                    <div class="row mt-20 addWalletBank d-none">
                        <div class="col-lg-12">
                            <select
                                class="primary_select  form-control{{ $errors->has('bank') ? ' is-invalid' : '' }}"
                                name="bank">
                                <option data-display="@lang('fees.select_bank')*" value="">@lang('fees.select_bank')*</option>
                                @foreach ($bankAccounts as $bankAccount)
                                    <option value="{{ $bankAccount->id }}"
                                        {{ old('bank') == $bankAccount->id ? 'selected' : '' }}>
                                        {{ $bankAccount->bank_name }} ({{ $bankAccount->account_number }})</option>
                                @endforeach
                            </select>
                            <span class="walletError" id="bankError"></span>
                        </div>
                    </div>

                    <div class="row mt-20 AddWalletChequeBank d-none">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('wallet::wallet.note') <span></span> </label>
                                <textarea class="primary_input_field form-control" cols="0" rows="3" name="note" id="note">{{old('note')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters input-right-icon mt-25 AddWalletChequeBank d-none">
                        <div class="col">
                            <div class="primary_input">
                                <input class="primary_input_field form-control {{ $errors->has('file') ? ' is-invalid' : '' }}" readonly="true" type="text" placeholder="{{isset($editData->upload_file) && @$editData->upload_file != ""? getFilePath3(@$editData->upload_file):trans('common.file').''}}" id="placeholderUploadContent">
                                @if ($errors->has('file'))
                                    <span class="text-danger mb-10" role="alert">
                                        {{ $errors->first('file') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="primary-btn-small-input" type="button">
                                <label class="primary-btn small fix-gr-bg"
                                    for="upload_content_file">@lang('common.browse')</label>
                                <input type="file" class="d-none form-control" name="file"
                                    id="upload_content_file">
                            </button>
                        </div>
                        <br>
                    </div>

                    <div class="AddWalletChequeBank d-none text-center">
                        <code>(JPG, JPEG, PNG, PDF are allowed for upload)</code>
                    </div>
                    <span class="walletError" id="fileError"></span>

                    <div class="stripeInfo d-none">
                        <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('accounts.name_on_card') <span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field form-control{{ $errors->has('name_on_card') ? ' is-invalid' : '' }}" type="text" name="name_on_card" id="name_on_card" autocomplete="off" value="{{old('name_on_card')}}">
                                    @if ($errors->has('name_on_card'))
                                        <span class="text-danger"> {{ $errors->first('name_on_card') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('accounts.card_number') <span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field form-control card-number" type="text" name="card-number" id="card-number" autocomplete="off" value="{{old('card-number')}}">
                                    @if ($errors->has('card_number'))
                                        <span class="text-danger"> {{ $errors->first('card_number') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('accounts.cvc') <span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field form-control card-cvc" type="text" name="card-cvc" id="card-cvc" autocomplete="off" value="{{old('card-cvc')}}">
                                    @if ($errors->has('cvc'))
                                        <span class="text-danger"> {{ $errors->first('cvc') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('accounts.expiration_month') <span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field form-control card-expiry-month" type="text" name="card-expiry-month" id="card-expiry-month" autocomplete="off" value="{{old('card-expiry-month')}}">
                                    @if ($errors->has('expiration_month'))
                                        <span class="text-danger"> {{ $errors->first('expiration_month') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('accounts.expiration_year') <span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field form-control card-expiry-year" type="text" name="card-expiry-year" id="card-expiry-year" autocomplete="off" value="{{old('card-expiry-year')}}">
                                    @if ($errors->has('expiration_year'))
                                        <span class="text-danger"> {{ $errors->first('expiration_year') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (moduleStatusCheck('MercadoPago') == true)
                        @include('mercadopago::form.userForm', ['wallet' => true])
                    @endif

                    <div class="row mt-30">
                        <div class="col-lg-12 text-center">
                            <button class="primary-btn fix-gr-bg submit addWallet generalPay"
                                title="@lang('common.add')">
                                <span class="ti-check"></span>@lang('common.add')
                            </button>
                        </div>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
    {{-- Refund Request Start --}}
    <div class="modal fade admin-query" id="refundRequest">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('wallet::wallet.refund_request')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'wallet.wallet-refund-request-store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'refundAmount']) }}
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('wallet::wallet.wallet_balance')
                                    ({{ generalSetting()->currency_symbol }})</label>
                                <input class="primary_input_field" type="text"
                                    value="{{ Auth::user()->wallet_balance != null ? number_format(Auth::user()->wallet_balance, 2, '.', '') : 0.0 }}"
                                    name="refund_amount" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('wallet::wallet.note')<span
                                        class="text-danger"> *</span></label>
                                <textarea class="primary_input_field form-control" cols="0" rows="3" name="refund_note"
                                    id="refundNote">{{ old('refund_note') }}</textarea>

                                <span class="walletError" id="refundNoteError"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters input-right-icon mt-25">
                        <div class="col">
                            <div class="primary_input ">
                                <input
                                    class="primary_input_field form-control {{ $errors->has('refund_file') ? ' is-invalid' : '' }}"
                                    readonly="true" type="text"
                                    placeholder="{{ isset($editData->upload_file) && @$editData->upload_file != '' ? getFilePath3(@$editData->upload_file) : trans('common.file') . '' }}"
                                    id="placeholderRefund">

                                @if ($errors->has('refund_file'))
                                    <span class="text-danger mb-10" role="alert">
                                        {{ $errors->first('refund_file') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="primary-btn-small-input" type="button">
                                <label class="primary-btn small fix-gr-bg"
                                    for="wallet_refund">@lang('common.browse')</label>
                                <input type="file" id="wallet_refund" class="d-none cutom-photo"
                                    name="refund_file">
                            </button>
                        </div>
                    </div>
                    <div class="text-center">
                        <code>(JPG, JPEG, PNG, PDF are allowed for upload)</code>
                    </div>
                    <span class="walletError" id="refundFileError"></span>
                    <span class="walletError" id="existsError"></span>
                    @if (Auth::user()->wallet_balance > 0)
                        <div class="row mt-30">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit addWallet" title="@lang('common.add')">
                                    <span class="ti-check"></span>
                                    @lang('common.submit')
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    {{-- Refund Request End --}}

    @include('backEnd.partials.data_table_js')
