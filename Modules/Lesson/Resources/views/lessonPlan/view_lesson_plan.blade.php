
<div class="container-fluid">
    <style type="text/css">
        .school-table-style {
            border-collapse: collapse;
        }
        .school-table-style tr td {           
            border-top: 0 !important;   
            padding: 20px 10px 20px 10px;       
        }
        #headerTableModal tr td {           
            padding: 2px 10px 10px 15px
        }
        .school-table-style tr th {
            border-bottom:none;
            padding: 20px 10px 20px 10px;  
        }
        .school-table-style tr  {
            border-bottom: 1px solid rgba(130, 139, 178, 0.3);         
        }
    </style>

<table  id="headerTableModal" class="table-style shadow-none pt-0 " cellspacing="0" width="100%">
    <tbody>
    @if(moduleStatusCheck('University'))
    <tr>
        <th class="d-flex justify-content-between align-items-center"><span>@lang('university::un.faculty_department')</span><strong>:</strong></th>
        <td>{{$lessonPlanDetail->unFaculty->name}}({{$lessonPlanDetail->unDepartment->name}})</td>
    </tr>
    <tr>
        <th class="d-flex justify-content-between align-items-center"><span>@lang('university::un.semester(label)')</span><strong>:</strong></th>
        <td>{{$lessonPlanDetail->unSemester->name}}({{$lessonPlanDetail->unSemesterLabel->name}})</td>
    </tr>
    @else
    <tr>
        <th class="d-flex justify-content-between align-items-center"><span>@lang('common.class')</span><strong>:</strong></th>
        <td>{{$lessonPlanDetail->class->class_name}}({{$lessonPlanDetail->sectionName->section_name}})</td>
    </tr>
    @endif
    <tr>
        
        <th class="d-flex justify-content-between align-items-center"><span>@lang('common.subject')</span><strong>:</strong></th>
        <td>
            @if(moduleStatusCheck('University'))
            {{$lessonPlanDetail->unSubject->subject_name}} ({{$lessonPlanDetail->unSubject->subject_code}}) -{{$lessonPlanDetail->unSubject->subject_type}}
            @else
            {{$lessonPlanDetail->subject->subject_name}} ({{$lessonPlanDetail->subject->subject_code}}) -{{$lessonPlanDetail->subject->subject_type == 'T'? 'Theory':'Practical'}}
            @endif
        </td>
    </tr>	
    <tr>
       
        <th class="d-flex justify-content-between align-items-center"><span>@lang('common.date')</span><strong>:</strong></th>
        <td>{{date('d-M-y',strtotime($lessonPlanDetail->lesson_date))}} </td>
    </tr>
    <tr>
        
        <th class="d-flex justify-content-between align-items-center"><span>@lang('lesson::lesson.lesson')</span><strong>:</strong></th>
    <td> {{$lessonPlanDetail->lessonName->lesson_title}}</td>
    </tr>

    <tr>
     
        <th class="d-flex justify-content-between align-items-center">
            <span>@lang('common.topic')</span>
            <strong>:</strong>
        </th>

        <td>
            @if(count($lessonPlanDetail->topics) > 0) 
                @foreach ($lessonPlanDetail->topics as $topic)
                {{$topic->topicName->topic_title}}  {{!$loop->last ? ',':'' }}
                @endforeach
            @else  
            {{$lessonPlanDetail->topicName->topic_title}}
            @endif

        </td>
    </tr>
    @if(generalSetting()->sub_topic_enable)
    <tr>
        
        <th class="d-flex justify-content-between align-items-center">
            <span>@lang('lesson::lesson.sub_topic')</span>
            <strong>:</strong>
        </th>

        <td> 
            @if (count($lessonPlanDetail->topics) > 0)
                @foreach ($lessonPlanDetail->topics as $topic)
                {{$topic->sub_topic_title}}
                {{ !$loop->last ? ',':''  }}
                @endforeach
            @else
                {{$lessonPlanDetail->sub_topic}}
            @endif
        </td>
    </tr>
    @endif
    <tr>
        
        <th class="d-flex justify-content-between align-items-center"><span>@lang('lesson::lesson.lecture_youtube_link')</span><strong>:</strong></th>

    <td> 
         @if($lessonPlanDetail->lecture_youube_link !='')
        @php $link = explode(',', $lessonPlanDetail->lecture_youube_link);
            $i=1;
        @endphp
        @foreach($link as $item)
        <a target="_blank" href="{{$item}}">{{$i++}}) {{$item}}</a> <br>
        @endforeach
        @endif
         </td>
    </tr>
    <tr>
       
        <th class="d-flex justify-content-between align-items-center"><span>@lang('common.document')</span><strong>:</strong></th>

        <td> 
            @if($lessonPlanDetail->attachment !='')
         
            <a href="{{ asset($lessonPlanDetail->attachment) }}" download="" >
                <i class="fa fa-download mr-1">
                    </i> @lang('common.download')
                </a>

            @endif
        </td>
      

    </tr>
    @if($lessonPlanDetail->general_objectives)
    <tr>
        
        <th class="d-flex justify-content-between align-items-center"><span>@lang('lesson::lesson.general_objectives')</span><strong>:</strong></th>

        <td colspan="2"> {{$lessonPlanDetail->general_objectives}}</td>
    </tr>
    @endif
    @if($lessonPlanDetail->teaching_method)
    <tr>
        
        <th class="d-flex justify-content-between align-items-center"><span>@lang('lesson::lesson.teaching_method')</span><strong>:</strong></th>

        <td colspan="2"> {{$lessonPlanDetail->teaching_method}}</td>
    </tr>
    @endif
    @if($lessonPlanDetail->previous_knowlege)
    <tr>
        
        <th class="d-flex justify-content-between align-items-center"><span>@lang('lesson::lesson.previous_knowledge')</span><strong>:</strong></th>

        <td colspan="2"> {{$lessonPlanDetail->previous_knowlege}}</td>
    </tr>
    @endif
    @if($lessonPlanDetail->comp_question)
    <tr>
        
        <th class="d-flex justify-content-between align-items-center"><span>@lang('lesson::lesson.comprehensive_questions')</span><strong>:</strong></th>

        <td colspan="2"> {{$lessonPlanDetail->comp_question}}</td>
    </tr>
    @endif
    <tr>
        
         <th class="d-flex justify-content-between align-items-center"><span>@lang('common.note')</span><strong>:</strong></th>
        <td colspan="2"> {{$lessonPlanDetail->note}}</td>
    </tr>
	<tr>
     
     <th class="d-flex justify-content-between align-items-center"><span>@lang('common.status')</span><strong>:</strong></th>
     <td colspan="2">
        <label class="switch_toggle">
                                    
                                    @if(Auth::user()->role_id==4 || Auth::user()->role_id==1 || Auth::user()->role_id==5)
                                <input type="checkbox" data-complete_date="{{Carbon::now()->format('Y-m-d')}}"  data-id="{{$lessonPlanDetail->id}}" {{@$lessonPlanDetail->completed_status == 'completed'? 'checked':''}}
                                        class="weekend_switch_btn">
                                    <span class="slider round"></span>
                                   @else
                                   <input type="checkbox" disabled="" {{@$lessonPlanDetail->completed_status == 'completed'? 'checked':''}}
                                        class="weekend_switch_btn">
                                    <span class="slider round"></span>
                                   @endif
                                </label> 
                               
     </td>   
    </tr>
    
</tbody>
</table>
 <script>
    $(document).ready(function() {
            $(".weekend_switch_btn").on("change", function() {
                var lessonplan_id = $(this).data("id");
               
               
                if ($(this).is(":checked")) {
                    var status = "completed";
                    var complete_date=$(this).data("complete_date");
                  
                } else {
                    var status = null;
                    var complete_date=null;
                     
                }
                
                var url = $("#url").val();
                

                $.ajax({
                    type: "POST",
                    data: {'status': status, 'lessonplan_id': lessonplan_id,'complete_date':complete_date},
                    dataType: "json",
                    url: url + "/" + "lesson/lessonPlan-status-ajax",
                    success: function(data) {
                          // location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success ", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                       
                    },
                    error: function(data) {
                       
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
</script>


