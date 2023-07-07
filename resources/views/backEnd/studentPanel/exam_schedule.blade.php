@extends('backEnd.master')
@section('title')
    @lang('exam.exam_routine')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('exam.exam_routine') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#"> @lang('exam.examinations')</a>
                    <a href="#"> @lang('exam.exam_routine') </a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_exam_schedule_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-6 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                        name="exam">
                                    <option data-display="Select Exam *" value="">@lang('exam.select_exam') *</option>
                                    @php
                                        $exams = [];
                                    @endphp
                                    @foreach($records as $record)
                                        @if($record->Exam)
                                            @foreach($record->Exam as $exam)
                                                @php
                                                    $s = $exam->exam_type_id.$exam->class_id.$exam->section_id;
                                                @endphp
                                                @if(!in_array($s, $exams))
                                                    @php
                                                        array_push($exams, $s);
                                                    @endphp
                                                    <option value="{{$exam->id}}" {{isset($exam_id)? (@$exam->id == @$exam_id? 'selected':''):''}}>{{$exam->examType->title}}
                                                        - {{$record->class->class_name}}
                                                        ({{$record->section->section_name}})
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('exam') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-6 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    search
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(isset($assign_subjects))

        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('exam.exam_routine')</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 pull-right">
                        <div class="main-title">
                            <div class="print_button pull-right mb-30">
                                <a href="{{route('student-routine-print', [$class_id, $section_id,$exam_type_id])}}"
                                   class="primary-btn small fix-gr-bg pull-left" target="_blank"><i
                                            class="ti-printer"> </i> @lang('common.print')</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
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
                                    <tr>
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
        </section>

    @endif

@endsection
