@if (isset($custom_fields) && $custom_fields)
    <style>
        .browse {
            top: 18px;
            right: -5px;
        }

        .input-right-icon button {
            position: absolute;
            top: 70%;
            right: 15px;
            display: inline-block;
            transform: translateY(-50%);
        }

        .input-right-icon button i {
            position: unset;
        }

        .input-right-icon button.primary-btn-small-input {
            top: 57%;
            right: 15px;
        }
    </style>
    <div class="row">
        @foreach ($custom_fields as $key => $custom_field)
            @if ($custom_field->type == 'textInput')
                <div class="{{ $custom_field->width }} mt-30">
                    <div class="primary_input ">
                        <label>{{ $custom_field->label }} <span class="text-danger">{{ $custom_field->required == 1 ? '*' : '' }}</span>
                        </label>
                        <input
                            class="primary_input_field form-control{{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}"
                            type="text"
                            name="customF[{{ $custom_field->label }}]"
                            value="{{ isset($student) ? customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name) : '' }}">

                        @if ($errors->has($custom_field->label))
                            <span class="text-danger">
                                {{ $errors->first($custom_field->label) }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            @elseif($custom_field->type == 'numericInput')
                @php
                    $min_max_value = json_decode($custom_field->min_max_value);
                @endphp
                <div class="{{ $custom_field->width }} mt-30">
                    <div class="primary_input ">
                        <label>{{ $custom_field->label }} <span class="text-danger">{{ $custom_field->required == 1 ? '*' : '' }}</span>
                        </label>
                        <input
                            class="primary_input_field form-control{{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}"
                            type="number" min="{{ $min_max_value[0] }}" max="{{ $min_max_value[1] }}"
                            name="customF[{{ $custom_field->label }}]"
                            value="{{ isset($student) ? customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name) : '' }}">

                        @if ($errors->has($custom_field->label))
                            <span class="text-danger">
                                {{ $errors->first($custom_field->label) }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            @elseif($custom_field->type == 'multilineInput')
                @php
                    $min_max_length = json_decode($custom_field->min_max_length);
                @endphp
                <div class="{{ $custom_field->width }} mt-40">
                    <div class="primary_input ">
                        <label>{{ $custom_field->label }} <span class="text-danger">{{ $custom_field->required == 1 ? '*' : '' }}</span>
                        </label>
                        <textarea class="primary_input_field form-control" cols="0" rows="3"
                            name="customF[{{ $custom_field->label }}]">{{ isset($student) ? customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name) : '' }}</textarea>

                        @if ($errors->has($custom_field->label))
                            <span class="text-danger">
                                {{ $errors->first($custom_field->label) }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            @elseif($custom_field->type == 'datepickerInput')
                <div class="{{ $custom_field->width }} mt-30">
                    <div class="no-gutters input-right-icon">
                        <div class="col">
                            <div class="primary_input ">
                                <label>{{ $custom_field->label }}
                                    <span class="text-danger">{{ $custom_field->required == 1 ? '*' : '' }}</span></label>
                                <input
                                    class="primary_input_field date form-control{{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}"
                                    id="startDate" type="text"
                                    name="customF[{{ $custom_field->label }}]"
                                    value="{{ isset($student) ? customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name) : '' }}"
                                    autocomplete="off">
                                <button class="" type="button">
                                    <label class="m-0 p-0" for="startDate">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </label>
                                </button>

                                @if ($errors->has($custom_field->label))
                                    <span class="text-danger">
                                        {{ $errors->first($custom_field->label) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-auto">
                        </div> --}}
                    </div>
                </div>
            @elseif($custom_field->type == 'checkboxInput')
                @php
                    $checkbox_values = json_decode($custom_field->name_value);
                @endphp
                <div class="{{ $custom_field->width }} mt-30 d-flex align-items-center">
                    <label class="mr-5">{{ $custom_field->label }} <span class="text-danger">
                        {{ $custom_field->required == 1 ? '*' : '' }}</span>
                    </label>
                    @if ($checkbox_values)
                        @foreach ($checkbox_values as $key => $checkbox_value)
                            <div class="row no-gutters input-right-icon mr-3">
                                <div class="primary_input">
                                    <input type="checkbox"
                                        id="custom_types_{{ $key }}_{{ $custom_field->id }}"
                                        class="common-checkbox exam-checkbox"
                                        name="customF[{{ $custom_field->label }}][]"
                                        @if (isset($student)) @if (!is_null(customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name)))
                                                   @if (in_array($checkbox_value, customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name)))
                                                       checked @endif
                                        @endif
                        @endif
                        value="{{ $checkbox_value }}">
                        <label
                            for="custom_types_{{ $key }}_{{ $custom_field->id }}">{{ $checkbox_value }}</label>
                </div>
    </div>
@endforeach
@endif
</div>
@elseif($custom_field->type == 'radioInput')
@php
    $name_values = json_decode($custom_field->name_value);
@endphp
<div class="{{ $custom_field->width }} d-flex flex-wrap mt-30">
    <p class="text-uppercase fw-500 mb-10">{{ $custom_field->label }}
        <span class="text-danger">{{ $custom_field->required == 1 ? '*' : '' }}</span>
    </p>
    <div class="d-flex radio-btn-flex ml-40">
        @if ($name_values)
            @foreach ($name_values as $key => $name_value)
                <div class="mr-30">
                    <input type="radio" name="customF[{{ $custom_field->label }}]"
                        id="{{ $key }}_custField_{{ $custom_field->id }}"
                        @if (isset($student) &&
                                $name_value == customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name)) checked @endif
                        value="{{ $name_value }}"
                        class="common-radio relationButton">
                    <label for="{{ $key }}_custField_{{ $custom_field->id }}">{{ $name_value }}</label>
                </div>
            @endforeach
        @endif
    </div>
</div>
@elseif($custom_field->type == 'dropdownInput')
@php
    $dropdown_name_values = json_decode($custom_field->name_value);
@endphp
<div class="{{ $custom_field->width }} mt-30">
    <div class="primary_input ">
        <label>{{ $custom_field->label }} <span class="text-danger">{{ $custom_field->required == 1 ? '*' : '' }}</span></label>
        <select class="primary_select  form-control{{ $errors->has($custom_field->label) ? ' is-invalid' : '' }}"
            name="customF[{{ $custom_field->label }}]">
            <option
                data-display="{{ $custom_field->label }} @lang('common.select') {{ $custom_field->required == 1 ? '*' : '' }}"
                value="">{{ $custom_field->label }} @lang('common.select')
                {{ $custom_field->required == 1 ? '*' : '' }}
            </option>
            @if ($dropdown_name_values)
                @foreach ($dropdown_name_values as $dropdown_name_value)
                    <option value="{{ $dropdown_name_value }}"
                        {{ isset($student) ? (customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name) == $dropdown_name_value ? 'selected' : '') : '' }}>
                        {{ $dropdown_name_value }}</option>
                @endforeach
            @endif
        </select>

        @if ($errors->has($custom_field->label))
            <span class="text-danger invalid-select" role="alert">
                {{ $errors->first($custom_field->label) }}</strong>
            </span>
        @endif
    </div>
</div>
@elseif($custom_field->type == 'fileInput')
<div class="{{ $custom_field->width }} mt-30">
    <div class="row no-gutters input-right-icon">
        <div class="col">
            <div class="primary_input ">
                <label>{{ $custom_field->label }} <span class="text-danger">{{ $custom_field->required == 1 ? '*' : '' }}</span></label>
                <input class="primary_input_field" type="text" id="placeholderPhoto_{{ $key }}"
                    placeholder="{{ isset($student) ? (!showFileName(customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name)) ? $custom_field->label : showFileName(customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name))) : $custom_field->label }} {{ (isset($student) ? '' : $custom_field->required == 1) ? '*' : '' }}"
                    readonly="">

                @if ($errors->has($custom_field->label))
                    <span class="text-danger d-block">
                        <strong>{{ @$errors->first($custom_field->label) }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-auto">
            <button class="primary-btn-small-input" type="button">
                <label class="primary-btn small fix-gr-bg browse"
                    for="photo_{{ $key }}">@lang('common.browse')</label>
                <input type="file" id="photo_{{ $key }}" data-id="#placeholderPhoto_{{ $key }}"
                    class="d-none cutom-photo"
                    value="{{ isset($student) ? customFieldValue($student->id, $custom_field->label, $student->custom_field_form_name) : '' }}"
                    name="customF[{{ $custom_field->label }}]"
                    @if (isset($student)) {{ ' ' }}
                                        @else
                                    @if ($custom_field->required == 1)
                                        {{ 'required' }} @endif
                    @endif
                >
            </button>
        </div>
    </div>
</div>
@endif
@endforeach
</div>
@endif

@include('backEnd.partials.date_picker_css_js')
