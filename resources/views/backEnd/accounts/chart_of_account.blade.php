@extends('backEnd.master')
@section('title') 
@lang('accounts.chart_of_account')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('accounts.chart_of_account')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('accounts.accounts')</a>
                <a href="#">@lang('accounts.chart_of_account')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($chart_of_account))
         @if(userPermission("chart-of-account-store"))

        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('chart-of-account')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($chart_of_account))
                                    @lang('accounts.edit_chart_of_account')
                                @else
                                    @lang('accounts.add_chart_of_account')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($chart_of_account))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true,  'route' => array('chart-of-account-update',@$chart_of_account->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                          @if(userPermission("chart-of-account-store"))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'chart-of-account',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                       
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('accounts.head') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('head') ? ' is-invalid' : '' }}"
                                                type="text" name="head" autocomplete="off" value="{{isset($chart_of_account)? $chart_of_account->head: old('head')}}">
                                            <input type="hidden" name="id" value="{{isset($chart_of_account)? $chart_of_account->id: ''}}">
                                            
                                            
                                            @if ($errors->has('head'))
                                            <span class="text-danger" >
                                                <strong>{{ @$errors->first('head') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('common.type') <span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ @$errors->has('type') ? ' is-invalid' : '' }}" name="type">
                                            <option data-display="@lang('common.type') *" value="">@lang('common.type') *</option>

                                            <option value="E" {{@$chart_of_account->type == 'E'? 'selected':old('type') == ('E'? 'selected':'') }}>@lang('accounts.expense')</option>
                                            <option value="I" {{@$chart_of_account->type == 'I'? 'selected':old('type') == ('I'? 'selected':'' )}}>@lang('accounts.income')</option>

                                        </select>

                                         
                                        @if ($errors->has('type'))
                                        <span class="text-danger invalid-select" role="alert">
                                            <strong>{{ @$errors->first('type') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            	@php
                                  $tooltip = "";
                                  if(userPermission("chart-of-account-store") || userPermission('chart-of-account-edit')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($chart_of_account))
                                                @lang('accounts.update_head')
                                            @else
                                                @lang('accounts.save_head')
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
                            <h3 class="mb-0">@lang('accounts.chart_of_account_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                               
                                <tr>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.type')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($chart_of_accounts as $chart_of_account)
                                <tr>
                                    <td>{{@$chart_of_account->head}}</td>
                                            
                                    <td>
                                        @if($chart_of_account->type=="A")@lang('accounts.asset') @endif
                                        @if($chart_of_account->type=="E")@lang('accounts.expense') @endif
                                        @if($chart_of_account->type=="I")@lang('accounts.income') @endif
                                        @if($chart_of_account->type=="L")@lang('accounts.liability') @endif

                                        {{-- {{@$chart_of_account->type == "I"? 'Income':'Expense'}} --}}
                                    </td>
                                    <td>
                                        <x-drop-down>
                                               @if(userPermission('chart-of-account-edit'))

                                                <a class="dropdown-item" href="{{route('chart-of-account-edit', [@$chart_of_account->id])}}">@lang('common.edit')</a>
                                               @endif
                                               @if(userPermission("chart-of-account-delete"))

                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteChartOfAccountModal{{@$chart_of_account->id}}"
                                                    href="#">@lang('common.delete')</a>
                                           @endif
                                        </x-drop-down>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteChartOfAccountModal{{@$chart_of_account->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('accounts.delete_chart_of_account')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('chart-of-account-delete',@$chart_of_account->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                     {{ Form::close() }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')