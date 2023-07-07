@extends('backEnd.master')
@section('title')
    @lang('style.color_theme')
@endsection
@section('mainContent')
    <style type="text/css">
        .bg-color {
            width: 20px;
            height: 20px;
            text-align: center;
            padding: 0px;
            margin: 0 12px;
        }

        .color_theme_list {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .color_theme_list > span:first-child{
            max-width: 60%;
            flex: 0 0 100%;
        }

        .color_theme_list .color_preview {
            flex-basis: 40%;
            display: inline-flex;
            justify-content: flex-start;
            align-items: flex-start;
            white-space: nowrap;
            align-items: center;
            justify-content: start;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('style.color_theme')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('style.style')</a>
                    <a href="#">@lang('style.color_theme')</a>
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
                            @if (userPermission('theme-create'))
                                <a class="primary-btn-small-input primary-btn small fix-gr-bg"
                                    href="{{ route('theme-create') }}"><i
                                        class="ti-plus"></i>{{ __('style.Add New Theme') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('common.title')</th>
                                            <th>@lang('common.type')</th>
                                            <th>@lang('style.colors')</th>
                                            <th>@lang('style.Background')</th>
                                            <th>@lang('common.status')</th>
                                            <th>{{ __('common.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php @$count=1; @endphp
                                        @foreach ($themes as $theme)
                                            <tr>
                                                <td>{{ $loop->index +1 }}</td>
                                                <td>{{ $theme->title }}</td>
                                                <td>{{ __('style.' . $theme->color_mode) }}</td>
                                                <td>
                                                    <div class="row">
                                                        @foreach ($theme->colors as $color)
                                                            <div class="col-12">
                                                                <div class="color_theme_list">
                                                                    <span>{{ __('style.' . $color->name) }} </span>


                                                                    <span class="color_preview">: <span class="bg-color"
                                                                            style="background: {{ @$color->pivot->value }}"></span>{{ @$color->pivot->value }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                        @endforeach
                                                    </div>

                                                </td>
                                                <td>
                                                    @if (@$theme->background_type == 'image')
                                                        <div class="bg_img_previw"
                                                            style="background-image : url({{ asset($theme->background_image) }})">

                                                        </div>
                                                    @else
                                                        <div
                                                            style="width: 100px; height: 50px; background-color:{{ @$theme->background_color }} ">
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (@$theme->is_default == 1)
                                                        <span class="primary-btn small fix-gr-bg "> @lang('common.active') </span>
                                                    @else
                                                        @if(userPermission("themes.default"))
                                                            @if (env('APP_SYNC'))
                                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                                    title="Disabled For Demo ">
                                                                    <a class="primary-btn small tr-bg text-nowrap" href="#">
                                                                        @lang('style.Make Default')</a>
                                                                </span>
                                                            @else
                                                                <a class="primary-btn small tr-bg text-nowrap"
                                                                    href="{{ route('themes.default', @$theme->id) }}">
                                                                    @lang('style.Make Default') </a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>

                                                    <div class="dropdown CRM_dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false"> {{ __('common.select') }}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                            aria-labelledby="dropdownMenu2">
                                                            @if (!$theme->is_system)
                                                                <a class="dropdown-item"
                                                                    href="{{ route('themes.edit', $theme->id) }}">@lang('common.edit')</a>
                                                            @endif


                                                            <a class="dropdown-item" type="button"
                                                                href="{{ route('themes.copy', $theme->id) }}">@lang('style.Clone Theme')</a>

                                                            @if (!$theme->is_default && !$theme->is_system && userPermission('themes.destroy'))
                                                                <a class="dropdown-item" type="button" data-toggle="modal"
                                                                    data-target="#deletebackground_settingModal{{ @$theme->id }}"
                                                                    href="#">@lang('common.delete')</a>
                                                            @endif

                                                        </div>
                                                    </div>

                                                </td>

                                                <div class="modal fade admin-query"
                                                    id="deletebackground_settingModal{{ @$theme->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('common.delete')</h4>
                                                                <button type="button" class="close" data-dismiss="modal"> <i
                                                                        class="ti-close"></i>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('style.Are you sure to delete ?')</h4>
                                                                </div>

                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('common.cancel')
                                                                    </button>

                                                                    {!! Form::open(['route' => ['themes.destroy', $theme->id], 'method' => 'delete']) !!}
                                                                    <button type="submit"
                                                                        class="primary-btn fix-gr-bg">@lang('common.delete')</button>
                                                                    {!! Form::close() !!}


                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
