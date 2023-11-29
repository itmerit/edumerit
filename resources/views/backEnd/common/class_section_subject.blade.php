@php
    $div = isset($div) ? $div : 'col-lg-3';
    $mt = isset($mt) ? $mt : 'mt-30-md';
    $required = $required ?? [];
    $selected = isset($selected) ? $selected : null;
    
    $class_id = $selected && in_array('class_id', $selected) ? $selected['class_id'] : null;
    $section_id = $selected && in_array('section_id', $selected) ? $selected['section_id'] : null;
    $subject_id = $selected && in_array('subject_id', $selected) ? $selected['subject_id'] : null;
    $sections = $class_id ? sections($class_id) : null;
    $subjects = $class_id && $section_id ? subjects($class_id, $section_id) : null;
    $visiable = $visiable ?? [];
@endphp

@if(in_array('class', $visiable))
<div class="{{ $div . ' ' . $mt }}">
    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class"
        name="class_id">
        <option data-display="@lang('common.select_class') {{ in_array('class', $required) ? ' *' : '' }}" value="">
            @lang('common.select_class') {{ in_array('class', $required) ? ' *' : '' }}</option>
        @if (isset($classes))
            @foreach ($classes as $class)
                <option value="{{ $class->id }}"
                    {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                    {{ $class->class_name }}</option>
            @endforeach
        @endif
    </select>

    @if ($errors->has('class'))
        <span class="text-danger invalid-select" role="alert">
            {{ $errors->first('class') }}
        </span>
    @endif
</div>
@endif
@if(in_array('section', $visiable))
<div class="{{ $div . ' ' . $mt }}" id="select_section_div">
    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
        id="select_section" name="section_id">
        <option data-display="@lang('common.select_section') {{ in_array('section', $required) ? '*' : '' }}" value="">
            @lang('common.select_section') {{ in_array('section', $required) ? '*' : '' }}</option>
        @isset($sections)
            @foreach ($sections as $section)
                <option value="{{ $section->id }}"
                    {{ isset($section_id) ? ($section_id == $section->section_id ? 'selected' : '') : '' }}>
                    {{ $section->sectionName->section_name }}
                </option>
            @endforeach
        @endisset
    </select>
    <div class="pull-right loader" id="select_section_loader" style="margin-top: -30px;padding-right: 21px;">
        <img src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="" style="width: 28px;height:28px;">
    </div>
    @if ($errors->has('section'))
        <span class="text-danger invalid-select" role="alert">
            {{ $errors->first('section') }}
        </span>
    @endif
</div>
@endif

@if(in_array('subject', $visiable))
<div class="{{ $div . ' ' . $mt }}" id="select_subject_div">
    <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject"
        id="select_subject" name="subject_id">
        <option data-display="@lang('common.select_subjects') {{ in_array('subject', $required) ? ' *' : '' }}" value="">
            @lang('common.select_subjects') {{ in_array('subject', $required) ? ' *' : '' }}</option>
        @isset($subjects)
            @foreach ($subjects as $subject)
                <option value="{{ $subject->subject_id }}"
                    {{ isset($subject_id) ? ($subject_id == $subject->subject_id ? 'selected' : '') : '' }}>
                    {{ $subject->subject->subject_name }}</option>
            @endforeach
        @endisset
    </select>
    <div class="pull-right loader" id="select_subject_loader" style="margin-top: -30px;padding-right: 21px;">
        <img src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="" style="width: 28px;height:28px;">
    </div>
    @if ($errors->has('subject'))
        <span class="text-danger invalid-select" role="alert">
            {{ $errors->first('subject') }}
        </span>
    @endif
</div>
@endif