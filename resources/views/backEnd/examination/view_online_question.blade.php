@extends('backEnd.master')
@section('title')
@lang('exam.online_exam')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.examinations') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="{{route('student_online_exam')}}">@lang('exam.online_exam')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('exam.take_online_exam')</h3>
                        </div>
                    </div>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_online_exam_submit', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'online_take_exam']) }}
                <div class="row">
                    <input type="hidden" name="online_exam_id" id="online_exam_id" value="{{@$online_exam->id}}">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <div class="container-fluid exam-bg">
                                <div class="">
                                    <div class="row  pl-10">
                                        <div class="col-lg-7 mt-20">
                                            <h3>@lang('exam.exam_name') : {{@$online_exam->title}}</h3>
                                                        <h4><strong>@lang('common.subject') : </strong>{{@$online_exam->subject !=""?@$online_exam->subject->subject_name:""}}</h4>
                                                        <h4><strong>@lang('common.class_Sec') : </strong>{{@$online_exam->class !=""?@$online_exam->class->class_name:""}} ({{@$online_exam->section !=""?@$online_exam->section->section_name:""}})</h4>
                                                        <h4 class="mb-20"><strong>@lang('exam.total_marks') : </strong>
                                                        @php
                                                        @$total_marks = 0;
                                                            foreach($online_exam->assignQuestions as $question){
                                                                $total_marks = $total_marks + $question->questionBank->marks;
                                                            }
                                                            echo @$total_marks;
                                                        @endphp</h4>
                                        <p><strong>@lang('exam.instruction') : </strong>{{@$online_exam->instruction}}</p>
                                        </div>
                                        <div class="col-lg-5 mt-20">
                                            <p class="mb-2"><strong>@lang('exam.exam_has_to_be_submitted_within'): </strong>{{@$online_exam->date}} {{@$online_exam->end_time}}</p>
                                            <p id="countDownTimer"></p>
                                            
                                            <input type="hidden" id="count_date" value="{{@$online_exam->date}}">
                                            <input type="hidden" id="count_start_time" value="{{date('Y-m-d H:i:s', strtotime(@$online_exam->start_time))}}">
                                            <input type="hidden" id="count_end_time" value="{{date('Y-m-d H:i:s', strtotime(@$online_exam->end_time))}}">
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">

                            
                            <table  class="" cellspacing="0" width="100%">
                                <tbody>
                                    
                                    @php $j=0; @endphp
                                    @foreach($assigned_questions as $question)
                                   
                                    <input type="hidden" name="online_exam_id" value="{{@$question->online_exam_id}}">
                                    <input type="hidden" name="question_ids[]" value="{{@$question->question_bank_id}}">

                                    
                                    <tr>
                                        <td width="80%" class="pt-5">
                                           <h4>{{++$j.'.'}} {{@$question->questionBank->question}}</h4> 
                                            @if(@$question->questionBank->type == "MI")
                                                <div class="qustion_banner_img">
                                                    <img src="{{asset($question->questionBank->question_image)}}" alt="">
                                                </div>
                                            @endif
                                            @if(@$question->questionBank->type == "M")
                                                @php
                                                    @$multiple_options = @$question->questionBank->questionMu;
                                                    @$number_of_option = @$question->questionBank->questionMu->count();
                                                    $i = 0;
                                                @endphp
                                                <div class="d-flex align-items-center justify-content-center">
                                                    @foreach($multiple_options as $multiple_option)
                                                    <div class="mt-20 mr-20">
                                                    <input  data-question = "{{@$question->question_bank_id}}" type="radio" id="answer{{@$multiple_option->id}}" class="common-checkbox answer_question_mu" name="options_{{@$question->question_bank_id}}" value="{{$multiple_option->id}}">
                                                        <label for="answer{{@$multiple_option->id}}">{{@$multiple_option->title}}</label>
                                                    </div>
                                                    @endforeach
                                                </div>

                                            @elseif($question->questionBank->type == "MI")
                                            @php
                                                @$multiple_options = @$question->questionBank->questionMu;
                                                @$number_of_option = @$question->questionBank->questionMu->count();
                                                $i = 0;
                                            @endphp
                                            <div class="quiestion_group">
                                                @foreach($multiple_options as $multiple_option)
                                                    <div class="single_question " style="background-image: url({{asset($multiple_option->title)}})">

                                                        <div class="img_ovelay">
                                                            <div class="icon">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @elseif($question->questionBank->type == "T")
                                            <div class="d-flex align-items-center justify-content-center radio-btn-flex mt-20">
                                                <div class="mr-30">
                                                    <input data-question = "{{@$question->question_bank_id}}" type="radio" name="trueOrFalse_{{@$question->question_bank_id}}" id="true_{{@$question->question_bank_id}}" value="T"  class="common-radio relationButton answer_question_mu">
                                                    <label for="true_{{$question->question_bank_id}}">@lang('exam.true')</label>
                                                </div>
                                                <div class="mr-30">
                                                    <input  data-question ="{{@$question->question_bank_id}}" type="radio" name="trueOrFalse_{{@$question->question_bank_id}}" id="false_{{@$question->question_bank_id}}" value="F"  class="common-radio relationButton answer_question_mu">
                                                    <label for="false_{{@$question->question_bank_id}}">@lang('exam.false')</label>
                                                </div>
                                            </div>
                                            @else

                                            @endif

                                            <div class="mt-20">
                                                @if($question->questionBank->type == "M")
                                                @php
                                                    $ques_bank_multiples = $question->questionBank->questionMu;
                                                    $currect_multiple = '';
                                                    $k = 0;
                                                    foreach($ques_bank_multiples as $ques_bank_multiple){
                                                    
                                                        if(@$ques_bank_multiple->status == 1){
                                                        $k++;
                                                            if($k == 1){
                                                                $currect_multiple .= $ques_bank_multiple->title;
                                                            }else{
                                                                $currect_multiple .= ','.$ques_bank_multiple->title;
                                                            }
                                                        }
                                                    }
    
                                                @endphp
                                                <h4>[@lang('exam.currect_answer'): {{$currect_multiple}}]</h4>

                                                @elseif(@$question->questionBank->type == "MI")
                                @php
                                    $ques_bank_multiples = $question->questionBank->questionMu;
                                    $currect_multiple = '';
                                    $k = 0;
                                @endphp
                                    <h4>[@lang('exam.currect_answer')]</h4>
                                <div class="quiestion_group">
                                    @php

                                    foreach($ques_bank_multiples as $ques_bank_multiple){
                                        if ($ques_bank_multiple->status == 0) {
                                            continue;
                                        }
                                    @endphp
                                    <div class="single_question "style="background-image: url({{asset($ques_bank_multiple->title)}})">

                                    <div class="img_ovelay">
                                    
                                        <div class="icon">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    </div>
                                    </div>

                                    @php
                                    
                                    }
                                  
                                    @endphp
                                </div>
                       
                                                @elseif(@$question->questionBank->type == "T")
                                                    <h4>[@lang('exam.currect_answer'): {{@$question->questionBank->trueFalse == "T"? 'True': 'False'}}]</h4>
                                                @else 
                                                    <h4>[@lang('exam.currect_answer'): {{@$question->questionBank->suitable_words}}]</h4>
                                                @endif
                                            </div>
                                        </td>
                                        <input type="hidden" name="marks[]" value="{{@$question->questionBank!=""?@$question->questionBank->id:""}}">
                                        <td width="20%" class="text-right">

                                                <span class="primary-btn fix-gr-bg">{{@$question->questionBank!=""?@$question->questionBank->marks:""}}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
                 {{ Form::close() }}
            </div>
        </div>
    </div>
</section>



@endsection


@push('script')

    @endpush
