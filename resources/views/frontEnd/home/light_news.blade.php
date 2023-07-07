@extends('frontEnd.home.front_master')
@push('css')
    <link rel="stylesheet" href="{{asset('public/')}}/frontend/css/new_style.css"/>
@endpush
@section('main_content')

    <!--================ Home Banner Area =================-->
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{$newsPage->image != ""? $newsPage->image : '../img/client/common-banner1.jpg'}}) no-repeat center;">
            <div class="banner-inner">
                <div class="banner-content">
                    <h2>{{$newsPage->title}}</h2>
                    <p>{{$newsPage->description}}</p>
                    <a class="primary-btn fix-gr-bg semi-large" href="{{$newsPage->button_url}}">{{$newsPage->button_text}}</a>
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
                    <div class="row all_news">
                        @foreach($news as $value)
                        <div class="col-lg-3 col-md-6 news_count">
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
                    <a class="primary-btn fix-gr-bg semi-large load_more_btn" href="javascript::void(0)">@lang('front_settings.load_more_news')</a>
                </div>
            </div>
        </div>
    </section>
    <!--================End News Area =================-->
@endsection
@section('script')
    <script>
        $(document).on('click', '.load_more_btn', function () {
        var totalNews = $('.news_count').length;
            $.ajax({
                url: "{{route('load-more-news')}}",
                method: "POST",
                data: {
                    skip: totalNews,
                    _token: "{{csrf_token()}}",
                },
                success: function (response) {
                    var hideButton = $('.hide-button').val();
                    var countCourse = $('.count-news').val();
                    for (var count  in response) count++;
                        $(".all_news").append(response);

                    if(countCourse  >= hideButton){
                        $('.load_more_btn').hide();
                    }else{
                        $('.load_more_btn').show();
                    }
                }
            })
        })
    </script>
@endsection
