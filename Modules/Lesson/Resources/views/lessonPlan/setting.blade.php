@extends('backEnd.master')
@section('title')
    @lang('lesson::lesson.lesson_plan_setting')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lesson::lesson.lesson_plan_setting')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('lesson::lesson.lesson_plan')</a>
                    <a href="{{route('lesson.lessonPlan-setting')}}">@lang('lesson::lesson.lesson_plan_setting')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6">
                    <div class="main-title">
                        <h3 class="mb-30">
                            @lang('lesson::lesson.lesson_plan_setting')
                        </h3>
                    </div>
                </div>
            </div>
            @if(userPermission("lesson.lesson-planner.setting"))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'lesson.lessonPlan-setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">


                        <div class="row mb-30 mt-20">
                            <div class="col-lg-12 d-flex relation-button">
                                <p class="text-uppercase mb-0">@lang('lesson::lesson.lesson_plan_subtopic')</p>
                                <div class="d-flex radio-btn-flex ml-30">
                                    <div class="mr-20">
                                        <input type="radio" name="sub_topic_enable" id="sub_topic_enable" value="1"
                                               class="common-radio relationButton" {{ generalSetting()->sub_topic_enable ? 'checked': ''}}>
                                        <label for="sub_topic_enable">@lang('system_settings.enable')</label>
                                    </div>
                                    <div class="mr-20">
                                        <input type="radio" name="sub_topic_enable" id="sub_topic_disable" value="0"
                                               class="common-radio relationButton" {{ !generalSetting()->sub_topic_enable ? 'checked': ''}}>
                                        <label for="sub_topic_disable">@lang('common.disable')</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">

                                @if(env('APP_SYNC')==TRUE)
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" > @lang('common.update')</button></span>
                                @else
                                    @if(userPermission("lesson.lesson-planner.setting"))
                                        <button type="submit" class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @lang('common.update')
                                        </button>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
        </div>

        </div>
    </section>

@endsection
