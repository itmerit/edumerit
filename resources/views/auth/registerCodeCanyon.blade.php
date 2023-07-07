<?php
$ttl_rtl = $setting->ttl_rtl;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{asset(generalSetting()->favicon)}}" type="image/png"/>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/login2')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/login2')}}/themify-icons.css">


    <link rel="stylesheet" href="{{url('/')}}/public/backEnd/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="{{url('/')}}/public/backEnd/vendors/js/select2/select2.css" />



    <link rel="stylesheet" href="{{asset('public/backEnd/login2')}}/css/style.css">
	<link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/toastr.min.css"/>
    <title>{{isset($setting)? !empty($setting->site_title) ? $setting->site_title : 'System ': 'System '}} | @lang('auth.login')</title>
    <style>

.loginButton {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.loginButton{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.singleLoginButton{
    flex: 22% 0 0;
}

.loginButton .get-login-access {
    display: block;
    width: 100%;
    border: 1px solid #fff;
    border-radius: 5px;
    margin-bottom: 20px;
    padding: 5px;
    white-space: nowrap;
}
@media (max-width: 576px) {
  .singleLoginButton{
    flex: 49% 0 0;
  }
}
@media (max-width: 576px) {
    .singleLoginButton{
        flex: 49% 0 0;
    }
    .loginButton .get-login-access {
        margin-bottom: 10px;
    }
}
.create_account a {
    color: #828bb2;
    font-weight: 500;
    text-decoration: none;
}

    #select-school{
        border: 0px;
        border-radius: 0px;
        border-bottom: 1px solid #d3cddd;
    }

    .nice-select:after {
    content: "\e62a";
    font-family: "themify";
    border: 0;
    transform: rotate(0deg);
    margin-top: -16px;
    font-size: 12px;
    font-weight: 500;
    right: 18px;
    transform-origin: none;
    -webkit-transition: all 0.1s ease-in-out;
    -moz-transition: all 0.1s ease-in-out;
    -o-transition: all 0.1s ease-in-out;
    transition: all 0.1s ease-in-out;
}

.nice-select.open:after {
    -webkit-transform: rotate(180deg);
    -moz-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    -o-transform: rotate(180deg);
    transform: rotate(180deg);
    margin-top: 15px;
}
.niceSelect {
    border: 0px;
    border-bottom: 1px solid rgba(130, 139, 178, 0.3);
    border-radius: 0px;
    -webkit-appearance: none;
    -moz-appearance: none;
    color: #828bb2;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    padding: 0;
    background: transparent;
}
.niceSelect:focus,.niceSelect:hover{
    border-color: rgba(130, 139, 178, 0.3);
    outline: none;
    box-shadow: none !important;
}
.mb-26{
    margin-bottom: 26px;
}

.nice-select.open .list {
    left: 0;
    position: absolute;
    right: 0;
}
.nice-select .nice-select-search {
    box-sizing: border-box;
    background-color: #fff;
    border: 1px solid rgba(130, 139, 178, 0.3);
    border-radius: 3px;
    box-shadow: none;
    color: #333;
    display: inline-block;
    vertical-align: middle;
    padding: 0px 8px;
    width: 100% !important;
    height: 36px;
    line-height: 36px;
    outline: 0 !important;
}
.nice-select .list {
    margin-top: 5px;
    top: 100%;
    border-top: 0;
    border-radius: 0 0 5px 5px;
    max-height: 210px;
    overflow-y: scroll;
    padding: 52px 0 0;
    left: 0 !important;
    right: 0 !important;
}
.niceSelect span.current {
    width: 85% !important;
    overflow: hidden !important;
    display: block !important;
}
    </style>
</head>
<body >
    <div class="in_login_part mb-40"  style="{{$css}}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-8 col-lg-5 col-xl-4 col-md-7">
					@if($errors->any())
						{{ implode('', $errors->all('<div>:message</div>')) }}
					@endif
                    <div class="in_login_content">
                        @if(!empty($setting->logo))<img src="{{asset($setting->logo)}}" alt="Login Panel">@endif
                        <div class="in_login_page_iner">
                            <div class="in_login_page_header">
                                <h5>{{__('Registration')}} @lang('common.details')</h5>
                            </div>
                            <form method="POST" class="loginForm" action="{{route('customer_register')}}" id="infix_form">
                                @csrf

                                <input type="hidden" name="school_id" value="1">
                                <input type="hidden" name="username" id="username-hidden">

                                <?php if(session()->has('message-danger') != ""): ?>
                                    <?php if(session()->has('message-danger')): ?>
                                    <p class="text-danger"><?php echo e(session()->get('message-danger')); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <input type="hidden" id="url" value="{{url('/')}}">


                            {{-- <div class="form-group input-group mb-4 mx-3">
                                <span class="input-group-addon">
                                    <i class="ti-user"></i>
                                </span>
                                <input class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" type="text" name='fullname' placeholder="Enter Name"/>
                                @if ($errors->has('fullname'))
                                    <span class="text-danger text-left pl-3" role="alert">
                                        {{ $errors->first('fullname') }}
                                    </span>
                                @endif
                            </div> --}}
                            <div class="row">
                            <div class="col-12">
                            {{-- @if (moduleStatusCheck('Saas') == TRUE) --}}
                                    <div class="primary_input mb-40 in_single_input">
                                        <select class="mb-26 niceSelect infix_theme_style w-100 bb form-control{{ $errors->has('school_id') ? ' is-invalid' : '' }}" name="school_id" id="select-school">
                                            <option data-display="Select School *" value="">Select school *</option>
                                            @foreach($schools as $school)
                                                <option value="{{$school->id}}"> {{$school->school_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('school_id'))
                                        <span class="invalid-select text-left text-danger pl-3" role="alert">
                                            {{ $errors->first('school_id') }}
                                        </span>
                                    @endif
                            {{-- @endif --}}
                            </div>
                            </div>


                                <div class="in_single_input">
                                    <input type="text" placeholder="@lang('auth.enter_name')" name="fullname" class="{{ $errors->has('fullname') ? ' is-invalid' : '' }}" value="{{old('fullname')}}" id="fullname">
                                    <span class="addon_icon">
                                    <i class="ti-user"></i>
                                    </span>
                                    @if ($errors->has('fullname'))
                                        <span class="text-danger text-left pl-3 d-block" role="alert">
                                            {{ $errors->first('fullname') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="in_single_input">
                                    <input type="text" placeholder="@lang('auth.enter_email')" name="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{old('email')}}" id="email-address">
                                    <span class="addon_icon">
                                        <i class="ti-email"></i>
                                    </span>
                                    @if ($errors->has('email'))
                                        <span class="text-danger text-left pl-3 d-block" role="alert">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>


                                <div class="in_single_input">
                                    <input type="password" placeholder="@lang('auth.enter_password')" name="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{old('password')}}">
                                    <span class="addon_icon">
                                        <i class="ti-key"></i>
                                    </span>
                                    @if ($errors->has('password'))
                                        <span class="text-danger text-left pl-3 d-block" role="alert">
                                            {{ $errors->first('password') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="in_single_input">
                                    <input type="password" placeholder="@lang('auth.enter_confirm_password')" name="password_confirmation"
                                    class="{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" value="{{old('password_confirmation')}}">
                                    <span class="addon_icon">
                                        <i class="ti-key"></i>
                                    </span>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="text-danger text-left pl-3 d-block" role="alert">
                                            {{ $errors->first('password_confirmation') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- <div class="d-flex justify-content-between">
                                    <div class="in_checkbox">
                                        <div class="boxes">
                                            <input type="checkbox" id="Remember">
                                            <label for="Remember">@lang('auth.remember_me')</label>
                                        </div>
                                    </div>
                                    <div class="in_forgot_pass">
                                        <a href="{{url('recovery/passord')}}">@lang('auth.forget_password') ? </a>
                                    </div>
                                </div> --}}
                                <div class="in_login_button text-center">
                                    <button type="submit" class="in_btn" id="btnsubmit">
                                        <span class="ti-lock"></span>
                                       {{__('Registred')}}
                                    </button>
                                </div>
                                <div class="create_account text-center">
                                    <p>Already have an account? <a href="{{url('login')}}">Login Here</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--================ Footer Area =================-->
    <footer class="footer_area min-height-10" style="margin-top: -50px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <p style="color: #828bb2">{!! generalSetting()->copyright_text !!} </p>
                </div>
            </div>
        </div>
    </footer>


    <!--================ End Footer Area =================-->
    <script src="{{asset('public/backEnd/login2')}}/js/jquery-3.4.1.min.js"></script>
    <script src="{{asset('public/backEnd/login2')}}/js/popper.min.js"></script>
	<script src="{{asset('public/backEnd/login2')}}/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/toastr.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/vendors/js/nice-select.min.js"></script>



	<script>
    if ($('.niceSelect').length) {
		$('.niceSelect').niceSelect();
	}
	$(document).ready(function () {

		$('#btnsubmit').on('click',function()
		{
		$(this).html('Please wait ...')
			.attr('disabled','disabled');
		$('#infix_form').submit();
		});

	 });


	$(document).ready(function() {
        $("#email-address").keyup(function(){
            $("#username-hidden").val($(this).val());
        });
    });

	 </script>



	{!! Toastr::message() !!}
  </body>
</html>
