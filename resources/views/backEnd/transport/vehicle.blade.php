@extends('backEnd.master')
@section('title')
@lang('transport.vehicle')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1> @lang('transport.vehicle')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('transport.transport')</a>
                <a href="#">@lang('transport.vehicle')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($assign_vehicle))
        @if(userPermission("vehicle-store"))

        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('vehicle')}}" class="primary-btn small fix-gr-bg">
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
                                    @lang('transport.edit_vehicle')
                                @else
                                    @lang('transport.add_vehicle')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($assign_vehicle))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('vehicle-update',@$assign_vehicle->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                         @if(userPermission("vehicle-store"))

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'vehicle',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                       
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('transport.vehicle_number') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('vehicle_number') ? ' is-invalid' : '' }}"
                                                type="text" name="vehicle_number" autocomplete="off" value="{{isset($assign_vehicle)? @$assign_vehicle->vehicle_no:old('vehicle_number')}}">
                                            <input type="hidden" name="id" value="{{isset($assign_vehicle)? @$assign_vehicle->id: ''}}">
                                            
                                            
                                            @if ($errors->has('vehicle_number'))
                                            <span class="text-danger" >
                                                {{ $errors->first('vehicle_number') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div> 
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('transport.vehicle_model') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('vehicle_model') ? ' is-invalid' : '' }}"
                                                type="text" name="vehicle_model" autocomplete="off" value="{{isset($assign_vehicle)? @$assign_vehicle->vehicle_model:old('vehicle_model')}}">
                                            
                                            
                                            @if ($errors->has('vehicle_model'))
                                            <span class="text-danger" >
                                                {{ $errors->first('vehicle_model') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('transport.year_made')</label>
                                            <input class="primary_input_field form-control{{ $errors->has('year_made') ? ' is-invalid' : '' }}"
                                                type="text" onkeypress="return isNumberKey(event)" name="year_made" autocomplete="off" value="{{isset($assign_vehicle)? @$assign_vehicle->made_year:old('year_made')}}">
                                           
                                            
                                            @if ($errors->has('year_made'))
                                            <span class="text-danger" >
                                                {{ $errors->first('year_made') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                          

                            <div class="row mt-15">
                                <div class="col-lg-12">
                                    <label class="primary_input_label" for="">@lang('transport.driver') <span class="text-danger"> *</span> </label>
                                    <select class="primary_select form-control {{ $errors->has('driver_id') ? ' is-invalid' : '' }}" id="select_class" name="driver_id">
                                        <option data-display="@lang('transport.select_driver') *" value="">@lang('transport.select_driver') *</option>
                                        @foreach($drivers as $driver)
                                        <option value="{{$driver->id}}" {{isset($assign_vehicle)? (@$assign_vehicle->driver_id == @$driver->id? 'selected':''):''}} > {{@$driver->full_name}}</option>

                                        @endforeach
                                    </select>
                                    @if ($errors->has('driver_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('driver_id') }}
                                    </span>
                                    @endif
                                </div>
                            </div>


                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('transport.note')</label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="4" name="note">{{isset($assign_vehicle)? @$assign_vehicle->note:old('note')}}</textarea>
                                           
                                            
                                        </div>
                                    </div>
                                </div>
                                @php 
                                  $tooltip = "";
                                  if(userPermission("vehicle-store") || userPermission('vehicle-delete')){
                                        @$tooltip = "";
                                    }else{
                                        @$tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                         <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($assign_vehicle))
                                                @lang('transport.update_vehicle')
                                            @else
                                                @lang('transport.save_vehicle')
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
                            <h3 class="mb-0">  @lang('transport.vehicle_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                                
                                <tr>
                                    <th> @lang('transport.vehicle_no')</th>
                                    <th> @lang('transport.model_no')</th>
                                    <th> @lang('transport.year_made')</th>
                                    <th> @lang('transport.driver_name')</th>
                                    <th> @lang('transport.driver_license')</th>
                                    <th> @lang('common.phone')</th>
                                    <th> @lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($assign_vehicles as $assign_vehicle)
                                <tr>
                                    <td>{{@$assign_vehicle->vehicle_no}}</td>
                                    <td>{{@$assign_vehicle->vehicle_model}}</td>
                                    <td>{{@$assign_vehicle->made_year}}</td>
                                    <td>{{(empty(@$assign_vehicle->driver->full_name))?'-':@$assign_vehicle->driver->full_name}}   </td> 

                                    <td>{{(empty(@$assign_vehicle->driver->driving_license))?'-':@$assign_vehicle->driver->driving_license}}   </td> 
                                    <td>{{(empty(@$assign_vehicle->driver->mobile))?'-':@$assign_vehicle->driver->mobile}}   </td> 

                                    <td>
                                        <x-drop-down>

                                                @if(userPermission('vehicle-edit'))
                                                <a class="dropdown-item" href="{{route('vehicle-edit', [@$assign_vehicle->id])}}"> @lang('common.edit')</a>
                                                @endif
                                               
                                                @if(userPermission('vehicle-delete'))
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteRoomTypeModal{{@$assign_vehicle->id}}"
                                                    href="#"> @lang('common.delete')</a>
                                                @endif
                                                </x-drop-down>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteRoomTypeModal{{@$assign_vehicle->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"> @lang('transport.delete_vehicle')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4> @lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal"> @lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('vehicle-delete',@$assign_vehicle->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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