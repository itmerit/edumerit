@extends('backEnd.master')
@section('title')
    @lang('admin.admission_query')
@endsection
@section('mainContent')
    <style>
        #table_id_wrapper {
            margin-top: 50px;
        }

        table.dataTable thead .sorting_asc::after,
        table.dataTable thead .sorting::after {
            top: 10px !important;
            left: 15px !important;
        }

        .ti-calendar:before {
            position: relative !important;
            bottom: 47px !important;
            left: 305px !important;
        }
        table.dataTable thead th {
            padding-left: 30px !important;
        }
        table.dataTable tbody th, table.dataTable tbody td {
            padding: 20px 10px 20px 18px !important;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('admin.manage_admin')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('admin.admin_section')</a>
                    <a href="{{ route('admission_query') }}">@lang('admin.admission_query')</a>
                    <a href="{{ route('add_query', [@$admission_query->id]) }}">@lang('admin.follow_up')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('admin.follow_up_admission_query')</h3>
                            </div>
                        </div>
                    </div>
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'query_followup_store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class="row mt-30">
                                    <input type="hidden" name="id" id="id" value="{{ @$admission_query->id }}">
                                    <div class="col-lg-4">
                                        <div class="no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <label class="primary_input_label"
                                                        for="">@lang('admin.follow_up_date')</label>
                                                    <input
                                                        class="primary_input_field  primary_input_field date form-control form-control{{ @$errors->has('follow_up_date') ? ' is-invalid' : '' }}"
                                                        id="startDate" type="text"
                                                        name="follow_up_date" readonly="true"
                                                        value="{{ date('m/d/Y', strtotime(@$admission_query->next_follow_up_date)) }}">

                                                    @if ($errors->has('follow_up_date'))
                                                        <span
                                                            class="text-danger">{{ @$errors->first('follow_up_date') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button class="" type="button">
                                                <label class="m-0 p-0" for="startDate">
                                                    <i class="ti-calendar" id="admission-date-icon"></i>
                                                </label>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('admin.next_follow_up_date')<span
                                                            class="text-danger"> *</span></label>
                                                    <input
                                                        class="primary_input_field  primary_input_field date form-control form-control{{ @$errors->has('next_follow_up_date') ? ' is-invalid' : '' }}"
                                                        id="endDate" type="text"
                                                        name="next_follow_up_date" autocomplete="off" readonly="true"
                                                        readonly="true" value="{{ date('m/d/Y', strtotime(' + 1 days')) }}">

                                                    @if ($errors->has('next_follow_up_date'))
                                                        <span
                                                            class="text-danger">{{ @$errors->first('next_follow_up_date') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="" type="button">
                                                    <label class="m-0 p-0" for="endDate">
                                                        <i class="ti-calendar" id="end-date-icon"></i>
                                                    </label>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="primary_input_label" for="">@lang('common.status') <span
                                                class="text-danger"> *</span> </label>
                                        <select class="primary_select " name="status">
                                            <option value="1"
                                                {{ @$admission_query->active_status == '1' ? 'selected' : '' }}>
                                                @lang('admin.active')</option>
                                            <option value="2"
                                                {{ @$admission_query->active_status == '2' ? 'selected' : '' }}>
                                                @lang('admin.inactive')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('admin.response') <span
                                                    class="text-danger"> *</span> </label>
                                            <textarea class="primary_input_field form-control{{ @$errors->has('response') ? ' is-invalid' : '' }}" cols="0"
                                                rows="3" name="response" id="address">{{ old('response') }}</textarea>


                                            @if ($errors->has('response'))
                                                <span class="text-danger">
                                                    <strong>{{ @$errors->first('response') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('admin.note')
                                                <span></span> </label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="3" name="note" id="description">{{ old('note') }}</textarea>


                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @lang('admin.save')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                    <div class="row mt-40">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4 no-gutters">
                                    <div class="main-title">
                                        <h3 class="mb-0"> @lang('admin.follow_up_list')</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">

                                    <table id="table_id" class="table" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>
                                                <th>@lang('common.sl')</th>
                                                <th>@lang('admin.query_by')</th>
                                                <th>@lang('admin.response')</th>
                                                <th>@lang('admin.note')</th>
                                                <th>@lang('common.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($follow_up_lists as $key => $follow_up_list)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ @$follow_up_list->user != '' ? @$follow_up_list->user->full_name : '' }}
                                                    </td>
                                                    <td>{{ @$follow_up_list->response }}</td>
                                                    <td>{{ @$follow_up_list->note }}</td>

                                                    <td valign="top">
                                                        <x-drop-down>
                                                            <a class="dropdown-item" data-toggle="modal"
                                                                data-target="#deletefollowUpQuery{{ @$follow_up_list->id }}"
                                                                href="">@lang('admin.delete')</a>
                                                        </x-drop-down>
                                                    </td>
                                                </tr>
                                                <div class="modal fade admin-query"
                                                    id="deletefollowUpQuery{{ @$follow_up_list->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('admin.delete_follow_up_query')</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                </div>

                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('admin.cancel')</button>
                                                                    <a href="{{ route('delete_follow_up', [@$follow_up_list->id]) }}"
                                                                        class="text-light primary-btn fix-gr-bg">@lang('admin.delete')
                                                                    </a>
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
                <div class="col-lg-4 mt-45">
                    <div class="student-meta-box">
                        <div class="white-box radius-t-y-0 student-details">
                            <div class="single-meta mt-10">
                                <h3 class="mb-30">@lang('common.details') </h3>
                            </div>
                            <div class="single-meta mt-10">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('common.created_by'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->user != '' ? @$admission_query->user->full_name : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.query_date'):
                                    </div>
                                    <div class="value">
                                        {{ !empty(@$admission_query->date) ? dateConvert(@$admission_query->date) : '' }}

                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.last_follow_up_date'):
                                    </div>
                                    <div class="value">
                                        {{ !empty(@$admission_query->follow_up_date) ? dateConvert(@$admission_query->follow_up_date) : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.next_follow_up_date'):
                                    </div>
                                    <div class="value">
                                        {{ !empty(@$admission_query->next_follow_up_date) ? dateConvert(@$admission_query->next_follow_up_date) : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.phone'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->phone }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.address'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->address }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.reference'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->reference != '' ? @$admission_query->referenceSetup->name : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.description'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->description }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.source'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->source != '' ? @$admission_query->sourceSetup->name : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.assigned'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->assigned }}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.email'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->email }}
                                    </div>
                                </div>
                            </div>
                            @if (moduleStatusCheck('University'))
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('university::un.session'):
                                        </div>
                                        <div class="value">
                                            {{ @$admission_query->unSession->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('university::un.faculty'):
                                        </div>
                                        <div class="value">
                                            {{ @$admission_query->unFaculty->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('university::un.academic_year'):
                                        </div>
                                        <div class="value">
                                            {{ @$admission_query->unAcademic->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('university::un.semester'):
                                        </div>
                                        <div class="value">
                                            {{ @$admission_query->unSemester->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('university::un.semester_level'):
                                        </div>
                                        <div class="value">
                                            {{ @$admission_query->unSemesterLabel->name }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('common.class'):
                                        </div>
                                        <div class="value">
                                            {{ @$admission_query->class != '' ? @$admission_query->className->class_name : '' }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('admin.number_of_child'):
                                    </div>
                                    <div class="value">
                                        {{ @$admission_query->no_of_child }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
