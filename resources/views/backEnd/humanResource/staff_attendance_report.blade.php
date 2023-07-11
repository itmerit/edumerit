@extends('backEnd.master')
@section('title')
    @lang('hr.staff_attendance_report')
@endsection

@push('css')
    <style>
        #table_id1 {
            border: 1px solid var(--border_color);
        }

        #table_id1 td {
            border: 1px solid var(--border_color);
            text-align: center;
        }

        #table_id1 th {
            border: 1px solid var(--border_color);
            text-align: center;
        }

        .main-wrapper {
            display: block;
            width: auto;
            align-items: stretch;
        }

        .main-wrapper {
            display: block;
            width: auto;
            align-items: stretch;
        }

        #main-content {
            width: auto;
        }

        #table_id1 td {
            border: 1px solid var(--border_color);
            text-align: center;
            padding: 7px;
            flex-wrap: nowrap;
            white-space: nowrap;
            font-size: 11px
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #828bb2;
            height: 5px;
            border-radius: 0;
        }

        .table-responsive::-webkit-scrollbar {
            width: 5px;
            height: 5px
        }

        .table-responsive::-webkit-scrollbar-track {
            height: 5px !important;
            background: #ddd;
            border-radius: 0;
            box-shadow: inset 0 0 5px grey
        }

        .attendance_hr {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        #table_id_student_wrapper th,
        #table_id_student_wrapper td {
            text-align: center;
            padding-left: 8px;
            padding-right: 8px;
        }
    </style>
@endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.staff_attendance_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('hr.human_resource')</a>
                    <a href="#">@lang('hr.staff_attendance_report')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area ">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staff_attendance_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-4">
                                <label class="primary_input_label" for="">@lang('hr.role') <span
                                        class="text-danger">
                                        *</span></label>
                                <select class="primary_select form-control{{ $errors->has('role') ? ' is-invalid' : '' }}"
                                    id="select_class" name="role">
                                    <option data-display="@lang('hr.select_role')*" value="">@lang('hr.select_role')
                                        *
                                    </option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ isset($role_id) ? ($role->id == $role_id ? 'selected' : '') : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('role') }}
                                    </span>
                                @endif
                            </div>
                            @php $current_month = date('m'); @endphp
                            <div class="col-lg-4">
                                <label class="primary_input_label" for="">@lang('student.select_month') <span
                                        class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('month') ? ' is-invalid' : '' }}"
                                    name="month">
                                    <option data-display="Select Month *" value="">@lang('student.select_month') *</option>
                                    <option value="01"
                                        {{ isset($month) ? ($month == '01' ? 'selected' : '') : ($current_month == '01' ? 'selected' : '') }}>
                                        @lang('student.january')</option>
                                    <option value="02"
                                        {{ isset($month) ? ($month == '02' ? 'selected' : '') : ($current_month == '02' ? 'selected' : '') }}>
                                        @lang('student.february')</option>
                                    <option value="03"
                                        {{ isset($month) ? ($month == '03' ? 'selected' : '') : ($current_month == '03' ? 'selected' : '') }}>
                                        @lang('student.march')</option>
                                    <option value="04"
                                        {{ isset($month) ? ($month == '04' ? 'selected' : '') : ($current_month == '04' ? 'selected' : '') }}>
                                        @lang('student.april')</option>
                                    <option value="05"
                                        {{ isset($month) ? ($month == '05' ? 'selected' : '') : ($current_month == '05' ? 'selected' : '') }}>
                                        @lang('student.may')</option>
                                    <option value="06"
                                        {{ isset($month) ? ($month == '06' ? 'selected' : '') : ($current_month == '06' ? 'selected' : '') }}>
                                        @lang('student.june')</option>
                                    <option value="07"
                                        {{ isset($month) ? ($month == '07' ? 'selected' : '') : ($current_month == '07' ? 'selected' : '') }}>
                                        @lang('student.july')</option>
                                    <option value="08"
                                        {{ isset($month) ? ($month == '08' ? 'selected' : '') : ($current_month == '08' ? 'selected' : '') }}>
                                        @lang('student.august')</option>
                                    <option value="09"
                                        {{ isset($month) ? ($month == '09' ? 'selected' : '') : ($current_month == '09' ? 'selected' : '') }}>
                                        @lang('student.september')</option>
                                    <option value="10"
                                        {{ isset($month) ? ($month == '10' ? 'selected' : '') : ($current_month == '10' ? 'selected' : '') }}>
                                        @lang('student.october')</option>
                                    <option value="11"
                                        {{ isset($month) ? ($month == '11' ? 'selected' : '') : ($current_month == '11' ? 'selected' : '') }}>
                                        @lang('student.november')</option>
                                    <option value="12"
                                        {{ isset($month) ? ($month == '12' ? 'selected' : '') : ($current_month == '12' ? 'selected' : '') }}>
                                        @lang('student.december')</option>
                                </select>
                                @if ($errors->has('month'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('month') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <label class="primary_input_label" for="">@lang('hr.select_year') <span
                                        class="text-danger">
                                        *</span></label>
                                <select class="primary_select form-control{{ $errors->has('year') ? ' is-invalid' : '' }}"
                                    name="year"
                                    id="year">
                                    <option data-display="@lang('hr.select_year') *" value="">@lang('hr.select_year') *
                                    </option>
                                    @php
                                        $current_year = date('Y');
                                        $ini = date('y');
                                        $limit = $ini + 30;
                                    @endphp
                                    @for ($i = $ini; $i <= $limit; $i++)
                                        <option value="{{ $current_year }}"
                                            {{ isset($year) ? ($year == $current_year ? 'selected' : '') : (date('Y') == $current_year ? 'selected' : '') }}>
                                            {{ $current_year-- }}</option>
                                    @endfor
                                </select>
                                @if ($errors->has('year'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('year') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if (isset($attendances))
        <section class="student-attendance up_admin_visitor">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('hr.staff_attendance_report')</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <a href="{{ route('staff-attendance/print', [$role_id, $month, $year]) }}"
                            class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>
                            @lang('common.print')</a>
                    </div>
                </div>
                <div class="white-box mt-5">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="lateday d-flex mt-4 justify-content-center">
                                <div class="mr-3">@lang('hr.present'): <span class="text-success">P</span></div>
                                <div class="mr-3">@lang('hr.late'): <span class="text-warning">L</span></div>
                                <div class="mr-3">@lang('hr.absent'): <span class="text-danger">A</span></div>
                                <div class="mr-3">@lang('hr.holiday'): <span class="text-dark">H</span></div>
                                <div>@lang('hr.half_day'): <span class="text-info">F</span></div>
                            </div>
                            <div class="table-responsive pt-30">
                                <div id="table_id_student_wrapper" class="dataTables_wrapper no-footer">
                                    <table id="table_id1" style="margin-bottom:25px" class="table table-responsive"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="6%">@lang('hr.staff_name')</th>
                                                <th width="6%">@lang('hr.staff_no')</th>
                                                <th width="6%">P</th>
                                                <th width="6%">L</th>
                                                <th width="6%">A</th>
                                                <th width="6%">H</th>
                                                <th width="6%">F</th>
                                                <th width="6%">%</th>
                                                @for ($i = 1; $i <= $days; $i++)
                                                    <th width="3%" class="{{ $i <= 18 ? 'all' : 'none' }}">
                                                        {{ $i }} <br>
                                                        @php
                                                            $date = $year . '-' . $month . '-' . $i;
                                                            $day = date('D', strtotime($date));
                                                            echo $day;
                                                        @endphp
                                                    </th>
                                                @endfor
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($attendances as $values)
                                                @php $total_attendance = 0; @endphp
                                                @php $count_absent = 0; @endphp
                                                <tr>
                                                    <td>
                                                        @php $student = 0; @endphp
                                                        @foreach ($values as $value)
                                                            @php $student++; @endphp
                                                            @if ($student == 1)
                                                                {{ $value->staffInfo->full_name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @php $student = 0; @endphp
                                                        @foreach ($values as $value)
                                                            @php $student++; @endphp
                                                            @if ($student == 1)
                                                                {{ $value->staffInfo->staff_no }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @php $p = 0; @endphp
                                                        @foreach ($values as $value)
                                                            @if ($value->attendence_type == 'P')
                                                                @php
                                                                    $p++;
                                                                    $total_attendance++;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        {{ $p }}
                                                    </td>
                                                    <td>
                                                        @php $l = 0; @endphp
                                                        @foreach ($values as $value)
                                                            @if ($value->attendence_type == 'L')
                                                                @php
                                                                    $l++;
                                                                    $total_attendance++;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        {{ $l }}
                                                    </td>
                                                    <td>
                                                        @php $a = 0; @endphp
                                                        @foreach ($values as $value)
                                                            @if ($value->attendence_type == 'A')
                                                                @php
                                                                    $a++;
                                                                    $count_absent++;
                                                                    $total_attendance++;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        {{ $a }}
                                                    </td>
                                                    <td>
                                                        @php $h = 0; @endphp
                                                        @foreach ($values as $value)
                                                            @if ($value->attendence_type == 'H')
                                                                @php
                                                                    $h++;
                                                                    $total_attendance++;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        {{ $h }}
                                                    </td>
                                                    <td>
                                                        @php $f = 0; @endphp
                                                        @foreach ($values as $value)
                                                            @if ($value->attendence_type == 'F')
                                                                @php
                                                                    $f++;
                                                                    $total_attendance++;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        {{ $f }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $total_present = $total_attendance - $count_absent;
                                                            if ($count_absent == 0) {
                                                                echo '100%';
                                                            } else {
                                                                $percentage = ($total_present / $total_attendance) * 100;
                                                                echo number_format((float) $percentage, 2, '.', '') . '%';
                                                            }
                                                        @endphp

                                                    </td>
                                                    @for ($i = 1; $i <= $days; $i++)
                                                        @php $date=$year.'-'.$month.'-'.$i; @endphp <td
                                                            width="3%" class="{{ $i <= 18 ? 'all' : 'none' }}">
                                                            @foreach ($values as $value)
                                                                @if (strtotime($value->attendence_date) == strtotime($date))
                                                                    {{ $value->attendence_type }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endfor
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


@endsection
@include('backEnd.partials.data_table_js')
