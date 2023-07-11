@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('front_settings.header_menu')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                @lang('front_settings.front_settings')
                <a href="#">@lang('front_settings.header_menu')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if (@$editData)
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('header-menu')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($editData))
                                    @lang('common.edit')
                                @else
                                    @lang('common.add')
                                @endif
                                @lang('front_settings.header_menu')
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true,  'route' => 'update-header-menu', 'method' => 'POST']) }}
                            <input type="hidden" name="id" value="{{@$editData->id}}">
                        @else
                          @if(userPermission('store-header-menu'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'store-header-menu',
                        'method' => 'POST']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if(session()->has('message-success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success') }}
                                        </div>
                                        @elseif(session()->has('message-danger'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger') }}
                                        </div>
                                        @endif
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ @$errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" autocomplete="off" value="{{isset($editData)? @$editData->title : old('title')}}">
                                            <label class="primary_input_label" for="">@lang('common.title') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('title'))
                                            <span class="text-danger" >
                                                <strong>{{ @$errors->first('title') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mt-40">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ @$errors->has('slug') ? ' is-invalid' : '' }}"
                                                type="text" name="slug" autocomplete="off" value="{{ isset($editData)? @$editData->slug : old('slug')}}">
                                            <label class="primary_input_label" for="">@lang('front_settings.slug') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('slug'))
                                            <span class="text-danger" >
                                                <strong>{{ @$errors->first('slug') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mt-40">
                                    <div class="col-lg-12">
                                        <select class="primary_select  form-control{{ @$errors->has('status') ? ' is-invalid' : '' }}" name="status">
                                            <option data-display="@lang('common.status') *" value="">@lang('common.status') *</option>
                                            <option value="1" {{@$editData->active_status == '1'? 'selected':old('status') == ('1'? 'selected':'') }}>@lang('front_settings.active')</option>
                                            <option value="0" {{@$editData->active_status == '0'? 'selected':old('status') == ('0'? 'selected':'') }}>@lang('front_settings.inactive')</option>
                                        </select>
                                        
                                        @if ($errors->has('status'))
                                            <span class="text-danger" >
                                                <strong>{{ @$errors->first('status') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            	@php
                                  $tooltip = "";
                                  if(userPermission('store-header-menu')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($editData))
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
                            <h3 class="mb-0">@lang('front_settings.header_menu_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="tableWithoutSort" class="table" cellspacing="0" width="100%">
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
                                    <th>@lang('common.title')</th>
                                    <th>@lang('front_settings.slug')</th>
                                    <th>@lang('common.status')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody id="menuDiv">
                                @foreach($menus as $menu)
                                <tr data-id="{{$menu->id}}">
                                    <td>{{$menu->title}}</td>
                                    <td>{{$menu->slug}}</td>
                                    <td>
                                        @if ($menu->active_status == 1)
                                            <button class="primary-btn small bg-success text-white border-0">@lang('front_settings.active')</button>
                                        @else
                                        <button class="primary-btn small bg-warning text-white border-0">@lang('front_settings.inactive')</button>
                                        @endif
                                    <td>
                                        <x-drop-down/>
                                                <a class="dropdown-item" href="{{route('setup-header-menu', ['id'=>$menu->id] )}}">
                                                    @lang('common.setup')
                                                </a>
                                                
                                                @if ($menu->deletable == 0)
                                                <a class="dropdown-item" href="{{route('edit-header-menu', ['id'=>$menu->id] )}}">
                                                    @lang('common.edit')
                                                </a>
                                                
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteChartOfAccountModal{{$menu->id}}" href="#">
                                                    @lang('common.delete')
                                                </a>
                                               @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteChartOfAccountModal{{$menu->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('common.delete_menu')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>
                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                    <a class="primary-btn fix-gr-bg" href="{{route('delete-header-menu', ['id'=>$menu->id])}}">@lang('common.delete')</a>
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
@push('script')
<script>
    $('#menuDiv').sortable({
        cursor:"move",
        update: function(event, ui){
            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
            if(ids.length > 0){
                let data = {
                '_token' :'{{ csrf_token() }}',
                'ids' : ids,
                }
                $.post("{{ route('sort-menu') }}", data, 
                function(data){
            });
            }
        }
    }).disableSelection();
</script>
@endpush