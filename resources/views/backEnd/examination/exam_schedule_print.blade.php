<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{__('Exam Routine')}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
    <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head>
<style>
        @page {
    margin-top: 0px;
    margin-bottom: 0px;
    }
    table, th, tr, td {
        font-size: 11px !important;
        /* text-align: center; */
 
    }
    .routineBorder{
        /* border-bottom: 1px solid; */
      
    }


</style>
<body style="font-family: 'dejavu sans', sans-serif;">

<div class="container-fluid" id="pdf">

    <table cellspacing="0" width="100%">
        <tr>
            <td>
                <img  style="padding-top: 20px;"  src="{{ url('/')}}/{{@generalSetting()->logo }}" alt="">
            </td>
               <td>
                <h3 style="font-size:20px !important; margin: 5px 0 0 0" class="text-white mb-0"> @lang('exam.exam_routine') </h3>
                <span style="font-size:14px !important;" 
                class="text-white">@lang('common.academic_year') : {{ @$academic_year->title}} ({{ @$academic_year->year}}) </span> </br>
                <span style="font-size:14px !important;margin-right:10px;" align="left"
                class="text-white">@lang('exam.exam'): {{ @$exam_type}} </span>

                <span style="font-size:14px !important;" class="text-white">
                    @lang('common.academic_year') : {{ @$academic_year->title}} ({{ @$academic_year->year}})
                </span> </br>


             <span style="font-size:14px !important;margin-right:10px;" align="left"
                class="text-white">@lang('common.class'): {{ @$class_name}} </span>
             <span style="font-size:14px !important;;margin-right:10px;" align="left"
                class="text-white">@lang('common.section'): {{ @$section_name}} </span>
             </td>
            <td style="text-aligh:center">               
                 
                    <h3 style="font-size:20px !important; margin: 5px 0 0 0"
                    class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                    <p style="font-size:16px !important;margin:0px"
                    class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                  
            </td>
        </tr>
    </table>
    <hr style="margin:0px;padding-top:6px;padding-bottom:0px">

    <table class="table table-bordered table-striped" style="width: 100%; table-layout: fixed">


        <tr>
            <th style="width:10%;padding: 2px; padding-left:8px;" >
                @lang('common.date_|_day')
            </th>
            <th style="padding: 2px; padding-left:8px;">@lang('common.subject')</th>
            <th style="padding: 2px; padding-left:8px;">@lang('common.class_Sec')</th>
            <th style="padding: 2px; padding-left:8px;">@lang('common.teacher')</th>         
            <th style="padding: 2px; padding-left:8px;">@lang('common.time')</th>         
            <th style="padding: 2px; padding-left:8px;">@lang('common.duration')</th>         
            <th style="padding: 2px; padding-left:8px;">@lang('common.room')</th>
        </tr>

        <tbody>
            @foreach ($exam_schedules as $item)               
          
            <tr>
                <td >{{ dateConvert($item->date) }} <br>{{ Carbon::createFromFormat('Y-m-d', $item->date)->format('l') }}</td>
                <td>
                  <strong> {{ $item->subject ? $item->subject->subject_name :'' }} </strong> 
                </td>
                <td>{{ $item->class ? $item->class->class_name :'' }} {{ $item->section ? '('. $item->section->section_name .')':'' }}</td>
                <td>{{ $item->teacher ? $item->teacher->full_name :'' }}</td>
               
                <td> {{ date('h:i A', strtotime(@$item->start_time))  }} - {{ date('h:i A', strtotime(@$item->end_time))  }} </td>
                <td> 
                    @php
                    $duration=strtotime($item->end_time)-strtotime($item->start_time); 
                @endphp
             
               {{ timeCalculation($duration)}}  
                </td>
               
                <td>{{ $item->classRoom ? $item->classRoom->room_no :''  }}</td>
               
            </tr>
            @endforeach
        </tbody>
       
    </table>
</div>

<script src="{{ asset('public/vendor/spondonit/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2canvas.min.js') }}"></script>

<script>
    function generatePDF() {
        const element = document.getElementById('pdf');
        var opt = {
            margin:       0.5,
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '#page2el' },
            filename:     'exam-schedule.pdf',
            image:        { type: 'jpeg', quality: 100 },
            html2canvas:  { scale: 5 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(element).save().then(function(){
            // window.close();
        });
    }



    $(document).ready(function(){

        generatePDF();

    })
</script>

</body>
</html>

