@extends('backEnd.master')
@section('title') 
@lang('fees.fees_type')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.fees_type')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('fees.fees')</a>
                @if(isset($feesType))
                    <a href="#">@lang('fees.edit_fees_type')</a>
                @else
                    <a href="#">@lang('fees.fees_type')</a>
                @endif
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($feesType))
            @if(userPermission('fees.fees-type-store'))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('fees.fees-type')}}" class="primary-btn small fix-gr-bg">
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
                                @if(isset($feesType))
                                    @lang('fees.edit_fees_type')
                                @else
                                    @lang('fees.add_fees_type')
                                @endif
                                 
                            </h3>
                        </div>
                        @if(isset($feesType))
                            {{ Form::open(['class' => 'form-horizontal','route' => 'fees.fees-type-update', 'method' => 'POST']) }}
                            <input type="hidden" name="id" value="{{isset($feesType)? $feesType->id: ''}}">
                        @else
                            @if(userPermission('fees.fees-type-store'))
                                {{ Form::open(['class' => 'form-horizontal', 'route' => 'fees.fees-type-store', 'method' => 'POST']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" autocomplete="off" value="{{isset($feesType)? $feesType->name: old('name')}}">
                                            
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- {{old('fees_group')}} --}}
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">
                                            {{ __('fees.fees_group') }}
                                                <span class="text-danger"> *</span>
                                        </label>
                                        <select class="primary_select  form-control{{ $errors->has('fees_group') ||  session()->has('message-exist')? ' is-invalid' : '' }}" name="fees_group" id="fees_group" {{isset($fees_master)? 'disabled': ''}}>
                                            <option data-display="@lang('fees.fees_group')" value="">@lang('fees.fees_group')</option>
                                            @foreach($feesGroups as $feesGroup)
                                                @if(isset($feesType))
                                                    <option value="{{$feesGroup->id}}"{{$feesGroup->id == $feesType->fees_group_id? 'selected':''}}>{{$feesGroup->name}} </option>
                                                @else
                                                    <option value="{{$feesGroup->id}}"  {{old('fees_group')!=''? (old('fees_group') == $feesGroup->id? 'selected':''):''}} >{{$feesGroup->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if (session()->has('message-exist'))
                                        <span class="text-danger invalid-select" role="alert">
                                            <strong>{{ session()->get('message-exist') }}
                                        </span>
                                        @endif
                                        @if ($errors->has('fees_group'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('fees_group') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description') <span></span></label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="4"
                                                name="description">{{isset($feesType)? $feesType->description: old('description')}}</textarea>
                                                
                                            
                                        </div>
                                    </div>
                                </div>
                                @php
                                $tooltip = "";
                                if(userPermission("fees.fees-type-store")){
                                      $tooltip = "";
                                  }elseif(isset($feesType) && userPermission('fees.fees-type-edit')){
                                    $tooltip = "";
                                  }else{
                                      $tooltip = "You have no permission to add";
                                  }
                            @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                         <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                         title="{{$tooltip}}">
                                            <span class="ti-check">
                                            </span>
                                            @if(userPermission('fees.fees-type-edit') && userPermission('fees.fees-type-store'))
                                                @if(isset($feesType))
                                                    @lang('common.update')
                                                @else
                                                    @lang('common.save')
                                                @endif
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
                            <h3 class="mb-0"> @lang('fees.fees_type_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th> @lang('common.name')</th>
                                        <th> @lang('fees.fees_group')</th>
                                        <th> @lang('common.description')</th>
                                        <th> @lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feesTypes as $feesType)
                                    <tr>
                                        <td>{{$feesType->name}}</td>
                                        <td>{{@$feesType->fessGroup->name}}</td>
                                        <td>{{@$feesType->description}}</td>
                                        <td>
                                            <x-drop-down>
                                                    @if(userPermission('fees.fees-type-edit'))
                                                        <a class="dropdown-item" href="{{route('fees.fees-type-edit', [$feesType->id])}}">@lang('common.edit')</a>
                                                    @endif
                                                    @if(userPermission("fees.fees-type-delete"))
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteFeesTypeModal{{$feesType->id}}" href="#">@lang('common.delete')</a>
                                                    @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>

                                    <div class="modal fade admin-query" id="deleteFeesTypeModal{{$feesType->id}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('fees.delete_fees_type')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                        {{ Form::open(['route' => 'fees.fees-type-delete', 'method' => 'POST',]) }}
                                                            <input type="hidden" name="id" value="{{$feesType->id}}">
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