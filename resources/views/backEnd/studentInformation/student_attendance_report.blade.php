@extends('backEnd.master')
@section('title')
@lang('student.student_attendance_report')
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
</style>
@endpush
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.student_attendance_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('student.student_information')</a>
                <a href="#">@lang('student.student_attendance_report')</a>
            </div>
        </div>
    </div>
</section>
<input type="text" hidden value="{{ @$class->class_name }}" id="cls">
<input type="text" hidden value="{{ @$section->section_name }}" id="sec">
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('student.select_criteria') </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_attendance_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                    <div class="row">
                        @php $current_month = date('m'); @endphp
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        @if(moduleStatusCheck('University'))
                        @includeIf('university::common.session_faculty_depart_academic_semester_level',['hide'=>['USUB'],
                        'required'=>['USN','UD', 'UA', 'US','USL']])
                        <div class="col-lg-3 mt-25">
                            <select class="primary_select form-control{{ $errors->has('month') ? ' is-invalid' : '' }}"
                                name="month">
                                <option data-display="Select Month *" value="">@lang('common.select_month') *</option>
                                <option value="01"
                                    {{isset($month)? ($month == "01"? 'selected':''):($current_month == "01"? 'selected':'')}}>
                                    @lang('student.january')</option>
                                <option value="02"
                                    {{isset($month)? ($month == "02"? 'selected':''):($current_month == "02"? 'selected':'')}}>
                                    @lang('student.february')</option>
                                <option value="03"
                                    {{isset($month)? ($month == "03"? 'selected':''):($current_month == "03"? 'selected':'')}}>
                                    @lang('student.march')</option>
                                <option value="04"
                                    {{isset($month)? ($month == "04"? 'selected':''):($current_month == "04"? 'selected':'')}}>
                                    @lang('student.april')</option>
                                <option value="05"
                                    {{isset($month)? ($month == "05"? 'selected':''):($current_month == "05"? 'selected':'')}}>
                                    @lang('student.may')</option>
                                <option value="06"
                                    {{isset($month)? ($month == "06"? 'selected':''):($current_month == "06"? 'selected':'')}}>
                                    @lang('student.june')</option>
                                <option value="07"
                                    {{isset($month)? ($month == "07"? 'selected':''):($current_month == "07"? 'selected':'')}}>
                                    @lang('student.july')</option>
                                <option value="08"
                                    {{isset($month)? ($month == "08"? 'selected':''):($current_month == "08"? 'selected':'')}}>
                                    @lang('student.august')</option>
                                <option value="09"
                                    {{isset($month)? ($month == "09"? 'selected':''):($current_month == "09"? 'selected':'')}}>
                                    @lang('student.september')</option>
                                <option value="10"
                                    {{isset($month)? ($month == "10"? 'selected':''):($current_month == "10"? 'selected':'')}}>
                                    @lang('student.october')</option>
                                <option value="11"
                                    {{isset($month)? ($month == "11"? 'selected':''):($current_month == "11"? 'selected':'')}}>
                                    @lang('student.november')</option>
                                <option value="12"
                                    {{isset($month)? ($month == "12"? 'selected':''):($current_month == "12"? 'selected':'')}}>
                                    @lang('student.december')</option>

                            </select>
                            @if ($errors->has('month'))
                            <span class="text-danger">
                                {{ $errors->first('month') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-25">
                            <select class="primary_select form-control{{ $errors->has('year') ? ' is-invalid' : '' }}"
                                name="year">
                                <option data-display="Select Year *" value="">@lang('student.select_year') *</option>
                                @php
                                $current_year = date('Y');
                                $ini = date('y');
                                $limit = $ini + 30;
                                @endphp
                                @for($i = $ini; $i <= $limit; $i++) <option value="{{$current_year}}"
                                    {{isset($year)? ($year == $current_year? 'selected':''):(date('Y') == $current_year? 'selected':'')}}>
                                    {{$current_year--}}</option>
                                    @endfor
                            </select>
                            @if ($errors->has('year'))
                            <span class="text-danger">
                                {{ $errors->first('year') }}
                            </span>
                            @endif
                        </div>
                        @else
                        <div class="col-lg-3 mt-30-md">
                            <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                id="select_class" name="class">
                                <option data-display="@lang('student.select_class') *" value="">
                                    @lang('student.select_class') *</option>
                                @foreach($classes as $class)
                                <option value="{{$class->id}}"
                                    {{ isset($class_id)? ($class_id == $class->id? 'selected':''): (old("class") == $class->id ? "selected":"")}}>
                                    {{$class->class_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('class'))
                            <span class="text-danger">
                                {{ $errors->first('class') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md" id="select_section_div">
                            <select
                                class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                id="select_section" name="section">
                                <option data-display="@lang('student.select_section') *" value="">
                                    @lang('student.select_section') *</option>
                            </select>
                            <div class="pull-right loader loader_style" id="select_section_loader">
                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}"
                                    alt="loader">
                            </div>
                            @if ($errors->has('section'))
                            <span class="text-danger">
                                {{ $errors->first('section') }}
                            </span>
                            @endif
                        </div>


                        <div class="col-lg-3 mt-30-md">
                            <select class="primary_select form-control{{ $errors->has('month') ? ' is-invalid' : '' }}"
                                name="month">
                                <option data-display="Select Month *" value="">@lang('common.select_month') *</option>
                                <option value="01"
                                    {{isset($month)? ($month == "01"? 'selected':''):($current_month == "01"? 'selected':'')}}>
                                    @lang('student.january')</option>
                                <option value="02"
                                    {{isset($month)? ($month == "02"? 'selected':''):($current_month == "02"? 'selected':'')}}>
                                    @lang('student.february')</option>
                                <option value="03"
                                    {{isset($month)? ($month == "03"? 'selected':''):($current_month == "03"? 'selected':'')}}>
                                    @lang('student.march')</option>
                                <option value="04"
                                    {{isset($month)? ($month == "04"? 'selected':''):($current_month == "04"? 'selected':'')}}>
                                    @lang('student.april')</option>
                                <option value="05"
                                    {{isset($month)? ($month == "05"? 'selected':''):($current_month == "05"? 'selected':'')}}>
                                    @lang('student.may')</option>
                                <option value="06"
                                    {{isset($month)? ($month == "06"? 'selected':''):($current_month == "06"? 'selected':'')}}>
                                    @lang('student.june')</option>
                                <option value="07"
                                    {{isset($month)? ($month == "07"? 'selected':''):($current_month == "07"? 'selected':'')}}>
                                    @lang('student.july')</option>
                                <option value="08"
                                    {{isset($month)? ($month == "08"? 'selected':''):($current_month == "08"? 'selected':'')}}>
                                    @lang('student.august')</option>
                                <option value="09"
                                    {{isset($month)? ($month == "09"? 'selected':''):($current_month == "09"? 'selected':'')}}>
                                    @lang('student.september')</option>
                                <option value="10"
                                    {{isset($month)? ($month == "10"? 'selected':''):($current_month == "10"? 'selected':'')}}>
                                    @lang('student.october')</option>
                                <option value="11"
                                    {{isset($month)? ($month == "11"? 'selected':''):($current_month == "11"? 'selected':'')}}>
                                    @lang('student.november')</option>
                                <option value="12"
                                    {{isset($month)? ($month == "12"? 'selected':''):($current_month == "12"? 'selected':'')}}>
                                    @lang('student.december')</option>

                            </select>
                            @if ($errors->has('month'))
                            <span class="text-danger">
                                {{ $errors->first('month') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md">
                            <select class="primary_select form-control{{ $errors->has('year') ? ' is-invalid' : '' }}"
                                name="year">
                                <option data-display="Select Year *" value="">@lang('student.select_year') *</option>
                                @php
                                $current_year = date('Y');
                                $ini = date('y');
                                $limit = $ini + 30;
                                @endphp
                                @for($i = $ini; $i <= $limit; $i++) <option value="{{$current_year}}"
                                    {{isset($year)? ($year == $current_year? 'selected':''):(date('Y') == $current_year? 'selected':'')}}>
                                    {{$current_year--}}</option>
                                    @endfor
                            </select>
                            @if ($errors->has('year'))
                            <span class="text-danger">
                                {{ $errors->first('year') }}
                            </span>
                            @endif
                        </div>
                        @endif
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
@if(isset($attendances))
<style>
    .dataTables_filter {
        margin-top: 30px;
    }

    .dt-buttons {
        margin-top: 30px;
    }
</style>
<section class="student-attendance">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-12 no-gutters d-flex align-items-center justify-content-between">
                <div class="main-title">
                    <h3 class="mb-0">@lang('student.student_attendance_report')
                        <small> <span class="text-success">P:<span id="total_present"></span></span>
                            <span class="text-warning">L:<span id="total_late"></span></span>
                            <span class="text-danger">A:<span id="total_absent"></span></span>
                            <span class="text-info">F:<span id="total_halfday"></span></span>
                            <span class="text-dark">H:<span id="total_holiday"></span></span> </small>
                    </h3>
                </div>

                @if(moduleStatusCheck('University'))
                <a href="{{route('un-student-attendance-print', [$un_semester_label_id, $month, $year])}}"
                    class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer">
                    </i>@lang('common.print')</a>
                @else
                <a href="{{route('student-attendance-print', [$class_id, $section_id, $month, $year])}}"
                    class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer">
                    </i>@lang('common.print')</a>
                @endif
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
                <div class="white-box">
                    <div class="table-responsive" style="padding:20px">
                        <table id="table_id1" style="margin-bottom:25px" class="display school-table table-responsive"
                            cellspacing="0" width="100%">
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
                                    @for($i = 1; $i<=$days; $i++) <th width="3%" class="{{($i<=18)? 'all':'none'}}">
                                        {{$i}} <br>
                                        @php
                                        $date = $year.'-'.$month.'-'.$i;
                                        $day = date("D", strtotime($date));
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
                                @foreach($attendances as $values)
                                @if($values[0]->studentInfo)
                                @php $total_attendance = 0; @endphp
                                @php $count_absent = 0; @endphp
                                <tr>
                                    <td>
                                        @php $student = 0; @endphp
                                        @foreach($values as $value)
                                        @php $student++; @endphp
                                        @if($student == 1)
                                        {{$value->studentInfo->full_name}}
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $student = 0; @endphp
                                        @foreach($values as $value)
                                        @php $student++; @endphp
                                        @if($student == 1)
                                        {{$value->studentInfo->admission_no}}
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $p = 0; @endphp
                                        @foreach($values as $value)
                                        @if($value->attendance_type == 'P')
                                        @php $p++; $total_attendance++; $total_grand_present++; @endphp
                                        @endif
                                        @endforeach
                                        {{$p}}
                                    </td>
                                    <td>
                                        @php $l = 0; @endphp
                                        @foreach($values as $value)
                                        @if($value->attendance_type == 'L')
                                        @php $l++; $total_attendance++; $total_late++; @endphp
                                        @endif
                                        @endforeach
                                        {{$l}}
                                    </td>
                                    <td>
                                        @php $a = 0; @endphp
                                        @foreach($values as $value)
                                        @if($value->attendance_type == 'A')
                                        @php $a++; $count_absent++; $total_attendance++; $total_absent++; @endphp
                                        @endif
                                        @endforeach
                                        {{$a}}
                                    </td>

                                    <td>
                                        @php $f = 0; @endphp
                                        @foreach($values as $value)
                                        @if($value->attendance_type == 'F')
                                        @php $f++; $total_attendance++; $total_halfday++; @endphp
                                        @endif
                                        @endforeach
                                        {{$f}}
                                    </td>
                                    <td>
                                        @php $h = 0; @endphp
                                        @foreach($values as $value)
                                        @if($value->attendance_type == 'H')
                                        @php $h++; $total_attendance++; $total_holiday++; @endphp
                                        @endif
                                        @endforeach
                                        {{$h}}
                                    </td>
                                    <td>
                                        @php
                                        $total_present = $total_attendance - $count_absent;
                                        if($count_absent == 0){
                                        echo '100%';
                                        }else{
                                        $percentage = $total_present / $total_attendance * 100;
                                        echo number_format((float)$percentage, 2, '.', '').'%';
                                        }
                                        @endphp

                                    </td>
                                    @for($i = 1; $i<=$days; $i++) @php $date=$year.'-'.$month.'-'.$i; $y=0; @endphp <td
                                        width="3%" class="{{($i<=18)? 'all':'none'}}">
                                        @foreach($values as $value)
                                        @if(strtotime($value->attendance_date) == strtotime($date))
                                        {{$value->attendance_type}}
                                        @endif
                                        @endforeach
                                        </td>
                                        @endfor
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" id="total-attendance"
                    value="{{$total_grand_present.'-'.$total_absent.'-'.$total_late.'-'.$total_halfday.'-'.$total_holiday}}">
            </div>
        </div>
    </div>
</section>
@endif
@endsection
@include('backEnd.partials.data_table_js')