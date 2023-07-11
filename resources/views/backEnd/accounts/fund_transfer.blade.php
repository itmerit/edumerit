@extends('backEnd.master')
@section('title')
    @lang('accounts.fund_transfer')
@endsection
@section('mainContent')
    @push('css')
        {{-- <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/style.css') }}" /> --}}
        <style>
            div#bankList,
            div#toBankList {
                position: absolute;
                left: 50%;
                top: 10%;
            }

            table.dataTable thead th {
                padding: 10px 30px !important;
            }

            table.dataTable tbody th,
            table.dataTable tbody td {
                padding: 20px 30px 20px 30px !important;
            }

            table.dataTable tfoot th,
            table.dataTable tfoot td {
                padding: 10px 30px 6px 30px;
            }
        </style>
    @endpush
    @php
        @$setting = app('school_info');
        if (!empty(@$setting->currency_symbol)) {
            @$currency = @$setting->currency_symbol;
        } else {
            @$currency = '$';
        }
    @endphp
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('accounts.fund_transfer')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('accounts.accounts')</a>
                    <a href="#">@lang('accounts.fund_transfer')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fund-transfer-store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h3 class="mb-10">@lang('common.add_information')</h3>
                                        <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.amount') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ @$errors->has('amount') ? ' is-invalid' : '' }}"
                                                type="text" name="amount" step="0.1" autocomplete="off"
                                                value="{{ old('amount') }}">


                                            @if ($errors->has('amount'))
                                                <span class="text-danger">
                                                    <strong>{{ @$errors->first('amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mt-30">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.purpose') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ @$errors->has('purpose') ? ' is-invalid' : '' }}"
                                                type="text" name="purpose" autocomplete="off"
                                                value="{{ old('purpose') }}">


                                            @if ($errors->has('purpose'))
                                                <span class="text-danger">
                                                    <strong>{{ @$errors->first('purpose') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $tooltip = '';
                                    if (userPermission('fund-transfer-store')) {
                                        $tooltip = '';
                                    } else {
                                        $tooltip = 'You have no permission to add';
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ $tooltip }}">
                                            <span class="ti-check"></span>
                                            @lang('accounts.fund_transfer')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <h3>@lang('accounts.from')</h3>
                                @foreach ($payment_methods as $payment_method)
                                    <div class=" radio-btn-flex ml-20">
                                        <div class="CustomPaymentMethod d-flex mb-2">
                                            <div class="primary_input custom-transfer-account  d-flex">
                                                <input type="radio" name="from_payment_method"
                                                    data-id="{{ $payment_method->method }}"
                                                    id="from_method{{ $payment_method->id }}"
                                                    value="{{ $payment_method->id }}" class="common-radio relation">
                                                <label style="margin-left: 10px; margin-top: 8px;"
                                                    for="from_method{{ $payment_method->id }}">{{ $payment_method->method }}
                                                    @php
                                                        $total = $payment_method->IncomeAmount - $payment_method->ExpenseAmount;
                                                    @endphp
                                                    @if ($payment_method->method != 'Bank')
                                                        ({{ $total }})
                                                    @else
                                                        ({{ $bank_amount }})
                                                    @endif
                                                </label>
                                            </div>
                                            @if ($payment_method->method == 'Bank')
                                                <div class="d-none pl-3" id="bankList">
                                                    @foreach ($bank_accounts as $bank_account)
                                                        <div class="primary_input custom-transfer-account mb-10">
                                                            <input type="radio" name="from_bank_name"
                                                                id="from_bank{{ $bank_account->id }}"
                                                                value="{{ $bank_account->id }}" class="common-radio">
                                                            <label
                                                                for="from_bank{{ $bank_account->id }}">{{ $bank_account->bank_name }}
                                                                ({{ $bank_account->current_balance }})
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if ($errors->has('from_payment_method'))
                                    <span class="text-danger d-block mt-0" role="alert">
                                        <strong>{{ @$errors->first('from_payment_method') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <h3>@lang('accounts.to')</h3>
                                @foreach ($payment_methods as $payment_method)
                                    <div class=" radio-btn-flex ml-20">
                                        <div class="CustomPaymentMethod d-flex mb-2">
                                            <div
                                                class="primary_input custom-transfer-account remove{{ $payment_method->id }} d-flex">
                                                <input style="bottom: 5px" type="radio" name="to_payment_method"
                                                    data-id="{{ $payment_method->method }}"
                                                    id="to_method{{ $payment_method->id }}"
                                                    value="{{ $payment_method->id }}"
                                                    class="common-radio toRelation">
                                                <label style="margin-left: 10px; margin-top: 8px;"
                                                    for="to_method{{ $payment_method->id }}">{{ $payment_method->method }}
                                                    @php
                                                        $total = $payment_method->IncomeAmount - $payment_method->ExpenseAmount;
                                                    @endphp
                                                    @if ($payment_method->method != 'Bank')
                                                        ({{ $total }})
                                                    @else
                                                        ({{ $bank_amount }})
                                                    @endif
                                                </label>


                                            </div>
                                            @if ($payment_method->method == 'Bank')
                                                <div class="d-none pl-3" id="toBankList">
                                                    @foreach ($bank_accounts as $bank_account)
                                                        <div class="primary_input custom-transfer-account mb-10">
                                                            <input type="radio" name="to_bank_name"
                                                                id="tobank{{ $bank_account->id }}"
                                                                value="{{ $bank_account->id }}" class="common-radio">
                                                            <label
                                                                for="tobank{{ $bank_account->id }}">{{ $bank_account->bank_name }}
                                                                ({{ $bank_account->current_balance }})
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if ($errors->has('to_payment_method'))
                                    <span class="text-danger d-block mt-0" role="alert">
                                        <strong>{{ @$errors->first('to_payment_method') }}</strong>
                                    </span>
                                @endif
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('accounts.amount_transfer_list')</h3>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="tableWithoutSort" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('accounts.purpose')</th>
                                            <th>@lang('accounts.amount')</th>
                                            <th>@lang('accounts.from')</th>
                                            <th>@lang('accounts.to')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($transfers as $transfer)
                                            @php
                                                $total = $total + $transfer->amount;
                                            @endphp
                                            <tr>
                                                <td>{{ $transfer->purpose }}</td>
                                                <td>{{ $transfer->amount }}</td>
                                                <td>{{ $transfer->fromPaymentMethodName->method }}</td>
                                                <td>{{ $transfer->toPaymentMethodName->method }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>@lang('accounts.total')</td>
                                            <td>{{ currency_format($total) }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(document).on('change', '.relation', function() {
            let from_account_id = $(this).data('id');
            if (from_account_id == "Bank") {
                $("#bankList").addClass("d-block");
            } else {
                $("#bankList").removeClass("d-block");
            }

        })

        $(document).on('change', '.toRelation', function() {
            let to_account_id = $(this).data('id');
            if (to_account_id == "Bank") {
                $("#toBankList").addClass("d-block");
            } else {
                $("#toBankList").removeClass("d-block");
            }

        })
    </script>
@endpush
