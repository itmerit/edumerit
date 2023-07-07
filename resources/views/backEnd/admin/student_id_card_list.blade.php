@extends('backEnd.master')
@section('title')
    @lang('admin.id_card_list')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('admin.id_card')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('admin.admin_section')</a>
                    <a href="#">@lang('admin.id_card')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{ route('create-id-card') }}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('admin.create_id_card')
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('admin.id_card_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row  ">
                        <div class="col-lg-12">
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('common.title')</th>
                                        <th>@lang('common.role')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($id_cards as $key => $id_card)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $id_card->title }}</td>
                                            <td>
                                                @php
                                                    $role_id = $id_card->role_id == 2 ? 2 : 0;
                                                    $role_names = App\SmStudentIdCard::roleName($id_card->id);
                                                @endphp
                                                @foreach ($role_names as $role_name)
                                                    {{ $role_name->name }},
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        @lang('common.select')
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="">@lang('common.edit')</a>

                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#deleteIdCard{{ $id_card->id }}" href="#">
                                                            @lang('common.delete')
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade admin-query" id="deleteIdCard{{ $id_card->id }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('admin.delete_custom_field')</h4>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">
                                                                @lang('common.cancel')
                                                            </button>
                                                            {{ Form::open(['route' => 'student-id-card-delete', 'method' => 'POST']) }}
                                                            <input type="hidden" name="id" value="{{ $id_card->id }}">
                                                            <button class="primary-btn fix-gr-bg"
                                                                type="submit">@lang('common.delete')</button>
                                                            {{ Form::close() }}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
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