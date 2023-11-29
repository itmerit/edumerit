{{-- single Exam Div  --}}

      <div class="row mt-25 mb-25">
          <div class="col-lg-12">
            <label> @lang('exam.exam_type') <span class="text-danger"> *</span></label>
              <select class="primary_select form-control {{ $errors->has('exams_type') ? ' is-invalid' : '' }}" id="exam_class" name="exams_type">
                  <option data-display="@lang('common.select_exam_type') *" value="">@lang('common.select_exam_type') *</option>
                  @foreach($exams_types as $exams_type)
                      <option value="{{@$exams_type->id}}">{{@$exams_type->title}}</option>
                  @endforeach
              </select>
              @if ($errors->has('exams_type'))
                  <span class="text-danger invalid-select" role="alert">
                      {{ $errors->first('exams_type') }}
                  </span>
              @endif
          </div>
      </div>
      @if(moduleStatusCheck('University'))
        @includeIf('university::common.session_faculty_depart_academic_semester_level',
        ['required' => 
            ['USN', 'UD', 'UA', 'US', 'USL'],
            'div'=>'col-lg-12','row'=>1,'mt'=>'mt-0' ,'subject'=>true, 
        ])

      {{-- <label class="mt-30">@lang('university::un.select_subject') <span class="text-danger"> *</span></label>
      <div class="row" id="universityExamSubejct"></div>
          <div class="text-center loader loader_style" id="unSubjectLoader">
              <img src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader" height="60px" width="60px">
          </div> --}}
      @else 
      <div class="row mt-25">
          <div class="col-lg-12">
            <label> @lang('common.class') <span class="text-danger"> *</span></label>
              <select class="primary_select form-control {{ $errors->has('class_id') ? ' is-invalid' : '' }}" id="classSelectStudentHomeWork" name="class_id">
                  <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                  @foreach($classes as $class)
                  <option value="{{@$class->id}}">{{@$class->class_name}}</option>
                  @endforeach
              </select>
              @if ($errors->has('class_id'))
                  <span class="text-danger invalid-select" role="alert">
                      {{ $errors->first('class_id') }}
                  </span>
              @endif
          </div>
      </div>
      <div class="row mt-25">
          <div class="col-lg-12">
              <div class="primary_input " id="subjectSelecttHomeworkDiv">
                <label> @lang('common.subject') <span class="text-danger"> *</span></label>
                  <select class="primary_select  form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }}"
                          name="subject_id" id="subjectSelect">
                      <option data-display="@lang('common.select_subjects') *"
                              value="">@lang('common.subject') *
                      </option>
                  </select>
                  <div class="pull-right loader loader_style" id="select_subject_loader">
                      <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                  </div>
                  
                  @if ($errors->has('subject_id'))
                      <span class="text-danger invalid-select" role="alert">
                              {{ $errors->first('subject_id') }}
                          </span>
                  @endif
              </div>
          </div>
      </div>
      <div class="row mt-25">
          <div class="col-lg-12 " id="selectSectionsDiv" style="margin-top: -25px;">
              <label for="checkbox" class="mb-2 mt-20">@lang('common.section') <span class="text-danger"> *</span></label>
                  <select multiple="multiple" id="selectSectionss" name="section_ids[]" style="width:300px" class="multypol_check_select active position-relative">
                    
                  </select>
                  
                  @if ($errors->has('section_ids'))
                      <span class="text-danger invalid-select" role="alert" style="display:block">
                          <strong style="top:-25px">{{ $errors->first('section_id') }}
                      </span>
                  @endif
          </div>
      </div>
      @endif


@include('backEnd.partials.multi_select_js_without_push')

<script>
    $(".primary_select").niceSelect('destroy');
    $(".primary_select").niceSelect();
</script>


@if(moduleStatusCheck('University'))
<script src="{{ asset('Modules/University/Resources/assets/js/app.js') }}"></script>
@else 
<script src="{{ asset('public/backEnd/js/custom.js') }}"></script>
<script src="{{ asset('public/backEnd/js/developer.js') }}"></script>
@endif 


  {{-- single Exam End  --}}