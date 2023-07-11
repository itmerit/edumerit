@extends('backEnd.master')
@section('title')
@lang('system_settings.manage_currency')
@endsection 
@push('css')
    <style>
        .badge{
            background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
            color: #fff;
            padding: 5px 10px;
            border-radius: 30px;
            display: inline-block;
            font-size: 8px;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.currency')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.currency')</a>
                    <a href="#">@lang('system_settings.manage_currency')</a>

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('create-currency')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                </div>
            </div>           
          
            <div class="row"> 
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3>@lang('system_settings.currency_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="table_id_info" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('system_settings.code')</th>
                                        <th>@lang('system_settings.symbol')</th> 
                                        <th>@lang('common.type')</th>
                                        <th>@lang('system_settings.currency_position')</th> 
                                        <th>@lang('system_settings.space')</th> 
                                        <th>@lang('system_settings.decimal_digit')</th> 
                                        <th>@lang('system_settings.decimal_separator')</th> 
                                        <th>@lang('system_settings.thousand_separator')</th> 
                                        <th>@lang('common.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i=1;  @endphp

                                    @foreach($currencies as $value)
                                        <tr>
                                            <td>{{$i++}}
                                            <td>{{@$value->name}} 
                                                @if($value->active) 
                                                    <span class="badge fix-gr-bg">{{ __('common.active') }}</span>
                                                @endif
                                            </td>
                                            <td>{{@$value->code}}</td>
                                            <td>{{@$value->symbol}}</td> 
                                            <td>{{@$value->type }}</td>
                                            <td>{{@$value->position}}</td>
                                            <td>{{@$value->space ? __('common.yes') : __('common.no')}}</td> 
                                            <td>{{@$value->decimal_digit}}</td>
                                            <td>{{@$value->decimal_separator}}</td>
                                            <td>{{@$value->thousand_separator}}</td> 
                                            <td>

                                            <x-drop-down>
                                                    @if(userPermission('currency_edit'))
                                                        <a class="dropdown-item" href="{{route('currency_edit', [@$value->id])}}">@lang('common.edit')</a>
                                                    @endif
                                                    @if(userPermission('currency_delete'))
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteCurrency{{@$value->id}}"  href="{{route('currency_delete', [@$value->id])}}">@lang('common.delete')</a>
                                                    @endif
                                                    @if(in_array(auth()->user()->role_id, [1, 5]))
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#activeCurrency{{@$value->id}}"  href="{{route('currency_delete', [@$value->id])}}">@lang('common.active')</a>
                                                    @endif
                                            </x-drop-down>
                                            </td>

                                                <div class="modal fade admin-query" id="activeCurrency{{@$value->id}}" >
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('system_settings.active_currency')</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('system_settings.are_you_sure_to_active ?') </h4>
                                                                </div>
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                                    <a href="{{route('currency_active', [@$value->id])}}" class="text-light">
                                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.active')</button>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="modal fade admin-query" id="deleteCurrency{{@$value->id}}" >
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('system_settings.delete_currency')</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                </div>
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                                    <a href="{{route('currency_delete', [@$value->id])}}" class="text-light">
                                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div> 
                                        </tr>
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