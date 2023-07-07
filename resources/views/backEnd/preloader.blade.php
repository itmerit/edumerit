@if(generalSetting()->preloader_status)
    @if(generalSetting()->preloader_type==2)
        <div class="preloader" dir="ltr">
            <img style="width: 200px" src="{{asset(generalSetting()->preloader_image)}}" alt="preloader">
        </div>
    @else
        @if(!generalSetting()->preloader_style||generalSetting()->preloader_style==1)
            <div class="preloader" dir="ltr">
                <div class="circle circle1"></div>
            </div>
        @elseif(generalSetting()->preloader_style==2)


            <div class="preloader" dir="ltr">
                <div class="circle circle2"></div>
            </div>
        @elseif(generalSetting()->preloader_style==3)
            <div class="preloader" dir="ltr">
                <div class="circle circle3 c31"></div>
                <div class="circle circle3 c32"></div>
            </div>
        @elseif(generalSetting()->preloader_style==4)
            <div class="preloader" dir="ltr">
                <div class="circle circle4 c41"></div>
                <div class="circle circle4 c42"></div>
                <div class="circle circle4 c43"></div>
                <div class="circle circle4 c44"></div>
            </div>
        @elseif(generalSetting()->preloader_style==5)
            <div class="preloader" dir="ltr">
                <div class="circle circle5 c51"></div>
            </div>
        @elseif(generalSetting()->preloader_style==6)
            <div class="preloader" dir="ltr">
                <div class="circle circle6 c61"></div>
                <div class="circle circle6 c62"></div>
                <div class="circle circle6 c63"></div>
                <div class="circle circle6 c64"></div>
                <div class="circle circle4 c65"></div>
            </div>
        @elseif(generalSetting()->preloader_style==7)
            <div class="preloader" dir="ltr">
                <div class="circle circle7 c71"></div>
                <div class="circle circle7 c72"></div>
                <div class="circle circle7 c73"></div>
                <div class="circle circle7 c74"></div>
                <div class="circle circle7 c75"></div>
            </div>
        @elseif(generalSetting()->preloader_style==8)
            <div class="preloader" dir="ltr">
                <div class="circle circle8 c81"></div>
                <div class="circle circle8 c82"></div>
                <div class="circle circle8 c83"></div>
                <div class="circle circle8 c84"></div>
                <div class="circle circle8 c85"></div>
            </div>
        @elseif(generalSetting()->preloader_style==9)
            <div class="preloader" dir="ltr">
                <div class="circle circle9 c91"></div>
                <div class="circle circle9 c92"></div>
                <div class="circle circle9 c93"></div>
                <div class="circle circle9 c94"></div>
                <div class="circle circle9 c95"></div>
            </div>
        @elseif(generalSetting()->preloader_style==10)
            <div class="preloader" dir="ltr">
                <div class="circle circle10 c101"></div>
                <div class="circle circle10 c102"></div>
                <div class="circle circle10 c103"></div>
                <div class="circle circle10 c104"></div>
                <div class="circle circle10 c105"></div>
            </div>
        @elseif(generalSetting()->preloader_style==11)

            <div class="preloader" dir="ltr">
                <div class="circle circle11 c111"></div>
                <div class="circle circle11 c112"></div>
                <div class="circle circle11 c113"></div>
                <div class="circle circle11 c114"></div>
                <div class="circle circle11 c115"></div>
            </div>
        @elseif(generalSetting()->preloader_style==12)

            <div class="preloader" dir="ltr">
                <div class="circle circle12 c121"></div>
                <div class="circle circle12 c122"></div>
                <div class="circle circle12 c123"></div>
                <div class="circle circle12 c124"></div>
                <div class="circle circle12 c125"></div>
            </div>
        @elseif(generalSetting()->preloader_style==13)

            <div class="preloader" dir="ltr">
                <div class="circle circle13 c131"></div>
            </div>
        @elseif(generalSetting()->preloader_style==14)

            <div class="preloader" dir="ltr">
                <div class="circle circle14 c141"></div>
            </div>
        @elseif(generalSetting()->preloader_style==15)

            <div class="preloader" dir="ltr">
                <div class="circle circle15 c151"></div>
            </div>
        @elseif(generalSetting()->preloader_style==16)


            <div class="preloader" dir="ltr">
                <div class="dot dot1 d11"></div>
                <div class="dot dot1 d12"></div>
                <div class="dot dot1 d13"></div>
            </div>
        @elseif(generalSetting()->preloader_style==17)

            <div class="preloader" dir="ltr">
                <div class="dot dot2 d21"></div>
                <div class="dot dot2 d22"></div>
                <div class="dot dot2 d23"></div>
            </div>
        @elseif(generalSetting()->preloader_style==18)

            <div class="preloader" dir="ltr">
                <div class="dot dot3"></div>
                <div class="dot dot3 dot31"></div>
            </div>
        @elseif(generalSetting()->preloader_style==19)

            <div class="preloader" dir="ltr">
                <div class="dot dot4"></div>
                <div class="dot dot4 dot41"></div>
            </div>
        @elseif(generalSetting()->preloader_style==20)

            <div class="preloader" dir="ltr">
                <div class="dot dot5 dot50"></div>
                <div class="dot dot5 dot51"></div>
                <div class="dot dot5 dot52"></div>
                <div class="dot dot5 dot53"></div>
            </div>
        @elseif(generalSetting()->preloader_style==21)

            <div class="preloader" dir="ltr">
                <div class="dot dot6 dot60"></div>
                <div class="dot dot6 dot61"></div>
                <div class="dot dot6 dot62"></div>
            </div>
        @elseif(generalSetting()->preloader_style==22)

            <div class="preloader" dir="ltr">
                <div class="dot dot7 dot70"></div>
                <div class="dot dot7 dot71"></div>
                <div class="dot dot7 dot72"></div>
                <div class="dot dot7 dot73"></div>
            </div>
        @elseif(generalSetting()->preloader_style==23)

            <div class="preloader" dir="ltr">
                <div class="dot dot8 dot80"></div>
                <div class="dot dot8 dot81"></div>
                <div class="dot dot8 dot82"></div>
            </div>
        @elseif(generalSetting()->preloader_style==24)

            <div class="preloader" dir="ltr">
                <div class="dot dot9"></div>
                <div class="dot dot9 dot91"></div>
                <div class="dot dot9 dot92"></div>
                <div class="dot dot9 dot93"></div>
            </div>
        @endif
    @endif
@endif
