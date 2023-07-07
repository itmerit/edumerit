@extends('backEnd.master')
@section('title')
    @lang('dormitory.dormitory_list')
@endsection
@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('dormitory.dormitory_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('dormitory.dormitory')</a>
                    <a href="#">@lang('dormitory.dormitory_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($dormitory_list))
                @if (userPermission('dormitory-list-store'))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{ route('dormitory-list') }}" class="primary-btn small fix-gr-bg">
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
                                    @if (isset($dormitory_list))
                                        @lang('dormitory.edit_dormitory')
                                    @else
                                        @lang('dormitory.add_dormitory')
                                    @endif
                                </h3>
                            </div>
                            @if (isset($dormitory_list))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['dormitory-list-update', $dormitory_list->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                            @else
                                @if (userPermission('dormitory-list-store'))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'dormitory-list', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('dormitory.dormitory_name') <span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('dormitory_name') ? ' is-invalid' : '' }}"
                                                    type="text" name="dormitory_name" autocomplete="off"
                                                    value="{{ isset($dormitory_list) ? $dormitory_list->dormitory_name : old('dormitory_name') }}">
                                                <input type="hidden" name="id"
                                                    value="{{ isset($dormitory_list) ? $dormitory_list->id : '' }}">
                                                @if ($errors->has('dormitory_name'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('dormitory_name') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('common.type') <span
                                                    class="text-danger"> *</span></label>
                                            <select
                                                class="primary_select  form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                                name="type">
                                                <option data-display="@lang('common.type') *" value="">
                                                    @lang('common.type') *</option>
                                                @if (isset($dormitory_list))
                                                    <option value="B"
                                                        {{ @$dormitory_list->type == 'B' ? 'selected' : '' }}>
                                                        @lang('dormitory.boys')</option>
                                                    <option value="G"
                                                        {{ @$dormitory_list->type == 'G' ? 'selected' : '' }}>
                                                        @lang('dormitory.girls')</option>
                                                @else
                                                    <option value="B">@lang('dormitory.boys')</option>
                                                    <option value="G">@lang('dormitory.girls')</option>
                                                @endif

                                            </select>
                                            @if ($errors->has('type'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('type') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row  mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('dormitory.address') <span
                                                        class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                    type="text" name="address"
                                                    value="{{ isset($dormitory_list) ? $dormitory_list->address : old('address') }}">
                                                @if ($errors->has('address'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('address') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('dormitory.intake') <span
                                                        class="text-danger"> *</span></label>
                                                <input oninput="numberCheck(this)"
                                                    class="primary_input_field form-control{{ $errors->has('intake') ? ' is-invalid' : '' }}"
                                                    type="text" name="intake"
                                                    value="{{ isset($dormitory_list) ? $dormitory_list->intake : old('intake') }}">
                                                @if ($errors->has('intake'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('intake') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('common.description')
                                                    <span></span></label>
                                                <textarea class="primary_input_field form-control" cols="0" rows="4" name="description">{{ isset($dormitory_list) ? $dormitory_list->description : old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $tooltip = '';
                                        if (userPermission('dormitory-list-store') || userPermission('dormitory-list-edit')) {
                                            $tooltip = '';
                                        } else {
                                            $tooltip = 'You have no permission to add';
                                        }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                <span class="ti-check"></span>
                                                @if (isset($dormitory_list))
                                                    @lang('dormitory.update_dormitory')
                                                @else
                                                    @lang('dormitory.save_dormitory')
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
                                <h3 class="mb-0"> @lang('dormitory.dormitory_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('dormitory.dormitory_name')</th>
                                            <th>@lang('common.type')</th>
                                            <th>@lang('dormitory.address')</th>
                                            <th>@lang('dormitory.intake') </th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dormitory_lists as $key => $dormitory_list)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ @$dormitory_list->dormitory_name }}</td>
                                                <td>{{ @$dormitory_list->type == 'B' ? 'Boys' : 'Girls' }}</td>
                                                <td>{{ @$dormitory_list->address }}</td>
                                                <td>{{ @$dormitory_list->intake }}</td>
                                                <td>
                                                    <x-drop-down>
                                                        @if (userPermission('dormitory-list-edit'))
                                                            <a class="dropdown-item"
                                                                href="{{ route('dormitory-list-edit', [$dormitory_list->id]) }}">@lang('common.edit')</a>
                                                        @endif
                                                        @if (userPermission('dormitory-list-delete'))
                                                            <a class="dropdown-item" data-toggle="modal"
                                                                data-target="#deleteDormitoryListModal{{ @$dormitory_list->id }}"
                                                                href="#">@lang('common.delete')</a>
                                                        @endif
                                                    </x-drop-down>
                                                </td>
                                            </tr>
                                            <div class="modal fade admin-query"
                                                id="deleteDormitoryListModal{{ @$dormitory_list->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('dormitory.delete_dormitory')</h4>
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
                                                                {{ Form::open(['route' => ['dormitory-list-delete', $dormitory_list->id], 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                                <button class="primary-btn fix-gr-bg"
                                                                    type="submit">@lang('common.delete')</button>
                                                                {{ Form::close() }}
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
