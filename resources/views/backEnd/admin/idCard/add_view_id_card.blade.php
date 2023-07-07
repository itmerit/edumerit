<div id="horizontal" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
    <div class="horizontal__card" style="line-height:1.02; background-image: url({{asset('public/backEnd/id_card/img/vertical_bg.png')}}); width: 57.15mm; height: 88.89999999999999mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff;">
        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:8px 12px">
            <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url('{{asset(generalSetting()->logo)}}');height: 30px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
            <div class="qr__img" style="line-height:1.02; width: 30px;">
                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%; width: 38px; position: absolute; right: 4px; top: 4px;"> --}}
            </div>
        </div>

        <div class="horizontal_card_body" style="line-height:1.02; display:block; padding-top: 2.5mm; padding-bottom: 2.5mm; padding-right: 3mm ; padding-left: 3mm; flex-direction: column;">
            <div class="thumb hSize photo hImg hRoundImg" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 21.166666667mm; flex: 80px 0 0; height: 21.166666667mm; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;"></div>
            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:25px; margin-bottom:10px">
                    <div class="card_text_left hId">
                        <div id="hName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;" class="role_name_text">
                                Student Name</h4>
                        </div>
                        <div id="hAdmissionNumber">
                            <h3 class="hStaffId" style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Admission No : 001</h3>
                        </div>
                        @if(moduleStatusCheck('University'))
                        <div id="hSession">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Session : 2022-2024 </h3>
                        </div>
                        <div id="hFaculty">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Faculty : FIST</h3>
                        </div>
                        <div id="hDepartment">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Department :Computer Science</h3>
                        </div>
                        <div id="hAcademic">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Academic : 2022</h3>
                        </div>
                        <div id="hSemester">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Semester : Summer</h3>
                        </div>
                        @else
                        <div id="hClass">
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
                        <div id="hFatherName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Mr. Father</h4>
                        </div>
                        <div id="hMotherName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; font-weight:500">Mother Name : Mrs. Mother</h4>
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
                    </div>
                    {{-- <div class="card_text_right">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase;font-weight:500; text-align:center;">B+</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Blood Group</h4>
                    </div> --}}
                </div>
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                    <div class="card_text_left" id="hAddress">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">
                            {{  generalSetting()->address }}</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
            <div class="singnature_img signPhoto hSign" style="background-image:url('{{asset('public/backEnd/id_card/img/Signature.png')}}');line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
        </div>
    </div>
</div>

<div id="vertical" class="d-none" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;">
    <div class="vertical__card" style="line-height:1.02; background-image: url({{asset('public/backEnd/id_card/img/horizontal_bg.png')}}); width: 86mm; height: 54mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
            <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url('{{asset(generalSetting()->logo)}}');background-size: cover; height: 30px;background-position: center center; background-repeat: no-repeat;"></div>
            <div class="qr__img" style="line-height:1.02; width: 48px; position: absolute; right: 4px; top: 4px;">
                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%;"> --}}
            </div>
        </div>
        <div class="vertical_card_body" style="line-height:1.02; display:flex; padding-top: 2.5mm; padding-bottom: 2.5mm; padding-right: 3mm ; padding-left: 3mm; align-items: center;">
            <div class="thumb vSize vSizeX photo vImg vRoundImg" style="background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}'); line-height:1.02; width: 13.229166667mm; height: 13.229166667mm; flex-basis: 13.229166667mm; flex-grow: 0; flex-shrink: 0; margin-right: 20px; background-size: cover; background-position: center center;"></div>
            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                    <div class="card_text_left vId">
                        <div id="vName">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;" class="role_name_text"> Student Name</h3>
                        </div>
                        <div id="vAdmissionNumber">
                            <h4 class="vStaffId" style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px;">Admission No : 001</h4>
                        </div>
                        @if(moduleStatusCheck('University'))
                        <div id="vSession">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Session : 2022-2024 </h3>
                        </div>
                        <div id="vFaculty">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Faculty : FIRST</h3>
                        </div>
                        <div id="vDepartment">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Department :Computer Science</h3>
                        </div>
                        <div id="vAcademic">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Academic : 2022</h3>
                        </div>
                        <div id="vSemester">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Semester : Summer</h3>
                        </div>
                        @else
                        <div id="vClass">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">Class : One (A)</h4>
                        </div>
                        @endif
                    </div>
                    <div class="card_text_right">
                        </br>
                        <div id="vDob">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">DOB : jan 21. 2030</h3>
                        </div>
                        <div id="vBloodGroup">
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
                        <div id="vFatherName">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Mr. Father</h3>
                        </div>
                        <div id="vMotherName">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Mother Name : Mrs. Mother</h3>
                        </div>
                    </div>
                    <div class="card_text_right">
                        {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase; ">American</h3> --}}
                        {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Nationality</h4> --}}
                    </div>
                </div>
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                    <div class="card_text_left vAddress">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase;">  {{  generalSetting()->address }} </h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Address</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
            <div class="singnature_img signPhoto vSign" style="background-image: url('{{asset('public/backEnd/id_card/img/Signature.png')}}'); line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;">
            </div>
        </div>
    </div>
</div>