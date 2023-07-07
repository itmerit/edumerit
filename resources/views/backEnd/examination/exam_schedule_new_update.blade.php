@extends('backEnd.master')
@section('title')
@lang('exam.exam_schedule_create')
@endsection
@push('css')
    <style>
        .input-right-icon {
            z-index: inherit !important;
        }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_schedule_create') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="{{route('exam_schedule')}}">@lang('exam.exam_schedule')</a>
                <a href="{{route('exam_schedule_create')}}">@lang('exam.exam_schedule_create')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">                   
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_schedule_create', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam_type">
                                        <option data-display="@lang('exam.select_exam') *" value="">@lang('exam.select_exam') *</option>
                                        @foreach($exam_types as $exam_type)
                                            <option value="{{@$exam_type->id}}" {{isset($exam_type_id)? ($exam_type_id == $exam_type->id? 'selected':''):''}}>{{@$exam_type->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam_type'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('exam_type') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{@$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section') " value="">@lang('common.select_section') </option>
                                        @isset($class_id)
                                            @foreach ($search_current_class->classSection as $section)
                                                <option  value="{{ $section->sectionName->id }}"  {{ ( ($section_id  == $section->sectionName->id) ? "selected":"")}}>{{ $section->sectionName->section_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
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
@if(isset($exam_schedule))
<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-9 col-md-9">
                <div class="main-title">
                    <h3 class="mb-30">@lang('exam.exam_schedule') | 
                                                        <small>
                                     @lang('exam.exam'): {{@$examName != '' ? $examName :' '}},
                                     @lang('common.class'): {{@$search_current_class != '' ? $search_current_class->class_name :' '}}, 
                                     @lang('common.section'): {{@$search_current_section !='' ? $search_current_section->section_name : 'All Sections'}},
                                      
                                    </small>
                    </h3>
                </div>
            </div>
         
            <div class="col-lg-3  col-md-3 ">
              <a href="{{route('exam-routine-print', [$class_id, $section_id,$exam_type_id])}}" class="primary-btn small fix-gr-bg pull-left" target="_blank"><i class="ti-printer"> </i> @lang('common.print')</a>
                    <button  class="primary-btn small fix-gr-bg pull-right" onclick="addRowInExamRoutine();" id="addRowBtn"> <span class="ti-plus pr-2"></span> @lang('common.add')</button>
            </div>
        </div>


    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'add-exam-routine-store',
    'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'myForm', 'id'=> "validateAddNewExamRoutine"]) }}

        <input type="hidden" name="class_id" id="class_id" value="{{ @$class_id}}">
        <input type="hidden" name="section_id" id="section_id" value="{{ @$section_id}}">
        <input type="hidden" name="exam_type_id" id="exam_type_id" value="{{ @$exam_type_id}}"> 
      

        <div class="row">
            <div class="col-lg-12">
                <table class="table school-table-style" cellspacing="0" width="100%" id="examRoutineTable">
                    <thead>
                        <tr>
                            <th>@lang('academics.subject')</th>
                            <th>@lang('common.class') </th>
                            <th>@lang('common.section')</th>
                            <th>@lang('common.teacher')</th>
                            <th>@lang('common.date')   </th>
                            <th>@lang('academics.start_time')</th>
                            <th>@lang('exam.end_time')</th>                       
                            <th>@lang('common.room')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                       <input type="hidden" id="row_count" value="{{ $exam_schedule->count() + 1 }}">
                            @foreach ($exam_schedule as $row=>$routine )
                                
                         
                      
                            <tr class="0" id="row_{{ $row }}">
                                <td  class="border-top-0">
                                    <div class="primary_input" >
                                            <select class="primary_select" name="routine[{{ $row }}][subject]" id="subject" data-rule-required="true" required >
                                                <option data-display="@lang('common.select_subject') *" >@lang('common.select_subject') *</option>

                                                @foreach($subjects as $subject)
                                                        
                                                    <option value="{{ $subject->id}}" {{ $routine->subject_id == $subject->id ?'selected':''}}>{{ @$subject->subject_name}}</option>
                                            
                                                @endforeach
                                                </select>
                                            
                                            <span class="text-danger subject_error"></span>  
                                    </div>
                                </td>

                                 <td class="border-top-0">
                                 <div class="row">
                                            <div class="col-lg-12">
       
                                              <select class="primary_select  promote_class" readonly disabled  id="promote_class" name="routine[{{$row}}][class]" required>
                                               <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                               @foreach($classes as $class)
                                                <option value="{{ @$class->id}}"  {{ ( ($class_id  == $class->id) ? "selected":"")}}>{{ $class->class_name}}</option>
                                               @endforeach
                                           </select>
                                           @if ($errors->has('class'))
                                           <span class="text-danger invalid-select" role="alert">
                                               {{ $errors->first('class') }}
                                           </span>
                                           @endif
       
                                       </div>
                                       </div>
                                       </td>
                                        <td class="border-top-0">
                                            
                                           <div class="row">

                                            <div class="col-lg-12" id="promote_section_div">
    
                                               <select class="primary_select"   name="routine[{{ $row }}][section]" required>
                                                
                                                <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *
                                                 </option>
                                                
                                                    @foreach ($search_current_class->classSection as $section)
                                                        <option  value="{{ $section->sectionName->id }}"  {{ ( ($routine->section_id  == $section->sectionName->id) ? "selected":"")}}>{{ $section->sectionName->section_name }}</option>
                                                    @endforeach
                                                    
                                             
                                                    </select>
                                                     
                                                    @if ($errors->has('section'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('section') }}</span>
                                                     @endif
    
                                            </div>
                                     </div>
                                </td>
                                    <td class="border-top-0"> 
                                    <div class="row " id="teacher-div">
                                        <div class="col-lg-12 mt-30-md">
                                            <select class="primary_select  form-control" name="routine[{{ $row }}][teacher_id]" >
                                                <option data-display="@lang('common.select_teacher')" required value="">@lang('common.select_teacher')</option>
                                                    @foreach($teachers as $teacher)                                
                                                        <option value="{{ @$teacher->id}}" {{ $routine->teacher_id == $teacher->id?'selected':''}}>{{ @$teacher->full_name}}</option>
                                                    @endforeach
                                            </select>
                                            <div class="pull-right loader loader_style" id="select_teacher_loader">
                                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                            </div>
                                            <span class="text-danger"  id="teacher_error"></span>
                                        </div>
                                    </div>
                                </td> 
                                <td class="border-top-0">
                                  
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-15">
                                          
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input 
                                                                class="primary_input_field primary_input_field date form-control"
                                                                id="startDate" type="text" name="routine[{{$row}}][date]" value="{{isset($routine)?  date('m/d/Y', strtotime(@$routine->date)) : date('m/d/Y')}}" autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#date_of_birth" type="button">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </td>
                                <td class="border-top-0">  
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field primary_input_field time start_time_required start_time  form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}" type="text" id="start_time_{{ $row }}" name="routine[{{ $row }}][start_time]"  value="{{isset($routine)? $routine->start_time: ''}}" required>
                                                
                                                
                                                <span class="text-danger start_time_error"></span> 
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-timer"></i>
                                            </button>
                                        </div>
                                    </div> 
                            
                                </td>   

                                <td class="border-top-0">   
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field primary_input_field time end_time form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}" id="end_time_{{ $row }}" type="text" name="routine[{{ $row }}][end_time]" data-row_id="{{ $row }}" value="{{isset($routine)? $routine->end_time: ''}}" required>
                                              
                                                
                                                <span class="text-danger end_time_error"></span> 
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-timer"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                               

                                <td class="border-top-0">   
                                    <div class="row">
                                        <div class="col-lg-12 mt-30-md">
                                            <select class="primary_select  form-control" name="routine[{{ $row }}][room]" id="room" required>
                                                <option data-display="@lang('common.select_room') *" value="">@lang('common.select_room') *</option>
                                                @foreach($rooms as $room)
                                                
                                                    <option value="{{ @$room->id}}" {{isset($routine)? ($routine->room_id == $room->id?'selected':''):''}}>{{ @$room->room_no}}</option>
                                                
                                                @endforeach
                                            </select>
                                            <span class="text-danger"  id="room_error"></span>
                                        </div>
                                    </div>
                                </td>
                                    <td class="border-top-0">
                                    
                                        @if(userPermission("delete-class-routine"))

                                            <button class="primary-btn icon-only fix-gr-bg removeExamRoutineRowBtn" data-row_id="{{ $row }}" data-exam_routine_id="{{ $routine->id }}" type="button">
                                                <span class="ti-trash"></span>
                                                </button>

                                        @endif  

                                    </td>
                                <td></td>
                            </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
        </div>

           <div class="col-lg-12 mt-20 text-center">
            <button class="primary-btn fix-gr-bg">
             <span class="ti-check"></span>
             @lang('common.save')
            </button>
        </div>
    {{ Form::close() }}    
    </div>
</section>
@endif
    <div class="modal fade" id="classRoutineDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">@lang('common.delete_exam_routine')</h5>
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
                  
                    <button class="primary-btn fix-gr-bg"  id="classRoutineDeleteSubmitButton">@lang('common.delete')</button>
                     
                </div>
          
            </div>
            
          </div>
        </div>
      </div>
    @if(isset($exam_schedule))
        @push('script')
            <script>
                addRowInExamRoutine = () => {
                    $("#addRowBtn").button("loading");
                    var tableLength = $("#examRoutineTable tbody tr").length;
                    var url = $("#url").val();       

                    let row_count = parseInt($('#row_count').val());
                        var tr=`<tr class="0" id="row_${row_count}">
                        
                                                <td  class="border-top-0">
                                                    <div class="primary_input" >
                                                            <select class="primary_select" name="routine[${row_count}][subject]" id="subject" required>
                                                                <option data-display="@lang('common.select_subject') *" >@lang('common.select_subject') *</option>

                                                                @foreach($subjects as $subject)
                                                                        
                                                                    <option value="{{ @$subject->id}}" >{{ @$subject->subject_name}}</option>
                                                            
                                                                @endforeach
                                                                </select>
                                                            
                                                            <span class="text-danger subject_error"></span>  
                                                    </div>
                                                </td>
                                                <td class="border-top-0">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                    
                                                            <select class="primary_select  promote_class" readonly disabled  id="promote_class" name="routine[${row_count}][class]" required>
                                                                <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                                                    @foreach($classes as $class)
                                                                        <option value="{{ @$class->id}}"  {{ ( ($class_id  == $class->id) ? "selected":"")}}>{{ $class->class_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                @if ($errors->has('class'))
                                                                <span class="text-danger invalid-select" role="alert">
                                                                    {{ $errors->first('class') }}
                                                                </span>
                                                                @endif
                    
                                                            </div>
                                                        </div>
                                                    </td>
                                                        <td class="border-top-0">
                                                            
                                                        <div class="row">

                                                            <div class="col-lg-12">
                    
                                                            <select class="primary_select" name="routine[${row_count}][section]" required>
                                                                
                                                                <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *
                                                                </option>
                                                                
                                                                    @foreach ($search_current_class->classSection as $section)
                                                                        <option  value="{{ $section->sectionName->id }}" >{{ $section->sectionName->section_name }}</option>
                                                                    @endforeach
                                                                    
                                                            
                                                                    </select>
                                                                    <div class="pull-right loader loader_style select_section_promote" id="select_section_promote">
                                                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                                                    </div>  
                                                                    @if ($errors->has('section'))
                                                                    <span class="text-danger invalid-select" role="alert">
                                                                        {{ $errors->first('section') }}
                                                                    </span>
                                                                    @endif
                    
                                                            </div>
                                                    </div>
                                                </td>
                                                    <td class="border-top-0"> 
                                                    <div class="row " id="teacher-div">
                                                        <div class="col-lg-12 mt-30-md">
                                                            <select class="primary_select  form-control" name="routine[${row_count}][teacher_id]" >
                                                                <option data-display="@lang('common.select_teacher')">@lang('common.select_teacher')</option>
                                                            
                                                                    @foreach($teachers as $teacher)                                
                                                                        <option value="{{ @$teacher->id}}" >{{ @$teacher->full_name}}</option>
                                                                    @endforeach
                                                            
                                                                
                                                            </select>
                                                            <div class="pull-right loader loader_style" id="select_teacher_loader">
                                                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                                            </div>
                                                            <span class="text-danger"  id="teacher_error"></span>
                                                        </div>
                                                    </div>
                                                </td> 
                                                <td class="border-top-0">
                                                    <div class="col-lg-12">
                                                        <div class="primary_input mb-15">
                                                            
                                                            <div class="primary_datepicker_input">
                                                                <div class="no-gutters input-right-icon">
                                                                    <div class="col">
                                                                        <div class="">
                                                                            <input 
                                                                                class="primary_input_field primary_input_field date form-control"
                                                                                id="startDate${row_count}" type="text" name="routine[${row_count}][date]" value="{{ date('m/d/Y') }}" autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn-date" data-id="#startDate" type="button">
                                                                        <label class="m-0 p-0" for="startDate${row_count}">
                                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                                        </label>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="border-top-0">  
                                                    <div class="row no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="primary_input">
                                                                <input class="primary_input_field primary_input_field time start_time form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}" type="text" id="start_time_${row_count}" name="routine[${row_count}][start_time]" required>
                                                              
                                                                
                                                                <span class="text-danger start_time_error"></span> 
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
                                                                <input class="primary_input_field primary_input_field time end_time  form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}" type="text" id="end_time_${row_count}" data-row_id="${row_count}" name="routine[${row_count}][end_time]" required>
                                                             
                                                                
                                                                <span class="text-danger end_time_error"></span> 
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
                                                    <div class="row">
                                                        <div class="col-lg-12 mt-30-md">
                                                            <select class="primary_select  form-control" data-room_row_id="${row_count}" name="routine[${row_count}][room]" id="room" required>
                                                                <option data-display="@lang('common.select_room') *" value="">@lang('common.select_room') *</option>
                                                                @foreach($rooms as $room)
                                                                
                                                                    <option value="{{ @$room->id}}" >{{ @$room->room_no}}</option>
                                                                
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger"  id="room_error"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                    <td class="border-top-0">
                                                    
                                                        @if(userPermission("delete-class-routine"))

                                                            <button class="primary-btn icon-only fix-gr-bg removeExamRoutineRowBtn"  type="button">
                                                                <span class="ti-trash"></span>
                                                                </button>

                                                        @endif  

                                                    </td>
                                                <td></td>
                                            </tr>`;
                            
                    
                        $("#examRoutineTable tbody").append(tr);
                        $('#row_count').val(row_count + 1);
                        

                        $('.primary_select').niceSelect('destroy');        
                        $(".primary_select").niceSelect();

                        $(".primary_input_field.time").datetimepicker({
                                format: "LT",
                        });
                        $(".primary_input_field.date").datepicker({
                            autoclose: true,
                            setDate: new Date(),
                    });
                };
                $(document).on("click", '.removeExamRoutineRowBtn', function(e) { 
                    let row_id = $(this).data('row_id'); 
            
                    let exam_routine_id = $(this).data('exam_routine_id');
                    
                    if(!exam_routine_id){         
                        $(this).parent().parent().remove();
                    }else{
                        let row_id = $(this).data('row_id'); 
                    
                        $('#classRoutineDeleteModal').modal('toggle');
                        $("#classRoutineDeleteSubmitButton").unbind("click");
                        $("#classRoutineDeleteSubmitButton").bind("click", function() {

                            var url          = $("#url").val();
                        
                            $.ajax({
                            type: "post",
                            data: {id : exam_routine_id},
                            dataType: "html",
                            url: url + "/" + "delete-exam-routine",
                    
                                                        
                                success: function(data) {
                                    
                                        $('#row_'+row_id).remove();
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
                                        console.log(data);
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
                $(document).on('focusout', '.end_time', function(e){
                    let row_id  = $(this).data('row_id');
                    let start_time = $('#start_time_'+row_id).val();
                    let end_time = $('#end_time_'+row_id).val();
                    if (moment(start_time, 'hh:mm a') > moment(end_time, 'hh:mm a')) {
                        toastr.error('End Time will be greater than start time');
                        console.log(start_time);

                    }
                  
                    console.log(moment(start_time, 'hh:mm a'));
                    console.log(moment(end_time, 'hh:mm a'));
                    console.log(end_time);
                   
                })
            </script>
        @endpush
    @endif
@endsection

@include('backEnd.partials.date_picker_css_js')