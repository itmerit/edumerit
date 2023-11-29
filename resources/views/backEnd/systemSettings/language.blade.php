@extends('backEnd.master')
@section('title')
@lang('system_settings.language')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.language')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.language')</a>

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
           
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@if(isset($editData))
                                        @lang('system_settings.edit_language')
                                    @else
                                        @lang('system_settings.add_language')
                                    @endif
                                    
                                </h3>
                            </div>
                            @if(isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'language_update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{isset($editData)? @$editData->id: ''}}">
                            @else
                                @if(userPermission('language_store'))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'language_store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row"> 
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span></label>
                                                <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" autocomplete="off" value="{{isset($editData)? @$editData->name: ''}}" maxlength="25" >                                            
                                                
                                                
                                                @if ($errors->has('name'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-15"> 
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.code') <span class="text-danger"> *</span></label>
                                                <input class="primary_input_field form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" type="text" name="code" autocomplete="off" value="{{isset($editData)? @$editData->code: ''}}" maxlength="191" >                                            
                                               
                                                
                                                @if ($errors->has('code'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('code') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-15"> 
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.native') <span class="text-danger"> *</span></label>
                                                <input class="primary_input_field form-control{{ $errors->has('native') ? ' is-invalid' : '' }}" type="text" name="native" autocomplete="off" value="{{isset($editData)? @$editData->native: ''}}" maxlength="191" >                                            
                                               
                                                
                                                @if ($errors->has('native'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('native') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-15"> 
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.text_alignment') <span class="text-danger"> *</span></label>
                                                <select class="primary_select form-control {{ $errors->has('rtl') ? ' is-invalid' : '' }}" id="rtl" name="rtl">
                                                    <option value="0" @if(isset($editData) && $editData->rtl == 0 ) selected @endif>LTL</option>
                                                    <option value="1">RTL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
 
                                    @php 
                                        $tooltip = "";
                                        if(userPermission('language_store') || userPermission('language_edit')){
                                                $tooltip = "";
                                            }else{
                                                $tooltip = "You have no permission to add";
                                            }
                                    @endphp
                                    <div class="row mt-15">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($editData))
                                                    @lang('system_settings.update_language')
                                                @else
                                                    @lang('system_settings.save_language')
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
                                <h3 class="mb-30">@lang('system_settings.language_list')</h3>
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
                                        <th>@lang('common.name')</th>
                                        <th>@lang('system_settings.code')</th>
                                        <th>@lang('system_settings.native')</th> 
                                        <th>@lang('system_settings.text_alignment')</th> 
                                        <th>@lang('common.action')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @php $i=1;  @endphp

                                    @foreach($languages as $value)
                                        <tr>
                                            <td>{{$i++}}
                                            <td>{{@$value->name}}</td>
                                            <td>{{@$value->code}}</td>
                                            <td>{{@$value->native}}</td>
                                            <td>
                                                @if($value->rtl == 1) 
                                                RTL
                                                @else 
                                                LTL  
                                                @endif 
                                            </td> 
                                            <td>

                                            <x-drop-down>
                                                    @if(userPermission('language_edit'))
                                                        <a class="dropdown-item" href="{{route('language_edit', [@$value->id])}}">@lang('common.edit')</a>
                                                    @endif
                                                    @if(userPermission('language_delete'))
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteCurrency{{@$value->id}}"  href="{{route('currency_delete', [@$value->id])}}">@lang('common.delete')</a>
                                                    @endif
                                            </x-drop-down>
                                            </td>

                                                <div class="modal fade admin-query" id="deleteCurrency{{@$value->id}}" >
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('system_settings.delete_language')</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                </div>
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                                    <a href="{{route('language_delete', [@$value->id])}}" class="text-light">
                                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div> 
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

@endsection
@include('backEnd.partials.data_table_js')