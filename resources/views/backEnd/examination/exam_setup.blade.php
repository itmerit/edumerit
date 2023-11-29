@extends('backEnd.master')
@section('title')
@lang('exam.exam')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="#">@lang('exam.exam')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area">
    @if(isset($exam))
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam-setup-store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        @else
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam',
        'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
        @endif
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($exam))
                                    @lang('common.add')
                                @else
                                    @lang('common.update')
                                @endif
                                @lang('exam.exam')
                            </h3>
                        </div>
                        
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                       
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('common.select_classes')</label>
                                            @php $h = 0; @endphp
                                        @foreach($classes as $class)
                                            <div class="primary_input">
                                                <input type="checkbox" id="classes_{{@@$class->id}}" class="common-checkbox" name="class_ids[]" value="{{@@$class->id}}" {{ (is_array(old('class_ids')) and in_array(@@$class->id, old('class_ids'))) ? ' checked' : '' }}>
                                                <label for="classes_{{@@$class->id}}">{{@@$class->class_name}}</label>
                                            </div>
                                            @php $h++; @endphp
                                        @endforeach

                                    </div>
                                    <div class="col-lg-12">

                                        @if($errors->has('class_ids'))
                                            <span class="text-danger validate-textarea-checkbox" role="alert">
                                                {{ $errors->first('class_ids') }}
                                            </span>
                                        @endif

                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('common.select_section')</label>
                                        @foreach($sections as $section)
                                            <div class="primary_input">
                                                <input type="checkbox" id="sections_{{@$section->id}}" class="common-checkbox" name="section_ids[]" value="{{@$section->id}}">
                                                <label for="sections_{{@$section->id}}">{{@$section->section_name}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-lg-12">

                                        @if($errors->has('section_ids'))
                                            <span class="text-danger validate-textarea-checkbox" role="alert">
                                                {{ $errors->first('section_ids') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('common.select_subjects')</label>
                                        @foreach($subjects as $subject)
                                            <div class="primary_input">
                                                <input type="checkbox" id="subjects_{{@$subject->id}}" class="common-checkbox" name="subjects_ids[]" value="{{@$subject->id}}">
                                                <label for="subjects_{{@$subject->id}}">{{@$subject->subject_name}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-lg-12">

                                        @if($errors->has('subjects_ids'))
                                            <span class="text-danger validate-textarea-checkbox" role="alert">
                                                {{ $errors->first('subjects_ids') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">

                                        <div class="primary_input">
                                            <input oninput="numberCheck(this)" class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            type="text" name="name" id="name" autocomplete="off" value="{{isset($exam)? $exam->name:''}}" readonly="">
                                            <label class="primary_input_label" for="">@lang('exam.exam_terms')</label>
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div> 

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="exam_term_id" value="{{$exam->id}}">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                            @foreach($classes as $class)
                                                <option value="{{@ @$class->id}}" {{isset($exam)? ($class->id == $exam->class_id? 'selected':''): (old('class') == $class->id? 'selected':'')}}>{{@ @$class->class_name}}</option>

                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('class') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12 mt-30-md" id="select_section_div">
                                        <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section"  readonly="">
                                            <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                            @if(isset($exam))
                                                @foreach($sections as $section)
                                                    <option value="{{ @$section->id}}" {{ @$exam->section_id == @$section->id? 'selected': ''}}>{{ @$section->section_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('section'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('section') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12" id="select_subject_div">
                                        <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" id="select_subject" name="subject"  readonly="">
                                            <option data-display="@lang('common.select_subjects') *" value="">@lang('common.select_subjects') *</option>
                                            @if(isset($exam))
                                                @foreach($subjects as $subject)
                                                    <option value="{{@$subject->id}}" {{@$exam->subject_id == @$subject->id? 'selected': ''}}>{{@$subject->subject_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('subject'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('subject') }}
                                        </span>
                                        @endif
                                    </div>
                                </div> 
                                <div class="row mt-25">
                                    <div class="col-lg-12">

                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off" value="{{isset($exam)? $exam->name:''}}">
                                            <input type="hidden" name="id" value="{{isset($exam)? $exam->id: ''}}"  readonly="">
                                            <label class="primary_input_label" for="">@lang('exam.exam_name')<span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">

                                        <div class="primary_input">
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control{{ $errors->has('exam_mark') ? ' is-invalid' : '' }}"
                                            type="text" name="total_exam_mark" id="exam_mark_main" autocomplete="off" value="{{isset($exam)? $exam->exam_mark:''}}" readonly="">
                                            <label class="primary_input_label" for="">@lang('exam.exam_mark')</label>
                                            
                                            @if ($errors->has('exam_mark'))
                                            <span class="text-danger" >
                                                {{ $errors->first('exam_mark') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div> 
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
               <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('exam.add_mark_distributions')</h3>
                    </div>
                </div>
                <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                    <button type="button" class="primary-btn small fix-gr-bg" onclick="addRowMark();" id="addRowBtn">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </button>
                </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
               <div class="white-box">
                   <table class="table" id="productTable">
                    <thead>
                      <tr>
                          <th>@lang('exam.exam_title')</th>
                          <th>@lang('exam.exam_mark')</th>
                          <th>@lang('common.action')</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr id="row1" class="0">
                            <td class="border-top-0">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">  
                                <div class="primary_input">
                                    <input class="primary_input_field form-control{{ $errors->has('exam_title') ? ' is-invalid' : '' }}"
                                    type="text" id="exam_title" name="exam_title[]" autocomplete="off" value="{{isset($editData)? $editData->exam_title : '' }}" placeholder="@lang('exam.exam_title')">


                                </div>
                            </td>
                            <td class="border-top-0">
                                <div class="primary_input">
                                    <input class="primary_input_field form-control{{ $errors->has('exam_mark') ? ' is-invalid' : '' }} exam_mark"
                                    type="text" id="exam_mark" name="exam_mark[]" autocomplete="off"   value="{{isset($editData)? $editData->exam_mark : '' }}">
                                </div>
                            </td> 
                            <td>
                                 <button class="primary-btn icon-only bg-danger text-light" type="button">
                                     <span class="ti-trash"></span>
                                </button>
                               
                            </td>
                        </tr>
                        <tfoot>
                            <tr>
                               <th class="border-top-0">@lang('exam.result')</th>

                               <th class="border-top-0" id="totalMark">
                                 <input type="text" class="primary_input_field form-control" name="totalMark" readonly="true">
                               </th>
                               <th class="border-top-0"></th>
                           </tr>
                       </tfoot>

                   </tbody>
               </table>
           </div>
       </div>
   </div>
    <div class="row mt-30">
        <div class="col-lg-12">
            <div class="white-box">
                <div class="row mt-40">
                    <div class="col-lg-12 text-center">
                        <button class="primary-btn fix-gr-bg"> 
                            <span class="ti-check"></span>
                            @if(isset($exam))
                                @lang('common.edit')
                            @else
                                @lang('common.add')
                            @endif
                            @lang('exam.mark_distribution')
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


        </div>
    </div>
</div>

{{ Form::close() }}


</section>
@endsection
