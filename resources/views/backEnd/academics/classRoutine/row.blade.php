<tr class="0" id="row_{{ $row }}"> 
    <td class="border-top-0"> 
        <div class="primary_input" >
            <select class="primary_select w-100 form-control subject_required selectSubject" data-subjet_row_id="{{ $row }}" name="routine[{{ $row }}][subject]"  id="subject_{{ $row }}" >
                <option data-display="@lang('common.select_subject') *" value="" >@lang('common.select_subject') *</option>

                @foreach($subjects as $subject)
                        
                <option value="{{ @$subject->subject_id}}" {{ $routine->subject_id == $subject->subject_id ?'selected':''}}>{{ @$subject->subject->subject_name}}</option>
            
            @endforeach
                </select>
                
                <span class="text-danger subject_error"></span>  
            </div>
    </td>
       
    <td class="border-top-0"> 
        <div class="row " id="teacher-div">
            <div class="col-lg-12 mt-30-md">
                <select class="primary_select  form-control selectTeacher" data-teacher_row_id="{{ $row }}" name="routine[{{ $row }}][teacher_id]" id="teacher_{{ $row }}">
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
        <div class="row no-gutters input-right-icon">
            <div class="col">
                <div class="primary_input">
                    <input class="primary_input_field primary_input_field time start_time_required  form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }} selectStartTime" type="text" name="routine[{{ $row }}][start_time]" data-start_time_row_id = "{{ $row }}"  value="{{isset($routine)? $routine->start_time: ''}}" required id="start_time_{{ $row }}">
               
                    
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
                    <input class="primary_input_field primary_input_field time end_time_required  form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }} selectEndTime" type="text" name="routine[{{ $row }}][end_time]" data-end_time_row_id = "{{ $row }}"  value="{{isset($routine)? $routine->end_time: ''}}" required id="end_time_{{ $row }}">
                   
                    
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
        <div class="primary_input">
            <input type="checkbox" id="isBreak[{{ $row }}]" class="common-checkbox is_break_checkbox" data-row_id="{{ $row }}" value="1"
             name="routine[{{ $row }}][is_break]"
             {{isset($routine)? ($routine->is_break == 1 ? 'checked':''):''}}
             >
               
        </div>
    </td>
    <td class="border-top-0 text-center">
        <div class="primary_input">
           <a href="" class="btn-primary" data-toggle="modal" data-target="#multipleDays_{{ $row }}" > <i class="fa fa-calendar"></i></a>
        </div>
    </td>
    <td class="border-top-0">   
        <div class="row">
            <div class="col-lg-12 mt-30-md">
                <select class="primary_select  form-control selectRoom" data-room_row_id="{{ $row }}" name="routine[{{ $row }}][room]" id="room_{{ $row }}">
                    <option data-display="@lang('common.select_room')" value="">@lang('common.select_room')</option>
                    @foreach($rooms as $room)
                      
                        <option value="{{ @$room->id}}" {{isset($routine)? ($routine->room_id == $room->id?'selected':''):''}}>{{ @$room->room_no}}</option>
                      
                    @endforeach
                </select>
                <span class="text-danger"  id="room_error"></span>
            </div>
        </div>
    </td>
    <td class="border-top-0">
     
        @if(userPermission(249))

            <button class="primary-btn icon-only fix-gr-bg removeRoutineRowBtn" data-row_id="{{ $row }}" data-class_routine_id="{{ $routine->id }}" type="button">
                <span class="ti-trash"></span>
                </button>

        @endif  

    </td>
  
</tr>

<div class="modal fade" id="multipleDays_{{ $row }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">  
        <div class="modal-header">
            <h5 class="modal-title">Multiple Day</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>      
        <div class="modal-body">
            <input type='checkbox' id="all_days_{{ $row }}" class='common-checkbox all_days' data-row_id="{{ $row }}" name='all_days[]' value='0'>
            <label for='all_days_{{ $row }}'>{{__('common.select_all')}}</label>
            <div class='row p-0'>

                @foreach($sm_weekends as $sm_weekend)
                <div class="col-lg-4 pr-0">
                    <input type="checkbox" class="common-checkbox day-checkbox day_{{ $row }}" value="{{ $sm_weekend->id }}" data-row_id="{{ $row }}" id="day_{{ $loop->index .'_'. $row }}"
                    name="routine[{{ $row }}][day_ids][]"  >
                        <label for="day_{{ $loop->index .'_'. $row }}">{{ $sm_weekend->name }}</label>
                </div>
                @endforeach

            </div>
            <div class="col-lg-12 text-center ">
               <div class="d-flex justify-content-between pull-right">
                     <button class="primary-btn fix-gr-bg pull right " data-dismiss="modal" >{{ __('common.Okay') }}</button>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>