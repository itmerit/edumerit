@extends('backEnd.master')
@section('title') 
@lang('lesson::lesson.lesson_plan_overview')
@endsection
@section('mainContent')
<link rel="stylesheet" href="{{url('Modules/Lesson/Resources/assets/css/jquery-ui.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
      $( "#progressbar" ).progressbar({
        value: @isset($percentage) {{$percentage}} @endisset
      });
    } );
</script>
<style type="text/css">
    #selectStaffsDiv, .forStudentWrapper {
        display: none;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    .buttons_div_one{
    /* border: 4px solid #FFFFFF; */
    border-radius:12px;

    padding-top: 0px;
    padding-right: 5px;
    padding-bottom: 0px;
    margin-bottom: 4px;
    padding-left: 0px;
     }
    .buttons_div{
    border: 4px solid #19A0FB;
    border-radius:12px
    }
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1> @lang('lesson::lesson.teacher_lesson_plan_overview')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('lesson::lesson.lesson')</a>
                <a href="#">@lang('lesson::lesson.lesson_plan_overview')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
  
    </div>

            <div class="row">
                <div class="col-lg-12">
                
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'search-teacher-lessonPlan-overview', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_lesson_Plan']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <input type="hidden" name="teacher" value="{{$teachers}}">
                              
                                @if(moduleStatusCheck('University'))
                                  
                                    @includeIf('university::common.session_faculty_depart_academic_semester_level',['div'=>'col-lg-4', 'required' => ['USN','UF', 'UD', 'UA', 'US', 'USL', 'USUB']])
                                @else
                                <div class="col-lg-4 mt-30-md">
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class')*" value="">@lang('common.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}"  {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
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
                                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                    </select>
                                    <div class="pull-right loader" id="select_section_loader" style="margin-top: -30px;padding-right: 21px;">
                                        <img src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="" style="width: 28px;height:28px;">
                                    </div> 
                                    @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section') }}
                                    </span>
                                    @endif
                                </div>
    
                                <div class="col-lg-4 mt-30-md" id="select_subject_div">
                                    <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject" id="select_subject" name="subject">
                                        <option data-display="Select subject *" value="">@lang('common.select_subjects') *</option>
                                    </select>
                                    <div class="pull-right loader" id="select_subject_loader" style="margin-top: -30px;padding-right: 21px;">
                                        <img src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="" style="width: 28px;height:28px;">
                                    </div> 
                                    @if ($errors->has('subject'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('subject') }}
                                    </span>
                                    @endif
                                </div>
    
                                @endif
                                
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
            @if(isset($lessonPlanner))
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title" style="padding-left: 15px;">
                                <h3 class="mb-0">@lang('lesson::lesson.progress') 
                                
                            
                            </h3><br>@isset($total)
                            {{$completed_total}}/{{$total}}
                            @endisset
                            
                            <div id="progressbar" style="height: 10px;margin-bottom:0px"></div>
                           <div class="pull-right" style="margin-top: -30px;">
                            @isset($percentage) {{(int)($percentage)}}  % @endisset
                           </div>
                            </div>
                        </div>
                    </div>
                <div class="col-lg-12">
                    <table id="table_id" class="table" cellspacing="0" width="100%"> 
                        <thead>
                          
                            <tr>
                            <th>@lang('lesson::lesson.lesson')</th>
                            <th>@lang('lesson::lesson.topic')</th>
                            <th>@lang('lesson::lesson.sup_topic')</th>
                            <th>@lang('lesson::lesson.completed_date') </th>
                            <th>@lang('lesson::lesson.upcoming_date') </th>
                            <th>@lang('common.status')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($lessonPlanner as $data)                           
                       
                        <tr>
                            <td>{{@$data->lessonName !=""?@$data->lessonName->lesson_title:""}}</td>

                            <td> 
                                @if(count($data->topics) > 0) 
                                @foreach ($data->topics as $topic)
                                {{$topic->topicName->topic_title}} </br>
                                @endforeach
                                @else  
                                {{$data->topicName->topic_title}}
                                @endif

                            </td>

                            <td>
                                @if (count($data->topics) > 0)
                                @foreach ($data->topics as $topic)
                                    {{$topic->sub_topic_title}} </br>
                                @endforeach
                            @else
                                {{$data->sub_topic}}
                            @endif 
                            </td>

                                <td>
                                    
                                    {{@$data->competed_date !=""?@$data->competed_date:""}}<br> 
                                   
                            
                                </td>
                                <td>
                                   
                                      
                                            @if(date('Y-m-d')< $data->lesson_date && $data->competed_date=="")
                                            Upcoming     ({{$data->lesson_date}})<br>                                          
                                           @elseif($data->competed_date=="")
                                            Assigned Date({{$data->lesson_date}})  
                                           <br>
                                           @endif
                                       
                                 
                                    
                           
                                </td>
                            <td>
                               
                               @if($data->competed_date=="")  
                                  
                                       @if(date('Y-m-d')< $data->lesson_date)
                                           {{-- Upcoming ({{$topicdate->lesson_date}}) --}}
                                        
                                       @else
                                       Incomplete <br>
                                       @endif
                                   
                              
                                @else 
                                Completed<br>

                                @endif
                                
                            </td>
                            
                            <td> 
                               
                            
                                <label class="switch_toggle">
                                <input type="checkbox" data-id="{{$data->id}}"  {{@$data->completed_status == 'completed'? 'checked':''}}
                                        class="weekend_switch_topic" ">
                                    <span class="slider round"></span>
                                </label> <br>
 
                               
                            </td>
                        </tr>
                        @endforeach
                       
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
</section>



<div class="modal fade admin-query" id="showReasonModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lesson::lesson.complete_date')  </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
              
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lessonPlan-complete-status',
                        'method' => 'POST',  'name' => 'myForm', 'onsubmit' => "return validateAddNewroutine()"]) }}
                <div class="form-group">
                    <input type="hidden" name="lessonplan_id" id="lessonplan_id">                   
                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('complete_date') ? ' is-invalid' : '' }}" id="complete_date" type="text"
                    name="complete_date" value="{{date('m/d/Y')}}">
                </div>
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">Close</button>
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.save') </button>
                    
                </div>
                {{ Form::close() }}
            </div>

        </div>
    </div>
</div>


<div class="modal fade admin-query" id="CancelModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lesson::lesson.incomplete')  </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <h2> {{ __('lesson::lesson.Are You Sure') }}</h2>
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lessonPlan-complete-status',
                        'method' => 'POST',  'name' => 'myForm', 'onsubmit' => "return validateAddNewroutine()"]) }}
                <div class="form-group">
                    <input type="hidden" name="lessonplan_id" id="calessonplan_id">
                    <input type="hidden" name="cancel" value="incomplete">                   
                  
                </div>
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal">Close</button>
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('lesson::lesson.yes') </button>
                    
                </div>
                {{ Form::close() }}
            </div>

        </div>
    </div>
</div>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
@push('script')

<script>
    $(document).ready(function() {
            $(".weekend_switch_topic").on("change", function() {
                var id = $(this).data("id");              
                $('#lessonplan_id').val(id);
                $('#calessonplan_id').val(id);
  
                if ($(this).is(":checked")) {
                    var status = "1";                                       
                    var modal = $('#showReasonModal');                  
                    modal.modal('show');
                  
                } else {
                    var status = "0";                                                        
                    var modal = $('#CancelModal');                  
                    modal.modal('show');
                }
            });
        });
</script>
@endpush