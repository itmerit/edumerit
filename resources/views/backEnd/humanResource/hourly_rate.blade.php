@extends('backEnd.master')
@section('title')
@lang('hr.hourly_rate')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('hr.hourly_rate')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('hourly-rate')}}">@lang('hr.hourly_rate')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        @if(isset($hourly_rate))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{url('human-resource-department')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($hourly_rate))
                                @lang('common.edit')
                                @else
                                @lang('common.add')
                                @endif
                                @lang('hr.hourly_rate')
                            </h3>
                        </div>
                        @if(isset($hourly_rate))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('hourly-rate-update',$hourly_rate->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'hourly-rate',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if(session()->has('message-success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success') }}
                                        </div>
                                        @elseif(session()->has('message-danger'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger') }}
                                        </div>
                                        @endif
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('grade') ? ' is-invalid' : '' }}"
                                                type="text" name="grade" autocomplete="off" value="{{isset($hourly_rate)? $hourly_rate->grade:''}}">
                                            <input type="hidden" name="id" value="{{isset($hourly_rate)? $hourly_rate->id: ''}}">
                                            <label class="primary_input_label" for="">@lang('exam.grade') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('grade'))
                                            <span class="text-danger" >
                                                {{ $errors->first('grade') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control{{ $errors->has('rate') ? ' is-invalid' : '' }}"
                                                type="text" name="rate" autocomplete="off" value="{{isset($hourly_rate)? $hourly_rate->rate:''}}">

                                            
                                            <label class="primary_input_label" for="">@lang('hr.rate') <span class="text-danger"> *</span></label>
                                            @if ($errors->has('rate'))
                                            <span class="text-danger" >
                                                {{ $errors->first('rate') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>
                                            {{isset($hourly_rate)? 'update':'save'}} @lang('hr.rate')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('hr.hourly_rate_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                                @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="4">
                                        @if(session()->has('message-success-delete'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success-delete') }}
                                        </div>
                                        @elseif(session()->has('message-danger-delete'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger-delete') }}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>@lang('exam.grade')</th>
                                    <th>@lang('hr.rate')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($hourly_rates as $hourly_rate)
                                <tr>
                                    <td>{{$hourly_rate->grade}}</td>
                                    <td>{{$hourly_rate->rate}}</td>
                                    <td>
                                        <x-drop-down/>
                                                <a class="dropdown-item" href="{{route('hourly-rate-edit', [$hourly_rate->id
                                                    ])}}">@lang('common.edit')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteHourlyRateModal{{$hourly_rate->id}}"
                                                    href="#">@lang('common.delete')</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteHourlyRateModal{{$hourly_rate->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('common.delete_hourly_rate')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')?</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('hourly-rate-delete',$hourly_rate->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
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