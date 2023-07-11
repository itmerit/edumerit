@extends('backEnd.master')
@section('title')
    @lang('front_settings.course_category')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.course_category')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#"> @lang('front_settings.front_settings')</a>
                    <a href="#">@lang('front_settings.course_category')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($editData))
                @if (userPermission('store_news_category'))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            @if (userPermission("store-course-category"))
                                <a href="{{ route('course-category') }}" class="primary-btn small fix-gr-bg">
                                    <span class="ti-plus pr-2"></span>
                                    @lang('common.add')
                                </a>
                            @endif
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
                                    @if (isset($editData))
                                        @lang('front_settings.edit_category')
                                    @else
                                        @lang('front_settings.add_category')
                                    @endif

                                </h3>
                            </div>
                            @if (isset($editData))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'update-course-category',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @else
                                @if (userPermission("store-course-category"))
                                    {{ Form::open([
                                        'class' => 'form-horizontal',
                                        'files' => true,
                                        'route' => 'store-course-category',
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12 mb-20">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('student.category_name')
                                                    <span class="text-danger"> *</span> </label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('category_name') ? ' is-invalid' : '' }}"
                                                    type="text" name="category_name" autocomplete="off"
                                                    value="{{ isset($editData) ? $editData->category_name : '' }}">
                                                <input type="hidden" name="id"
                                                    value="{{ isset($editData) ? $editData->id : '' }}">


                                                @if ($errors->has('category_name'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('category_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        
                                        <div class="col-lg-12 mt-15">
                                            <div class="primary_input">
                                                <div class="primary_file_uploader">
                                                    <input
                                                    class="primary_input_field form-control{{ $errors->has('category_image') ? ' is-invalid' : '' }}" name="category_image" readonly="true" type="text"
                                                    placeholder="{{ isset($editData->category_image) && @$editData->category_image != '' ? getFilePath3(@$editData->category_image) : trans('front_settings.image') }} *"
                                                    id="placeholderUploadContent">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg" for="upload_content_file">{{ __('common.browse') }}</label>
                                                        <input type="file" class="d-none" name="category_image" id="upload_content_file">
                                                    </button>
                                                    
                                                    @if ($errors->has('category_image'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('category_image') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-10">
                                        <code>@lang('front_settings.min') (1420*450 px)</code>
                                    </div>
                                    @php
                                        $tooltip = '';
                                        if (userPermission("store-course-category")) {
                                            $tooltip = '';
                                        } else {
                                            $tooltip = 'You have no permission to add';
                                        }
                                        if (isset($editData)) {
                                            if (userPermission('edit-course-category')) {
                                                $tooltip = '';
                                            } else {
                                                $tooltip = 'You have no permission to edit';
                                            }
                                        }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                <span class="ti-check"></span>
                                                @if (isset($editData))
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
                                <h3 class="mb-0"> @lang('front_settings.course_category_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th> @lang('student.category_title')</th>
                                        <th> @lang('front_settings.image') </th>
                                        <th> @lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($course_categories))
                                        @foreach ($course_categories as $value)
                                            <tr>
                                                <td>{{ $value->category_name }}</td>
                                                <td>
                                                    <img src="{{ asset($value->category_image) }}" height="100"
                                                        width="200">
                                                </td>
                                                <td>
                                                    <x-drop-down>
                                                        @if (userPermission('edit-course-category'))
                                                            <a class="dropdown-item"
                                                                href="{{ route('edit-course-category', $value->id) }}">
                                                                @lang('common.edit')</a>
                                                        @endif

                                                        @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                            <span tabindex="0" data-toggle="tooltip"
                                                                title="Disabled For Demo"> <a href="#"
                                                                    class="dropdown-item small fix-gr-bg  demo_view"
                                                                    style="pointer-events: none;">@lang('common.delete')</a></span>
                                                        @else
                                                            @if (userPermission('delete-course-category'))
                                                                <a class="dropdown-item" data-toggle="modal"
                                                                    data-target="#deleteCourseCategory{{ $value->id }}"
                                                                    href="#">
                                                                    @lang('common.delete')
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </x-drop-down>
                                                </td>
                                            </tr>
                                            <div class="modal fade admin-query"
                                                id="deleteCourseCategory{{ $value->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                @lang('front_settings.delete_course_category')
                                                            </h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                                {{ Form::open(['route' => ['delete-course-category', $value->id], 'method' => 'post']) }}
                                                                <button class="primary-btn fix-gr-bg" type="submit">
                                                                    @lang('common.delete')
                                                                </button>
                                                                {{ Form::close() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
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