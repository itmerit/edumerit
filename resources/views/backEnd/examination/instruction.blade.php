@extends('backEnd.master')
@section('title')
@lang('exam.instruction')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.examinations') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="{{route('instruction')}}">@lang('exam.instruction')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        @if(isset($instruction))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('instruction')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($instruction))
                                @lang('common.edit')
                                @else
                                @lang('common.add')
                                @endif
                                @lang('exam.instruction')
                            </h3>
                        </div>
                        @if(isset($instruction))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('instruction-update'.$instruction->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'instruction',
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
                                            <input class="primary_input_field "
                                                type="text" name="title" autocomplete="off" value="{{isset($instruction)? $instruction->title:''}}">
                                            <input type="hidden" name="id" value="{{isset($instruction)? $instruction->id: ''}}">
                                            <label class="primary_input_label" for="">@lang('common.title') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('title'))
                                            <span class="text-danger" >
                                                {{ $errors->first('title') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" cols="0" rows="4" name="description">{{isset($instruction)? $instruction->description: ''}}</textarea>
                                            <label class="primary_input_label" for="">@lang('common.description') <span class="text-danger"> *</span></label>
                                            <span class="focus-border textarea"> </span>
                                            @if ($errors->has('description'))
                                            <span class="text-danger" >
                                                {{ $errors->first('description') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>
                                            {{!isset($instruction)? 'save':'update'}} @lang('exam.instruction')
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
                            <h3 class="mb-0"> @lang('exam.instruction_list')</h3>
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
                                    <td colspan="3">
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
                                    <th>@lang('common.title')</th>
                                    <th>@lang('common.description')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($instructions as $instruction)
                                <tr>
                                    <td>{{ @$instruction->title}}</td>
                                    <td>{{ @$instruction->description}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                 @lang('common.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{route('instruction-edit', [$instruction->id])}}"> @lang('common.edit')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteRouteModal{{@$instruction->id}}"
                                                    href="#"> @lang('common.delete')</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteRouteModal{{@$instruction->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"> @lang('exam.delete_instruction')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4> @lang('common.are_you_sure_to_detete_this_item')?</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal"> @lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('instruction-delete',$instruction->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                    <button class="primary-btn fix-gr-bg" type="submit"> @lang('common.delete')</button>
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