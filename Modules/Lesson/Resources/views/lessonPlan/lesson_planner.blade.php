@extends('backEnd.master')
@section('title')
@lang('lesson::lesson.lesson_plan_create')
@endsection
@section('mainContent')

<link rel="stylesheet" href="{{url('Modules/Lesson/Resources/assets/css/lesson_plan.css')}}">

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lesson::lesson.lesson_plan_create')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('lesson::lesson.lesson_plan')</a>
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

                @if(userPermission('add-new-lesson-plan') )
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lesson-planner', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        {{-- <input type="text" name="teacher_id" value="{{$teacher_id}}"> --}}
                        <div class="col-lg-12">
                            <label class="primary_input_label" for="">
                                {{ __('common.teacher') }}
                                <span class="text-danger"> *</span>
                            </label>
                            <select
                                class="primary_select form-control{{ $errors->has('teacher') ? ' is-invalid' : '' }}"
                                id="select_class" name="teacher">
                                <option data-display="@lang('common.select_teacher') *" value="">
                                    @lang('common.select_teacher') *</option>
                                @foreach($teachers as $teacher)
                                <option value="{{ @$teacher->id }}"
                                    {{isset($teacher_id)? ($teacher_id == $teacher->id?'selected':''):''}}>
                                    {{ @$teacher->full_name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('teacher'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('teacher') }}
                            </span>
                            @endif
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 mt-30 text-right">
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

                    <h3 class="text-center "><a href="{{url('/lesson/dicrease-week/'.$teacher_id.'/'.$dates[0])}}">
                            <</a> Week {{$week_number}} | <span class="yearColor"> {{date('Y', strtotime($dates[0]))}}
                                </span><a href="{{url('/lesson/change-week/'.$teacher_id.'/'.$dates[6])}}"> > </a></h3>
                    {{-- {{ $dt =Carbon::now()->dayOfWeekIso}} --}}
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
                            @php
                            $teacherClassRoutineById=App\SmWeekend::teacherClassRoutineById($sm_weekend->id,$teacher_id);
                            @endphp
                            @if( $teacherClassRoutineById->count() >$height)
                            @php
                            $height = $teacherClassRoutineById->count();
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
                        $teacherClassRoutineById=App\SmWeekend::teacherClassRoutineById($sm_weekend->id,$teacher_id);
                        @endphp
                        @foreach($teacherClassRoutineById as $routine)
                        @php
                        if(!in_array($routine->id, $used)){
                        if(moduleStatusCheck('University')) {
                        $tr[$i][$sm_weekend->name][$loop->index]['un_semester_label_id'] =
                        $routine->un_semester_label_id;
                        $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->unSubject ?
                        $routine->unSubject->subject_name :'';
                        $tr[$i][$sm_weekend->name][$loop->index]['subject_code']= $routine->unSubject ?
                        $routine->unSubject->subject_code :'';
                        $tr[$i][$sm_weekend->name][$loop->index]['subject_id']= $routine->unSubject ?
                        $routine->unSubject->id :null;
                        } else {
                        $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->subject ?
                        $routine->subject->subject_name :'';
                        $tr[$i][$sm_weekend->name][$loop->index]['subject_code']= $routine->subject ?
                        $routine->subject->subject_code :'';
                        $tr[$i][$sm_weekend->name][$loop->index]['subject_id']= $routine->subject ?
                        $routine->subject->id :null;

                        }

                        $tr[$i][$sm_weekend->name][$loop->index]['class_name']= $routine->class ?
                        $routine->class->class_name : '';
                        $tr[$i][$sm_weekend->name][$loop->index]['section_name']= $routine->section ?
                        $routine->section->section_name : '';
                        $tr[$i][$sm_weekend->name][$loop->index]['class_room']= $routine->classRoom ?
                        $routine->classRoom->room_no : '';
                        $tr[$i][$sm_weekend->name][$loop->index]['teacher']= $routine->teacherDetail ?
                        $routine->teacherDetail->full_name :'';
                        $tr[$i][$sm_weekend->name][$loop->index]['start_time']= $routine->start_time;
                        $tr[$i][$sm_weekend->name][$loop->index]['end_time'] = $routine->end_time;

                        $tr[$i][$sm_weekend->name][$loop->index]['is_break']= $routine->is_break;
                        $used[] = $routine->id;

                        $tr[$i][$sm_weekend->name][$loop->index]['class_id']= $routine->class ? $routine->class->id :
                        null;
                        $tr[$i][$sm_weekend->name][$loop->index]['section_id']= $routine->section ?
                        $routine->section->id : null;

                        $tr[$i][$sm_weekend->name][$loop->index]['class_room_id']= $routine->classRoom ?
                        $routine->classRoom->id : null;
                        $tr[$i][$sm_weekend->name][$loop->index]['teacher_id']= $routine->teacherDetail ?
                        $routine->teacherDetail->id : null;
                        $tr[$i][$sm_weekend->name][$loop->index]['routine_id']= $routine->id;
                        }

                        @endphp
                        @endforeach

                        @php

                        $i++;
                        @endphp

                        @endforeach

                        @for($i = 0; $i < $height; $i++) <tr>
                            @foreach($tr as $days)
                            @foreach($sm_weekends as $key=>$sm_weekend)
                            @php
                            $lesson_date=$dates[$key]
                            @endphp
                            <td>
                                @php
                                $classes=gv($days,$sm_weekend->name);
                                @endphp
                                @if($classes && gv($classes,$i))
                                @if($classes[$i]['is_break'])
                                <strong> @lang('lesson::lesson.break') </strong>

                                <span class=""> ({{date('h:i A', strtotime(@$classes[$i]['start_time']))  }} -
                                    {{date('h:i A', strtotime(@$classes[$i]['end_time']))  }}) <br> </span>
                                @else
                                <span class="">{{date('h:i A', strtotime(@$classes[$i]['start_time']))  }} -
                                    {{date('h:i A', strtotime(@$classes[$i]['end_time']))  }} <br> </span>
                                <span class=""> <strong> {{ $classes[$i]['subject'] }} </strong>
                                    ({{ $classes[$i]['subject_code'] }}) <br> </span>
                                @if ($classes[$i]['class_room'])
                                <span class=""> <strong>@lang('common.room') :</strong> {{ $classes[$i]['class_room'] }}
                                    <br> </span>
                                @endif
                                @if ($classes[$i]['class_name'])
                                <span class=""> {{ $classes[$i]['class_name'] }} @if ($classes[$i]['section_name']) (
                                    {{ $classes[$i]['section_name'] }} ) @endif <br> </span>
                                @endif

                                @php

                                $class_id = $classes[$i]['class_id'];
                                $section_id = $classes[$i]['section_id'];
                                $subject_id = $classes[$i]['subject_id'];
                                $start_time = $classes[$i]['start_time'];
                                $end_time = $classes[$i]['end_time'];
                                $routine_id = $classes[$i]['routine_id'];
                                if(moduleStatusCheck('University')) {
                                $un_semester_label_id = $classes[$i]['un_semester_label_id'];

                                $lessonPlan = DB::table('lesson_planners')
                                ->where('lesson_date',$lesson_date)
                                ->where('un_semester_label_id', $un_semester_label_id)
                                ->where('un_subject_id',$subject_id)
                                ->where('routine_id',$routine_id)
                                ->where('school_id',Auth::user()->school_id)
                                ->first();
                                } else {

                                $lessonPlan = DB::table('lesson_planners')
                                ->where('lesson_date',$lesson_date)
                                ->where('class_id',$class_id)
                                ->where('section_id',$section_id)
                                ->where('subject_id',$subject_id)
                                ->where('routine_id',$routine_id)
                                ->where('academic_id', getAcademicId())
                                ->where('school_id',Auth::user()->school_id)
                                ->first();
                                }
                                @endphp
                                @if($lessonPlan)
                                <div class="row">
                                    @if(userPermission('view-lesson-planner-lesson'))
                                    <div class="col-lg-2 text-right">
                                        <a href="{{route('view-lesson-planner-lesson', [$lessonPlan->id])}}"
                                            class="primary-btn small tr-bg icon-only modalLink"
                                            title="@lang('lesson::lesson.lesson_overview') " data-modal-size="modal-lg">
                                            <span class="ti-eye" id=""></span>
                                        </a>
                                    </div>
                                    @endif
                                    @if(userPermission('delete-lesson-planner-lesson'))
                                    <div class="col-lg-2 text-right">
                                        <a href="{{route('delete-lesson-planner-lesson', [$lessonPlan->id])}}"
                                            class="primary-btn small tr-bg icon-only  modalLink"
                                            data-modal-size="modal-md"
                                            title="@lang('lesson::lesson.delete_lesson_plan')">
                                            <span class="ti-close" id=""></span>
                                        </a>
                                    </div>
                                    @endif
                                    @if(userPermission('edit-lesson-planner-lesson'))
                                    <div class="col-lg-2 text-right">
                                        <a href="{{route('edit-lesson-planner-lesson', [$lessonPlan->id])}}"
                                            class="primary-btn small tr-bg icon-only mr-10 modalLink"
                                            data-modal-size="modal-lg"
                                            title="@lang('lesson::lesson.edit_lesson_plan') {{date('d-M-y',strtotime($lesson_date))}} ( {{date('h:i A', strtotime(@$start_time))}}-{{date('h:i A', strtotime(@$end_time))}} )">
                                            <span class="ti-pencil" id=""></span>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                @else

                                @if(userPermission('add-lesson-planner-lesson'))
                                <div class="col-lg-6 text-right">
                                    <a href="{{route('add-lesson-planner-lesson', [$sm_weekend->id,$teacher_id,$routine_id,$lesson_date])}}"
                                        class="primary-btn small tr-bg icon-only mr-10 modalLink"
                                        data-modal-size="modal-lg"
                                        title="@lang('lesson::lesson.add_lesson_plan') {{date('d-M-y',strtotime($lesson_date))}} ( {{date('h:i A', strtotime(@$start_time))}}-{{date('h:i A', strtotime(@$end_time))}} )">
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