@extends('backEnd.master')
@section('title')
@lang('front_settings.pages')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('front_settings.pages')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('front_settings.front_settings')</a>
                <a href="#">@lang('front_settings.pages')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                @if(userPermission("save-page-data"))
                    <a href="{{route('create-page')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add')
                    </a>
                @endif

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('front_settings.pages_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.title')</th>
                                        <th>@lang('front_settings.sub_title')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                    <tr>
                                        <td>{{$page->title}}</td>
                                        <td>{{@$page->sub_title}}</td>
                                        <td>
                                            <x-drop-down>
                                                    @if(userPermission('view-page'))
                                                        <a class="dropdown-item" href="{{route('view-page', ['slug'=>@$page->slug])}}">@lang('front_settings.preview')</a>
                                                    @endif

                                                    @if(userPermission('edit-page'))
                                                        <a class="dropdown-item" href="{{route('edit-page', [@$page->id])}}">@lang('common.edit')</a>
                                                    @endif

                                                    @if(userPermission('delete-page'))
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#deletePages{{@$page->id}}" href="#">
                                                            @lang('common.delete')
                                                        </a>
                                                    @endif

                                                    @if (@$page->header_image)
                                                        @if(userPermission('download-page'))
                                                            <a class="dropdown-item" href="{{url(@$page->header_image)}}" download>
                                                                @lang('front_settings.download')  
                                                                <span class="pl ti-download"></span>
                                                            </a>
                                                        @endif
                                                    @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deletePages{{@$page->id}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">
                                                        @lang('front_settings.delete_pages')
                                                    </h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>

                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                        {{ Form::open(['route' => array('delete-page',@$page->id), 'method' => 'post',]) }}
                                                            <button class="primary-btn fix-gr-bg" type="submit">
                                                                @lang('common.delete')
                                                            </button>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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