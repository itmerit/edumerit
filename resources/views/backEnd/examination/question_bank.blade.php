@extends('backEnd.master')
@section('title')
@lang('exam.question_bank')
@endsection
@push('css')
    <style>
        .common-checkbox~label::before, .common-checkbox~label::after {
            top: 20px !important;
        }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.question_bank')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.online_exam')</a>
                <a href="#">@lang('exam.question_bank')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($bank))
        @if(userPermission("question-bank-store"))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('question-bank')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        @endif
        <div class="row">

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($bank))
                                    @lang('exam.edit_question_bank')
                                @else
                                    @lang('exam.add_question_bank')
                                @endif
                               
                            </h3>
                        </div>
                       
                    
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="white-box">
                            @if(isset($bank))

                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('question-bank-update',$bank->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'question_bank']) }}
    
                            @else
                             @if(userPermission("question-bank-store"))
    
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'question-bank',
                            'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'question_bank']) }}
    
                            @endif
                            @endif
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">
                                            {{ __('exam.group') }}
                                                <span class="text-danger"> *</span>
                                        </label>
                                        <select class="primary_select  form-control{{ $errors->has('group') ? ' is-invalid' : '' }}" name="group">
                                            <option data-display="@lang('exam.select_group') *" value="">@lang('exam.select_group') *</option>
                                            @foreach($groups as $group)
                                                @if(isset($bank))
                                                <option value="{{$group->id}}" {{$group->id == $bank->q_group_id? 'selected': ''}}>{{$group->title}}</option>
                                                @else
                                                <option value="{{$group->id}}" {{old('group')!=''? (old('group') == $group->id? 'selected':''):''}} >{{$group->title}}</option>
                                                @endif

                                            @endforeach
                                        </select>
                                        @if ($errors->has('group'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('group') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">
                                            {{ __('common.class') }}
                                                <span class="text-danger"> *</span>
                                        </label>
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="class_id_email_sms" name="class">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                            @foreach($classes as $class)
                                                @if(isset($bank))
                                                <option value="{{$class->id}}" {{$bank->class_id == $class->id? 'selected': ''}}>{{$class->class_name}}</option>
                                                @else
                                                <option value="{{$class->id}}" {{old('class')!=''? (old('class') == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                                @endif

                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('class') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                              
                                <div class="row mt-15">
                                    @if(!isset($bank))        
                                      
                                    <div class="col-lg-12" id="selectSectionsDiv">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">{{ __('common.section')}} <span class="text-danger"> *</span></label>
                                            <select name="section[]" id="selectMultiSections" multiple="multiple" class="multypol_check_select active position-relative">
                                                
                                            </select>
                                        </div>
                                    </div>
                               @else
                              
                                        <div class="col-lg-12 mt-15" id="select_section_div">
                                            <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                                <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                                    @foreach($sections as $section)
                                                    @if(isset($bank))
                                                        <option value="{{$section->id}}" {{$bank->section_id == $section->id? 'selected': ''}}>{{$section->section_name}}</option>  
                                                    @endif
                                                    @endforeach
                                            </select>
                                            <div class="pull-right loader loader_style" id="select_section_loader">
                                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                            </div>
                                            @if ($errors->has('section'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('section') }}
                                            </span>
                                            @endif
                                        </div>
                                  @endif
                                  </div>
                             
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">
                                            {{ __('exam.question_type') }}
                                                <span class="text-danger"> *</span>
                                        </label>
                                        <select class="primary_select  form-control{{ $errors->has('question_type') ? ' is-invalid' : '' }}" name="question_type"  id="question-type">
                                            <option data-display="@lang('exam.question_type') *" value="">@lang('exam.question_type') *</option>
                                           
                                            @if (moduleStatusCheck('MultipleImageQuestion')== TRUE)
                                                <option value="MI" {{isset($bank)? $bank->type == "MI"? 'selected': '' : ''}}>@lang('exam.multiple_image')</option>
                                            @endif
                                            
                                            <option value="M" {{isset($bank)? $bank->type == "M"? 'selected': '' : ''}}>@lang('exam.multiple_choice')</option>
                                            <option value="T" {{isset($bank)? $bank->type == "T"? 'selected': '' : ''}}>@lang('exam.true_false')</option>
                                            <option value="F" {{isset($bank)? $bank->type == "F"? 'selected': '' : ''}}>@lang('exam.fill_in_the_blanks')</option>
                                        </select>
                                        @if ($errors->has('group'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('group') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('exam.question') <span class="text-danger"> *</span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('question') ? ' is-invalid' : '' }}" cols="0" rows="4" name="question">{{isset($bank)? $bank->question:(old('question')!=''?(old('question')):'')}}</textarea>
                                          
                                            
                                            @if ($errors->has('question'))
                                                <span class="error text-danger">{{ $errors->first('question') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('exam.marks') <span class="text-danger"> *</span></label>
                                            <input oninput="numberCheck(this)" class="primary_input_field form-control{{ $errors->has('marks') ? ' is-invalid' : '' }}" type="text" name="marks" value="{{isset($bank)? $bank->marks:(old('marks')!=''?(old('marks')):'')}}">
                                         
                                            
                                            @if ($errors->has('marks'))
                                            <span class="text-danger" >
                                                {{ $errors->first('marks') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php
                                    if(!isset($bank)){
                                        if(old('question_type') == "M"){
                                            $multiple_choice = "";
                                        }
                                    }else{
                                        if($bank->type == "M" || old('question_type') == "M"){
                                            $multiple_choice = "";
                                        }
                                    }
                                @endphp
                                <div class="multiple-choice" id="{{isset($multiple_choice)? $multiple_choice: 'multiple-choice'}}">
                                    <div class="row  mt-15">
                                        <div class="col-lg-8">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.number_of_options') <span class="text-danger"> *</span></label>
                                                <input class="primary_input_field form-control{{ $errors->has('number_of_option') ? ' is-invalid' : '' }}"
                                                    type="number" min="2" name="number_of_option" autocomplete="off" id="number_of_option" value="{{isset($bank)? $bank->number_of_option: ''}}">
                                              
                                                
                                                @if ($errors->has('number_of_option'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('number_of_option') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-2 mt-35">
                                            <button type="button" class="primary-btn small fix-gr-bg" id="create-option">@lang('common.create')</button>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    if(!isset($bank)){
                                        if(old('question_type') == "M"){
                                            $multiple_options = "";
                                        }
                                    }else{
                                        if($bank->type == "M" || old('question_type') == "M"){
                                            $multiple_options = "";
                                        }
                                    }
                                @endphp
                                <div class="multiple-options" id="{{isset($multiple_options)? "": 'multiple-options'}}">
                                    @php 
                                        $i=0;
                                        $multiple_options = [];

                                        if(isset($bank)){
                                            if($bank->type == "M"){
                                                $multiple_options = $bank->questionMu;
                                            }
                                        }
                                    @endphp
                                    
                                    @foreach($multiple_options as $multiple_option)
                                    @php $i++; @endphp
                                    <div class='row  mt-15'>
                                        <div class='col-lg-10'>
                                            <div class='primary_input'>
                                                <label class="primary_input_label" for="">@lang('exam.option') {{$i}}</label>
                                                <input class='primary_input_field form-control' type='text' name='option[]' autocomplete='off' required value="{{$multiple_option->title}}">
                                               
                                                
                                            </div>
                                        </div>
                                        <div class='col-lg-2'>
                                            <input type="checkbox" id="option_check_{{$i}}" class="common-checkbox" name="option_check_{{$i}}" value="1"
                                                @if ($multiple_option->status==1) checked @endif
                                            >
                                            <label for="option_check_{{$i}}"></label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @php
                                    if(!isset($bank)){
                                        if(old('question_type') == "T"){
                                            $true_false = "";
                                        }
                                    }else{
                                        if($bank->type == "T" || old('question_type') == "T"){
                                            $true_false = "";
                                        }
                                    }
                                @endphp
                                <div class="true-false" id="{{isset($true_false)? $true_false: 'true-false'}}">
                                    <div class="row  mt-15">
                                        <div class="col-lg-12 d-flex">
                                            <p class="text-uppercase fw-500 mb-10"></p>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-30">
                                                    <input type="radio" name="trueOrFalse" id="relationFather" value="T" class="common-radio relationButton" {{isset($bank)? $bank->trueFalse == "T"? 'checked': '' : 'checked'}}>
                                                    <label for="relationFather">@lang('exam.true')</label>
                                                </div>
                                                <div class="mr-30">
                                                    <input type="radio" name="trueOrFalse" id="relationMother" value="F" class="common-radio relationButton" {{isset($bank)? $bank->trueFalse == "F"? 'checked': '' : ''}}>
                                                    <label for="relationMother">@lang('exam.false')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    if(!isset($bank)){
                                        if(old('question_type') == "F"){
                                            $fill_in = "";
                                        }
                                    }else{
                                        if($bank->type == "F" || old('question_type') == "F"){
                                            $fill_in = "";
                                        }
                                    }
                                @endphp
                                <div class="fill-in-the-blanks" id="{{isset($fill_in)? $fill_in : 'fill-in-the-blanks'}}">
                                    <div class="row  mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.suitable_words') <span class="text-danger"> *</span></label>
                                                <textarea class="primary_input_field form-control{{ $errors->has('suitable_words') ? ' is-invalid' : '' }}" cols="0" rows="5" name="suitable_words">{{isset($bank)? $bank->suitable_words: ''}}</textarea>
                                                
                                                
                                                @if ($errors->has('suitable_words'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('suitable_words') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--Start Multiple Images Question --}}
                                @php
                                    if(!isset($bank)){
                                        if(old('question_type') == "MI"){
                                            $multiple_image = "";
                                        }
                                    }else{
                                        if($bank->type == "MI" || old('question_type') == "MI"){
                                            $multiple_image = "";
                                        }
                                    }
                                @endphp
                                <div class="multiple-image-section mt-20" id="{{isset($multiple_image)? $multiple_image : 'multiple-image-section'}}">
                                        
                                    <div class="row mt-15 mb-20">
                                        <div class="col-lg-12">
                                            <label class="primary_input_label" for="">
                                                {{ __('exam.answer_type') }}
                                                    <span class="text-danger"> *</span>
                                            </label>
                                            <select class="primary_select  form-control{{ $errors->has('answer_type') ? ' is-invalid' : '' }}" id="answer_type" name="answer_type" >
                                                <option data-display="@lang('exam.answer_type') *" value="">@lang('exam.answer_type') *</option>
                                                <option value="radio" {{isset($bank)? $bank->answer_type == "radio"? 'selected': '' : ''}}>@lang('exam.single_select')</option>
                                                <option value="checkbox" {{isset($bank)? $bank->answer_type == "checkbox"? 'selected': '' : ''}}>@lang('exam.multiple_select')</option>
                                            </select>
                                            @if ($errors->has('answer_type'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('answer_type') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row no-gutters input-right-icon mb-20">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <input
                                                        class="primary_input_field form-control {{ $errors->has('question_image') ? ' is-invalid' : '' }}"
                                                        readonly="true" type="text"
                                                        placeholder="{{isset($bank->question_image) && @$bank->question_image != ""? getFilePath3(@$bank->question_image):trans('exam.question_image').' *'}}  [650x450]"
                                                        id="placeholderUploadContent">
                                                    
                                                    @if ($errors->has('question_image'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('question_image') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                           for="upload_content_file">@lang('common.browse')</label>
                                                        
                                                    <input type="file" onclick="uploadQuestionImage()" class="d-none form-control" name="question_image"
                                                           id="upload_content_file">
                                                </button>
                                            </div>
                                            {{-- <code>(jpg,png,jpeg,pdf,doc,docx,mp4,mp3 are allowed for upload)</code> --}}
                                        </div>
                                            <style>
                                        .multiple-images ::-webkit-file-upload-button {
                                            background: #8432FA;
                                            color: white;
                                            border: #8432FA;
                                            }
                                            </style>
                                            
                                            <div class="row  mt-15">
                                                <div class="col-lg-8">
                                                    <div class="primary_input">
                                                        <label class="primary_input_label" for="">@lang('exam.number_of_options') <span class="text-danger"> *</span></label>
                                                        <input class="primary_input_field form-control{{ $errors->has('number_of_option') ? ' is-invalid' : '' }}"
                                                            type="number" min="2"  name="number_of_optionImg" autocomplete="off" id="number_of_image_option" value="{{isset($bank)? $bank->number_of_option: ''}}">
                                                       
                                                        
                                                        @if ($errors->has('number_of_option'))
                                                        <span class="text-danger" >
                                                            {{ $errors->first('number_of_option') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="button" class="primary-btn small fix-gr-bg" id="create-image-option">@lang('common.create')</button>
                                                </div>
                                            </div>

                                            <div class="multiple-images" id="multiple-images">
                                                @php 
                                                    $i=0;
                                                    $multiple_options = [];
            
                                                    if(isset($bank)){
                                                        if($bank->type == "MI"){
                                                            $multiple_options = $bank->questionMu;
                                                        }
                                                    }
                                                @endphp
                                                
                                                @foreach($multiple_options as $multiple_option)
                                                @php $i++; @endphp
                                                <div class='row  mt-15'>
                                                    <div class='col-lg-10'>
                                                        <div class='primary_input'>
                                                            <label class="primary-btn fix-gr-bg multiple_images">
                                                                <i class="fa fa-image"></i> <span class="show_file_name">{{ \Illuminate\Support\Str::limit(showFileName($multiple_option->title), 10, '...')}}</span> <input type="file" name='images[{{$multiple_option->id}}]' style="display: none;" name="image">
                                                            </label>
                                                            <input type="hidden" name="images_old[{{$multiple_option->id}}]" value="{{@$multiple_option->title}}">
                                                            {{-- <input class='primary_input_field form-control' type='file' name='images[]' autocomplete='off'  value="{{$multiple_option->title}}"> --}}
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                    <div class='col-lg-2 mt-10'>
                                                        <input type="checkbox" id="option_check_{{$i}}" class="common-checkbox" {{$multiple_option->status==1? 'checked':''}} name="option_check_{{$i}}" value="1">
                                                        <label for="option_check_{{$i}}">@lang('exam.yes')</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                </div>

                                 {{--End Multiple Images Question --}}
                                 {{ Form::close() }}
                                 
                                 @php 
                                  $tooltip = "";
                                  if(userPermission("question-bank-store") || userPermission('question-bank-edit')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                            </div>
                            <div class="row mt-40">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg" id="question_bank_submit" data-toggle="tooltip" title="{{$tooltip}}">
                                        <span class="ti-check"></span>
                                        @if(isset($bank))
                                            @lang('exam.update_question')
                                        @else
                                            @lang('exam.save_question')
                                        @endif
                                        
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- <button>test</button> --}}
                        
                            
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('exam.question_bank_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                                
                                <tr>
                                    <th>@lang('exam.group')</th>
                                    <th>@lang('common.class_Sec')</th>
                                    <th>@lang('exam.question')</th>
                                    <th>@lang('common.type')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($banks as $bank)
                                <tr>

                                    <td>{{($bank->questionGroup)!=''?$bank->questionGroup->title:''}}</td>
                                    <td>{{($bank->class)!=''?$bank->class->class_name:''}} ({{($bank->section)!=''?$bank->section->section_name:''}})</td>
                                    <td>{{$bank->question}}</td>
                                    <td>
                                        @if($bank->type == "M")
                                        {{"Multiple Choice"}}
                                        @elseif($bank->type == "T")
                                        {{"True False"}}
                                        @elseif($bank->type == "MI")
                                        {{"Multiple Image Choice"}}
                                        @else
                                        {{"Fill in the blank"}}
                                        @endif
                                    </td>
                                    <td>
                                        <x-drop-down>
                                               @if(userPermission('question-bank-edit'))

                                               <a class="dropdown-item" href="{{route('question-bank-edit', [$bank->id
                                                    ])}}">@lang('common.edit')</a>
                                                @endif
                                                @if(userPermission('question-bank-delete'))

                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteQuestionBankModal{{$bank->id}}"
                                                    href="#">@lang('common.delete')</a>
                                            @endif
                                        </x-drop-down>
                                    </td>
                                </tr>
                                {{-- <input type="file"> --}}
                                <div class="modal fade admin-query" id="deleteQuestionBankModal{{$bank->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('exam.delete_question_bank')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>
                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('question-bank-delete',$bank->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                     {{ Form::close() }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
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
@include('backEnd.partials.multi_select_js')
@section('script')
<script> 
    $(document).ready(function() { 
        $('.multiple_images input[type="file"]').change(function() { 
            console.log($(this).closest('.multiple_images').find('.show_file_name'));
            $(this).closest('.multiple_images').find('.show_file_name').html('File Selected');
            
        }); 
    }); 
</script> 
<script> 

function uploadImage(id) {
    $('.show_file_name'+id).html('File Selected');
    var select_image= $('#question_image'+id);

    console.log('initial image value '+ select_image.val());
    var file = document.getElementById("question_image"+id).files[0];
    if (file) {
        if (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/jpg") {
            var img = new Image();

            img.src = window.URL.createObjectURL(file);

            img.onload = function() {
                var width = img.naturalWidth,
                    height = img.naturalHeight;
                window.URL.revokeObjectURL(img.src);
                if (width <= 650 && height <= 450) {
                    $('.show_file_name'+id).html(file.name.substr(0, 10));
                } else {
                    $('.show_file_name'+id).html("Invalid image dimension");
                    $('#question_image'+id).val(null);
                }
            };
        } else {
            $('.show_file_name'+id).html("Invalid file type");
            $('#question_image'+id).val(null);
        }

    }

}

    
</script> 

    <script>

        $('#question_bank_submit').click(function(e){
            e.preventDefault();
            console.log(e);
            var ck_box = $('.multiple-images input[type="checkbox"]:checked').length;
            var answer_type = $("#answer_type").val();
            var question_type = $("#question-type").val();
            console.log(answer_type); 

            if(ck_box > 0){
                    if($("input[name='images[]']" ).val()=="")
                    { // alert for "address_" input
                        toastr.warning('Please Select Valid Option Images', 'Warning', {
                                    timeOut: 5000 })
                    }else{
                        if (answer_type=='radio' && ck_box >1 ) {
                            toastr.warning('Please Select One Correct Answer', 'Warning', {
                                    timeOut: 5000
                                })
                        } else {
                            $('#question_bank').submit();
                        }
                       
                    }
            }else{
                
                if (question_type!='MI') {
                    $('#question_bank').submit();
                }else{

                    toastr.warning('Please Select Correct  Answer', 'Warning', {
                                timeOut: 5000
                            })
                }
            } 

        });



        $(document).on('click', '.common-checkbox', function(){

            var answer_type = $("#answer_type").val();
            
            if (answer_type=='radio') {
                $('.common-checkbox').prop('checked', false);
                $(this).prop('checked', true)
            }

            })
    </script>



@endsection
@include('backEnd.partials.data_table_js')