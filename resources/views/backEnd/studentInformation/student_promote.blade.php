@extends('backEnd.master')
@section('title') 
@lang('student.student_promote')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.student_promote')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('student.student_information')</a>
                <a href="#">@lang('student.student_promote')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-current-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_promoteA']) }}
                            <div class="row">
                                <div class="col-lg-3">
                                    <select class="primary_select  form-control{{ $errors->has('current_session') ? ' is-invalid' : '' }}" name="current_session" id="current_session">
                                        <option data-display="@lang('student.select_academic_year') *" value="">@lang('student.select_academic_year') *</option>
                                        @foreach($sessions as $session)
                                        <option value="{{$session->id}}" {{isset($current_session)? ($current_session == $session->id? 'selected':''):''}}>{{$session->year}} [{{$session->title}}]</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('current_session'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('current_session') }}
                                    </span>
                                    @endif                                  
                                </div>
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-3 mt-30-md">
                                    <select class="primary_select  form-control{{ $errors->has('current_class') ? ' is-invalid' : '' }}" id="classSelectStudent" name="current_class">
                                        <option data-display="@lang('student.select_current_class') *" value="">@lang('student.select_current_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($current_class)? ($current_class == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_class_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                     @if ($errors->has('current_class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('current_class') }}
                                    </span>
                                    @endif 
                                </div>
                                <div class="col-lg-2 mt-30-md" id="sectionStudentDiv">
                                    <select class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="sectionSelectStudent" name="section">
                                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section')</option>
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

                                <div class="col-lg-2 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                        <option data-display="@lang('exam.select_exam')*" value="">@lang('exam.select_exam') *</option>
                                        @foreach($exams as $exam)
                                            <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('exam') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-2 mt-30-md">
                                    <select class="primary_select  form-control{{ $errors->has('result') ? ' is-invalid' : '' }}" name="result">
                                        <option data-display="@lang('reports.result') *" value="">@lang('reports.result')</option>
                                        <option value="P">@lang('student.pass')</option>
                                        <option value="F">@lang('student.fail')</option>
                                         <option value="Null">@lang('student.pass') & @lang('student.fail')</option>
                                    </select>
                                    @if ($errors->has('result'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('result') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg" id="search_promote">
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


    @if(isset($students))
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('student.promote_student_in_next_session')</h3>
                            </div>
                        </div>
                    </div>

                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-promote-store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'student_promote_submit']) }}
                    <input type="hidden" name="current_session" value="{{$current_session}}">
                    <input type="hidden" name="current_class" value="{{$current_class}}">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table school-table-style" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%">
                                       
                                            <input type="checkbox" id="checkAll" class="common-checkbox" name="checkAll">
                                            <label for="checkAll">@lang('common.all')</label>
                                        </th>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('common.class')/@lang('common.section')</th>
                                        <th>@lang('common.name')</th>                                      
                                        <th>@lang('student.current_result')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach( @$students['students'] ? @$students['students']: $students  as $student)
                                    {{$student->result}}
                                    @php
                                 
                                       if (@$student->result!='F') {
                                          $type='disabled';
                                       } else {
                                        $type='';
                                       }
                                       
                                  @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="student.{{$student->id}}" class="common-checkbox" name="student_checked[]" value="{{$student->id}}">
                                            <label for="student.{{$student->id}}"></label>
                                        </td>
                                        <td>{{$student->admission_no}}</td>
                                        {{-- <td>
                                            <div class="mr-30">
                                                <input type="checkbox" name="id[]" id="radioP{{$student->id}}" {{$type}} class="common-radio" value="{{$student->id}}"checked />
                                                <label for="radioP{{$student->id}}">{{$student->admission_no}} &nbsp;</label>
                                            </div>
                                        </td> --}}
                                        {{-- <input type="hidden" name="id[]" value="{{$student->id}}"> --}}
                                        @if ($student->class_name)
                                            <td>{{@$student->class_name !=""?@$student->class_name:""}} ({{@$student->class_name !=""?@$student->class_name:""}})</td>
                                        @else
                                             <td>{{$student->class !=""?$student->class->class_name:""}}</td>
                                        @endif
                                       
                                        <td>{{@$student->studentinfo ? $student->studentinfo->first_name .' '.$student->studentinfo->last_name : $student->first_name .' '.$student->last_name}}</td>
                                     
                                        <td>
                                        
                                            @if(in_array($student->id,$passesdStudentIds))
                                            <input type="text" hidden  name="result[{{$student->id}}]" value="P">
                                                 @lang('student.pass')
                                            @else             
                                                <input type="text" hidden  name="result[{{$student->id}}]" value="F">                              
                                                @lang('student.fail') 
                                            
                                            @endif         
                                        </td>
                                       
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5">
                                            <div class="row mt-30">
                                                <div class="col-lg-3">
                                                    <select class="primary_select  promote_session form-control{{ $errors->has('promote_session') ? ' is-invalid' : '' }}" name="promote_session" id="promote_session">
                                                        <option data-display="@lang('common.select_academic_year') *" value="">@lang('common.select_academic_year') *</option>
                                                        @foreach($Upsessions as $session)
                                                        @if (@$current_session != $session->id)
                                                          <option value="{{$session->id}}" {{( old("promote_session") == $session->id ? "selected":"")}}>{{$session->year}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                    
                                                    <span class="text-danger d-none" role="alert" id="promote_session_error">
                                                        <strong>@lang('common.the_session_is_required')</strong>
                                                    </span>
                                                </div>

                                              
                                                 <div class="col-lg-3 " id="select_class_div">
                                                    <select class="primary_select "  name="promote_class" id="select_class">
                                                        <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                                    </select>
                                                </div>

                                                 <div class="col-lg-3 " id="select_section_div">
                                                    <select class="primary_select " id="select_section" name="promote_section">
                                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                                    </select>
                                                </div>
                                               
                                                @if(userPermission(82))
                                                <div class="col-lg-3 text-center">
                                                    <button type="submit" class="primary-btn fix-gr-bg" id="student_promote_submit">
                                                        <span class="ti-check"></span>
                                                        @lang('student.promote')
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    

                    {{ Form::close() }}
                </div>
            </div>
    </div>
</section>
@endif
<script>



</script>

@endsection
