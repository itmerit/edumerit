@extends('backEnd.master')
    @section('title')
        @if(isset($editData))
            @lang('front_settings.edit_page')
        @else
            @lang('front_settings.add_page')
        @endif
        
    @endsection
@section('mainContent')
@push('css')
    <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/editor/summernote-bs4.css') }}">
<style>
    .cust-class{
        font-size: 12px;
    }
</style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('front_settings.create_page')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#"> @lang('front_settings.front_settings')</a>
                <a href="#">@lang('front_settings.create_page')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-15">
                <a href="{{route('page-list')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-angle-left pr-2"></span>
                    @lang('front_settings.back')
                </a>
                @if (isset($editData))
                    @if(userPermission("save-page-data"))
                        <a href="{{route('create-page')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($editData))
                                    @lang('front_settings.edit_page')
                                @else
                                    @lang('front_settings.add_page')
                                @endif
                             
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-page-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="{{$editData->id}}">
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save-page-data',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.title') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" onkeyup="processSlug(this.value, '#slug')" autocomplete="off" value="{{ isset($editData) ? $editData->title : old('title') }}">
                                           
                                            
                                            @if ($errors->has('title'))
                                            <span class="text-danger" >
                                                {{ @$errors->first('title') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mt-40">
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.slug') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('slug') ? ' is-invalid' : '' }}"
                                                type="text" name="slug" id="slug" autocomplete="off" value="{{ isset($editData) ? $editData->slug : old('slug') }}">
                                            
                                            
                                            @if ($errors->has('slug'))
                                            <span class="text-danger" >
                                                {{ @$errors->first('slug') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row mb-15">
                                          
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label">{{ __('front_settings.image_header_min') }}</label>
                                                    <div class="primary_file_uploader">
                                                        <input
                                                        class="primary_input_field form-control {{ $errors->has('header_image') ? ' is-invalid' : '' }}"
                                                        readonly="true" type="text"
                                                        placeholder="{{isset($editData->header_image) && @$editData->header_image != ""? getFilePath3(@$editData->header_image):trans('front_settings.image_header_min')." (1420*450 PX)"}}"
                                                        id="placeholderUploadContent">
                                                        <button class="" type="button">
                                                            <label class="primary-btn small fix-gr-bg" for="header_image">{{ __('common.browse') }}</label>
                                                            <input type="file" class="d-none" name="file" id="header_image">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @isset($editData)
                                            <a class="btn btn-primary cust-class pull-right" href="{{route('view-page', ['slug'=>@$editData->slug])}}" target="blank">@lang('front_settings.preview')</a>
                                        @endisset
                                        {{-- @if(isset($editData->header_image))
                                            <a class="btn btn-primary cust-class pull-right" data-toggle="modal" data-target="#viewImages" data-modal-size="full-width-modal" href="#">
                                                @lang('front_settings.preview')
                                            </a>
                                        @endif --}}
                                    </div>
                                </div>
                                <div class="row  mt-40">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.sub_title')</label>
                                            <input class="primary_input_field form-control{{ @$errors->has('sub_title') ? ' is-invalid' : '' }}"
                                                type="text" name="sub_title" autocomplete="off" value="{{ isset($editData) ? $editData->sub_title : old('sub_title') }}">
                                            
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.details')<span class="text-danger"> *</span></label>
                                            <textarea class="primary_input_field summer_note form-control{{ $errors->has('details') ? ' is-invalid' : '' }}" cols="0" rows="4" name="details" >{{isset($editData)? $editData->details: old('details')}}</textarea>
                                            
                                            
                                            @if($errors->has('details'))
                                                <span class="text-danger" >{{ $errors->first('details') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	@php
                                  $tooltip = "";
                                  if(userPermission("save-page-data")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                    if(isset($editData)){
                                        if(userPermission("edit-page")){
                                            $tooltip = "";
                                        }else{
                                            $tooltip = "You have no permission to edit";
                                        }
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($editData))
                                                @lang('front_settings.update_page')
                                            @else
                                                @lang('front_settings.save_page')
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
        </div>
    </div>
    <div class="modal fade admin-query" id="viewImages">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        @lang('front_settings.image_preview')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <img src="{{asset(@$editData->header_image)}}" width="100%" style="float: left">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')

    <script src="{{ asset('public/backEnd/vendors/editor/summernote-bs4.js') }}"></script>
<script>
    function processSlug(value, slug_id){
        let data = value.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
        $(slug_id).val('');
        $(slug_id).val(data);
        $('#slug').addClass( "has-content" );
    }

    $(document).on('change', '#header_image', function(event){
        getFileName($(this).val(),'#placeholderUploadContent');
    });
</script>
@endpush