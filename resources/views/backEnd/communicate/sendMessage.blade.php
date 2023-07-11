@extends('backEnd.master')
@section('title')
    @lang('communicate.add_notice')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('communicate.add_notice')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('communicate.communicate')</a>
                    <a href="#">@lang('communicate.add_notice')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor">
        @if (userPermission('add-notice'))
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('communicate.add_notice')</h3>
                        </div>
                    </div>
                    <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                        <a href="{{ route('notice-list') }}" class="primary-btn small fix-gr-bg">
                            @lang('communicate.notice_board')
                        </a>
                    </div>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save-notice-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <div class="row">
                    <div class="col-lg-12">

                        <div class="white-box">
                            <div class="">
                                <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                                <div class="row">
                                    <div class="col-lg-7">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="">@lang('common.title')
                                                <span class="text-danger"> *</span> </label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('notice_title') ? ' is-invalid' : '' }}"
                                                type="text" name="notice_title" autocomplete="off"
                                                value="{{ isset($leave_type) ? $leave_type->type : '' }}">


                                            @if ($errors->has('notice_title'))
                                                <span class="text-danger">
                                                    {{ $errors->first('notice_title') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="primary_input mt-0">
                                            <label class="primary_input_label" for="">@lang('communicate.notice')
                                                <span></span> </label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="5" name="notice_message"
                                                id="article-ckeditor"></textarea>


                                        </div>


                                        <div class="primary_input mt-15">
                                            <input type="checkbox" id="is_published" class="common-checkbox" value="1"
                                                name="is_published">
                                            <label for="is_published">@lang('communicate.is_published_web_site')</label>
                                        </div>


                                    </div>


                                    <div class="col-lg-5">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-15">
                                                    <label class="primary_input_label" for="">@lang('communicate.notice_date')
                                                        <span class="text-danger"> *</span> </label>
                                                    <div class="primary_datepicker_input">
                                                        <div class="no-gutters input-right-icon">
                                                            <div class="col">
                                                                <div class="">
                                                                    <input
                                                                        class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('notice_date') ? ' is-invalid' : '' }}"
                                                                        id="notice_date" type="text" autocomplete="off"
                                                                        name="notice_date" value="{{ date('m/d/Y') }}">
                                                                </div>
                                                            </div>
                                                            <button class="btn-date" data-id="#notice_date" type="button">
                                                                <label class="m-0 p-0" for="notice_date">
                                                                    <i class="ti-calendar" id="start-date-icon"></i>
                                                                </label>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('notice_date'))
                                                        <span class="text-danger">
                                                            {{ $errors->first('notice_date') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-15">
                                                    <label class="primary_input_label" for="">@lang('communicate.publish_on')
                                                        <span class="text-danger"> *</span> </label>
                                                    <div class="primary_datepicker_input">
                                                        <div class="no-gutters input-right-icon">
                                                            <div class="col">
                                                                <div class="">
                                                                    <input
                                                                        class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('publish_on') ? ' is-invalid' : '' }}"
                                                                        id="publish_on" type="text" autocomplete="off"
                                                                        name="publish_on" value="{{ date('m/d/Y') }}">
                                                                </div>
                                                            </div>
                                                            <button class="btn-date" data-id="#notice_date" type="button">
                                                                <label class="m-0 p-0" for="publish_on">
                                                                    <i class="ti-calendar" id="start-date-icon"></i>
                                                                </label>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('publish_on'))
                                                        <span class="text-danger">
                                                            {{ $errors->first('publish_on') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label class="primary_input_label"
                                                    for="">@lang('communicate.message_to')</label>
                                                @foreach ($roles as $role)
                                                    <div class="">
                                                        <input type="checkbox" id="role_{{ @$role->id }}"
                                                            class="common-checkbox" value="{{ @$role->id }}"
                                                            name="role[]">
                                                        <label for="role_{{ @$role->id }}">{{ @$role->name }}</label>
                                                    </div>
                                                @endforeach
                                                @if ($errors->has('role'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('role') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-40">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg submit">
                                        <span class="ti-check"></span>
                                        @lang('communicate.save_content')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
            </div>
        @endif
    </section>
@endsection
@include('backEnd.partials.date_picker_css_js')
@push('scripts')
    <script>
        CKEDITOR.replace('notice_message');
    </script>
@endpush
