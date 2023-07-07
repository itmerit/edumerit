@extends('backEnd.master')
@section('title')
    @lang('lesson::lesson.lesson_plan')
@endsection
@section('mainContent')
    <link rel="stylesheet" href="{{ url('Modules/Lesson/Resources/assets/css/lesson_plan.css') }}">
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ $student_detail->full_name }} - @lang('lesson::lesson.lesson_plan') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('lesson::lesson.lesson_plan')</a>
                </div>
            </div>
        </div>
    </section>

    @if (isset($class_times))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row">
                    <!-- Start Student Details -->
                    <div class="col-lg-12 student-details up_admin_visitor">
                        <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">

                            @foreach ($records as $key => $record)
                                <li class="nav-item">                                 
                                        @if (moduleStatusCheck('University'))
                                        <a class="nav-link @if ($key == 0) active @endif "
                                            href="#tab{{ $key }}" role="tab"
                                            data-toggle="tab">{{ $record->semesterLabel->name }} (
                                            {{ $record->unSemester->name }} - {{ $record->unAcademic->name }} ) </a>
                                    @else
                                        <a class="nav-link @if ($key == 0) active @endif "
                                            href="#tab{{ $key }}" role="tab"
                                            data-toggle="tab">{{ $record->class->class_name }}
                                            ({{ $record->section->section_name }})
                                        </a>
                                    @endif
                                </li>
                            @endforeach

                        </ul>


                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Start Fees Tab -->
                            @foreach ($records as $key => $record)
                                <div role="tabpanel" class="tab-pane fade  @if ($key == 0) active show @endif"
                                    id="tab{{ $key }}">
                                   
                                        <div class="container-fluid p-0 mt-40">
                                            <div class="white-box">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="main-title">
                                                            <?php
                                                            $dates[6];
                                                            if (isset($week_number)) {
                                                                $week_number = $week_number;
                                                            } else {
                                                                $week_number = $this_week;
                                                            }
                                                            
                                                            ?>

                                                            <h3 class="text-center "><a
                                                                    href="{{ url('/lesson/parent/dicrease-week/' . $student_detail->id . '/' . $dates[0]) }}">
                                                                    < </a> week {{ $week_number }} | <span class="yearColor">
                                                                            {{ date('Y', strtotime($dates[0])) }} </span> <a
                                                                            href="{{ url('/lesson/parent/change-week/' . $student_detail->id . '/' . $dates[6]) }}">
                                                                            > </a></h3>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-lg-12">

                                                        <table id="" class="table" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    @php
                                                                        $height = 0;
                                                                        $tr = [];
                                                                    @endphp
                                                                    @foreach ($sm_weekends as $key => $sm_weekend)
                                                                        @php
                                                                        if(moduleStatusCheck('University'))
                                                                        {
                                                                            $parentRoutine = App\SmWeekend::studentClassRoutineFromRecordUniversity($record->un_academic_id, $record->un_semester_label_id, $sm_weekend->id);
                                                                        } else {

                                                                            $parentRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id);
                                                                        }
                                                                        @endphp
                                                                        @if ($parentRoutine->count() > $height)
                                                                            @php
                                                                                $height = $parentRoutine->count();
                                                                            @endphp
                                                                        @endif

                                                                        <th>{{ @$sm_weekend->name }} <br>
                                                                            {{ date('d-M-y', strtotime($dates[$key])) }}
                                                                        </th>
                                                                    @endforeach

                                                                </tr>
                                                            </thead>
                                                            @php
                                                                $used = [];
                                                                $tr = [];
                                                                
                                                            @endphp
                                                            @foreach ($sm_weekends as $sm_weekend)
                                                                @php
                                                                  if(moduleStatusCheck('University'))
                                                                    {
                                                                        $parentRoutine = App\SmWeekend::studentClassRoutineFromRecordUniversity($record->un_academic_id, $record->un_semester_label_id, $sm_weekend->id);
                                                                    } else {
                                                                        $parentRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id);
                                                                    }
                                                                    $i = 0;
                                                                @endphp
                                                                @foreach ($parentRoutine as $routine)
                                                                    @php
                                                                        if (!in_array($routine->id, $used)) {
                                                                            if (moduleStatusCheck('University')) {
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['un_semester_label_id'] = $routine->un_semester_label_id;
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->unSubject ? $routine->unSubject->subject_name : '';
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->unSubject ? $routine->unSubject->subject_code : '';
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['subject_id'] = $routine->unSubject ? $routine->unSubject->id : null;
                                                                    } else {
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->subject ? $routine->subject->subject_name : '';
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->subject ? $routine->subject->subject_code : '';
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['subject_id'] = $routine->subject ? $routine->subject->id : null;
                                                                    }
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['class_room'] = $routine->classRoom ? $routine->classRoom->room_no : '';
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['teacher'] = $routine->teacherDetail ? $routine->teacherDetail->full_name : '';
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['start_time'] = $routine->start_time;
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['end_time'] = $routine->end_time;
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['is_break'] = $routine->is_break;
                                                                            $used[] = $routine->id;
                                                                           
                                                                            $tr[$i][$sm_weekend->name][$loop->index]['routine_id'] = $routine->id;
                                                                        }
                                                                        
                                                                    @endphp
                                                                @endforeach

                                                                @php
                                                                    
                                                                    $i++;
                                                                @endphp

                                                            @endforeach

                                                            @for ($i = 0; $i < $height; $i++)
                                                                <tr>
                                                                    @foreach ($tr as $days)
                                                                        @foreach ($sm_weekends as $key => $sm_weekend)

                                                                            <td>
                                                                                @php
                                                                                    $lesson_date = $dates[$key];
                                                                                    $classes = gv($days, $sm_weekend->name);
                                                                                @endphp
                                                                                @if ($classes && gv($classes, $i))
                                                                                    @if ($classes[$i]['is_break'])
                                                                                        <strong> @lang('common.break') </strong>

                                                                                        <span class="">
                                                                                            ({{ date('h:i A', strtotime(@$classes[$i]['start_time'])) }}
                                                                                            -
                                                                                            {{ date('h:i A', strtotime(@$classes[$i]['end_time'])) }})
                                                                                            <br> </span>
                                                                                    @else
                                                                                        <span class="">
                                                                                            <strong>@lang('common.time') :</strong>
                                                                                            {{ date('h:i A', strtotime(@$classes[$i]['start_time'])) }}
                                                                                            -
                                                                                            {{ date('h:i A', strtotime(@$classes[$i]['end_time'])) }}
                                                                                            <br> </span>
                                                                                        <span class=""> <strong>
                                                                                                {{ $classes[$i]['subject'] }}
                                                                                            </strong>
                                                                                            ({{ $classes[$i]['subject_code'] }})
                                                                                            <br> </span>
                                                                                        @if ($classes[$i]['class_room'])
                                                                                            <span class="">
                                                                                                <strong>@lang('common.room')
                                                                                                    :</strong>
                                                                                                {{ $classes[$i]['class_room'] }}
                                                                                                <br> </span>
                                                                                        @endif
                                                                                        @if ($classes[$i]['teacher'])
                                                                                            <span class="">
                                                                                                {{ $classes[$i]['teacher'] }}
                                                                                                <br> </span>
                                                                                        @endif
                                                                                        @php
                                                                                            $subject_id = $classes[$i]['subject_id'];
                                                                                            $routine_id = $classes[$i]['routine_id'];
                                                                                            if(moduleStatusCheck('University')) {
                                                                                        $un_semester_label_id    =  $classes[$i]['un_semester_label_id'];

                                                                                        $lessonPlan    =  DB::table('lesson_planners')
                                                                                                        ->where('lesson_date',$lesson_date) 
                                                                                                        ->where('un_semester_label_id', $un_semester_label_id) 
                                                                                                        ->where('un_subject_id',$subject_id)
                                                                                                        ->where('routine_id',$routine_id)
                                                                                                        ->where('school_id',Auth::user()->school_id)
                                                                                                        ->first();
                                                                                    } else {
                                                                                            $lessonPlan = DB::table('lesson_planners')
                                                                                                ->where('lesson_date', $lesson_date)
                                                                                                ->where('class_id', $record->class_id)
                                                                                                ->where('section_id', $record->section_id)
                                                                                                ->where('subject_id', $subject_id)
                                                                                                ->where('routine_id', $routine_id)
                                                                                                ->where('academic_id', getAcademicId())
                                                                                                ->where('school_id', Auth::user()->school_id)
                                                                                                ->first();
                                                                                    }
                                                                                        @endphp
                                                                                        @if ($lessonPlan)


                                                                                            <a href="{{ route('view-lesson-planner-lesson', [$lessonPlan->id]) }}"
                                                                                                class="primary-btn small tr-bg icon-only mr-10 modalLink"
                                                                                                title="@lang('lesson::lesson.lesson_overview') "
                                                                                                data-modal-size="modal-lg">
                                                                                                <span class="ti-eye"
                                                                                                    id=""></span>
                                                                                            </a>
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
                                                </div>
                                            </div>
                                        </div>

                                </div>


                            @endforeach

                            <!-- End Fees Tab -->
                        </div>

                    </div>
                    <!-- End Student Details -->
                </div>


            </div>
        </section>
    @endif



@endsection
