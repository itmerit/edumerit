@extends('backEnd.master')
@section('title')
@lang('student.student_registration')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.student_registration')</h1>
            <div class="bc-pages">
                <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">@lang('student.custom_field')</a>
                <a href="#">@lang('student.student_registration')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if (isset($v_custom_field))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{ route('student-reg-custom-field') }}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-8 col-md-6 col-sm-6">
                <div class="main-title mt_0_sm mt_0_md">
                    <h3 class="mb-30">
                        @if (isset($v_custom_field))
                        @lang('student.edit_custom_field')
                        @else
                        @lang('student.add_custom_field')
                        @endif

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    @if (isset($v_custom_field))
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'update-student-registration-custom-field', 'method' => 'POST']) }}
                    <input type="hidden" name="id" value="{{ $v_custom_field->id }}">
                    @else
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'store-student-registration-custom-field', 'method' => 'POST']) }}
                    @endif
                    @include('backEnd.customField._custom_form', ['type' => 'student_online_regi'])
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="row mt-40 full_wide_table">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('student.custom_field_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row  ">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('student.label')</th>
                                        <th>@lang('common.type')</th>
                                        <th>@lang('student.width')</th>
                                        <th>@lang('student.required')</th>
                                        <th>@lang('student.value')</th>
                                        @if (moduleStatusCheck('ParentRegistration') == true)
                                        {{-- added for online student registration custom field showing --abunayem --}}
                                        <th>@lang('student.available_for_online_registration')</th>
                                        @endif
                                        <th>@lang('common.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($custom_fields as $key => $custom_field)
                                    @php
                                    $v_lengths = json_decode($custom_field->min_max_length);
                                    $v_values = json_decode($custom_field->min_max_value);
                                    $v_name_values = json_decode($custom_field->name_value);
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $custom_field->label }}</td>
                                        <td>
                                            @if ($custom_field->type == 'textInput')
                                            @lang('student.text_input')
                                            @elseif ($custom_field->type == 'numericInput')
                                            @lang('student.numeric_input')
                                            @elseif ($custom_field->type == 'multilineInput')
                                            @lang('student.multiline_input')
                                            @elseif ($custom_field->type == 'datepickerInput')
                                            @lang('student.datepicker_input')
                                            @elseif ($custom_field->type == 'checkboxInput')
                                            @lang('student.checkbox_input')
                                            @elseif ($custom_field->type == 'radioInput')
                                            @lang('student.radio_input')
                                            @elseif ($custom_field->type == 'dropdownInput')
                                            @lang('student.dropdown_input')
                                            @else
                                            @lang('student.file_input')
                                            @endif
                                            </br>
                                            @if ($custom_field->type == 'textInput' || $custom_field->type ==
                                            'multilineInput')
                                            <small>
                                                @lang('student.min_length') : {{ $v_lengths[0] }}
                                            </small>
                                            </br>
                                            <small>
                                                @lang('student.max_length') : {{ $v_lengths[1] }}
                                            </small>
                                            </br>
                                            @endif

                                            @if ($custom_field->type == 'numericInput')
                                            <small>
                                                @lang('student.min_value') : {{ $v_values[0] }}
                                            </small>
                                            </br>
                                            <small>
                                                @lang('student.max_value') : {{ $v_values[1] }}
                                            </small>
                                            </br>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($custom_field->width == 'col-12')
                                            @lang('student.full_width')
                                            @elseif ($custom_field->width == 'col-6')
                                            @lang('student.half_width')
                                            @elseif ($custom_field->width == 'col-4')
                                            @lang('student.one_fourth_width')
                                            @elseif($custom_field->width == 'col-3')
                                            @lang('student.one_thired_width')
                                            @endif
                                        </td>
                                        <td>
                                            @if ($custom_field->required == 1)
                                            @lang('student.required')
                                            @else
                                            @lang('student.not_required')
                                            @endif
                                        </td>

                                        <td>
                                            @if (
                                            $custom_field->type == 'checkboxInput' ||
                                            $custom_field->type == 'radioInput' ||
                                            $custom_field->type == 'dropdownInput')
                                            @if ($v_name_values)
                                            @foreach ($v_name_values as $v_name_value)
                                            {{ @$v_name_value }}@if (!$loop->last),
                                            @endif
                                            @endforeach
                                            @endif
                                            @endif
                                        </td>
                                        @if (moduleStatusCheck('ParentRegistration') == true)
                                        {{-- added for online student registration custom field showing --abunayem --}}
                                        <td>
                                            @if ($custom_field->is_showing == 1)
                                            @lang('common.yes')
                                            @else
                                            @lang('common.no')
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            <x-drop-down>
                                                @if (userPermission(1103))
                                                <a class="dropdown-item"
                                                    href="{{ route('edit-custom-field', ['id' => @$custom_field->id]) }}">@lang('common.edit')</a>
                                                @endif
                                                @if (userPermission(1104))
                                                <a class="dropdown-item" data-toggle="modal"
                                                    data-target="#deleteCustomField{{ @$custom_field->id }}"
                                                    href="#">
                                                    @lang('common.delete')
                                                </a>
                                                @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>

                                    <div class="modal fade admin-query"
                                        id="deleteCustomField{{ @$custom_field->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('common.delete_custom_field')</h4>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                            data-dismiss="modal">
                                                            @lang('common.cancel')
                                                        </button>
                                                        {{ Form::open(['route' => 'delete-custom-field', 'method' => 'POST']) }}
                                                        <input type="hidden" name="id"
                                                            value="{{ @$custom_field->id }}">
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