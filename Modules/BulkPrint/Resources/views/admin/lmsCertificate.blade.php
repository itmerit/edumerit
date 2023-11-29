@extends('backEnd.master')
    @section('title') 
        @lang('bulkprint::bulk.lms_certificate')
    @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('bulkprint::bulk.student_certificate')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('bulkprint::bulk.bulk_print')</a>
                <a href="#">@lang('bulkprint::bulk.lms_certificate')</a>
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
        {{ Form::open(['class' => 'form-horizontal', 'method' => 'POST', 'route' => 'lms-certificate-bulk-print-seacrh']) }}
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-9 mt-30-md">
                                <select class="primary_select new_test w-100 bb form-control {{ @$errors->has('course_id') ? ' is-invalid' : '' }}" name="course_id">
                                    <option data-display="@lang('lms::lms.course') @lang('lms::lms.name') *" value="">@lang('lms::lms.course') @lang('lms::lms.name') *</option>
                                    @foreach($courses as $course)
                                        <option value="{{$course->id}}" {{old('course_id')? 'selected':''}}>{{$course->course_title}}</option>
                                    @endforeach
                                </select>
                            
                                @if ($errors->has('course_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        <strong>{{ @$errors->first('course_id') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 mt-30-md">
                                <div class="primary_input">
                                    <input class="primary_input_field form-control{{ $errors->has('grid_gap') ? ' is-invalid' : '' }}" type="number" name="grid_gap" autocomplete="off" value="{{old('grid_gap')}}">
                                    <label class="primary_input_label" for="">@lang('admin.grid_gap')(px)<span class="text-danger"> *</span></label>
                                    
    
                                    @if ($errors->has('grid_gap'))
                                        <span class="text-danger" >
                                            {{ $errors->first('grid_gap') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-printer pr-2"></span>
                                    @lang('common.print')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</section>
@endsection