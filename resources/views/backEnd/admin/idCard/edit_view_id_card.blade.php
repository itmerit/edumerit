
    <div id="horizontal" class="{{($id_card->page_layout_style == "horizontal")? 'd-block':'d-none'}}" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
        <div class="horizontal__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/vertical_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 57.15}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 88.89999999999999}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff;">
            <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:8px 12px">
                <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo )}});height: 30px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                <div class="qr__img" style="line-height:1.02; width: 30px;">
                    {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%; width: 38px; position: absolute; right: 4px; top: 4px;"> --}}
                </div>
            </div>

            <div class="horizontal_card_body" style="line-height:1.02; display:block; padding-top:{{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm ; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm ; padding-right: {{!empty($id_card->r_space) ? $id_card->r_space : 3}}mm ; padding-left: {{!empty($id_card->l_space) ? $id_card->l_space : 3}}mm ; flex-direction: column;">
                <div class="thumb hSize photo hImg hRoundImg" style=" 
                @if (@$id_card->user_photo_style=='round')
                    {{"border-radius : 50%;"}}
                @endif
                background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->profile_image) : asset('public/backEnd/id_card/img/thumb.png') }});background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 21.166666667}}mm; flex: 80px 0 0; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 21.166666667}}mm; margin: auto; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;"></div>
                <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                    <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:25px; margin-bottom:10px">
                        <div class="card_text_left hId">
                            <div id="hName" style="{{($id_card->student_name==0)? 'display:none':''}}">
                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;" class="role_name_text">  @if(in_array(2,$roleId)) Student @else Staff @endif  Name</h4>
                            </div>
                            <div id="hAdmissionNumber" style="{{($id_card->admission_no==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Admission No : 001</h3>
                            </div>
                            @if(moduleStatusCheck('University'))
                            <div id="hSession"  style="{{($id_card->un_session==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Session : 2022-2024 </h3>
                            </div>
                            <div id="hFaculty" style="{{($id_card->un_faculty==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Faculty : FIST</h3>
                            </div>
                            <div id="hDepartment" style="{{($id_card->un_department==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Department :Computer Science</h3>
                            </div>
                            <div id="hAcademic" style="{{($id_card->un_academic==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Academic : 2022</h3>
                            </div>
                            <div id="vSemester" style="{{($id_card->un_semester==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Semester : Summer</h3>
                            </div>
                            @else
                            <div id="hClass" style="{{($id_card->class==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Class : One (A)</h3>
                            </div>
                            @endif
                        </div>
                        {{-- <div class="card_text_right">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:9px; font-weight:500;text-transform: uppercase; font-weight:500">jan 21. 2030</h3>
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; text-transform: uppercase; font-weight:500 ">Date of iSSued</h4>
                        </div> --}}
                    </div>

                    <div class="card_text_head hStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                        <div class="card_text_left">
                            {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h3> --}}
                            <div id="hFatherName" style="{{($id_card->father_name==0)? 'display:none':''}}">
                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Mr. Father</h4>
                            </div>
                            <div id="hMotherName" style="{{($id_card->mother_name==0)? 'display:none':''}}">
                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; font-weight:500">Mother Name : Mr. Mother</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                        <div class="card_text_left">
                            <div id="hDob" style="{{($id_card->dob==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Date of Birth : Dec 25 , 2022</h3>
                            </div>
                            <div id="hBloodGroup"{{($id_card->blood==0)? 'display:none':''}}>
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Blood Group : B+</h3>
                            </div>
                        </div>
                        {{-- <div class="card_text_right">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase;font-weight:500; text-align:center;">B+</h3>
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Blood Group</h4>
                        </div> --}}
                    </div>
                    <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                        <div class="card_text_left" id="hAddress" style="{{($id_card->student_address==0)? 'display:none':''}}">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">{{  generalSetting()->address }}</h3>
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                <div class="singnature_img signPhoto hSign" style="background-image:url({{ @$id_card->signature != "" ? asset(@$id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }});line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
            </div>
        </div>
    </div>

    <div id="vertical" class="{{($id_card->page_layout_style == "vertical")? 'd-block':'d-none'}}" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;">
        <div class="vertical__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/horizontal_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 86}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 54}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
            <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
                <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo)}});background-size: cover; height: 30px;background-position: center center; background-repeat: no-repeat;"></div>
                <div class="qr__img" style="line-height:1.02; width: 48px; position: absolute; right: 4px; top: 4px;">
                    {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%;"> --}}
                </div>
            </div>
            <div class="vertical_card_body" style="line-height:1.02; display:flex; padding-top: {{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm; padding-right: {{!empty($id_card->r_space) ? $id_card->r_space : 3}}mm ; padding-left: {{!empty($id_card->l_space) ? $id_card->l_space : 3}}mm; align-items: center;">
                <div class="thumb vSize vSizeX photo vImg vRoundImg" style="
                @if (@$id_card->user_photo_style=='round')
                    {{"border-radius : 50%;"}}
                @endif
                background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->profile_image) : asset('public/backEnd/id_card/img/thumb.png') }}); line-height:1.02; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 13.229166667}}mm; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 13.229166667}}mm; flex-basis: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 13.229166667}}mm; flex-grow: 0; flex-shrink: 0; margin-right: 20px; background-size: cover; background-position: center center;"></div>
                <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                    <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                        <div class="card_text_left vId">
                            <div id="vName" style="{{($id_card->student_name==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;" class="role_name_text">   @if(in_array(2,$roleId)) Student @else Staff @endif Name</h3>
                            </div>
                            <div id="vAdmissionNumber" style="{{($id_card->admission_no==0)? 'display:none':''}}">
                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px;">Admission No : 001</h4>
                            </div>
                            @if(moduleStatusCheck('University'))
                            <div id="hSession"  style="{{($id_card->un_session==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Session : 2022-2024 </h3>
                            </div>
                            <div id="hFaculty" style="{{($id_card->un_faculty==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Faculty : FIST</h3>
                            </div>
                            <div id="hDepartment" style="{{($id_card->un_department==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Department :Computer Science</h3>
                            </div>
                            <div id="hAcademic" style="{{($id_card->un_academic==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Academic : 2022</h3>
                            </div>
                            <div id="vSemester" style="{{($id_card->un_semester==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Semester : Summer</h3>
                            </div>
                            @else
                            <div id="vClass" style="{{($id_card->class==0)? 'display:none':''}}">
                                <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">Class : One (A)</h4>
                            </div>
                            @endif
                        </div>
                        <div class="card_text_right">
                            </br>
                            <div id="vDob" style="{{($id_card->dob==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">DOB : jan 21. 2030</h3>
                            </div>
                            <div id="vBloodGroup" style="{{($id_card->blood==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">Blood Group : B+</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card_text_head vStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                        <div class="card_text_left">
                            {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase;font-weight:500">@lang('common.name')</h4> --}}
                        </div>
                    </div>

                    <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                        <div class="card_text_left">
                            <div id="vFatherName" style="{{($id_card->father_name==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Mr. Father</h3>
                            </div>
                            <div id="vMotherName" style="{{($id_card->mother_name==0)? 'display:none':''}}">
                                <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Mother Name : Mrs. Mother</h3>
                            </div>
                        </div>
                        <div class="card_text_right">
                            {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase; ">American</h3> --}}
                            {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Nationality</h4> --}}
                        </div>
                    </div>
                    <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                        <div class="card_text_left vAddress" style="{{($id_card->student_address==0)? 'display:none':''}}">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase;">  {{  generalSetting()->address }} </h3>
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Address</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
                <div class="singnature_img signPhoto vSign" style="background-image: url({{ @$id_card->signature != "" ? asset(@$id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }}); line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;">
                </div>
            </div>
        </div>
    </div>
