@extends('backEnd.master')

@section('title')
@lang('front_settings.contact_message')
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.contact_message')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.front_settings')</a>
                    <a href="#">@lang('front_settings.contact_message')</a>
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
                                <h3 class="mb-0">@lang('front_settings.contact_message')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 ">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%">@lang('common.name')</th>
                                        <th width="20%">@lang('common.email')</th>
                                        <th width="10%">@lang('common.subject')</th>
                                        <th width="10%">@lang('common.message')</th>
                                        <th width="10%">@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($contact_messages as $contact_message)
                                    <tr>
                                        <td width="10%">{{$contact_message->name}}</td>
                                        <td width="20%">{{$contact_message->email}}</td>
                                        <td width="10%">{{$contact_message->subject}}</td>
                                        <td width="10%">{{$contact_message->message}}</td>
                                        <td width="10%">
                                            @if(userPermission("delete-message"))
                                                <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                                data-target="#deleteDocumentModal{{$contact_message->id}}" href="#">
                                                 <span class="ti-trash"></span>
                                                </a>
                                             @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deleteDocumentModal{{$contact_message->id}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('common.delete_message')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        &times;
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">@lang('common.cancel')
                                                        </button>
                                                        <a class="primary-btn fix-gr-bg"
                                                           href="{{route('delete-message', [$contact_message->id])}}">
                                                            @lang('common.delete')
                                                        </a>
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