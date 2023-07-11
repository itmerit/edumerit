@extends('frontEnd.home.front_master')
@section('main_content')
@push('css')
<style>
    .success-color{
        color: #79838b;
    }
    .danger-color{
        color: #ff0000;
    }
    .ft {
        font-size: 11px;
        position: absolute;
        bottom: -21px;
        left: 0;
    }
</style>
@endpush
    <!--================ Home Banner Area =================-->
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{$contact_info->image != ""? $contact_info->image : '../img/client/common-banner1.jpg'}}) no-repeat center;">

            <div class="banner-inner">
                <div class="banner-content">
                    <h2>{{$contact_info->title}}</h2>
                    <p>{{$contact_info->description}}</p>

                    <a class="primary-btn fix-gr-bg semi-large" href="{{url($contact_info->button_url)}}">{{$contact_info->button_text}}</a>

                </div>
            </div>
        </div>
    </section>
    <!--================ End Home Banner Area =================-->

   <!--================Contact Area =================-->
   <section class="contact_area section-gap-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="map mapBox"></div>
                </div>
                <div class="offset-lg-1 col-lg-5">
                    <div class="contact_info">
                        <div class="info_item">
                            <i class="ti-home"></i>
                            <h6>{{$contact_info->address}}</h6>
                            <p>{{$contact_info->address_text}}</p>
                        </div>
                        <div class="info_item">
                            <i class="ti-headphone-alt"></i>
                            <h6><a href="#">{{$contact_info->phone}}</a></h6>
                            <p>{{$contact_info->phone_text}}</p>
                        </div>
                        <div class="info_item">
                            <i class="ti-envelope"></i>
                            <h6>
                                <a href="#">{{$contact_info->email}}</a>
                            </h6>
                            <p>{{$contact_info->email_text}}</p>
                        </div>
                    </div>
                    <section class="container box-1420 mt-30">
                        </section>
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    type="text" name="name" id="name" autocomplete="off" value="{{old('name')}}">
                                <label class="primary_input_label" for="">@lang('front_settings.Enter_your_name') <span class="text-danger"> *</span></label>
                                
                                <span id="nameError" class="text-danger ft"></span>
                            </div>
                            <div class="primary_input mt-30">
                                <input oninput="emailCheck(this)" class="primary_input_field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" autocomplete="off" type="text" id="email" name="email" value="{{old('email')}}">
                                <label class="primary_input_label" for="">@lang('front_settings.Enter_your_email') <span class="text-danger"> *</span></label>
                                
                                <span id="emailError" class="text-danger ft"></span>
                            </div>
                            <div class="primary_input mt-30">
                                <input class="primary_input_field form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" autocomplete="off" type="text" id="subject" name="subject" value="{{old('subject')}}">
                                <label class="primary_input_label" for="">@lang('front_settings.enter_subject') <span class="text-danger"> *</span></label>
                                
                                <span id="subjectError" class="text-danger ft"></span>
                            </div>
                            <div class="primary_input mt-30 mb-10">
                                <textarea class="primary_input_field form-control" autocomplete="off" name="message" id="message" cols="0" rows="4">{{old('email')}}</textarea>
                                <label class="primary_input_label" for="">@lang('front_settings.enter_message') <span class="text-danger"> *</span></label>
                                
                                <span id="messageError" class="text-danger ft"></span>
                            </div>
                                <p class=" mt-3 text-success"></p>
                                <p class=" mt-3 text-danger"></p>
                        </div>
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="col-md-12 ">
                            <button type="submit" value="submit" id="click_btn" class=" mt-30 primary-btn fix-gr-bg submit">
                               @lang('front_settings.send_message')
                            </button>
                        </div>
                    {{-- </form> --}}
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
        zoom:<?php echo $contact_info->zoom_level;?>
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

<script>
        $(document).ready(function(){
            $("#click_btn").click(function(){
                let url = $('#url').val();
                let name = $('#name').val();
                let email = $('#email').val();
                let subject= $('#subject').val();
                let message = $('#message').val();

                $.ajax({
                    url: url + '/' + 'send-message',
                    method : "POST",
                    data : {
                        name : name,
                        email : email,
                        subject : subject,
                        message : message,
                    },
                    success : function (result){
                        if(result.success){
                            $('#name').val('');
                            $('#email').val('');
                            $('#subject').val('');
                            $('#message').val('');
                            $('.primary_input_field').removeClass('has-content');
                            $('.text-success').html('Email Sent Successfully');
                            $('#nameError').html();
                            $('#emailError').html();
                            $('#subjectError').html();
                            $('#messageError').html();
                        }else{
                            $('#name').val('');
                            $('#email').val('');
                            $('#subject').val('');
                            $('#message').val('');
                            $('#nameError').html();
                            $('#emailError').html();
                            $('#subjectError').html();
                            $('#messageError').html();
                            $('.primary_input_field').removeClass('has-content');
                            $('.text-danger').html('Something Went Wrong');
                            $('#nameError').html();
                            $('#emailError').html();
                            $('#subjectError').html();
                            $('#messageError').html();
                        }
                    },
                    error:function (xhr){
                        $('#nameError').html(xhr.responseJSON.errors.name);
                        $('#emailError').html(xhr.responseJSON.errors.email);
                        $('#subjectError').html(xhr.responseJSON.errors.subject);
                        $('#messageError').html(xhr.responseJSON.errors.message);
                    }
                })
            });
        });
</script>

@endsection
