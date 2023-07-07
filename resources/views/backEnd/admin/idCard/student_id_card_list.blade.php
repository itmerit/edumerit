@extends('backEnd.master')
@section('title') 
    @lang('admin.id_card_list')
@endsection
@section('mainContent')
@php
    $breadCrumbs = 
    [
        'h1'=> __('admin.id_card'),
        'bcPages'=> [               
                '<a href="#">'.__('admin.admin_section').'</a>',
                ],
    ];
@endphp
<x-bread-crumb-component :breadCrumbs="$breadCrumbs" />
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    @if(userPermission('create-id-card'))
                        <a href="{{route('create-id-card')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                                @lang('admin.create_id_card')
                        </a>
                    @endif
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('admin.id_card_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('admin.title')</th>
                                        <th>@lang('admin.role')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($id_cards as $key=>$id_card)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$id_card->title}}</td>
                                            <td>
                                                @php
                                                    $role_id= ($id_card->role_id == 2) ? 2 : 0;
                                                    $role_names= App\SmStudentIdCard::roleName($id_card->id);
                                                @endphp
                                                @foreach ($role_names as $key =>$role_name)
                                                    {{$role_name->name}} {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                @endforeach
                                            </td>
                                            <td>
                                               
                                                <x-drop-down>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#previewIdCard{{$id_card->id}}" href="#">
                                                        @lang('admin.preview')
                                                    </a>
                                                    @if(userPermission('student-id-card-edit'))
                                                    <a class="dropdown-item" href="{{route('student-id-card-edit',['id' => $id_card->id])}}">@lang('common.edit')</a>
                                                    @endif
                                                    @if(userPermission('student-id-card-delete'))
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteIdCard{{$id_card->id}}" href="#">
                                                        @lang('common.delete')
                                                    </a>
                                                    @endif
                                                </x-drop-down>
                                                
                                            </td>
                                        </tr>

                                        {{-- Preview Modal Start --}}
                                        <div class="modal fade admin-query" id="previewIdCard{{$id_card->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{$id_card->title}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $roleId= json_decode($id_card->role_id);
                                                        @endphp
                                                        @if(!in_array(3,$roleId))
                                                            @if($id_card->page_layout_style=='horizontal')
                                                                <div id="horizontal" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
                                                                    <div class="horizontal__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/vertical_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 57.15}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 88.89999999999999}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff;">
                                                                        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:8px 12px">
                                                                            <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo)}});height: 30px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                                                                            <div class="qr__img" style="line-height:1.02; width: 30px;">
                                                                                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%; width: 38px; position: absolute; right: 4px; top: 4px;"> --}}
                                                                            </div>
                                                                        </div>

                                                                        <div class="horizontal_card_body" style="line-height:1.02; display:flex; padding-top:{{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm ; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm ; padding-right: {{!empty($id_card->r_space) ? $id_card->r_space : 3}}mm ; padding-left: {{!empty($id_card->l_space) ? $id_card->l_space : 3}}mm ; flex-direction: column;">
                                                                            <div class="thumb hRoundImg hSize photo hImg hRoundImg" style="
                                                                        @if (@$id_card->user_photo_style=='round')
                                                                            {{"border-radius : 50%;"}}
                                                                        @endif
                                                                        background-image: url({{ @$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/uploads/staff/demo/staff.jpg') }}); background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 21.166666667}}mm; flex: 80px 0 0; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 21.166666667}}mm; margin: auto; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;"></div>
                                                                            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:25px; margin-bottom:10px">
                                                                                    <div class="card_text_left hId">
                                                                                        @if($id_card->student_name==1)
                                                                                            <div id="hName">
                                                                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;"> @if(in_array(2,$roleId)) Student @else Staff @endif Name </h4>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if($id_card->admission_no==1 )
                                                                                            <div id="hAdmissionNumber">
                                                                                                @if($role_id==2)
                                                                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('student.admission_no') : 001</h3>
                                                                                                @else
                                                                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('admin.id') : 001</h3>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endif
                                                                                        @if(in_array(2,$roleId))
                                                                                            @if(!moduleStatusCheck('University'))
                                                                                                @if($id_card->class==1)
                                                                                                    <div id="hClass">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('common.class') : One (A)</h3>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endif
                                                                                            @if(moduleStatusCheck('University'))
                                                                                                @if($id_card->un_session==1)
                                                                                                    <div id="hSession">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.session') : 2022-2026</h3>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if($id_card->un_faculty==1)
                                                                                                    <div id="hFaculty">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.faculty') : FIST</h3>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if($id_card->un_department==1)
                                                                                                    <div id="hDepartment">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.department') : Computer Science</h3>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if($id_card->un_academic==1)
                                                                                                    <div id="hAcademic">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.academic') : 2022</h3>
                                                                                                    </div>
                                                                                                @endif

                                                                                                @if($id_card->un_semester==1)
                                                                                                    <div id="hSemester">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.semester') : Summer</h3>
                                                                                                    </div>
                                                                                                @endif

                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card_text_head hStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px">
                                                                                    <div class="card_text_left">
                                                                                        @if($id_card->father_name ==1)
                                                                                            <div id="hFatherName">
                                                                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('student.father_name') : Mr. Father</h4>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if($id_card->mother_name==1)
                                                                                            <div id="hMotherName">
                                                                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; font-weight:500">@lang('student.mother_name') : Mrs. Mother</h4>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px">
                                                                                    <div class="card_text_left">
                                                                                        @if($id_card->dob==1)
                                                                                            <div id="hDob">
                                                                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('common.date_of_birth') :  Dec 25 , 2022</h3>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if(in_array(2,$roleId))
                                                                                            @if($id_card->blood==1)
                                                                                                <div id="hBloodGroup">
                                                                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('student.blood_group') : B+</h3>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px">
                                                                                    @if($id_card->student_address==1)
                                                                                        <div class="card_text_left" id="hAddress">
                                                                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">{{ generalSetting()->address }}</h3>
                                                                                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                                                                            <div class="singnature_img signPhoto hSign" style="background-image:url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }});line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($id_card->page_layout_style=='vertical')
                                                                <div id="vertical"  style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;">
                                                                    <div class="vertical__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/horizontal_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 86}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 54}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
                                                                        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
                                                                            <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo)}});background-size: cover; height: 30px;background-position: center center; background-repeat: no-repeat;"></div>
                                                                            <div class="qr__img" style="line-height:1.02; width: 48px; position: absolute; right: 4px; top: 4px;">
                                                                                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%;"> --}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical_card_body" style="line-height:1.02; display:flex; padding-top: {{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm; padding-right: {{!empty($id_card->r_space) ? $id_card->r_space : 3}}mm ; padding-left: {{!empty($id_card->l_space) ? $id_card->l_space : 3}}mm; align-items: center;">
                                                                            <div class="thumb vSize vSizeX photo vImg vRoundImg" style="background-image: url({{ @$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/uploads/staff/demo/staff.jpg') }});
                                                                        @if (@$id_card->user_photo_style=='round')
                                                                            {{"border-radius : 50%;"}}
                                                                        @endif
                                                                        line-height:1.02; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 13.229166667}}mm; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 13.229166667}}mm; flex-basis: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 13.229166667}}mm; flex-grow: 0; flex-shrink: 0; margin-right: 20px; background-size: cover; background-position: center center;"></div>
                                                                            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                                                                                <div class="card_text_head" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px">
                                                                                    <div class="card_text_left vId">
                                                                                        @if($id_card->student_name==1)
                                                                                            <div id="vName">
                                                                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">@if(in_array(2,$roleId)) Student @else Staff @endif Name </h3>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if($id_card->admission_no==1)
                                                                                            <div id="vAdmissionNumber">
                                                                                                @if(in_array(2,$roleId))
                                                                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px;">@lang('student.admission_no') : 001</h4>
                                                                                                @else
                                                                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px;">@lang('admin.id') : 001</h4>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endif
                                                                                        @if(in_array(2,$roleId))

                                                                                            @if(moduleStatusCheck('University'))
                                                                                                @if($id_card->un_session==1)
                                                                                                    <div id="vSession">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">@lang('university::un.session') : 2022-2026</h3>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if($id_card->un_faculty==1)
                                                                                                    <div id="vFaculty">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">@lang('university::un.faculty') : FIST</h3>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if($id_card->un_department==1)
                                                                                                    <div id="vDepartment">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">@lang('university::un.department') : Computer Science</h3>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if($id_card->un_academic==1)
                                                                                                    <div id="vAcademic">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">@lang('university::un.academic') : 2022</h3>
                                                                                                    </div>
                                                                                                @endif

                                                                                                @if($id_card->un_semester==1)
                                                                                                    <div id="vSemester">
                                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">@lang('university::un.semester') : Summer</h3>
                                                                                                    </div>
                                                                                                @endif



                                                                                            @else
                                                                                                @if($id_card->class==1)
                                                                                                    <div id="vClass">
                                                                                                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">@lang('common.class') :  One (A)</h4>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="card_text_right">
                                                                                        </br>
                                                                                        @if($id_card->dob==1)
                                                                                            <div id="vDob">
                                                                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">@lang('common.date_of_birth') :   jan 21. 2030</h3>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if(in_array(2,$roleId))
                                                                                            @if($id_card->blood==1)
                                                                                                <div id="vBloodGroup">
                                                                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">@lang('student.blood_group') : B+</h3>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card_text_head vStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px">
                                                                                    <div class="card_text_left">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px">
                                                                                    <div class="card_text_left">
                                                                                        @if($id_card->father_name ==1)
                                                                                            <div id="vFatherName">
                                                                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('student.father_name') : Mr. Father</h3>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if($id_card->mother_name==1)
                                                                                            <div id="vMotherName">
                                                                                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('student.mother_name') : Mrs. Mother</h3>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="card_text_right">

                                                                                    </div>
                                                                                </div>
                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px">
                                                                                    @if($id_card->student_address==1)
                                                                                        <div class="card_text_left vAddress">
                                                                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase;">{{ generalSetting()->address }} </h3>
                                                                                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                                                                            <div class="singnature_img signPhoto vSign" style="background-image: url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }}); line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @if($id_card->page_layout_style=='horizontal')
                                                                <div id="gHorizontal" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
                                                                    <div class="horizontal__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/vertical_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 55}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 106}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff;">
                                                                        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:8px 12px">
                                                                            <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo)}});height: 30px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                                                                            <div class="qr__img" style="line-height:1.02; width: 30px;">
                                                                                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%; width: 38px; position: absolute; right: 4px; top: 4px;"> --}}
                                                                            </div>
                                                                        </div>

                                                                        <div class="horizontal_card_body" style="line-height:1.02; display:block; padding-top:{{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm ; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm ; padding-right: {{!empty($id_card->r_space) ? $id_card->r_space : 3}}mm ; padding-left: {{!empty($id_card->l_space) ? $id_card->l_space : 3}}mm; flex-direction: column;">
                                                                            <div class="thumb hSize photo hImg hRoundImg" style="
                                                                        @if (@$id_card->user_photo_style=='round')
                                                                            {{"border-radius : 50%;"}}
                                                                        @endif
                                                                         background-image: url({{ @$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/uploads/staff/demo/staff.jpg') }});background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; flex: 80px 0 0; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 21.166666667}}mm; flex: 80px 0 0; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 21.166666667}}mm; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;"></div>
                                                                            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:25px; margin-bottom:10px">
                                                                                    <div class="card_text_left hId">
                                                                                        @if($id_card->student_name==1)
                                                                                            <div id="gHName">
                                                                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">Guardian Name</h4>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card_text_head hStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px">
                                                                                    <div class="card_text_left">
                                                                                        {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h3> --}}
                                                                                        @if($id_card->phone_number == 1)
                                                                                            <div id="hPhoneNumber">
                                                                                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">phone : 0123456789</h4>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px">
                                                                                    <div class="child__thumbs" style="display:flex; align-items: center; margin: 15px 0 20px 0; display: flex;
                                                                                    align-items: flex-start;
                                                                                    margin: 15px 0 2px 0;
                                                                                    justify-content: space-between;">
                                                                                        <div class="single__child" style="text-align: center; flex: 45px 0 0; ">
                                                                                            <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 45px;
                                                                                        flex: 45px 0 0;
                                                                                        height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                                                                            </div>
                                                                                            <p style="font-size:12px; font-weight:400">Child 01</p>
                                                                                        </div>
                                                                                        <div class="single__child" style="text-align: center;flex: 45px 0 0;">
                                                                                            <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 45px;
                                                                                        flex: 45px 0 0;
                                                                                        height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                                                                            </div>
                                                                                            <p style="font-size:12px; font-weight:400">Child 02</p>
                                                                                        </div>
                                                                                        <div class="single__child" style="text-align: center;flex: 45px 0 0;">
                                                                                            <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 45px;
                                                                                        flex: 45px 0 0;
                                                                                        height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                                                                            </div>
                                                                                            <p style="font-size:12px; font-weight:400">Child 03</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- <div class="card_text_right">
                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase;font-weight:500; text-align:center;">B+</h3>
                                                                                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Blood Group</h4>
                                                                                    </div> --}}
                                                                                </div>
                                                                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px">
                                                                                    @if($id_card->student_address==1)
                                                                                        <div class="card_text_left" id="gHAddress">
                                                                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">
                                                                                                {{ generalSetting()->address }}</h3>
                                                                                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                                                                            <div class="singnature_img signPhoto hSign" style="background-image:url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }});line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($id_card->page_layout_style=='vertical')
                                                                <div class="vertical__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/horizontal_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 106}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 55}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
                                                                    <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
                                                                        <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo)}});background-size: cover; height: 30px;background-position: center center; background-repeat: no-repeat;"></div>
                                                                        <div class="qr__img" style="line-height:1.02; width: 48px; position: absolute; right: 4px; top: 4px;">
                                                                            {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%;"> --}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical_card_body" style="line-height:1.02; display:flex; padding-top:{{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm ; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm ; padding-right: {{!empty($id_card->r_space) ? $id_card->r_space : 3}}mm ; padding-left: {{!empty($id_card->l_space) ? $id_card->l_space : 3}}mm;  align-items: center;">
                                                                        <div class="thumb vSize vSizeX photo vImg vRoundImg" style="
                                                                    @if (@$id_card->user_photo_style=='round')
                                                                        {{"border-radius : 50%;"}}
                                                                    @endif
                                                                    background-image: url({{ @$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/uploads/staff/demo/staff.jpg') }}); line-height:1.02; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 13.229166667}}mm; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 13.229166667}}mm; flex-basis: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 13.229166667}}mm; flex-grow: 0; flex-shrink: 0; margin-right: 20px; background-size: cover; background-position: center center;"></div>
                                                                        <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                                                                            <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:0px">
                                                                                <div class="card_text_left vId">
                                                                                    @if($id_card->student_name==1)
                                                                                        <div id="gVName">
                                                                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">Guardian Name</h3>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="card_text_right">
                                                                                    </br>
                                                                                    @if($id_card->phone_number == 1)
                                                                                        <div id="gVAddress">
                                                                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">Phone : 0123456789</h3>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            {{-- <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px">
                                                                                <div class="card_text_left">
                                                                                    <div id="phoneNumber">
                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : InfixEdu</h3>
                                                                                    </div>
                                                                                    <div id="vMotherName">
                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Mother Name : InfixEdu</h3>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card_text_right">
                                                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase; ">American</h3>
                                                                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Nationality</h4>
                                                                                </div>
                                                                            </div> --}}
                                                                            <div class="child__thumbs" style="display:flex; align-items: center; margin:  0px 0 0px 0; display: flex;
                                                                            align-items: flex-start;
                                                                            margin: 0px 0 0px 0;
                                                                            justify-content: start;">
                                                                                <div class="single__child" style="text-align: center; flex: 75px 0 0; ">
                                                                                    <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 45px;
                                                                                flex: 45px 0 0;
                                                                                height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                                                                    </div>
                                                                                    <p style="font-size:12px; font-weight:400; margin-bottom: 0;">Child 01</p>
                                                                                </div>
                                                                                <div class="single__child" style="text-align: center;flex: 75px 0 0;">
                                                                                    <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 45px;
                                                                                flex: 45px 0 0;
                                                                                height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                                                                    </div>
                                                                                    <p style="font-size:12px; font-weight:400; margin-bottom: 0;">Child 02</p>
                                                                                </div>
                                                                                <div class="single__child" style="text-align: center;flex: 75px 0 0;">
                                                                                    <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 45px;
                                                                                flex: 45px 0 0;
                                                                                height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                                                                    </div>
                                                                                    <p style="font-size:12px; font-weight:400; margin-bottom: 0;">Child 03</p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px">
                                                                                @if($id_card->student_address==1)
                                                                                    <div class="card_text_left gVAddress">
                                                                                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase;">{{ generalSetting()->address }} </h3>
                                                                                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Address</h4>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                                                                        <div class="singnature_img signPhoto vSign" style="background-image: url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }}); line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Preview Modal End --}}

                                        {{-- Delete Modal Start --}}
                                        <div class="modal fade admin-query" id="deleteIdCard{{$id_card->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('common.delete_id_card')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">
                                                                @lang('common.cancel')
                                                            </button>
                                                            {{ Form::open(['route' =>'student-id-card-delete', 'method' => 'POST']) }}
                                                                <input type="hidden" name="id" value="{{$id_card->id}}">
                                                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                            {{ Form::close() }}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- Delete Modal End --}}
                                        @endforeach
                                </tbody>
                            </table>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
