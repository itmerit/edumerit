
@extends('backEnd.master')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('public/backEnd/')}}/css/croppie.css">
@endsection
@push('css')  
    <style>
        .ti-calendar:before {
            position: absolute;
            bottom: 32px !important;
            left: 360px !important;
        }
        .input-right-icon button.primary-btn-small-input {
            top: 50% !important;
            right: 10px !important;
        }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb up_breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.profile_update') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('student_list')}}">@lang('common.student_list')</a>
                <a href="#">@lang('common.profile_update') </a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row mb-30">
            <div class="col-lg-6">
                <div class="main-title">
                    <h3>@lang('common.profile_update') </h3>
                </div>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'my-children-update',
                        'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'student_form']) }}
         <div class="row">
            <div class="col-lg-12">
             
                <div class="white-box">
                    <div class="">
                        <div class="row mb-4">
                            <div class="col-lg-12 text-center">

                                @if($errors->any())
                                    @foreach ($errors->all() as $error)


                                    @if($error == "The email address has already been taken.")
                                        <div class="error text-danger ">
                                            {{ 'The email address has already been taken, You can find out in student list or disabled student list' }}
                                        </div>
                                    @endif 
                                    @endforeach
                                @endif

                                @if ($errors->any())
                                     <div class="error text-danger ">{{ 'Something went wrong, please try again' }}</div>
                                @endif
                            </div>
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h4 class="stu-sub-head">@lang('student.personal_info')</h4>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                        <input type="hidden" name="id" id="id" value="{{$student->id}}">

                        <!-- <input type="hidden" name="parent_id" id="parent_id" value="{{$student->parent_id}}">  -->



                        <div class="row mb-20">                           
                            @if(in_array('admission_number',$fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('student.admission_number')  @if(is_required('admission_number')==true) * @endif</label>
                                    <input class="primary_input_field form-control{{ $errors->has('admission_number') ? ' is-invalid' : '' }}" type="text" name="admission_number" value="{{$student->admission_no}}" onkeyup="GetAdminUpdate(this.value,{{$student->id}})">
                                    
                                    <span class="text-danger" id="Admission_Number" role="alert"></span>
                                    @if ($errors->has('admission_number'))
                                    <span class="text-danger" >
                                        {{ $errors->first('admission_number') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif   
                            @if(in_array('roll_number',$fields))
                                @if(generalSetting()->multiple_roll==0)
                                    <div class="col-lg-2">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('student.roll')@if(is_required('roll_number')==true) <span class="text-danger"> *</span> @endif</label>
                                            <input class="primary_input_field read-only-input" type="text" placeholder="Roll Number" name="roll_number" value="{{$student->roll_no}}" readonly id="roll_number">
                                            
                                        </div>
                                    </div>
                                @endif
                            @endif                       
                        </div>
                        <div class="row mb-20">
                            @if(in_array('first_name',$fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('student.first_name') @if(is_required('first_name')==true) <span class="text-danger"> *</span> @endif</label>
                                    <input class="primary_input_field form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" name="first_name" value="{{$student->first_name}}">
                                    
                                    @if ($errors->has('first_name'))
                                    <span class="text-danger" >
                                        {{ $errors->first('first_name') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('last_name',$fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('student.last_name')@if(is_required('last_name')==true) <span class="text-danger"> *</span> @endif</label>
                                    <input class="primary_input_field form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" name="last_name" value="{{$student->last_name}}">
                                    
                                    @if ($errors->has('last_name'))
                                    <span class="text-danger" >
                                        {{ $errors->first('last_name') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('gender',$fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('student.gender')@if(is_required('gender')==true) <span class="text-danger"> *</span> @endif</label>
                                    <select class="primary_select  form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender">
                                        <option data-display="@lang('common.gender') @if(is_required('gender')==true)  * @endif" value="">@lang('common.gender') @if(is_required('gender')==true) <span class="text-danger"> *</span> @endif</option>
                                        @foreach($genders as $gender)
                                            @if(isset($student->gender_id))
                                                <option value="{{$gender->id}}" {{$student->gender_id == $gender->id? 'selected': ''}}>{{$gender->base_setup_name}}</option>
                                            @else
                                                <option value="{{$gender->id}}">{{$gender->base_setup_name}}</option>
                                            @endif
                                        @endforeach

                                    </select>
                                    
                                    @if ($errors->has('gender'))
                                    <span class="text-danger" >
                                        {{ $errors->first('gender') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('date_of_birth',$fields))
                            <div class="col-lg-3">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.date_of_birth') @if(is_required('date_of_birth')==true) <span class="text-danger"> *</span> @endif</label>
                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" id="startDate" type="text" name="date_of_birth" value="{{date('m/d/Y', strtotime($student->date_of_birth))}}" autocomplete="off">
                                            @if ($errors->has('date_of_birth'))
                                    <span class="text-danger" >
                                        {{ $errors->first('date_of_birth') }}
                                    </span>
                                    @endif
                                        </div>
                                    </div>
                                    <button class="" type="button">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mb-20">
                            @if(in_array('blood_group',$fields))
                             <div class="col-lg-2">
                                <div class="primary_input">
                                    <select class="primary_select  form-control{{ $errors->has('blood_group') ? ' is-invalid' : '' }}" name="blood_group">
                                        <option data-display="@lang('student.blood_group') @if(is_required('blood_group')==true)  * @endif" value="">@lang('student.blood_group') @if(is_required('blood_group')==true) <span class="text-danger"> *</span> @endif</option>
                                        @foreach($blood_groups as $blood_group)
                                        @if(isset($student->bloodgroup_id))
                                            <option value="{{$blood_group->id}}" {{$blood_group->id == $student->bloodgroup_id? 'selected': ''}}>{{$blood_group->base_setup_name}}</option>
                                        @else
                                            <option value="{{$blood_group->id}}">{{$blood_group->base_setup_name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    
                                    @if ($errors->has('blood_group'))
                                    <span class="text-danger" >
                                        {{ $errors->first('blood_group') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('religion',$fields))
                            <div class="col-lg-2">
                                <div class="primary_input">
                                    <select class="primary_select  form-control{{ $errors->has('religion') ? ' is-invalid' : '' }}" name="religion">
                                        <option data-display="@lang('student.religion') @if(is_required('religion')==true)  * @endif" value="">@lang('student.religion') @if(is_required('religion')==true) <span class="text-danger"> *</span> @endif</option>
                                        @foreach($religions as $religion)
                                            <option value="{{$religion->id}}" {{$student->religion_id != ""? ($student->religion_id == $religion->id? 'selected':''):''}}>{{$religion->base_setup_name}}</option>
                                        @endforeach

                                    </select>
                                    
                                    @if ($errors->has('religion'))
                                    <span class="text-danger" >
                                        {{ $errors->first('religion') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('caste',$fields))
                            <div class="col-lg-2">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('student.caste') @if(is_required('caste')==true) <span class="text-danger"> *</span> @endif</label>
                                    <input class="primary_input_field" type="text" name="caste" value="{{$student->caste}}">
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('email_address',$fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('common.email_address') @if(is_required('email_address')==true) <span class="text-danger"> *</span> @endif</label>
                                    <input oninput="emailCheck(this)" class="primary_input_field form-control{{ $errors->has('email_address') ? ' is-invalid' : '' }}" type="text" name="email_address" value="{{$student->email}}">
                                    
                                    @if ($errors->has('email_address'))
                                    <span class="text-danger" >
                                        {{ $errors->first('email_address') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('phone_number',$fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('common.phone_number') @if(is_required('phone_number')==true) <span class="text-danger"> *</span> @endif</label>
                                    <input oninput="phoneCheck(this)" class="primary_input_field form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" type="text" name="phone_number" value="{{$student->mobile}}">
                                    
                                    @if ($errors->has('phone_number'))
                                    <span class="text-danger" >
                                        {{ $errors->first('phone_number') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mb-20">
                            @if(in_array('admission_date',$fields))
                            <div class="col-lg-2">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('student.admission_date')
                                                @if(is_required('admission_date')==true) <span class="text-danger"> *</span> @endif</span>
                                            </label>
                                            <input class="primary_input_field  primary_input_field date form-control" id="endDate" type="text" name="admission_date" value="{{$student->admission_date != ""? date('m/d/Y', strtotime($student->admission_date)): date('m/d/Y')}}" autocomplete="off">
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="end-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('student_category_id',$fields))
                            <div class="col-lg-4">
                                <div class="primary_input">
                                    <div class="primary_input">
                                    <select class="primary_select  form-control{{ $errors->has('student_category_id') ? ' is-invalid' : '' }}" name="student_category_id">
                                        <option data-display="@lang('student.category') @if(is_required('student_category_id')==true) * @endif" value="">@lang('student.category') @if(is_required('student_category_id')==true) <span class="text-danger"> *</span> @endif</option>
                                        @foreach($categories as $category)
                                        @if(isset($student->student_category_id))
                                        <option value="{{$category->id}}" {{$student->student_category_id == $category->id? 'selected': ''}}>{{$category->category_name}}</option>
                                        @else
                                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                                        @endif
                                        @endforeach

                                    </select>
                                    
                                    @if ($errors->has('student_category_id'))
                                    <span class="text-danger" >
                                        {{ $errors->first('student_category_id') }}
                                    </span>
                                    @endif
                                </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('student_group_id',$fields))
                            <div class="col-lg-2">
                                <div class="primary_input">
                                    <div class="primary_input">
                                    <select class="primary_select  form-control{{ $errors->has('student_group_id') ? ' is-invalid' : '' }}" name="student_group_id">
                                        <option data-display="@lang('student.group') @if(is_required('student_group_id')==true) * @endif" value="">@lang('student.group') @if(is_required('student_group_id')==true) <span class="text-danger"> *</span> @endif</option>
                                        @foreach($groups as $group)
                                        @if(isset($student->student_group_id))
                                        <option value="{{$group->id}}" {{$student->student_group_id == $group->id? 'selected': ''}}>{{$group->group}}</option>
                                        @else
                                        <option value="{{$group->id}}">{{$group->group}}</option>
                                        @endif
                                        @endforeach

                                    </select>
                                    
                                    @if ($errors->has('student_group_id'))
                                    <span class="text-danger" >
                                        {{ $errors->first('student_group_id') }}
                                    </span>
                                    @endif
                                </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('height',$fields))
                            <div class="col-lg-2">
                                <div class="primary_input">
                                    <input class="primary_input_field" type="text" name="height" value="{{$student->height}}">
                                    <label class="primary_input_label" for="">@lang('student.height_in') @if(is_required('height')==true) <span class="text-danger"> *</span> @endif </label>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('weight',$fields))
                            <div class="col-lg-2">
                                <div class="primary_input">
                                    <input class="primary_input_field" type="text" name="weight" value="{{$student->weight}}">
                                    <label class="primary_input_label" for="">@lang('student.weight_kg') @if(is_required('weight')==true) <span class="text-danger"> *</span> @endif </label>
                                    
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(moduleStatusCheck('Lead')==true && in_array('source_id',$fields))
                        <div class="row mb-40">                           
                            <div class="col-lg-4 ">
                                <div class="primary_input">
                                        <select class="primary_select  form-control{{ $errors->has('route') ? ' is-invalid' : '' }}" name="source_id" id="source_id">
                                            <option data-display="@lang('lead::lead.source') @if(is_required('source_id')==true) * @endif" value="">@lang('lead::lead.source') @if(is_required('source_id')==true) <span class="text-danger"> *</span> @endif</option>
                                            @foreach($sources as $source)
                                            <option value="{{$source->id}}" {{$student->source_id == $source->id? 'selected': ''}}>{{$source->source_name}}</option>
                                            @endforeach
                                        </select>
                                        
                                        @if ($errors->has('source_id'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('source_id') }}
                                        </span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-20">
                           
                            @if(in_array('photo',$fields))
                            <div class="col-lg-3">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text" id="placeholderPhoto" placeholder="{{$student->student_photo != ""? getFilePath3($student->student_photo):(is_required('student_photo')==true ? trans('common.student_photo') .'*': trans('common.student_photo'))}}"
                                                disabled>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="photo">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="photo" id="photo">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('add_parent',$fields))
                                <div class="col-lg-6 text-right">
                                    <div class="row">
                                        <div class="col-lg-7 text-left" id="parent_info">
                                            <input type="hidden" name="parent_id" value="">
    
                                        </div>
                                        <div class="col-lg-5">
                                            <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button" data-toggle="modal" data-target="#editStudent">
                                                <span class="ti-plus pr-2"></span> 
                                                @lang('student.add_parent')
                                            </button>
                                        </div>
                                    </div>
    
                                </div>
                            @endif
                        </div>
                        <!-- Start Sibling Add Modal -->
                        <div class="modal fade admin-query" id="editStudent">
                            <div class="modal-dialog small-modal modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('common.select_sibling')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <form action="">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-12" id="sibling_required_error">
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="row mt-25">
                                                            <div class="col-lg-12" id="sibling_class_div">
                                                                <select class="primary_select " name="sibling_class" id="select_sibling_class">
                                                                    <option data-display="@lang('common.class') *" value="">@lang('common.class') *</option>
                                                                    @foreach($classes as $class)
                                                                    <option value="{{$class->id}}">{{$class->class_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-25">
                                                            <div class="col-lg-12" id="sibling_section_div">
                                                                <select class="primary_select " name="sibling_section" id="select_sibling_section">
                                                                    <option data-display="@lang('common.section') *" value="">@lang('common.section') *</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-25">
                                                            <div class="col-lg-12" id="sibling_name_div">
                                                                <select class="primary_select " name="select_sibling_name" id="select_sibling_name">
                                                                    <option data-display="@lang('student.sibling') *" value="">@lang('student.sibling') *</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- <div class="col-lg-12 text-center mt-40">
                                                        <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                                            <span class="ti-check"></span>
                                                            save information
                                                        </button>
                                                    </div> -->
                                                    <div class="col-lg-12 text-center mt-40">
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                            <button class="primary-btn fix-gr-bg" id="save_button_parent" data-dismiss="modal" type="button">@lang('student.update_information')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Sibling Add Modal -->
                        <input type="hidden" name="sibling_id" value="{{count($siblings) > 1? 1: 0}}" id="sibling_id">
                        @if(count($siblings) > 1)
                         <div class="row mt-40 mb-4" id="siblingTitle">
                            <div class="col-lg-11 col-md-10">
                                <div class="main-title">
                                    <h4 class="stu-sub-head">@lang('student.siblings')</h4>
                                </div>
                            </div>
                            <div class="col-lg-1 text-right col-md-2">
                                <button type="button" class="primary-btn small fix-gr-bg icon-only ml-10"  data-toggle="modal" data-target="#removeSiblingModal">
                                    <span class="pr ti-close"></span>
                                </button>
                            </div>
                        </div>
                        <div class="row mb-20 student-details" id="siblingInfo">
                            @foreach($siblings as $sibling)
                                @if($sibling->id != $student->id)
                                    <div class="col-sm-12 col-md-6 col-lg-3 mb-30">
                                        <div class="student-meta-box">
                                            <div class="student-meta-top siblings-meta-top"></div>
                                            <img class="student-meta-img img-100" src="{{asset($student->parents->fathers_photo)}}" alt="{{$student->parents->fathers_name}}">
                                            <div class="white-box radius-t-y-0">
                                                <div class="single-meta mt-10">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="name">
                                                            @lang('student.full_name')
                                                        </div>
                                                        <div class="value">
                                                            {{$sibling->full_name}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="single-meta">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="name">
                                                            @lang('student.admission_number')
                                                        </div>
                                                        <div class="value">
                                                            {{$sibling->admission_no}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="single-meta">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="name">
                                                            @lang('common.class')
                                                        </div>
                                                        <div class="value">
                                                            {{$sibling->class!=""?$sibling->class->class_name:""}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="single-meta">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="name">
                                                            @lang('common.section')
                                                        </div>
                                                        <div class="value">
                                                            {{$sibling->section !=""?$sibling->section->section_name:""}}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        <div class="parent_details" id="parent_details">
                            <div class="row mb-4">
                                <div class="col-lg-12">
                                    <div class="main-title">
                                        <h4 class="stu-sub-head">@lang('student.parents_and_guardian_info')</h4>
                                    </div>
                                </div>
                            </div>
                           
                                <div class="row mb-20">
                                    @if(in_array('fathers_name',$fields))
                                    <div class="col-lg-3">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('student.father_name') @if(is_required('father_name')==true) <span class="text-danger"> *</span> @endif</label>
                                            <input class="primary_input_field form-control{{ $errors->has('fathers_name') ? ' is-invalid' : '' }}" type="text" name="fathers_name" id="fathers_name" value="{{$student->parents->fathers_name}}">
                                            
                                            @if ($errors->has('fathers_name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('fathers_name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @if(in_array('fathers_occupation',$fields))
                                    <div class="col-lg-3">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('student.occupation')  @if(is_required('fathers_occupation')==true) <span class="text-danger"> *</span> @endif</label>
                                            <input class="primary_input_field form-control" type="text" placeholder="" name="fathers_occupation" id="fathers_occupation" value="{{$student->parents->fathers_occupation}}">
                                            
                                        </div>
                                    </div>
                                    @endif
                                    @if(in_array('fathers_phone',$fields))
                                    <div class="col-lg-3">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('student.father_phone') @if(is_required('father_phone')==true) <span class="text-danger"> *</span> @endif</label>
                                            <input oninput="phoneCheck(this)" class="primary_input_field form-control{{ $errors->has('fathers_phone') ? ' is-invalid' : '' }}" type="text" name="fathers_phone" id="fathers_phone"  value="{{$student->parents->fathers_mobile}}">
                                            
                                            @if ($errors->has('fathers_phone'))
                                            <span class="text-danger" >
                                                {{ $errors->first('fathers_phone') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @if(in_array('fathers_photo',$fields))
                                    <div class="col-lg-3">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('student.fathers_photo')
                                                        @if (is_required('fathers_photo') == true)
                                                            <span class="text-danger"> *</span>
                                                        @endif
                                                    </label>
                                                    <input class="primary_input_field" type="text" id="placeholderFathersName" placeholder="{{isset($student->parents->fathers_photo) && $student->parents->fathers_photo != ""? getFilePath3($student->parents->fathers_photo):(is_required('fathers_photo')==true ? __('common.photo') .'*': __('common.photo'))}}"
                                                        disabled>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="fathers_photo">@lang('common.browse')</label>
                                                    <input type="file" class="d-none" name="fathers_photo" id="fathers_photo">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                           
                          
                            <div class="row mb-20">
                                @if(in_array('mothers_name',$fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.mother_name')  @if(is_required('mothers_name')==true) <span class="text-danger"> *</span> @endif</label>
                                        <input class="primary_input_field form-control{{ $errors->has('mothers_name') ? ' is-invalid' : '' }}" type="text" name="mothers_name" id="mothers_name"   value="{{$student->parents->mothers_name}}">
                                        
                                        @if ($errors->has('mothers_name'))
                                        <span class="text-danger" >
                                            {{ $errors->first('mothers_name') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if(in_array('mothers_occupation',$fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.occupation') @if(is_required('mothers_occupation')==true) <span class="text-danger"> *</span> @endif</label>
                                        <input class="primary_input_field" type="text" name="mothers_occupation" id="mothers_occupation" value="{{$student->parents->mothers_occupation}}">
                                        
                                    </div>
                                </div>
                                @endif
                                @if(in_array('mothers_phone',$fields))
                                 <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.mother_phone') @if(is_required('mothers_phone')==true) <span class="text-danger"> *</span> @endif</label>
                                        <input oninput="phoneCheck(this)" class="primary_input_field form-control{{ $errors->has('mothers_phone') ? ' is-invalid' : '' }}" type="text" name="mothers_phone" id="mothers_phone" value="{{$student->parents->mothers_mobile}}">
                                        
                                        @if ($errors->has('mothers_phone'))
                                        <span class="text-danger" >
                                            {{ $errors->first('mothers_phone') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if(in_array('mothers_photo',$fields))
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('student.mothers_photo')
                                                    @if (is_required('mothers_photo') == true)
                                                        <span class="text-danger"> *</span>
                                                    @endif
                                                </label>
                                                <input class="primary_input_field" type="text" id="placeholderMothersName" placeholder="{{isset($student->parents->mothers_photo) && $student->parents->mothers_photo != ""? getFilePath3($student->parents->mothers_photo):(is_required('mothers_photo')==true ? __('common.photo') .'*': __('common.photo'))}}"
                                                    disabled>
                                                
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="mothers_photo">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="mothers_photo" id="mothers_photo">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                               
                            </div>
                           
                            @if(in_array('relation',$fields))
                             <div class="row mb-40">
                                <div class="col-lg-12 d-flex">
                                    <p class="text-uppercase fw-500 mb-10">@lang('student.relation_with_guardian') *</p>
                                    <div class="d-flex radio-btn-flex ml-40">
                                        <div class="mr-30">
                                            <label for="relationFather">@lang('student.father')</label>
                                            <input type="radio" name="relationButton" id="relationFather" value="F" class="common-radio relationButton" {{$student->parents->relation == "F"? 'checked':''}}>
                                        </div>
                                        <div class="mr-30">
                                            <label for="relationMother">@lang('student.mother')</label>
                                            <input type="radio" name="relationButton" id="relationMother" value="M" class="common-radio relationButton" {{$student->parents->relation == "M"? 'checked':''}}>
                                        </div>
                                        <div>
                                            <label for="relationOther">@lang('student.Other')</label>
                                            <input type="radio" name="relationButton" id="relationOther" value="O" class="common-radio relationButton"
                                                    {{$student->parents->relation == "O"? 'checked':''}}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                           
                        
                            <div class="row mb-20">
                                @if(in_array('guardians_name',$fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.guardian_name')  @if(is_required('guardians_name')==true) <span class="text-danger"> *</span> @endif </label>
                                        <input class="primary_input_field form-control{{ $errors->has('guardians_name') ? ' is-invalid' : '' }}" type="text" name="guardians_name" id="guardians_name" value="{{$student->parents->guardians_name}}">
                                        
                                        @if ($errors->has('guardians_name'))
                                        <span class="text-danger" >
                                            {{ $errors->first('guardians_name') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @php
                                    if($student->parents->guardians_relation=='F'){
                                            $show_relation="Father";
                                    }
                                    if($student->parents->guardians_relation=='M'){
                                            $relashow_relationtion="Mother";
                                    }
                                    if($student->parents->guardians_relation=='O'){
                                            $show_relation="Other";
                                    }
                                @endphp
                                @if(in_array('relation',$fields))
                                    <div class="col-lg-3">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('student.relation_with_guardian') @if(is_required('relation')==true) <span class="text-danger"> *</span> @endif </label>
                                            <input class="primary_input_field read-only-input" type="text" placeholder="Relation" name="relation" id="relation" value="{{$student->parents !=""?@$student->parents->guardians_relation:""}}" readonly>
                                            
                                        </div>
                                    </div>
                                @endif
                                @if(in_array('guardians_email',$fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.guardian_email') @if(is_required('guardians_email')==true) <span class="text-danger"> *</span> @endif</label>
                                        <input class="primary_input_field form-control{{ $errors->has('guardians_email') ? ' is-invalid' : '' }}" type="text" name="guardians_email" id="guardians_email" value="{{$student->parents->guardians_email}}">
                                        
                                        @if ($errors->has('guardians_email'))
                                        <span class="text-danger" >
                                            {{ $errors->first('guardians_email') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if(in_array('guardians_photo',$fields))
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('student.guardians_photo')
                                                    @if (is_required('guardians_photo') == true)
                                                        <span class="text-danger"> *</span>
                                                    @endif
                                                </label>
                                                <input class="primary_input_field" type="text" id="placeholderGuardiansName" placeholder="{{isset($student->parents->guardians_photo) && $student->parents->guardians_photo != ""? getFilePath3($student->parents->guardians_photo):(is_required('guardians_photo')==true ? __('common.photo') .'*': __('common.photo'))}}"
                                                    disabled>
                                                
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="guardians_photo">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="guardians_photo" id="guardians_photo">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                           
                           
                            <div class="row mb-20">
                                @if(in_array('guardians_phone',$fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.guardian_phone') @if(is_required('guardians_phone')==true) <span class="text-danger"> *</span> @endif</label>
                                        <input class="primary_input_field form-control{{ $errors->has('guardians_phone') ? ' is-invalid' : '' }}" type="text" name="guardians_phone" id="guardians_phone" value="{{$student->parents->guardians_mobile}}">
                                        
                                        @if ($errors->has('guardians_phone'))
                                        <span class="text-danger" >
                                            {{ $errors->first('guardians_phone') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif 
                                @if(in_array('guardian_occupation',$fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.guardian_occupation') @if(is_required('guardians_occupation')==true) <span class="text-danger"> *</span> @endif</label>
                                        <input class="primary_input_field" type="text" name="guardians_occupation" id="guardians_occupation" value="{{$student->parents->guardians_occupation}}">
                                        
                                    </div>
                                </div>
                                @endif 
                                
                            </div>
                            
                            @if(in_array('guardians_address',$fields))
                             <div class="row mt-35">
                                <div class="col-lg-6">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('student.guardian_address') @if(is_required('guardians_address')==true) <span class="text-danger"> *</span> @endif  </label>
                                        <textarea class="primary_input_field form-control" cols="0" rows="4" name="guardians_address" id="guardians_address">{{$student->parents->guardians_address}}</textarea>
                                        
                                       @if ($errors->has('guardians_address'))
                                        <span class="danger text-danger">
                                            {{ $errors->first('guardians_address') }}
                                        </span>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                       <div class="row mt-40">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h4 class="stu-sub-head">@lang('student.student_address_info')</h4>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-30 mt-30">
                            @if(moduleStatusCheck('Lead')==true && in_array('lead_city',$fields))
                            <div class="col-lg-4 ">
                                <div class="primary_input" style="margin-top:53px !important">
                                    <select class="primary_select  form-control{{ $errors->has('route') ? ' is-invalid' : '' }}" name="lead_city" id="lead_city">
                                        <option data-display="@lang('lead::lead.city') @if(is_required('lead_city')==true) * @endif" value="">@lang('lead::lead.city') @if(is_required('lead_city')==true) <span class="text-danger"> *</span> @endif</option>
                                        @foreach($lead_city as $city)
                                        <option value="{{$city->id}}" {{ $student->lead_city_id == $city->id? 'selected': ''}}>{{$city->city_name}}</option>
                                        @endforeach
                                    </select>
                                    
                                    @if ($errors->has('lead_city'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('lead_city') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('current_address',$fields))
                            <div class="col-lg-4">

                                <div class="primary_input mt-20">
                                    <label class="primary_input_label" for="">@lang('student.current_address') @if(is_required('current_address')==true) <span class="text-danger"> *</span> @endif  </label>
                                    <textarea class="primary_input_field form-control{{ $errors->has('current_address') ? ' is-invalid' : '' }}" cols="0" rows="3" name="current_address" id="current_address">{{$student->current_address}}</textarea>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('permanent_address',$fields))
                            <div class="col-lg-4">

                                <div class="primary_input mt-20">
                                    <label class="primary_input_label" for="">@lang('student.permanent_address')  @if(is_required('permanent_address')==true) <span class="text-danger"> *</span> @endif  </label>
                                    <textarea class="primary_input_field form-control{{ $errors->has('permanent_address') ? ' is-invalid' : '' }}" cols="0" rows="3" name="permanent_address" id="permanent_address">{{$student->permanent_address}}</textarea>
                                    
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mt-40 mb-4">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h4 class="stu-sub-head">@lang('student.transport_and_dormitory_info')</h4>
                                </div>
                            </div>
                        </div>

                         <div class="row mb-20">
                             @if(in_array('route', $fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <select class="primary_select  form-control{{ $errors->has('route') ? ' is-invalid' : '' }}" name="route" id="route">
                                            <option data-display="@lang('student.route_list') @if(is_required('route')==true) * @endif" value="">@lang('student.route_list') @if(is_required('route')==true) * @endif</option>
                                            @foreach($route_lists as $route_list)
                                            @if(isset($student->route_list_id))
                                            <option value="{{$route_list->id}}" {{$student->route_list_id == $route_list->id? 'selected':''}}>{{$route_list->title}}</option>
                                            @else
                                            <option value="{{$route_list->id}}">{{$route_list->title}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        
                                        @if ($errors->has('route'))
                                        <span class="text-danger" >
                                            {{ $errors->first('route') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if(in_array('vehicle', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input" id="select_vehicle_div">
                                    <select class="primary_select  form-control{{ $errors->has('vehicle') ? ' is-invalid' : '' }}" name="vehicle" id="selectVehicle">
                                        <option data-display="@lang('student.vehicle_number') @if(is_required('vehicle')==true) * @endif" value="">@lang('student.vehicle_number') @if(is_required('vehicle')==true) * @endif</option>
                                        @foreach($vehicles as $vehicle)
                                        @if(isset($student->vechile_id) && $vehicle->id == $student->vechile_id)
                                        <option value="{{$vehicle->id}}" {{$student->vechile_id == $vehicle->id? 'selected':''}}>{{$vehicle->vehicle_no}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_transport_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    
                                    @if ($errors->has('vehicle'))
                                    <span class="text-danger" >
                                        {{ $errors->first('vehicle') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                         <div class="row mb-20">
                          
                            @if(in_array('dormitory_name', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <select class="primary_select  form-control{{ $errors->has('dormitory_name') ? ' is-invalid' : '' }}" name="dormitory_name" id="SelectDormitory">
                                        <option data-display="@lang('dormitory.dormitory_name') @if(is_required('dormitory_name')==true) * @endif" value="">@lang('dormitory.dormitory_name') @if(is_required('dormitory_name')==true) * @endif</option >
                                        @foreach($dormitory_lists as $dormitory_list)
                                        @if($student->dormitory_id)
                                        <option value="{{$dormitory_list->id}}" {{$student->dormitory_id == $dormitory_list->id? 'selected':''}}>{{$dormitory_list->dormitory_name}}</option>
                                        @else
                                        <option value="{{$dormitory_list->id}}">{{$dormitory_list->dormitory_name}}</option>
                                        @endif
                                        @endforeach                                    
                                    </select>
                                    
                                    @if ($errors->has('dormitory_name'))
                                    <span class="text-danger" >
                                        {{ $errors->first('dormitory_name') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('room_number', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input" id="roomNumberDiv">
                                    <select class="primary_select  form-control{{ $errors->has('room_number') ? ' is-invalid' : '' }}" name="room_number" id="selectRoomNumber">
                                        <option data-display="@lang('academics.room_number') @if(is_required('room_number')==true) <span class="text-danger"> *</span> @endif" value="">@lang('academics.room_number') @if(is_required('room_number')==true) <span class="text-danger"> *</span> @endif</option>
                                        @if($student->room_id != "")
                                            <option value="{{$student->room_id}}" selected="true"}}>{{$student->room !=""?$student->room->name:""}}</option>
                                        @endif
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_dormitory_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    
                                    @if ($errors->has('room_number'))
                                    <span class="text-danger" >
                                        {{ $errors->first('room_number') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mt-40 mb-4">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h4 class="stu-sub-head">@lang('student.Other_info')</h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-20">
                            @if(in_array('national_id_number', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field form-control{{ $errors->has('national_id_number') ? ' is-invalid' : '' }}" type="text" name="national_id_number" value="{{$student->national_id_no}}">
                                    <label class="primary_input_label" for="">@lang('student.national_id_number') @if(is_required('national_id_number')==true) <span class="text-danger"> *</span> @endif<span></span></label>
                                    
                                    @if ($errors->has('national_id_number'))
                                    <span class="text-danger" >
                                        {{ $errors->first('national_id_number') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if(in_array('local_id_number', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field form-control" type="text" name="local_id_number" value="{{$student->local_id_no}}">
                                    <label class="primary_input_label" for="">@lang('student.birth_certificate_number')@if(is_required('local_id_number')==true) <span class="text-danger"> *</span> @endif </label>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('bank_account_number', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field form-control" type="text" name="bank_account_number" value="{{$student->bank_account_no}}">
                                    <label class="primary_input_label" for="">@lang('student.bank_account_number') @if(is_required('bank_account_number')==true) <span class="text-danger"> *</span> @endif </label>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('bank_name', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field form-control" type="text" name="bank_name" value="{{$student->bank_name}}">
                                     <label class="primary_input_label" for="">@lang('student.bank_name') @if(is_required('bank_name')==true) <span class="text-danger"> *</span> @endif </label>
                                    
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mb-20 mt-40">
                            @if(in_array('bank_name', $fields))
                            <div class="col-lg-6">
                                <div class="primary_input">
                                    <textarea class="primary_input_field form-control" cols="0" rows="4" name="previous_school_details">{{$student->previous_school_details}}</textarea>
                                    <label class="primary_input_label" for="">@lang('student.previous_school_details') @if(is_required('previous_school_details')==true) <span class="text-danger"> *</span> @endif</label>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('additional_notes', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <textarea class="primary_input_field form-control" cols="0" rows="4" name="additional_notes">{{$student->aditional_notes}}</textarea>
                                     <label class="primary_input_label" for="">@lang('student.additional_notes') @if(is_required('additional_notes')==true) <span class="text-danger"> *</span> @endif</label>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('ifsc_code', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input mt-50">
                                    <input class="primary_input_field form-control" type="text" name="ifsc_code" value="{{old('ifsc_code')}}{{$student->ifsc_code}}">
                                    <label class="primary_input_label" for="">@lang('student.ifsc_code')@if(is_required('ifsc_code')==true) <span class="text-danger"> *</span> @endif</label>
                                    
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mt-40 mb-4">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h4 class="stu-sub-head">@lang('student.document_info')</h4>
                                </div>
                            </div>
                        </div>
                        
                         <div class="row mb-20">
                             @if(in_array('document_file_1', $fields))
                                <div class="col-lg-3">
                                    <div class="primary_input">
                                        <input class="primary_input_field" type="text" name="document_title_1" value="{{$student->document_title_1}}">
                                        <label class="primary_input_label" for="">@lang('student.document_01_title') @if(is_required('document_title_1')==true) <span class="text-danger"> *</span> @endif</label>
                                        
                                    </div>
                                </div>
                            @endif
                            @if(in_array('document_file_2', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field" type="text" name="document_title_2" value="{{$student->document_title_2}}">
                                    <label class="primary_input_label" for="">@lang('student.document_02_title') @if(is_required('document_title_2')==true) <span class="text-danger"> *</span> @endif</label>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('document_file_3', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field" type="text" name="document_title_3" value="{{$student->document_title_3}}">
                                    <label class="primary_input_label" for="">@lang('student.document_03_title') @if(is_required('document_title_3')==true) <span class="text-danger"> *</span> @endif</label>
                                    
                                </div>
                            </div>
                            @endif
                            @if(in_array('document_file_4', $fields))
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <input class="primary_input_field" type="text" name="document_title_4" value="{{$student->document_title_4}}">
                                    <label class="primary_input_label" for="">@lang('student.document_04_title') @if(is_required('document_title_4')==true) <span class="text-danger"> *</span> @endif</label>
                                    
                                </div>
                            </div>
                            @endif
                        </div>
                         <div class="row mb-20">
                            @if(in_array('document_file_1', $fields))
                             <div class="col-lg-3">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text" id="placeholderFileOneName" placeholder="{{$student->document_file_1 != ""? showPicName($student->document_file_1):(is_required('document_title_1')==true ? '01 *': '01') }}"
                                                disabled>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="document_file_1">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_1" id="document_file_1">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('document_file_2', $fields))
                             <div class="col-lg-3">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text" id="placeholderFileTwoName" placeholder="{{isset($student->document_file_2) && $student->document_file_2 != ""? showPicName($student->document_file_2):(is_required('document_title_2')==true ? '02 *': '02')}}"
                                                disabled>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="document_file_2">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_2" id="document_file_2">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('document_file_3', $fields))
                             <div class="col-lg-3">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text" id="placeholderFileThreeName" placeholder="{{isset($student->document_file_3) && $student->document_file_3 != ""? showPicName($student->document_file_3):(is_required('document_title_3')==true ? '03 *': '03')}}"
                                                disabled>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="document_file_3">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_3" id="document_file_3">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('document_file_4', $fields))
                             <div class="col-lg-3">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text" id="placeholderFileFourName" placeholder="{{isset($student->document_file_4) && $student->document_file_4 != ""? showPicName($student->document_file_4):(is_required('document_title_4')==true ? '04 *': '04') }}"
                                                disabled>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="document_file_4">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_4" id="document_file_4">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Custom Field Start --}}
                        @if(in_array('custom_field', $fields))
                        <div class="row mt-40">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h4 class="stu-sub-head">@lang('student.custom_field')</h4>
                                </div>
                            </div>
                        </div>

                        @include('backEnd.studentInformation._custom_field')
                        {{-- Custom Field End --}}
                        @endif
                        @if(count($fields)>0)
                            <div class="row mt-5">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg submit">
                                        <span class="ti-check"></span>
                                        @lang('student.update_student')
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</section>


<div class="modal fade admin-query" id="removeSiblingModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('student.remove')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('student.are_you')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        <button type="button" class="primary-btn fix-gr-bg" data-dismiss="modal" id="yesRemoveSibling">@lang('common.delete')</button>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>


 {{-- student photo --}}
 <input type="text" id="STurl" value="{{ route('student_update_pic',$student->id)}}" hidden>
 <div class="modal" id="LogoPic">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Crop Image And Upload</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="resize"></div>
                <button class="btn rotate float-lef" data-deg="90" > 
                <i class="ti-back-right"></i></button>
                <button class="btn rotate float-right" data-deg="-90" > 
                <i class="ti-back-left"></i></button>
                <hr>                
                <a href="javascript:;" class="primary-btn fix-gr-bg pull-right" id="upload_logo">Crop</a>
            </div>
        </div>
    </div>
</div>
{{-- end student photo --}}

 {{-- father photo --}}

 <div class="modal" id="FatherPic">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Crop Image And Upload</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="fa_resize"></div>
                <button class="btn rotate float-lef" data-deg="90" > 
                <i class="ti-back-right"></i></button>
                <button class="btn rotate float-right" data-deg="-90" > 
                <i class="ti-back-left"></i></button>
                <hr>                
                <a href="javascript:;" class="primary-btn fix-gr-bg pull-right" id="FatherPic_logo">Crop</a>
            </div>
        </div>
    </div>
</div>
{{-- end father photo --}}
 {{-- mother photo --}}

 <div class="modal" id="MotherPic">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Crop Image And Upload</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="ma_resize"></div>
                <button class="btn rotate float-lef" data-deg="90" > 
                <i class="ti-back-right"></i></button>
                <button class="btn rotate float-right" data-deg="-90" > 
                <i class="ti-back-left"></i></button>
                <hr>                
                <a href="javascript:;" class="primary-btn fix-gr-bg pull-right" id="Mother_logo">Crop</a>
            </div>
        </div>
    </div>
</div>
{{-- end mother photo --}}
 {{-- mother photo --}}

 <div class="modal" id="GurdianPic">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Crop Image And Upload</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="Gu_resize"></div>
                <button class="btn rotate float-lef" data-deg="90" > 
                <i class="ti-back-right"></i></button>
                <button class="btn rotate float-right" data-deg="-90" > 
                <i class="ti-back-left"></i></button>
                <hr>
                
                <a href="javascript:;" class="primary-btn fix-gr-bg pull-right" id="Gurdian_logo">Crop</a>
            </div>
        </div>
    </div>
</div>
{{-- end mother photo --}}

@endsection
@section('script')
<script src="{{asset('public/backEnd/')}}/js/croppie.js"></script>
<script src="{{asset('public/backEnd/')}}/js/st_addmision.js"></script>
@endsection

@include('backEnd.partials.date_picker_css_js')