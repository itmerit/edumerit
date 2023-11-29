@extends('backEnd.master')
@section('title')
@lang('style.color_style')
@endsection
@section('mainContent')
    <style type="text/css">
        .bg-color{
            width: 20px;
            height: 20px;
            text-align: center;
            padding: 0px;
            margin: 0 auto;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('style.color_style')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('style.style')</a>
                    <a href="#">@lang('style.color_style')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('style.color_style_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('common.title')</th>
                                        <th>@lang('style.primary_color')</th>
                                        <th>@lang('style.primary_color2')</th>
                                        <th>@lang('style.primary_color3')</th>
                                        <th>@lang('style.title_color')</th>
                                        <th>@lang('style.text_color')</th>
                                        <th>@lang('style.sidebar_bg')</th>
                                        <th>@lang('common.status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php @$count=1; @endphp
                                @foreach($color_styles as $background_setting)
                                    <tr>
                                        <td>{{@$count++}}</td>
                                        <td>{{@$background_setting->style_name}}</td>
                                        <td>
                                            <div class="row">
                                                <div class=" col-lg-2">
                                                    <div class="bg-color"  style="background: {{@$background_setting->primary_color}}"></div>
                                                </div>
                                                <div class="col-lg-9">{{@$background_setting->primary_color}}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class=" col-lg-2">
                                                    <div class="bg-color"  style="background: {{@$background_setting->primary_color2}}"></div>
                                                </div>
                                                <div class="col-lg-9">{{@$background_setting->primary_color2}}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class=" col-lg-2">
                                                    <div class="bg-color"  style="background: {{@$background_setting->primary_color3}}"></div>
                                                </div>
                                                <div class="col-lg-9">{{@$background_setting->primary_color3}}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class=" col-lg-2">
                                                    <div class="bg-color"  style="background: {{@$background_setting->title_color}}"></div>
                                                </div>
                                                <div class="col-lg-9">{{@$background_setting->title_color}}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class=" col-lg-2">
                                                    <div class="bg-color"  style="background: {{@$background_setting->text_color}}"></div>
                                                </div>
                                                <div class="col-lg-9">{{@$background_setting->text_color}}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class=" col-lg-2">
                                                    <div class="bg-color"  style="background: {{@$background_setting->sidebar_bg}}"></div>
                                                </div>
                                                <div class="col-lg-9">{{@$background_setting->sidebar_bg}}</div>
                                            </div>
                                        </td>
                                        <td>

                                            @if($background_setting->is_active==1)
                                                <label class="primary-btn small fix-gr-bg ">@lang('style.activated')</label>
                                            @else
                                                @if(userPermission(491))
                                                    <a class="primary-btn small tr-bg"
                                                    href="{{route('make-default-theme',@$background_setting->id)}}">
                                                        @lang('style.make_default')</a>
                                                @endif
                                            @endif
                                        </td>
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
