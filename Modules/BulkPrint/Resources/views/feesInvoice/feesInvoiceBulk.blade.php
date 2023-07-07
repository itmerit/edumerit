@extends('backEnd.master')
    @section('title') 
        @lang('bulkprint::bulk.fees_invoice_bulk_print')
    @endsection
@section('mainContent')
<input type="hidden" id="classToSectionRoute" value="{{route('fees.ajax-get-all-section')}}">
<input type="hidden" id="sectionToStudentRoute" value="{{route('fees.ajax-section-all-student')}}">
<input type="hidden" id="classToStudentRoute" value="{{route('fees.ajax-get-all-student')}}">
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('bulkprint::bulk.fees_invoice_bulk_print')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('bulkprint::bulk.bulk_print')</a>
                <a href="#">@lang('bulkprint::bulk.fees_invoice_bulk_print')</a>
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
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'fees-invoice-bulk-print-search', 'method' => 'POST']) }}
                        <div class="row">
                            @if(moduleStatusCheck('University')==true)
                                @includeIf('university::common.session_faculty_depart_academic_semester_level',['hide'=>['USUB']])

                                <div class="col-lg-4 mt-25" id="selectStudentDiv">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.student') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select" id="selectStudent" name="student">
                                        <option data-display="@lang('common.select_student')" value="">@lang('common.select_student')</option>
                                    </select>
                                    <div class="pull-right loader loader_style" id="student_section_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('student'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('student') }}
                                        </span>
                                    @endif
                                </div>
                            @else
                            <div class="col-lg-4 mt-30-md">
                                <label class="primary_input_label" for="">
                                    {{ __('common.class') }}
                                        <span class="text-danger"> </span>
                                </label>
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="feesSelectClass" name="class">
                                    <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                    @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                                @endif
                            </div>
                        
                            <div class="col-lg-4 mt-30-md" id="feesSectionDiv">
                                <label class="primary_input_label" for="">
                                    {{ __('common.section') }}
                                        <span class="text-danger"> </span>
                                </label>
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="feesSection" name="section">
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

                            <div class="col-lg-4 mt-30-md" id="selectStudentDiv">
                                <label class="primary_input_label" for="">
                                    {{ __('common.student') }}
                                        <span class="text-danger"> </span>
                                </label>
                                <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="selectStudent" name="student">
                                    <option data-display="@lang('common.select_student')" value="">@lang('common.select_student')</option>
                                </select>
                                <div class="pull-right loader loader_style" id="student_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                @if ($errors->has('student'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('student') }}
                                    </span>
                                @endif
                            </div>
                            @endif

                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-printer pr-2"></span>
                                    @lang('common.print')
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript" src="{{url('Modules\Fees\Resources\assets\js\app.js')}}"></script>
@endpush
