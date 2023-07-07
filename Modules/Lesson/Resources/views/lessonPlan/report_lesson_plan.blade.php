@extends('backEnd.master')
@section('title')
    @lang('lesson::lesson.teacher_lesson_plan_overview')
@endsection

@section('mainContent')
    <link rel="stylesheet" href="{{url('Modules/Lesson/Resources/assets/css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{url('Modules/Lesson/Resources/assets/css/lesson_plan.css')}}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#progressbar").progressbar({
                value: @isset($percentage) {{$percentage}} @endisset
            });
        });
    </script>


    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lesson::lesson.teacher_lesson_plan_overview')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('lesson::lesson.lesson')</a>
                    <a href="#">@lang('lesson::lesson.lesson_plan_overview')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">

        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'serach-report-lesson-plan', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_lesson_Plan']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="col-lg-3 mt-30-md">
                            <select class="primary_select form-control{{ $errors->has('teahcer') ? ' is-invalid' : '' }} select_teacher"
                                    name="teacher" id="select_teacher">
                                <option data-display="@lang('common.select_teacher') *"
                                        value="">@lang('common.select_teacher') *
                                </option>
                                @foreach($teachers as $teacher)
                                    <option value="{{$teacher->id}}" {{isset($teacher_id)? ($teacher_id == $teacher->id? 'selected':''):''}}>{{$teacher->full_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('teacher'))
                                <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('teacher') }}
                                    </span>
                            @endif
                        </div>


                        <div class="col-lg-3 mt-30-md" id="select_subject_div">
                            <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject"
                                    id="select_subject" name="subject">
                                <option data-display="Select subject *" value="">@lang('common.select_subjects')*
                                </option>
                                @isset($subjects)
                                    @foreach($subjects as $subject)
                                        <option value="{{$subject->id}}" {{isset($subject_id)? ($subject_id == $subject->id? 'selected':''):''}}>{{$subject->subject_name}}</option>
                                    @endforeach
                                @endisset
                            </select>
                            <div class="pull-right loader" id="select_subject_loader"
                                 style="margin-top: -30px;padding-right: 21px;">
                                <img src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt=""
                                     style="width: 28px;height:28px;">
                            </div>
                            @if ($errors->has('subject'))
                                <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('subject') }}
                                    </span>
                            @endif
                        </div>


                        <div class="col-lg-3 mt-20 text-right">
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
        @if(isset($lessonPlanner))
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title" style="padding-left: 15px;">
                                <h3 class="mb-0">@lang('lesson::lesson.Progress')


                                </h3><br>@isset($total)
                                    {{$completed_total}}/{{$total}}
                                @endisset

                                <div id="progressbar" style="height: 10px;margin-bottom:0px"></div>
                                <div class="pull-right" style="margin-top: -30px;">
                                    @isset($percentage)
                                        {{(int)($percentage)}}  %
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>

                                <tr>
                                    <th>@lang('lesson::lesson.lesson')</th>
                                    <th>@lang('common.topic')</th>
                                    <th>@lang('lesson::lesson.sup_topic')</th>
                                    <th>@lang('lesson::lesson.completed_date') </th>
                                    <th>@lang('lesson::lesson.upcoming_date') </th>
                                    <th>@lang('common.status')</th>

                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($lessonPlanner as $data)

                                    <tr>
                                        <td>{{@$data->lessonName !=""?@$data->lessonName->lesson_title:""}}</td>

                                        <td>
                                            @php
                                                $alllessonPlannerTopic=DB::table('lesson_planners')
                                                                     ->join('sm_lessons','sm_lessons.id','=','lesson_planners.lesson_detail_id')
                                                                    ->join('sm_lesson_topic_details','sm_lesson_topic_details.id','=','lesson_planners.topic_detail_id')
                                                                    ->where('lesson_detail_id',$data->lesson_detail_id)
                                                                    ->where('lesson_planners.active_status', 1)
                                                                    ->select('lesson_planners.*','sm_lessons.lesson_title','sm_lesson_topic_details.topic_title')
                                                                    ->get();
                                            @endphp
                                            @foreach($alllessonPlannerTopic as $allData)
                                                {{@$allData->topic_title}}<br>
                                            @endforeach

                                        </td>
                                        <td>
                                            @foreach($alllessonPlannerTopic as $allData)
                                                @php
                                                    $topicdate=DB::table('lesson_planners')->where('id',$allData->id)->first();
                                                @endphp
                                                {{@$topicdate->sub_topic !=""?@$topicdate->sub_topic:""}}<br>
                                            @endforeach

                                        </td>

                                        <td>
                                            @foreach($alllessonPlannerTopic as $allData)
                                                @php
                                                    $topicdate=DB::table('lesson_planners')->where('id',$allData->id)->first();
                                                @endphp
                                                {{@$topicdate->competed_date !=""?@$topicdate->competed_date:""}}<br>
                                            @endforeach

                                        </td>
                                        <td>
                                            @foreach($alllessonPlannerTopic as $allData)
                                                @php
                                                    $topicdate=DB::table('lesson_planners')->where('id',$allData->id)->first();
                                                @endphp


                                                @if(date('Y-m-d')< $topicdate->lesson_date)
                                                    <span> Upcoming</span>     ({{$topicdate->lesson_date}})<br>
                                                @elseif($topicdate->competed_date=="")
                                                    Assigned Date({{$topicdate->lesson_date}})
                                                    <br>
                                                @endif

                                            @endforeach

                                        </td>
                                        <td>
                                            @foreach($alllessonPlannerTopic as $allData)
                                                @php
                                                    $topicdate=DB::table('lesson_planners')->where('id',$allData->id)->first();
                                                @endphp

                                                @if($topicdate->competed_date=="")
                                                    Incomplete
                                                    <br>
                                                @else
                                                    Completed <br>
                                                @endif
                                            @endforeach
                                        </td>

                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </x-table>
                    </div>
                </div>
            </div>
        @endif
    </section>



    <div class="modal fade admin-query" id="showReasonModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('lesson::lesson.complete_date')  </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lessonPlan-complete-status',
                        'method' => 'POST',  'name' => 'myForm', 'onsubmit' => "return validateAddNewroutine()"]) }}
                    <div class="form-group">
                        <input type="hidden" name="lessonplan_id" id="lessonplan_id">
                        <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('complete_date') ? ' is-invalid' : '' }}"
                               id="complete_date" type="text"
                               name="complete_date" value="{{date('m/d/Y')}}">
                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">Close</button>
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.save') </button>

                    </div>
                    {{ Form::close() }}
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade admin-query" id="CancelModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <h1> Are You Sure</h1>
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lessonPlan-complete-status',
                        'method' => 'POST',  'name' => 'myForm', 'onsubmit' => "return validateAddNewroutine()"]) }}
                    <div class="form-group">
                        <input type="hidden" name="lessonplan_id" id="calessonplan_id">
                        <input type="hidden" name="cancel" value="incomplete">

                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">Close</button>
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('lesson::lesson.yes') </button>

                    </div>
                    {{ Form::close() }}
                </div>

            </div>
        </div>
    </div>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
