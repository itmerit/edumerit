@extends('backEnd.master')
@section('title')
@lang('hr.designation')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('hr.designation')</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">@lang('hr.human_resource')</a>
                <a href="#">@lang('hr.designation')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor up_st_admin_visitor pl_22">
    <div class="container-fluid p-0">
        @if (isset($designation))
        @if (userPermission('designation-store'))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{ route('designation') }}" class="primary-btn small fix-gr-bg">
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
                                @if (isset($designation))
                                @lang('hr.edit_designation')
                                @else
                                @lang('hr.add_designation')
                                @endif
                            </h3>
                        </div>
                        @if (isset($designation))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['designation-update', $designation->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        @if (userPermission('designation-store'))
                        {{ Form::open([
                                        'class' => 'form-horizontal',
                                        'files' => true,
                                        'route' => 'designation',
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('hr.designation_title')
                                                <span class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" autocomplete="off"
                                                value="{{ isset($designation) ? $designation->title : Request::old('title') }}">
                                            <input type="hidden" name="id"
                                                value="{{ isset($designation) ? $designation->id : '' }}">

                                            @if ($errors->has('title'))
                                            <span class="text-danger">
                                                {{ $errors->first('title') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php
                                $tooltip = '';
                                if (userPermission('designation-store')) {
                                    $tooltip = '';
                                }elseif(isset($designation) && userPermission('designation-edit')){
                                    $tooltip = '';
                                } else {
                                    $tooltip = 'You have no permission to add';
                                }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ $tooltip }}">
                                            <span class="ti-check"></span>
                                            @isset($designation)
                                            @lang('hr.update_designation')
                                            @else
                                            @lang('hr.save_designation')
                                            @endisset
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
                            <h3 class="mb-0">@lang('hr.designation_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">

                                <thead>

                                    <tr>
                                        <th>@lang('hr.designation')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($designations as $designation)
                                    <tr>
                                        <td>{{ $designation->title }}</td>
                                        <td>
                                            <x-drop-down>
                                                @if (userPermission('designation-edit'))
                                                <a class="dropdown-item"
                                                    href="{{ route('designation-edit', [$designation->id]) }}">@lang('common.edit')</a>
                                                @endif
                                                @if (userPermission('designation-delete'))
                                                <a class="dropdown-item" data-toggle="modal"
                                                    data-target="#deleteDesignationModal{{ $designation->id }}"
                                                    href="#">@lang('common.delete')</a>
                                                @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query"
                                        id="deleteDesignationModal{{ $designation->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('hr.delete_designation')</h4>
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
                                                        {{ Form::open(['route' => ['designation-delete', $designation->id], 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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