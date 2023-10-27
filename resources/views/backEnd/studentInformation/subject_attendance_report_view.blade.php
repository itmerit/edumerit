@extends('backEnd.master')
@section('title')
    @lang('student.subject_wise_attendance')
@endsection

@section('mainContent')
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
                padding: .5rem !important;
                font-size: 10px !important;
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

            hr {
                margin: 0px;
            }

            .th {
                padding: .5rem !important;
                font-size: 10px !important;
            }
        </style>
    @endpush
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('student.student_attendance_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('student.student_information')</a>
                    <a href="#">@lang('student.student_attendance_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subject-attendance-report-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-3 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                    id="select_class" name="class">
                                    <option data-display="@lang('common.select_class')*" value="">@lang('common.select_class') *</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                                            {{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md" id="select_section_div">
                                <select
                                    class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                    id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                </select>
                                @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section') }}
                                    </span>
                                @endif
                            </div>
                            {{-- <div class="col-lg-4 mt-30-md" id="select_subject_div">
                                <select
                                    class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject"
                                    id="select_subject" name="subject">
                                    <option data-display="Select subject *" value="">@lang('student.select_subject') *</option>
                                </select>
                                @if ($errors->has('subject'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('subject') }}
                                    </span>
                                @endif
                            </div> --}}
                            @php $current_month = date('m'); @endphp
                            <div class="col-lg-3 mt-30-md">
                                <select class="primary_select {{ $errors->has('month') ? ' is-invalid' : '' }}"
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
                            <div class="col-lg-3 mt-30-md  ">
                                <select class="primary_select form-control{{ $errors->has('year') ? ' is-invalid' : '' }}"
                                    name="year">
                                    <option data-display="Select Year *" value="">@lang('student.select_year') *</option>
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
    @if (count($records)>0)
        <section class="student-attendance">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 no-gutters">
                        <div class="main-title mb-30">
                            <h3 class="mb-0">@lang('student.student_attendance_report')
                                <small> <span class="text-success">P:<span id="total_present"></span></span>
                                    <span class="text-warning">L:<span id="total_late"></span></span>
                                    <span class="text-danger">A:<span id="total_absent"></span></span>
                                    <span class="text-info">F:<span id="total_halfday"></span></span>
                                    <span class="text-dark">H:<span id="total_holiday"></span></span> </small>
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-6 no-gutters mb-30">
                        @if (userPermission(536))
                            <a href="{{ route('subject-attendance/print', [$class_id, $section_id, $month, $year]) }}"
                                class="primary-btn small fix-gr-bg pull-right"><i class="ti-printer">
                                </i>@lang('common.print')</a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="lateday d-flex flex-wrap">
                            <div class="mr-3 mb-10">@lang('student.present'): <span class="text-success">P</span></div>
                            <div class="mr-3 mb-10">@lang('student.late'): <span class="text-warning">L</span></div>
                            <div class="mr-3 mb-10">@lang('student.absent'): <span class="text-danger">A</span></div>
                            <div class="mr-3 mb-10">@lang('student.half_day'): <span class="text-info">F</span></div>
                            <div>@lang('student.holiday'): <span class="text-dark">H</span></div>
                        </div>
                    </div>
                </div>
                <div class="white-box">
                    <div class="row" style="padding:20px">
                        <div class="table-responsive" style="margin-bottom:25px">
                            <table id="table_id1" style="margin-bottom:25px" class="table table-responsive"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="6%">@lang('common.name')</th>
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
                                    @foreach ($records as $record)
                                        <tr>
                                            @foreach($record as $val)
                                                @if(is_array($val))
                                                    <td>{!! implode("<br>", $val) !!}</td>
                                                @else
                                                    <td>{{ $val }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@include('backEnd.partials.data_table_js')
