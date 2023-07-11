@extends('backEnd.master')
@section('title')
@lang('front_settings.social_media')
@endsection
@section('mainContent')
<style>
    .QA_section.check_box_table .QA_table .table thead tr th:first-child {
        padding-left: 50px !important;
    }
    table.dataTable thead .sorting_asc::after {
    left: 35px !important;
}
</style>
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('front_settings.social_media')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                @lang('front_settings.front_settings')
                <a href="#">@lang('front_settings.social_media')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($visitor))
        @if(userPermission('social-media-store'))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('social-media')}}" class="primary-btn small fix-gr-bg">
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
                                @if(isset($visitor))
                                    @lang('front_settings.edit_social_media')
                                @else
                                    @lang('front_settings.add_social_media')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($visitor))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'social-media-update',
                            'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @else
                            @if(userPermission('social-media-store'))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'social-media-store',
                            'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-warning">
                                            Note: Font awesome icon enter only e.g. fa fa-facebook.
                                        </div>
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.icon')(fa fa-facebook)<span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}"  type="text" id="icon" name="icon" autocomplete="off" value="{{isset($visitor)? $visitor->icon: old('icon')}}">
                                            <input type="hidden" name="id" value="{{isset($visitor)? $visitor->id: ''}}">
                                          
                                            
                                            @if ($errors->has('icon'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('icon') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.url')<span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"  type="text" name="url" autocomplete="off" value="{{isset($visitor)? $visitor->url: old('url')}}">
                                            <input type="hidden" name="id"
                                                    value="{{isset($visitor)? $visitor->id: ''}}">
                                            
                                            
                                            @if ($errors->has('url'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('url') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('common.status')<span class="text-danger"> *</span></label>
                                        <select class="primary_select  form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status">
                                                <option data-display="@lang('common.status') *" value="">@lang('common.select')*</option>
                                                <option value="1" {{isset($visitor)? ($visitor->status == 1? 'selected':''):'selected'}}>@lang('front_settings.active')</option>
                                                <option value="0"  {{isset($visitor)? ($visitor->status == 0? 'selected':''):''}}>@lang('front_settings.inactive')</option>
                                        </select>
                                        @if ($errors->has('status'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('status') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                    @php 
                                        $tooltip = "";
                                        if(userPermission('social-media-store')){
                                                $tooltip = "";
                                            }elseif(userPermission('social-media-edit') && isset($visitor)){
                                                $tooltip = "";
                                            }else{
                                                $tooltip = "You have no permission to add";
                                            }
                                    @endphp
                                <div class="row mt-40">
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
                            <h3 class="mb-0">@lang('front_settings.social_media')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="pl-10">@lang('front_settings.url')</th>
                                        <th>@lang('front_settings.icon')</th>
                                        <th>@lang('common.status')</th>
                                        <th>@lang('common.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visitors as $value)
                                        <tr>
                                            <td>
                                                <a href="{{ @$value->url}}"> 
                                                    {{ @$value->url}}
                                                </a>
                                            </td>
                                            <td class="pl-2"><i class="{{$value->icon}}"></td>
                                            <td>{{ @$value->status == 1? 'active':'inactive'}}</td>
                                            <td>
                                            <x-drop-down>
                                                        @if(userPermission('social-media-edit'))

                                                            <a class="dropdown-item"
                                                                href="{{route('social-media-edit', [@$value->id])}}">@lang('common.edit')</a>
                                                        @endif
                                                        @if(userPermission('social-media-delete'))

                                                            <a class="dropdown-item" data-toggle="modal"
                                                                data-target="#deleteVisitorModal{{@$value->id}}"
                                                                href="#">@lang('common.delete')</a>
                                                            
                                                        @endif
                                            </x-drop-down>
                                            </td>
                                        </tr>
                                        <div class="modal fade admin-query" id="deleteVisitorModal{{@$value->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('front_settings.delete_social_media')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')
                                                            </button>
                                                            <a href="{{route('social-media-delete', [@$value->id])}}"
                                                                class="primary-btn fix-gr-bg">@lang('common.delete')</a>
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