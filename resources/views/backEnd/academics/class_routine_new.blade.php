@extends('backEnd.master')
@section('title')
    @lang('academics.class_routine_create')
@endsection
<style>
    .nice-select.bb .current {
        bottom: 10px;
    }

    .dloader_img_style {
        width: 40px;
        height: 40px;
    }

    .dloader {
        display: none;
    }

    .pre_dloader {
        display: block;
    }
</style>
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('academics.class_routine_create')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('academics.academics')</a>
                    <a href="#">@lang('academics.class_routine_create')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'class_routine_new', 'method' => 'get', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">

                            <div class="col-lg-6">
                                <label class="primary_input_label" for="">
                                    {{ __('common.class') }}
                                    <span class="text-danger"> *</span>
                                </label>
                                <select class="primary_select form-control{{ @$errors->has('class') ? ' is-invalid' : '' }}"
                                    id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">
                                        @lang('common.select_class') *</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ @$class->id }}"
                                            {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                                            {{ @$class->class_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-6 mt-30-md" id="select_section_div">
                                <label class="primary_input_label" for="">
                                    {{ __('common.section') }}
                                    <span class="text-danger"> *</span>
                                </label>
                                <select
                                    class="primary_select form-control{{ @$errors->has('section') ? ' is-invalid' : '' }}"
                                    id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">
                                        @lang('common.select_section') *</option>
                                    @isset($smClass)
                                        @foreach ($smClass->classSection as $item)
                                            <option value="{{ $item->section_id }}"
                                                {{ isset($section_id) ? ($section_id == $item->section_id ? 'selected' : '') : '' }}>
                                                {{ $item->sectionName->section_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                        alt="loader">
                                </div>
                                @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section') }}
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
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (isset($sm_weekends))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('academics.class_routine_create')</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 pull-right">
                        <a href="{{ route('classRoutinePrint', [$class_id, $section_id]) }}"
                            class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>
                            @lang('academics.print')</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 student-details up_admin_visitor">
                        <ul class="nav nav-tabs tabs_scroll_nav" role="tablist" id="tabs_ul">
                            <input type="hidden" name="routine_class_id" id="routine_class_id"
                                value="{{ $class_id }}">
                            <input type="hidden" name="routine_section_id" id="routine_section_id"
                                value="{{ $section_id }}">
                            @foreach ($sm_weekends as $sm_weekend)
                                <li class="nav-item">
                                    <a class="nav-link  {{ Session::get('session_day_id') != null ? (Session::get('session_day_id') == $sm_weekend->id ? 'active' : '') : ($loop->index == 0 ? 'active' : '') }} tab_link "
                                        href="{{ $sm_weekend->name }}" data-sm_weekend_id="{{ $sm_weekend->id }}"
                                        role="tab" data-toggle="tab">{{ @$sm_weekend->name }}</a>
                                </li>
                            @endforeach

                            <li class="nav-item edit-button">
                                @if (userPermission('add-new-class-routine-store'))
                                    <button class="primary-btn small fix-gr-bg" onclick="addRowInRoutine();" id="addRowBtn">
                                        <span class="ti-plus pr-2"></span> @lang('common.add')</button>
                                @endif

                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Start day wise routine  Tab -->

                            <div role="tabpanel" class="tab-pane fade show active">
                                {{-- <div class="white-box dloader" id=select_class_routine_loader>
                                    <div class="dloader_style mt-2 text-center">
                                        <img class="dloader_img_style"
                                            src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
                                    </div>
                                </div> --}}
                                <div id="show_routine">

                                </div>

                            </div>

                            <!-- End day wise routine Tab -->


                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <div class="modal fade" id="classRoutineDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">@lang('academics.delete_class_routine')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>

                        <button class="primary-btn fix-gr-bg"
                            id="classRoutineDeleteSubmitButton">@lang('common.delete')</button>

                    </div>

                </div>

            </div>
        </div>
    </div>


    @push('script')
        @if (isset($sm_weekends))
            <script>
                $(document).on('click', '.tab_link', function(e) {
                    e.preventDefault();
                    $('.tab_link').removeClass('active');
                    $(this).addClass('active');
                    let day_id = $(this).data('sm_weekend_id');

                    addDayData(day_id);
                });

                $(document).ready(function() {
                    addDayData($('.tab_link.active').data('sm_weekend_id'));
                })


                function addDayData(day_id) {
                    $('#show_routine').html('');
                    $('#select_class_routine_loader').removeClass('dloader').addClass('pre_dloader');

                    var url = $("#url").val();
                    var day_id = day_id;
                    var class_id = $('#routine_class_id').val();
                    var section_id = $('#routine_section_id').val();

                    var formData = {
                        day_id: day_id,
                        class_id: class_id,
                        section_id: section_id,
                    };

                    $.ajax({
                        type: "post",
                        data: formData,
                        dataType: "html",
                        url: url + "/" + "day-wise-class-routine",


                        success: function(data) {



                            $('#show_routine').html(data);
                            $('.primary_select').niceSelect('destroy');
                            $(".primary_select").niceSelect();

                            $(".primary_input_field.time").datetimepicker({
                                format: "LT",
                            });

                            $('#select_class_routine_loader').removeClass('pre_dloader').addClass('dloader');


                        },

                        error: function(data) {
                            $('#select_class_routine_loader').removeClass('pre_dloader').addClass('dloader');
                        }


                    });


                    //after fetch data by day id append to show_routine div
                }



                addRowInRoutine = () => {
                    $("#addRowBtn").button("loading");
                    var tableLength = $("#classRoutineTable tbody tr").length;
                    var url = $("#url").val();

                    let row_count = parseInt($('#row_count').val());
                    var tr = `
        <tr id="row_${row_count}" class="0">
            <td class="border-top-0"> 
                <div class="primary_input">
                    <select class="primary_select  form-control selectSubject" name="routine[${row_count}][subject]" id="subject_${row_count}" required>
                        <option data-display="@lang('common.select') @lang('academics.subject') *" value="">@lang('common.select') @lang('academics.subject') *</option>

                        @foreach ($subjects as $subject)
                                
                        <option value="{{ @$subject->subject_id }}">{{ @$subject->subject->subject_name }}</option>
                    
                    @endforeach
                        </select>
                        
                        @if ($errors->has('subject'))
                        <span class="text-danger invalid-select" role="alert">
                            {{ $errors->first('subject') }}
                        </span>
                        @endif
                    </div>
            </td>
                
            <td class="border-top-0"> 
                <div class="row " id="teacher-div">
                    <div class="col-lg-12 mt-30-md">
                        <select class="primary_select  form-control selectTeacher" data-teacher_row_id="${row_count}" name="routine[${row_count}][teacher_id]"  id="teacher_${row_count}">
                            <option data-display="@lang('common.select_teacher')" value="">@lang('common.select_teacher') </option>
                            
                                @foreach ($teachers as $teacher)                                
                                    <option value="{{ @$teacher->id }}">{{ @$teacher->full_name }}</option>
                                @endforeach
                            
                            
                        </select>
                        <div class="pull-right loader loader_style" id="select_teacher_loader">
                            <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
                        </div>
                        <span class="text-danger"  id="teacher_error"></span>
                    </div>
                </div>
            </td> 

            <td class="border-top-0">  
                <div class="row no-gutters input-right-icon">
                    <div class="col">
                        <div class="primary_input">
                            <input class="primary_input_field primary_input_field time start_time_required  form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }} selectStartTime" required type="text" data-start_time_row_id="${row_count}" name="routine[${row_count}][start_time]" id="start_time_${row_count}">
                       
                            
                            @if ($errors->has('start_time'))
                                <span class="text-danger" >
                                    <strong>{{ @$errors->first('start_time') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="" type="button">
                            <label class="m-0 p-0" for="start_time_${row_count}">
                                <i class="ti-timer"></i>
                            </label>
                        </button>
                    </div>
                </div> 
            
            </td>   

            <td class="border-top-0">   
                <div class="row no-gutters input-right-icon">
                    <div class="col">
                        <div class="primary_input">
                            <input class="primary_input_field primary_input_field time end_time_required  form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }} selectEndTime"  required type="text" name="routine[${row_count}][end_time]"  data-end_time_row_id="${row_count}" id="end_time_${row_count}">
                           
                            
                            @if ($errors->has('end_time'))
                            <span class="text-danger" >
                                {{ $errors->first('end_time') }}
                            </span>
                            <span class="text-danger start_time_error"></span> 
                        @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="" type="button">
                            <label class="m-0 p-0" for="end_time_${row_count}">
                                <i class="ti-timer"></i>
                            </label>
                        </button>
                    </div>
                </div>
            </td>
            
            <td class="border-top-0">
                <div class="primary_input mt-10">
                    <input type="checkbox" id="isBreak[${row_count}]" class="common-checkbox is_break_checkbox" data-row_id="${row_count}" value="1"
                    name="routine[${row_count}][is_break]"
                    {{ isset($class_time) ? ($class_time->is_break == 1 ? 'checked' : '') : '' }}
                    >
                        <label for="isBreak[${row_count}]"></label>
                </div>
            </td>
            <td class="border-top-0 ">
                <div class="primary_input text-center">
                    <a href="" class="btn-primary" data-toggle="modal" data-target="#multipleDaysModal_${row_count}" > <i class="fa fa-calendar "></i></a>
                </div>
                <div class="modal fade" 
                    id="multipleDaysModal_${row_count}"     tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content"> 
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('academics.multiple_day') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>       
                        <div class="modal-body">
                            <input type='checkbox' id="all_days_${row_count}" class='common-checkbox all_days' data-row_id="${row_count}" name='all_days[]' value='0'>
                            <label for='all_days_${row_count}'>{{ __('academics.select_all') }}</label>
                            <div class='row p-0'>

                                @foreach ($sm_weekends as $sm_weekend)
                                <div class="col-lg-4 pr-0">
                                    <input type="checkbox" class="common-checkbox day-checkbox day_${row_count}" value="{{ $sm_weekend->id }}" data-row_id="${row_count}" id="day_{{ $loop->index . '_${row_count}' }}"
                                    name="routine[${row_count}][day_ids][]"  >
                                        <label for="day_{{ $loop->index . '_${row_count}' }}">{{ $sm_weekend->name }}</label>
                                </div>
                                @endforeach

                            </div>
                            <div class="col-lg-12 text-center ">
                            <div class="d-flex justify-content-between pull-right">
                                    <button class="primary-btn fix-gr-bg pull right " data-dismiss="modal" >Okay</button>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                </div>
            </td>
            <td class="border-top-0">   
                <div class="row">
                    <div class="col-lg-12 mt-30-md">
                        <select class="primary_select  form-control selectRoom" data-room_row_id = "${row_count}" id="room_${row_count}" name="routine[${row_count}][room]">
                            <option data-display="@lang('academics.select_room')" value="">@lang('academics.select_room')</option>
                            @foreach ($rooms as $room)
                                
                                <option value="{{ @$room->id }}">{{ @$room->room_no }}</option>
                                
                            @endforeach
                        </select>
                        <span class="text-danger"  id="room_error"></span>
                    </div>
                </div>
            </td>
            <td class="border-top-0">
                
                @if (userPermission('delete-class-routine'))

                    
                    <button class="removeRoutineRowBtn primary-btn icon-only fix-gr-bg" type="button">
                        <span class="ti-trash" ></span>
                        </button>

                @endif  
    
            </td>
        </tr>`;


                    $("#classRoutineTable tbody").append(tr);
                    $('#row_count').val(row_count + 1);


                    $('.primary_select').niceSelect('destroy');
                    $(".primary_select").niceSelect();

                    $(".primary_input_field.time").datetimepicker({
                        format: "LT",
                    });
                };


                $(document).on("change", '.all_days', function() {
                    let day_id = $(this).data('row_id');

                    $(".day_" + day_id).prop("checked", this.checked);
                });

                $(document).on("change", '.day-checkbox', function() {
                    let day_id = $(this).data('row_id');
                    if ($(".day_" + day_id + ":checked").length == $(".day_" + day_id).length) {
                        $('#all_days_' + day_id).prop("checked", true);
                    } else {
                        $('#all_days_' + day_id).prop("checked", false);
                    }
                });
                $(document).on("change", '.is_break_checkbox', function() {

                    let row_id = $(this).data('row_id');

                    let tr = $('#row_' + row_id);
                    console.log(tr);
                    if (tr.length > 0) {
                        if (this.checked) {
                            tr.find('.primary_select').prop('disabled', true);

                        } else {
                            tr.find('.primary_select').prop('disabled', false);
                        }
                    }
                });
                $(document).on("click", '.removeRoutineRowBtn', function(e) {

                    let class_routine_id = $(this).data('class_routine_id');

                    if (!class_routine_id) {
                        $(this).parent().parent().remove();
                    } else {
                        let row_id = $(this).data('row_id');
                        $('#classRoutineDeleteModal').modal('toggle');
                        $("#classRoutineDeleteSubmitButton").unbind("click");
                        $("#classRoutineDeleteSubmitButton").bind("click", function() {

                            var url = $("#url").val();

                            $.ajax({
                                type: "post",
                                data: {
                                    id: class_routine_id
                                },
                                dataType: "html",
                                url: url + "/" + "delete-class-routine",


                                success: function(data) {

                                    $('#row_' + row_id).remove();
                                    setTimeout(function() {

                                        toastr.success(
                                            "Operation Success!",
                                            "Success Alert", {
                                                iconClass: "customer-info",
                                            }, {
                                                timeOut: 2000,
                                            }
                                        );
                                    }, 500);
                                    $('#classRoutineDeleteModal').modal('hide');
                                    // console.log(data);
                                },
                                error: function(data) {
                                    console.log('error');
                                    // setTimeout(function() {
                                    //     toastr.error("Operation Not Done!", "Error Alert", {
                                    //         timeOut: 5000,
                                    //     });
                                    // }, 500);
                                },

                            });


                        });

                    }




                });

                $(document).on('change', '.selectTeacher', function(e) {
                    let id = $(this).data('teacher_row_id');
                    let type = 'teacher';
                    isBusy(id, type)
                })
                $(document).on('change', '.selectStartTime', function(e) {
                    let id = $(this).data('teacher_row_id');
                    let type = 'teacher';
                    isBusy(id, type)
                })
                $(document).on('change', '.selectEndtTime', function(e) {
                    let id = $(this).data('teacher_row_id');
                    let type = 'teacher';
                    isBusy(id, type)
                })
                $(document).on('change', '.selectRoom', function(e) {
                    let id = $(this).data('teacher_row_id');
                    let type = 'room';
                    isBusy(id, type)
                })

                function isBusy(id, type) {
                    let url = $('#url').val();

                    let class_id = $('#select_class').val();
                    let section_id = $('#select_section').val();
                    let day_id = $('#tabs_ul li a.active').attr('data-sm_weekend_id');
                    let start_time = $('#start_time_' + id).val();
                    let end_time = $('#end_time_' + id).val();

                    let subject_id = $("#subject_" + id).val();
                    let teacher_id = $("#teacher_" + id).val();

                    let room_id = $('#room_' + id).val();

                    var formatData = {
                        class_id: class_id,
                        day_id: day_id,
                        type: type,
                        section_id: section_id,
                        subject_id: subject_id,
                        teacher_id: teacher_id,
                        start_time: start_time,
                        end_time: end_time,
                        room_id: room_id,
                    };
                    $.ajax({
                        type: "GET",
                        data: formatData,
                        dataType: "json",
                        url: url + '/is-busy',
                        success: function(data) {
                            if (data.status == true) {
                                if (data.type == 'teacher') {
                                    $("#teacher_" + id).val('');
                                }
                                if (data.type == 'room') {
                                    $("#room_" + id).val('');
                                }
                                toastr.warning(data.msg);
                            }
                        },
                        error: function() {

                        }
                    });
                }
            </script>
        @endif
    @endpush

@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')