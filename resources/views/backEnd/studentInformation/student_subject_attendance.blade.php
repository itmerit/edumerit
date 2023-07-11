<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('student.student_attendance')  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>
 table,th,tr,td{
     font-size: 11px !important;
     padding: 0px !important;
     text-align: center !important; 
 }
</style>
<style>
    #attendance.th,#attendance.tr,#attendance.td{
        font-size: 10px !important;
        padding: 0px !important;
        text-align: center !important;
        border:1px solid #ddd;
        vertical-align: middle !important;
    }
    #attendance th{
        background: #ddd;
        text-align: center;
    }
    #attendance{
        border: 1px solid var(--border_color);
           border-collapse: collapse;
    }
    #attendance tr{
        border: 1px solid var(--border_color);
           border-collapse: collapse;
    }
    #attendance th{
        border: 1px solid var(--border_color);
           border-collapse: collapse;
           text-align: center !important;
           font-size: 11px;
    }
    #attendance td{
        border: 1px solid var(--border_color);
           border-collapse: collapse;
           text-align: center;
           font-size: 10px;
    }
    .nowrap{
        white-space: nowrap;
    }
   </style>
<body style="font-family: 'dejavu sans', sans-serif;">
@php
    $generalSetting= generalSetting(); 
    if(!empty($generalSetting)){
        $school_name =$generalSetting->school_name;
        $site_title =$generalSetting->site_title;
        $school_code =$generalSetting->school_code;
        $address =$generalSetting->address;
        $phone =$generalSetting->phone; 
    } 
    $class=DB::table('sm_classes')->find($class_id);
    $section=DB::table('sm_sections')->find($section_id);
@endphp
<div class="container-fluid">
                    <table  cellspacing="0" width="100%" >
                        <tr>
                            <td> 
                                <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt=""> 
                            </td>
                            <td> 
                                <h3 style="font-size:22px !important" class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p> 
                                <p style="font-size:15px !important" class="text-white mb-0">@lang('student.student_attendance') </p>
                          </td>
                            <td style="text-aligh:center"> 
                                @if(moduleStatusCheck('University'))
                                    <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">{{ __('university::un.department') }}:{{ isset($unSemesterLabel) ? $unSemesterLabel->departmentDetails->name .'('. (isset($unDepartment) ? $unDepartment->name:'').')':''}} </p>
                
                                    <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('university::un.semester(label)'): {{ isset($unSemesterLabel) ? $unSemesterLabel->semesterDetails->name .'('. (isset($unSemesterLabel) ? $unSemesterLabel->name : '') .')' :''}} </p>
                                @else                                   
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Class: {{ $class->class_name}} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Section: {{ $section->section_name}} </p> 
                                @endif
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Month: {{ date("F", strtotime('00-'.$month.'-01')) }} </p> 
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">Year: {{ $year }} </p>
                          </td>
                        </tr>
                    </table>
                    <table style="width: 100%; table-layout: fixed" id="attendance">
                        <thead>
                                <tr>
                                    <th width="3%">SL</th>
                                    <th width="8%">@lang('common.name')</th>
                                    <th width="10%">@lang('student.admission_no')</th>
                                    <th width="3%">P</th>
                                    <th width="3%">L</th>
                                    <th width="3%">A</th>
                                    <th width="3%">F</th>
                                    <th width="3%">H</th>
                                    <th width="5%">%</th>
                                    @for($i = 1;  $i<=$days; $i++)
                                    <th width="3%" class="{{($i<=18)? 'all':'none'}} nowrap">
                                        {{$i}} <br>
                                        @php
                                            $date = $year.'-'.$month.'-'.$i;
                                            $day = date("D", strtotime($date));
                                            echo substr($day,0,2);
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
                                $countstudent=1;
                                @endphp
                                @foreach($attendances as $values)
                                @php $total_attendance = 0; @endphp
                                @php $count_absent = 0; @endphp
                                <tr>
                                <td>{{$countstudent++}}</td>
                                    <td>
                                        @php $student = 0; @endphp
                                        @foreach($values as $value)
                                            @php $student++; @endphp
                                            @if($student == 1)
                                                {{@$value->student->full_name}}
                                            @endif
                                        @endforeach
                                       
                                    </td>
                                    <td>
                                        @php $student = 0; @endphp
                                        @foreach($values as $value)
                                            @php $student++; @endphp
                                            @if($student == 1)
                                                {{@$value->student->admission_no}}
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
                                        @endphp
                                            {{$total_present.'/'.$total_attendance}}
                                            <hr>
                                        @php
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
                                        $y = 0;
                                    @endphp
                                    <td width="3%" class="{{($i<=18)? 'all':'none'}}">
                                        @php
                                            $date_present=0;
                                            $date_absent=0;
                                            $date_total_class=0;
                                        @endphp
                                        @foreach($values as $key => $value)
                                            @if(strtotime($value->attendance_date) == strtotime($date))
                                            @php
                                                if($value->attendance_type=='P' || $value->attendance_type=='F' || $value->attendance_type=='L'){
                                                    $date_present++;
                                                }else{
                                                    $date_absent++;
                                                }
                                                $date_total_class=$date_present+$date_absent;
                                            @endphp
                                                {{-- {{$value->attendance_type}} --}}
                                            @endif
                                        @endforeach
                                                {{-- Date Report --}}
                                        @if ($date_total_class!=0)
                                        {{$date_present.'/'.$date_total_class}}
                                        <hr>
                                        @php
                                        if($date_absent == 0){
                                            echo '100%';
                                        }else{
                                            // echo $date_present;
                                            if ($date_present!=0) {
                                            
                                                $date_percentage = $date_present / $date_total_class * 100;
                                                echo @number_format((float)$date_percentage, 2, '.', '').'%';
                                            }else{
                                                echo '0%';
                                            }
                                        }
                                    @endphp
                                        @endif
                                    </td>
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                </table>
        </div>
</body>
</html>
    

