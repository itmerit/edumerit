@extends('frontEnd.home.front_master')
@section('main_content')
@push('css')
<style>
    .academic-img{
            height: 220px;
            background-size : cover !important;
            background-position: top center !important; 
        }
</style>
@endpush

    <!--================ Home Banner Area =================-->
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{$course->image != ""? asset($course->image) : '../img/client/common-banner1.jpg'}}) no-repeat center;">
            <div class="banner-inner">
                <div class="banner-content">
                    <h2>{{$course->title}}</h2>
                    {{-- <p>{{$course_details->description}}</p> --}}
                    {{-- <a class="primary-btn fix-gr-bg semi-large" href="{{url($course->button_url)}}">{{$course_details->button_text}}</a> --}}
                </div>
            </div>
        </div>
    </section>
    <!--================ End Home Banner Area =================-->

    <!--================ Course Overview Area =================-->
    <section class="overview-area student-details section-gap-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs mb-50 ml-0" role="tablist">
                        @if ($course->overview)
                            <li class="nav-item">
                                <a class="nav-link active" href="#overviewTab" role="tab" data-toggle="tab">@lang('front_settings.overview')</a>
                            </li>
                        @endif
                        @if ($course->outline)
                            <li class="nav-item">
                                <a class="nav-link" href="#outlineTab" role="tab" data-toggle="tab">@lang('front_settings.outline')</a>
                            </li>
                        @endif
                        @if ($course->prerequisites)
                            <li class="nav-item">
                                <a class="nav-link" href="#prerequisitesTab" role="tab" data-toggle="tab">@lang('front_settings.prerequisites')</a>
                            </li>
                        @endif
                        @if ($course->resources)
                            <li class="nav-item">
                                <a class="nav-link" href="#resourcesTab" role="tab" data-toggle="tab">@lang('front_settings.resources')</a>
                            </li>
                        @endif
                        @if ($course->stats )
                            <li class="nav-item">
                                <a class="nav-link" href="#statsTab" role="tab" data-toggle="tab">@lang('front_settings.stats')</a>
                            </li>
                        @endif
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Start Overview Tab -->
                        <div role="tabpanel" class="tab-pane fade show active" id="overviewTab">
                            <p>
                               {!! $course->overview !!}
                            </p>
                        </div>
                        <!-- End Overview Tab -->

                        <!-- Start Outline Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="outlineTab">
                            <p>
                                {!! $course->outline !!}
                            </p>
                        </div>
                        <!-- End Outline Tab -->

                        <!-- Start Prerequisites Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="prerequisitesTab">
                            <p>
                                {!! $course->prerequisites !!}
                            </p>
                        </div>
                        <!-- End Prerequisites Tab -->

                        <!-- Start Resources Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="resourcesTab">
                            <p>
                                {!! $course->resources !!}
                            </p>
                        </div>
                        <!-- End Resources Tab -->

                        <!-- Start Stats Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="statsTab">
                            <p>
                                {!! $course->stats !!}
                            </p>
                        </div>
                        <!-- End Stats Tab -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ End Course Overview Area =================-->

    <!--================ Related Course Area =================-->
    <section class="academics-area section-gap-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="title">@lang('front_settings.related_courses')</h3>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($courses as $course)
                        <div class="col-lg-4 col-md-6">
                            <div class="academic-item">
                                <div class="academic-img"
                                style="background:  
                                url({{$course->image != ""? asset($course->image) : '../img/client/common-banner1.jpg'}}) 
                                            no-repeat top;">
                                </div>
                                <div class="academic-text">
                                    <h4>
                                        <a href="{{url('course-Details/'.$course->id)}}">{{$course->title}}</a>
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
    <!--================ End Related Course Area =================-->
@endsection

