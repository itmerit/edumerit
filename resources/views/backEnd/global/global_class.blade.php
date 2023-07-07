@extends('backEnd.master')
@push('css')
    <style>
        .student_rec_card{
            border-radius: 6px;
            border: 1px solid var(--border_color);
            width: 100%;
        }
        .student_rec_header{
            padding: 12px;
            background: -webkit-linear-gradient( 90deg, var(--gradient_1) 0%, var(--gradient_3) 51%, var(--gradient_2) 100% );
            background: -moz-linear-gradient( 90deg, var(--gradient_1) 0%, var(--gradient_3) 51%, var(--gradient_2) 100% );
            background: -o-linear-gradient(90deg, var(--gradient_1) 0%, var(--gradient_3) 51%, var(--gradient_2) 100%);
            background: -ms-linear-gradient(90deg, var(--gradient_1) 0%, var(--gradient_3) 51%, var(--gradient_2) 100%);
            background: linear-gradient(90deg, var(--gradient_1) 0%, var(--gradient_3) 51%, var(--gradient_2) 100%);
        }
        .student_rec_footer{
            padding: 12px;
            margin-top: 16px;
            border-top: 1px solid var(--border_color);
        }
        .student_rec_content{
            padding: 16px;
            max-height: 300px;
            min-height: 300px;
        }
        
    </style>
@endpush
@section('title') 
@lang('common.class')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.class')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('academics.academics')</a>
                    <a href="#">@lang('common.class')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($sectionId))
                @if(userPermission(266))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{route('global_class')}}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('common.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@if(isset($sectionId))
                                        @lang('academics.edit_class')
                                    @else
                                        @lang('academics.add_class')
                                    @endif
                                   
                                </h3>
                            </div>
                            @if(isset($sectionId))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'global_class_update', 'method' => 'POST']) }}
                            @else
                                @if(userPermission(266))

                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'global_class_store', 'method' => 'POST']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12"> 
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('common.name') <span>*</span></label>
                                                <input class="primary_input_field form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}"
                                                       type="text" name="name" autocomplete="off"
                                                       value="{{isset($classById)? @$classById->class_name: ''}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($classById)? $classById->id: ''}}">
                                               
                                                
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                   
                                    @if (generalSetting()->result_type == 'mark')
                                    <div class="row mt-30">
                                        <div class="col-lg-12"> 
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ @$errors->has('pass_mark') ? ' is-invalid' : '' }}"
                                                       type="text" name="pass_mark" autocomplete="off"
                                                       value="{{isset($classById)? @$classById->pass_mark: ''}}">
                                                <label class="primary_input_label" for="">@lang('exam.pass_mark') <span>*</span></label>
                                                
                                                @if ($errors->has('pass_mark'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('pass_mark') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif 
                                    <div class="row mt-30">
                                        <div class="col-lg-12">
                                            <label class="primary_input_label" for="">@lang('common.section')<span class="text-danger">*</span></label><br>
                                            @foreach($sections as $section)
                                                <div class="">
                                                    @if(isset($sectionId))
                                                        <input type="checkbox" id="section{{@$section->id}}"
                                                               class="common-checkbox form-control{{ @$errors->has('section') ? ' is-invalid' : '' }}"
                                                               name="section[]"
                                                               value="{{@$section->id}}" {{in_array(@$section->id, @$sectionId)? 'checked': ''}}>
                                                        <label for="section{{@$section->id}}">@lang('common.section') {{@$section->section_name}}</label>
                                                    @else
                                                        <input type="checkbox" id="section{{@$section->id}}"
                                                               class="common-checkbox form-control{{ @$errors->has('section') ? ' is-invalid' : '' }}"
                                                               name="section[]" value="{{@$section->id}}">
                                                        <label for="section{{@$section->id}}">@lang('common.section') {{@$section->section_name}}</label>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($errors->has('section'))
                                                <span class="text-danger validate-textarea-checkbox" role="alert">
                                                <strong>{{ @$errors->first('section') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @php
                                        $tooltip = "";
                                        if(userPermission(266)){
                                              $tooltip = "";
                                          }else{
                                              $tooltip = "You have no permission to add";
                                          }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                                    title="{{$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($sectionId))
                                                    @lang('academics.update_class')
                                                @else
                                                    @lang('academics.save_class')
                                                @endif
                                              
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('academics.class_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">

                                    <thead>
                                
                                    <tr>
                                        <th>@lang('common.class')</th>
                                        <th>@lang('common.section')</th>
                                        @if (@generalSetting()->result_type == 'mark')
                                        <th>@lang('exam.pass_mark')</th>
                                        @endif 
                                        <th>@lang('student.students')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($classes as $class)
                                        <tr>
                                            <td valign="top">{{@$class->class_name}}</td>
                                            <td>
                                                @if(@$class->globalGroupclassSections)
                                                    @foreach ($class->globalGroupclassSections as $section)
                                                     <a href="{{route('sorting_student_list_section',[$class->id,$section->globalSectionName->id])}}">{{@$section->globalSectionName->section_name}}-({{total_no_records($class->id, $section->globalSectionName->id)}})</a> 
                                                     {{ !$loop->last ? ', ':'' }}
                                                    @endforeach
                                                @endif
                                            </td>
                                            @if (@generalSetting()->result_type == 'mark')
                                            <td>
                                                {{$class->pass_mark}}
                                            </td>
                                            @endif
                                            <td>
                                                <a href="{{route('sorting_student_list',[$class->id])}}">{{$class->records_count}}</a>
                                            </td>
    
    
                                            <td valign="top">
                                                @php
                                                    $routeList = [
                                                        userPermission(263) ?
                                                            '<a class="dropdown-item"
                                                               href="'.route('global_class_edit', [@$class->id]).'">'.__('common.edit').'</a>' : null,
                                                        
                                                        userPermission(264) ? 
                                                            '<a class="dropdown-item" data-toggle="modal"
                                                               data-target="#deleteClassModal'.$class->id.'"
                                                               href="'.route('global_class_delete', [@$class->id]).'">'.__('common.delete').'</a>' : null,
    
                                                        ];
                                                @endphp
                                                <x-drop-down-action-component :routeList="$routeList" />
                                            </td>
                                        </tr>
    
                                        <div class="modal fade admin-query" id="deleteClassModal{{@$class->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('academics.delete_class')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;
                                                        </button>
                                                    </div>
    
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
    
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                            <a href="{{route('global_class_delete', [$class->id])}}"
                                                               class="text-light">
                                                                <button class="primary-btn fix-gr-bg"
                                                                        type="submit">@lang('common.delete')</button>
                                                            </a>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@push('script')
<script>
    $(document).on("change", ".assignedClassSection", function() {
        var id = $(this).val();
        var url = '{{url('/')}}';
        var formData = {
            assignedClass: $(this).val(),
        };
        $.ajax({
                type:'GET',
                data: formData,
                dataType:"json",
                url: "{{route('loadAssignedSubject')}}",
                success:function(data){
                    $('#classSectionWiseSubjects_'+ data.class_id).html(data.html);
                    $('#classSectionWiseStudyMat_'+ data.class_id).html(data.html2);
                    
                },
                error:function(error){
                    console.log(error);
                }
            });
    });

    $('.primary_select').niceSelect('update');

     function deleteSubject(id){
        alert(id);
        $(".assignedSubject"+id).remove();
     }


     $(document).on('click', '.saveAssignedSubject', function(event) 
        {
                var submit_btn = $(this).find("button[type=submit]");
                event.preventDefault();
                var url = $("#url").val();
                var class_id = $(this).data('class_id');
                var formData = $("#form_"+class_id).serialize();
                console.log(formData);
                    $.ajax({
                        type: "POST",                   
                        data:formData, 
                        dataType: "json",
                        url: "{{route('saveAssignedSubject')}}" ,
                        beforeSend: function() {
                            submit_btn.button('loading');
                        },
                        success: function(data) {
                            if (data.status == true) {
                                toastr.success(data.message, 'Success');                           
                            } else {
                                toastr.error(data.message, 'Error'); 
                            }
                            submit_btn.button('reset');
                        },
                        error: function(xhr) { 
                            toastr.error("Error occured. please try again", "Error");
                        },
                        complete: function() {
                            submit_btn.button('reset');
                        }
                    });
        });

        function addMoreGlobalSubject(id){
            var url = $("#url").val();
            var count = $("#assign-subject").children().length;
            var divCount = count + 1;

            // get section for student
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url + "/" + "global-assign-subject-get-by-ajax",
                success: function(data) {
                    var subject_teacher = "";
                    subject_teacher +=
                        "<div class='assignedSubject" +id + "' id='assign-subject-" +
                        divCount +
                        "'>";
                    subject_teacher += "<div class='row'>";
                    subject_teacher += "<div class='col-lg-5 mt-30-md'>";
                    subject_teacher +=
                        "<select class='primary_select' name='subjects[]' style='display:none'>";
                    subject_teacher +=
                        "<option data-display='"+window.jsLang('select_subject')+"'  value=''>"+window.jsLang('select_subject')+"</option>";
                    $.each(data[0], function(key, subject) {
                        subject_teacher +=
                            "<option value=" +
                            subject.id +
                            ">" +
                            subject.subject_name +
                            "</option>";
                    });
                    subject_teacher += "</select>";

                    subject_teacher +=
                        "<div class='nice-select primary_select form-control' tabindex='0'>";
                    subject_teacher += "<span class='current'>"+window.jsLang('select_subject')+"</span>";
                    subject_teacher +=
                        "<div class='nice-select-search-box'><input type='text' class='nice-select-search' placeholder='Search...'></div>";
                    subject_teacher += "<ul class='list'>";
                    subject_teacher +=
                        "<li data-value='' data-display='"+window.jsLang('select_subject')+"' class='option selected'>"+window.jsLang('select_subject')+"</li>";
                    $.each(data[0], function(key, subject) {
                        subject_teacher +=
                            "<li data-value=" +
                            subject.id +
                            " class='option'>" +
                            subject.subject_name +
                            "</li>";
                    });
                    subject_teacher += "</ul>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    subject_teacher += "<div class='col-lg-5 mt-30-md'>";
                    subject_teacher +=
                        "<select class='primary_select form-control' name='teachers[]' style='display:none'>";
                    subject_teacher +=
                        "<option data-display='"+window.jsLang('select_teacher')+"' value=''>"+window.jsLang('select_teacher')+"</option>";
                    $.each(data[1], function(key, teacher) {
                        subject_teacher +=
                            "<option value=" +
                            teacher.id +
                            ">" +
                            teacher.full_name +
                            "</option>";
                    });
                    subject_teacher += "</select>";
                    subject_teacher +=
                        "<div class='nice-select primary_select form-control' tabindex='0'>";
                    subject_teacher += "<span class='current'>"+window.jsLang('select_teacher')+"</span>";
                    subject_teacher +=
                        "<div class='nice-select-search-box'><input type='text' class='nice-select-search' placeholder='Search...'></div>";
                    subject_teacher += "<ul class='list'>";
                    subject_teacher +=
                        "<li data-value='' data-display='"+window.jsLang('select_teacher')+"' class='option selected'>"+window.jsLang('select_teacher')+"</li>";
                    $.each(data[1], function(key, teacher) {
                        subject_teacher +=
                            "<li data-value=" +
                            teacher.id +
                            " class='option'>" +
                            teacher.full_name +
                            "</li>";
                    });
                    subject_teacher += "</ul>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    subject_teacher += "<div class='col-lg-2'>";
                    subject_teacher +=
                        "<button class='primary-btn icon-only fix-gr-bg id='removeSubject' onclick='deleteSubject(" +id + ")' type='button'>";
                    subject_teacher += "<span class='ti-trash' ></span>";
                    subject_teacher += "</button>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    subject_teacher += "</div>";
                    $("#assign-subject_"+id).append(subject_teacher);
                },
                error: function(data) {
                    // console.log("Error:", data);
                },
            });
        }

</script>
@endpush
