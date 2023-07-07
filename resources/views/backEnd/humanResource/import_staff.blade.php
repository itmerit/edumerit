@extends('backEnd.master')
@section('title')
    @lang('hr.staff_import')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.staff_import')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('hr.human_resource')</a>
                    <a href="#">@lang('hr.staff_import')</a>
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
                    <a href="{{ url('/public/backEnd/bulksample/staffs.xlsx') }}">
                        <button class="primary-btn tr-bg text-uppercase bord-rad">
                            @lang('student.download_sample_file')
                            <span class="pl ti-download"></span>
                        </button>
                    </a>
                </div>
            </div>

            {{ Form::open([
                'class' => 'form-horizontal',
                'files' => true,
                'route' => 'staff-bulk-store',
                'method' => 'POST',
                'enctype' => 'multipart/form-data',
                'id' => 'staff_import_form',
            ]) }}
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-title">
                                        <div class="box-body">
                                            <br>
                                            1. @lang('hr.point1') <br>
                                            2. @lang('hr.point2') <br>
                                            3. @lang('hr.point3') (@foreach ($roles as $role)
                                                {{ '"'.$role->name.'"' }}  {{ !$loop->last ? ',' :''  }}
                                            @endforeach)<br>
                                            4. @lang('hr.point4') (@foreach ($departments as $department)
                                                {{ '"'.$department->name.'"' }}  {{ !$loop->last ? ',' :'' }}
                                            @endforeach)<br>
                                            5. @lang('hr.point5') @if (count($designations) > 0)
                                                @foreach ($designations as $designation)
                                                    {{ '"'.$designation->title.'"' }}  {{ !$loop->last ? ',' :''  }}
                                                @endforeach
                                                <br>
                                            @endif

                                            6. @lang('hr.point6')(
                                            @foreach ($genders as $gender)
                                                {{ $gender->id . '=' . $gender->base_setup_name . ',' }}
                                            @endforeach


                                            ).<br>
                                            7. @lang('hr.point7'). ("married", "unmarried")<br>
                                            8. @lang('hr.point8'). ("permanent", "contract")<br>

                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="row mb-40 mt-30">

                                <div class="col-lg-6">
                                    
                                  
                                    <div class="primary_input">
                                        <div class="primary_file_uploader">
                                            <input
                                                    class="primary_input_field form-control{{ $errors->has('file') ? ' is-invalid' : '' }}"
                                                    type="text" id="placeholderInput" placeholder="Excel file" readonly>
                                                
                                                @if ($errors->has('file'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('file') }}</span>
                                                @endif
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="browseFile">{{ __('common.browse') }}</label>
                                                <input type="file" class="d-none" name="file" id="browseFile">
                                            </button>
                                        </div>
                                    </div>
                                  
                                </div>
                                <div class="col-lg-6 text-center">
                                    <button class="primary-btn fix-gr-bg">
                                        <span class="ti-check"></span>
                                        @lang('hr.save_bulk_staffs')
                                    </button>
                                </div>
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
