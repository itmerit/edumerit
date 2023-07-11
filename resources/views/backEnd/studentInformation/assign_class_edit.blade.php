{{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student.record.update', 'method' => 'POST']) }}
<input type="hidden" name="student_id" value="{{ $student_detail->id }}">
<input type="hidden" name="record_id" value="{{ $record->id }}">
<div class="row">
    @php  $setting = app('school_info');   @endphp
    @if(moduleStatusCheck('University'))
    <div class="col-lg-12">


        @includeIf('university::common.session_faculty_depart_academic_semester_level',['div'=>'col-lg-12', 'row'=>1, 'niceSelect' => 'niceSelect1', 'mt'=> 'mt-0','required' => ['USN','UF', 'UD', 'UA', 'US', 'USL'], 'hide' => ['USUB']])
    </div>
    @else
    <div class="col-lg-12">
        <div class="primary_input" id="academic-div">
            <select class="primary_select form-control{{ $errors->has('session') ? ' is-invalid' : '' }}"
                name="session" id="edit_academic_year">
                <option data-display="@lang('common.academic_year') *" value="">
                    @lang('common.academic_year') *</option>
                @foreach ($sessions as $session)
                    <option value="{{ $session->id }}" {{ $record->session_id == $session->id ? 'selected' : '' }}>
                        {{ $session->year }}[{{ $session->title }}]</option>
                @endforeach
            </select>
            
            @if ($errors->has('session'))
                <span class="text-danger invalid-select" role="alert">
                    {{ $errors->first('session') }}
                </span>
            @endif
        </div>
    </div>
    <div class="col-lg-12 mt-25">
        <div class="primary_input" id="edit_class-div">
            <select class="primary_select form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                name="class" id="edit_classSelectStudent">
                <option data-display="@lang('common.class') *" value="">@lang('common.class') *</option>
                @foreach ($record->classes as $class)
                    <option value="{{ $class->id }}" {{ $record->class_id == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                @endforeach

            </select>
            <div class="pull-right loader loader_style" id="edit_select_class_loader">
                <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
            </div>
            
            @if ($errors->has('class'))
                <span class="text-danger invalid-select" role="alert">
                    {{ $errors->first('class') }}
                </span>
            @endif
        </div>
    </div>
    <div class="col-lg-12 mt-25">
        <div class="primary_input" id="edit_sectionStudentDiv">
            <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                name="section" id="edit_sectionSelectStudent">
                <option data-display="@lang('common.section') *" value="">@lang('common.section') *
                </option>
                @if ($record->session_id && $record->class_id)
                    @foreach ($record->class->classSection as $section)
                        <option value="{{ $section->sectionName->id }}"
                            {{ $record->section_id == $section->sectionName->id ? 'selected' : '' }}>
                            {{ $section->sectionName->section_name }}</option>
                    @endforeach
                @endif
            </select>
            <div class="pull-right loader loader_style" id="edit_select_section_loader">
                <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
            </div>
            
            @if ($errors->has('section'))
                <span class="text-danger invalid-select" role="alert">
                    {{ $errors->first('section') }}
                </span>
            @endif
        </div>
    </div>
    @endif
    @if($setting->multiple_roll ==1)
    <div class="col-lg-12 mt-25">
        <div class="primary_input">
            <input oninput="numberCheck(this)" class="primary_input_field form-control has-content" type="text"
                id="roll_number" name="roll_number" value="{{ $record->roll_no }}">
            <label>
                {{ moduleStatusCheck('Lead') == true ? __('lead::lead.id_number') : __('student.roll') }}
                @if (is_required('roll_number') == true) <span class="text-danger"> *</span> @endif</label>
            
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

    <div class="col-lg-12 mt-25">
        <label for="is_default">@lang('student.is_default')</label>
        <div class="d-flex radio-btn-flex mt-10">
            
            <div class="mr-30">
                <input type="radio" name="is_default" id="isDefaultYesEdit" value="1" class="common-radio relationButton" {{ $record->is_default == 1 ? 'checked':'' }}>
                <label for="isDefaultYesEdit">@lang('common.yes')</label>
            </div>
            <div class="mr-30">
                <input type="radio" name="is_default" id="isDefaultNoEdit" value="0" class="common-radio relationButton" {{ $record->is_default == 0 ? 'checked':'' }}>
                <label for="isDefaultNoEdit">@lang('common.no')</label>
            </div>
            
        </div>
    </div>
    <div class="col-lg-12 text-center mt-25">
        <div class="mt-40 d-flex justify-content-between">
            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('admin.cancel')</button>
            <button class="primary-btn fix-gr-bg submit" id="save_button_query"
                type="submit">@lang('admin.save')</button>
        </div>
    </div>
</div>
{{ Form::close() }}

<script>
    $(".primary_select").niceSelect('destroy');
    $(".primary_select").niceSelect();
    // $(document).ready(function() {
        $(document).on("change",'#edit_academic_year',function() {
            // alert($(this).val());
                var url = $("#url").val();
                var i = 0;
                var formData = {
                    id: $(this).val(),
                };
                // get section for student
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "academic-year-get-class",

                    beforeSend: function() {
                        $('#edit_select_class_loader').addClass('pre_loader');
                        $('#edit_select_class_loader').removeClass('loader');
                    },

                    success: function(data) {
                        $("#edit_classSelectStudent").empty().append(
                            $("<option>", {
                                value:  '',
                                text: window.jsLang('select_class') + ' *',
                            })
                        );

                        if (data[0].length) {
                            $.each(data[0], function(i, className) {
                                $("#edit_classSelectStudent").append(
                                    $("<option>", {
                                        value: className.id,
                                        text: className.class_name,
                                    })
                                );
                            });
                        } 
                        $('#edit_classSelectStudent').niceSelect('update');
                        $('#edit_classSelectStudent').trigger('change');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    },
                    complete: function() {
                        i--;
                        if (i <= 0) {
                            $('#edit_select_class_loader').removeClass('pre_loader');
                            $('#edit_select_class_loader').addClass('loader');
                        }
                    }
                });
        });
        $(document).on("change","#edit_classSelectStudent", function() {
            var url = $("#url").val();
            var i = 0;

            var formData = {
                id: $(this).val(),
            };
            // get section for student
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSectionStudent",

                beforeSend: function() {
                    $('#edit_select_section_loader').addClass('pre_loader');
                    $('#edit_select_section_loader').removeClass('loader');
                },
                success: function(data) {

                    $("#edit_sectionSelectStudent").empty().append(
                        $("<option>", {
                            value:  '',
                            text: window.jsLang('select_section') + ' *',
                        })
                    );
                    $.each(data, function(i, item) {
                       
                        if (item.length) {
                            $.each(item, function(i, section) {
                                $("#edit_sectionSelectStudent").append(
                                    $("<option>", {
                                        value: section.id,
                                        text: section.section_name,
                                    })
                                );
                                
                            });
                        } 
                    });
                    $("#edit_sectionSelectStudent").trigger('change').niceSelect('update')
                    

                },
                error: function(data) {
                    console.log("Error:", data);
                },
                complete: function() {
                    i--;
                    if (i <= 0) {
                        $('#edit_select_section_loader').removeClass('pre_loader');
                        $('#edit_select_section_loader').addClass('loader');
                    }
                }
            });
        });
    
</script>
