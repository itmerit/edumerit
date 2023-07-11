<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('student.student_attendance')  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head>
<style>
 table,th,tr,td{
     font-size: 11px !important;
     padding: 0px !important;
     text-align: center !important;
 }
 
</style>
<body>

<div class="container-fluid">

    <table cellspacing="0" width="100%">
        <tr>
            <td>
                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt="">
            </td>
            <td>
                <h3 style="font-size:22px !important"
                    class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                <p style="font-size:18px !important"
                   class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                <p style="font-size:15px !important" class="text-white mb-0"> @lang('student.attendance') </p>
            </td>
            <td style="text-aligh:center">
                <p style="font-size:14px !important; border-bottom:1px solid gray" alaign="left"
                   class="text-white">@lang('common.name'): {{ $student_detail->full_name}} </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" alaign="left"
                   class="text-white">@lang('student.month'): {{ date("F", strtotime('00-'.$month.'-01')) }} </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" alaign="left"
                   class="text-white">@lang('student.year'): {{ $year }} </p>

            </td>
        </tr>
    </table>

    <hr>

    <table class="table table-bordered table-striped" cellspacing="0" width="100%">


        <tr>
        <!-- <th>@lang('common.name')</th>
                            <th>@lang('student.admission_no')</th> -->
                            <th>P</th>
                            <th>L</th>
                            <th>A</th>
                            <th>H</th>
                            <th>F</th>
                            <th>%</th>
                            @for($i = 1;  $i<=$days; $i++)
                            <th class="{{($i<=18)? 'all':'none'}}">
                                {{$i}} <br>
                                @php
                                    $date = $year.'-'.$month.'-'.$i;
                                    $day = date("D", strtotime($date));
                                    echo $day;
                                @endphp
                            </th>
                            @endfor
                        </tr>
               
                        @php $total_attendance = 0; @endphp
                        @php $count_absent = 0; @endphp
                        <tr>
                            <!-- <td>
                                @php $student = 0; @endphp
                                @foreach($attendances as $value)
                                    @php $student++; @endphp
                                    @if($student == 1)
                                        {{$student_detail->full_name}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @php $student = 0; @endphp
                                @foreach($attendances as $value)
                                    @php $student++; @endphp
                                    @if($student == 1)
                                        {{$student_detail->admission_no}}
                                    @endif
                                @endforeach
                            </td> -->

                            <td>
                                @php $p = 0; @endphp
                                @foreach($attendances as $value)
                                    @if($value->attendance_type == 'P')
                                        @php $p++; $total_attendance++; @endphp
                                    @endif
                                @endforeach
                                {{$p}}
                            </td>
                            <td>
                                @php $l = 0; @endphp
                                @foreach($attendances as $value)
                                    @if($value->attendance_type == 'L')
                                        @php $l++; $total_attendance++; @endphp
                                    @endif
                                @endforeach
                                {{$l}}
                            </td>
                            <td>
                                @php $a = 0; @endphp
                                @foreach($attendances as $value)
                                    @if($value->attendance_type == 'A')
                                        @php $a++; $count_absent++; $total_attendance++; @endphp
                                    @endif
                                @endforeach
                                {{$a}}
                            </td>
                            <td>
                                @php $h = 0; @endphp
                                @foreach($attendances as $value)
                                    @if($value->attendance_type == 'H')
                                        @php $h++; $total_attendance++; @endphp
                                    @endif
                                @endforeach
                                {{$h}}
                            </td>
                            <td>
                                @php $f = 0; @endphp
                                @foreach($attendances as $value)
                                    @if($value->attendance_type == 'F')
                                        @php $f++; $total_attendance++; @endphp
                                    @endif
                                @endforeach
                                {{$f}}
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
                            @for($i = 1;  $i<=$days; $i++)
                                @php
                                    $date = $year.'-'.$month.'-'.$i;
                                @endphp
                                <td class="{{($i<=18)? 'all':'none'}}">
                                    @foreach($attendances as $value)
                                        @if(strtotime($value->attendance_date) == strtotime($date))
                                            {{$value->attendance_type}}
                                        @endif
                                    @endforeach
                                </td>
                               
                            @endfor
                        </tr>
                        
                </table>
        </div>  
 

</body>
</html>
    

