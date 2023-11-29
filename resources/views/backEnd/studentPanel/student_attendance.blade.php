@extends('backEnd.master')
@section('title')
@lang('student.attendance')
@endsection
@push('css')
<style>
    #table_id1{
        border: 1px solid var(--border_color);

    }
    #table_id1 td{
        border: 1px solid var(--border_color);
        text-align:center;
    }
    #table_id1 th{
        border: 1px solid var(--border_color);
        text-align:center;
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
      height:5px;
      border-radius: 0;
    }
    .table-responsive::-webkit-scrollbar {
      width: 5px;
      height: 5px
    }
    .table-responsive::-webkit-scrollbar-track {
      height: 5px !important ;
      background: #ddd;
      border-radius: 0;
      box-shadow: inset 0 0 5px grey
    }
    th{
    padding: .5rem !important;
    font-size: 10px !important;
    }
    td{
        padding: .3rem !important;
        font-size: 9px !important;
    }
    </style>
@endpush
@section('mainContent')
<style>
    th{
        padding: .5rem !important;
        font-size: 10px !important;
    }
    td{
        padding: .3rem !important;
        font-size: 9px !important;  
    }
    td {
        padding: 20px 10px 20px 10px !important;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.attendance')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('student_my_attendance')}}">@lang('student.attendance')</a>
            </div>
        </div>
    </div>
</section>
@php
    $year=date('Y');
     
@endphp
<section class="admin-visitor-area">
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
                        @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                        @elseif(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                        </div>
                        @endif
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_my_attendance', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                
                                
                                <div class="col-lg-6 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('month') ? ' is-invalid' : '' }}" name="month">
                                        <option data-display="Select Month *" value="">@lang('student.select_month') *</option>
                                        <option value="01" {{isset($month)? ( @$month == "01"? 'selected': ''): ''}}>@lang('student.january')</option>
                                        <option value="02" {{isset($month)? ( @$month == "02"? 'selected': ''): ''}}>@lang('student.february')</option>
                                        <option value="03" {{isset($month)? ( @$month == "03"? 'selected': ''): ''}}>@lang('student.march')</option>
                                        <option value="04" {{isset($month)? ( @$month == "04"? 'selected': ''): ''}}>@lang('student.april')</option>
                                        <option value="05" {{isset($month)? ( @$month == "05"? 'selected': ''): ''}}>@lang('student.may')</option>
                                        <option value="06" {{isset($month)? ( @$month == "06"? 'selected': ''): ''}}>@lang('student.june')</option>
                                        <option value="07" {{isset($month)? ( @$month == "07"? 'selected': ''): ''}}>@lang('student.july')</option>
                                        <option value="08" {{isset($month)? ( @$month == "08"? 'selected': ''): ''}}>@lang('student.august')</option>
                                        <option value="09" {{isset($month)? ( @$month == "09"? 'selected': ''): ''}}>@lang('student.september')</option>
                                        <option value="10" {{isset($month)? ( @$month == "10"? 'selected': ''): ''}}>@lang('student.october')</option>
                                        <option value="11" {{isset($month)? ( @$month == "11"? 'selected': ''): ''}}>@lang('student.november')</option>
                                        <option value="12" {{isset($month)? ( @$month == "12"? 'selected': ''): ''}}>@lang('student.december')</option>
                                    </select>
                                    @if ($errors->has('month'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('month') }}
                                    </span>
                                    @endif
                                </div>
                              
                                <div class="col-lg-6 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('year') ? ' is-invalid' : '' }}" name="year">
                                        <option data-display="Select Year *" value="">@lang('student.select_year') *</option>
                                        @php
                                        $current_year = date('Y');
                                        $ini = date('y');
                                        $limit = $ini + 30;
                                    @endphp
                                    @for($i = $ini; $i <= $limit; $i++)
                                        <option value="{{$current_year}}" {{isset($year)? ($year == $current_year? 'selected':''):(date('Y') == $current_year? 'selected':'')}}>{{$current_year--}}</option>
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
                                        search
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>


    </div>
</section>


@if(isset($records))

<section class="student-attendance">
    <div class="container-fluid p-0">

        <div class="row mt-40">
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                    @foreach($records as $key => $record)
                        <li class="nav-item">
                            <a class="nav-link @if($key== 0) active @endif " href="#tab{{$key}}" role="tab" data-toggle="tab">{{$record->class->class_name}} ({{$record->section->section_name}}) </a>
                        </li>
                    @endforeach
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    @foreach($records as $key => $record)
                        <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                            <div class="row">
                                <div class="col-lg-12 d-flex justify-content-between my-3">
                                    <div class="lateday d-flex">
                                        <div class="mr-3">@lang('student.present'): <span class="text-success">P</span></div>
                                        <div class="mr-3">@lang('student.late'): <span class="text-warning">L</span></div>
                                        <div class="mr-3">@lang('student.absent'): <span class="text-danger">A</span></div>
                                        <div class="mr-3">@lang('student.half_day'): <span class="text-info">F</span></div>
                                        <div>@lang('student.holiday'): <span class="text-dark">H</span></div>
                                    </div>
                                    <a href="{{route('my-attendance/print', [$record->id,$month, $year])}}" class="primary-btn small fix-gr-bg " target="_blank"><i class="ti-printer"> </i> @lang('common.print')</a>
                                </div>

                                <div class="col-lg-12">

                                    <div class="white-box">
                                        <div class="table-responsive" style="padding:20px">
                                            <table id="table_id1" style="margin-bottom:25px" class="table table-responsive" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="3%">P</th>
                                                        <th width="3%">L</th>
                                                        <th width="3%">A</th>
                                                        <th width="3%">H</th>
                                                        <th width="3%">F</th>
                                                        <th width="2%">%</th>
                                                        @for($i = 1;  $i<=@$days; $i++)
                                                        <th width="3%" class="{{($i<=18)? 'all':'none'}}">
                                                            {{$i}} <br>
                                                            @php
                                                                @$date = @$year.'-'.@$month.'-'.$i;
                                                                @$day = date("D", strtotime(@$date));
                                                                echo @$day;
                                                            @endphp
                                                        </th>
                                                        @endfor
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php $studentAttendance = $record->studentAttendanceByMonth($month, $year); @endphp
                                                    @php @$total_attendance = 0; @endphp
                                                    @php @$count_absent = 0; @endphp
                                                    <tr>
                                                        <td>
                                                            @php $p = 0; @endphp
                                                            @foreach($studentAttendance as $value)
                                                                @if(@$value->attendance_type == 'P')
                                                                    @php $p++; @$total_attendance++; @endphp
                                                                @endif
                                                            @endforeach
                                                            {{$p}}
                                                        </td>
                                                        <td>
                                                            @php $l = 0; @endphp
                                                            @foreach($studentAttendance as $value)
                                                                @if(@$value->attendance_type == 'L')
                                                                    @php $l++; @$total_attendance++; @endphp
                                                                @endif
                                                            @endforeach
                                                            {{$l}}
                                                        </td>
                                                        <td>
                                                            @php $a = 0; @endphp
                                                            @foreach($studentAttendance as $value)
                                                                @if(@$value->attendance_type == 'A')
                                                                    @php $a++; @$count_absent++; @$total_attendance++; @endphp
                                                                @endif
                                                            @endforeach
                                                            {{$a}}
                                                        </td>
                                                        <td>
                                                            @php $h = 0; @endphp
                                                            @foreach($studentAttendance as $value)
                                                                @if(@$value->attendance_type == 'H')
                                                                    @php $h++; @$total_attendance++; @endphp
                                                                @endif
                                                            @endforeach
                                                            {{$h}}
                                                        </td>
                                                        <td>
                                                            @php $f = 0; @endphp
                                                            @foreach($studentAttendance as $value)
                                                                @if(@$value->attendance_type == 'F')
                                                                    @php $f++; @$total_attendance++; @endphp
                                                                @endif
                                                            @endforeach
                                                            {{$f}}
                                                        </td>
                                                        <td>
                                                        @php
                                                            @$total_present = @$total_attendance - @$count_absent;
                                                            if(@$count_absent == 0){
                                                                echo '100%';
                                                            }else{
                                                                @$percentage = @$total_present / @$total_attendance * 100;
                                                                echo number_format((float)@$percentage, 2, '.', '').'%';
                                                            }
                                                        @endphp

                                                        </td>
                                                        @for($i = 1;  $i<=@$days; $i++)
                                                            @php
                                                                @$date = @$year.'-'.@$month.'-'.$i;
                                                            @endphp
                                                            <td width="3%" class="{{($i<=18)? 'all':'none'}}">
                                                                @foreach($studentAttendance as $value)
                                                                    @if(strtotime(@$value->attendance_date) == strtotime(@$date))
                                                                        {{@$value->attendance_type}}
                                                                    @endif
                                                                @endforeach
                                                            </td>

                                                        @endfor
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>
@endif


@endsection
@include('backEnd.partials.data_table_js')