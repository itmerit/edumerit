@extends('backEnd.master')
@section('title')
    @lang('system_settings.backup_settings')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.backup_settings')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="{{ route('sms-settings') }}">@lang('system_settings.backup_settings')</a>
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
                                <h3 class="mb-30">@lang('system_settings.upload_from_local_directory')</h3>
                            </div>
                            {{-- @if (isset($sms_dbs))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'session/'.@$session->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else --}}
                            @if (userPermission('backup-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'backup-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                            {{-- @endif --}}
                            <div class="white-box sm_mb_20  ">
                                <div class="add-visitor">

                                    <div class="row mb-20">
                                        <div class="col-lg-12 mt-15">
                                            <div class="primary_input">
                                                <div class="primary_file_uploader">
                                                    <input
                                                        class="primary_input_field form-control {{ $errors->has('content_file') ? ' is-invalid' : '' }}"
                                                        readonly="true" type="text"
                                                        placeholder="{{ isset($editData->file) && @$editData->file != '' ? getFilePath3(@$editData->file) : trans('common.attach_file') . '*' }} "
                                                        id="placeholderUploadContent" name="content_file">
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                            for="upload_content_file">{{ __('common.browse') }}</label>
                                                        <input type="file" class="d-none" name="content_file"
                                                            id="upload_content_file">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">

                                            {{-- DEMO LIVE --}}
                                            {{--  <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo ">
                      <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" disabled> @lang('common.save')</button>
                    </span> --}}
                                            @php
                                                $tooltip = '';
                                                if (userPermission('backup-store')) {
                                                    $tooltip = '';
                                                } else {
                                                    $tooltip = 'You have no permission to add';
                                                }
                                            @endphp
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                <span class="ti-check"></span>
                                                @if (isset($sms_dbs))
                                                    @lang('system_settings.update_file')
                                                @else
                                                    @lang('system_settings.save_file')
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


                        <div class="col-lg-4  col-xl-3">
                            <div class="main-title mb-20">
                                <h3 class="mb-0"> @lang('system_settings.database_backup_list')</h3>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xl-9 text-right col-md-12 mb-20  md_mb_20 title_custom_margin">
                            {{-- <div class="col-lg-12 col-xl-8 text-right col-md-12 mb-20 text_xs_left text_sm_left md_mb_20 title_custom_margin"> --}}


                            {{-- DEMO LIVE --}}

                            @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                                <span class="d-inline-block mb-10" tabindex="0" data-toggle="tooltip"
                                    title="@lang('system_settings.disabled_image_backup')">
                                    <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;"
                                        type="button"> @lang('system_settings.upload_file_backup')</button>
                                </span>

                                <span class="d-inline-block mb-10" tabindex="0" data-toggle="tooltip"
                                    title="@lang('system_settings.disabled_database_backup')">
                                    <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;"
                                        type="button">@lang('system_settings.database_backup')</button>
                                </span>
                            @else
                                @if (userPermission('get-backup-files'))
                                    <a href="{{ route('get-backup-files', 1) }}"
                                        class="primary-btn small fix-gr-bg  demo_view">
                                        <span class="ti-arrow-circle-down pr-2"></span>
                                        @lang('system_settings.upload_file_backup')
                                    </a>
                                @endif

                                @if (userPermission('get-backup-db'))
                                    <a href="{{ route('get-backup-db') }}" class="primary-btn small fix-gr-bg demo_view">
                                        <span class="ti-arrow-circle-down pr-2"></span> @lang('system_settings.database_backup') </a>
                                @endif
                            @endif



                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                                    <thead>




                                        <tr>
                                            <th>@lang('system_settings.size')</th>
                                            <th>@lang('system_settings.created_date_time')</th>
                                            <th>@lang('system_settings.backup_files')</th>
                                            <th>@lang('system_settings.file_type')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($sms_dbs as $sms_db)
                                            <tr>
                                                <td>
                                                    @php
                                                        if (file_exists(@$sms_db->source_link)) {
                                                            @$size = filesize(@$sms_db->source_link);
                                                            @$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                                                            @$power = @$size > 0 ? floor(log(@$size, 1024)) : 0;
                                                            echo number_format(@$size / pow(1024, @$power), 2, '.', ',') . ' ' . @$units[@$power];
                                                        } else {
                                                            echo 'File already deleted.';
                                                        }
                                                    @endphp
                                                </td>
                                                <td>
                                                    {{ @$sms_db->created_at != '' ? dateConvert(@$sms_db->created_at) : '' }}

                                                </td>
                                                <td>{{ @$sms_db->file_name }}</td>
                                                <td>
                                                    @php
                                                        if (@$sms_db->file_type == 0) {
                                                            echo 'Database';
                                                        } elseif (@$sms_db->file_type == 1) {
                                                            echo 'Images';
                                                        } else {
                                                            echo 'Whole Project';
                                                        }
                                                    @endphp
                                                </td>
                                                <td>


                                                    @if (userPermission('download-files'))
                                                        <a class="primary-btn small tr-bg  "
                                                            href="{{ route('download-files', @$sms_db->id) }}">
                                                            <span class="pl ti-download"></span> @lang('common.download')
                                                        </a>
                                                    @endif

                                                    @if (@$sms_db->file_type == 10)
                                                        <a class="primary-btn small tr-bg  "
                                                            href="{{ route('restore-database', @$sms_db->id) }}">
                                                            <span class="pl ti-upload"></span> @lang('system_settings.restore')
                                                        </a>
                                                    @endif

                                                    @if (userPermission('delete_database'))
                                                        <a data-target="#deleteDatabase{{ @$sms_db->id }}"
                                                            data-toggle="modal" class="primary-btn small tr-bg  "
                                                            href="{{ url('/' . @$sms_db->id) }}">
                                                            <span class="pl ti-close"></span> @lang('common.delete')
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>



                                            <div class="modal fade admin-query" id="deleteDatabase{{ @$sms_db->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"> @lang('common.delete_item')</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4> @lang('common.are_you_sure_to_delete')</h4>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal"> @lang('common.cancel')</button>
                                                                <a href="{{ route('delete_database', [@$sms_db->id]) }}"
                                                                    class="text-light">
                                                                    <button class="primary-btn fix-gr-bg" type="submit">
                                                                        @lang('common.delete')</button>
                                                                </a>
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
    </section>

@endsection
