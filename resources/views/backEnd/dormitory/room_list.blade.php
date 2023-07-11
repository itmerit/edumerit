@extends('backEnd.master')
@section('title')
@lang('dormitory.dormitory_rooms')
@endsection
@section('mainContent')
<style>
    .check_box_table table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child::before, .check_box_table table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child::before {
    top: 30px !important;
}
</style>
@php  $setting = app('school_info');
 if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } 
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('dormitory.dormitory_rooms')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('dormitory.dormitory')</a>
                <a href="#">@lang('dormitory.dormitory_rooms')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($room_list))
        @if(userPermission("room-list-store"))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('room-list')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($room_list))
                                    @lang('dormitory.edit_dormitory_rooms')
                                @else
                                    @lang('dormitory.add_dormitory_rooms')
                                @endif
                             
                            </h3>
                        </div>
                        @if(isset($room_list))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('room-list-update',$room_list->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                         @if(userPermission("room-list-store"))

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'room-list',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('dormitory.dormitory') <span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ $errors->has('dormitory') ? ' is-invalid' : '' }}" name="dormitory">
                                            <option data-display="@lang('dormitory.dormitory') *" value="">@lang('dormitory.dormitory') *</option>
                                            @foreach($dormitory_lists as $dormitory_list)
                                                @if(isset($room_list))
                                                <option value="{{@$dormitory_list->id}}" {{@$dormitory_list->id == @$room_list->dormitory_id? 'selected': ''}}>{{@$dormitory_list->dormitory_name}}</option>
                                                @else
                                                <option value="{{@$dormitory_list->id}}" {{old('dormitory') == @$dormitory_list->id? 'selected':''}}>{{@$dormitory_list->dormitory_name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('dormitory'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('dormitory') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('dormitory.room_number') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off" value="{{isset($room_list)? $room_list->name: old('name')}}">
                                            <input type="hidden" name="id" value="{{isset($room_list)? $room_list->id: ''}}">
                                            
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('common.type') <span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ $errors->has('room_type') ? ' is-invalid' : '' }}" name="room_type">
                                            <option data-display="@lang('dormitory.room_type') *" value="">@lang('dormitory.room_type') *</option>
                                            @foreach($room_types as $room_type)
                                                 @if(isset($room_list))
                                                <option value="{{@$room_type->id}}" {{@$room_type->id == @$room_list->room_type_id? 'selected': ''}}>{{ @$room_type->type}}</option>
                                                @else
                                                <option value="{{@$room_type->id}}" {{old('room_type') == @$room_type->id? 'selected':''}}>{{@$room_type->type}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('room_type'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('room_type') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('dormitory.number_of_bed') <span class="text-danger"> *</span></label>
                                            <input oninput="numberCheck(this)" class="primary_input_field form-control{{ $errors->has('number_of_bed') ? ' is-invalid' : '' }}" type="text" name="number_of_bed" value="{{isset($room_list)? $room_list->number_of_bed: old('number_of_bed')}}">
                                           
                                            
                                            @if ($errors->has('number_of_bed'))
                                        <span class="text-danger" >
                                            {{ $errors->first('number_of_bed') }}
                                        </span>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('dormitory.cost_per_bed')<span class="text-danger"> *</span></label>
                                            <input oninput="numberCheck(this)" class="primary_input_field form-control{{ $errors->has('cost_per_bed') ? ' is-invalid' : '' }}" type="text" step="0.1" name="cost_per_bed" value="{{isset($room_list)? $room_list->cost_per_bed: old('cost_per_bed')}}">
                                            
                                            
                                            @if ($errors->has('cost_per_bed'))
                                        <span class="text-danger" >
                                            {{ $errors->first('cost_per_bed') }}
                                        </span>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description') <span></span></label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="4" name="description">{{isset($room_list)? $room_list->description: old('description')}}</textarea>
                                          
                                            
                                        </div>
                                    </div>
                                </div>
                                 @php 
                                  $tooltip = "";
                                  if(userPermission("room-list-store") || userPermission('room-list-edit')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{ @$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($room_list))
                                                @lang('dormitory.update_room')
                                            @else
                                                @lang('dormitory.save_room')
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
                            <h3 class="mb-0"> @lang('dormitory.dormitory_room_list')</h3>
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
                                    <th>@lang('dormitory.dormitory')</th>
                                    <th>@lang('dormitory.room_number')</th>
                                    <th>@lang('dormitory.room_type')</th>
                                    <th>@lang('dormitory.no_of_bed')</th>
                                    <th>@lang('dormitory.cost_per_bed') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($room_lists as $key=>$room_list)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($room_list->dormitory->dormitory_name)? $room_list->dormitory->dormitory_name:''}}</td>
                                    <td>{{ @$room_list->name}}</td>
                                    <td>{{isset($room_list->roomType->type)? $room_list->roomType->type: ''}}</td>
                                    <td>{{ @$room_list->number_of_bed}}</td>
                                    <td>{{ @$room_list->cost_per_bed}}</td>
                                    <td>
                                        <x-drop-down>
                                                @if(userPermission('room-list-edit'))

                                                <a class="dropdown-item" href="{{route('room-list-edit', [$room_list->id])}}">@lang('common.edit')</a>
                                                @endif
                                                @if(userPermission('room-list-delete'))

                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteRoomTypeModal{{$room_list->id}}"
                                                    href="#">@lang('common.delete')</a>
                                            @endif
                                        </x-drop-down>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteRoomTypeModal{{@$room_list->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('dormitory.delete_room')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('room-list-delete',$room_list->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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