<div class="row">
    <div class="col-lg-4">
        <div class="primary_input">
            <label class="primary_input_label" for="">@lang('student.label')<span class="text-danger"> *</span></label>
            <input class="primary_input_field form-control{{ $errors->has('label') ? ' is-invalid' : '' }}" type="text" name="label" autocomplete="off"
                    value="{{isset($v_custom_field)? $v_custom_field->label: old('label')}}">
            
            
            @if ($errors)
            
                <span class="text-danger">
                {{ $errors->first('label') }}
            </span>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <label class="primary_input_label" for="">@lang('common.type')<span class="text-danger"> *</span></label>
        <select class="primary_select form-control {{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" id="inputType">
            <option data-display="@lang('common.type') *" value="">@lang('common.select_type') *</option>
            <option value="textInput" {{isset($v_custom_field)? ($v_custom_field->type =="textInput" ?'selected':''): (old('type') == 'textInput' ? 'selected' : '') }}>@lang('student.text_input')</option>
            <option value="numericInput" {{isset($v_custom_field)? ($v_custom_field->type =="numericInput"?'selected':''): (old('type') == 'numericInput' ? 'selected' : '')}}>@lang('student.numeric_input')</option>
            <option value="multilineInput" {{isset($v_custom_field)? ($v_custom_field->type =="multilineInput"?'selected':''): (old('type') == 'multilineInput' ? 'selected' : '')}}>@lang('student.multiline_input')</option>
            <option value="datepickerInput" {{isset($v_custom_field)? ($v_custom_field->type =="datepickerInput"?'selected':''): (old('type') == 'datepickerInput' ? 'selected' : '')}}>@lang('student.datepicker_input')</option>
            <option value="checkboxInput" {{isset($v_custom_field)? ($v_custom_field->type =="checkboxInput"?'selected':''): (old('type') == 'checkboxInput' ? 'selected' : '')}}>@lang('student.checkbox_input')</option>
            <option value="radioInput" {{isset($v_custom_field)? ($v_custom_field->type =="radioInput"?'selected':''): (old('type') == 'radioInput' ? 'selected' : '')}}>@lang('student.radio_input')</option>
            <option value="dropdownInput" {{isset($v_custom_field)? ($v_custom_field->type =="dropdownInput"?'selected':''):(old('type') == 'dropdownInput' ? 'selected' : '')}}>@lang('student.dropdown_input')</option>
            <option value="fileInput" {{isset($v_custom_field)? ($v_custom_field->type =="fileInput"?'selected':''):(old('type') == 'fileInput' ? 'selected' : '')}}>@lang('student.file_input')</option>
        </select>
        @if($errors->has('type'))
            <span class="text-danger invalid-select" role="alert">
                {{ $errors->first('type') }}
            </span>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="primary_input sm_mb_20 mt-30" style="position: relative; top: 12px;">
            <input type="checkbox" name="required" id="labalRequired" class="common-checkbox permission-checkAll" value="1"
            {{isset($v_custom_field)? ($v_custom_field->required == 1?'checked':''):(old('required') ? 'checked' : '')}}>
            <label for="labalRequired">@lang('student.required')</label>
        </div>
        @php
           $type = isset($type) ? $type : null;
        @endphp
        @if(moduleStatusCheck('ParentRegistration')== TRUE && $type == 'student_online_regi'){{-- added for online student registration custom field showing --abunayem --}}
            <div class="primary_input sm_mb_20 mt-30 pt-10">
                <input type="checkbox" name="is_showing_online_registration" id="is_showing_online_registration" class="common-checkbox " value="1"
                {{isset($v_custom_field)? ($v_custom_field->is_showing == 1?'checked':''):(old('is_showing_online_registration') ? 'checked' : '')}}>
                <label for="is_showing_online_registration">@lang('student.available_for_online_registration')</label>
            </div>
        @endif
    </div>
</div>

@php
    if(isset($v_custom_field)){
        $v_lengths = json_decode($v_custom_field->min_max_length);
        $v_values = json_decode($v_custom_field->min_max_value);
    }
@endphp
<div class="row">
    <div class="col-xl-8">
        <div class="row mt-30 text_input d-none">
            <div class="col-lg-6">
                <div class="primary_input sm_mb_20 ">
                    <label class="primary_input_label" for="">@lang('student.min_length')</label>
                    <input class="primary_input_field" type="text" name="min_max_length[]" value="{{isset($v_custom_field)? $v_lengths[0]:(old('min_max_length') ? old('min_max_length')[0] : '')}}">
                  
                    
                </div>
            </div>
            <div class="col-lg-6">
                <div class="primary_input sm_mb_20 ">
                    <label class="primary_input_label" for="">@lang('student.max_length')</label>
                    <input class="primary_input_field" type="text" name="min_max_length[]" value="{{isset($v_custom_field)? $v_lengths[1]:(old('min_max_length') ? old('min_max_length')[1] : '')}}">
                  
                    
                </div>
            </div>
        </div>

        <div class="row mt-30 numeric_input d-none">
            <div class="col-lg-6">
                <div class="primary_input sm_mb_20 ">
                    <label class="primary_input_label" for="">@lang('student.min_value')</label>
                    <input class="primary_input_field" type="text" name="min_max_value[]" value="{{isset($v_custom_field)? $v_values[0]:(old('min_max_value') ? old('min_max_value')[0] : '')}}">
                    
                    
                </div>
            </div>
            <div class="col-lg-6">
                <div class="primary_input sm_mb_20 ">
                    <label class="primary_input_label" for="">@lang('student.max_value')</label>
                    <input class="primary_input_field" type="text" name="min_max_value[]" value="{{isset($v_custom_field)? $v_values[1]:(old('min_max_value') ? old('min_max_value')[1] : '')}}">
                 
                    
                </div>
            </div>
        </div>

        <div class="row mt-30 checkbox_input d-none">
            <div class="col-lg-8 mt-20 text-right">
                <button type="button" class="primary-btn small fix-gr-bg" onclick="customFieldAddRow();" id="customFieldaddRowBtn">
                    <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                </button>
            </div>
        </div>

        @php
            if(isset($v_custom_field)){
                $v_name_values= json_decode($v_custom_field->name_value);
            }
        @endphp
        <input type="hidden" value="@lang('student.value')" id="rowLang" >
        @if (isset($v_custom_field))
            @if ($v_custom_field->type == "checkboxInput" || $v_custom_field->type == "radioInput" || $v_custom_field->type == "dropdownInput")
                @foreach ($v_name_values as $v_name_value)
                    <div class="row mt-30 static d-none">
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <label>{{isset($v_custom_field) ? $v_name_value : trans('student.value')}}</label>
                                <input class="primary_input_field form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" type="text" name="name_value[]" autocomplete="off"
                                value='{{isset($v_custom_field)? $v_name_value:''}}'>
                            
                                
                            </div>
                            @if ($errors->has('value'))
                                <span class="text-danger" >
                                {{ $errors->first('value') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <button class="primary-btn icon-only fix-gr-bg" type="button" id="deleteCustRow" {{isset($v_custom_field)? '':'disabled'}} >
                                <span class="ti-trash"></span>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
        <div id="addCustRow"></div>
    </div>
    <div class="col-xl-4 mt-30 widthDropdown d-none">
        <label class="primary_input_label" for="">@lang('student.width')<span class="text-danger"> *</span></label>
        <select class="primary_select form-control {{ $errors->has('width') ? ' is-invalid' : '' }}" name="width">
            <option data-display="@lang('student.width') *" value="">@lang('common.select_width') *</option>
            <option value="col-12" {{isset($v_custom_field)? ($v_custom_field->width =="col-12"?'selected':''):(old('width') == 'col-12' ? 'selected' : '')}}>@lang('student.full_width')</option>
            <option value="col-6" {{isset($v_custom_field)? ($v_custom_field->width =="col-6"?'selected':''):(old('width') == 'col-6' ? 'selected' : '')}}>@lang('student.half_width')</option>
            <option value="col-3" {{isset($v_custom_field)? ($v_custom_field->width =="col-3"?'selected':''):(old('width') == 'col-3' ? 'selected' : '')}}>@lang('student.one_thired_width')</option>
            <option value="col-4" {{isset($v_custom_field)? ($v_custom_field->width =="col-4"?'selected':''):(old('width') == 'col-4' ? 'selected' : '')}}>@lang('student.one_fourth_width')</option>
        </select>
        @if($errors->has('width'))
            <span class="text-danger invalid-select" role="alert">
                {{ $errors->first('width') }}
            </span>
        @endif
    </div>
</div>
@if(userPermission("store-student-registration-custom-field") || userPermission("store-staff-registration-custom-field"))
    <div class="col-lg-12 mt-20 text-right">
        <button type="submit" class="primary-btn small fix-gr-bg">
            <span class="ti-save pr-2"></span>
            {{isset($v_custom_field)?trans('common.update'):trans('student.save')}}
        </button>
    </div>
@endif

<script type="text/javascript">
    $( document ).ready(function() {
        let inputType= $('#inputType').val();
        if(inputType == "checkboxInput" || inputType == "radioInput" ||inputType == "dropdownInput")
        {
            $('.static').removeClass('d-none');
            $('.checkbox_input').removeClass('d-none');
        }
        
        showHideFields(inputType);

        $(document).on("change","#inputType", function(event)
        {
            let inputType = $(this).val();
            showHideFields(inputType);
        });

        $(document).on("click","#customFieldaddRowBtn", function(event)
        {
            $('.addRow').removeClass('d-none');
        });
    });

    function showHideFields(inputType){
        if(inputType == ''){
            $('.widthDropdown').addClass('d-none');
            $('.text_input').addClass('d-none');
            $('.numeric_input').addClass('d-none');
            $('.addRow').addClass('d-none');
            $('.static').addClass('d-none');
            $('.checkbox_input').addClass('d-none');
            $('.addRow').addClass('d-none');
        }else{
            if(inputType == "textInput" || inputType == "multilineInput")
            {
                $('.text_input').removeClass('d-none');
                $('.addRow').addClass('d-none');
                $('.static').addClass('d-none');
            }else{
                $('.text_input').addClass('d-none');
            }
            if(inputType == "numericInput")
            {
                $('.numeric_input').removeClass('d-none');
                $('.addRow').addClass('d-none');
                $('.static').addClass('d-none');
            }else{
                $('.numeric_input').addClass('d-none');
            }
            if(inputType == "datepickerInput")
            {
                $('.static').addClass('d-none');
                $('.addRow').addClass('d-none');
            }
            if(inputType == "checkboxInput" || inputType == "radioInput" ||inputType == "dropdownInput")
            {
                $('.static').removeClass('d-none');
                $('.checkbox_input').removeClass('d-none');
            }else{
                $('.checkbox_input').addClass('d-none');
            }
            if(inputType == "fileInput")
            {
                $('.static').addClass('d-none');
                $('.addRow').addClass('d-none');
            }
            $('.widthDropdown').removeClass('d-none');
        }
    }
    
    customFieldAddRow = () => 
        {
            var divLength = $(".addRow").length;
            var rowLang = $("#rowLang").val();
            var count = divLength + 1;
            var newRow = `<div class="row mt-30 addRow d-none">
                            <div class="col-lg-6">
                                <div class="primary_input">
                                    <label>${rowLang} ${count}<span class="text-danger"> *</span></label>
                                    <input class="primary_input_field form-control has-content" type="text" name="name_value[]" autocomplete="off"">
                                    
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button class="primary-btn icon-only fix-gr-bg mt-35" type="button" id="deleteCustRow">
                                    <span class="ti-trash"></span>
                                </button>
                            </div>
                        </div>`;
            $("#addCustRow").append(newRow);
    };
    
    $(document).on('click', '#deleteCustRow', function() 
    {
        $(this).parent().parent().remove();
    });
</script>