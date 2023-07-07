@php
$div = isset($div) ? $div : 'col-lg-4';
$mt = isset($mt) ? $mt : 'mt-30-md';
$subject = isset($subject) ? true : false;
$required = $required ?? [];
$selected = isset($selected) ? $selected : null;
$academic_year = $selected && isset($selected['academic_year']) ? $selected['academic_year'] : null;
$class_id = $selected && isset($selected['class_id']) ? $selected['class_id'] : null;
$section_id = $selected && isset($selected['section_id']) ? $selected['section_id'] : null;
$subject_id = $selected && isset($selected['subject_id']) ? $selected['subject_id'] : null;
$student_id = $selected && isset($selected['student_id']) ? $selected['student_id'] : null;

if ($academic_year) {
$classes = classes($academic_year) ?? null;
$sections = $class_id ? sections($class_id, $academic_year) : null;
$subjects = $class_id && $section_id ? subjects($class_id, $section_id, $academic_year) : null;
$students = $class_id && $section_id ? students($class_id, $section_id, $academic_year) : null;
} else {
$sections = $class_id ? sections($class_id) : null;
$subjects = $class_id && $section_id ? subjects($class_id, $section_id) : null;
}
$visiable = $visiable ?? [];

@endphp
@if (in_array('academic', $visiable))
<div class="{{ $div . ' ' . $mt }}">
    <div class="primary_input ">
        <label class="primary_input_label" for="">{{ __('common.academic_year') }}
            <span class="text-danger">{{ in_array('academic', $required) ? '*' : '' }}</span>
        </label>
        <select
            class="primary_select  form-control{{ $errors->has('academic_year') ? ' is-invalid' : '' }} common_academic_year"
            name="academic_year" id="common_academic_year">
            <option data-display="@lang('common.academic_year') {{ in_array('academic', $required) ? '*' : '' }}"
                value="">
                @lang('common.academic_year') {{ in_array('academic', $required) ? '*' : '' }}
            </option>
            @isset($sessions)
            @foreach ($sessions as $session)
            <option value="{{ $session->id }}"
                {{ isset($academic_year) && $academic_year == $session->id ? 'selected' : (getAcademicId() == $session->id ? 'selected' : '') }}>
                {{ $session->year }}[{{ $session->title }}]</option>
            @endforeach
            @endisset

        </select>

        @if ($errors->has('academic_year'))
        <span class="text-danger" role="alert">
            {{ $errors->first('academic_year') }}
        </span>
        @endif
    </div>
</div>
@endif
@if (in_array('class', $visiable))
<div class="{{ $div . ' ' . $mt }}" id="common_select_class_div">
    <div class="primary_input mb-25">
        <label class="primary_input_label" for="">{{ __('common.class') }}
            <span class="text-danger">{{ in_array('class', $required) ? '*' : '' }}</span>
        </label>
        <select class="primary_select form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}" name="class_id"
            id="common_select_class">
            <option data-display="@lang('common.select_class') {{ in_array('class', $required) ? '*' : '' }}" value="">
                {{ __('common.select_class') }} {{ in_array('class', $required) ? '*' : '' }}</option>
            @if (isset($classes))
            @foreach ($classes as $class)
            <option value="{{ $class->id }}" {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                {{ $class->class_name }}</option>
            @endforeach
            @endif
        </select>
        <div class="pull-right loader loader_style" id="common_select_class_loader">
            <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
        </div>
        <span class="text-danger">{{ $errors->first('class_id') }}</span>
    </div>
</div>

@endif
@if (in_array('section', $visiable))
<div class="{{ $div . ' ' . $mt }}" id="common_select_section_div">
    <label class="primary_input_label" for="">{{ __('common.section') }}
        <span class="text-danger">{{ in_array('section', $required) ? '*' : '' }}</span>
    </label>
    <select class="primary_select form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }} select_section"
        id="common_select_section" name="section_id">
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
    <div class="pull-right loader loader_style" id="common_select_section_loader" style="margin-top: -30px;padding-right: 21px;">
        <img src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="" style="width: 28px;height:28px;">
    </div>


    @if ($errors->has('section_id'))
    <span class="text-danger">
        {{ $errors->first('section_id') }}
    </span>
    @endif
</div>
@endif
@if (in_array('subject', $visiable))
<div class="{{ $div . ' ' . $mt }}" id="common_select_subject_div">
    <label class="primary_input_label" for="">{{ __('common.subject') }}
        <span class="text-danger">{{ in_array('subject', $required) ? '*' : '' }}</span>
    </label>
    <select class="primary_select form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }} select_subject"
        id="common_select_subject" name="subject_id">
        <option data-display="@lang('common.select_subject') {{ in_array('subject', $required) ? ' *' : '' }}" value="">
            @lang('common.select_subject') {{ in_array('subject', $required) ? ' *' : '' }}</option>
        @isset($subjects)
        @foreach ($subjects as $subject)
        <option value="{{ $subject->subject_id }}"
            {{ isset($subject_id) ? ($subject_id == $subject->subject_id ? 'selected' : '') : '' }}>
            {{ $subject->subject->subject_name }}</option>
        @endforeach
        @endisset
    </select>
    <div class="pull-right loader loader_style" id="common_select_subject_loader" style="margin-top: -30px;padding-right: 21px;">
        <img src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="" style="width: 28px;height:28px;">
    </div>

    @if ($errors->has('subject_id'))
    <span class="text-danger">
        {{ $errors->first('subject_id') }}
    </span>
    @endif
</div>
@endif
@if (in_array('student', $visiable))
<div class="{{ $div . ' ' . $mt }}" id="common_select_student_div">
    <label class="primary_input_label" for="">{{ __('common.student') }}
        <span class="text-danger">{{ in_array('student', $required) ? '*' : '' }}</span>
    </label>
    <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}"
        id="common_select_student" name="student">
        <option data-display="@lang('reports.select_student') {{ in_array('student', $required) ? '*' : '' }}" value="">
            @lang('reports.select_student') <span>{{ in_array('student', $required) ? '*' : '' }}</span>
        </option>
        @isset($students)
        @foreach ($students as $student)
        <option value="{{ $student->id }}"
            {{ isset($student_id) ? ($student_id == $student->id ? 'selected' : '') : '' }}>
            {{ $student->full_name }}
        </option>
        @endforeach
        @endisset
    </select>

    <div class="pull-right loader loader_style" id="common_select_student_loader">
        <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
    </div>

    @if ($errors->has('student'))
    <span class="text-danger">
        {{ $errors->first('student') }}
    </span>
    @endif
</div>
@endif

@push('script')
<script>
    $(document).ready(function () {
        let class_required = "{{ in_array('class', $required) ? ' *' : '' }}";
        let section_required = "{{ in_array('section', $required) ? ' *' : '' }}";
        let subject_required = "{{ in_array('subject', $required) ? ' *' : '' }}";
        let student_required = "{{ in_array('student', $required) ? ' *' : '' }}";
        $("#common_academic_year").on(
            "change",
            function () {
                var url = $("#url").val();
                var i = 0;
                var formData = {
                    id: $(this).val(),
                };

                // get class
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "academic-year-get-class",

                    beforeSend: function () {
                        $('#common_select_class_loader').addClass('pre_loader').removeClass(
                            'loader');
                    },

                    success: function (data) {
                        $("#common_select_class").empty().append(
                            $("<option>", {
                                value: '',
                                text: window.jsLang('select_class') + class_required,
                            })
                        );

                        if (data[0].length) {
                            $.each(data[0], function (i, className) {
                                $("#common_select_class").append(
                                    $("<option>", {
                                        value: className.id,
                                        text: className.class_name,
                                    })
                                );
                            });
                        }
                        $('#common_select_class').niceSelect('update');
                        $('#common_select_class').trigger('change');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    },
                    complete: function () {
                        i--;
                        if (i <= 0) {
                            $('#common_select_class_loader').removeClass('pre_loader').addClass(
                                'loader');
                        }
                    }
                });
            }
        );

        $("#common_select_class").on("change", function () {

            var url = $("#url").val();
            var i = 0;
            var formData = {
                id: $(this).val(),
            };
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxStudentPromoteSection",

                beforeSend: function () {
                    $('#common_select_section_loader').addClass('pre_loader').removeClass(
                        'loader');
                },
                success: function (data) {
                    $("#common_select_section").empty().append(
                        $("<option>", {
                            value: '',
                            text: window.jsLang('select_section') +
                                section_required,
                        })
                    );

                    if (data[0].length) {
                        $.each(data[0], function (i, section) {
                            $("#common_select_section").append(
                                $("<option>", {
                                    value: section.id,
                                    text: section.section_name,
                                })
                            );
                        });
                    }
                    $('#common_select_section').niceSelect('update');
                    $('#common_select_section').trigger('change');

                },
                error: function (data) {
                    console.log("Error:", data);
                },
                complete: function () {
                    i--;
                    if (i <= 0) {
                        $('#common_select_section_loader').removeClass('pre_loader')
                            .addClass('loader');
                    }
                }
            });
        });
        $("#common_select_section").on("change", function () {
            var url = $("#url").val();
            var i = 0;
            var subject = "{{ $subject }}";
            var select_class = $("#common_select_class").val();
            var class_id = $("#common_select_class").val();
            var section_id = $(this).val();

            var formData = {
                section: $(this).val(),
                class: $("#common_select_class").val(),
            };
            // get section for student
            if(subject == false)
            {
            $.ajax({
                type: "GET",
                data: formData,
                dataType: "json",
                url: url + "/" + "ajaxSelectStudent",

                beforeSend: function () {
                    $('#common_select_student_loader').addClass('pre_loader').removeClass(
                        'loader');
                },

                success: function (data) {

                    $("#common_select_student").empty().append(
                        $("<option>", {
                            value: '',
                            text: window.jsLang('select_student') +
                                student_required,
                        })
                    );

                    if (data[0].length) {
                        $.each(data[0], function (i, student) {
                            $("#common_select_student").append(
                                $("<option>", {
                                    value: student.id,
                                    text: student.full_name,
                                })
                            );
                        });
                    }
                    $('#common_select_student').niceSelect('update');
                    $('#common_select_student').trigger('change');
                },
                error: function (data) {
                    console.log("Error:", data);
                },
                complete: function () {
                    i--;
                    if (i <= 0) {
                        $('#common_select_student_loader').removeClass('pre_loader')
                            .addClass('loader');
                    }
                }
            });
            }
            // get subject from section
            if(subject == true)
            {
                getSubject(class_id, section_id);
            }
        });

        function getSubject(class_id, section_id) {
            var url = $("#url").val();
            var i = 0;
            $.ajax({
                type: "GET",
                data: {class: class_id, section: section_id},
                dataType: "json",
                url: url + "/" + "ajaxSelectSubject",

                beforeSend: function () {
                    $('#common_select_student_loader').addClass('pre_loader').removeClass('loader');
                },

                success: function (data) {

                    $("#common_select_subject").empty().append(
                        $("<option>", {
                            value: '',
                            text: window.jsLang('select_subject'),
                        })
                    );

                    if (data[0].length) {
                        $.each(data[0], function (i, subject) {
                            $("#common_select_subject").append(
                                $("<option>", {
                                    value: subject.id,
                                    text: subject.subject_name,
                                })
                            );
                        });
                    }
                    $('#common_select_subject').niceSelect('update');
                    $('#common_select_subject').trigger('change');
                },
                error: function (data) {
                    console.log("Error:", data);
                },
                complete: function () {
                    i--;
                    if (i <= 0) {
                        $('#common_select_student_loader').removeClass('pre_loader').addClass(
                            'loader');
                    }
                }
            });
        }
    });
</script>
@endpush