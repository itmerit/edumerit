@extends('frontEnd.home.front_master')
<style>
img{
    max-width: 100%;
}
</style>
@push('css')
    <link rel="stylesheet" href="{{asset('public/')}}/frontend/css/new_style.css"/>
@endpush
@section('main_content')
    @if (@$page->header_image)
    <section class="container box-1420">
        <div class="banner-area" style="background: linear-gradient(0deg, rgba(124, 50, 255, 0.6), rgba(199, 56, 216, 0.6)), url({{@$page->header_image != ""? asset(@$page->header_image) : '../img/client/common-banner1.jpg'}}) no-repeat center;">
            <div class="banner-inner">
                <div class="banner-content">
                    <h2>{{$page->title}}</h2>
                    <p>{{$page->sub_title}}</p>
                </div>
            </div>
        </div>
    </section>
    <section class="academics-area section-gap-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row all_info">
                        <div class="col-lg-12 col-md-12 all_courses">
                            <div class="academic-item">
                                <div class="academic-text text-left">
                                    {!! $page->details !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-12 mt-30">
                <div class="text-center white-box single_registration_area">
                    <div class="reg_tittle mt-20 mb-20">
                        <h2>{{$page->title}}</h2>
                        <p>{{$page->sub_title}}</p>
                    </div>
                        <div class="row mt-20">
                            <div class="col-lg-12 text-left">
                                {!! $page->details !!}
                            </div>
                        </div> 
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection