@extends('backEnd.master')
@section('title')
    @lang('lesson::lesson.lesson_plan_overview')
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
                <h1>@lang('lesson::lesson.lesson_plan_overview')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('lesson::lesson.lesson')</a>
                    <a href="#">@lang('lesson::lesson.lesson_plan_overview')</a>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-lg-12">

                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'search-lesson-plan', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_lesson_Plan']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="col-lg-3 mt-30-md">
                            <label class="primary_input_label" for="">
                                {{ __('common.teacher') }}
                                <span class="text-danger"> *</span>
                            </label>
                            <select class="primary_select form-control{{ $errors->has('teahcer') ? ' is-invalid' : '' }}"
                                    name="teacher">
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

                        @if(moduleStatusCheck('University'))

                            @includeIf('university::common.session_faculty_depart_academic_semester_level',['ac_mt'=>'mt-25', 'required' => ['USN','UF', 'UD', 'UA', 'US', 'USL', 'USUB']])
                        @else
                            @includeIf('backEnd.common.search_criteria', [
                            'div' => 'col-lg-3',
                            'required'=>['class', 'section', 'subject'],
                            'visiable'=>['class', 'section', 'subject'],
                            'subject' => true
                            ])
                        @endif

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
        @if(isset($lessonPlanner))
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title" style="padding-left: 15px;">
                                <h3 class="mb-0">@lang('lesson::lesson.progress')


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
                                    <th>@lang('lesson::lesson.topic')</th>
                                    <th>
                                        @if(generalSetting()->sub_topic_enable)
                                            @lang('lesson::lesson.sup_topic')
                                        @else
                                            @lang('common.note')
                                        @endif
                                    </th>
                                    <th>@lang('lesson::lesson.completed_date') </th>
                                    <th>@lang('lesson::lesson.upcoming_date') </th>
                                    <th>@lang('common.status')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($lessonPlanner as $data)

                                    <tr>
                                        <td>{{@$data->lessonName !=""?@$data->lessonName->lesson_title:""}}</td>

                                        <td>

                                            @if(count($data->topics) > 0)
                                                @foreach ($data->topics as $topic)
                                                    {{$topic->topicName->topic_title}} </br>
                                            @endforeach
                                            @else
                                                {{$data->topicName->topic_title}}
                                            @endif

                                        </td>
                                        <td>
                                            @if(generalSetting()->sub_topic_enable)
                                                @if (count($data->topics) > 0)
                                                    @foreach ($data->topics as $topic)
                                                        {{$topic->sub_topic_title}} </br>
                                            @endforeach
                                            @else
                                                {{$data->sub_topic}}
                                            @endif
                                            @else
                                                {{$data->note}}
                                            @endif
                                        </td>

                                        <td>

                                            {{@$data->competed_date !=""?@$data->competed_date:""}}<br>


                                        </td>
                                        <td>


                                            @if(date('Y-m-d')< $data->lesson_date && $data->competed_date=="")
                                                @lang('lesson::lesson.upcoming') ({{$data->lesson_date}})<br>
                                            @elseif($data->competed_date=="")
                                                @lang('lesson::lesson.assigned_date') ({{$data->lesson_date}})
                                                <br>
                                            @endif


                                        </td>
                                        <td>


                                            @if($data->competed_date=="")
                                                Incomplete
                                                <br>
                                            @else
                                                Completed <br>
                                            @endif

                                        </td>

                                        <td>


                                            <label class="switch_toggle">
                                                <input type="checkbox" data-id="{{$data->id}}"
                                                       {{@$data->completed_status == 'completed'? 'checked':''}}
                                                       class="weekend_switch_topic">
                                                <span class="slider round"></span>
                                            </label> <br>


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
                        <button type="button" class="primary-btn fix-gr-bg"
                                data-dismiss="modal">{{ __('common.close') }}</button>
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
                    <h4 class="modal-title">@lang('common.status')  </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <h1>@lang('lesson::lesson.are_you_sure_to_incomplete')?</h1>
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lessonPlan-complete-status',
                        'method' => 'POST',  'name' => 'myForm', 'onsubmit' => "return validateAddNewroutine()"]) }}
                    <div class="form-group">
                        <input type="hidden" name="lessonplan_id" id="calessonplan_id">
                        <input type="hidden" name="cancel" value="incomplete">

                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn fix-gr-bg"
                                data-dismiss="modal">{{ __('common.close') }}</button>
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
@push('script')

    <script>
        $(document).ready(function () {
            $(".weekend_switch_topic").on("change", function () {
                var id = $(this).data("id");
                $('#lessonplan_id').val(id);
                $('#calessonplan_id').val(id);

                if ($(this).is(":checked")) {
                    var status = "1";
                    var modal = $('#showReasonModal');
                    modal.modal('show');

                } else {
                    var status = "0";
                    var modal = $('#CancelModal');
                    modal.modal('show');
                }


            });
        });
    </script>
@endpush