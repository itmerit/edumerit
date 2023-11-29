@extends('backEnd.master')
@section('title') 
@lang('lesson::lesson.lesson_plan')
@endsection
@section('mainContent')
 <link rel="stylesheet" href="{{url('Modules/Lesson/Resources/assets/css/lesson_plan.css')}}">
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lesson::lesson.lesson_plan_create')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('lesson::lesson.lesson')</a>
                    <a href="#">@lang('lesson::lesson.lesson_plan_create')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
              
                    @if(Auth::user()->role_id==1)
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lesson-planner', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">                          
                            <div class="col-lg-6 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="teacher">
                                    <option data-display="@lang('common.select_teacher') *" value="">@lang('common.select_teacher') *</option>
                                   
                                        <option value="{{ $teacher_id }}"  selected="">{{ @$teacher->full_name }}</option>
                                   
                                </select>
                                @if ($errors->has('teacher'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('teacher') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-6 mt-20 text-left">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if(isset($class_times))
<section class="mt-20">
    <div class="container-fluid p-0">
<div class="row mt-40">
                    <div class="col-lg-12 col-md-12">
                        <div class="main-title">
                            <?php 
                            $dates[6];
                            if(isset($week_number)){
                                $week_number=$week_number;
                            } else{
                                $week_number=$this_week;
                            } 

                             ?>

                    <h3 class="text-center "><a href="{{url('/lesson/teacher/dicrease-week/'.$dates[0])}}"><</a> week {{$week_number}} <a href="{{url('/lesson/teacher/change-week/'.$dates[6])}}">></a></h3> 
                   
                        </div>
                    </div>
             </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table school-table-style" cellspacing="0" width="100%">
                    <thead>
                       
                        <tr>
                           
                            @php
                                $height= 0;
                                $tr = [];
                            @endphp
                            @foreach($sm_weekends as $key=>$sm_weekend)
                        
                                @if( $sm_weekend->teacherClassRoutine->count() >$height)
                                    @php
                                        $height =  $sm_weekend->teacherClassRoutine->count();
                                    @endphp
                                @endif

                            <th>{{@$sm_weekend->name}} <br> 
                                {{date('d-M-y', strtotime($dates[$key]))}}
                            </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>

                        @php
                        $used = [];
                        $tr=[];
            
                    @endphp
                    @foreach($sm_weekends as $sm_weekend)
                    @php
                    
                        $i = 0;
                    @endphp
                        @foreach($sm_weekend->teacherClassRoutine as $routine)
                            @php
                            if(!in_array($routine->id, $used)){
                                $tr[$i][$sm_weekend->name][$loop->index]['subject']= $routine->subject ? $routine->subject->subject_name :'';
                                $tr[$i][$sm_weekend->name][$loop->index]['subject_code']= $routine->subject ? $routine->subject->subject_code :'';
                                $tr[$i][$sm_weekend->name][$loop->index]['class_room']= $routine->classRoom ? $routine->classRoom->room_no : '';
                                $tr[$i][$sm_weekend->name][$loop->index]['teacher']= $routine->teacherDetail ? $routine->teacherDetail->full_name :'';
                                $tr[$i][$sm_weekend->name][$loop->index]['start_time']=  $routine->start_time;
                                $tr[$i][$sm_weekend->name][$loop->index]['end_time']= $routine->end_time;
                                $tr[$i][$sm_weekend->name][$loop->index]['class_name']= $routine->class ? $routine->class->class_name : '';
                                $tr[$i][$sm_weekend->name][$loop->index]['section_name']= $routine->section ? $routine->section->section_name : '';
                                $tr[$i][$sm_weekend->name][$loop->index]['is_break']= $routine->is_break;
                                $used[] = $routine->id;
                                $tr[$i][$sm_weekend->name][$loop->index]['routine_id']= $routine->id;
                            } 
                                 
                            @endphp
                        @endforeach
            
                        @php
                            
                            $i++;
                        @endphp
            
                    @endforeach
            
                   @for($i = 0; $i < $height; $i++)
                   <tr>
                    @foreach($tr as $days)
                     @foreach($sm_weekends as $key=>$sm_weekend)
                     
                        <td>
                            @php
                            
                                 $lesson_date=$dates[$key];
                                 $classes=gv($days,$sm_weekend->name);

                             @endphp
                             @if($classes && gv($classes,$i))              
                               @if($classes[$i]['is_break'])
                              <strong > @lang('lesson::lesson.break') </strong>
                                 
                               <span class=""> ({{date('h:i A', strtotime(@$classes[$i]['start_time']))  }}  - {{date('h:i A', strtotime(@$classes[$i]['end_time']))  }})  <br> </span> 
                                @else
                                <span class="">{{date('h:i A', strtotime(@$classes[$i]['start_time']))  }}  - {{date('h:i A', strtotime(@$classes[$i]['end_time']))  }}  <br> </span> 
                                    <span class="">  <strong>  {{ $classes[$i]['subject'] }} </strong>  ({{ $classes[$i]['subject_code'] }}) <br>  </span>            
                                    @if ($classes[$i]['class_room'])
                                        <span class=""> <strong>@lang('common.room') :</strong>  {{ $classes[$i]['class_room'] }}  <br>     </span>
                                    @endif    
                                    @if ($classes[$i]['class_name'])
                                    <span class=""> {{ $classes[$i]['class_name'] }}   @if ($classes[$i]['section_name']) ( {{ $classes[$i]['section_name'] }} )   @endif  <br> </span>
                                    @endif 

                                    @php
                               
                                      $routine_id   =  $classes[$i]['routine_id'];
                                     
                                      $lessonPlan   =  DB::table('lesson_planners')
                                                            ->where('lesson_date',$lesson_date)
                                                            ->where('routine_id',$routine_id)                                              
                                                            ->where('academic_id', getAcademicId())
                                                            ->where('school_id',Auth::user()->school_id)
                                                            ->first();
                            
                                                        
                                    @endphp
                                    @if($lessonPlan)
                                    <div class="row">
                                           @if(userPermission(814))
                                        <div class="col-lg-2 text-right">
                                            <a href="{{route('view-lesson-planner-lesson', [$lessonPlan->id])}}" 
                                                class="primary-btn small tr-bg icon-only modalLink"
                                                title="@lang('lesson::lesson.lesson_overview') " data-modal-size="modal-lg" >
                                                <span class="ti-eye" id=""></span>
                                            </a>
                                        </div>
                                         @endif
                                           @if(userPermission(813))
                                                 <div class="col-lg-2 text-right">
                                                    <a href="{{route('delete-lesson-planner-lesson', [$lessonPlan->id])}}" 
                                                        class="primary-btn small tr-bg icon-only  modalLink" data-modal-size="modal-md" 
                                                        title="@lang('common.delete_lesson_plan')">
                                                        <span class="ti-close" id=""></span>
                                                    </a>
                                                </div>
                                         @endif
                                           @if(userPermission(812))
                                                <div class="col-lg-2 text-right">
                                                    <a href="{{route('edit-lesson-planner-lesson', [$lessonPlan->id])}}" 
                                                        class="primary-btn small tr-bg icon-only mr-10 modalLink" data-modal-size="modal-lg" 
                                                        title="@lang('common.edit_lesson_plan') {{date('d-M-y',strtotime($lesson_date))}} ( {{date('h:i A', strtotime(@$start_time))}}-{{date('h:i A', strtotime(@$end_time))}} )">
                                                        <span class="ti-pencil" id=""></span>
                                                    </a>
                                                </div>
                                            @endif
                                    </div>
                                    @else
                                   
                                        @if(userPermission(811))
                                            <div class="col-lg-6 text-right">
                                                <a href="{{route('add-lesson-planner-lesson', [$sm_weekend->id,$teacher_id,$routine_id,$lesson_date])}}" 
                                                    class="primary-btn small tr-bg icon-only mr-10 modalLink" data-modal-size="modal-lg" 
                                                    title="@lang('common.add_lesson_plan') {{date('d-M-y',strtotime($lesson_date))}} ( {{date('h:i A', strtotime(@$start_time))}}-{{date('h:i A', strtotime(@$end_time))}} )">
                                                    <span class="ti-plus" id="addClassRoutine"></span>
                                                </a>
                                            </div>
                                         @endif
                                    @endif
                                 @endif
            
                            @endif
                            
                        </td>
                        @endforeach
            
              
                                
                    @endforeach
                   </tr>
            
                   @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endif

    

@endsection
