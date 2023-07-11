@extends('backEnd.master')
@section('title')
Lms design
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('public/backEnd/css/lms-video.css') }}">
@endpush
@section('mainContent')


<!--
     card body te title er por sub hading add hobe 
right button text ac ext



2. arrow hide card with button

http://localhost/infixedu/lms-design -->

<!-- lms design here -->
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="main-title mb-4">
                        <h3 class="mb-0">Course Details</h3>
                    </div>
                </div>
                <div class="col-12">
                    <!-- edu_cart::start  -->
                    <div class="eduLms_video_area">
                        <div class="edu_video_header d-flex align-items-center flex-wrap gap_15">
                            <div class="edu_video_header_left flex-fill">
                                <h3 class="f_s_20 f_w_500 m-0">Managerial Accounting Advance Course</h3>
                            </div>
                            <div class="edu_video_header_right d-flex align-items-center">
                                <div class="auto_text">
                                    <label class="edu_toggle_check" for="Anable">
                                        <input type="checkbox" id="Anable">
                                        <div class="slider round"></div>
                                    </label>
                                    <span class="f_w_500 f_s_16 text-nowrap">Auto Next</span>
                                </div>
                                <div class="next_prev_btn d-flex align-items-center gap_5">
                                    <a href="#" class="primary-btn fix-gr-bg">previous</a>
                                    <a href="#" class="primary-btn fix-gr-bg">Next</a>
                                </div>
                                <div class="rating_text d-flex align-items-center">
                                    <i class="fa fa-star"></i>
                                    <span class="text-nowrap" >Leave a Rating</span>
                                </div>
                                <div class="shair_progress_info d-flex align-items-center">
                                    <div class="progress">
                                        <span class="title timer" data-from="0" data-to="85" data-speed="1800">85</span>
                                        <div class="overlay"></div>
                                        <div class="left"></div>
                                        <div class="right"></div>
                                        <div class="round_border"></div>
                                    </div>
                                    <a href="#" class="primary-btn fix-gr-bg text-nowrap">Share <i class="fa fa-share"></i> </a>
                                </div>
                            </div>
                        </div>
                        <div class="video_palyer_area position-relative d-flex ">
                            <div class="video_palyer_box">
                                <div class="video_screen theme__overlay">
                                    <div class="video_play text-center">
                                        <a href="https://www.youtube.com/watch?v=y1WUmv27fRE" class="play_button popup-video">
                                            <i class="ti-control-play"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="video_palyer_list_wrapper">
                                <div class="infixLms_video_palyer_lists d-flex flex-column postion-relative">
                                    <div class="videoList_toggleBtn">
                                        <i class="ti-menu"></i>
                                    </div>
                                    <div class="video_palyer_lHEad d-flex align-items-center position-relative">
                                        <span class="lisson_arrow">
                                            <i class="ti-arrow-left"></i>
                                        </span>
                                        <h4 class="m-0">05 Leasson</h4>
                                    </div>
                                    <!-- accordian -->
                                    <div class="accordion infixLms_accordian  mb_50 flex-fill" id="accordion_ex">
                                        @include('lms.chapter')
                                        @include('lms.online_exam', ['level' => 0])
<!--                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <h2 class="mb-0">
                                                    <a href="#" class="btn collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapseTwo"
                                                        aria-expanded="false" aria-controls="collapseTwo">
                                                        <h5>Section 1: Getting started </h5>
                                                        <span>02 Lectures</span>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                data-parent="#accordion_ex">
                                                <div class="card-body">
                                                    <div class="video_menu_list">
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingThree">
                                                <h2 class="mb-0">
                                                    <a href="#" class="btn collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapseThree"
                                                        aria-expanded="false" aria-controls="collapseThree">
                                                        <h5>Section 1: Getting started </h5>
                                                        <span>02 Lectures</span>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                                data-parent="#accordion_ex">
                                                <div class="card-body">
                                                    <div class="video_menu_list">
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingThree3">
                                                <h2 class="mb-0">
                                                    <a href="#" class="btn collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapseThree3"
                                                        aria-expanded="false" aria-controls="collapseThree">
                                                        <h5>Section 1: Getting started </h5>
                                                        <span>02 Lectures</span>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div id="collapseThree3" class="collapse" aria-labelledby="headingThree3"
                                                data-parent="#accordion_ex">
                                                <div class="card-body">
                                                    <div class="video_menu_list">
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingThree3">
                                                <h2 class="mb-0">
                                                    <a href="#" class="btn collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapseThree4"
                                                        aria-expanded="false" aria-controls="collapseThree">
                                                        <h5>Section 1: Getting started </h5>
                                                        <span>02 Lectures</span>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div id="collapseThree4" class="collapse" aria-labelledby="headingThree3"
                                                data-parent="#accordion_ex">
                                                <div class="card-body">
                                                    <div class="video_menu_list">
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                        <div class="single_video_menu_list d-flex align-items-center">
                                                            <label class="primary_vcheckbox d-flex flex-fill">
                                                                <input type="checkbox">
                                                                <span class="checkmark mr_15"></span>
                                                                <span class="label_name">  <i class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
                                                            </label>
                                                            <p class="video_time  m-0 text-nowrap">50 Min</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h2 class="mb-0">
                                                    <div href="#" class="btn hideArrow  d-flex align-items-center gap_10" type="button">
                                                        <div class="flex-fill">
                                                            <h5>Section 1: Getting started </h5>
                                                            <span>02 Lectures</span>
                                                        </div>
                                                        <div class="card_btns">
                                                            <a href="#" class="primary-btn fix-gr-bg text-nowrap">Download</a>
                                                        </div>

                                                    </div>
                                                </h2>
                                            </div>
                                        </div>-->
                                    </div>
                                    <!-- accordian -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ edu_cart::end -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- lms design here -->
@endsection