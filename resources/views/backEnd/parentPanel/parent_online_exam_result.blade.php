@extends('backEnd.master')
@section('title') 
@lang('exam.online_exam_result')
@endsection

@section('mainContent')
@push('css')
<style>
    .QA_table.mt-30 {
        margin-top: 0 !important;
    }

</style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.online_exam')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.exam')</a>
                <a href="#">@lang('exam.online_exam_result')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">

                @foreach($records as $key => $record)
                    <li class="nav-item mb-0">
                        <a class="nav-link mb-0 @if($key== 0) active @endif " href="#tab{{$key}}" role="tab" data-toggle="tab">{{$record->class->class_name}} ({{$record->section->section_name}}) </a>
                    </li>
                    @endforeach

                </ul>
                <!-- Tab panes -->
                <div class="tab-content mt-10">
                    @foreach($records as $key => $record)
                        <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
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
                                        @php
                                            $results = App\Models\StudentRecord::getInfixStudentTakeOnlineExamParent($record->student_id, $record->id);
                                        @endphp
                                        @foreach($results as $result_view)
                                        
                                            <tr>
                                                <td>{{$result_view->onlineExam !=""?@$result_view->onlineExam->title:""}}</td>
                                                <td  data-sort="{{strtotime(@$result_view->onlineExam->date)}}" >
                                                    @if(!empty(@$result_view->onlineExam))
                                                {{@$result_view->onlineExam->date != ""? dateConvert(@$result_view->onlineExam->date):''}}


                                                    <br> Time: {{@$result_view->onlineExam->start_time.' - '.@$result_view->onlineExam->end_time}}
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
                                                    if($total_marks){
                                                        @$result = @$result_view->total_marks * 100 / @$total_marks;
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
                                                    {{-- <a class="btn btn-success modalLink" data-modal-size="modal-lg" title="Answer Script"  href="{{route('parent_answer_script', [@$result_view->online_exam_id, @$result_view->student_id])}}" >@lang('exam.answer_script')</a> --}}
                                                @php
                                                $startTime = strtotime($result_view->onlineExam->date . ' ' . $result_view->onlineExam->start_time);
                                                $endTime = strtotime($result_view->onlineExam->date . ' ' . $result_view->onlineExam->end_time);
                                                $now = date('h:i:s');
                                                $now =  strtotime("now");
                                                @endphp
                                                @if($now >= $endTime)
                                                @if (moduleStatusCheck('OnlineExam')== TRUE)
                                                    <a class="btn primary-btn" title="Answer Script"  href="{{route('om-parent_answer_script', [@$result_view->online_exam_id, @$result_view->student_id, $record->id])}}" >@lang('exam.answer_script')</a>
                                                
                                                @else
                                                    <a class="btn primary-btn small  fix-gr-bg" data-modal-size="modal-lg" title="Answer Script"  href="{{route('parent_answer_script', [@$result_view->online_exam_id, @$result_view->student_id])}}" >@lang('exam.answer_script')</a>
                                                
                                                @endif
                                                    
                                                @else
                                                    <span class="btn primary-btn small  fix-gr-bg" style="background:blue">@lang('exam.Wait_Till_Exam_Finish')</span>
                                                @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@include('backEnd.partials.data_table_js')
