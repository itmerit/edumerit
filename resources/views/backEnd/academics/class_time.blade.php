@extends('backEnd.master')
@section('title') 
@lang('lang.class_time_setup')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.class_time_setup')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('lang.academics')</a>
                <a href="#">@lang('lang.class_time_setup')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($class_time))
         @if(userPermission(274))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('class-time')}}" class="primary-btn small fix-gr-bg">
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
                                @if(isset($class_time))
                                    @lang('common.edit')
                                 @else
                                    @lang('common.add')
                                @endif
                                    @lang('lang.class_time')
                            </h3>
                        </div>
                        @if(isset($class_time))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('class-time-update',$class_time->id), 'method' => 'PUT']) }}
                        @else
                         @if(userPermission(274))
           
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'class-time', 'method' => 'POST']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row  ">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ @$errors->has('period') ? ' is-invalid' : '' }}" type="text" name="period" autocomplete="off" value="{{isset($class_time)? $class_time->period: ''}}">
                                            <input type="hidden" name="id" value="{{isset($class_time)? $class_time->id: ''}}">
                                            <label class="primary_input_label" for="">@lang('common.class_period') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('period'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('period') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters input-right-icon mt-25">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field primary_input_field time  form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}" type="text" name="start_time" value="{{isset($class_time)? $class_time->start_time: ''}}">
                                            <label class="primary_input_label" for="">@lang('lang.select_time') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('start_time'))
                                                <span class="text-danger" >
                                                    <strong>{{ @$errors->first('start_time') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="" type="button">
                                            <i class="ti-timer"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row no-gutters input-right-icon mt-25">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field primary_input_field time  form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}" type="text" name="end_time"  value="{{isset($class_time)? $class_time->end_time: ''}}">
                                                <label class="primary_input_label" for="">@lang('lang.end_time') <span class="text-danger"> *</span></label>
                                                
                                               @if ($errors->has('end_time'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('end_time') }}
                                                </span>
                                            @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-timer"></i>
                                            </button>
                                        </div>
                                </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                        
                                            <div class="primary_input">
                                                <input type="checkbox" id="isBreak" class="common-checkbox" value="1"
                                                 name="is_break"
                                                 {{isset($class_time)? ($class_time->is_break == 1? 'checked':''):''}}
                                                 >
                                                    <label for="isBreak">Is Break</label>
                                            </div>
                                        </div>
                                    </div>
	                           @php 
                                  $tooltip = "";
                                  if(userPermission(274)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($class_time))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif
                                            @lang('common.time')
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
                            <h3 class="mb-0">@lang('lang.time_list') </h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                               @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="3">
                                         @if(session()->has('message-success-delete'))
                                          <div class="alert alert-success">
                                              {{ session()->get('message-success-delete') }}
                                          </div>
                                        @elseif(session()->has('message-danger-delete'))
                                          <div class="alert alert-danger">
                                              {{ session()->get('message-danger-delete') }}
                                          </div>
                                        @endif
                                    </td>
                                </tr>
                                 @endif
                                <tr>
                                    <th>@lang('lang.time_type')</th>
                                    <th>@lang('common.class_period')</th>
                                    <th>@lang('lang.start_time')</th>
                                    <th>@lang('lang.end_time')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class_times as $class_time)
                                <tr>
                                    <td valign="top">{{@$class_time->type == 'exam'? 'Exam time':'Class time'}}</td>
                                    <td valign="top">{{@$class_time->period}}</td>
                                    <td valign="top">{{date('h:i A', strtotime(@$class_time->start_time))}}</td>
                                    <td valign="top">{{date('h:i A', strtotime(@$class_time->end_time))}}</td>
                                    
                                    <td valign="top">
                                        <x-drop-down/>
                                                @if(userPermission(275))
                                                <a class="dropdown-item" href="{{route('class-time-edit',$class_time->id)}}">@lang('common.edit')</a>
                                                @endif
                                                 @if(userPermission(276))
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteClassTime{{@$class_time->id}}"  href="#">@lang('common.delete')</a>
                                           @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteClassTime{{@$class_time->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('common.delete_class_time')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                    {{ Form::open(['route' => array('class-time-delete',@$class_time->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
