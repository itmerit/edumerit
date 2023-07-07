@extends('backEnd.master')
@section('title') 
@lang('common.search_expense')
@endsection
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.accounts')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('lang.accounts')</a>
                <a href="{{route('search_expense')}}">@lang('common.search_expense')</a>
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
                <div class="col-lg-6">
                    @if(session()->has('message-success') != "")
                        @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                        @endif
                    @endif
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'search_expense_report_by_date', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-6 mt-30-md">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ @$errors->has('date_from') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                     name="date_from" value="{{date('m/d/Y')}}" readonly>
                                                    <label class="primary_input_label" for="">@lang('accounts.date_from')</label>
                                                    
                                                @if ($errors->has('date_from'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('date_from') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="admission-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-30-md">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ @$errors->has('date_to') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                     name="date_to" value="{{date('m/d/Y')}}" readonly>
                                                    <label class="primary_input_label" for="">@lang('accounts.date_to')</label>
                                                    
                                                @if ($errors->has('date_to'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('date_to') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="admission-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <div class="col-lg-6">
                    @if(session()->has('message-success') != "")
                        @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                        @endif
                    @endif
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'search_expense_report_by_income', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                    <div class="col-lg-12 mt-30-md">
                                        <div class="primary_input">
                                        <input class="primary_input_field form-control{{ @$errors->has('expense') ? ' is-invalid' : '' }}" type="text" name="expense">
                                        <label class="primary_input_label" for="">@lang('common.search_by_expense')<span class="text-danger"> *</span></label>
                                        
                                        @if ($errors->has('expense'))
                                        <span class="text-danger" >
                                            {{ $errors->first('expense') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>            
        @if(isset($add_expenses))
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('accounts.expense_result') </h3>
                            </div>
                        </div>
                    </div>                
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('accounts.expense_head')</th>
                                        <th>@lang('lang.payment_method')</th>
                                        <th>@lang('common.date')</th>
                                        <th>@lang('accounts.amount')({{generalSetting()->currency_symbol}})</th>
                                    </tr>
                                </thead>
                                @php $total_expense = 0;@endphp
                                <tbody>
                                    @foreach($add_expenses as $add_expense)
                                    @php @$total_expense = @$total_expense + @$add_expense->amount; @endphp
                                    <tr>
                                        <td>{{@$add_expense->name}}</td>
                                        <td>{{@$add_expense->expenseHead !=""? @$add_expense->expenseHead->name:""}}</td>
                                        <td>{{@$add_expense->paymentMethod!=""?@$add_expense->paymentMethod->method:""}}</td>

                                        <td>
                                            {{@$add_expense->date != ""? dateConvert(@$add_expense->date):''}}
                                        </td>
                                        <td>{{number_format(@$add_expense->amount, 2)}}</td>
                                    </tr>
                                    @endforeach
                                    @if($item_receives != 0)
                                    @php @$total_expense = @$total_expense + @$item_receives; @endphp
                                    <tr>
                                        <td>@lang('lang.to_item_Receive')</td>
                                        <td>@lang('inventory.item_Receive')</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{number_format(@$item_receives, 2)}}</td>
                                    </tr>
                                    @endif
                                    @if(@$payroll_payments != 0)
                                    @php @$total_expense = @$total_expense + @$payroll_payments; @endphp
                                    <tr>
                                        <td>@lang('fees.from_payroll')</td>
                                        <td>@lang('hr.payroll')</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{number_format(@$payroll_payments, 2)}}</td>
                                    </tr>
                                    @endif  
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>@lang('accounts.grand_total')</th>
                                        <th></th>
                                        <th>{{number_format(@$total_expense, 2)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')