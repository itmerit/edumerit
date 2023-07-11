<style>
    .input-right-icon button {
        position: absolute;
        top: 50%;
        right: 15px;
        display: inline-block;
        transform: translateY(-50%);
    }

    .input-right-icon button i {
        position: unset;
    }
</style>
{{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'admission_query_update', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'admission-query-store']) }}
<input type="hidden" name="id" value="{{ @$admission_query->id }}">
<div class="" id="editAdmissionQuery">
    <div class="container-fluid">
        <form action="">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.name') <span
                                        class="text-danger"> *</span></label>
                                <input class="primary_input_field read-only-input form-control" type="text"
                                    name="name"
                                    id="name" value="{{ @$admission_query->name }}" required>

                                <span class="text-danger" id="nameError">
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.phone')</label>
                                <input oninput="phoneCheck(this)"
                                    class="primary_input_field read-only-input form-control" type="text"
                                    name="phone"
                                    id="phone" value="{{ @$admission_query->phone }}">

                                </span>


                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.email')</label>
                                <input oninput="emailCheck(this)"
                                    class="primary_input_field read-only-input form-control" type="text"
                                    name="email"
                                    value="{{ @$admission_query->email }}">


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-25">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.address') <span></span>
                                </label>
                                <textarea class="primary_input_field form-control has-content" cols="0" rows="3" name="address"
                                    id="address">{{ @$admission_query->address }}</textarea>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('common.description') <span></span>
                                </label>
                                <textarea class="primary_input_field form-control has-content" cols="0" rows="3" name="description"
                                    id="description">{{ @$admission_query->description }}</textarea>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-25">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('common.date')</label>
                                        <div class="position-relative">
                                            <input
                                                class="primary_input_field  primary_input_field date form-control form-control has-content"
                                                id="startDate" type="text"
                                                name="date" readonly="true"
                                                value="{{ @$admission_query->date != '' ? date('m/d/Y', strtotime(@$admission_query->date)) : date('m/d/Y') }}">
                                            <button class="btn-date" data-id="#date_from" type="button">
                                                <label class="m-0 p-0" for="startDate">
                                                    <i class="ti-calendar" id="start-date-icon"></i>
                                                </label>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('academics.next_follow_up_date')</label>
                                        <div class="position-relative">
                                            <input
                                                class="primary_input_field  primary_input_field date form-control form-control has-content"
                                                id="endDate" type="text"
                                                name="next_follow_up_date" autocomplete="off" readonly="true"
                                                value="{{ @$admission_query->next_follow_up_date != '' ? date('m/d/Y', strtotime(@$admission_query->next_follow_up_date)) : date('m/d/Y') }}">
                                            <button class="btn-date" data-id="#date_from" type="button">
                                                <label class="m-0 p-0" for="endDate">
                                                    <i class="ti-calendar" id="end-date-icon"></i>
                                                </label>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('academics.assigned')
                                    <span></span></label>
                                <input class="primary_input_field read-only-input form-control" type="text"
                                    name="assigned"
                                    value="{{ @$admission_query->assigned }}" id="assigned" required>

                                <span class="text-danger" id="assignedError"> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-25">
                    <div class="row">
                        <div class="col-lg-3">
                            <label class="primary_input_label" for="">@lang('academics.reference') <span></span></label>
                            <select class="primary_select " name="reference" id="reference" required>
                                <option data-display="@lang('academics.reference')" value="">@lang('academics.reference')</option>
                                @foreach ($references as $reference)
                                    <option value="{{ @$reference->id }}"
                                        {{ @$reference->id == @$admission_query->reference ? 'selected' : '' }}>
                                        {{ @$reference->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="referenceError"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="primary_input_label" for="">@lang('admin.source')
                                *<span></span></label>
                            <select class="primary_select " name="source" id="source" required>
                                <option data-display="@lang('academics.source') *" value="">@lang('academics.source') *
                                </option>
                                @foreach ($sources as $source)
                                    <option value="{{ @$source->id }}"
                                        {{ @$source->id == @$admission_query->source ? 'selected' : '' }}>
                                        {{ @$source->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="sourceError">
                            </span>
                        </div>
                        @if (moduleStatusCheck('University') == false)
                            <div class="col-lg-3">
                                <label class="primary_input_label" for="">@lang('common.class')
                                    *<span></span></label>
                                <select class="primary_select " name="class" id="class" id="class"
                                    required>

                                    <option data-display="@lang('common.class')" value="">@lang('common.class')
                                    </option>
                                    @foreach ($classes as $class)
                                        <option value="{{ @$class->id }}"
                                            {{ @$class->id == @$admission_query->class ? 'selected' : '' }}>
                                            {{ @$class->class_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="classError"></span>
                            </div>
                        @endif
                        <div class="col-lg-3">
                            <div class="primary_input">
                                <label class="primary_input_label" for="">@lang('academics.number_of_child')
                                    <span></span></label>
                                <input oninput="numberCheck(this)"
                                    class="primary_input_field form-control has-content" type="text"
                                    name="no_of_child"
                                    value="{{ @$admission_query->no_of_child }}" id="no_of_child" required>

                                <span class="text-danger" id="no_of_childError"></span>
                            </div>
                        </div>
                    </div>
                </div>
                @if (moduleStatusCheck('University'))
                    <div class="col-lg-12 mt-25">
                        <div class="row">
                            @if (moduleStatusCheck('University'))
                                @includeIf(
                                    'university::common.session_faculty_depart_academic_semester_level',
                                    [
                                        'div' => 'col-lg-4',
                                        'niceSelect' => 'niceSelect1',
                                        'rowMt' => 'mt-25',
                                        'hide' => ['USUB'],
                                        'required' => ['USN', 'UF', 'UD', 'UA', 'US', 'USL'],
                                    ]
                                )
                                <input type="hidden" name="un_academic_id" id="select_academic"
                                    value="{{ getAcademicId() }}">
                            @endif
                        </div>
                    </div>
                @endif
                <div class="col-lg-12 text-center mt-40">
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.cancel')</button>
                        <button class="primary-btn fix-gr-bg submit" id="update_button_query"
                            type="submit">@lang('common.update')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
{{ Form::close() }}

@include('backEnd.partials.date_picker_css_js')

<script>
    $('.input-right-icon button').on("click", function(){
        $(this).parent().find("input").focus();
    });

    $("#search-icon").on("click", function() {
        $("#search").focus();
    });

    $("#start-date-icon").on("click", function() {
        $("#startDate").focus();
    });

    $("#end-date-icon").on("click", function() {
        $("#endDate").focus();
    });

    $(".primary_input_field.date").datepicker({
        autoclose: true,
        setDate: new Date(),
    });
    $(".primary_input_field.date").on("changeDate", function(ev) {
        // $(this).datepicker('hide');
        $(this).focus();
    });

    $(".primary_input_field.time").datetimepicker({
        format: "LT",
    });

    if ($(".niceSelect1").length) {
        $(".niceSelect1").niceSelect();
    }

    $(".primary_select").niceSelect('destroy');
    $(".primary_select").niceSelect();
</script>


<!-- End Sibling Add Modal -->
