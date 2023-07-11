@extends('backEnd.master')
@section('title')
    @lang('style.color_style')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('style.color_style')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('style.style')</a>
                    <a href="#">@lang('style.color_style')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('style.Edit Color Theme')</h3>
                    </div>
                </div>
            </div>
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['themes.update', $theme->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
            <input type="hidden" value="{{ $theme->id }}" name="theme_id">
            <input type="hidden" id="old_bg_image" value="{{ asset($theme->background_image) }}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="title">
                                        {{ __('style.Theme Title') }} <span class="text-danger"> *</span>
                                        
                                        </label>
                                    <input type="text" name="title"
                                        class="primary_input_field form-control {{ @$errors->has('title') ? ' is-invalid' : '' }}"
                                        id="title" required maxlength="191" value="{{ old('title', $theme->title) }}">
                                    
                                    
                                    @if ($errors->has('title'))
                                        <span class="text-danger" >
                                            <strong>{{ @$errors->first('title') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="background-type" class="primary_input_label"> {{ __('common.type') }}</label>
                                <select
                                    class="primary_select  form-control{{ $errors->has('background_type') ? ' is-invalid' : '' }}"
                                    name="background_type" id="background-type">                                   
                                    
                                    <option value="image" {{ old('background_type', $theme->background_type) == 'image' ? 'selected' : '' }}>
                                        @lang('common.image') (1920x1400)</option>

                                    <option value="color" {{ old('background_type', $theme->background_type) == 'color' ? 'selected' : '' }}>
                                            @lang('style.color')</option>

                                </select>
                                @if ($errors->has('background_type'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('background_type') }}
                                    </span>
                                @endif

                            </div>
                            <div class="col-lg-4" id="background-color">
                                <div class="primary_input">
                                    <label class="primary_input_label">@lang('style.color')<span class="text-danger"> *</span></label>

                                    <input class="primary_input_field color-input form-control" type="color" name="background_color"
                                        autocomplete="off" id="background_color" value="{{old('background_color', $theme->background_color)}}">


                                    @if ($errors->has('background_color'))
                                        <span class="text-danger" >
                                            {{ $errors->first('background_color') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4" id="background-image">
                                <div class="primary_input">
                                    <label class="primary_input_label"
                                        for="">{{ trans('common.file') }}</label>
                                    <div class="primary_file_uploader">
                                        <input class="primary_input_field" id="placeholderInput" type="text"
                                                placeholder="{{ isset($theme) ? (@$theme->background_image != '' ? getFilePath3(@$theme->background_image) : trans('style.background_image') . ' *') : trans('style.background_image') . ' *' }}"
                                                readonly>
                                        <button class="" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="browseFile">@lang('common.browse')</label>
                                            <input type="file" class="d-none" id="browseFile" name="background_image">
                                        </button>
                                    </div>
                                   
                                    @if ($errors->has('background_image'))
                                    <span class="text-danger d-block">
                                        {{ $errors->first('background_image') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($theme->colors as $color)
                                <div class="col-lg-3 mt-25" id="{{ $color->name . '_div' }}">
                                    <div class="primary_input">

                                        <label class="fw-400 fs-12 position-unset mb-1 top-0">{{ __('style.' . $color->name) }}<span class="text-danger"> *</span></label>
                                        <input type="color" name="color[{{ $color->id }}]"
                                            class="primary_input_field color-input form-control color_field" id="{{ $color->name }}"  data-name="{{ $color->name }}"
                                            required  value="{{ old('color.'.$color->id, $color->pivot->value) }}" data-value="{{ $color->pivot->value }}">
                                        
                                       
                                        @if ($errors->has('color'))
                                            <span class="text-danger" >
                                                {{ $errors->first('color') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-4 mt-25">

                                <div class="">

                                    <input type="checkbox" id="box_shadow"
                                        class="common-checkbox form-control{{ @$errors->has('box_shadow') ? ' is-invalid' : '' }}"
                                        name="box_shadow" {{ old('box_shadow', $theme->box_shadow) ? 'checked' : '' }}>
                                    <label for="box_shadow">{{ __('style.box_shadow') }}</label>

                                </div>

                                @if ($errors->has('box_shadow'))
                                    <span class="text-danger validate-textarea-checkbox" role="alert">
                                        <strong>{{ @$errors->first('box_shadow') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="submit_btn text-center ">
                                    <button class="primary-btn semi_large2 fix-gr-bg" id="reset_to_default"
                                        type="button"><i class="ti-check"></i>{{ __('style.Reset To Default') }}
                                    </button>
                                    <button class="primary-btn semi_large2 fix-gr-bg" type="submit"><i
                                            class="ti-check"></i>{{ __('common.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        {{ Form::close() }}
        </div>
    </section>
@endsection

@push('scripts')
    @include('backEnd.style.script')
@endpush
