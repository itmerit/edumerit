@extends('backEnd.master')
@section('title') 
@lang('examplan::exp.generate_seat_plan')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1> @lang('examplan::exp.generate_seat_plan')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('examplan::exp.exam_plan')</a>
                <a href="#">@lang('examplan::exp.generate_seat_plan')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria') </h3>
                </div>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'examplan.seatplan.search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
            <div class="white-box">
                <div class="row">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam" id="exam">
                                    <option data-display="@lang('common.select_exam') *" value="">@lang('common.select_exam') *</option>
                                    @foreach($exams as $exam)
                                    <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                    @endforeach
                                </select>
                               
                                @if ($errors->has('exam'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ @$errors->first('exam') }}
                                </span>
                                @endif
                            </div>                           
                            <div class="col-lg-4 mt-30-md" id="id-card-div">
                                <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                    @endforeach
                                </select>
                                 @if ($errors->has('class'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md" id="select_section_div">
                                <select class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
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
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</section>


@if(isset($records))
 <section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row mt-40">  
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('common.student_list')</h3>
                        </div>
                    </div>
                </div>

                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'examplan.seatplan.generate', 'method' => 'POST', 'target' => '_blank', 'enctype' => 'multipart/form-data']) }}
                <div class="row mt-20">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <input type="hidden" name="exam_type_id" value="{{$exam_id}}">
                            <table  class="table school-table-style" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%">
                                            <input type="checkbox" id="checkAll" class="common-checkbox" name="checkAll">
                                        <label for="checkAll"> @lang('common.all')</label>
                                        </th>
                                        <th width="20%">@lang('student.student_name')</th>
                                        <th width="10%">@lang('student.admission_no')</th>
                                        <th width="15%">@lang('common.class')</th>
                                        <th width="15%">@lang('student.father_name')</th>
                                        <th width="10%">@lang('student.category')</th>
                                        <th width="5%">@lang('common.gender')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $student)
                                        <tr>
                                            <td>
                                                
                                                <input type="checkbox" id="student.{{$student->id}}" @if(in_array($student->id, $seat_plan_ids)) checked @endif class="common-checkbox" name="data[{{$loop->index}}][checked]" value="1">
                                                <label for="student.{{$student->id}}"></label>
                                            </td>
                                                <input type="hidden" name="data[{{$loop->index}}][student_record_id]" value="{{@$student->id}}">
                                            
                                            <td>{{$student->studentDetail->full_name}}</td>
                                            <td>{{$student->studentDetail->admission_no}}</td>
                                            <td>{{$student->class != ""? @$student->class->class_name :""}} ({{'('.$student->section!=""? $student->section->section_name:"".')' }})</td>
                                            
                                            <td>{{$student->studentDetail->parents!=""?$student->studentDetail->parents->fathers_name:""}}</td>
                                            <td>{{$student->studentDetail->category!=""?$student->studentDetail->category->category_name:""}}</td>
                                            <td>{{$student->studentDetail->gender!=""?$student->studentDetail->gender->base_setup_name:""}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-20">
                    <div class="col-lg-12 text-center">
                        
                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="" data-original-title="">
                            <span class="ti-check"></span>
                            @lang('examplan::exp.generate_seat_plan')
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>
@endif
@section('script')
<script>

</script>
@endsection
@endsection
