
<div class="container-fluid">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'add-exam-routine-store',
    'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'myForm', 'onsubmit' => "return validateAddNewExamRoutine()"]) }}
    <div class="row">
            <div class="col-lg-12">
                

                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                <input type="hidden" name="exam_period_id" id="exam_period_id" value="{{ @$exam_period_id}}">
                <input type="hidden" name="class_id" id="class_id" value="{{ @$class_id}}">
                <input type="hidden" name="section_id" id="section_id" value="{{ @$section_id}}">
                <input type="hidden" name="exam_term_id" id="exam_term_id" value="{{ @$exam_term_id}}">
                <input type="hidden" name="subject_id" id="subject_id" value="{{ @$subject_id}}">
                <input type="hidden" name="section_id_all" id="section_id_all" value="{{ @$section_id_all}}">
                


                <input type="hidden" name="date_error_count" id="date_error_count" value="">

                <input type="hidden" name="assigned_id" id="assigned_id" value="{{isset($assigned_exam)? $assigned_exam->id:''}}">


                <span class="text-success" role="alert" id="holiday_message">
                </span>

                <div class="row no-gutters input-right-icon mt-35">
                    <div class="col">
                        <div class="primary_input">
                            <input class="primary_input_field  primary_input_field date form-control read-only-input has-content" id="startDate" type="text" name="date" onkeyup="examRoutineCheck()" value="{{isset($assigned_exam)? date('m/d/Y', strtotime($assigned_exam->date)):date('m/d/Y')}}" readonly="true">
                                <label class="primary_input_label" for="">@lang('common.date')</label>
                            
                            <span class="text-danger"  id="date_error">
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="" type="button">
                            <i class="ti-calendar" id="start-date-icon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="row mt-25">
                    <div class="col-lg-12 mt-30-md">
                        <select class="primary_select1 form-control" name="room" id="room">
                            <option data-display="@lang('common.select_room') *" value="">@lang('common.select_room') *</option>
                            @foreach($rooms as $room)
                                <option value="{{ @$room->id}}" {{isset($assigned_exam)? ($assigned_exam->room_id == $room->id? 'selected':''):''}}>{{ $room->room_no}} ({{$room->capacity}})</option>
                            @endforeach
                        </select>
                        <span class="text-danger"  id="room_error">
                        </span>
                    </div>
                </div>
            </div>


            <!-- <div class="col-lg-12 text-center mt-40">
                <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                    <span class="ti-check"></span>
                    save information
                </button>
            </div> -->
            <div class="col-lg-12 text-center mt-40">
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>

                    <button class="primary-btn fix-gr-bg submit" type="submit">@lang('common.save_information')</button>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>

@include('backEnd.partials.date_picker_css_js')
<script>
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
</script>