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
    @if (isset($ttl_rtl) && $ttl_rtl==1)
    <link rel="stylesheet" href="{{ url('public/backEnd/') }}/assets/vendors/vendors_static_style.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/style.css') }}" />
        @endif
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
    <section class="login-area up_login login_screen">

        <div class="container">

            @if (config('app.app_sync') and isset($schools) and !session('domain'))

            <div class="row justify-content-center">

                @foreach ($schools as $school)
                <div class="col-md-3">
                    <h4 class="text-center text-white">@lang('auth.school') {{ $loop->iteration }}</h4>
                    <hr>
                    <a target="_blank" href="//{{ $school->domain . '.' . config('app.short_url') }}/home"
                        class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12">{{ Str::limit($school->school_name, 20, '...') }}</a>
                </div>
                @endforeach


            </div>
            @endif


            <input type="hidden" id="url" value="{{ url('/') }}">
            <div class="row login-height justify-content-center align-items-center mb-30 mt-30">
                <div class="col-lg-6 col-md-8">
                    <div class="form-wrap text-center">
                        <div class="logo-container">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset(generalSetting()->logo) }}" alt="" class="logoimage">
                            </a>
                        </div>

                        <h5 class="text-uppercase">@lang('auth.login_details')</h5>

                        <?php if(session()->has('message-success') != ""): ?>
                        <?php if(session()->has('message-success')): ?>
                        <p class="text-success"><?php echo e(session()->get('message-success')); ?></p>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if(session()->has('message-danger') != ""): ?>
                        <?php if(session()->has('message-danger')): ?>
                        <p class="text-danger"><?php echo e(session()->get('message-danger')); ?></p>
                        <?php endif; ?>
                        <?php endif; ?>
                        <form method="POST" class="" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group input-group mb-4">

                                <input type="hidden" name="username" id="username-hidden">

                                 
                                <div class="form-group input-group mb-4">
                                    <span class="input-group-addon">
                                        <i class="ti-email"></i>
                                    </span>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        type="text" name='email' id="email-address"
                                        placeholder="@lang('auth.enter_email_address')" value="{{ old('email') }}" />
                                </div>
                                @if ($errors->has('email'))
                                <span class="text-danger text-left mb-15" role="alert">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif

                                <div class="form-group input-group mb-4">
                                    <span class="input-group-addon">
                                        <i class="ti-key"></i>
                                    </span>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        type="password" name='password' id="password"
                                        placeholder="@lang('auth.enter_password')" />
                                </div>
                                @if ($errors->has('password'))
                                <span class="text-danger text-left mb-15" role="alert">
                                    {{ $errors->first('password') }}
                                </span>
                                @endif

                                <div class="d-flex form-group input-group justify-content-between align-items-center">
                                    <div class="checkbox ">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label for="rememberMe">@lang('auth.remember_me')</label>
                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('recoveryPassord')); ?>">@lang('auth.forget_password')
                                            ?</a>
                                    </div>
                                </div>

                                <div class="form-group mt-30 mb-30 flex-fill">
                                    <button type="submit" class="primary-btn fix-gr-bg" id="btnsubmit">
                                        <span class="ti-lock mr-2"></span>
                                        @lang('auth.login')
                                    </button>
                                </div>
                        </form>
                    </div>

                </div>
            </div>


        </div>
        @if (config('app.app_sync'))
        <div class="row justify-content-center align-items-center" style="">
            <div class="col-lg-6 col-md-8">
                <div class="grid__button__layout">
                    @foreach ($users as $user)
                    @if ($user)
                    <form method="POST" class="loginForm" action="{{ route('login') }}">
                        @csrf()
                        <input type="hidden" name="email" value="{{ $user[0]->email }}">
                        <input type="hidden" name="auto_login" value="true">
                        <button type="submit"
                            class="primary-btn fix-gr-bg  mt-10 text-center col-lg-12 text-nowrap">{{ $user[0]->roles->name }}</button>
                    </form>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </section>
    <!--================ Start End Login Area =================-->

    <!--================ Footer Area =================-->
    <footer class="footer_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <p class="mb-0">{!! generalSetting()->copyright_text !!}</p>
                </div>
            </div>
        </div>
    </footer>
    <!--================ End Footer Area =================-->

    <script src="{{ asset('public/backEnd/') }}/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/popper.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/public/backEnd/vendors/js/nice-select.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/js/login.js"></script>
    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/toastr.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#email-address").keyup(function () {
                $("#username-hidden").val($(this).val());
            });
        });
    </script>

    {!! Toastr::message() !!}


</body>

</html>