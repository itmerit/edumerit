@extends('frontEnd.home.front_master')
@push('css')
    <link rel="stylesheet" href="{{asset('public/')}}/frontend/css/new_style.css"/>
@endpush
@section('main_content')

    <!--================ Home Banner Area =================-->
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url('../img/client/common-banner1.jpg') no-repeat center;">
            <div class="banner-inner">
                <div class="banner-content">
                    <h2>{{$category_id->category_name}}</h2>
                </div>
            </div>
        </div>
    </section>
    <!--================ End Home Banner Area =================-->

    <!--================ News Area =================-->
    <section class="news-area section-gap-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="title">@lang('front_settings.latest_news')</h3>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($newsCtaegories as $value)
                        <div class="col-lg-3 col-md-6">
                            <div class="news-item">
                                <div class="news-img">
                                    <img class="img-fluid w-100 news-image" src="{{asset($value->image)}}" alt="">
                                </div>
                                <div class="news-text">
                                    <p class="date">                                                                            
                                        {{$value->publish_date != ""? dateConvert($value->publish_date):''}}
                                    </p>
                                    <h4>
                                        <a href="{{url('news-details/'.$value->id)}}">
                                            {{$value->news_title}}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <div class="row text-center mt-40">
                <div class="col-lg-12">
                    <a class="primary-btn fix-gr-bg semi-large" href="#">@lang('front_settings.load_more_news')</a>
                </div>
            </div>
        </div>
    </section>
    <!--================End News Area =================-->
@endsection
