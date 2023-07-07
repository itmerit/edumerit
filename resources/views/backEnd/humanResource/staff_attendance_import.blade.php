@extends('backEnd.master')
@push('css')
    <style>
        .input-right-icon button.primary-btn-small-input {
            top: 8px !important;
            right: 12px !important;
        }
    </style>
@endpush
@section('title')
@lang('hr.staff_attendance_import')
@endsection 
@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('hr.staff_attendance')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('hr.human_resource')</a>
                <a href="#">@lang('hr.staff_attendance')</a>
                <a href="#">@lang('hr.staff_attendance_import')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-6">
                <div class="main-title">
                    <h3>@lang('common.select_criteria')</h3>
                </div>
            </div>
            <div class="offset-lg-3 col-lg-3 text-right mb-20">
                {{-- <a href="{{url('download-staff-attendance-file')}}" > --}}
                <a href="{{url('/public/backEnd/bulksample/staff_attendance.xlsx')}}" >
                    <button class="primary-btn tr-bg text-uppercase bord-rad">
                        @lang('student.download_sample_file')
                        <span class="pl ti-download"></span>
                    </button>
                </a>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staff-attendance-bulk-store',
                        'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'student_form']) }}
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success'))
                  <div class="alert alert-success">
                      {{ session()->get('message-success') }}
                  </div>
                @elseif(session()->has('message-danger'))
                  <div class="alert alert-danger">
                      {{ session()->get('message-danger') }}
                  </div>
                @endif
                <div class="white-box">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <div class="box-body">      
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="row mb-40 mt-30">
                            <div class="col-lg-6 mt-30-md">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field  primary_input_field date form-control form-control read-only-input has-content" id="startDate" type="text" name="attendance_date" autocomplete="off" value="{{date('m/d/Y')}}">
                                                <label for="startDate"> @lang('hr.attendance_date') <span class="text-danger"> *</span></label>
                                                
                                             </div>
                                        </div>
                                        <button class="" type="button">
                                            <label class="m-0 p-0" for="startDate">
                                                <i class="ti-calendar" style="top: -15px;" id="admission-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                    
                                </div>
                            <div class="col-lg-6">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control {{ $errors->has('file') ? ' is-invalid' : '' }}" type="text" id="placeholderPhoto" placeholder="Excel file (xlsx, csv) *"
                                                readonly>
                                            
                                            @if ($errors->has('file'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('file') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="upload_content_file">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="file" id="upload_content_file">
                                        </button>
                                    </div>
                                </div>
                            </div>
                                
                        </div>

                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @lang('hr.import_attendance')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</section>
@endsection
@include('backEnd.partials.date_picker_css_js')
