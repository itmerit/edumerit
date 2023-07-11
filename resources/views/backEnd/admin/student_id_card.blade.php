@extends('backEnd.master')
@section('title') 
    @lang('admin.create_id_card')
@endsection
@section('mainContent')
<style>
    /* .user_id_card{
        border-radius:5px;
        background: #fff;
        height:54mm;
        width:86mm;
    } */
    .user_id_card_header{
        padding: 10px;
        background: #c738d8;

    }
    .user_id_card_header h4{
        font-size:18px;
        font-weight: 500;
        text-align: center;
        margin-bottom: 0;
        color: #fff;
    }
    .user_id_card .user_body{
        padding: 30px;
        /* background-image: url({{asset('public/backEnd/img/student/id-card-img.jpg') }}); */
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-position:top center;
    }
    .user_id_card .user_thumb {
        text-align: center;
    }
    .user_id_card .user_thumb img {
        width: 25mm;
        height: 25mm;
    }
    .user_id_card .user_body .user_info_details{
        margin-top: 25px;
    }
    .user_id_card .user_body .user_info_details{}
    .user_id_card .user_body .user_info_details .single_info{
        display: flex;
        justify-content : space-between;
        align-items : center;
    }
    .user_id_card .user_body .user_info_details .single_info span{
        font-size: 13px;
        font-weight: 500;
        color: #828bb2;
        text-transform:capitalize;
    }
    .user_id_card .user_body .single_info .thumb_singnature img{
        max-width: 60px;
        height: 28px;
    }
    .user_id_card .user_body .user_logo{
        text-align: center;
        margin-top: 20px;
    }
    .user_id_card .user_body .user_logo p{
        font-size: 13px;
        font-weight: 500;
        color: #828bb2;
        text-transform:capitalize;
        margin-top: 10px;
    }
    .user_id_card .user_body .user_logo img{
        max-width: 130px;
        height: 40px

    }
    .image_round{
        border-radius:50%;
    }
    .image_squre{
        border-radius:0%;
    }

    .cust-margin{
        margin-left: -125px !important;
    }

.sticky_card {
    position: sticky;
    top: 0;
}



</style>
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('admin.create_id_card')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('admin.admin_section')</a>
                <a href="#">@lang('admin.id_card')</a>
                <a href="#">@lang('admin.create_id_card')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($id_card))
            @if(userPermission(46))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('student-id-card')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                </div>
            </div>
            @endif
        @endif
        <div class="row">
            <div class="col-lg-7">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($id_card))
                                    @lang('common.edit')
                                @else
                                    @lang('common.add')
                                @endif
                                @lang('admin.id_card')
                            </h3>
                        </div>
                        {{-- @if(isset($id_card))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('student-id-card-update',@$id_card->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else --}}
                        @if(userPermission(46))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'store-id-card',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input class="primary_input_field "
                                                type="text" name="title" autocomplete="off" value="{{isset($id_card)? $id_card->title: old('title')}}" id="title">
                                            <label class="primary_input_label" for="">@lang('admin.id_card_title') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('title'))
                                            <span class="text-danger" >
                                                {{ $errors->first('title') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <select class="primary_select  form-control{{ $errors->has('page_layout_style') ? ' is-invalid' : '' }}" name="page_layout_style" id="pageLayoutStyle">
                                                <option value="horizontal">@lang('admin.horizontal')</option>
                                                <option value="vertical">@lang('admin.vertical')</option>
                                            </select>
                                                
                                                @if ($errors->has('page_layout_style'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('page_layout_style') }}
                                                </span>
                                                @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-25">
                                    <div class="row flex-grow-1 d-flex justify-content-between input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('background_img') ? ' is-invalid' : '' }}" type="text" id="backgroundImage" placeholder="{{isset($id_card)? ($id_card->logo != ""? getFilePath3($id_card->logo): trans('admin.background').' '.trans('admin.image')):trans('admin.background').' '.trans('admin.image')}}"
                                                    readonly>
                                                
                                                @if ($errors->has('background_img'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('background_img') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input cust-margin" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="document_file_5">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="background_img" id="document_file_5" onchange="imageChangeWithBackFile(this)" value="{{isset($id_card)? ($id_card->file != ""? getFilePath3($id_card->logo):''): ''}}">
                                            </button>
                                        </div>
                                    </div>
                                    <button class="primary-btn icon-only fix-gr-bg" type="button" id="deleteBackImg">
                                        <span class="ti-trash"></span>
                                    </button>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <select class="primary_select  form-control{{ $errors->has('applicable_user') ? ' is-invalid' : '' }}" name="applicable_user" id="applicableUser">
                                                <option data-display="@lang('admin.applicable_user') *" value="">@lang('common.select')*</option>
                                                <option value="2">@lang('admin.student')</option>
                                                <option value="0">@lang('admin.staff')</option>
                                            </select>
                                                <div class="text-danger" id="applicableUserError"></div>
                                                
                                                @if ($errors->has('applicable_user'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('applicable_user') }}
                                                </span>
                                                @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25 staffInfo d-none">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('admin.role')<span class="text-danger"> *</span></label><br> 
                                        @foreach($roles as $role)
                                            @if($role->id != 2 && $role->id != 3)
                                                <div class="">
                                                    <input type="checkbox" id="role_{{@$role->id}}" class="common-checkbox" value="{{@$role->id}}" name="role[]">
                                                    <label for="role_{{@$role->id}}">{{@$role->name}}</label>
                                                </div>
                                            @endif
                                        @endforeach 
                                        @if($errors->has('section'))
                                            <span class="text-danger" >
                                                {{ $errors->first('section') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('pl_width') ? ' is-invalid' : '' }}" type="text" name="pl_width" id="plWidth" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.page_layout_width') <span id="pWidth">(@lang('admin.default') 57 mm)</span></label>
                                            
                                            @if ($errors->has('pl_width'))
                                            <span class="text-danger" >
                                                {{ $errors->first('pl_width') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('pl_height') ? ' is-invalid' : '' }}" type="text" name="pl_height" id="plHeight" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.page_layout_height') <span id="pHeight">(@lang('admin.default') 89 mm)</span></label>
                                            
                                            @if ($errors->has('pl_height'))
                                            <span class="text-danger" >
                                                {{ $errors->first('pl_height') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-25">
                                    <div class="row flex-grow-1 d-flex justify-content-between input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('profile_image') ? ' is-invalid' : '' }}" type="text" id="profileImage" placeholder="{{isset($id_card)? ($id_card->logo != ""? getFilePath3($id_card->logo): trans('admin.profile').' '.trans('admin.image')):trans('admin.profile').' '.trans('admin.image')}}"
                                                    readonly>
                                                
                                                @if ($errors->has('profile_image'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('profile_image') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input cust-margin" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="document_file_6">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="profile_image" id="document_file_6" onchange="imageChangeWithFile(this,'.photo')" value="{{isset($id_card)? ($id_card->file != ""? getFilePath3($id_card->logo):''): ''}}">
                                            </button>
                                        </div>
                                    </div>
                                    <button class="primary-btn icon-only fix-gr-bg" type="button" id="deleteProImg">
                                        <span class="ti-trash"></span>
                                    </button>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10"> @lang('admin.user_photo_style') </p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="primary_input">
                                            <select class="primary_select  form-control{{ $errors->has('user_photo_style') ? ' is-invalid' : '' }}" name="user_photo_style" id="userPhotoStyle">
                                                <option data-display="@lang('admin.user_photo_style') *" value="">@lang('common.select')*</option>
                                                <option value="squre">@lang('admin.squre')</option>
                                                <option value="round">@lang('admin.round')</option>
                                            </select>
                                            <div class="text-danger" id="applicableUserError"></div>
                                            
                                            @if ($errors->has('user_photo_style'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('user_photo_style') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('user_photo_width') ? ' is-invalid' : '' }}" type="text" id="userPhotoWidth" name="user_photo_width" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.user_photo_size_width') <span id="profileWidth">(@lang('admin.default') 21 mm)</span> <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('user_photo_width'))
                                            <span class="text-danger" >
                                                <strong>{{$errors->first('user_photo_width')}}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('user_photo_height') ? ' is-invalid' : '' }}" type="text" id="userPhotoheight" name="user_photo_height" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.user_photo_size_height') <span id="profileHeight">(@lang('admin.default') 21 mm)</span> <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('user_photo_height'))
                                            <span class="text-danger" >
                                                {{ $errors->first('user_photo_height') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-4">
                                        <span>@lang('admin.layout_spacing')</span>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('t_space') ? ' is-invalid' : '' }}" type="text" id="tSpace" name="t_space" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.top_space')<span> (@lang('admin.default') 2.5 mm)</span></label>
                                            
                                            @if ($errors->has('t_space'))
                                            <span class="text-danger" >
                                                {{ $errors->first('t_space') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('b_space') ? ' is-invalid' : '' }}" type="text" id="bSpace" name="b_space" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.bottom_space') <span>(@lang('admin.default') 2.5 mm)</span></label>
                                            
                                            @if ($errors->has('b_space'))
                                            <span class="text-danger" >
                                                {{ $errors->first('b_space') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-4">

                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('l_space') ? ' is-invalid' : '' }}" type="text" id="lSpace" name="l_space" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.left_space') (@lang('admin.default') 3 mm)</label>
                                            
                                            @if ($errors->has('l_space'))
                                            <span class="text-danger" >
                                                {{ $errors->first('l_space') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('r_space') ? ' is-invalid' : '' }}" type="text" id="rSpace" name="r_space" autocomplete="off">
                                            <label class="primary_input_label" for="">@lang('admin.right_space') (@lang('admin.default') 3 mm)</label>
                                            
                                            @if ($errors->has('r_space'))
                                            <span class="text-danger" >
                                                {{ $errors->first('r_space') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="d-flex mt-25">
                                    <div class="row flex-grow-1 d-flex justify-content-between input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('logo') ? ' is-invalid' : '' }}" type="text" id="placeholderFileThreeName" placeholder="{{isset($id_card)? ($id_card->logo != ""? getFilePath3($id_card->logo): trans('admin.logo').' *'):trans('admin.logo') .' *'}}"
                                                    readonly>
                                                
                                                @if ($errors->has('logo'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('logo') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input cust-margin" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="document_file_3">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="logo" id="document_file_3" onchange="logoImageChangeWithFile(this,'.logoImage')" value="{{isset($id_card)? ($id_card->file != ""? getFilePath3($id_card->logo):''): ''}}">
                                            </button>
                                        </div>
                                    </div>
                                    <button class="primary-btn icon-only fix-gr-bg" type="button" id="deleteLogoImg">
                                        <span class="ti-trash"></span>
                                    </button>
                                </div>


                                {{-- <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('designation') ? ' is-invalid' : '' }}"
                                                type="text" id="signDesignation" name="designation" autocomplete="off" value="{{isset($id_card)? $id_card->designation: old('designation')}}">
                                            <input type="hidden" name="id" value="{{isset($id_card)? $id_card->id: ''}}">
                                            <label class="primary_input_label" for="">@lang('admin.Designation_of_Signature_person')<span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('designation'))
                                            <span class="text-danger" >
                                                {{ $errors->first('designation') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}


                                <div class="d-flex mt-25">
                                    <div class="row flex-grow-1 d-flex justify-content-between input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('signature') ? ' is-invalid' : '' }}" type="text" id="placeholderFileFourName" placeholder="{{isset($id_card)? ($id_card->signature != ""? getFilePath3($id_card->signature):trans('admin.signiture').' *'):trans('admin.signiture'). ' *'}}"
                                                    readonly>
                                                
                                                @if ($errors->has('signature'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('signature') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input cust-margin" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="document_file_4">@lang('common.browse')</label>
                                                <input type="file" class="d-none" name="signature" id="document_file_4" onchange="signatureImageChangeWithFile(this,'.signPhoto')">
                                            </button>
                                        </div>
                                    </div>
                                    <button class="primary-btn icon-only fix-gr-bg" type="button" id="deleteSignImg">
                                        <span class="ti-trash"></span>
                                    </button>
                                </div>

                                {{-- <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <textarea class="primary_input_field form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" cols="0" rows="4" name="address" id="addressValue">{{isset($id_card)? $id_card->address: old('address')}}</textarea>
                                            <label class="primary_input_label" for="">@lang('common.address')/@lang('common.phone')/@lang('common.email') <span class="text-danger"> *</span></label>
                                            
                                        </div>
                                        @if($errors->has('address'))
                                            <span class="error text-danger"><strong class="validate-textarea">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div> --}}

                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('admin.id')/@lang('student.roll')</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="admission_no" id="id_roll_yes" value="1" class="common-radio relationButton" onclick="idRoll('1')" {{isset($id_card)? ($id_card->admission_no == 1? 'checked': ''):'checked'}}>
                                                <label for="id_roll_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="admission_no" id="id_roll_no" value="0" class="common-radio relationButton" onclick="idRoll('0')" {{isset($id_card)? ($id_card->admission_no == 0? 'checked': ''):''}}>
                                                <label for="id_roll_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('student.student_name') </p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="student_name" id="student_name_yes" value="1" class="common-radio relationButton" onclick="studentName('1')" {{isset($id_card)? ($id_card->student_name == 1? 'checked': ''):'checked'}}>
                                                <label for="student_name_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="student_name" id="student_name_no" value="0" class="common-radio relationButton" onclick="studentName('0')" {{isset($id_card)? ($id_card->student_name == 0? 'checked': ''):''}}>
                                                <label for="student_name_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('common.class') </p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="class" id="class_yes" value="1" class="common-radio relationButton" onclick="IDclass('1')" {{isset($id_card)? ($id_card->class == 1? 'checked': ''):'checked'}}>
                                                <label for="class_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="class" id="class_no" value="0" class="common-radio relationButton" onclick="IDclass('0')" {{isset($id_card)? ($id_card->class == 0? 'checked': ''):''}}>
                                                <label for="class_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('student.father_name')</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="father_name" id="father_name_yes" value="1" class="common-radio relationButton" onclick="fatherName('1')" {{isset($id_card)? ($id_card->father_name == 1? 'checked': ''):'checked'}}>
                                                <label for="father_name_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="father_name" id="father_name_no" value="0" class="common-radio relationButton" onclick="fatherName('0')" {{isset($id_card)? ($id_card->father_name == 0? 'checked': ''):''}}>
                                                <label for="father_name_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('student.mother_name')</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="mother_name" id="mother_name_yes" value="1" class="common-radio relationButton" onclick="motherName('1')" {{isset($id_card)? ($id_card->mother_name == 1? 'checked': ''):'checked'}}>
                                                <label for="mother_name_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="mother_name" id="mother_name_no" value="0" class="common-radio relationButton" onclick="motherName('0')" {{isset($id_card)? ($id_card->mother_name == 0? 'checked': ''):''}}>
                                                <label for="mother_name_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('admin.student_address')</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="student_address" id="address_yes" value="1" class="common-radio relationButton" onclick="addRess('1')" {{isset($id_card)? ($id_card->student_address == 1? 'checked': ''):'checked'}}>
                                                <label for="address_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="student_address" id="address_no" value="0" class="common-radio relationButton" onclick="addRess('0')" {{isset($id_card)? ($id_card->student_address == 0? 'checked': ''):''}}>
                                                <label for="address_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('common.phone')</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="mobile" id="phone_yes" value="1" class="common-radio relationButton" {{isset($id_card)? ($id_card->phone == 1? 'checked': ''):'checked'}}>
                                                <label for="phone_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="mobile" id="phone_no" value="0" class="common-radio relationButton" {{isset($id_card)? ($id_card->phone == 0? 'checked': ''):''}}>
                                                <label for="phone_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('common.date_of_birth')</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="dob" id="dob_yes" value="1" class="common-radio relationButton" onclick="dOB('1')"  {{isset($id_card)? ($id_card->dob == 1? 'checked': ''):'checked'}}>
                                                <label for="dob_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="dob" id="dob_no" value="0" class="common-radio relationButton" onclick="dOB('0')" {{isset($id_card)? ($id_card->dob == 0? 'checked': ''):''}}>
                                                <label for="dob_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-4 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('student.blood_group')</p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="blood" id="blood_yes" value="1" class="common-radio relationButton" onclick="bloodGroup('1')" {{isset($id_card)? ($id_card->blood == 1? 'checked': ''):'checked'}}>
                                                <label for="blood_yes">@lang('admin.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="blood" id="blood_no" value="0" class="common-radio relationButton" onclick="bloodGroup('0')" {{isset($id_card)? ($id_card->blood == 0? 'checked': ''):''}}>
                                                <label for="blood_no">@lang('admin.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php 
                                  $tooltip = "";
                                  if(userPermission(46)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit savaIdCard" type="submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($id_card))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif
                                        @lang('admin.id_card')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('admin.preview_id_card') </h3>
                        </div>
                    </div>
                </div>

                <div class="sticky_card">
                    <div class="user_id_card_header mt-30">
                        <h4 id="titleV">@lang('admin.user_id_card')</h4>
                    </div>
                    <div class="mt-10">

                        <div id="horizontal" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
                            <div class="horizontal__card" style="line-height:1.02; background-image: url({{asset('public/backEnd/id_card/img/vertical_bg.png')}}); width: 57.15mm; height: 88.89999999999999mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff;">
                                <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:8px 12px">
                                    <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url('{{asset('public/backEnd/img/logo.png')}}');height: 30px;
                                    background-size: cover;
                                    background-repeat: no-repeat;
                                    background-position: center center;">
                                        {{-- <img class="logoImage hLogo" src=""  alt="" style="line-height:1.02; width: 100%;"> --}}
                                    </div>
                                    <div class="qr__img" style="line-height:1.02; width: 30px;">
                                        <img src="{{asset('public/backEnd/id_card/img/dd.png')}}" alt="" style="line-height:1.02; width: 100%;">
                                    </div>
                                </div>
                                <div class="horizontal_card_body" style="line-height:1.02; display:block; padding-top: 2.5mm; padding-bottom: 2.5mm; padding-right: 3mm ; padding-left: 3mm; flex-direction: column;">
                                    <div class="thumb hRoundImg hSize photo hImg hRoundImg" style=" background-image: url('{{asset('public/backEnd/id_card/img/thumb.png')}}');background-size: cover;
                                    background-position: center center;
                                    background-repeat: no-repeat; line-height:1.02; width: 21.166666667mm; flex: 80px 0 0; height: 21.166666667mm; margin: auto;border-radius: 50%; padding: 3px; align-content: center;
                                    justify-content: center;
                                    display: flex; border: 3px solid #fff;">
                                        {{-- <img class="" src="{{asset('public/backEnd/id_card/img/thumb2.png')}}" alt="" style="line-height:1.02; width: 100%;  border-radius: 50%; "> --}}
                                    </div>

                                    <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                                        <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:25px; margin-bottom:10px">
                                            <div class="card_text_left hId">
                                                <div id="hName">
                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h4>
                                                </div>
                                                <div id="hAdmissionNumber">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Admission No : 001</h3>
                                                </div>
                                                <div id="hClass">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Class : One (A)</h3>
                                                </div>
                                            </div>

                                            {{-- <div class="card_text_right">
                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:9px; font-weight:500;text-transform: uppercase; font-weight:500">jan 21. 2030</h3>
                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; text-transform: uppercase; font-weight:500 ">Date of iSSued</h4>
                                            </div> --}}
                                        </div>

                                        <div class="card_text_head hStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                                            <div class="card_text_left">
                                                {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h3> --}}
                                                <div id="hFatherName">
                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Infixedu</h4>
                                                </div>
                                                <div id="hMotherName">
                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; font-weight:500">Mother Name : Infixedu</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                                            <div class="card_text_left">
                                                <div id="hDob">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Date of Birth : Dec 25 , 2022</h3>
                                                </div>
                                                <div id="hBloodGroup">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Blood Group : B+</h3>
                                                </div>
                                                {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:9px; font-weight:500">DOB</h4> --}}
                                            </div>
                                            {{-- <div class="card_text_right">
                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase;font-weight:500; text-align:center;">B+</h3>
                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Blood Group</h4>
                                            </div> --}}
                                        </div>
                                        <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                                            <div class="card_text_left" id="hAddress">
                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">89/2 Panthapath, Dhaka 1215, Bangladesh</h3>
                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                                    <div class="singnature_img signPhoto hSign" style="background-image:url('{{asset('public/backEnd/id_card/img/Signature.png')}}');line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;height: 25px;
                                    background-size: cover;
                                    background-repeat: no-repeat;
                                    background-position: center center;">
                                        {{-- <img class="" src="" alt="" style="line-height:1.02; width: 100%;"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="vertical" class="d-none" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;">
                            <div class="vertical__card" style="line-height:1.02; background-image: url({{asset('public/backEnd/id_card/img/horizontal_bg.png')}}); width: 86mm; height: 54mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
                                <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
                                    <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url('{{asset('public/backEnd/img/logo.png')}}');background-size: cover;
                                    height: 30px;background-position: center center;
                                    background-repeat: no-repeat;">
                                        {{-- <img class="" src=""  alt="" style="line-height:1.02; width: 100%;"> --}}
                                    </div>
<!--                                    <div class="qr__img" style="line-height:1.02; width: 30px;">
                                        <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%;">
                                    </div>-->
                                </div>
                                <div class="vertical_card_body" style="line-height:1.02; display:flex; padding-top: 2.5mm; padding-bottom: 2.5mm; padding-right: 3mm ; padding-left: 3mm;">
                                    <div class="thumb vSize vSizeX photo vImg vRoundImg" style="background-image: url('{{asset('public/backEnd/id_card/img/thumb.png')}}'); line-height:1.02; width: 13.229166667mm; height: 13.229166667mm; flex-basis: 13.229166667mm; flex-grow: 0; flex-shrink: 0; margin-right: 30px; background-size: cover;
                                    background-position: center center;">
                                        {{-- <img class="" src="" alt="" style="line-height:1.02; width: 100%; padding: 3px; background: #fff"> --}}
                                    </div>
                                    <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                                        <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                                            <div class="card_text_left vId">
                                                <div id="vName">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h3>
                                                </div>
                                                <div id="vAdmissionNumber">
                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px;">Admission No : 001</h4>
                                                </div>
                                                <div id="vClass">
                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">Class : One (A)</h4>
                                                </div>
                                                {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:9px; font-weight:500">JB-007</h3> --}}
                                            </div>
                                            <div class="card_text_right">
                                            </br>
                                            <div id="vDob">
                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">DOB : jan 21. 2030</h3>
                                            </div>
                                            <div id="vBloodGroup">
                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">Blood Group : B+</h3>
                                            </div>
                                                {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; text-transform: uppercase; font-weight:500">Date of iSSued</h4> --}}
                                            </div>
                                        </div>

                                        <div class="card_text_head vStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                                            <div class="card_text_left">
                                                {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase;font-weight:500">@lang('common.name')</h4> --}}
                                            </div>
                                        </div>

                                        <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                                            <div class="card_text_left">
                                                <div id="vFatherName">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : InfixEdu</h3>
                                                </div>
                                                <div id="vMotherName">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Mother Name : InfixEdu</h3>
                                                </div>
                                                {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase;font-weight:500">DOB</h4> --}}
                                            </div>
                                            <div class="card_text_right">
                                                {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase; ">American</h3> --}}
                                                {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Nationality</h4> --}}
                                            </div>
                                        </div>
                                        <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                                            <div class="card_text_left vAddress">
                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase;">89/2 Panthapath, Dhaka 1215, Bangladesh </h3>
                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Address</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                                    <div class="singnature_img signPhoto vSign" style="background-image: url('{{asset('public/backEnd/id_card/img/Signature.png')}}'); line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;
                                    height: 25px;
                                    background-size: cover;
                                    background-repeat: no-repeat;
                                    background-position: center center;">
                                        {{-- <img class="" src="" alt="" style="line-height:1.02; width: 100%;"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="user_body">
                            <div class="user_thumb">
                                <img src="{{asset('public/backEnd/img/student/id-card-img.jpg') }}" id="photo" alt="" class="img_style">
                            </div>
                            <div class="user_info_details">
                                <div class="single_info studentName">
                                    <span>@lang('admin.user_name') :</span> </span>
                                    <span>@lang('common.name')</span>
                                </div>
                                <div class="single_info addMNumber">
                                    <span>@lang('student.admission_no') :</span>
                                    <span>123456 </span>
                                </div>
                                <div class="single_info idClass">
                                    <span>@lang('common.class') :</span>
                                    <span>ONE</span>
                                </div>
                                <div class="single_info fatherName">
                                    <span>@lang('student.father_name') :</span>
                                    <span>@lang('admin.user_father')</span>
                                </div>
                                <div class="single_info motherName">
                                    <span>@lang('student.mother_name') :</span>
                                    <span>@lang('admin.user_mother')</span>
                                </div>
                                <div class="single_info address">
                                    <span>@lang('common.address') :</span>
                                    <span>@lang('admin.user_address')</span>
                                </div>
                                <div class="single_info dob">
                                    <span>@lang('common.date_of_birth'):</span>
                                    <span>@lang('admin.user_date_of_birth')</span>
                                </div>
                                <div class="single_info blood_group">
                                    <span>@lang('student.blood_group') :</span>
                                    <span>@lang('admin.user_blood_group')</span>
                                </div>
                                <div class="single_info">
                                    <span id="disSign">@lang('admin.signature') :</span>
                                    <div class="thumb_singnature">
                                        <img src="{{asset('public/backEnd/img/student/id-card-img.jpg') }}" alt="" id="signPhoto">
                                    </div>
                                </div>
                                <div class="user_logo">
                                    <div class="logo_img">
                                        <img src="{{asset('public/backEnd/img/student/id-card-img.jpg') }}" alt="" id="logoImage">
                                    </div>
                                    <p id="address">@lang('common.address'), @lang('common.email'), @lang('common.phone')</p>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
$( document ).ready(function() {
    
    $(document).on("keyup", "#title", function(event) {
        let titleValue = $(this).val();
        $("#titleV").html(titleValue);
    });

    $(document).on("change", "#pageLayoutStyle", function(event) {
        let pageLayout = $(this).val();
        if(pageLayout == "horizontal"){
            $('#horizontal').removeClass('d-none');
            $('#vertical').addClass('d-none');
            $('#pWidth').html('(@lang('admin.default') 57 mm)');
            $('#pHeight').html('(@lang('admin.default') 89 mm)');
            $('#profileWidth').html('(@lang('admin.default') 21 mm)');
            $('#profileHeight').html('(@lang('admin.default') 21 mm)');
        }else{
            $('#horizontal').addClass('d-none');
            $('#vertical').removeClass('d-none');
            $('#pWidth').html('(@lang('admin.default') 89 mm)');
            $('#pHeight').html('(@lang('admin.default') 57 mm)');
            $('#profileWidth').html('(@lang('admin.default') 13 mm)');
            $('#profileHeight').html('(@lang('admin.default') 13 mm)');
        }
    });

    $(document).on("keyup", "#addressValue", function(event) {
        let addressValue = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".hAddress").html(addressValue);
        }else{
            $(".vAddress").html(addressValue);
        }
    });

    $(document).on("keyup", "#signDesignation", function(event) {
        let disSignValue = $(this).val();
        $("#disSign").html(disSignValue);
    });

    $(document).on("keyup", "#plWidth", function(event) {
        let plWidthValue = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".horizontal__card").css({"width": plWidthValue +"mm"});
        }else{
            $(".vertical__card").css({"width": plWidthValue +"mm"});
        }
    });

    $(document).on("keyup", "#plHeight", function(event) {
        let plHeightValue = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".horizontal__card").css({"height": plHeightValue +"mm"});
        }else{
            $(".vertical__card").css({"height": plHeightValue +"mm"});
        }
    });

    $(document).on("change", "#userPhotoStyle", function(event) {
        let userPhotoStyle = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(userPhotoStyle == "round"){
                $(".hRoundImg").css({ 'border-radius' : '50%'});
            }else{
                $(".hRoundImg").css({ 'border-radius' : '0'});
            }
        }else{
            if(userPhotoStyle == "round"){
                $(".vRoundImg").css({ 'border-radius' : '50%'});
            }else{
                $(".vRoundImg").css({ 'border-radius' : '0'});
            }
        }
    });

    $(document).on("keyup", "#userPhotoWidth", function(event) {
        let userPhotoWidth = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".hSize").css({"width": userPhotoWidth +"mm"});
        }else{
            $(".vSize").css({"width": userPhotoWidth +"mm"});
            $(".vSize").css({"flex-basis": userPhotoWidth +"mm"});
        }
    });

    $(document).on("keyup", "#userPhotoheight", function(event) {
        let userPhotoHeight = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".hSize").css({"height": userPhotoHeight +"mm"});
        }else{
            $(".vSize").css({"height": userPhotoHeight +"mm"});
        }
    });

    $(document).on("keyup", "#tSpace", function(event) {
        let tSpace = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".horizontal_card_body").css({"padding-top": tSpace +"mm"});
        }else{
            $(".vertical_card_body").css({"padding-top": tSpace +"mm"});
        }
    });

    $(document).on("keyup", "#bSpace", function(event) {
        let bSpace = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".horizontal_card_body").css({"padding-bottom": bSpace +"mm"});
        }else{
            $(".vertical_card_body").css({"padding-bottom": bSpace +"mm"});
        }
    });

    $(document).on("keyup", "#lSpace", function(event) {
        let lSpace = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".horizontal_card_body").css({"padding-left": lSpace +"mm"});
        }else{
            $(".vertical_card_body").css({"padding-left": lSpace +"mm"});
        }
    });

    $(document).on("keyup", "#rSpace", function(event) {
        let rSpace = $(this).val();
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            $(".horizontal_card_body").css({"padding-right": rSpace +"mm"});
        }else{
            $(".vertical_card_body").css({"padding-right": rSpace +"mm"});
        }
    });

// Radio Button
    studentName = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hName").show();
            }else{
                $("#hName").hide();
            }
        }else{
            if(status == "1"){
                $("#vName").show();
            }else{
                $("#vName").hide();
            }
        }
    }

    idRoll = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hAdmissionNumber").show();
            }else{
                $("#hAdmissionNumber").hide();
            }
        }else{
            if(status == "1"){
                $("#vAdmissionNumber").show();
            }else{
                $("#vAdmissionNumber").hide();
            }
        }
    }
    
    IDclass = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hClass").show();
            }else{
                $("#hClass").hide();
            }
        }else{
            if(status == "1"){
                $("#vClass").show();
            }else{
                $("#vClass").hide();
            }
        }
    }

    fatherName = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hFatherName").show();
            }else{
                $("#hFatherName").hide();
            }
        }else{
            if(status == "1"){
                $("#vFatherName").show();
            }else{
                $("#vFatherName").hide();
            }
        }
    }

    motherName = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hMotherName").show();
            }else{
                $("#hMotherName").hide();
            }
        }else{
            if(status == "1"){
                $("#vMotherName").show();
            }else{
                $("#vMotherName").hide();
            }
        }
    }

    dOB = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hDob").show();
            }else{
                $("#hDob").hide();
            }
        }else{
            if(status == "1"){
                $("#vDob").show();
            }else{
                $("#vDob").hide();
            }
        }
    }

    bloodGroup = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hBloodGroup").show();
            }else{
                $("#hBloodGroup").hide();
            }
        }else{
            if(status == "1"){
                $("#vBloodGroup").show();
            }else{
                $("#vBloodGroup").hide();
            }
        }
    }

    addRess = (status) => {
        let pageLayout = $('#pageLayoutStyle').val();
        if(pageLayout == "horizontal"){
            if(status == "1"){
                $("#hAddress").show();
            }else{
                $("#hAddress").hide();
            }
        }else{
            if(status == "1"){
                $(".vAddress").show();
            }else{
                $(".vAddress").hide();
            }
        }
    }
});

// Image Show
    function imageChangeWithBackFile(input,srcBack){
        let pageLayout = $('#pageLayoutStyle').val();
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                if(pageLayout == "horizontal"){
                    $('.horizontal__card').css('background-image','url('+ e.target.result + ')');
                }else{
                    $('.vertical__card').css('background-image','url('+ e.target.result + ')');
                }
            };
                reader.readAsDataURL(input.files[0]);
            }
        }

    function imageChangeWithFile(input,srcId) {

        let pageLayout = $('#pageLayoutStyle').val();
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if(pageLayout == "horizontal"){
                    $('.hImg').css('background-image','url('+ e.target.result + ')');
                }else{
                    $('.vImg').css('background-image','url('+ e.target.result + ')');
                }
            };
                reader.readAsDataURL(input.files[0]);
            }
        }

    function logoImageChangeWithFile(input,srcIdLogo) {
        let pageLayout = $('#pageLayoutStyle').val();
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if(pageLayout == "horizontal"){
                    $('.hLogo').css('background-image','url('+ e.target.result + ')');
                }else{
                    $('.vLogo').css('background-image','url('+ e.target.result + ')');
                }
            };
                reader.readAsDataURL(input.files[0]);
            }
        }

    function signatureImageChangeWithFile(input,srcIdDis) {
        let pageLayout = $('#pageLayoutStyle').val();
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if(pageLayout == "horizontal"){
                    $('.hSign').css('background-image','url('+ e.target.result + ')');
                }else{
                    $('.vSign').css('background-image','url('+ e.target.result + ')');
                }
            };
                reader.readAsDataURL(input.files[0]);
            }
        }

// Delete
    $(document).on("click", "#deleteBackImg", function(event) {
        let pageLayout = $('#pageLayoutStyle').val();
        $('#backgroundImage').removeAttr('placeholder');
        $('#backgroundImage').attr("placeholder", "@lang('admin.background_image')");
        
        if(pageLayout == "horizontal"){
            $('.horizontal__card').css('background-image','url({{asset('public/backEnd/id_card/img/vertical_bg.png')}})');
        }else{
            $('.vertical__card').css('background-image','url({{asset('public/backEnd/id_card/img/horizontal_bg.png')}})');
        }
    });

    $(document).on("click", "#deleteProImg", function(event) {
        let pageLayout = $('#pageLayoutStyle').val();
        $('#profileImage').removeAttr('placeholder');
        $('#profileImage').attr("placeholder", "@lang('admin.profile_image')");

        if(pageLayout == "horizontal"){
            $('.hImg').css('background-image','url({{asset('public/backEnd/id_card/img/thumb.png')}})');
        }else{
            $('.vImg').css('background-image','url({{asset('public/backEnd/id_card/img/thumb.png')}})');
        }
    });

    $(document).on("click", "#deleteLogoImg", function(event) {
        let pageLayout = $('#pageLayoutStyle').val();
        $('#placeholderFileThreeName').removeAttr('placeholder');
        $('#placeholderFileThreeName').attr("placeholder", "@lang('admin.logo')");
        if(pageLayout == "horizontal"){
            $('.hLogo').attr('src',"{{asset('public/backEnd/id_card/img/logo.png')}}");
        }else{
            $('.vLogo').attr('src',"{{asset('public/backEnd/id_card/img/logo.png')}}");
        }
    });

    $(document).on("click", "#deleteSignImg", function(event) {
        let pageLayout = $('#pageLayoutStyle').val();
        $('#placeholderFileFourName').removeAttr('placeholder');
        $('#placeholderFileFourName').attr("placeholder", "@lang('admin.signature')");

        if(pageLayout == "horizontal"){
            $('.hSign').attr('src',"{{asset('public/backEnd/id_card/img/Signature.png')}}");
        }else{
            $('.vSign').attr('src',"{{asset('public/backEnd/id_card/img/Signature.png')}}");
        }
    });
</script>
    
@endpush
