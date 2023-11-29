@extends('frontEnd.home.front_master')
@push('css')
    <link rel="stylesheet" href="{{asset('public/')}}/frontend/css/new_style.css"/>
    <style>
        .academic-img{
            height: 220px;
            background-size : cover !important;
            background-position: top center !important; 
        }

        .news-img{
            height: 340px;
            background-size : cover !important;
            background-position: top center !important; 
        }
    </style>
@endpush
@section('main_content')
    <!--================ Home Banner Area =================-->
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{$category_id->category_image != ""? asset($category_id->category_image) : '../img/client/common-banner1.jpg'}}) no-repeat center;">
            <div class="banner-inner">
                <div class="banner-content nowrap">
                    <h2>@lang('front_settings.course_category')</h2>
                    <p>{{$category_id->category_name}}</p>
                </div>
            </div>
        </div>
    </section>
    <!--================ End Home Banner Area =================-->

    <!--================ Course List Area =================-->
    <section class="academics-area section-gap-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="title">@lang('front_settings.course_list')</h3>
                        </div>
                    </div>
                    <div class="row all_info">
                        @foreach($courseCtaegories as $value)
                        <div class="col-lg-4 col-md-6 all_courses">
                            <div class="academic-item">
                                <div class="academic-img" 
                                style="background:  
                                url({{$value->image != ""? asset($value->image) : '../img/client/common-banner1.jpg'}}) 
                                            no-repeat top;">
                                </div>
                                <div class="academic-text">
                                    <h4>
                                        <a href="{{url('course-Details/'.$value->id)}}">{{$value->title}}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- <div class="row text-center mt-30">
                <div class="col-lg-12">
                    <a class="primary-btn fix-gr-bg semi-large load_more_btn" href="javascript::void(0)">@lang('front_settings.load_more_courses')</a>
                </div>
            </div> --}}
        </div>
    </section>
@endsection