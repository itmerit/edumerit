@extends('backEnd.master')
@section('title')
    @lang('parent.parent_dashboard')
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/css/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/calender_js/core/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/calender_js/daygrid/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/calender_js/timegrid/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/calender_js/list/main.css') }}" />
@endpush
@section('mainContent')
    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <div class="main-title">
                        <h3 class="mb-20">@lang('parent.my_children')</h3>
                    </div>
                </div>
            </div>

            {{-- <div class="row"> --}}
            @foreach ($my_childrens as $children)
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Start Student Meta Information -->
                        <div class="main-title">
                            <h3 class="mb-20"><strong> {{ $children->full_name }}</strong></h3>
                        </div>

                        @php
                            $student_detail = $children;

                            $issueBooks = $student_detail->bookIssue;

                            $homeworkLists = 0;
                            $totalSubjects = 0;
                            $totalOnlineExams = 0;
                            $totalTeachers = 0;
                            $totalExams = 0;

                            foreach ($student_detail->studentRecords as $record) {
                                $homeworkLists += $record->getHomeWorkAttribute()->count();
                                $totalSubjects += $record->getAssignSubjectAttribute()->count();
                                $totalTeachers += $record->getStudentTeacherAttribute()->count();
                                $totalOnlineExams += $record->getOnlineExamAttribute()->count();
                                $totalExams += $record->examSchedule()->count();
                            }

                            $attendances = $student_detail->studentAttendances->where('academic_id', generalSetting()->session_id);
                            
                        @endphp
                    </div>
                </div>
                <div class="row">
                    @if (userPermission('parent-dashboard-subject'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_subjects', $children->id) }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('common.subject')</h3>
                                            <p class="mb-0">@lang('parent.total_subject')</p>
                                        </div>
                                        <h1 class="gradient-color2">

                                                {{ $totalSubjects }}

                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (userPermission('parent-dashboard-notice'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_noticeboard') }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('parent.notice')</h3>
                                            <p class="mb-0">@lang('parent.total_notice')</p>
                                        </div>
                                        <h1 class="gradient-color2">
                                            @if (isset($totalNotices))
                                                {{ count($totalNotices) }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (userPermission('parent-dashboard-exam'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_exam_schedule', $children->id) }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('parent.exam')</h3>
                                            <p class="mb-0">@lang('parent.total_exam')</p>
                                        </div>
                                        <h1 class="gradient-color2">

                                                {{ $totalExams }}
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (userPermission('parent-dashboard-exam'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_online_examination', $children->id) }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('parent.online_exam')</h3>
                                            <p class="mb-0">@lang('parent.total_online_exam')</p>
                                        </div>
                                        <h1 class="gradient-color2">

                                                {{ $totalOnlineExams }}
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (userPermission('parent-dashboard-teacher'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_teacher_list', $children->id) }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('parent.teachers')</h3>
                                            <p class="mb-0">@lang('parent.total_teachers')</p>
                                        </div>
                                        <h1 class="gradient-color2">
                                                {{ $totalTeachers }}
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (userPermission('parent-dashboard-issued-books'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_library') }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('parent.issued_book')</h3>
                                            <p class="mb-0">@lang('parent.total_issued_book')</p>
                                        </div>
                                        <h1 class="gradient-color2">
                                            @if (isset($issueBooks))
                                                {{ count($issueBooks) }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (userPermission('parent-dashboard-pending-homeworks'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_homework', $children->id) }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('parent.pending_home_work')</h3>
                                            <p class="mb-0">@lang('parent.total_pending_home_work')</p>
                                        </div>
                                        <h1 class="gradient-color2">
                                            @if (isset($homeworkLists))
                                                {{ $homeworkLists }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (userPermission('parent-dashboard-attendance-in-current-month'))
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('parent_attendance', $children->id) }}" class="d-block">
                                <div class="white-box single-summery">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>@lang('parent.attendance_in_current_month')</h3>
                                            <p class="mb-0">@lang('parent.total_attendance_in_current_month')</p>
                                        </div>
                                        <h1 class="gradient-color2">
                                            @if (isset($attendances))
                                                {{ count($attendances) }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                </div>
                <br>
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('academics.class_routine')</h3>
                    </div>
                </div>
                <div class="col-lg-12 student-details up_admin_visitor mb-20">
                    <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">
                        @foreach ($children->studentRecords as $key => $record)
                            <li class="nav-item">
                                <a class="nav-link @if ($key == 0) active @endif" href="#tab{{ $key }}" role="tab" data-toggle="tab">
                                    @if (moduleStatusCheck('University'))
                                        {{ $record->semesterLabel->name }} ({{ $record->unSection->section_name }}) -
                                        {{ @$record->unAcademic->name }}
                                    @else
                                        {{ $record->class->class_name }} ({{ $record->section->section_name }})
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content mt-20">
                        @foreach ($children->studentRecords as $key => $record)
                            <div role="tabpanel" class="tab-pane fade  @if ($key == 0) active show @endif"
                                id="tab{{ $key }}">
                                <div class="container-fluid p-0">
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table id="default_table" class="table customeDashboard" cellspacing="0" width="100%">
                                                    <tr>
                                                        @php
                                                            $height = 0;
                                                            $tr = [];
                                                        @endphp
                                                        @foreach ($sm_weekends as $sm_weekend)
                                                            @php
                                                                if (moduleStatusCheck('University')) {
                                                                    $studentClassRoutine = App\SmWeekend::universityStudentClassRoutine($record->un_semester_label_id, $record->un_section_id, $sm_weekend->id);
                                                                } else {
                                                                    $studentClassRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id);
                                                                }
                                                            @endphp
                                                            @if ($studentClassRoutine->count() > $height)
                                                                @php
                                                                    $height = $studentClassRoutine->count();
                                                                @endphp
                                                            @endif

                                                            <th class="{{\Carbon\Carbon::now()->format('l') == $sm_weekend->name ? 'main-border-color' : ''}}">{{ @$sm_weekend->name }}</th>
                                                        @endforeach

                                                    </tr>

                                                    @php
                                                        $used = [];
                                                        $tr = [];
                                                        
                                                    @endphp
                                                    @foreach ($sm_weekends as $sm_weekend)
                                                        @php
                                                            $i = 0;
                                                            if (moduleStatusCheck('University')) {
                                                                $studentClassRoutine = App\SmWeekend::universityStudentClassRoutine($record->un_semester_label_id, $record->un_section_id, $sm_weekend->id);
                                                            } else {
                                                                $studentClassRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id);
                                                            }
                                                        @endphp
                                                        @foreach ($studentClassRoutine as $routine)
                                                            @php
                                                                if (!in_array($routine->id, $used)) {
                                                                    if (moduleStatusCheck('University')) {
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->unSubject ? $routine->unSubject->subject_name : '';
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->unSubject ? $routine->unSubject->subject_code : '';
                                                                    } else {
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->subject ? $routine->subject->subject_name : '';
                                                                        $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->subject ? $routine->subject->subject_code : '';
                                                                    }
                                                                    $tr[$i][$sm_weekend->name][$loop->index]['class_room'] = $routine->classRoom ? $routine->classRoom->room_no : '';
                                                                    $tr[$i][$sm_weekend->name][$loop->index]['teacher'] = $routine->teacherDetail ? $routine->teacherDetail->full_name : '';
                                                                    $tr[$i][$sm_weekend->name][$loop->index]['start_time'] = $routine->start_time;
                                                                    $tr[$i][$sm_weekend->name][$loop->index]['end_time'] = $routine->end_time;
                                                                    $tr[$i][$sm_weekend->name][$loop->index]['is_break'] = $routine->is_break;
                                                                    $used[] = $routine->id;
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
                                                                @foreach ($sm_weekends as $sm_weekend)
                                                                    <td class="{{\Carbon\Carbon::now()->format('l') == $sm_weekend->name ? 'main-border-color' : ''}}">
                                                                        @php
                                                                            $classes = gv($days, $sm_weekend->name);
                                                                        @endphp
                                                                        @if ($classes && gv($classes, $i))
                                                                            @if ($classes[$i]['is_break'])
                                                                                <strong> @lang('academics.break') </strong>

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
                                                                                        <strong>@lang('academics.room') :</strong>
                                                                                        {{ $classes[$i]['class_room'] }}
                                                                                        <br> </span>
                                                                                @endif
                                                                                @if ($classes[$i]['teacher'])
                                                                                    <span class="">
                                                                                        {{ $classes[$i]['teacher'] }} <br>
                                                                                    </span>
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
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('exam.exam_routine')</h3>
                    </div>
                </div>
                <div class="col-lg-12 student-details up_admin_visitor mb-20">
                    <ul class="nav nav-tabs tabs_scroll_nav ml-0" id="myTab" role="tablist">
                        @foreach ($children->studentRecords as $key => $record)
                            @if($record->Exam)
                                @foreach($record->Exam->unique(function ($item) {
                                    return $item->exam_type_id.$item->class_id.$item->section_id;
                                    }) as $exam)
                                    <li class="nav-item">
                                        <a class="nav-link" id="home-tab{{$children->id.$exam->id}}" data-toggle="tab" href="#home{{$children->id.$exam->id}}" role="tab" aria-controls="home" aria-selected="true">
                                        {{$exam->examType->title}} - {{$record->class->class_name}} ({{$record->section->section_name}})
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach ($children->studentRecords as $record)
                            @if($record->Exam)
                                @foreach($record->Exam->unique(function ($item) {
                                    return $item->exam_type_id.$item->class_id.$item->section_id;
                                    }) as $key => $exam)
                                    @php
                                        $exam_routines = App\SmExamSchedule::getAllExams($exam->class_id, $exam->section_id, $exam->exam_type_id);
                                    @endphp
                                    {{-- <div role="tabpanel" class="tab-pane fade  @if ($key == 0) active show @endif" id="examTab{{$children->id}}"> --}}
                                    <div  class="tab-pane fade @if ($loop->parent->first) active show @endif" id="home{{$children->id.$exam->id}}" role="tabpanel" aria-labelledby="home-tab{{$children->id.$exam->id}}">
                                        <div class="container-fluid p-0">
                                            <div class="col-lg-12">
                                                <x-table>
                                                    <table id="default_table" class="table" cellspacing="0" width="100%">
                                                        <thead>
                                                        <tr>
                                                            <th style="width:10%;">
                                                                @lang('exam.date_&_day')
                                                            </th>
                                                            <th>@lang('exam.subject')</th>
                                                            <th>@lang('common.class_Sec')</th>
                                                            <th>@lang('exam.teacher')</th>
                                                            <th>@lang('exam.time')</th>
                                                            <th>@lang('exam.duration')</th>
                                                            <th>@lang('exam.room')</th>
                        
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($exam_routines as $date => $exam_routine)
                                                            <tr class="{{Carbon::parse($exam_routine->date)->format('Y-m-d') == Carbon::now()->format('Y-m-d') ? 'main-border-color' : '' }}">
                                                                <td>{{ dateConvert($exam_routine->date) }}
                                                                    <br>{{ Carbon::createFromFormat('Y-m-d', $exam_routine->date)->format('l') }}
                                                                </td>
                                                                <td>
                                                                    <strong> {{ $exam_routine->subject ? $exam_routine->subject->subject_name :'' }} </strong> {{ $exam_routine->subject ? '('.$exam_routine->subject->subject_code .')':'' }}
                                                                </td>
                                                                <td>{{ $exam_routine->class ? $exam_routine->class->class_name :'' }} {{ $exam_routine->section ? '('. $exam_routine->section->section_name .')':'' }}</td>
                                                                <td>{{ $exam_routine->teacher ? $exam_routine->teacher->full_name :'' }}</td>
                        
                                                                <td> {{ date('h:i A', strtotime(@$exam_routine->start_time))  }}
                                                                    - {{ date('h:i A', strtotime(@$exam_routine->end_time))  }} </td>
                                                                <td>
                                                                    @php
                                                                        $duration=strtotime($exam_routine->end_time)-strtotime($exam_routine->start_time);
                                                                    @endphp
                        
                                                                    {{ timeCalculation($duration)}}
                                                                </td>
                        
                                                                <td>{{ $exam_routine->classRoom ? $exam_routine->classRoom->room_no :''  }}</td>
                        
                                                            </tr>
                                                        @endforeach
                        
                                                        </tbody>
                                                    </table>
                                                </x-table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if (userPermission('parent-dashboard-calendar'))
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('parent.calendar')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class='common-calendar'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span
                            class="sr-only">close</span></button>
                    <h4 id="modalTitle" class="modal-title"></h4>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="There are no image" id="image" height="150" width="auto">
                    <div id="modalBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/fullcalendar.min.js"></script>
    <script src="{{ asset('public/backEnd/vendors/js/fullcalendar-locale-all.js') }}"></script>

    <script type="text/javascript">
        /*-------------------------------------------------------------------------------
               Full Calendar Js
            -------------------------------------------------------------------------------*/
        if ($('.common-calendar').length) {
            $('.common-calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                eventClick: function(event, jsEvent, view) {
                    $('#modalTitle').html(event.title);
                    $('#modalBody').html(event.description);
                    $('#image').attr('src', event.url);
                    $('#fullCalModal').modal();
                    return false;
                },
                height: 650,
                events: <?php echo json_encode($calendar_events); ?>
            });
        }
    </script>
@endpush
