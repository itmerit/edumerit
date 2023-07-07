<link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/css/jquery-ui.css') }}" />
{{-- metsimenu --}}
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/metisMenu.css') }}" />

<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/loade.css') }}" />
<link rel="stylesheet" href="{{ asset('public/css/app.css') }}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/croppie.css')}}" />
 @if(userRtlLtl() ==1)
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/style.css')}}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/rtl/infix.css')}}" />
@else
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/backend_static_style.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/infix.css') }}" />
@endif

<link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/vendors_static_style.css') }}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/preloader.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/solid_style.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/multiselect/css/jquery.multiselect.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/multiselect/css/custom_style.css')}}" />
<link rel="stylesheet" href="{{asset('public/backEnd/assets/css/radio_checkbox.css')}}" />
<style>
    /* for toastr dynamic start*/
    .toast-success {
        background-color: #4BCF90!important;
    }

    .toast-message {
        color: #FFFFFF;
    }

    .toast-title {
        color: #FFFFFF;

    }

    .toast {
        color: #FFFFFF;
    }

    .toast-error {
        background-color: #FF6D68!important;
    }

    .toast-warning {
        background-color: #E09079!important;
    }
</style>
<style>

    :root {
    --background: url(url('/public/backEnd/img/body-bg.jpg')) no-repeat center;
    --base_color: #415094;

    --gradient_1: #7C32FF;
    --gradient_2: #A235EC;
    --gradient_3: #C738D8;
    --text-color: #828BB2;
    --border_color: rgba(130, 139, 178, 0.3);
    --scroll_color: #7E7172;
    --bg_white: #FFFFFF;

    --bg_black: #000000;
    --input_bg: #FFFFFF;
    --text_white: #FFFFFF;
    --text_black: #000000;
    --success: #4BCF90;
    --danger: #FF6D68;
    --warning: #E09079;
    }
</style>