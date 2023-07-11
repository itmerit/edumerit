@extends('backEnd.master')
@section('title')
    @lang('hr.staff_attendance')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.staff_attendance')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('hr.human_resource')</a>
                    <a href="#">@lang('hr.staff_attendance')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <a href="{{ route('staff-attendance-import') }}" class="primary-btn small fix-gr-bg pull-right "><span
                            class="ti-plus pr-2"></span>@lang('hr.import_attendance')</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staff-attendance-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}

                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-6 mt-30-md col-md-6">
                                <label class="primary_input_label" for="">@lang('hr.role') <span
                                        class="text-danger"> *</span></label>
                                <select class="primary_select  form-control{{ $errors->has('role') ? ' is-invalid' : '' }}"
                                    id="select_class" name="role">
                                    <option data-display="@lang('hr.select_role') *" value="">@lang('hr.select_role') *</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ isset($role_id) ? ($role->id == $role_id ? 'selected' : '') : '' }}>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('role') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-6 mt-30-md col-md-6">
                                <div class="primary_input mb-15">
                                    <label for="startDate">@lang('hr.attendance_date')<span class="text-danger"> *</span></label>
                                    <div class="primary_datepicker_input">
                                        <div class="no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="">
                                                    <input class="primary_input_field primary_input_field date form-control"
                                                        {{ isset($date) ? 'read-only-input' : '' }}" id="startDate"
                                                        type="text" name="attendance_date" autocomplete="off"
                                                        value="{{ isset($date) ? $date : date('m/d/Y') }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                            <button class="btn-date" type="button">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg" id='btnsubmit'>
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            @if (isset($staffs))
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-30">@lang('hr.staff_attendance')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 no-gutters">
                                @if ($attendance_type != '' && $attendance_type == 'H')
                                    <div class="alert alert-warning">@lang('hr.attendance_already_submitted_as_holiday')</div>
                                @elseif($attendance_type != '' && $attendance_type != 'H')
                                    <div class="alert alert-success">@lang('hr.attendance_already_submitted')</div>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-20">
                            <div class="col-lg-6  col-md-6 no-gutters text-md-left mark-holiday ">
                                @if ($attendance_type != 'H')
                                    <form action="{{ route('staff-holiday-store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="purpose" value="mark">
                                        <input type="hidden" name="role_id" value="{{ $role_id }}">
                                        <input type="hidden" name="date" value="{{ $date }}">
                                        <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                            @lang('hr.mark_holiday')
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('staff-holiday-store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="purpose" value="unmark">
                                        <input type="hidden" name="role_id" value="{{ $role_id }}">
                                        <input type="hidden" name="date" value="{{ $date }}">
                                        <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                            @lang('hr.unmark_holiday')
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staff-attendance-store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input class="staff_attendance_date" type="hidden" name="date"
                            value="{{ isset($date) ? $date : '' }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table school-table-style" cellspacing="0" width="100%">
                                    <thead>

                                        <tr>
                                            <th>@lang('hr.staff_no')</th>
                                            <th>@lang('hr.staff_name')</th>
                                            <th>@lang('hr.role')</th>
                                            <th>@lang('hr.attendance')</th>
                                            <th>@lang('hr.note')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($staffs as $staff)
                                            <tr>
                                                <td>{{ $staff->staff_no }}<input type="hidden" name="id[]"
                                                        value="{{ $staff->id }}"></td>
                                                <td>{{ $staff->first_name . ' ' . $staff->last_name }}</td>
                                                <td>{{ $staff->roles != '' ? $staff->roles->name : '' }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="mr-20">
                                                            <input type="radio" name="attendance[{{ $staff->id }}]"
                                                                id="attendanceP{{ $staff->id }}" value="P"
                                                                class="common-radio attendanceP attendance_type_staff"
                                                                {{ $staff->DateWiseStaffAttendance != null ? ($staff->DateWiseStaffAttendance->attendence_type == 'P' ? 'checked' : '') : 'checked' }}>
                                                            <label
                                                                for="attendanceP{{ $staff->id }}">@lang('hr.present')</label>
                                                        </div>
                                                        <div class="mr-20">
                                                            <input type="radio" name="attendance[{{ $staff->id }}]"
                                                                id="attendanceL{{ $staff->id }}" value="L"
                                                                class="common-radio attendance_type_staff"
                                                                {{ $staff->DateWiseStaffAttendance != null ? ($staff->DateWiseStaffAttendance->attendence_type == 'L' ? 'checked' : '') : '' }}>
                                                            <label
                                                                for="attendanceL{{ $staff->id }}">@lang('hr.late')</label>
                                                        </div>
                                                        <div class="mr-20">
                                                            <input type="radio" name="attendance[{{ $staff->id }}]"
                                                                id="attendanceA{{ $staff->id }}" value="A"
                                                                class="common-radio attendance_type_staff"
                                                                {{ $staff->DateWiseStaffAttendance != null ? ($staff->DateWiseStaffAttendance->attendence_type == 'A' ? 'checked' : '') : '' }}>
                                                            <label
                                                                for="attendanceA{{ $staff->id }}">@lang('hr.absent')</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="primary_input">
                                                        <textarea class="primary_input_field form-control note_{{ $staff->id }}" cols="0" rows="2"
                                                            name="note[{{ $staff->id }}]" id="">{{ $staff->DateWiseStaffAttendance != null ? $staff->DateWiseStaffAttendance->notes : '' }}</textarea>
                                                        <label class="primary_input_label"
                                                            for="">@lang('hr.add_note_here')</label>
                                                        <span class="focus-border textarea"></span>
                                                        <span class="invalid-feedback">
                                                            <strong>@lang('hr.error')</strong>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">
                                                <button type="submit" class="primary-btn mr-40 fix-gr-bg nowrap submit">
                                                    @lang('hr.save_attendance')
                                                </button>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@include('backEnd.partials.date_picker_css_js')
