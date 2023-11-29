@extends('backEnd.master')
    @section('title') 
        @lang('admin.certificate')
    @endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('admin.certificate')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('admin.admin_section')</a>
                <a href="#">@lang('admin.certificate')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('create-certificate')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                        @lang('admin.create_certificate')
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('admin.certificate_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row  ">
                    <div class="col-lg-12">
                        <table id="table_id" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('common.sl')</th>
                                    <th>@lang('admin.title')</th>
                                    <th>@lang('admin.certificate') @lang('admin.type')</th>
                                    <th>@lang('admin.role')</th>
                                    <th>@lang('common.view')</th>
                                    <th>@lang('admin.create_date')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($certificates as $key=>$certificate)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$certificate->title}}</td>
                                        <td>{{$certificate->type}}</td>
                                        <td>{{@$certificate->roleName->name? $certificate->roleName->name : trans('common.student') }}</td>
                                        <td>
                                            <a class="text-color" href="{{route('view-certificate',$certificate->id)}}" target="_blank">@lang('common.view')</a>
                                        </td>
                                        <td>{{dateConvert($certificate->created_at)}}</td>
                                        <td>
                                            <x-drop-down/>
                                                    <a class="dropdown-item" href="{{route('student-certificate-edit',$certificate->id)}}">@lang('common.edit')</a>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteCertificate{{$certificate->id}}" href="">@lang('admin.delete')</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Delete Certificate Start  --}}
                                        <div class="modal fade admin-query" id="deleteCertificate{{$certificate->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('admin.delete_certificate')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                            {{ Form::open(['method' => 'POST','route' =>'student-certificate-delete']) }}
                                                                <input type="hidden" name="id" value="{{$certificate->id}}">
                                                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                            {{ Form::close() }}
                                                        </div>
                                                    </div>
        
                                                </div>
                                            </div>
                                        </div>
                                    {{-- Delete Certificate End  --}}
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