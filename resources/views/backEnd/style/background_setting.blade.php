@extends('backEnd.master')
@section('title')
@lang('style.background_settings')
@endsection
@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('style.background_settings')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('style.style')</a>
                    <a href="#">@lang('style.background_settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @lang('style.add_style')
                                </h3>
                            </div>
                            @if(isset($visitor))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'background-settings-update',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                                @if(userPermission('background-settings-store'))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'background-settings-store',
                                    'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('style.style')<span class="text-danger"> *</span></label>
                                            <select class="primary_select  form-control{{ $errors->has('style') ? ' is-invalid' : '' }}" name="style" id="style">
                                                <option data-display="@lang('style.select_position') *" value="">@lang('style.select_position') *</option>
                                                {{-- <option value="1" {{old('style') == 1? 'selected': ''}}>@lang('style.dashboard_background')</option> --}}
                                                <option value="2" {{old('style') == 2? 'selected': ''}}>@lang('style.login_page_background')</option>
                                                @if(moduleStatusCheck('Lead')==true)
                                                <option value="3" {{old('style') == 3? 'selected': ''}}>@lang('lead::lead.lead_form_background')</option>
                                                @endif
                                            </select>
                                            @if ($errors->has('style'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('style') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="row mt-15">
                                        <div class="col-lg-12"> 
                                            <label class="primary_input_label" for="">@lang('style.background_type')<span class="text-danger"> *</span></label>
                                            <select class="primary_select  form-control{{ $errors->has('background_type') ? ' is-invalid' : '' }}" name="background_type" id="background-type">
                                                <option data-display="@lang('style.background_type') *" value="">@lang('style.background_type') *</option>            
                                                <option value="color" {{old('background_type') == 'color'? 'selected': ''}}>@lang('style.color')</option>
                                                <option value="image" {{old('background_type') == 'image'? 'selected': ''}}>@lang('common.image') (1920x1400)</option>
                                            </select>
                                            @if ($errors->has('background_type'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('background_type') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>



                                    <div class="row mt-15" id="background-color">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('style.color')<span class="text-danger"> *</span></label>
                                                <input class="primary_input_field form-control{{ $errors->has('color') ? ' is-invalid' : '' }}" type="color" name="color" autocomplete="off" value="{{isset($visitor)? $visitor->purpose: old('color')}}">
                                                <input type="hidden" name="id" value="{{isset($visitor)? @$visitor->id: ''}}">
                                               
                                                
                                                @if ($errors->has('color'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('color') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row  mt-15" id="background-image">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                    for="">{{ trans('common.file') }}</label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary_input_field" id="placeholderInput" type="text" placeholder="{{isset($visitor)? (@$visitor->file != ""? getFilePath3(@$visitor->file): trans('style.background_image').' *'): trans('style.background_image').' *'}}"
                                                    readonly>
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="browseFile"><span
                                                                class="ripple rippleEffect"
                                                                style="width: 56.8125px; height: 56.8125px; top: -16.4062px; left: 10.4219px;"></span>@lang('common.browse')</label>
                                                                <input type="file" class="d-none" id="browseFile" name="image">
                                                    </button>
                                                </div>
                                            
                                                @if ($errors->has('image'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('image') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    
                                    @php 
                                        $tooltip = "";
                                        if(userPermission('background-settings-store')){
                                                $tooltip = "";
                                            }else{
                                                $tooltip = "You have no permission to add";
                                            }
                                    @endphp

                                    <div class="row mt-25">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($visitor))
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
                                <h3 class="mb-0">@lang('common.view')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">

                                <thead>
                              
                                <tr>
                                    <th>@lang('common.title')</th>
                                    <th>@lang('common.type')</th>
                                    <th>@lang('common.image')</th> 
                                    <th>@lang('common.status')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($background_settings as $background_setting)
                                    <tr>
                                        <td>{{@$background_setting->title}}</td>
                                        <td><p class="primary-btn small tr-bg">{{@$background_setting->type}}</p></td>
                                        <td>
                                            @if(@$background_setting->type == 'image')
                                            <img src="{{asset($background_setting->image)}}" width="200px" height="100px">
                                            @else
                                             <div style="width: 200px; height: 100px; background-color:{{@$background_setting->color}} "></div>
                                            @endif
                                        </td> 
                                        <td>
                                            <div class="col-md-12">
                                            
                                            @if(@$background_setting->is_default==1) 
                                                <a  class="primary-btn small fix-gr-bg " href="{{route('background_setting-status',@$background_setting->id)}}"> @lang('style.activated') </a> 
                                            @else
                                            @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> 
                                                @if(userPermission("background_setting-status"))
                                                <a  class="primary-btn small tr-bg" href="#"> @lang('style.make_default')</a> 
                                                </span>
                                                @endif
                                            @else
                                            @if(userPermission('background_setting-status'))
                                            <a  class="primary-btn small tr-bg" href="{{route('background_setting-status',@$background_setting->id)}}"> @lang('style.make_default')</a> 
                                           
                                            @endif
                                            @endif
                                           

                                            @endif
                                        </div>
                                        </td>

                                        <td>
                                            @if(@$background_setting->id==1)
                                            <p class="primary-btn small tr-bg">@lang('common.disable')</p>
                                            @else

                                            <x-drop-down>
                                                    @if(userPermission('background-setting-delete'))
                                                    <a class="dropdown-item" data-toggle="modal"
                                                       data-target="#deletebackground_settingModal{{@$background_setting->id}}"
                                                       href="#">@lang('common.delete')</a>
                                                    @endif
                                            </x-drop-down>
                                            
                                            @endif
                                        </td>
                                        <div class="modal fade admin-query" id="deletebackground_settingModal{{@$background_setting->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('common.delete')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>

                                                        <div class="mt-15 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')
                                                            </button>

                                                            <a href="{{route('background-setting-delete',@$background_setting->id)}}"
                                                               class="primary-btn fix-gr-bg">@lang('common.delete')</a>

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