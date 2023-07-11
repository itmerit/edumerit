@extends('backEnd.master')
@section('title') 
@lang('academics.class_room')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('academics.class_room')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('academics.academics')</a>
                <a href="#">@lang('academics.class_room')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($class_room))
          @if(userPermission("class-room-store"))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('class-room')}}" class="primary-btn small fix-gr-bg">
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
                                @if(isset($class_room))
                                    @lang('academics.edit_class_room')
                                 @else
                                    @lang('academics.add_class_room')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($class_room))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('class-room-update',@$class_room->id), 'method' => 'PUT']) }}
                        @else
                        @if(userPermission("class-room-store"))
           
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'class-room', 'method' => 'POST']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('academics.room_no') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('room_no') ? ' is-invalid' : '' }}" type="text" name="room_no" autocomplete="off" value="{{isset($class_room)? $class_room->room_no: old('room_no')}}">
                                            <input type="hidden" name="id" value="{{isset($class_room)? $class_room->id: ''}}">
                                           
                                            
                                            @if ($errors->has('room_no'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('room_no') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        
                                         <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('academics.capacity') <span class="text-danger"> *</span></label>
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control{{ $errors->has('capacity') ? ' is-invalid' : '' }}" type="text" name="capacity" autocomplete="off" value="{{isset($class_room)? $class_room->capacity: old('capacity')}}">
                                           
                                            
                                            @if ($errors->has('capacity'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('capacity') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php 
                                  $tooltip = "";
                                  if(userPermission("class-room-store")){
                                        $tooltip = "";
                                    }elseif(userPermission("class-room-edit") && isset($class_room)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($class_room))
                                                @lang('academics.update_class_room')
                                            @else
                                                @lang('academics.save_class_room')
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
                            <h3 class="mb-0">@lang('academics.class_room_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">

                                <thead>
                                   
                                    <tr>
                                        <th>@lang('academics.room_no')</th>
                                        <th>@lang('academics.capacity')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($class_rooms as $class_room)
                                    <tr>
                                        <td valign="top">{{ @$class_room->room_no}}</td>
                                        <td class="pl-4" valign="top">{{ @$class_room->capacity}}</td>
                                        
                                        <td valign="top">
                                            <x-drop-down>
                                                    @if(userPermission("class-room-edit"))
                                                    <a class="dropdown-item" href="{{route('class-room-edit',$class_room->id)}}">@lang('common.edit')</a>
                                                   @endif
                                                    @if(userPermission("class-room-delete"))
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteClassRoomModal{{ @$class_room->id }}"  href="#">@lang('common.delete')</a>
                                                @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deleteClassRoomModal{{ @$class_room->id }}" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('academics.delete_class_room')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
    
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
    
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                        {{ Form::open(['route' => array('class-room-delete',$class_room->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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