@extends('backEnd.master')
@section('title')
@lang('homework.edit_home_work')
@endsection
@section('mainContent')
<style>
   .input-right-icon button.primary-btn-small-input {
      top: 50% !important;
   }
   .input-right-icon button {
      position: absolute;
      bottom: 20px;
      right: 6%;
   }
</style>
<section class="sms-breadcrumb mb-40 white-box">
   <div class="container-fluid">
      <div class="row justify-content-between">
         <h1>@lang('homework.edit_home_work')</h1>
         <div class="bc-pages">
            <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
            <a href="{{route('homework-list')}}">@lang('homework.home_work_list')</a>
            <a href="{{route('homework_edit', [$homeworkList->id])}}">@lang('homework.edit_home_work')</a>
         </div>
      </div>
   </div>
</section>
<section class="admin-visitor-area">
   <div class="container-fluid p-0">
      <div class="row">
         <div class="col-lg-6">
            <div class="main-title">
               <h3 class="mb-30">@lang('homework.edit_home_work')</h3>
            </div>
         </div>
      </div>
      {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'homework_update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
      <div class="row">
         <div class="col-lg-12">
            <div class="white-box">
               <div class="">
                  <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                  <input type="hidden" name="id" value="{{$homeworkList->id}}"> 
                  <input type="hidden" name="course_id" value="{{$homeworkList->course_id}}"> 
                  <input type="hidden" name="class_id" value="{{$homeworkList->class_id}}"> 
                  <input type="hidden" name="section_id" value="{{$homeworkList->section_id}}"> 
                  <input type="hidden" name="subject_id" value="{{$homeworkList->subject_id}}">
                  
                @if(moduleStatusCheck('University'))
                    <div class="row mb-30">
                        @includeIf('university::common.session_faculty_depart_academic_semester_level',['subject'=>true,])
                    </div>
                @else
                  <div class="row mb-30">
                     <div class="col-lg-4">
                        <div class="primary_input">
                           <label class="primary_input_label" for="">@lang('common.class')<span class="text-danger"> *</span></label>
                           <select class="primary_select  form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}" name="class_id" id="classSelectStudent">
                              <option data-display="@lang('common.select_class') *" value="">@lang('common.select')</option>
                              @foreach($classes as $key=>$value)
                              <option value="{{$value->id}}" {{$homeworkList->class_id == $value->id? 'selected':''}}>{{$value->class_name}}</option>
                              @endforeach
                           </select>
                           
                           @if ($errors->has('class_id'))
                           <span class="text-danger invalid-select" role="alert">
                           {{ $errors->first('class_id') }}
                           </span>
                           @endif
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="primary_input" id="subjectSelecttDiv">
                           <label class="primary_input_label" for="">@lang('common.subject') <span class="text-danger"> *</span></label>
                           <select class="primary_select  form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }}" name="subject_id" id="subjectSelect">
                              <option data-display="Select Subject *" value="">@lang('homework.subject') *</option>
                              @foreach($subjects as $subject)
                              <option value="{{$subject->subject->id}}" {{$homeworkList->subject_id == $subject->subject->id? 'selected':''}}>{{$subject->subject->subject_name}}</option>
                              @endforeach
                           </select>
                           
                           @if ($errors->has('subject_id'))
                           <span class="text-danger invalid-select" role="alert">
                           {{ $errors->first('subject_id') }}
                           </span>
                           @endif
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="primary_input" id="sectionStudentDiv">
                           <label class="primary_input_label" for="">@lang('common.section') <span class="text-danger"> *</span></label>
                           <select class="primary_select  form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}" name="section_id" id="sectionSelectStudent">
                              <option data-display="@lang('common.select_section') *" value="">@lang('common.section') *</option>
                              @foreach($sections as $section)
                              <option value="{{$section->sectionName->id}}" {{$homeworkList->section_id == $section->sectionName->id? 'selected':''}}>{{$section->sectionName->section_name}}</option>
                              @endforeach
                           </select>
                           
                           @if ($errors->has('section_id'))
                           <span class="text-danger invalid-select" role="alert">
                           {{ $errors->first('section_id') }}
                           </span>
                           @endif
                        </div>
                     </div>
                  </div>
                @endif

                  <div class="row mb-30">
                     <div class="col-lg-3">
                        <div class="no-gutters input-right-icon">
                           <div class="col">
                              <div class="primary_input">
                                 <label class="primary_input_label" for="">@lang('homework.home_work_date') <span class="text-danger"> *</span></label>
                                 <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('homework_date') ? ' is-invalid' : '' }}" id="homework_date" type="text" name="homework_date" value="{{date('m/d/Y', strtotime($homeworkList->homework_date))}}" readonly="true">
                                 @if ($errors->has('homework_date'))
                                 <span class="text-danger" >
                                 {{ $errors->first('homework_date') }}
                                 </span>
                                 @endif
                              </div>
                           </div>
                           <div class="col-auto">
                              <button class="" type="button">
                                 <label class="m-0 p-0" for="homework_date">
                                    <i class="ti-calendar" id="homework_date_icon"></i>
                                 </label>
                              </button>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-3">
                        <div class="no-gutters input-right-icon">
                           <div class="col">
                              <div class="primary_input">
                                 <label class="primary_input_label" for="">@lang('homework.submission_date') <span class="text-danger"> *</span></label>
                                 <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('submission_date') ? ' is-invalid' : '' }}" id="submission_date" type="text" name="submission_date" value="{{date('m/d/Y', strtotime($homeworkList->submission_date))}}" readonly="true">
                                 @if ($errors->has('submission_date'))
                                 <span class="text-danger" >
                                 {{ $errors->first('submission_date') }}
                                 </span>
                                 @endif
                              </div>
                           </div>
                           <div class="col-auto">
                              <button class="" type="button">
                                 <label class="m-0 p-0" for="submission_date">
                                    <i class="ti-calendar" id="submission_date_icon"></i>
                                 </label>
                              </button>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-3">
                        <div class="row no-gutters input-right-icon">
                           <div class="col">
                              <div class="primary_input">
                                 <label class="primary_input_label" for="">@lang('homework.marks') <span class="text-danger"> *</span></label>
                                 <input class="primary_input_field form-control{{ $errors->has('marks') ? ' is-invalid' : '' }}" type="text" name="marks" autocomplete="off" value="{{$homeworkList->marks}}">
                                 @if ($errors->has('marks'))
                                 <span class="text-danger" >
                                 {{ $errors->first('marks') }}
                                 </span>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-3">
                        <div class="row no-gutters input-right-icon">
                           <div class="col">
                              <div class="primary_input">
                                 <label class="primary_input_label" for="">@lang('homework.attach_file') <span class="text-danger"> *</span></label>
                                 <input class="primary_input_field" type="text" id="placeholderHomeworkName" placeholder="{{$homeworkList->file != ""? getFilePath3($homeworkList->file):__('common.file')}}" readonly>
                                 
                              </div>
                           </div>
                           <div class="col-auto">
                              <button class="primary-btn-small-input" type="button">
                              <label class="primary-btn small fix-gr-bg" for="homework_file">@lang('common.browse')</label>
                              <input type="file" class="d-none" name="homework_file" id="homework_file">
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row md-20">
                     <div class="col-lg-12">
                        <div class="primary_input">
                           <label class="primary_input_label" for="">@lang('common.description') <span class="text-danger"> *</span> </label>
                           <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" cols="0" rows="4" name="description" id="description *">{{$homeworkList->description}}</textarea>
                           
                           @if ($errors->has('description'))
                           <span class="text-danger" >
                           {{ $errors->first('description') }}
                           </span>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row mt-40">
                  <div class="col-lg-12 text-center">
                     <button class="primary-btn fix-gr-bg">
                     <span class="ti-check"></span>
                     @lang('homework.update_home_work')
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   {{ Form::close() }}
   </div>
</section>
@endsection
@include('backEnd.partials.date_picker_css_js')