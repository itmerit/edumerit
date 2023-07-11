<div id="gHorizontal" class="d-none" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
    <div class="horizontal__card" style="line-height:1.02; background-image: url({{asset('public/backEnd/id_card/img/vertical_bg.png')}}); width: 60mm; height: 106mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff;">
        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:8px 12px">
            <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url('{{ generalSetting()->logo }}');height: 30px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
            <div class="qr__img" style="line-height:1.02; width: 30px;">
                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%; width: 38px; position: absolute; right: 4px; top: 4px;"> --}}
            </div>
        </div>

        <div class="horizontal_card_body" style="line-height:1.02; display:block; padding-top: 2.5mm; padding-bottom: 2.5mm; padding-right: 3mm ; padding-left: 3mm; flex-direction: column;">
            <div class="thumb hSize photo hImg hRoundImg" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 21.166666667mm; flex: 80px 0 0; height: 21.166666667mm; margin: auto; margin-top: 5px; border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;"></div>
            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:25px; margin-bottom:10px">
                    <div class="card_text_left hId">
                        <div id="gHName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">Guardian Name</h4>
                        </div>
                    </div>
                </div>

                <div class="card_text_head hStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                    <div class="card_text_left">
                        {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h3> --}}
                        <div id="hPhoneNumber">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">phone : 0123456789</h4>
                        </div>
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
                    <div class="card_text_left" id="gHAddress">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">
                            {{ generalSetting()->address }}</h3>
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

<div id="gVertical" class="d-none" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;">
    <div class="vertical__card" style="line-height:1.02; background-image: url({{asset('public/backEnd/id_card/img/horizontal_bg.png')}}); width: 106mm; height: 60mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
            <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url('{{ generalSetting()->logo }}');background-size: cover; height: 30px;background-position: center center; background-repeat: no-repeat;"></div>
            <div class="qr__img" style="line-height:1.02; width: 48px; position: absolute; right: 4px; top: 4px;">
                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%;"> --}}
            </div>
        </div>
        <div class="vertical_card_body" style="line-height:1.02; display:flex; padding-top: 2.5mm; padding-bottom: 2.5mm; padding-right: 3mm ; padding-left: 3mm; align-items: center;">
            <div class="thumb vSize vSizeX photo vImg vRoundImg" style="background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}'); line-height:1.02; width: 13.229166667mm; height: 13.229166667mm; flex-basis: 13.229166667mm; flex-grow: 0; flex-shrink: 0; margin-right: 20px; background-size: cover; background-position: center center;"></div>
            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:0px"> 
                    <div class="card_text_left vId">
                        <div id="gVName">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">Guardian Name</h3>
                        </div>
                    </div>
                    <div class="card_text_right">
                        <br>
                        <div id="gVAddress">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">Phone : 0123456789</h3>
                        </div>
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
                    <div class="card_text_left gVAddress">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase;">
                            {{ generalSetting()->address }} </h3>
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