@extends('backEnd.master')
@section('title')
    @lang('admin.generate_certificate')
@endsection

@section('mainContent')
<input type="hidden" id="moduleStatus" value="University">
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('admin.generate_certificate')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('admin.admin_section')</a>
                    <a href="#">@lang('admin.generate_certificate')</a>
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
            {{ Form::open(['class' => 'form-horizontal', 'route' => 'print-generate-certificate', 'method' => 'POST']) }}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 mt-30-md systemRole">
                                    <select class="primary_select new_test w-100 bb form-control {{ @$errors->has('role') ? ' is-invalid' : '' }}" name="certificate_role" id="certificateRole">
                                        <option data-display="@lang('admin.select_role') *" value="">@lang('admin.select_role') *</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{old('certificate_role')? 'selected':''}}>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                
                                    @if ($errors->has('certificate_role'))
                                        <span class="text-danger invalid-select" role="alert">
                                            <strong>{{ @$errors->first('certificate_role') }}
                                        </span>
                                    @endif
                                </div>
                                @if(moduleStatusCheck('University'))

                                @else
                                <div class="col-lg-2 mt-30-md classSection d-none">
                                    <select class="primary_select new_test w-100 bb form-control {{ @$errors->has('class') ? ' is-invalid' : '' }}" name="class" id="classSelectStudent">
                                        <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                        @foreach($classes as $class)
                                            <option value="{{$class->id}}" {{old('class')? 'selected':''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                
                                    @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            <strong>{{ @$errors->first('class') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="col-lg-2 mt-30-md classSection d-none" id="sectionStudentDiv">
                                    <select class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="sectionSelectStudent" name="section">
                                        <option data-display="@lang('student.select_section')" value="">@lang('student.select_section')</option>
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
                                @endif
                                <div class="col-lg-4 mt-30-md" id="certificate-div">
                                    <select class="primary_select  form-control{{ $errors->has('certificate') ? ' is-invalid' : '' }}" id="certificateList" name="certificate">
                                        <option data-display=" @lang('admin.certificate') *" value=""> @lang('admin.certificate') *</option>
                                    </select>

                                    <div class="pull-right loader loader_style" id="certificateLoader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>

                                    @if ($errors->has('certificate'))
                                        <span class="text-danger invalid-select" role="alert">
                                            <strong>{{ @$errors->first('certificate') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="col-lg-4 mt-30-md gridGap">
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
                                
                                @if(moduleStatusCheck('University'))
                                <input type="hidden" id="moduleStatus" value="University">
                                <div class="col-lg-12 mt-25 classSection d-none">
                                    <div class="row">
                                        @includeIf('university::common.session_faculty_depart_academic_semester_level',['hide' => ['USUB'], 'div'=>'col-lg-4', 'ac_mt'=>'col-lg-25'])
                                    </div>
                                </div>
                                @endif
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
@push('script')
    <script>
        $(document).ready(function() {
            $("#certificateRole").on("change", function() {

                if($(this).val() == 2){
                    $('.classSection').removeClass('d-none');
                    if($('#moduleStatus').val() =='') {
                        $('.systemRole').removeClass('col-lg-4');
                        $('.systemRole').addClass('col-lg-2');
                        $('.gridGap').removeClass('col-lg-4');
                        $('.gridGap').addClass('col-lg-2');
                    }
                }else{
                    $('.classSection').addClass('d-none');
                    $('#certificateClass').val('');
                    $('#certificateSection').val('');
                    $('.systemRole').removeClass('col-lg-2');
                    $('.systemRole').addClass('col-lg-4');
                    $('.gridGap').removeClass('col-lg-2');
                    $('.gridGap').addClass('col-lg-4');
                }

                var i = 0;
                var formData = {
                    role_id: $(this).val(),
                };
            
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: '{{route("get-role-wise-certificate")}}',
                    beforeSend: function() {
                        $('#certificateLoader').addClass('pre_loader');
                        $('#certificateLoader').removeClass('loader');
                    },
                    success: function(data) {            
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#certificateList").find("option").not(":first").remove();
                                $("#certificate-div ul").find("li").not(":first").remove();
        
                                $.each(item, function(i, certificate) {
                                    $("#certificateList").append(
                                        $("<option>", {
                                            value: certificate.id,
                                            text: certificate.title,
                                        })
                                    );
        
                                    $("#certificate-div ul").append(
                                        "<li data-value='" +
                                        certificate.id +
                                        "' class='option'>" +
                                        certificate.title +
                                        "</li>"
                                    );
                                });
                            } else {
                                $("#certificate-div .current").html("certicicate *");
                                $("#certificateList").find("option").not(":first").remove();
                                $("#certificate-div ul").find("li").not(":first").remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                    complete: function() {
                        i--;
                        if (i <= 0) {
                            $('#certificateLoader').removeClass('pre_loader');
                            $('#certificateLoader').addClass('loader');
                        }
                    }
                });
            });
        });
    </script>
@endpush
