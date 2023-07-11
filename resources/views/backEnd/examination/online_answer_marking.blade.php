@extends('backEnd.master')
@section('title')
@lang('exam.online_exam')
@endsection
@push('css')
    <style>
        .common-checkbox:checked:disabled+label:before {
            display: none !important;
        }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.examinations') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="{{route('online-exam')}}">@lang('exam.online_exam')</a>
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
                            <h3 class="mb-30">@lang('exam.marking_online_exam')</h3>
                        </div>
                    </div>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'online_exam_marks_store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <input type="hidden" name="online_exam_id" value="{{@$take_online_exam->online_exam_id}}">
                <input type="hidden" name="student_id" value="{{@$take_online_exam->student_id}}">
                <div class="row  white-box">
                    <div class="col-lg-12">
                        <div class="container-fluid exam-bg ">
                            <div class="">
                                <div class="row  pl-10">
                                    <div class="col-lg-7 mt-20">
                                        <h3>{{@$take_online_exam->onlineExam !=""?$take_online_exam->onlineExam->question:""}}</h3>
                                                    <h4><strong>@lang('common.subjects'): </strong>{{@$take_online_exam->onlineExam!=""?@$take_online_exam->onlineExam->subject->subject_name:""}}</h4>
                                                    <h4><strong>@lang('exam.total_marks'): {{@$total_mark}} </strong></h4>
                                                    <h4><strong>@lang('exam.minimum_percentage') : {{@$online_exam_info->percentage}} </strong></h4>
                                                    
                                    <p><strong>@lang('exam.instruction') : </strong>{{@$take_online_exam->onlineExam->instruction}}</p>
                                    </div>
                                    <div class="col-lg-5 mt-20">
                                        {{-- <p class="mb-2"><strong>@lang('exam.exam_has_to_be_submitted_within'): </strong>{{@$take_online_exam->onlineExam !=""?@$take_online_exam->onlineExam->date:""}} {{@$take_online_exam->onlineExam!=""?@$take_online_exam->onlineExam->end_time:""}}</p> --}}
                                        <p class="mb-2">
                                            <strong>@lang('common.date') & @lang('common.time'): </strong>{{@$take_online_exam->onlineExam !=""?dateConvert(@$take_online_exam->onlineExam->date):""}} {{@$take_online_exam->onlineExam!=""?dateConvert(@$take_online_exam->onlineExam->end_time):""}}
                                        </p>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="question_table">
                        <table id="table_id" class="table" cellspacing="0" width="100%">
                            <tbody>
                                
                                <tr>
                                    <th>@lang('exam.question')</th>
                                    <th class="text-right">@lang('exam.marks')</th>
                                    <th class="text-right">@lang('exam.incorrect')</th>
                                    <th class="text-right">@lang('exam.currect')</th>
                                </tr>
                                @php 
                                    $j=0;
                                    $answered_questions = $take_online_exam->answeredQuestions;
                                @endphp
                                @foreach($answered_questions as $question)
                                @php
                                    $student_answer=App\OnlineExamStudentAnswerMarking::StudentGivenAnswer($online_exam_info->id,$question->question_bank_id,$s_id);
                                    if ($question->questionBank->type=='MI') {
                                            $submited_answer=App\OnlineExamStudentAnswerMarking::StudentImageAnswer($online_exam_info->id,$question->question_bank_id,$s_id);
                                            $submited_answer_status=App\OnlineExamStudentAnswerMarking::StudentImageAnswerStatus($online_exam_info->id,$question->question_bank_id,$s_id);
                                        } 
                                @endphp
                               
                                <tr>
                                    <td width="80%">
                                        <h4 class="text-center">{{++$j.'.'}} {{@$question->questionBank->question}}</h4>
                                        @if(@$question->questionBank->type == "MI")
                                        <div class="qustion_banner_img">
                                            <img src="{{asset($question->questionBank->question_image)}}" alt="">
                                        </div>
                                     @endif
                                        @if(@$question->questionBank->type == "M")
                                        <input type="hidden"   name="options_{{@$question->question_bank_id}}" value="{{@$student_answer->user_answer}}">
                                            @php

                                                $multiple_options = $question->takeQuestionMu;
                                                $number_of_option = $question->takeQuestionMu->count();
                                                $i = 0;
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-center">
                                                @foreach($question->questionBank->questionMu as $multiple_option)
                                                <div class="mt-20 mr-20">
                                                    <input type="radio" disabled id="answer{{@$multiple_option->id}}" class="common-checkbox"  value="{{@$multiple_option->id}}" {{@$student_answer->user_answer == $multiple_option->id? 'checked': ''}}>
                                                    <label for="answer{{@$multiple_option->id}}">{{@$multiple_option->title}}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                            @elseif(@$question->questionBank->type == "MI")
                                            <input type="hidden"   name="options_{{@$question->question_bank_id}}" value="{{@$student_answer->user_answer}}">
                                            <div class="quiestion_group">
                                                                                                        
                                                @php
                                                    if($question->questionBank->answer_type=='radio'){
                                                            $question_type_class='radio_question';
                                                    }else{
                                                        $question_type_class='check_question';

                                                    }
                                                @endphp
                                                @foreach($question->questionBank->questionMu as $multiple_option)
                                                    <div class="single_question {{$question_type_class}} {{isset($submited_answer)? in_array($multiple_option->id,$submited_answer) ? 'active' :'' : '' }}" style="background-image: url({{asset($multiple_option->title)}})" 
                                                        data-question = "{{@$question->question_bank_id}}"
                                                        data-id="answer{{@$multiple_option->id}}"
                                                        data-name="options_{{@$question->question_bank_id}}"
                                                        data-value="{{$multiple_option->id}}" >

                                                        <div class="img_ovelay">
                                                            <input  data-question = "{{@$question->question_bank_id}}" type="{{@$question->questionBank->answer_type}}" 
                                                            data-option="{{@$multiple_option->id}}" id="answer{{@$multiple_option->id}}"
                                                             class="common-checkbox answer_question_mu" name="options_{{@$question->question_bank_id}}" 
                                                             value="{{$multiple_option->id}}" {{isset($submited_answer)? in_array($multiple_option->id,$submited_answer) ? 'checked' :'' : '' }}>
                                                    
                                                            <div class="icon">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
    
                                        @elseif($question->questionBank->type == "T")
                                        <input type="hidden"   value="{{@$student_answer->user_answer}}" name="trueOrFalse_{{@$question->question_bank_id}}" >
                                        <div class="d-flex align-items-center justify-content-center radio-btn-flex mt-20">
                                            <div class="mr-30">
                                                <input type="radio" disabled  id="true_{{@$question->question_bank_id}}" value="T" class="common-checkbox common-radio relationButton" {{@$student_answer->user_answer == "T"? 'checked': ''}}>
                                                <label for="true_{{@$question->question_bank_id}}">@lang('exam.true')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" disabled  id="false_{{@$question->question_bank_id}}" value="F" class="common-checkbox common-radio relationButton" {{@$student_answer->user_answer == "F"? 'checked': ''}}>
                                                <label for="false_{{@$question->question_bank_id}}">@lang('exam.false')</label>
                                            </div>
                                        </div>

                                        
                                        @else

                                            <div class="primary_input mt-20">
                                                <textarea readonly class="primary_input_field form-control mt-10 has-content" cols="0" rows="5" name="suitable_words_{{@$question->question_bank_id}}">{{@$student_answer->user_answer}}</textarea>
                                                <label class="primary_input_label" for="">@lang('exam.suitable_words')</label>
                                                
                                            </div>
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
                                            <h4 class="text-center">[@lang('exam.currect_answer'): {{$currect_multiple}}]</h4>
                                            @elseif(@$question->questionBank->type == "MI")
                                            @php
                                                $ques_bank_multiples = $question->questionBank->questionMu;
                                                $currect_multiple = '';
                                                $k = 0;
                                            @endphp
                                        <h4 class="text-center">[@lang('exam.currect_answer')]</h4>
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
                                                <h4 class="text-center">[@lang('exam.currect_answer'): {{@$question->questionBank->trueFalse == "T"? 'True': 'False'}}]</h4>
                                            @else 
                                                <h4 class="text-center">[@lang('exam.currect_answer'): {{@$question->questionBank->suitable_words}}]</h4>
                                            @endif
                                        </div>
                                    </td>
                                    <td width="5%" class="text-right">
                                        <span class="primary-btn fix-gr-bg">{{@$question->questionBank !=""?@$question->questionBank->marks:""}}</span>
                                          
                                    </td>
                                    @php
                                    if ($student_answer->marked_by!=0) {
                                        if ($student_answer->answer_status==1) {
                                            $correct="checked";
                                            $incorrect="";
                                        } else {
                                            $correct="";
                                            $incorrect="checked";
                                        }
                                    } else {
                                        $correct="";
                                        $incorrect="checked";
                                    }
                                    
                                       
                                        
                                    @endphp
                                    <td width="10%" class="text-right">
                                        <div class="mt-20 float-right">
                                            <input type="radio" id="marks_{{@$question->questionBank!=""?@$question->questionBank->id:""}}_incorrect"  class="common-checkbox common-radio relationButton" name="marks[]{{@$question->questionBank->id}}" {{@$incorrect}} >
                                            <label for="marks_{{@$question->questionBank!=""?@$question->questionBank->id:""}}_incorrect"></label>
                                        </div>
                                    </td>
                                    <td width="10%" class="text-right">
                                        <div class="mt-20">
                                            <input type="radio" id="marks_{{@$question->questionBank !=""?@$question->questionBank->id:""}}" class="common-checkbox common-radio relationButton" name="marks[]{{@$question->questionBank->id}}" value="{{@$question->questionBank!=""?@$question->questionBank->id:""}}" {{@$correct}}>
                                            <label for="marks_{{@$question->questionBank!=""?@$question->questionBank->id:""}}"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @if ($online_take_exam_mark->status==2)
                                    <tr>

                                        <td colspan="3" class="text-center">
                                            <div class="alert alert-warning" role="alert">
                                                @lang('exam.exam_marks_already_submitted')
                                              </div>
                                            <a href="{{ url()->previous() }}" class="primary-btn fix-gr-bg">
                                                {{-- <span class="ti-check"></span> --}}
                                                @lang('exam.already_submitted')
                                            </a>
                                        </td>
                                    </tr>
                                @else
                                    
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <button class="primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                @lang('exam.submit_marks')
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                 {{ Form::close() }}
            </div>
        </div>
    </div>
</section>



@endsection
@include('backEnd.partials.data_table_js')