@extends('backEnd.master')
@section('title')
@lang('leave.apply_leave')
@endsection
@section('mainContent')
<style>
    .ti-calendar:before {
        position: absolute !important;
        top: 28px !important;
    }
    .input-right-icon button.primary-btn-small-input {
        top: 8px  !important;
        right: 12px  !important;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('leave.apply_leave')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('common.student')</a>
                <a href="#">@lang('leave.apply_leave')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor pl_22">
<div class="container-fluid p-0">
    <div class="row mb-30">

        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('leave.my_remaining_leaves')</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <x-table>
                    <table id="table_id" class="table" cellspacing="0" width="100%">

                        <thead>
                            
                            <tr>
                                <th>@lang('common.type')</th>
                                <th>@lang('leave.remaining_days')</th>
                                <th>@lang('leave.extra_taken')</th>
                                <th>@lang('leave.leave_taken')</th>
                                <th>@lang('leave.leave_days')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($my_leaves as $my_leave)
                            @php

                            $approved_leaves = App\SmLeaveRequest::approvedLeave($my_leave->id);
                                $remaining_days = $my_leave->days - $approved_leaves;
                                $extra_days= $remaining_days < 0? $approved_leaves - $my_leave->days:0;
                            @endphp
                            <tr>
                                <td>{{$my_leave->leaveType !=""?$my_leave->leaveType->type:""}}</td>
                                <td>{{$remaining_days >= 0? $remaining_days:0}}</td>

                                <td>{{$remaining_days < 0? $approved_leaves - $my_leave->days:0}}</td>
                                <td>{{$approved_leaves}}</td>
                                <td>{{$my_leave->days}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
    @if(isset($apply_leave))
    @if(userPermission('student-apply-leave'))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('student-apply-leave')}}" class="primary-btn small fix-gr-bg">
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
                    <h3 class="mb-30">@if(isset($apply_leave))
                            @lang('leave.edit_apply_leave')
                        @else
                            @lang('leave.add_apply_leave')
                        @endif
                        
                    </h3>
                </div>
                @if(isset($apply_leave))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('student-leave-update',$apply_leave->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                @else
                    @if(userPermission('student-leave-store'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-leave-store','method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    @endif
                @endif
                <div class="white-box">
                    <div class="add-visitor">
                        <div class="row no-gutters input-right-icon">
                            
                            <div class="col">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('leave.apply_date')<span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('apply_date') ? ' is-invalid' : '' }}" id="apply_date" type="text"
                                        name="apply_date" value="{{isset($apply_leave)? date('m/d/Y', strtotime($apply_leave->apply_date)) : date('m/d/Y')}}">
                                    
                                     @if ($errors->has('apply_date'))
                                    <span class="text-danger" >
                                        {{ $errors->first('apply_date') }}
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <label class="m-0 p-0" for="apply_date">
                                        <i class="ti-calendar" id="apply_date_icon"></i>
                                    </label>
                                </button>
                            </div>
                           
                        </div>
                        <input type="hidden" name="id" value="{{isset($apply_leave)? $apply_leave->id: ''}}">
                        <div class="row mt-25">
                            <div class="col-lg-12">
                                <select class="primary_select  form-control{{ $errors->has('leave_type') ? ' is-invalid' : '' }}" name="leave_type">
                                    <option data-display="@lang('leave.leave_type') *" value="">@lang('leave.leave_type') *</option>
                                    @foreach($leave_types as $leave_type)
                                        <option value="{{$leave_type->id}}" {{isset($apply_leave)? ($apply_leave->leave_define_id == $leave_type->id? 'selected':''):''}}>{{$leave_type->leaveType->type}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('leave_type'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('leave_type') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="row no-gutters input-right-icon mt-25">
                            <div class="col">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('leave.leave_from')<span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('leave_from') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                         name="leave_from"  autocomplete="off" value="{{isset($apply_leave)? date('m/d/Y', strtotime($apply_leave->leave_from)):date('m/d/Y')}}">
                                    
                                     @if ($errors->has('leave_from'))
                                    <span class="text-danger" >
                                        {{ $errors->first('leave_from') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <label class="m-0 p-0" for="startDate">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </label>
                                </button>
                            </div>

                        </div>
                        <div class="row no-gutters input-right-icon mt-25">
                            <div class="col">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('leave.leave_to')<span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('leave_to') ? ' is-invalid' : '' }}" id="leave_to" type="text" name="leave_to" value="{{isset($apply_leave)? date('m/d/Y', strtotime($apply_leave->leave_to)):date('m/d/Y')}}">
                                    
                                     @if ($errors->has('leave_to'))
                                    <span class="text-danger" >
                                        {{ $errors->first('leave_to') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <label class="m-0 p-0" for="leave_to">
                                        <i class="ti-calendar" id="to-date-icon"></i>
                                    </label>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-25">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('leave.reason')</label>
                                    <textarea class="primary_input_field form-control" cols="0" rows="4" name="reason">{{isset($apply_leave)? $apply_leave->reason: ''}}</textarea>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters input-right-icon mt-25">
                            <div class="col">
                                <div class="primary_input">
                                    <input class="primary_input_field" type="text" 
                                    placeholder="{{isset($apply_leave->file) && $apply_leave->file != ""? getFilePath3($apply_leave->file):'Attach File'}}"
                                    disabled id="placeholderAttachFile">
                                    

                                    @if ($errors->has('file'))
                                        <span class="text-danger d-block" >
                                            <strong>{{ @$errors->first('file') }}
                                        </span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="primary-btn-small-input" type="button">
                                    <label class="primary-btn small fix-gr-bg" for="attach_file">@lang('common.browse')</label>
                                    <input type="file" class="d-none" name="attach_file" id="attach_file">
                                </button>
                            </div>
                        </div>
                          @php 
                                  $tooltip = "";
                                  if(userPermission('student-leave-store')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                            @endphp
                        <div class="row mt-10 mb-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                    <span class="ti-check"></span>
                                    @if(isset($apply_leave))
                                        @lang('leave.update_apply_leave')
                                    @else
                                        @lang('leave.save_apply_leave')
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
                    <h3 class="mb-0">@lang('leave.leave_list') </h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <x-table>
                    <table id="table_id" class="table" cellspacing="0" width="100%">

                        <thead>
                          
                            <tr>
                                <th>@lang('common.type')</th>
                                <th>@lang('common.from')</th>
                                <th>@lang('common.to')</th>
                                <th>@lang('leave.apply_date')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('common.action')</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            @foreach($apply_leaves as $apply_leave)
                            <tr>
                                <td>
                                    @if($apply_leave->leaveDefine != "" && $apply_leave->leaveDefine->leaveType !="")
                                        {{$apply_leave->leaveDefine->leaveType->type}}
                                    @endif
                                </td>
                                <td  data-sort="{{strtotime($apply_leave->leave_from)}}" >
                                 {{$apply_leave->leave_from != ""? dateConvert($apply_leave->leave_from):''}}
    
                                </td>
                                <td  data-sort="{{strtotime($apply_leave->leave_to)}}" >
                                   {{$apply_leave->leave_to != ""? dateConvert($apply_leave->leave_to):''}}
    
                                </td>
                                <td  data-sort="{{strtotime($apply_leave->apply_date)}}" >
                                  {{$apply_leave->apply_date != ""? dateConvert($apply_leave->apply_date):''}}
    
                                </td>
                                <td>
                                @if($apply_leave->approve_status == 'P')
                                <button class="primary-btn small bg-warning  text-white border-0 tr-bg">@lang('common.pending')</button>@endif
                                @if($apply_leave->approve_status == 'A')
                                <button class="primary-btn small bg-success  text-white border-0 tr-bg">@lang('common.approved')</button>
                                @endif
                                @if($apply_leave->approve_status == 'C')
                                <button class="primary-btn small bg-danger text-white border-0">@lang('leave.cancelled')</button>
                                @endif
                                </td>
                                <td>
                                    <div class="dropdown CRM_dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            @lang('common.select')
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
    
                                            @if(userPermission(93))
    
                                            <a data-modal-size="modal-lg" title="View Leave Details" class="dropdown-item modalLink" href="{{route('view-leave-details-apply', $apply_leave->id)}}">@lang('common.view')</a>
    
                                            @endif
                                            @if($apply_leave->approve_status == 'P')
                                            @if(userPermission(42))
                                            <a class="dropdown-item" href="{{route('student-leave-edit', [$apply_leave->id
                                                ])}}">@lang('common.edit')</a> 
    
                                            @endif
                                            @if(userPermission(94))
    
                                             <a class="dropdown-item" data-toggle="modal" data-target="#deleteApplyLeaveModal{{$apply_leave->id}}"
                                                href="#">@lang('common.delete')</a>
                                            @endif
                                            @endif
                                            @if($apply_leave->file != "")
                                                <a class="dropdown-item" href="{{url(@$apply_leave->file)}}" download>
                                                    @lang('leave.download') 
                                                    <span class="pl ti-download"></span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade admin-query" id="deleteApplyLeaveModal{{$apply_leave->id}}" >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('leave.delete_apply_leave')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
    
                                        <div class="modal-body">
                                            <div class="text-center">
                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                            </div>
    
                                            <div class="mt-40 d-flex justify-content-between">
                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                 {{ Form::open(['route' => array('apply-leave-delete',$apply_leave->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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
@include('backEnd.partials.date_picker_css_js')