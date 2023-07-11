@extends('backEnd.master')
@section('title')
@lang('system_settings.base_setup')
@endsection 
@push('css')
    <style>
        .base-setup .card .card-header .icon-only:before {
            position: relative !important;
            right: 2px !important;
        }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.base_setup')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.base_setup')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($base_setup))
            @if(userPermission('base_setup_store'))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('base_setup')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($base_setup))
                                    @lang('system_settings.edit_base_setup')
                                @else
                                    @lang('system_settings.add_base_setup')

                                @endif
                              
                            </h3>
                        </div>
                        @if(isset($base_setup))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'base_setup_update',
                        'method' => 'POST']) }}
                        @else
                            @if(userPermission('base_setup_store'))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'base_setup_store',
                            'method' => 'POST']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('system_settings.base_group') <span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ $errors->has('base_group') ? ' is-invalid' : '' }}"
                                            name="base_group">
                                            <option data-display="@lang('system_settings.base_group') *" value="">@lang('system_settings.base_group')*</option>
                                            @foreach($base_groups as $base_group)
                                            @if(isset($base_setup))
                                            <option value="{{@$base_group->id}}"
                                                {{@$base_group->id == @$base_setup->base_group_id? 'selected': ''}}>{{@$base_group->name}}</option>
                                            @else
                                            <option value="{{@$base_group->id}}">{{@$base_group->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @if($errors->has('base_group'))
                                        <span class="text-danger" >
                                            {{ $errors->first('base_group') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row  mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" value="{{isset($base_setup)? @$base_setup->base_setup_name: ''}}">
                                            <input type="hidden" name="id" value="{{isset($base_setup)? @$base_setup->id: ''}}">
                                            
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php 
                                  $tooltip = "";
                                  if(userPermission('base_setup_store')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($base_setup))
                                                @lang('system_settings.update_base_setup')
                                            @else
                                                @lang('system_settings.save_base_setup')
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
                            <h3 class="mb-0">@lang('system_settings.base_setup_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row base-setup">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    
                                    <tr>
                                        <th width="33%">@lang('system_settings.base_type')</th>
                                        <th width="33%">@lang('common.label')</th>
                                        <th width="33%">@lang('common.action')</th>
                                    </tr>
                                </thead>
    
                                <tbody>
    
                                    <tr>
                                        <td colspan="3" class="pr-0">
                                            <div id="accordion" role="tablist">
                                                @php $i = 0; @endphp
                                                @foreach($base_groups as $base_group)
    
                                                <div class="card mr-4">
                                                    <div class="card-header d-flex justify-content-between" role="tab" id="headingOne{{@$base_group->id}}">
                                                        <div class="row w-100 align-items-center">
                                                            <div class="col-lg-6">
                                                                <a data-toggle="collapse" href="#collapseOne{{@$base_group->id}}" aria-expanded="true" aria-controls="collapseOne{{@$base_group->id}}">
                                                                {{$base_group->name}}
                                                                </a>
                                                            </div>
                                                            <div class="col-lg-6 text-right">
                                                                <a class="primary-btn icon-only tr-bg" data-toggle="collapse" href="#collapseOne{{@$base_group->id}}" aria-expanded="true" aria-controls="collapseOne">
                                                                    <span class="ti-arrow-down"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                    @$base_setups = @$base_group->baseSetups;
                                                    @endphp
                                                    <div id="collapseOne{{@$base_group->id}}" class="collapse {{$i++ == 0? 'show':''}}" role="tabpanel" aria-labelledby="headingOne{{@$base_group->id}}" data-parent="#accordion">
                                                        <div class="card-body">
                                                            @foreach($base_setups as $base_setup)
                                                            <div class="row py-3 border-bottom align-items-center">
                                                                <div class="offset-lg-4 col-lg-4">{{@$base_setup->base_setup_name}}</div>
                                                                <div class="col-lg-4">
                                                                    <div class="dropdown CRM_dropdown">
                                                                        <button type="button" class="btn dropdown-toggle"
                                                                            data-toggle="dropdown">
                                                                            @lang('common.select')
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            @if(userPermission('base_setup_edit'))
                                                                                <a class="dropdown-item" href="{{route('base_setup_edit', [@$base_setup->id])}}">@lang('common.edit')</a>
                                                                            @endif
                                                                            @if(userPermission('base_setup_delete'))
                                                                                <a class="dropdown-item deleteBaseSetupModal" href="#" data-toggle="modal" data-target="#deleteBaseSetupModal" data-id="{{@$base_setup->id}}">@lang('common.delete')</a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
    
                                                @endforeach
                                                
                                            </div>
                                        </td>
                                        <td class="d-none p-0"></td>
                                        <td class="d-none p-0"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </x-table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<div class="modal fade admin-query" id="deleteBaseSetupModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('system_settings.delete_base_setup')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'base_setup_delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" value="" id="base_setup_id">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>



@endsection
@include('backEnd.partials.data_table_js')