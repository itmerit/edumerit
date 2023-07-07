@extends('backEnd.master')
@section('title')
    @lang('exam.exam_schedule')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('exam.exam_schedule')</h1>
                <div class="bc-pages">
                    <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examinations')</a>
                    <a href="#">@lang('exam.exam_schedule')</a>
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
                @if (userPermission('exam_schedule_create'))
                    <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                        <a href="{{ route('exam_schedule_create') }}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('exam.add_exam_schedule')
                        </a>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_schedule_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                    name="exam_type">
                                    <option data-display="Select Exam *"
                                        value="">@lang('common.select_exam') *
                                    </option>
                                    @foreach ($exam_types as $exam_type)
                                        <option value="{{ @$exam_type->id }}"
                                            {{ isset($exam_type_id) ? ($exam_type_id == $exam_type->id ? 'selected' : '') : '' }}>
                                            {{ @$exam_type->title }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('exam') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                    id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *"
                                        value="">@lang('common.select_class') *
                                    </option>
                                    @foreach ($classes as $class)
                                        <option value="{{ @$class->id }}"
                                            {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                                            {{ @$class->class_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md" id="select_section_div">
                                <select
                                    class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                    id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') "
                                        value="">@lang('common.select_section')
                                    </option>
                                </select>
                                @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (isset($exam_schedules))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('exam.exam_routine')|
                                <small>
                                    @lang('exam.exam'): {{ @$examName != '' ? $examName : ' ' }},
                                    @lang('common.class'):
                                    {{ @$search_current_class != '' ? $search_current_class->class_name : ' ' }},
                                    @lang('common.section'):
                                    {{ @$search_current_section != '' ? $search_current_section->section_name : 'All Sections' }},

                                </small>
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-6 pull-right">
                        <div class="main-title">
                            <div class="print_button pull-right mb-30">
                                <a href="{{ route('exam-routine-print', [$class_id, $section_id, $exam_type_id]) }}"
                                    class="primary-btn small fix-gr-bg pull-left" target="_blank"><i class="ti-printer">
                                    </i> @lang('common.print')</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div id="default_table_wrapper" class="dataTables_wrapper no-footer">
                            <x-table>
                                <table id="default_table" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:10%;">
                                                @lang('exam.date_&_day')
                                            </th>
                                            <th>@lang('common.subject')</th>
                                            <th>@lang('common.class_Sec')</th>
                                            <th>@lang('common.teacher')</th>
                                            <th>@lang('common.time')</th>
                                            <th>@lang('common.duration')</th>
                                            <th>@lang('common.room')</th>
    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($exam_schedules as $date => $exam_routine)
                                            <tr>
                                                <td>{{ dateConvert($exam_routine->date) }}
                                                    <br>{{ Carbon::createFromFormat('Y-m-d', $exam_routine->date)->format('l') }}
                                                </td>
                                                <td>
                                                    <strong>
                                                        {{ $exam_routine->subject ? $exam_routine->subject->subject_name : '' }}
                                                    </strong>
                                                    {{ $exam_routine->subject ? '(' . $exam_routine->subject->subject_code . ')' : '' }}
                                                </td>
                                                <td>{{ $exam_routine->class ? $exam_routine->class->class_name : '' }}
                                                    {{ $exam_routine->section ? '(' . $exam_routine->section->section_name . ')' : '' }}
                                                </td>
                                                <td>{{ $exam_routine->teacher ? $exam_routine->teacher->full_name : '' }}</td>
    
                                                <td> {{ date('h:i A', strtotime(@$exam_routine->start_time)) }} -
                                                    {{ date('h:i A', strtotime(@$exam_routine->end_time)) }} </td>
                                                <td>
                                                    @php
                                                        $duration = strtotime($exam_routine->end_time) - strtotime($exam_routine->start_time);
                                                    @endphp
    
                                                    {{ timeCalculation($duration) }}
                                                </td>
    
                                                <td>{{ $exam_routine->classRoom ? $exam_routine->classRoom->room_no : '' }}</td>
    
                                            </tr>
                                        @endforeach
    
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif



@endsection
