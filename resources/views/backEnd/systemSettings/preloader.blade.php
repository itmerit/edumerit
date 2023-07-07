@extends('backEnd.master')
@section('title')
    @lang('system_settings.Preloader Settings')
@endsection
@section('mainContent')
    <style>

        .preloaderr {
            border: 1px solid #ccc;
            width: 200px;
            height: 200px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        #preloaderStyleDiv input[type="radio"][id^="checkbox"] {
            display: none;
        }

        #preloaderStyleDiv label {
            width: 100%;
            padding: 10px;
            display: block;
            position: relative;
            margin: 0px;
            cursor: pointer;
        }

        #preloaderStyleDiv label:before {
            background: linear-gradient(90deg, var(--gradient_1) .47%, #c738d8);
            box-shadow: 0 5px 10px rgb(108 39 255 / 25%);
            transition: .3s;
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: 0;
            left: -2.5px;
            z-index: 100;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 25px;
            transition-duration: 0.4s;
            transform: scale(0);
        }


        #preloaderStyleDiv :checked + label:before {
            content: "\E64C";
            font-family: themify;
            background-color: grey;
            transform: scale(1);
        }

        #preloaderStyleDiv :checked + label img {
            transform: scale(0.9);
            z-index: -1;
        }

        .preloaderr.active {
            border: 3px solid var(--gradient_1) !important;
        }

    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{__('system_settings.Preloader Settings')}} </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">{{__('system_settings.Settings')}} </a>
                    <a href="#">{{__('system_settings.Preloader Settings')}}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header">
                        <div class="main-title d-flex">
                            <h3 class="mb-30">
                                @lang('system_settings.Preloader Settings')
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="white-box">
                        <div class="row">

                            <div class="col-lg-12">
                                <!-- tab-content  -->
                                <div class="tab-content " id="myTabContent">
                                    <!-- General -->
                                    <div class="tab-pane fade white_box_30px show active" id="Activation"
                                         role="tabpanel" aria-labelledby="Activation-tab">
                                        <div class="main-title mb-25">


                                            <form action="{{route('setting.preloader')}}" id="" method="POST"
                                                  enctype="multipart/form-data">

                                                @csrf

                                                <div class="single_system_wrap">
                                                    <div class="row">


                                                        <div class="col-xl-12">
                                                            <div class="primary_input mb-25">
                                                                <div class="row">


                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12  ">
                                                                                        <label
                                                                                                class="primary_input_label"
                                                                                                for=""> {{__('system_settings.Preloader Status')}} </label>
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-25">
                                                                                            <div class="mr-20">
                                                                                                <input type="radio" name="preloader_status" id="preloader_status_enable" value="1" class="common-checkbox class-checkbox" @if (generalSetting()->preloader_status == 1) checked @endif>
                                                                                                <label for="preloader_status_enable">{{ __('system_settings.Show') }}</label>
                                                                                            </div>

                                                                                    </div>

                                                                                    <div class="col-md-3 mb-25">
                                                                                        <div class="mr-20">
                                                                                            <input type="radio" name="preloader_status" id="preloader_status_disable" value="0" class="common-checkbox class-checkbox " @if (generalSetting()->preloader_status == 0) checked
                                                                                                    @endif>
                                                                                            <label for="preloader_status_disable">{{__('system_settings.Hide')}}</label>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12  ">
                                                                                        <label
                                                                                                class="primary_input_label"
                                                                                                for=""> {{__('system_settings.Preloader Type')}} </label>
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-25">
                                                                                        <div class="mr-20">
                                                                                            <input type="radio" name="preloader_type" id="preloader_type_animation" value="1" class="common-checkbox class-checkbox" @if (!generalSetting()->preloader_type || generalSetting()->preloader_type == 1) checked @endif>
                                                                                            <label for="preloader_type_animation">{{ __('system_settings.Animation') }}</label>
                                                                                        </div>

                                                                                    </div>

                                                                                    <div class="col-md-3 mb-25">
                                                                                        <div class="mr-20">
                                                                                            <input type="radio" name="preloader_type" id="preloader_type_image" value="2" class="common-checkbox class-checkbox " @if (generalSetting()->preloader_type == 2) checked @endif>
                                                                                            <label for="preloader_type_image">{{__('system_settings.Image')}}</label>
                                                                                        </div>

                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row d-none" id="preloaderImageDiv">
                                                                            <div class="col-xl-4">
                                                                                <div class="row no-gutters input-right-icon">
                                                                                    <div class="col">
                                                                                        <div class="primary_input ">
                                                                                            <input class="primary_input_field filePlaceholder" type="text" id="placeholderPhoto" placeholder="@lang('system_settings.Preloader Image')"
                                                                                                   readonly="">
                                                                                            



                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-auto">
                                                                                        <button class="primary-btn-small-input" type="button">
                                                                                            <label class="primary-btn small fix-gr-bg" for="file1">@lang('common.browse')</label>
                                                                                            <input type="file" class="d-none fileUpload imgInput1" name="preloader_image" id="file1">
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="col-xl-2">
                                                                                <div class="primary_input mb-25 pt-4">
                                                                                    <img height="70"
                                                                                         class="img-fluid imagePreview1"
                                                                                         src="{{asset(generalSetting()->preloader_image)}}"
                                                                                         alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 d-none"
                                                                         id="preloaderStyleDiv">
                                                                        <label class="primary_input_label"
                                                                               for=""> {{__('system_settings.Preloader Style')}} </label>
                                                                        <div class="row pt-2">

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox1"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==1?'checked':''}}
                                                                                       value="1"/>
                                                                                <label for="checkbox1">
                                                                                    <div
                                                                                            class="preloaderr  {{generalSetting()->preloader_style==1?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle1"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox2"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==2?'checked':''}}
                                                                                       value="2"/>
                                                                                <label for="checkbox2">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==2?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle2"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox3"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==3?'checked':''}}
                                                                                       value="3"/>
                                                                                <label for="checkbox3">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==3?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle3 c31"></div>
                                                                                        <div
                                                                                                class="circle circle3 c32"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox4"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==4?'checked':''}}
                                                                                       value="4"/>
                                                                                <label for="checkbox4">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==4?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle4 c41"></div>
                                                                                        <div
                                                                                                class="circle circle4 c42"></div>
                                                                                        <div
                                                                                                class="circle circle4 c43"></div>
                                                                                        <div
                                                                                                class="circle circle4 c44"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>


                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox5"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==5?'checked':''}}
                                                                                       value="5"/>
                                                                                <label for="checkbox5">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==5?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle5 c51"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox6"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==6?'checked':''}}
                                                                                       value="6"/>
                                                                                <label for="checkbox6">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==6?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle6 c61"></div>
                                                                                        <div
                                                                                                class="circle circle6 c62"></div>
                                                                                        <div
                                                                                                class="circle circle6 c63"></div>
                                                                                        <div
                                                                                                class="circle circle6 c64"></div>
                                                                                        <div
                                                                                                class="circle circle4 c65"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox7"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==7?'checked':''}}
                                                                                       value="7"/>
                                                                                <label for="checkbox7">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==7?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle7 c71"></div>
                                                                                        <div
                                                                                                class="circle circle7 c72"></div>
                                                                                        <div
                                                                                                class="circle circle7 c73"></div>
                                                                                        <div
                                                                                                class="circle circle7 c74"></div>
                                                                                        <div
                                                                                                class="circle circle7 c75"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox8"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==8?'checked':''}}
                                                                                       value="8"/>
                                                                                <label for="checkbox8">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==8?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle8 c81"></div>
                                                                                        <div
                                                                                                class="circle circle8 c82"></div>
                                                                                        <div
                                                                                                class="circle circle8 c83"></div>
                                                                                        <div
                                                                                                class="circle circle8 c84"></div>
                                                                                        <div
                                                                                                class="circle circle8 c85"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox9"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==9?'checked':''}}
                                                                                       value="9"/>
                                                                                <label for="checkbox9">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==9?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle9 c91"></div>
                                                                                        <div
                                                                                                class="circle circle9 c92"></div>
                                                                                        <div
                                                                                                class="circle circle9 c93"></div>
                                                                                        <div
                                                                                                class="circle circle9 c94"></div>
                                                                                        <div
                                                                                                class="circle circle9 c95"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox10"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==10?'checked':''}}
                                                                                       value="10"/>
                                                                                <label for="checkbox10">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==10?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle10 c101"></div>
                                                                                        <div
                                                                                                class="circle circle10 c102"></div>
                                                                                        <div
                                                                                                class="circle circle10 c103"></div>
                                                                                        <div
                                                                                                class="circle circle10 c104"></div>
                                                                                        <div
                                                                                                class="circle circle10 c105"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox11"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==11?'checked':''}}
                                                                                       value="11"/>
                                                                                <label for="checkbox11">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==11?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle11 c111"></div>
                                                                                        <div
                                                                                                class="circle circle11 c112"></div>
                                                                                        <div
                                                                                                class="circle circle11 c113"></div>
                                                                                        <div
                                                                                                class="circle circle11 c114"></div>
                                                                                        <div
                                                                                                class="circle circle11 c115"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox12"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==12?'checked':''}}
                                                                                       value="12"/>
                                                                                <label for="checkbox12">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==12?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle12 c121"></div>
                                                                                        <div
                                                                                                class="circle circle12 c122"></div>
                                                                                        <div
                                                                                                class="circle circle12 c123"></div>
                                                                                        <div
                                                                                                class="circle circle12 c124"></div>
                                                                                        <div
                                                                                                class="circle circle12 c125"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox13"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==13?'checked':''}}
                                                                                       value="13"/>
                                                                                <label for="checkbox13">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==13?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle13 c131"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox14"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==14?'checked':''}}
                                                                                       value="14"/>
                                                                                <label for="checkbox14">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==14?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle14 c141"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox15"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==15?'checked':''}}
                                                                                       value="15"/>
                                                                                <label for="checkbox15">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==15?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="circle circle15 c151"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox16"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==16?'checked':''}}
                                                                                       value="16"/>
                                                                                <label for="checkbox16">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==16?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div class="dot dot1 d11"></div>
                                                                                        <div class="dot dot1 d12"></div>
                                                                                        <div class="dot dot1 d13"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox17"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==17?'checked':''}}
                                                                                       value="17"/>
                                                                                <label for="checkbox17">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==17?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div class="dot dot2 d21"></div>
                                                                                        <div class="dot dot2 d22"></div>
                                                                                        <div class="dot dot2 d23"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox18"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==18?'checked':''}}
                                                                                       value="18"/>
                                                                                <label for="checkbox18">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==18?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div class="dot dot3"></div>
                                                                                        <div
                                                                                                class="dot dot3 dot31"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox19"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==19?'checked':''}}
                                                                                       value="19"/>
                                                                                <label for="checkbox19">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==19?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div class="dot dot4"></div>
                                                                                        <div
                                                                                                class="dot dot4 dot41"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio" id="checkbox20"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==20?'checked':''}}
                                                                                       value="20"/>
                                                                                <label for="checkbox20">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==20?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="dot dot5 dot50"></div>
                                                                                        <div
                                                                                                class="dot dot5 dot51"></div>
                                                                                        <div
                                                                                                class="dot dot5 dot52"></div>
                                                                                        <div
                                                                                                class="dot dot5 dot53"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio"
                                                                                       id="checkbox21"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==21?'checked':''}}
                                                                                       value="21"/>
                                                                                <label for="checkbox21">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==21?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="dot dot6 dot60"></div>
                                                                                        <div
                                                                                                class="dot dot6 dot61"></div>
                                                                                        <div
                                                                                                class="dot dot6 dot62"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio"
                                                                                       id="checkbox22"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==22?'checked':''}}
                                                                                       value="22"/>
                                                                                <label for="checkbox22">

                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==22?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="dot dot7 dot70"></div>
                                                                                        <div
                                                                                                class="dot dot7 dot71"></div>
                                                                                        <div
                                                                                                class="dot dot7 dot72"></div>
                                                                                        <div
                                                                                                class="dot dot7 dot73"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <input type="radio"
                                                                                       id="checkbox23"
                                                                                       name="preloader_style"
                                                                                       {{generalSetting()->preloader_style==23?'checked':''}}
                                                                                       value="23"/>
                                                                                <label for="checkbox23">
                                                                                    <div
                                                                                            class="preloaderr {{generalSetting()->preloader_style==23?'active':''}}"
                                                                                            dir="ltr">
                                                                                        <div
                                                                                                class="dot dot8 dot80"></div>
                                                                                        <div
                                                                                                class="dot dot8 dot81"></div>
                                                                                        <div
                                                                                                class="dot dot8 dot82"></div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                            {{--
                                                                                                                                                        <div class="col-md-2">
                                                                                                                                                            <input type="radio"
                                                                                                                                                                   id="checkbox24"
                                                                                                                                                                   name="preloader_style"
                                                                                                                                                                   {{generalSetting()->preloader_style==24?'checked':''}}
                                                                                                                                                                   value="24"/>
                                                                                                                                                            <label for="checkbox24">
                                                                                                                                                                <div
                                                                                                                                                                    class="preloaderr {{generalSetting()->preloader_style==24?'active':''}}"
                                                                                                                                                                    style=""
                                                                                                                                                                    dir="ltr">
                                                                                                                                                                    <div
                                                                                                                                                                        class="dot dot9"></div>
                                                                                                                                                                    <div
                                                                                                                                                                        class="dot dot9 dot91"></div>
                                                                                                                                                                    <div
                                                                                                                                                                        class="dot dot9 dot92"></div>
                                                                                                                                                                    <div
                                                                                                                                                                        class="dot dot9 dot93"></div>
                                                                                                                                                                </div>
                                                                                                                                                            </label>
                                                                                                                                                        </div>
                                                                                                                                                         --}}

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>


                                                <div class="submit_btn  mt-4">
                                                    <button class="primary-btn small fix-gr-bg" type="submit"
                                                            data-toggle="tooltip" title=""
                                                            id="general_info_sbmt_btn"><i
                                                                class="ti-check"></i> {{ __('common.update') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $('input[name=preloader_type]').change(function () {
            let type = $('input[name="preloader_type"]:checked').val();
            if (type == 1) {
                $('#preloaderStyleDiv').removeClass('d-none');
                $('#preloaderImageDiv').addClass('d-none');
            } else {
                $('#preloaderStyleDiv').addClass('d-none');
                $('#preloaderImageDiv').removeClass('d-none');
            }
        });
        $('input[name=preloader_type]').trigger('change');

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(".imagePreview1").attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(".imgInput1").change(function () {

            readURL1(this);
        });
    </script>
@endpush
