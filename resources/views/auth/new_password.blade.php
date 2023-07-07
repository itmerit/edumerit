@php
$gs = generalSetting();
@endphp
<!DOCTYPE html>
@php
App::setLocale(getUserLanguage());
$ttl_rtl = userRtlLtl();

$login_background = App\SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();

if (empty($login_background)) {
$css = 'background: url(' . url('public/backEnd/img/login-bg.png') . ') no-repeat center; background-size: cover; ';
} else {
if (!empty($login_background->image)) {
$css = "background: url('" . url($login_background->image) . "') no-repeat center; background-size: cover;";
} else {
$css = 'background:' . $login_background->color;
}
}
@endphp
<html lang="{{ app()->getLocale() }}" @if (isset($ttl_rtl) && $ttl_rtl==1) dir="rtl" class="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(generalSetting()->favicon) }}" type="image/png" />
    <title>@lang('auth.login')</title>
    <meta name="_token" content="{!! csrf_token() !!}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/themify-icons.css" />

    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/css/nice-select.css" />
    <link rel="stylesheet" href="{{ url('/') }}/public/backEnd/vendors/js/select2/select2.css" />

    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('public/frontend/') }}/css/{{ activeStyle()->path_main_style }}" />
    <x-root-css />
</head>

<body class="login admin login_screen_body" style=" {{ $css }} ">
    <style>
        .login_screen_body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 30px 0;
            grid-gap: 20px;
        }

        @media (max-width: 991px) {
            .login.admin.hight_100 .login-height .form-wrap {
                padding: 50px 8px;
            }

            .login-area .login-height {
                min-height: auto;
            }
        }

        body {
            height: 100%;
        }

        hr {
            background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%) !important;
            height: 1px !important;
        }

        .invalid-select strong {
            font-size: 11px !important;
        }

        .login-area .form-group i {
            position: absolute;
            top: 12px;
            left: 0;
        }

        .grid__button__layout {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 15px;
        }

        .grid__button__layout button {
            font-size: 11px;
            margin: 0 !important;
            padding: 0;
            height: 31px;
            line-height: 31px;
        }

        @media (max-width: 575.98px) {
            .grid__button__layout {
                grid-template-columns: repeat(2, 1fr);
                grid-gap: 10px;
            }
        }
    </style>

    <!--================ Start Login Area =================-->
	<section class="login-area up_login">
		<div class="container">
			<div class="row login-height justify-content-center align-items-center">
				<div class="col-lg-5 col-md-8">
					<div class="form-wrap text-center">
						<div class="logo-container">
							@php 
                            	$setting = generalSetting();
                        	@endphp
							<a href="#">
								{{-- <img src="{{asset('public/backEnd/img/logo.png')}}" alt=""> --}}
								<img src="{{asset($setting->logo)}}" alt="" width="170px" height="68px">
							</a>
						</div>
						<h5 class="text-uppercase">@lang('auth.reset_password')</h5>
						@if(session()->has('message-success') != "")
		                    @if(session()->has('message-success'))
		                    <p class="text-success">{{session()->get('message-success')}}</p>
		                    @endif
		                @endif
		                @if(session()->has('message-danger') != "")
		                    @if(session()->has('message-danger'))
		                    <p class="text-danger">{{session()->get('message-danger')}}</p>
		                    @endif
		                @endif
						<form method="POST" class="" action="{{ route('storeNewPassword') }}">
							<input type="hidden" name="email" value="{{$email}}">
                        @csrf

							<div class="form-group input-group mb-4 mx-3">
								<span class="input-group-addon">
									<i class="ti-key"></i>
								</span>
								<input class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" type="password" name='new_password' placeholder="@lang('auth.enter_new_password')"/>
								@if ($errors->has('new_password'))
                                    <span class="text-danger text-left pl-3" role="alert">
                                        {{ $errors->first('new_password') }}
                                    </span>
                                @endif
							</div>

							<div class="form-group input-group mb-4 mx-3">
								<span class="input-group-addon">
									<i class="ti-key"></i>
								</span>
								<input class="form-control{{ $errors->has('confirm_password') ? ' is-invalid' : '' }}" type="password" name='confirm_password' placeholder="@lang('auth.confirm_new_password')"/>
								@if ($errors->has('confirm_password'))
                                    <span class="text-danger text-left pl-3" role="alert">
                                        {{ $errors->first('confirm_password') }}
                                    </span>
                                @endif
							</div>
							

							<div class="form-group mt-30 mb-30">
								<button type="submit" class="primary-btn fix-gr-bg">
									<span class="ti-lock mr-2"></span>
									@lang('common.save')
                                </button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================ Start End Login Area =================-->

	<!--================ Footer Area =================-->
	<footer class="footer_area">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-12 text-center">
					@if($setting->copyright_text)
					<p>{!! $setting->copyright_text !!}</p> 
					</p>
					@else
					<p>Copyright © 2019 All rights reserved | This application is made with <span class="ti-heart"></span>  by Codethemes</p> 
					</p>
					@endif
				</div>
			</div>
		</div>
	</footer>
	<!--================ End Footer Area =================-->


    <script src="{{asset('public/backEnd/')}}/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/vendors/js/popper.js"></script>
	<script src="{{asset('public/backEnd/')}}/vendors/js/bootstrap.min.js"></script>
	<script>
		$('.primary-btn').on('click', function(e) {
		// Remove any old one
		$('.ripple').remove();

		// Setup
		var primaryBtnPosX = $(this).offset().left,
			primaryBtnPosY = $(this).offset().top,
			primaryBtnWidth = $(this).width(),
			primaryBtnHeight = $(this).height();

		// Add the element
		$(this).prepend("<span class='ripple'></span>");

		// Make it round!
		if (primaryBtnWidth >= primaryBtnHeight) {
			primaryBtnHeight = primaryBtnWidth;
		} else {
			primaryBtnWidth = primaryBtnHeight;
		}

		// Get the center of the element
		var x = e.pageX - primaryBtnPosX - primaryBtnWidth / 2;
		var y = e.pageY - primaryBtnPosY - primaryBtnHeight / 2;

		// Add the ripples CSS and start the animation
		$('.ripple')
			.css({
				width: primaryBtnWidth,
				height: primaryBtnHeight,
				top: y + 'px',
				left: x + 'px'
			})
			.addClass('rippleEffect');
		});
	</script>
</body>
</html>
