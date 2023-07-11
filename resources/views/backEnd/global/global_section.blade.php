@extends('backEnd.master')
@section('title')
    @lang('common.section')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.section') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('academics.academics')</a>
                    <a href="#">@lang('common.section')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($section))
                @if (userPermission(266))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{ route('global_section') }}" class="primary-btn small fix-gr-bg">
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
                                <h3 class="mb-30">
                                    @if (isset($section))
                                        @lang('academics.edit_section')
                                    @else
                                        @lang('academics.add_section')
                                    @endif
                                </h3>
                            </div>
                            @if (isset($section))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'global_section_update', 'method' => 'POST']) }}
                            @else
                                @if (userPermission(266))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'global_section_store', 'method' => 'POST']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    @if(isset($parentSection))
                                        <input type="hidden" name="parentSection" value="{{$parentSection}}">
                                    @endif 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label for="levelText">@lang('common.name') <span>*</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}"
                                                    type="text" name="name" autocomplete="off" id="levelText"
                                                    value="{{ isset($section) ? $section->section_name : old('name') }}">
                                                <input type="hidden" name="id"
                                                    value="{{ isset($section) ? $section->id : '' }}">
                                              
                                                
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ @$errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                     
                                    </div>
                                   
                                    @php
                                        $tooltip = '';
                                        if (userPermission(266)) {
                                            $tooltip = '';
                                        } else {
                                            $tooltip = 'You have no permission to add';
                                        }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                <span class="ti-check"></span>
                                                @if (isset($section))
                                                    @lang('academics.update_section')
                                                @else
                                                    @lang('academics.save_section')
                                                @endif

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
                                <h3 class="mb-0">@lang('academics.section_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                           <x-table>
                                <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">

                                    <thead>

                                        <tr>
                                            <th>@lang('common.section')</th>
                                            @if (moduleStatusCheck('MultiBranch') && isset($branches))
                                                <th>@lang('common.branch')</th>
                                            @endif
                                            @if(moduleStatusCheck('University'))
                                                <th>@lang('common.academic')</th>
                                            @endif
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($sections as $section)
                                            <tr>
                                                <td>{{ @$section->section_name }}</td>
                                                @if (moduleStatusCheck('MultiBranch') && isset($branches))
                                                    <td></td>
                                                @endif
                                                @if(moduleStatusCheck('University'))
                                                    <td>{{ $section->unAcademic->name }}</td>
                                                @endif
                                                <td>
                                                    @php
                                                        $routeList = [
                                                            userPermission(267) ? 
                                                            '  <a class="dropdown-item"
                                                                    href="'. route('global_section_edit', [$section->id]).'">'.__('common.edit').'</a>':null,
                                                            
                                                            userPermission(268) ?
                                                                '<a class="dropdown-item" data-toggle="modal"
                                                                    data-target="#deleteSectionModal'.$section->id.'"
                                                                    href="#">'.__('common.delete').'</a>':null,
                                                            
                                                        ]
                                                    @endphp
                                                    <x-drop-down-action-component :routeList="$routeList" />
                                                </td>
                                            </tr>
                                            <div class="modal fade admin-query" id="deleteSectionModal{{ @$section->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('academics.delete_section')</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                                <a href="{{ route('global_section_delete', [@$section->id]) }}"
                                                                    class="text-light">
                                                                    <button class="primary-btn fix-gr-bg"
                                                                        type="submit">@lang('common.delete')</button>
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