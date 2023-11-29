
@php
    $divButton = generalSetting()->multiple_roll == 1 ? 'col-3' : 'col-4';
@endphp
@foreach ($student->studentRecords as $record)
<div class="row mb-4 align-items-end" id="div_id_{{ $record->student_id.$record->id }}">
    <div class="{{ $divButton }}">
        <div class="primary_input">
            <select class="primary_select  classSelectClass class_{{ $record->student_id }} form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                name="old_record[{{ $record->id }}][class][]">
                <option data-display="@lang('common.class') *" value="">
                    @lang('common.class') *</option>
                   @isset($classes)
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ $record->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                   @endisset
            </select>
            <div class="pull-right loader loader_style select_class_loader">
                <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
            </div>
            
            @if ($errors->has('class'))
                <span class="text-danger invalid-select" role="alert">
                    {{ $errors->first('class') }}
                </span>
            @endif
        </div>
    </div>
    <div class="{{ $divButton }}">
        <div class="primary_input">
            <select class="primary_select  classSelectSection form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                name="old_record[{{ $record->id }}][section][]" id="sectionSelectStudent">
                <option data-display="@lang('common.section') *" value="">
                    @lang('common.section') *</option>
                    @isset($record)
                        @if ($record->session_id && $record->class_id)
                            @foreach ($record->class->classSection as $section)
                                <option value="{{ $section->sectionName->id }}"
                                    {{ $record->section_id == $section->sectionName->id ? 'selected' : '' }}>
                                    {{ $section->sectionName->section_name }}</option>
                            @endforeach
                        @endif
                    @endisset
            </select>
            <div class="pull-right loader loader_style select_section_loader">
                <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
            </div>
            
            @if ($errors->has('section'))
                <span class="text-danger invalid-select" role="alert">
                    {{ $errors->first('section') }}
                </span>
            @endif
        </div>
    </div>
    <div class="col-3">
        <input type="checkbox" id="is_default_{{@$record->id}}" data-student_id="{{ $record->student_id }}"  data-row_id="{{ $record->id }}" class="common-checkbox is_default is_default_{{@$record->student_id}} form-control{{ @$errors->has('is_default') ? ' is-invalid' : '' }}" {{ $record->is_default ? 'checked':'' }}>
        <label class="mb-0" for="is_default_{{@$record->id}}">@lang('common.default')</label>

    </div>
    @if (generalSetting()->multiple_roll == 1)
        <div class="col-2">
            <div class="primary_input">
                <input oninput="numberCheck(this)" class="primary_input_field" type="text" id="roll_number" placeholder="{{ moduleStatusCheck('Lead') == true ? __('lead::lead.id_number') : __('student.roll') }}{{ is_required('roll_number') == true ? ' *' : '' }}"
                    name="old_record[{{ $record->id }}][roll_number][]" value="{{ old('roll_number') }}">
                
                <span class="text-danger" id="roll-error" role="alert">
                    <strong></strong>
                </span>
                @if ($errors->has('roll_number'))
                    <span class="text-danger" >
                        {{ $errors->first('roll_number') }}
                    </span>
                @endif
            </div>
        </div>
    @endif
    <div class="col-1 text-left">
        <button class="primary-btn small fix-gr-bg icon-only removrButton" type="button" data-student_id="{{ $record->student_id }}" data-record_id={{ $record->id }}><i class="ti-trash"></i></button>
    </div>
</div>
@endforeach
<div id="appendDiv_{{ $student->id }}">

</div>
