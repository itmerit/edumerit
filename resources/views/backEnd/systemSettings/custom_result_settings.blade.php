@extends('backEnd.master')
@section('title')
@lang('exam.custom_result_setting')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.custom_result_setting')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('exam.custom_result_setting')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
          
        <div class="row">
            @php
                @$system_setting=generalSetting();
                @$system_setting=@$system_setting->session_id;

                @$check_exist=App\CustomResultSetting::where('academic_year','=',@$system_setting)->first();
            @endphp
          
            <div class="col-lg-12 mt-20">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">  @lang('exam.custom_result_setting')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                               
                                <tr>
                                    <th>@lang('exam.exam_type')</th>
                                    <th>@lang('exam.percentage')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($custom_settings as $custom_setting)
                                <tr>
                                    <td>{{@$custom_setting->exam_type}}</td>
                                    <td>{{@$custom_setting->percentage}}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')