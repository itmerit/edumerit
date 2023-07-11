@extends('backEnd.master')
@section('title')
@lang('dormitory.student_dormitory_report')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('dormitory.student_dormitory_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('dormitory.dormitory')</a>
                <a href="#">@lang('dormitory.student_dormitory_report')</a>
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_dormitory_report', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                @if(moduleStatusCheck('University'))
                                @includeIf('university::common.session_faculty_depart_academic_semester_level',['required'=>['USN','UD','UA','US','USL','USEC'], 'hide' => ['USUB']])
                                @else
                                <div class="col-lg-4 mt-30-md">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.class') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                        @foreach($classes as $class)
                                        <option value="{{ @$class->id }}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{ @$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.section') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                        @if(isset($class_id))
                                            @foreach ($class->classSection as $section)
                                            <option value="{{ $section->sectionName->id }}" {{ old('section')==$section->sectionName->id ? 'selected' : '' }} >
                                                {{ $section->sectionName->section_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                </div>
                                @endif
                                <div class="{{ moduleStatusCheck('University') ? 'col-lg-3 mt-25' :'col-lg-4 mt-30-md' }}">
                                    <label class="primary_input_label" for="">
                                        {{ __('dormitory.dormitory') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select form-control {{ $errors->has('dormitory') ? ' is-invalid' : '' }}" name="dormitory">
                                        <option data-display="@lang('dormitory.select_dormitory')" value="">@lang('dormitory.select_dormitory')</option>
                                        @foreach($dormitories as $dormitory)
                                        <option value="{{ @$dormitory->id}}"  {{isset($dormitory_id)? ($dormitory_id == $dormitory->id? 'selected':''):''}}>{{ @$dormitory->dormitory_name}}</option>
                                        @endforeach
                                    </select>
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
            {{-- @if(isset($students)) --}}
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0"> @lang('dormitory.student_dormitory_report')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>

                                        <tr>
                                            @if(!moduleStatusCheck('University'))
                                                <th>@lang('common.class_Sec')</th>
                                            @endif
                                            <th> @lang('student.admission_no')</th>
                                            <th> @lang('student.student_name')</th>
                                            <th> @lang('common.mobile')</th>
                                            <th>@lang('student.guardian_phone')</th>
                                            <th>@lang('dormitory.dormitory_name')</th>
                                            <th>@lang('dormitory.room_number')</th>
                                            <th>@lang('dormitory.room_type')</th>
                                            <th>@lang('dormitory.cost_per_bed')</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($students as $student)

                                        <tr>
                                            @if(!moduleStatusCheck('University'))
                                            <td>
                                                @if(isset($class_id))
                                                @php if(!empty($student->recordClass)){ echo $student->recordClass->class->class_name; }else { echo ''; } @endphp
                                                @if(isset($section_id))

                                                {{$student->recordSection != ""? $student->recordSection->section->section_name:""}}
                                                @else
                                                (@foreach ($student->recordClasses as $section)
                                                {{$section->section->section_name}} {{ !$loop->last ? ', ':'' }}
                                                @endforeach)
                                                @endif

                                               @else
                                               @foreach ($student->studentRecords as $record)
                                               {{__('common.class') }} : {{ $record->class->class_name}}({{ $record->section->section_name}}) {{ !$loop->last ? ', ':'' }} <br>
                                                @endforeach
                                               @endif
                                                <input type="hidden" name="id[]" value="{{@$student->student_id}}">
                                            </td>
                                            @endif

                                            <td>{{@$student->admission_no}}</td>
                                            <td>{{@$student->full_name}}</td>
                                            <td>{{@$student->mobile}}</td>
                                            <td>{{@$student->parents->guardians_mobile}}</td>
                                            <td>{{ @$student->dormitory != ""? @$student->dormitory->dormitory_name: ''}}</td>
                                            <td>{{ @$student->room != ""? @$student->room->name: ''}}</td>
                                            <td>{{ @$student->room != ""? @$student->room->roomType->type: ''}}</td>
                                            <td>{{ @$student->room != ""? number_format( (float) @$student->room->cost_per_bed, 2, '.', ''): ''}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @endif --}}
        </div>
    </section>

@endsection
@include('backEnd.partials.data_table_js')