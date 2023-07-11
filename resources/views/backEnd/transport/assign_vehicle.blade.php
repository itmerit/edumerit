@extends('backEnd.master')
@section('title')
@lang('transport.assign_vehicle')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-25 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('transport.assign_vehicle')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('transport.transport')</a>
                <a href="#">@lang('transport.assign_vehicle')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($assign_vehicle))
         @if(userPermission("assign-vehicle-store") )

        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('assign-vehicle')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($assign_vehicle))
                                    @lang('transport.edit_assign_vehicle')
                                @else
                                    @lang('transport.add_assign_vehicle')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($assign_vehicle))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('assign-vehicle-update',@$assign_vehicle->id), 'method' => 'PUT']) }}
                        @else
                         @if(userPermission("assign-vehicle-store") )

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'assign-vehicle', 'method' => 'POST']) }}
                        @endif
                        @endif
                        <input type="hidden" name="id" value="{{isset($assign_vehicle)? @$assign_vehicle->id:''}}">
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        

                                        <select class="primary_select  form-control{{ $errors->has('route') ? ' is-invalid' : '' }}" name="route">
                                            <option data-display="@lang('transport.select_route') *" value="">@lang('transport.select_route') *</option>
                                            @foreach($routes as $routes)
                                                @if(isset($assign_vehicle))
                                                    <option value="{{@$routes->id}}" {{@$assign_vehicle->route_id == @$routes->id? 'selected':''}}>{{@$routes->title}}</option>
                                                @else
                                                    <option value="{{@$routes->id}}">{{@$routes->title}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('route'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('route') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('transport.vehicle') <span class="text-danger"> *</span></label>
                                        @foreach($vehicles as $vehicle)
                                            @if(isset($assign_vehicle))
                                                <div class="">
                                                    <input type="radio" id="vehicle{{@$vehicle->id}}" class="common-checkbox" name="vehicles[]" value="{{@$vehicle->id}}" {{in_array(@$vehicle->id, @$vehiclesIds)? 'checked': ''}}>
                                                    <label for="vehicle{{@$vehicle->id}}">{{@$vehicle->vehicle_no}}</label>
                                                </div>
                                            @else
                                                <div class="">
                                                    <input type="radio" id="vehicle{{@$vehicle->id}}" class="common-checkbox" name="vehicles[]" value="{{@$vehicle->id}}">
                                                    <label for="vehicle{{@$vehicle->id}}">{{@$vehicle->vehicle_no}}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if($errors->has('vehicles'))
                                            <span class="text-danger validate-textarea-checkbox" role="alert">
                                                {{ $errors->first('vehicles') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @php 
                                  $tooltip = "";
                                  if(userPermission("assign-vehicle-store")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-15">
                                    <div class="col-lg-12 text-center">
                                         <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($assign_vehicle))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
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
                            <h3 class="mb-0">@lang('transport.assign_vehicle_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                             
                                <tr>
                                    <th>@lang('transport.route')</th>
                                    <th>@lang('transport.vehicle')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($assign_vehicles as $assign_vehicle)
                                <tr>
                                    <td valign="top">{{@$assign_vehicle->route !=""? @$assign_vehicle->route->title:""}}</td>
                                    <td>
                                    {{@$assign_vehicle->vehicle !=""? @$assign_vehicle->vehicle->vehicle_no:""}}
                                    </td>
                                    
                                    <td valign="top">
                                        <x-drop-down>

                                               @if(userPermission('assign-vehicle-edit'))
                                                <a class="dropdown-item" href="{{route('assign-vehicle-edit',@$assign_vehicle->id)}}">@lang('common.edit')</a>
                                                @endif
                                               
                                                @if(userPermission('assign-vehicle-delete'))
                                                <a class="dropdown-item deleteAssignVehicle" data-toggle="modal" href="#" data-id="{{@$assign_vehicle->id}}" data-target="#deleteAssignVehicle">@lang('common.delete')</a>
                                           @endif
                                        </x-drop-down>
                                    </td>
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

<div class="modal fade admin-query" id="deleteAssignVehicle" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('transport.delete_assign_vehicle')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-15 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'assign-vehicle-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" id="assign_vehicle_id" >
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@include('backEnd.partials.data_table_js')