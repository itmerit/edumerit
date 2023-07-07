@extends('frontEnd.home.front_master')
@section('main_content')

<?php
$setting = generalSetting();
if(isset($setting->copyright_text)){ $copyright_text = $setting->copyright_text; }else{ $copyright_text = 'Copyright © 2020 All rights reserved | This application is made with by Codethemes'; }
if(isset($setting->logo)) { $logo = $setting->logo; } else{ $logo = 'public/uploads/settings/logo.png'; }

if(isset($setting->favicon)) { $favicon = $setting->favicon; } else{ $favicon = 'public/backEnd/img/favicon.png'; }

$login_background = App\SmBackgroundSetting::where([['is_default',1],['title','Login Background']])->first();

if(empty($login_background)){
    $css = "";
}else{
    if(!empty($login_background->image)){
        $css = "background: url('". url($login_background->image) ."')  no-repeat center;  background-size: cover;";

    }else{
        $css = "background:".$login_background->color;
    }
}
$active_style = App\SmStyle::where('is_active', 1)->first();

$ttl_rtl = $setting->ttl_rtl;
?>


    <!--================ Home Banner Area =================-->
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{$contact_info->image != ""? $contact_info->image : '../img/client/common-banner1.jpg'}}) no-repeat center;">

            <div class="banner-inner">
                <div class="banner-content">
                    <h2>Privacy Policy</h2>
                </div>
            </div>
        </div>
    </section>
    <!--================ End Home Banner Area =================-->

   <!--================Contact Area =================-->
   <section class="contact_area section-gap-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h2>Introduction</h2>
                    <p>
                        Welcome to the The <strong>{{@generalSetting()->school_name}}</strong> website located at <strong>{{url('/')}}</strong> (the “Website”).
                        The School provides this Website to you subject to the following Terms of Use and Privacy Policy (together, the “Terms”).
                        When you use this Website, you agree to abide by these Terms. If you do not agree to abide by these Terms, you may not use this Website. Please read the Terms carefully.
                        The School reserves the right to make changes to this Website and to modify the Terms at any time at its sole discretion.
                        We encourage you to review the Terms frequently for modifications. By your use of this Website, you agree to abide by any such modifications to the Terms,
                        which are binding on you.
                    </p>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h2>Privacy Policy</h2>
                    <p>
                       This Privacy Policy describes the School’s agreement with you regarding how we will handle certain information on the Website.
                       This Privacy Policy does not address information obtained from other sources such as submissions by mail, phone or other devices or from personal contact.
                       By accessing the Website and/or providing information to the School on the Website, you consent to the collection, use and disclosure of certain information in accordance with this Privacy Policy.
                    </p>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h2>Information Collected on Our Website:</h2>
                    <p>
                        If you merely download material or browse through the Website,
                        our servers may automatically collect certain information from you which may include:<br> (a) the name of the domain and host from which you access the Internet;
                        <br>(b) the browser software you use and your operating system; and <br>(c) the Internet address of the website from which you linked to the Website.<br><br>
                        The information we automatically collect may be used to improve the Website to make it as useful as possible for our visitors; however,
                        such information will not be tied to the personal information you choose to provide to us.</p>

                        <p>

                        We do collect and keep personally identifiable information when you choose to voluntarily submit such information.
                        For example, if you choose to fill out a form on the Website we retain the information submitted by you.
                        You should not submit any information that you do not want to be retained.
                        After we have taken the appropriate action in response to your submittal, we retain the information you submit for our records and to contact you from time to time.
                        Please note that if we decide to change the manner in which we use or retain personal information, we may update this Privacy Policy, at our sole discretion.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!--================Contact Area =================-->
@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/vendors/js/gmap3.min.js"></script>
<script>
    $('.map')
      .gmap3({
        center:[<?php echo $contact_info->latitude;?>, <?php echo $contact_info->longitude;?>],
        zoom:4
      })
      .marker([
        {position:[<?php echo $contact_info->latitude;?>, <?php echo $contact_info->longitude;?>]},
        {address:"<?php echo $contact_info->google_map_address;?>"},
        {address:"<?php echo $contact_info->google_map_address;?>", icon: "https://maps.google.com/mapfiles/marker_grey.png"}
      ])
      .on('click', function (marker) {
        marker.setIcon('https://maps.google.com/mapfiles/marker_green.png');
      });

</script>
@endsection
