<div class="row">
    <input type="hidden" id="classToSectionRoute" value="{{route('fees.ajax-get-all-section')}}">
    <input type="hidden" id="sectionToStudentRoute" value="{{route('fees.ajax-section-all-student')}}">
    <input type="hidden" id="classToStudentRoute" value="{{route('fees.ajax-get-all-student')}}">
    <div class="col-lg-3 mt-30-md">
        <label class="primary_input_label" for="">
            {{ __('common.date_range') }}
                <span class="text-danger"></span>
        </label>
        <input class="primary_input_field primary_input_field form-control" type="text" name="date_range" value="">
    </div>

    <div class="col-lg-3 mt-30-md">
        <label class="primary_input_label" for="">
            {{ __('common.class') }}
                <span class="text-danger"></span>
        </label>
        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="feesSelectClass" name="class">
            <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
            @foreach($classes as $class)
                <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
            @endforeach
        </select>
        @if ($errors->has('class'))
        <span class="text-danger invalid-select" role="alert">
            {{ $errors->first('class') }}
        </span>
        @endif
    </div>

    <div class="col-lg-3 mt-30-md" id="feesSectionDiv">
        <label class="primary_input_label" for="">
            {{ __('common.section') }}
                <span class="text-danger"> </span>
        </label>
        <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="feesSection" name="section">
            <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
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

    <div class="col-lg-3 mt-30-md" id="selectStudentDiv">
        <label class="primary_input_label" for="">
            {{ __('common.student') }}
                <span class="text-danger"> </span>
        </label>
        <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="selectStudent" name="student">
            <option data-display="@lang('common.select_student')" value="">@lang('common.select_student')</option>
        </select>
        <div class="pull-right loader loader_style" id="student_section_loader">
            <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
        </div>
        @if ($errors->has('student'))
            <span class="text-danger invalid-select" role="alert">
                {{ $errors->first('student') }}
            </span>
        @endif
    </div>

    <div class="col-lg-12 mt-20 text-right">
        <button type="submit" class="primary-btn small fix-gr-bg">
            <span class="ti-search pr-2"></span>
            @lang('common.search')
        </button>
    </div>
</div>