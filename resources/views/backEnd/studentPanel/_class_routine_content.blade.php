@if($routineDashboard)
    <div class="col-lg-12 col-md-12">
        <div class="main-title">
            <h3 class="mb-30">@lang('academics.class_routine')</h3>
        </div>
    </div>
@endif
<div class="col-lg-12 student-details up_admin_visitor">
    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
        @foreach ($records as $key => $record)
            @if (moduleStatuscheck('University'))
                <li class="nav-item">
                    <a class="nav-link @if ($key == 0) active @endif" href="#tab{{ $key }}" role="tab" data-toggle="tab">
                        {{ $record->semesterLabel->name }} ({{ $record->unSection->section_name }}) -
                        {{ @$record->unAcademic->name }}
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link @if ($key == 0) active @endif" href="#tab{{ $key }}" role="tab"
                        data-toggle="tab">{{ $record->class->class_name }}
                        ({{ $record->section->section_name }}) </a>
                </li>
            @endif
        @endforeach
    </ul>
    @if (moduleStatuscheck('University'))
        <div class="tab-content">
            <!-- Start Fees Tab -->
            @foreach ($records as $key => $record)
                <div role="tabpanel"
                    class="tab-pane fade  @if ($key == 0) active show @endif"
                    id="tab{{ $key }}">
                    <div class="container-fluid p-0">
                        <div class="row mt-40">
                            @if(!$routineDashboard)
                                <div class="col-lg-6 col-md-6">
                                    <div class="main-title">
                                        <h3 class="mb-30">@lang('academics.class_routine')</h3>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6 pull-right">
                                <a href="{{ route('university.academics.classRoutinePrint', [$record->un_semester_label_id, $record->un_section_id]) }}"
                                    class="primary-btn small fix-gr-bg pull-right" target="_blank"><i
                                        class="ti-printer"> </i> Print</a>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table Crm_table_active3" cellspacing="0"
                                    width="100%">
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

                                            <th>{{ @$sm_weekend->name }}</th>
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
                                                    <td>
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
                                                                        <strong>@lang('academics.room')
                                                                            :</strong>
                                                                        {{ $classes[$i]['class_room'] }}
                                                                        <br> </span>
                                                                @endif
                                                                @if ($classes[$i]['teacher'])
                                                                    <span class="">
                                                                        {{ $classes[$i]['teacher'] }}
                                                                        <br> </span>
                                                                @endif
                                                            @endif
                                                        @endif

                                                    </td>
                                                @endforeach
                                            @endforeach
                                        </tr>
                                    @endfor
                                </table>
                                </x-table>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
            <!-- End Fees Tab -->
        </div>
    @else
        <div class="tab-content">
            <!-- Start Fees Tab -->
            @foreach ($records as $key => $record)
                <div role="tabpanel" class="tab-pane fade  @if ($key == 0) active show @endif" id="tab{{ $key }}">
                    <div class="container-fluid p-0">
                        <div class="row {{$routineDashboard ? 'mt-10' : 'mt-40'}}">
                            @if(!$routineDashboard)
                                <div class="col-lg-6 col-md-6">
                                    <div class="main-title">
                                        <h3 class="mb-30">@lang('academics.class_routine')</h3>
                                    </div>
                                </div>
                            @endif
                            @if(!$routineDashboard)
                                <div class="col-lg-6 pull-right">
                                    <a href="{{ route('classRoutinePrint', [@$record->class_id, @$record->section_id]) }}"
                                        class="primary-btn small fix-gr-bg pull-right" target="_blank"><i
                                            class="ti-printer"> </i> Print</a>
                                </div>
                            @endif
                            
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="default_table" class="table school-table-data {{ $routineDashboard ? 'customeDashboard' : '' }}"
                                    cellspacing="0" width="100%">
                                    <tr>
                                        @php
                                            $height = 0;
                                            $tr = [];
                                        @endphp
                                        @foreach ($sm_weekends as $sm_weekend)
                                            @php
                                                $studentClassRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id);
                                            @endphp
                                            @if ($studentClassRoutine->count() > $height)
                                                @php
                                                    $height = $studentClassRoutine->count();
                                                @endphp
                                            @endif
                                            <th class="{{ $routineDashboard ? (\Carbon\Carbon::now()->format('l') == $sm_weekend->name ? 'main-border-color' : '') : '' }}">{{ @$sm_weekend->name }}</th>
                                        @endforeach

                                    </tr>

                                    @php
                                        $used = [];
                                        $tr = [];
                                        
                                    @endphp
                                    @foreach ($sm_weekends as $sm_weekend)
                                        @php
                                            
                                            $i = 0;
                                            $studentClassRoutine = App\SmWeekend::studentClassRoutineFromRecord($record->class_id, $record->section_id, $sm_weekend->id);
                                        @endphp
                                        @foreach ($studentClassRoutine as $routine)
                                            @php
                                                if (!in_array($routine->id, $used)) {
                                                    $tr[$i][$sm_weekend->name][$loop->index]['subject'] = $routine->subject ? $routine->subject->subject_name : '';
                                                    $tr[$i][$sm_weekend->name][$loop->index]['subject_code'] = $routine->subject ? $routine->subject->subject_code : '';
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
                                                    <td class="{{ $routineDashboard ? (\Carbon\Carbon::now()->format('l') == $sm_weekend->name ? 'main-border-color' : '') : '' }}">
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
                                                                    <strong>@lang('common.time')
                                                                        :</strong>
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
                                                                        <strong>@lang('academics.room')
                                                                            :</strong>
                                                                        {{ $classes[$i]['class_room'] }}
                                                                        <br> </span>
                                                                @endif
                                                                @if ($classes[$i]['teacher'])
                                                                    <span class="">
                                                                        {{ $classes[$i]['teacher'] }}
                                                                        <br> </span>
                                                                @endif
                                                            @endif
                                                        @endif

                                                    </td>
                                                @endforeach
                                            @endforeach
                                        </tr>
                                    @endfor
                                </table>
                                </x-table>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach

            <!-- End Fees Tab -->
        </div>
    @endif
</div>
