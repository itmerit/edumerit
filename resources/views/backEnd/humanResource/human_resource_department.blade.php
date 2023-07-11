@extends('backEnd.master')
@section('title')
@lang('hr.departments')
@endsection 
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('hr.departments')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('hr.human_resource')</a>
                <a href="#">@lang('hr.departments')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($department))
         @if(userPermission("department-store"))
                        
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('department')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        @endif
        <div class="row">
           

            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($department))
                                    @lang('hr.edit_department')
                                @else
                                    @lang('hr.add_department')
                                @endif
                             
                            </h3>
                        </div>
                        @if(isset($department))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('department-update',$department->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                         @if(userPermission("department-store"))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'department',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('hr.department_name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off" value="{{isset($department)? $department->name:''}}">
                                            <input type="hidden" name="id" value="{{isset($department)? $department->id: ''}}">
                                           
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                               @php 
                                  $tooltip = "";
                                  if(userPermission("department-store")){
                                        $tooltip = "";
                                    }elseif(isset($department) && userPermission('department-edit')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                      <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                          @isset($department)
                                              @lang('hr.update_department')
                                          @else
                                              @lang('hr.save_department')
                                          @endisset
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
                            <h3 class="mb-0">@lang('hr.department_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                               
                                <tr>
                                    <th>@lang('hr.department')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($departments as $department)
                                <tr>
                                    <td>{{$department->name}}</td>
                                    <td>
                                        <x-drop-down>
                                                @if(userPermission('department-edit'))

                                                <a class="dropdown-item" href="{{route('department-edit', [$department->id
                                                    ])}}">@lang('common.edit')</a>
                                               @endif
                                               @if(userPermission('department-delete'))

                                               <a class="dropdown-item" data-toggle="modal" data-target="#deleteHumanDepartModal{{$department->id}}"
                                                    href="#">@lang('common.delete')</a>
                                            @endif
                                               </x-drop-down>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteHumanDepartModal{{$department->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('hr.delete_department')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('department-delete',$department->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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
                    </x-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')