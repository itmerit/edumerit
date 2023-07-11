<?php
$setting = generalSetting();
if(isset($setting->copyright_text)){ generalSetting()->copyright_text = $setting->copyright_text; }else{ generalSetting()->copyright_text = 'Copyright © 2019 All rights reserved | This application is made with by Codethemes'; }
if(isset($setting->logo)) { generalSetting()->logo = $setting->logo; } else{ generalSetting()->logo = 'public/uploads/settings/logo.png'; }

if(isset($setting->favicon)) { generalSetting()->favicon = $setting->favicon; } else{ generalSetting()->favicon = 'public/backEnd/img/favicon.png'; }
 
$login_background = App\SmBackgroundSetting::where([['is_default',1],['title','Login Background']])->where('school_id', app('school')->id)->first();
 
if(empty($login_background)){
    $css = "background: url(".url('public/backEnd/img/login-bg.png').")  no-repeat center; background-size: cover; ";
}else{
    if(!empty($login_background->image)){
        $css = "background: url('". url($login_background->image) ."')  no-repeat center;  background-size: cover;";
 
    }else{
        $css = "background:".$login_background->color;
    }
}
activeStyle() = App\SmStyle::where('is_active', 1)->first();

$ttl_rtl = $setting->ttl_rtl;
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('public/backEnd/login')}}/css/4_3_1_bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/login')}}/css/themify-icons.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/login')}}/css/style.css">
  </head>
  <body >
    
    <!-- code start here -->

    <div class="login_resister_area" style="{{$css }}">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-6 col-md-8">
                            <div class="main_login_form">
                                <div class="login_header text-center">
                                    <div class="logo_img"> 
                                        <a href="{{route('login')}}"> 
                                            <img src="{{asset($setting->logo)}}" alt="">
                                        </a>
                                    </div>
                                    <h5>Login Details</h5>
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
                                </div>
                                <form method="POST" class="loginForm" action="<?php echo e(route('login')); ?>">
                                    <input type="hidden" id="url" value="{{url('/')}}">
                                    <?php  echo csrf_field(); ?>
                                    <div class="single_input">
                                        <input type="email" placeholder="Enter Email address" name="email">
                                        <span class="addon_icon" >
                                            <i class="ti-email"></i>
                                        </span>
                                @if ($errors->has('email'))
                                    <span class="text-danger text-left pl-3" role="alert">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                                    </div>
                                    <div class="single_input">
                                        <input type="password" placeholder="Enter Password" name="password">
                                        <span class="addon_icon" >
                                            <i class="ti-key"></i>
                                        </span>
                                @if ($errors->has('password'))
                                    <span class="text-danger text-left pl-3" role="alert">
                                        {{ $errors->first('password') }}
                                    </span>
                                @endif
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="checkbox">
                                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                            <label for="rememberMe">Remember Me</label>
                                        </div>
                                        <div class="forgot_pass" >
                                            <a href="<?php echo e(route('recoveryPassord')); ?>">Forget Password?</a>
                                        </div>
                                    </div>
                                    <div class="login_button text-center">
                                        <button type="submit" class="primary-btn fix-gr-bg">
                                            <span class="ti-lock mr-2"></span>
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="footer_text text-center">
                    <p>{!! generalSetting()->copyright_text !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--/ code start here -->






    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{asset('public/backEnd/login')}}/js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="{{asset('public/backEnd/login')}}/js/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{asset('public/backEnd/login')}}/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>
</html>
