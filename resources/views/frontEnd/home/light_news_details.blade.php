@extends('frontEnd.home.front_master')
@push('css')
    <link rel="stylesheet" href="{{asset('public/')}}/frontend/css/new_style.css"/>
@endpush
@section('main_content')
    <!--================ Home Banner Area =================-->
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient( 0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{asset($news->image)}}) no-repeat center;">
        </div>
    </section>
    <!--================ End Home Banner Area =================-->

   <!--================ News Details Area =================-->
    <section class="news-details-area section-gap-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <h1>{{$news->news_title}}</h1>
                    <div class="meta mb-30 d-flex flex-md-row flex-column">
                        <div class="date text-uppercase">
                            <span class="ti-calendar mr-10"></span>   
                            {{$news->publish_date != ""? dateConvert($news->publish_date):''}}
                        </div>
                        <div class="date text-uppercase">
                            <span class="ti-map-alt mr-10"></span>
                            {{$news->category->category_name}}
                        </div>
                    </div>
                    <div class="news-img">
                        <img class="img-fluid news-image" src="{{asset($news->image)}}" alt="{{$news->news_title}}">
                    </div>
                    <p class="mt-2">
                        {!!$news->news_body!!}
                    </p>

                </div>

                <div class="col-lg-3 notice-board-area">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="title">@lang('communicate.notice_board')</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="notice-board">
                                @if(isset($notice_board))
                                @foreach($notice_board as $notice)
                                    <div class="notice-item">
                                        <p class="date">
                                            
            {{$notice->publish_on != ""? dateConvert($notice->publish_on):''}}

                                        </p>
                                        <h4>{{$notice->notice_title}}</h4>
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ End News Details Area =================-->
    
    <!--================ Related News Area =================-->
    <section class="news-area section-gap-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="title">@lang('front_settings.related_news')</h3>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($otherNews as $theNews)
                        <div class="col-lg-3 col-md-6">
                            <div class="news-item">
                                <div class="news-img">
                                    <img class="img-fluid w-100 news-image" src="{{asset($theNews->image)}}" alt="">
                                </div>
                                <div class="news-text">
                                    <p class="date">
                                        
{{$theNews->publish_date != ""? dateConvert($theNews->publish_date):''}}

                                    </p>
                                    <h4>
                                        <a href="{{url('news-details/'.$theNews->id)}}">
                                            {{$theNews->news_title}}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ End Related News Area =================-->
@endsection
