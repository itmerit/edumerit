@extends('backEnd.master')
@section('title')
@lang('exam.take_online_exam')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Examinations </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="{{route('student_online_exam')}}">@lang('exam.online_exam')</a>
                <a href="{{route('take_online_exam',@$online_exam->id)}}">@lang('exam.take_online_exam')</a>
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
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_done_online_exam', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'online_take_exam']) }}
                <div class="row">
                    <input type="hidden" name="online_exam_id" id="online_exam_id" value="{{@$online_exam->id}}">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <table  class="" cellspacing="0" width="100%">
                                <tbody>
                                    <tr align="center" class="exam-bg">
                                        <td colspan="2" class="pt-4 pb-3 px-sm-5">
                                            <h1>@lang('exam.exam_name') : {{@$online_exam->title}}</h1>
                                            <h2><strong>@lang('common.subject') : </strong>{{@$online_exam->subject !=""?@$online_exam->subject->subject_name:""}}</h2>
                                            <h6><strong>@lang('common.class_Sec') : </strong>{{@$online_exam->class !=""?@$online_exam->class->class_name:""}} ({{@$online_exam->section !=""?@$online_exam->section->section_name:""}})</h6>
                                            <h3 class="mb-20"><strong>@lang('exam.total_marks') : </strong>
                                            @php
                                            @$total_marks = 0;
                                                foreach($online_exam->assignQuestions as $question){
                                                    $total_marks = $total_marks + $question->questionBank->marks;
                                                }
                                                echo @$total_marks;
                                            @endphp</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>@lang('exam.instruction') : </strong>{{@$online_exam->instruction}}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-2"><strong>@lang('exam.exam_has_to_be_submitted_within'): </strong>{{@$online_exam->date}} {{@$online_exam->end_time}}</p>
                                                <p id="countDownTimer"></p>
                                                </div>
                                            </div>
                                            <input type="hidden" id="count_date" value="{{@$online_exam->date}}">
                                            <input type="hidden" id="count_start_time" value="{{date('Y-m-d H:i:s', strtotime(@$online_exam->start_time))}}">
                                            <input type="hidden" id="count_end_time" value="{{date('Y-m-d H:i:s', strtotime(@$online_exam->end_time))}}">
                                        </td>
                                    </tr>
                                    @php $j=0; @endphp
                                    @foreach($assigned_questions as $question)
                                    @php
                                    $student_id=Auth::user()->student->id;
                                        $submited_answer=App\OnlineExamStudentAnswerMarking::StudentGivenAnswer($question->online_exam_id,$question->question_bank_id,$student_id);
                                        if ($question->questionBank->type=='MI') {
                                            $submited_answer=App\OnlineExamStudentAnswerMarking::StudentImageAnswer($question->online_exam_id,$question->question_bank_id,$student_id);
                                            
                                        }
                                    @endphp
                                    <input type="hidden" name="online_exam_id" value="{{@$question->online_exam_id}}">
                                    <input type="hidden" name="question_ids[]" value="{{@$question->question_bank_id}}">

                                    
                                    <tr>
                                        <td width="80%" class="pt-5">
                                            {{++$j.'.'}} {{@$question->questionBank->question}}
                                            @if(@$question->questionBank->type == "MI")
                            
                                            <img class="mb-20" width="100%" height="auto" src="{{asset($question->questionBank->question_image)}}" alt="">
                                        @endif
                                            @if(@$question->questionBank->type == "M")
                                                @php
                                                    @$multiple_options = @$question->questionBank->questionMu;
                                                    @$number_of_option = @$question->questionBank->questionMu->count();
                                                    $i = 0;
                                                @endphp
                                                @foreach($multiple_options as $multiple_option)
                                                <div class="mt-20">
                                                <input  data-question = "{{@$question->question_bank_id}}" type="radio" data-option="{{@$multiple_option->id}}" id="answer{{@$multiple_option->id}}" class="common-checkbox answer_question_mu" name="options_{{@$question->question_bank_id}}" value="{{$multiple_option->id}}" {{isset($submited_answer)? $submited_answer->user_answer==$multiple_option->id? 'checked' :'' : '' }}>
                                                    <label for="answer{{@$multiple_option->id}}">{{@$multiple_option->title}}</label>
                                                </div>
                                                @endforeach

                                            @elseif($question->questionBank->type == "MI")
                                                @php
                                                    @$multiple_options = @$question->questionBank->questionMu;
                                                    @$number_of_option = @$question->questionBank->questionMu->count();
                                                    $i = 0;
                                                @endphp
                                                <div class="upload_grid_wrapper">
                                                    @foreach($multiple_options as $multiple_option)
                                                     
                                                        <div class="single_upload_img">
                                                            <div class="img_check">
                                                                <input  data-question = "{{@$question->question_bank_id}}" type="{{@$question->questionBank->answer_type}}" data-option="{{@$multiple_option->id}}" id="answer{{@$multiple_option->id}}" class="common-checkbox answer_question_mu" name="options_{{@$question->question_bank_id}}" value="{{$multiple_option->id}}" {{isset($submited_answer)? in_array($multiple_option->id,$submited_answer) ? 'checked' :'' : '' }}>
                                                                <label for="answer{{@$multiple_option->id}}"></label>
                                                                {{-- {{@$multiple_option->title}}  --}}
                                                            </div>
                                                            <img src="{{asset($multiple_option->title)}}" alt="">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($question->questionBank->type == "T")
                                            <div class="d-flex radio-btn-flex mt-20">
                                                <div class="mr-30">
                                                    <input data-question = "{{@$question->question_bank_id}}" type="radio" name="trueOrFalse_{{@$question->question_bank_id}}" id="true_{{@$question->question_bank_id}}" value="T" {{isset($submited_answer)? $submited_answer->user_answer=='T'? 'checked' :'' : '' }} class="common-radio relationButton answer_question_mu">
                                                    <label for="true_{{$question->question_bank_id}}">True</label>
                                                </div>
                                                <div class="mr-30">
                                                    <input  data-question ="{{@$question->question_bank_id}}" type="radio" name="trueOrFalse_{{@$question->question_bank_id}}" id="false_{{@$question->question_bank_id}}" value="F" {{isset($submited_answer)? $submited_answer->user_answer=='F'? 'checked' :'' : '' }} class="common-radio relationButton answer_question_mu">
                                                    <label for="false_{{@$question->question_bank_id}}">False</label>
                                                </div>
                                            </div>
                                            @else

                                                <div class="row">
                                                    <div class="col-10">
                                                        <div class="primary_input mt-20">
                                                            <label class="primary_input_label" for="">@lang('exam.suitable_words')</label>
                                                            <textarea class="primary_input_field form-control mt-10 form_filler_{{@$question->question_bank_id}}" name="answer_word_{{@$question->question_bank_id}}" id="answer_word_{{@$question->question_bank_id}}">{{isset($submited_answer)? $submited_answer->user_answer : '' }} </textarea>
                                                           
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <p class="primary-btn fix-gr-bg" data-question = "{{@$question->question_bank_id}}" onclick="fillBlanks({{@$question->question_bank_id }})">{{ __('common.Fill') }}</p>
                                                    </div>
                                                </div>
                                                
                                            @endif
                                        </td>
                                        <input type="hidden" name="marks[]" value="{{@$question->questionBank!=""?@$question->questionBank->id:""}}">
                                        <td width="20%" class="text-right">
                                             <div class="std_mark_box">

                                                <strong>{{@$question->questionBank!=""?@$question->questionBank->marks:""}}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-center pt-4">
                                            <button class="primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                @lang('exam.submit_exam')
                                            </button>
                                        </td>
                                    </tr>
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


@push('script')
<script>
    $(document).on('change','.answer_question_mu',function (){
        let question_id = $(this).data('question');
        let option = $(this).data('option');
        let online_exam_id = $('#online_exam_id').val();
        let submit_value = '';
                if ($(this).is(':checked'))
                {
                    submit_value = $(this).val();
                }

                $.ajax({
                url : "{{route('ajax_student_online_exam_submit')}}",
                method : "GET",
                data : {
                    online_exam_id : online_exam_id,
                    question_id : question_id,
                    option : option,
                    submit_value : submit_value,
                },
                success : function (result){
                    // console.log(result);
                    if (result.type=='warning') {
                        toastr.warning(result.message, result.title, {
                            timeOut: 5000
                        })
                    } else {
                        // toastr.success(result.message, result.title, {
                        //     timeOut: 5000
                        // })
                    }
                    
                }
            })
                
    });
    function fillBlanks(question_id) {
        let online_exam_id = $('#online_exam_id').val();
        let submit_value = $('#answer_word_'+question_id).val();
                $.ajax({
                url : "{{route('ajax_student_online_exam_submit')}}",
                method : "GET",
                data : {
                    online_exam_id : online_exam_id,
                    question_id : question_id,
                    submit_value : submit_value,
                },
                success : function (result){
                    // console.log(result);
                    if (result.type=='warning') {
                        toastr.warning(result.message, result.title, {
                            timeOut: 5000
                        })
                    } else {
                        toastr.success(result.message, result.title, {
                            timeOut: 5000
                        })
                    }
                }
            })
    }

</script>


    @endpush
