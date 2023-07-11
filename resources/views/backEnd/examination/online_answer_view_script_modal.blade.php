
<div class="row">
    <div class="col-lg-12">
        <table id="table_id" class="table" cellspacing="0" width="100%">
            <tbody>
                <tr align="center">
                    <td colspan="3">
                        <h4>{{ @$take_online_exam->onlineExam !=""?@$take_online_exam->onlineExam->question:""}}</h4>
                        <h3><strong>@lang('common.subjects'): </strong>{{ @$take_online_exam->onlineExam !=""? @$take_online_exam->onlineExam->subject->subject_name:""}}</h3>
                        <h3><strong>@lang('exam.total_marks'): {{@$take_online_exam->total_marks}} </strong></h3>
                        <h3>
                            <strong>@lang('common.exam_date'): </strong>{{@$take_online_exam->onlineExam !=""?dateConvert(@$take_online_exam->onlineExam->date):""}}
                        </h3>
                        <h3>
                            <strong>@lang('common.end_date'): </strong>{{@$take_online_exam->onlineExam!=""?dateConvert(@$take_online_exam->onlineExam->end_time):""}}
                        </h3>
                    </td>
                </tr>
                <tr>
                    <th>@lang('exam.question')</th>
                    <th class="text-right">@lang('exam.marks')</th>
                </tr>
                @php 
                    $j=0;
                    $answered_questions = $take_online_exam->answeredQuestions;
                @endphp
                @foreach($answered_questions as $question)
                @php
                    $student_answer=App\OnlineExamStudentAnswerMarking::StudentGivenAnswer($take_online_exam->online_exam_id,$question->question_bank_id,$s_id);
                    if ($question->questionBank->type=='MI') {
                            $submited_answer=App\OnlineExamStudentAnswerMarking::StudentImageAnswer($take_online_exam->online_exam_id,$question->question_bank_id,$s_id);
                            $submited_answer_status=App\OnlineExamStudentAnswerMarking::StudentImageAnswerStatus($take_online_exam->online_exam_id,$question->question_bank_id,$s_id);
                        } 
                @endphp
                <tr>
                   
                    <td width="90%">
                        <h5 class="mt-10 text-center">{{++$j.'.'}} {{@$question->questionBank!=""?@$question->questionBank->question:""}}</h5>
                        

                        @if(@$question->questionBank->type == "MI")
                            
                            <div class="qustion_banner_img" >
                                <img src="{{asset($question->questionBank->question_image)}}" alt="">
                            </div>
                        @endif

                        @if(@$question->questionBank->type == "M")
                            @php

                                $multiple_options = $question->takeQuestionMu;
                                $number_of_option = $question->takeQuestionMu->count();
                                $i = 0;
                            @endphp
                            <div class="d-flex align-items-center justify-content-center">
                                @foreach($question->questionBank->questionMu as $multiple_option)
                                <div class="mt-20 mr-20">
                                        <input type="checkbox" id="answer{{@$multiple_option->id}}" class="common-checkbox" name="options_{{@$question->question_bank_id}}_{{$i++}}" value="1" {{@$student_answer->user_answer==$multiple_option->id? 'checked': ''}} disabled>
                                        <label for="answer{{@$multiple_option->id}}">{{@$multiple_option->title}}</label>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(@$question->questionBank->type == "MI")
                            @php

                                $multiple_options = $question->takeQuestionMu;
                                $number_of_option = $question->takeQuestionMu->count();
                                $i = 0;
                            @endphp
                      

                            <div class="quiestion_group">
                                @foreach($question->questionBank->questionMu as $multiple_option)
                                    <div class="single_question {{isset($submited_answer)? in_array($multiple_option->id,$submited_answer) ? 'active' :'' : '' }}" style="background-image: url({{asset($multiple_option->title)}})">

                                        <div class="img_ovelay">
                                            <div class="icon">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @elseif(@$question->questionBank->type == "T")
                        <div class="d-flex align-items-center justify-content-center radio-btn-flex mt-20">
                            <div class="mr-30">
                                <input type="radio" name="trueOrFalse_{{@$question->question_bank_id}}" id="true_{{@$question->question_bank_id}}" value="T" class="common-radio relationButton" {{@$student_answer->user_answer == "T"? 'checked': ''}} disabled>
                                <label for="true_{{@$question->question_bank_id}}">@lang('exam.true')</label>
                            </div>
                            <div class="mr-30">
                                <input type="radio" name="trueOrFalse_{{@$question->question_bank_id}}" id="false_{{@$question->question_bank_id}}" value="F" class="common-radio relationButton" {{@$student_answer->user_answer == "F"? 'checked': ''}} disabled>
                                <label for="false_{{@$question->question_bank_id}}">@lang('exam.FALSE')</label>
                            </div>
                        </div>

                        
                        @else

                            <div class="primary_input mt-20">
                                <textarea class="primary_input_field form-control mt-10 has-content" cols="0" rows="5" name="suitable_words_{{@$question->question_bank_id}}">{{@$student_answer->user_answer}}</textarea>
                                <label class="primary_input_label" for="">@lang('exam.suitable_words')</label>
                                
                            </div>
                        @endif

                        <div class="mt-20">
                            @if(@$question->questionBank->type == "M")
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
                    @if (@$question->questionBank->type == "MI")
                        @if ($submited_answer_status==1)

                            <td width="10%" class="text-right">
                                <span class="primary-btn fix-gr-bg">
                                {{@$question->questionBank->marks}}
                                </span>
                            @else

                            <td width="10%" class="text-right"><span class="primary-btn fix-gr-bg">0</span></td>
                            
                        @endif
                   
                    @else
                    <td width="10%" class="text-right"><span class="primary-btn fix-gr-bg">{{@$student_answer->obtain_marks}}</span></td>
                        
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>
@include('backEnd.partials.data_table_js')
