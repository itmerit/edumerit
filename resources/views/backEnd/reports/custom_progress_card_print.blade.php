<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('exam.progress_card')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head>
<style>
    body, table, th, td {
        font-size: 10px;
        font-family: 'Poppins', sans-serif;

    }
    
   .marklist th, .marklist td{
        border: 1px solid var(--border_color);  
        text-align: center !important;
        font-family: 'Poppins', sans-serif;
        
    }
    .marklist th{
        text-transform: capitalize;
        text-align: center;  
    }
    .marklist td{
        text-align: center; 
    }
 
 
    .container{ 
        padding-bottom: 50px;
        background: white;
        font-family: 'Poppins', sans-serif;
    }
    h1,h2,h3,h4{

        font-family: "Poppins", sans-serif; 
        margin-bottom: 15px;
    }
    hr{
        margin: 0px;
    }
    .mt-10 {
        margin-top:10px;
    }
    .mb-10{
        margin-bottom:10px;
    }
  


    #grade_table th{
        border: 1px solid black;
        text-align: center;
        background: #351681;
        color: white;
    }
    #grade_table td{
        color: black;
        text-align: center;
        border: 1px solid black;
    }



   .custom_table td {
    border: 1px solid var(--border_color);
    padding: .8rem;
    text-align: center;
  }
  .custom_table th{
    border: 1px solid var(--border_color);
    text-transform: uppercase;
    text-align: center;
  }
  thead{
    font-weight:bold;
    text-align:center;
    color: #222;
    font-size: 10px
  }
  
  table {
    border-collapse: collapse;
  }
  
  .footer {
    text-align:right;
    font-weight:bold;
  }


.custom_table{
    width:100%;
}
.custom_table {
    width: 98%;
    margin: auto;
}
.custom_table th{
    
        border: 1px solid black;
        text-align: center;
        background: #351681;
        color: white;
        font-size: 12px;
        line-height: 1;
        padding: .8rem;
        /* border-right: 1px solid white; */
    }

</style>

<script>
        var is_chrome = function () { return Boolean(window.chrome); }
        if(is_chrome) 
        {
           window.print();
        //    setTimeout(function(){window.close();}, 10000); 
           //give them 10 seconds to print, then close
        }
        else
        {
           window.print();
           window.close();
        }
        </script>
    <body onLoad="loadHandler();" style="font-family: 'dejavu sans', sans-serif;">

<div class="container"> 
        <table style="width:100%; border:0;"> 
                <tr >
                    
                    <th>
                        <img class="logo-img" src="{{ url('/')}}/{{generalSetting()->logo }}" alt=""> 
                    </th>
                    <th colspan="2"> 
                        <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3> 
                        <p class="text-white mb-0" style="padding-right:10px !important;"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                    </th> 

                </tr>  
                <tr>
                    <td colspan="3"><hr></td>
                </tr>
                <tr>
                    <td  style=" padding:10px; vertical-align:top;">
                        
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('common.name') : <span class="primary-color fw-500">{{$studentDetails->full_name}}</span> </p>
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('common.academic_year') : <span class="primary-color fw-500">{{generalSetting()->session_year}}</span> </p>
                        {{-- <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('exam.exam') : <span class="primary-color fw-500">{{@$exam_name}}</span> </p> --}}
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('common.class') : <span class="primary-color fw-500">{{@$class->class_name}}</span> </p>
                        <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> @lang('common.section') : <span class="primary-color fw-500">{{@$section->section_name}}</span> </p>
                    </td>
                    <td  style="  padding:10px; vertical-align:top;"> 
                        <p style="font-weight: 700;">@lang('exam.subjects_list')</p> 
                        <div class="row">
                            <div class="col-md-12 w-100" style="columns: 2">
                                @foreach($assign_subjects as $subject)
                                <p class="mb-0" style="padding-right:10px !important; font-size:11px;"> <span class="primary-color fw-500">{{$subject->subject->subject_name}}</span> </p>
                                @endforeach 
                            </div>
                        </div>
                    </td>
                    <td  style=" padding:10px; vertical-align:top;">
                         @php $marks_grade=DB::table('sm_marks_grades')->where('academic_id', getAcademicId())->get(); @endphp
                                                    @if(@$marks_grade)
                                                        <table class="table  table-bordered table-striped " id="grade_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('exam.starting')</th>
                                                                    <th>@lang('exam.Ending')</th>
                                                                    <th>@lang('exam.gpa')</th>
                                                                    <th>@lang('exam.grade')</th>
                                                                    <th>@lang('homework.evalution')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                    
                                                                @foreach($marks_grade as $grade_d)
                                                                    <tr>
                                                                        <td>{{$grade_d->percent_from}}</td>
                                                                        <td>{{$grade_d->percent_upto}}</td>
                                                                        <td>{{$grade_d->gpa}}</td>
                                                                        <td>{{$grade_d->grade_name}}</td>
                                                                        <td class="text-left">{{$grade_d->description}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif 
                    </td>
                </tr> 
        </table>


        
        <h4 style=" text-align: center;  padding: 10px;">@lang('exam.student_progress_card')</h4> 

        @php
        $final_result=0;
        @endphp
        @foreach ($assigned_exam as $key => $item)
        @php
                $percentage=0;
            @endphp
            @if ($key==0)
                @php
                    $percentage=$custom_result_setup->percentage1;
                @endphp
            @endif
            @if ($key==1)
                @php
                $percentage=$custom_result_setup->percentage2;
                @endphp
            @endif
            @if ($key==2)
                @php
                $percentage=$custom_result_setup->percentage3;
                @endphp
         @endif
        <div class="student_marks_table" style="margin-top:20px">
            <table class="custom_table">
              <thead>
                <tr>
                  <th colspan="{{ $assign_subjects->count()+11 }}" class="full_width" >{{$item->title}} {{ $percentage }}% </th>
                </tr>
                <tr>
                    @foreach ($assign_subjects as $subject)
                    <td colspan="2">{{ $subject->subject_name }} </td>
                     @endforeach
                <td rowspan="{{ $assign_subjects->count()+11}}" > @lang('exam.gpa') </td>
                </tr>
              </thead>
              <tbody>
                <tr>
                    @foreach ($assign_subjects as $subject)
                    <td>@lang('common.mark')</td>
                    <td>@lang('exam.gpa')</td>
                    @endforeach
                  <td rowspan="{{ $assign_subjects->count()+11 }}" >
                    @php
                        $term_gpa=App\CustomResultSetting::termResult($item->exam_type_id,$input_class,$input_section,$input_student,$assign_subjects->count());
                        echo number_format((float)$term_gpa, 2, '.', '');
                        $term_final_gpa=App\CustomResultSetting::getFinalResult($item->exam_type_id,$input_class,$input_section,$input_student,$percentage);
                        $final_result= $final_result+ $term_final_gpa;
                    @endphp
                </td>
                </tr>
                <tr>
                    @foreach ($assign_subjects as $subject)
                            <td> 
                                @php
                                    $gpa=App\CustomResultSetting::getSubjectGpa($item->exam_type_id,$input_class,$input_section,$input_student,$subject->subject_id);
                                    $subject_mark=$gpa[$subject->subject_id][0];
                                    $subject_gpa=$gpa[$subject->subject_id][1];
                                    echo $subject_mark;
                                @endphp
                                </td>
                            <td>
                                    @php
                                    $grade=App\CustomResultSetting::getDrade($subject_mark);
                                    echo $grade;
                                @endphp
                            </td>
                        @endforeach
                  
                </tr>
              </tbody>
            </table>
          </div>
           @php
                $first_term_percentage='percentage1';
                $second_term_percentage='percentage2';
                $third_term_percentage='percentage3';

                $first_term_result=App\CustomResultSetting::getFinalResult($item->exam_type_id,$input_class,$input_section,$input_student,$first_term_percentage);
                $second_term_result=App\CustomResultSetting::getFinalResult($item->exam_type_id,$input_class,$input_section,$input_student,$second_term_percentage);
                $third_term_result=App\CustomResultSetting::getFinalResult($item->exam_type_id,$input_class,$input_section,$input_student,$third_term_percentage);

                $student_final_result=$first_term_result+$second_term_result+$third_term_result;
           @endphp                
@endforeach
<div class="student_marks_table" style="margin-top:20px;">
        <table class="custom_table">
    <thead>
       
        <th>
            Final GPA : {{  $student_final_result }}
        </th>
        <th>
            Final Grade : {{  App\CustomResultSetting::gpaToGrade($student_final_result) }}
        </th>
    </thead>
</table>
</div>
        <table style="width:100%">
            <tr> 
                <td> 
                    <p style="padding-top:10px; text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">( Exam Controller )</p> 
                </td>
            </tr>

        </table>
       
               
 

</body>
</html>
    
