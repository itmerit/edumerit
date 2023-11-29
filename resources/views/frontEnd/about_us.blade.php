@extends('backEnd.master')
@section('title')
@lang('front_settings.about_page')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.about_page')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#"> @lang('front_settings.front_settings')</a>
                    <a href="#">@lang('front_settings.about_page')</a>
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
                                <h3 class="mb-30">
                                    @if(isset($update))
                                        @lang('common.edit')
                                    @else
                                        @lang('common.add')
                                    @endif
                                </h3>
                            </div>
                            @if(isset($update))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'about-page/update',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'visitor_store',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            <div class="white-box">
                                @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        @lang('front_settings.inserted_message')
                                    </div>
                                @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        @lang('front_settings.error_message')
                                    </div>
                                @endif
                                <div class="add-visitor {{isset($update)? '':'isDisabled'}}">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <div class="primary_input">
                                                <input
                                                    class="primary_input_field "
                                                    type="text" name="title" autocomplete="off"
                                                    value="{{isset($update)? ($about_us != ''? $about_us->title:''):''}}">
                                                <label class="primary_input_label" for="">@lang('common.title')<span class="text-danger"> *</span></label>
                                                
                                                @if ($errors->has('title'))
                                                    <span class="text-danger" >
                                                    {{ $errors->first('title') }}
                                                </span>
                                                @endif
                                            </div>
                                            <div class="primary_input mt-25">
                                                <div class="primary_input">
                                                    <textarea class="primary_input_field form-control" cols="0" rows="5" name="description" id="description">{{isset($update)? ($about_us != ''? $about_us->description:''):'' }}</textarea>
                                                    <label class="primary_input_label" for="">@lang('common.description') <span class="text-danger"> *</span> </label>
                                                    @if($errors->has('description'))
                                                        <span class="text-danger" >
                                                        {{ $errors->first('description') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="primary_input mt-25">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('main_title') ? ' is-invalid' : '' }}"
                                                    type="text" name="main_title" autocomplete="off"
                                                    value="{{isset($update)? ($about_us != ''? $about_us->main_title:''):''}}">
                                                <label class="primary_input_label" for="">@lang('front_settings.main_title')<span class="text-danger"> *</span></label>
                                                
                                                @if ($errors->has('main_title'))
                                                    <span class="text-danger" >
                                                    {{ $errors->first('main_title') }}
                                                </span>
                                                @endif
                                            </div>
                                            <div class="primary_input mt-25">
                                                <div class="primary_input">
                                                    <textarea class="primary_input_field form-control" cols="0" rows="5" name="main_description" id="main_description">{{isset($update)? ($about_us != ''? $about_us->main_description:''):'' }}</textarea>
                                                    <label class="primary_input_label" for="">@lang('front_settings.main_description') <span class="text-danger"> *</span> </label>
                                                    @if($errors->has('main_description'))
                                                        <span class="text-danger" >
                                                        {{ $errors->first('main_description') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="primary_input mt-25">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('button_text') ? ' is-invalid' : '' }}"
                                                    type="text" name="button_text" autocomplete="off"
                                                    value="{{isset($update)? ($about_us != ''? $about_us->button_text:''):'' }}">
                                                <label class="primary_input_label" for="">@lang('front_settings.button_text')<span class="text-danger"> *</span></label>
                                                
                                                @if ($errors->has('button_text'))
                                                    <span class="text-danger" >
                                                    {{ $errors->first('button_text') }}
                                                </span>
                                                @endif
                                            </div>
                                            <div class="primary_input mt-25">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('button_text') ? ' is-invalid' : '' }}"
                                                    type="text" name="button_url" autocomplete="off"
                                                    value="{{isset($update)? ($about_us != ''? $about_us->button_url:''):'' }}">
                                                <label class="primary_input_label" for="">@lang('front_settings.button_url')<span class="text-danger"> *</span></label>
                                                
                                                @if ($errors->has('button_url'))
                                                    <span class="text-danger" >
                                                    {{ $errors->first('button_url') }}
                                                </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row no-gutters input-right-icon mt-35">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" id="placeholderInput" type="text"
                                                       placeholder="{{isset($update) and $about_us ? ($about_us->image !="") ? getFilePath3($about_us->image) :trans('common.image') .' *' :trans('common.image') .' *' }}"
                                                       readonly>
                                                
                                                @if($errors->has('image'))
                                                    <span class="text-danger mb-10" role="alert">
                                                        {{ $errors->first('image') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                       for="browseFile">@lang('common.browse')</label>
                                                <input type="file" class="d-none" id="browseFile" name="image">
                                            </button>

                                        </div>


                                    </div>
                                    <span class="mt-10">@lang('common.image')(1420px*450px)</span>
                                    <div class="row no-gutters input-right-icon mt-35">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('main_image') ? ' is-invalid' : '' }}" id="placeholderInputMainFile" type="text"
                                                       placeholder="{{isset($update) and $about_us? ($about_us->main_image !="") ? getFilePath3($about_us->main_image) :trans('front_settings.main') .' '. trans('common.image') .' *' :trans('front_settings.main') .' '. trans('common.image') .' *' }}"
                                                       readonly>
                                                
                                                @if($errors->has('main_image'))
                                                    <span class="text-danger mb-10" role="alert">
                                                        {{ $errors->first('main_image') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                       for="browseFileMailFile">@lang('common.browse')</label>
                                                <input type="file" class="d-none" id="browseFileMailFile" name="main_image">
                                            </button>

                                        </div>

                                    </div>
                                    <span class="mt-10">@lang('common.image')(1420px*450px)</span>

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('common.update') </button></span>
                                            @else
                                                <button class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    @if(isset($update))
                                                        @lang('common.update')
                                                    @else
                                                        @lang('common.save')
                                                    @endif
                                                </button>
                                            @endif
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
                                <h3 class="mb-30">@lang('front_settings.info')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 scroll_table">

                            <table class="table school-table-style" cellspacing="0" width="100%">

                                <thead>
                                <tr>
                                    <th width="10%">@lang('common.title')</th>
                                    <th width="20%">@lang('common.description')</th>
                                    <th width="10%">@lang('front_settings.button_text')</th>
                                    <th width="10%">@lang('front_settings.button_url')</th>
                                    <th width="10%">@lang('common.action')</th>
                                </tr>
                                </thead>

                                <tbody>

                                <tr>
                                    <td width="10%">{{$about_us != ""? $about_us->title:""}}</td>
                                    <td width="20%">{{$about_us != ""? $about_us->description:""}}</td>
                                    <td width="10%">{{$about_us != ""? $about_us->button_text:""}}</td>
                                    <td width="10%">{{$about_us != ""? $about_us->button_url:""}}</td>
                                    <td width="20%">
                                        @if($about_us != "")
                                            @if(userPermission('about-page/view'))
                                                <a class="primary-btn small fix-gr-bg" data-toggle="modal" data-target="#showimageModal"  href="#">@lang('common.view')</a>
                                            @endif
                                            @if(userPermission('about-page/edit'))
                                                <a href="{{route('about-page/edit')}}" class="primary-btn small fix-gr-bg">@lang('common.edit')</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if($about_us)
    <div class="modal fade admin-query" id="showimageModal">
        <div class="modal-dialog modal-dialog-centered max_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('front_settings.about_us_details') </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body p-0">
                    <div class="container student-certificate">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 text-center">
                                <div class="mt-20">
                                    <section class="container box-1420">
                                        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{$about_us->image != ""? $about_us->image : '../img/client/common-banner1.jpg'}}) no-repeat center;background-size: cover">
                                            <div class="banner-inner">
                                                <div class="banner-content">
                                                    <h2 style="color: whitesmoke">{{$about_us->title}}</h2>
                                                    <p style="color: whitesmoke">{{$about_us->description}}</p>
                                                    <a class="primary-btn fix-gr-bg semi-large" href="{{$about_us->button_url}}">{{$about_us->button_text}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <div class="mt-10 row">
                                        <div class="col-md-6">
                                            <div class="academic-item">
                                                <div class="academic-img">
                                                    <img class="img-fluid" src="{{asset($about_us->main_image)}}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="academic-text mt-30">
                                                <h4>
                                                    {{$about_us->main_title}}
                                                </h4>
                                                <p>
                                                    {{$about_us->main_description}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

