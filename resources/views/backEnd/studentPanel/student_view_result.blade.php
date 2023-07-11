@extends('backEnd.master')

@section('title')
@lang('exam.view_result')
@endsection

@section('mainContent')
@php
    $user = Auth::user()->student;
    date_default_timezone_set($time_zone_setup->time_zone);
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.online_exam')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.online_exam')</a>
                <a href="{{route('student_view_result')}}">@lang('exam.view_result')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('exam.online_exam_view_result')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead> 
                                <tr>
                                    <th>@lang('common.title')</th>
                                    <th>@lang('common.time')</th>
                                    <th>@lang('exam.total_marks')</th>
                                    <th>@lang('exam.obtained_marks') </th>
                                    <th>@lang('reports.result')</th>
                                    <th>@lang('common.status')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($result_views as $result_view)
                                    <tr>
                                        <td>{{$result_view->onlineExam !=""?@$result_view->onlineExam->title:""}}</td>
                                        <td  data-sort="{{strtotime(@$result_view->onlineExam->date)}}" >
                                            @if(!empty(@$result_view->onlineExam))
                                           {{@$result_view->onlineExam->date != ""? dateConvert(@$result_view->onlineExam->date):''}}


                                             {{-- Time: {{@$result_view->onlineExam->start_time.' - '.@$result_view->onlineExam->end_time}} --}}
                                             <br> Time: {{date('h:i A', strtotime(@$result_view->onlineExam->start_time)).' - '.date('h:i A', strtotime(@$result_view->onlineExam->end_time))}}
                                            @endif
                                        </td>
                                        <td>
                                            @php 
                                            $total_marks = 0;
                                            foreach($result_view->onlineExam->assignQuestions as $assignQuestion){
                                                @$total_marks = $total_marks + @$assignQuestion->questionBank->marks;
                                            }
                                            echo @$total_marks;
                                            @endphp
                                        </td>
                                        <td>{{@$result_view->total_marks}}</td>
                                        <td>
                                            @php
                                            if($total_marks > 0){
                                                $result = @$result_view->total_marks * 100 / @$total_marks;
                                            } else{
                                                $result = 0;
                                            }
                                               
                                                if(@$result >= @$result_view->onlineExam->percentage){
                                                    echo "Pass";  
                                                }else{
                                                    echo "Fail";
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                             @php
                                                $startTime = strtotime($result_view->onlineExam->date . ' ' . $result_view->onlineExam->start_time);
                                                $endTime = strtotime($result_view->onlineExam->date . ' ' . $result_view->onlineExam->end_time);
                                                $now = date('h:i:s');
                                                $now =  strtotime("now");
                                            @endphp

                                            <x-drop-down>

                                                    @if($now >= $endTime)
                                                    <a class=" dropdown-item btn btn-success modalLink" data-modal-size="modal-lg" title="@lang('exam.answer_script')"  href="{{route('student_answer_script', [@$result_view->online_exam_id, @$result_view->student_id])}}" >@lang('exam.answer_script')</a>
                                                    <a class="dropdown-item" href="{{route("student-online-exam-question-view", [$result_view->online_exam_id])}}">@lang('exam.view_question')</a>
                                                    
                                                    @else
                                                        <span class=" dropdown-item">@lang('exam.Wait_Till_Exam_Finish')</span>
                                                    @endif
                                                   </div>
                                                </div>
                                            </x-drop-down>
                                            
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
</section>


@endsection
@include('backEnd.partials.data_table_js')