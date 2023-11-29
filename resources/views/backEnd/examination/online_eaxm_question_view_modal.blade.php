<div class="add-visitor">
            <div class="common-fields" id="common-fields">

                <div class="row  mt-25">
                    <div class="col-lg-12">
                        <div class="primary_input">
                            <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control read-only-input"
                                type="text" name="mark" autocomplete="off" value="{{@$question_bank->marks}}" required>
                            <label class="primary_input_label" for="">@lang('exam.marks')</label>
                            
                        </div>
                    </div>
                </div>
                <div class="row mt-25">
                    <div class="col-lg-12">
                        <div class="primary_input">
                            <textarea class="primary_input_field form-control read-only-input" cols="0" rows="5" name="question_title" readonly="true">{{@$question_bank->question}}</textarea>
                            <label class="primary_input_label" for="">@lang('exam.question_title')</label>
                            
                        </div>
                    </div>
                </div>
            </div>
            @if(@$question_bank->type == "M")
            @php
                $multiple_options = $question_bank->questionMu;
                $number_of_option = $question_bank->questionMu->count();
            @endphp
            <div class="multiple-choice" id="multiple-choice">
                
                <div class="row  mt-25">
                    <div class="col-lg-10">
                        <div class="primary_input">
                            <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control read-only-input"
                                type="text" name="number_of_option" autocomplete="off" id="number_of_option_edit" value="{{@$number_of_option}}">
                            <label class="primary_input_label" for="">@lang('exam.number_options')</label>
                            
                        </div>
                    </div>
                    <div class="col-lg-2">
                        {{-- <button class="primary-btn fix-gr-bg" type="button" id="create-option-edit">Create</button> --}}
                    </div>
                </div>
            </div>

            <div class="multiple-options" id="multiple-options">
                @php $i=0; @endphp
                @foreach($multiple_options as $multiple_option)
                @php $i++; @endphp
                <div class='row  mt-25'>
                    <div class='col-lg-10'>
                        <div class='primary_input'>
                            <input class='primary_input_field form-control read-only-input' type='text' name='option[]' autocomplete='off' required value="{{@$multiple_option->title}}">
                            <label class="primary_input_label" for="">@lang('exam.option') {{$i}}</label>
                            <span class='focus-border'></span>
                        </div>
                    </div>
                    <div class='col-lg-2'>
                        <input type='checkbox' class="common-checkbox" id="commonCheckbox{{$i}}" name='option_check_{{$i}}' value='1' {{@$multiple_option->status == 1? 'checked': ''}} disabled="">
                        <label for="commonCheckbox{{$i}}"></label>
                    </div>
                </div>
                @endforeach

            </div>
            @elseif(@$question_bank->type == "MI")
            @php
                $multiple_options = $question_bank->questionMu;
                $number_of_option = $question_bank->questionMu->count();
            @endphp
            <div class="multiple-choice" id="multiple-choice">
                
                <div class="row  mt-25">
                    <div class="col-lg-10">
                        <div class="primary_input">
                            <input class="primary_input_field form-control read-only-input"
                                type="number" name="number_of_option" autocomplete="off" id="number_of_option_edit" value="{{@$number_of_option}}">
                            <label class="primary_input_label" for="">@lang('exam.number_options')</label>
                            
                        </div>
                    </div>
                    <div class="col-lg-2">
                        {{-- <button class="primary-btn fix-gr-bg" type="button" id="create-option-edit">Create</button> --}}
                    </div>
                </div>
            </div>

            <div class="quiestion_group" id="multiple-options">
                @php $i=0; @endphp
                @foreach($multiple_options as $multiple_option)
                @php $i++; @endphp
                <div class='single_question' style="background-image: url({{asset($multiple_option->title)}})">
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
            @elseif($question_bank->type == "T")
            <div class="true-false" id="true-false">
                <div class="row  mt-25">
                    <div class="col-lg-12 d-flex">
                        <p class="text-uppercase fw-500 mb-10"></p>
                        <div class="d-flex radio-btn-flex">
                            <div class="mr-30">
                                <input type="radio" name="trueOrFalse" id="relationFatherEdit" value="T" class="common-radio relationButton" {{@$question_bank->trueFalse == 'T'? 'checked': ''}} disabled="">
                                <label for="relationFatherEdit">@lang('exam.true')</label>
                            </div>
                            <div class="mr-30">
                                <input type="radio" name="trueOrFalse" id="relationMotherEdit" value="F" class="common-radio relationButton" {{@$question_bank->trueFalse == 'F'? 'checked': ''}} disabled="">
                                <label for="relationMotherEdit">@lang('exam.false')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="fill-in-the-blanks" id="fill-in-the-blanks">
                <div class="row  mt-25">
                    <div class="col-lg-12">
                        <div class="primary_input">
                            <textarea class="primary_input_field form-control read-only-input" cols="0" rows="5" name="suitable_words" readonly="true">{{@$question_bank->suitable_words}}</textarea>
                            <label class="primary_input_label" for="">@lang('exam.suitable_words')</label>
                            
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>


