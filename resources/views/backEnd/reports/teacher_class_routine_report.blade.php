@extends('backEnd.master')
@section('title')
    @lang('academics.teacher_class_routine_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('academics.teacher_class_routine_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('academics.academics')</a>
                    <a href="#">@lang('academics.teacher_class_routine_report')</a>
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

                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'teacher-class-routine-report', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-12 mt-30-md">
                                <label class="primary_input_label" for="">
                                    {{ __('common.teacher') }}
                                    <span class="text-danger"> *</span>
                                </label>
                                <select
                                    class="primary_select form-control {{ $errors->has('teacher') ? ' is-invalid' : '' }}"
                                    name="teacher">
                                    <option data-display="@lang('common.select_teacher') *" value="">@lang('common.select_teacher') *</option>

                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"
                                            {{ isset($teacher_id) ? ($teacher_id == $teacher->id ? 'selected' : '') : '' }}>
                                            {{ $teacher->full_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('teacher'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('teacher') }}
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

    @if (isset($class_times))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('academics.class_routine')</h3>
                        </div>
                    </div>
                    <div class="col-lg-8 pull-right mb-30">
                        <a href="{{ route('print-teacher-routine', [$teacher_id]) }}"
                            class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>
                            @lang('academics.print')</a>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                       <x-table>
                        <table id="default_table" class="table school-table-data" cellspacing="0" width="100%">
                            <thead>

                                <tr>

                                    @php
                                        $height = 0;
                                        $tr = [];
                                    @endphp
                                    @foreach ($sm_weekends as $sm_weekend)
                                        @if ($sm_weekend->teacherClassRoutineAdmin->count() > $height)
                                            @php
                                                $height = $sm_weekend->teacherClassRoutineAdmin->count();
                                            @endphp
                                        @endif

                                        <th>{{ @$sm_weekend->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>

                                @php
                                    $used = [];
                                    $tr = [];
                                    
                                @endphp
                                @foreach ($sm_weekends as $sm_weekend)
                                    @php
                                        
                                        $i = 0;
                                    @endphp
                                    @foreach ($sm_weekend->teacherClassRoutineAdmin as $routine)
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
                                                $tr[$i][$sm_weekend->name][$loop->index]['class_name'] = $routine->class ? $routine->class->class_name : '';
                                                $tr[$i][$sm_weekend->name][$loop->index]['section_name'] = $routine->section ? $routine->section->section_name : '';
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
                                                            </span><br>
                                                        @else
                                                            <span
                                                                class="">{{ date('h:i A', strtotime(@$classes[$i]['start_time'])) }}
                                                                - {{ date('h:i A', strtotime(@$classes[$i]['end_time'])) }}
                                                            </span><br>
                                                            <span class=""> <strong> {{ $classes[$i]['subject'] }}
                                                                </strong> ({{ $classes[$i]['subject_code'] }}) </span><br>
                                                            @if ($classes[$i]['class_room'])
                                                                <span class=""> <strong>@lang('academics.room') :</strong>
                                                                    {{ $classes[$i]['class_room'] }} </span><br>
                                                            @endif
                                                            @if ($classes[$i]['class_name'])
                                                                <span class=""> {{ $classes[$i]['class_name'] }}
                                                                    @if ($classes[$i]['section_name'])
                                                                        (
                                                                        {{ $classes[$i]['section_name'] }} )
                                                                    @endif
                                                                </span>
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
                       </x-table>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@include('backEnd.partials.data_table_js')
