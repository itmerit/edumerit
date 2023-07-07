@extends('backEnd.master')
@section('title')
@lang('leave.leave_type')
@endsection
@section('mainContent')


<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('leave.leave_type')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('leave.leave')</a>
                <a href="#">@lang('leave.leave_type')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($leave_type))
        @if(userPermission('leave-type-store'))
                
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('leave-type')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($leave_type))
                                    @lang('leave.edit_leave_type')
                                @else
                                    @lang('leave.add_leave_type')
                                @endif 
                                
                            </h3>
                        </div>
                @if(isset($leave_type))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('leave-type-update',$leave_type->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                @else
                 @if(userPermission('leave-type-store'))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'leave-type',
                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                @endif
                @endif
                <div class="white-box">
                    <div class="add-visitor">
                        <div class="row">
                            <div class="col-lg-12">                               
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('leave.type_name') <span class="text-danger"> *</span> </label>
                                    <input class="primary_input_field form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                    type="text" name="type" autocomplete="off" value="{{isset($leave_type)? $leave_type->type:Request::old('type')}}">
                                  
                                    <input type="hidden" name="id" value="{{isset($leave_type)? $leave_type->id: ''}}">

                                    
                                    @if ($errors->has('type'))
                                    <span class="text-danger" >
                                        {{ $errors->first('type') }}
                                    </span>
                                    @endif
                                </div>
                              
                            </div>
                        </div>
                          @php 
                              $tooltip = "";
                              if(userPermission('leave-type-store')){
                                    $tooltip = "";
                                }else{
                                    $tooltip = "You have no permission to add";
                                }
                            @endphp
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                    <span class="ti-check"></span>

                                    @if(isset($leave_type))
                                        @lang('leave.update_type')
                                    @else
                                        @lang('leave.save_type')
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
                            <h3 class="mb-0">@lang('leave.leave_type_list')</h3>
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
                              
                                <th>@lang('common.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($leave_types as $leave_type)
                            <tr>
                                <td>{{$leave_type->type}}</td>
                               
                                <td>
                                    <x-drop-down>
                                            @if(userPermission('leave-type-edit'))

                                            <a class="dropdown-item" href="{{route('leave-type-edit', [$leave_type->id
                                                ])}}">@lang('common.edit')</a>
                                               @endif
                                               @if(userPermission('leave-type-delete'))

                                               <a class="dropdown-item" data-toggle="modal" data-target="#deleteLeaveTypeModal{{$leave_type->id}}"
                                                    href="#">@lang('common.delete')</a>
                                               @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deleteLeaveTypeModal{{$leave_type->id}}" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('leave.delete_leave_type')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>

                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                        {{ Form::open(['route' => array('leave-type-delete',$leave_type->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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