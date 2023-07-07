@extends('backEnd.master')
@section('title')
    @lang('lesson::lesson.lesson_plan_overview')
@endsection
@section('mainContent')


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
    <section class="student-details">
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
                                    <div class="white-box ">                                      
                                        <div class="row">
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
                            
                                                    </tr>
                                                    </thead>
                            
                                                    <tbody>
                                                    @foreach ($record->LessonPlan as $data)
                                                        
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
                                                                    @lang('lesson::lesson.upcoming')     ({{$data->lesson_date}})<br>
                                                                @elseif($data->competed_date=="")
                                                                    @lang('lesson::lesson.assigned_date')({{$data->lesson_date}})
                                                                    <br>
                                                                @endif
                                                            </td>
                                                            <td>
                            
                                                                @if(date('Y-m-d')< $data->lesson_date && $data->competed_date=="")
                                                                    @lang('lesson::lesson.upcoming') <br>
                                                                @elseif($data->competed_date=="")
                                                                    @lang('lesson::lesson.incomplete') <br>
                                                                    <br>
                                                                @else
                                                                    <strong> @lang('lesson::lesson.completed')</strong> <br>
                                                                @endif
                            
                                                            </td>
                            
                            
                                                        </tr>
                                                    @endforeach
                            
                                                    </tbody>
                                                </table>
                                                </x-table>

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

@endsection
@include('backEnd.partials.data_table_js')