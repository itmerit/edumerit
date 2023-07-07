@extends('backEnd.master')
    @section('title')
        @lang('transport.transport_route')
    @endsection
@section('mainContent')
@php  
    $setting = app('school_info');
    if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } 
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('transport.transport_route')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('transport.transport')</a>
                <a href="#">@lang('transport.transport_route')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($route))
            @if(userPermission("transport-route-store"))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('transport-route')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">
                                @if(isset($route))
                                    @lang('transport.edit_route')
                                @else
                                    @lang('transport.add_route')
                                @endif
                                
                            </h3>
                        </div>
                        @if(isset($route))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('transport-route-update',@$route->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                            @if(userPermission("transport-route-store"))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'transport-route', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12"> 
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('transport.route_title') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field " type="text" name="title" autocomplete="off" value="{{isset($route)? @$route->title:old('title')}}">
                                            <input type="hidden" name="id" value="{{isset($route)? @$route->id: ''}}">
                                           
                                            
                                            @if ($errors->has('title'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('title') }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('transport.fare') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('far') ? ' is-invalid' : '' }}" type="number" step="0.1" name="far" autocomplete="off" value="{{isset($route)? @$route->far:old('far')}}">
                                          
                                            
                                            @if ($errors->has('far'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('far') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $tooltip = "";
                                    if(userPermission("transport-route-store") || userPermission("transport-route-edit")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                                @if(isset($route))
                                                    @lang('transport.update_route')
                                                @else
                                                    @lang('transport.save_route')
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
                            <h3 class="mb-0">  @lang('transport.route_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('common.sl')</th>
                                    <th> @lang('transport.route_title')</th>
                                    <th> @lang('transport.fare') ({{generalSetting()->currency_symbol}})</th>
                                    <th> @lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($routes as $key=>$route)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{@$route->title}}</td>
                                    <td>{{number_format((float)@$route->far, 2, '.', '')}}</td>
                                    <td>
                                        <x-drop-down>
                                                @if(userPermission('transport-route-edit'))
                                                    <a class="dropdown-item" href="{{route('transport-route-edit', [@$route->id])}}"> @lang('common.edit')</a>
                                                @endif
                                                @if(userPermission('transport-route-delete'))
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteRouteModal{{@$route->id}}" href="#"> @lang('common.delete')</a>
                                                @endif
                                        </x-drop-down>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteRouteModal{{@$route->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"> @lang('transport.delete_route')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4> @lang('common.are_you_sure_to_delete')</h4>
                                                </div>
                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal"> @lang('common.cancel')</button>
                                                    {{ Form::open(['route' => array('transport-route-delete',@$route->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                        <button class="primary-btn fix-gr-bg" type="submit"> @lang('common.delete')</button>
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