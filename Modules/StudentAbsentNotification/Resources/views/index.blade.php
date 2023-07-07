@extends('backEnd.master')
@section('title')
    @lang('student.time_setup')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('student.time_setup')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('student.student_information')</a>
                    <a href="#">@lang('student.sms_sending_time')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">


            <div class="row">
                <div class="col-lg-8">
                </div>
                <div class="col-lg-4">
                    <a class="primary-btn fix-gr-bg" style="position: relative; left: 355px;" data-toggle="modal" data-target="#commandModal" href="#">Cron
                        Command
                    </a>
                </div>
            </div>

            <div class="modal fade admin-query" id="commandModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"> Cron Jobs Command</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <div class="text-center">
                                <h4><code>artisan absent_notification:sms</code> </h4>

                            </div>

                            <div class="mt-40 d-flex ">
                                Example: <br>
                                <code>
                                    {{ 'cd ' . base_path() . '/ && php artisan absent_notification:sms >> /dev/null 2>&1' }}
                                </code>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row mt-20">


                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if (isset($editData))
                                        @lang('student.edit_time_setup')
                                    @else
                                        @lang('student.add_time_setup')
                                    @endif
                                </h3>
                            </div>
                            @if (isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'absent_time_setup_update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

                                <input type="hidden" name="id" value="{{ $editData->id }}">
                            @else
                                @if (userPermission('absent_time_setup'))
                                    {{ Form::open([
                                        'class' => 'form-horizontal',
                                        'files' => true,
                                        'route' => 'absent_time_setup',
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) }}
                                @endif
                            @endif
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row no-gutters input-right-icon mt-25">
                                        <div class="col">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('student.start_time')<span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field primary_input_field time form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}"
                                                    type="text" name="start_time" id="start_time"
                                                    value="{{ isset($editData) ? $editData->time_from : Carbon::now()->format('H:i') }}">


                                                @if ($errors->has('start_time'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('start_time') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <label for="start_time" class="m-0 p-0">
                                                <i class="ti-timer"></i>
                                            </label>
                                        </button>
                                    </div>
                                    {{-- <div class="row no-gutters input-right-icon d-none mt-25">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input
                                                class="primary_input_field primary_input_field time  form-control{{ $errors->has('end_time') ? ' is-invalid' : '' }}"
                                type="text" name="end_time"
                                value="{{isset($editData)? $editData->time_to: old('end_time')}}">
                                <label class="primary_input_label" for="">@lang('student.end_time')</label>

                                @if ($errors->has('end_time'))
                                <span class="text-danger">
                                    {{ $errors->first('end_time') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="" type="button">
                                <i class="ti-timer"></i>
                            </button>
                        </div>
                    </div> --}}
                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <select
                                                class="primary_select form-control{{ $errors->has('active_status') ? ' is-invalid' : '' }}"
                                                name="active_status">
                                                <option data-display="@lang('student.status') *" value="">
                                                    @lang('student.status') *</option>
                                                <option value="1"
                                                    {{ isset($editData) ? ($editData->active_status == 1 ? 'selected' : '') : '' }}>
                                                    @lang('common.active')</option>
                                                <option value="0"
                                                    {{ isset($editData) ? ($editData->active_status == 0 ? 'selected' : '') : '' }}>
                                                    @lang('common.pending')</option>
                                            </select>
                                            @if ($errors->has('active_status'))
                                                <span class="text-danger invalid-select d-block" role="alert">
                                                    {{ $errors->first('active_status') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="">
                                                <span class="ti-check"></span>
                                                @if (isset($editData))
                                                    @lang('student.update_time_setup')
                                                @else
                                                    @lang('student.save_time_setup')
                                                @endif

                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="url" value="{{ Request::url() }}">
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">

                                <h3 class="mb-0">@lang('student.time_setup_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">

                                    <thead>
                                        <tr>
                                            <th>@lang('student.time')</th>
                                            {{-- <th>@lang('student.end_time')</th> --}}
                                            <th>@lang('common.status')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($setups as $item)
                                            <tr>
                                                <td>{{ @$item->time_from }}</td>
                                                {{-- <td>{{@$item->time_to}}</td> --}}
                                                <td>
                                                    @if ($item->active_status == 1)
                                                        @lang('common.active')
                                                    @else
                                                        @lang('common.pending')
                                                    @endif
                                                </td>
                                                <td>
                                                    <x-drop-down>
                                                        <a class="dropdown-item"
                                                            href="{{ route('absent_time_edit', [$item->id]) }}">@lang('common.edit')</a>
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#deleteStudentTypeModal{{ $item->id }}"
                                                            href="#">@lang('common.delete')</a>
                                                    </x-drop-down>
                        </div>
                    </div>
                    </td>
                    </tr>
                    <div class="modal fade admin-query" id="deleteStudentTypeModal{{ $item->id }}">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('student.delete_time_setup')</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                    </div>
                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg"
                                            data-dismiss="modal">@lang('common.cancel')</button>
                                        <a href="{{ route('time_setup_delete', [$item->id]) }}"><button
                                                class="primary-btn fix-gr-bg"
                                                type="submit">@lang('common.delete')</button></a>
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
@include('backEnd.partials.date_picker_css_js')
@include('backEnd.partials.data_table_js')
