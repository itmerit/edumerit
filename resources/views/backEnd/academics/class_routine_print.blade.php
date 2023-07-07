<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{__('Class Routine')}}</title>
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
 
    }
    .routineBorder{
        /* border-bottom: 1px solid; */
      
    }

</style>
<body style="font-family: 'dejavu sans', sans-serif;">

<div class="container-fluid"  id="pdf">

    <table cellspacing="0" width="100%">
        <tr>
            <td>
                <img  src="{{ url('/')}}/{{@generalSetting()->logo }}" style="padding-top: 20px;" alt="">
            </td>
            <td style="text-aligh:left"> 
                <h3 style="font-size:20px !important; margin-bottom : 0;margin-top: 0px;" class="text-white mb-0"> @lang('academics.class_routine') </h3>
                <span style="font-size:11px !important;margin-right:10px;" align="left"
                class="text-white">@lang('common.class_Sec'): {{ @$class->class_name.'('.$section->section_name.')' }} </span>
             <span style="font-size:11px !important;" align="left"
                class="text-white">@lang('common.academic_year'): {{ @$academic_year->title}}
                 ({{ @$academic_year->year}}) </span>
            </td>
               
            <td style="text-aligh:center">
               
                    <h3 style="font-size:20px !important; margin-bottom : 0;margin-top: 0px;"
                    class="text-white mb-0"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                    <span style="font-size:11px !important;margin:0px"
                    class="text-white "> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </span>
                    

               

            </td>
        </tr>
    </table>

    <hr style="margin-bottom: 6px;margin-top: 6px;">

    <table class="table table-bordered table-striped" style="width: 100%; table-layout: fixed">


        <tr >
            <th style="width:7%;padding: 2px; padding-left:8px;">
          
            </th>
            @php
                $height= 0;
                $tr = [];
               
            @endphp
            @foreach($sm_weekends as $sm_weekend)
              
                @if( $sm_weekend->classRoutine->count() >$height)
                    @php
                        $height =  $sm_weekend->classRoutine->count();
                    @endphp
                @endif

                <th style="margin-top: 0px;padding: 2px; padding-left:8px">{{@$sm_weekend->name}}</th>
            @endforeach

        </tr>

        @php
            $used = [];
            $tr=[];

        @endphp
        @foreach($sm_weekends as $sm_weekend)
        @php
        
            $i = 0;
        @endphp
            @foreach($sm_weekend->classRoutine as $routine)
                @php
                if(!in_array($routine->id, $used)){
                    $tr[$i][$sm_weekend->name][$loop->index]['subject']= $routine->subject ? $routine->subject->subject_name :'';
                    $tr[$i][$sm_weekend->name][$loop->index]['subject_code']= $routine->subject ? $routine->subject->subject_code :'';
                    $tr[$i][$sm_weekend->name][$loop->index]['class_room']= $routine->classRoom ? $routine->classRoom->room_no : '';
                    $tr[$i][$sm_weekend->name][$loop->index]['teacher']= $routine->teacherDetail ? $routine->teacherDetail->full_name :'';
                    $tr[$i][$sm_weekend->name][$loop->index]['start_time']=  $routine->start_time;
                    $tr[$i][$sm_weekend->name][$loop->index]['end_time']= $routine->end_time;
                    $tr[$i][$sm_weekend->name][$loop->index]['is_break']= $routine->is_break;
                    $used[] = $routine->id;
                } 
                     
                @endphp
            @endforeach

            @php
                
                $i++;
            @endphp

        @endforeach

       @for($i = 0; $i < $height; $i++)
          <tr style="border-bottom:1px solid #000000">
             <td style="padding-top:0px;padding-bottom:0px;font-size:10px !important;">@lang('common.time')</td>
                @foreach($tr as $days)
                @foreach($sm_weekends as $sm_weekend)
                    <td style="padding-top:0px ;padding-bottom:0px;">
                        @php
                            $classes=gv($days,$sm_weekend->name);
                        @endphp
                        @if($classes && gv($classes,$i))  
                            
                        <span style="font-size:10px !important;"> {{date('h:i A', strtotime(@$classes[$i]['start_time']))  }}  - {{date('h:i A', strtotime(@$classes[$i]['end_time']))  }}  </span> 

                        @endif
                        
                    </td>
                    @endforeach                    
                @endforeach
          </tr>
      
       <tr>
       <td>@lang('common.details')</td>
        @foreach($tr as $days)
         @foreach($sm_weekends as $sm_weekend)
            <td style="padding-top:0px ;padding-bottom:0px;">
                @php
                     $classes=gv($days,$sm_weekend->name);
                 @endphp
                 @if($classes && gv($classes,$i))              
                   @if($classes[$i]['is_break'])
                      <strong class="routineBorder"> @lang('common.break') </strong>
                    @else
            
                       @if ($classes[$i]['subject'])
                        <span class="">  <strong>  {{ $classes[$i]['subject'] }} </strong>  @if ($classes[$i]['class_room'])
                                ({{ $classes[$i]['class_room'] }})  
                        @endif  <br>  </span>  
                        @endif          
                          
                        @if ($classes[$i]['teacher'])
                             <span class=""> {{ $classes[$i]['teacher'] }}  <br> </span>
                        @endif           
    

                     @endif

                @endif
                
            </td>
            @endforeach

  
                    
        @endforeach
       </tr>

       @endfor
    </table>
</div>


</body>

<script src="{{ asset('public/vendor/spondonit/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('public/backEnd/js/pdf/html2canvas.min.js') }}"></script>

<script>
    function generatePDF() {
        const element = document.getElementById('pdf');
        var opt = {
            margin:       0.5,
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '#page2el' },
            filename:     'class-routine.pdf',
            image:        { type: 'jpeg', quality: 100 },
            html2canvas:  { scale: 5 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(element).save().then(function(){
            // window.close()
        });
    }



    $(document).ready(function(){
        @if($print)
        window.print();
        setTimeout(function () {
            window.close()
        }, 3000);
        @else
        generatePDF();
        @endif

    })
</script>
</html>

