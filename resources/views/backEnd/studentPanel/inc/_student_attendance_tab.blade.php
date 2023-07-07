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

        th {
            padding: .5rem !important;
            font-size: 10px !important;
        }

        td {
            padding: .3rem !important;
            font-size: 9px !important;
        }

        .dataTables_filter {
            margin-top: 30px;
        }

        .dt-buttons {
            margin-top: 30px;
        }
    </style>
@endpush
<div role="tabpanel" class="tab-pane fade" id="studentAttendance">
    <div class="white-box">
        @if (isset($attendance))
            <section class="student-attendance">
                <div class="container-fluid p-0">
                    <div class="row mt-40">
                        <div class="col-lg-12 no-gutters d-flex align-items-center justify-content-between">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('student.student_attendance_report')
                                    <small>
                                        <span class="text-success">P:<span id="total_present"></span></span>
                                        <span class="text-warning">L:<span id="total_late"></span></span>
                                        <span class="text-danger">A:<span id="total_absent"></span></span>
                                        <span class="text-info">F:<span id="total_halfday"></span></span>
                                        <span class="text-dark">H:<span id="total_holiday"></span></span>
                                    </small>
                                </h3>
                            </div>
                            {{-- @if (moduleStatusCheck('University'))
                                <a href="{{ route('un-student-attendance-print', [$un_semester_label_id, $month, $year]) }}"
                                    class="primary-btn small fix-gr-bg pull-right" target="_blank"><i
                                        class="ti-printer">
                                    </i>@lang('common.print')</a>
                            @else
                                <a href="{{ route('student-attendance-print', [$class_id, $section_id, $month, $year]) }}"
                                    class="primary-btn small fix-gr-bg pull-right" target="_blank"><i
                                        class="ti-printer">
                                    </i>@lang('common.print')</a>
                            @endif --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="lateday d-flex mt-4">
                                <div class="mr-3">@lang('student.present'): <span class="text-success">P</span></div>
                                <div class="mr-3">@lang('student.late'): <span class="text-warning">L</span></div>
                                <div class="mr-3">@lang('student.absent'): <span class="text-danger">A</span></div>
                                <div class="mr-3">@lang('student.half_day'): <span class="text-info">F</span></div>
                                <div>@lang('student.holiday'): <span class="text-dark">H</span></div>
                            </div>
                            <div class="table-responsive">
                                <table id="table_id1" class="display school-table table-responsive" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="6%">@lang('student.name')</th>
                                            <th width="6%">@lang('student.admission_no')</th>
                                            <th width="3%">P</th>
                                            <th width="3%">L</th>
                                            <th width="3%">A</th>
                                            <th width="3%">F</th>
                                            <th width="3%">H</th>
                                            <th width="2%">%</th>
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
                                        @php
                                            $total_grand_present = 0;
                                            $total_late = 0;
                                            $total_absent = 0;
                                            $total_holiday = 0;
                                            $total_halfday = 0;
                                        @endphp
                                        @php $total_attendance = 0; @endphp
                                        @php $count_absent = 0; @endphp
                                        <tr>
                                            <td>
                                                @php $student = 0; @endphp
                                                @foreach ($attendance as $value)
                                                    @php $student++; @endphp
                                                    @if ($student == 1)
                                                        {{ $value->studentInfo->full_name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @php $student = 0; @endphp
                                                @foreach ($attendance as $value)
                                                    @php $student++; @endphp
                                                    @if ($student == 1)
                                                        {{ $value->studentInfo->admission_no }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @php $p = 0; @endphp
                                                @foreach ($attendance as $value)
                                                    @if ($value->attendance_type == 'P')
                                                        @php
                                                            $p++;
                                                            $total_attendance++;
                                                            $total_grand_present++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{ $p }}
                                            </td>
                                            <td>
                                                @php $l = 0; @endphp
                                                @foreach ($attendance as $value)
                                                    @if ($value->attendance_type == 'L')
                                                        @php
                                                            $l++;
                                                            $total_attendance++;
                                                            $total_late++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{ $l }}
                                            </td>
                                            <td>
                                                @php $a = 0; @endphp
                                                @foreach ($attendance as $value)
                                                    @if ($value->attendance_type == 'A')
                                                        @php
                                                            $a++;
                                                            $count_absent++;
                                                            $total_attendance++;
                                                            $total_absent++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{ $a }}
                                            </td>

                                            <td>
                                                @php $f = 0; @endphp
                                                @foreach ($attendance as $value)
                                                    @if ($value->attendance_type == 'F')
                                                        @php
                                                            $f++;
                                                            $total_attendance++;
                                                            $total_halfday++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{ $f }}
                                            </td>
                                            <td>
                                                @php $h = 0; @endphp
                                                @foreach ($attendance as $value)
                                                    @if ($value->attendance_type == 'H')
                                                        @php
                                                            $h++;
                                                            $total_attendance++;
                                                            $total_holiday++;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{ $h }}
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
                                                @php
                                                    $date = $year . '-' . $month . '-' . $i;
                                                    $y = 0;
                                                @endphp <td
                                                    width="3%" class="{{ $i <= 18 ? 'all' : 'none' }}">
                                                    @foreach ($attendance as $value)
                                                        @if (strtotime($value->attendance_date) == strtotime($date))
                                                            {{ $value->attendance_type }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endfor
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" id="total-attendance"
                                value="{{ $total_grand_present . '-' . $total_absent . '-' . $total_late . '-' . $total_halfday . '-' . $total_holiday }}">
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>
