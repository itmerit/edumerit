@extends('backEnd.master')
@section('title')
@lang('inventory.supplier_list')
@endsection
@section('mainContent')
@push('css')
    <style>
        .invalid-feedback {
            display: inline-block !important;
        }
    </style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('inventory.supplier_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('inventory.inventory')</a>
                <a href="#">@lang('inventory.supplier_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
         @if(isset($editData))
          @if(userPermission('suppliers-store'))
           
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('suppliers')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($editData))
                                    @lang('inventory.edit_supplier')
                                @else
                                    @lang('inventory.add_supplier')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('suppliers-update',$editData->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                         @if(userPermission('suppliers-store'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'suppliers',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                   <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label> @lang('inventory.company_name') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field"
                                            type="text" name="company_name" autocomplete="off" value="{{isset($editData)? $editData->company_name : old('company_name') }}">
                                            
                                            
                                            @if ($errors->has('company_name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('company_name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-15">
                                        <div class="primary_input">
                                            <label> @lang('inventory.company_address') <span class="text-danger"> *</span> </label>
                                            <textarea class="primary_input_field" cols="0" rows="4" name="company_address" id="company_address">{{isset($editData) ? $editData->company_address : old('company_address')}}</textarea>
                                            
                                            
                                            @if ($errors->has('company_address'))
                                            <span class="text-danger custom-server-feedback" role="alert">
                                                {{ $errors->first('company_address') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('inventory.contact_person_name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field"
                                            type="text" name="contact_person_name" autocomplete="off" value="{{isset($editData)? $editData->contact_person_name : old('contact_person_name') }}">
                                           
                                            
                                            @if ($errors->has('contact_person_name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('contact_person_name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('inventory.contact_person_mobile')</label>
                                            <input class="primary_input_field form-control{{ $errors->has('contact_person_mobile') ? ' is-invalid' : '' }}"
                                            type="tel" oninput="phoneCheck(this)" name="contact_person_mobile" autocomplete="off" value="{{isset($editData)? $editData->contact_person_mobile : old('contact_person_mobile') }}">
                                          
                                            
                                            @if ($errors->has('contact_person_mobile'))
                                            <span class="text-danger" >
                                                {{ $errors->first('contact_person_mobile') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('inventory.contact_person_email') <span class="email_info"></span></label>
                                            <input oninput="emailCheck(this)" class="primary_input_field form-control{{ $errors->has('contact_person_email') ? ' is-invalid' : '' }}"
                                            type="email" name="contact_person_email" autocomplete="off" value="{{isset($editData)? $editData->contact_person_email : old('contact_person_email') }}">
                                            @if ($errors->has('contact_person_email'))
                                            <span class="text-danger" >
                                                {{ $errors->first('contact_person_email') }}
                                            </span>
                                            @endif
                                            
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description') <span></span> </label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="4" name="description" id="description">{{isset($editData) ? $editData->description : old('description')}}</textarea>
                                          
                                            
                                        </div>
                                    </div>
                                </div>
                                	  @php 
                                  $tooltip = "";
                                  if(userPermission('suppliers-store') ||userPermission('suppliers-edit')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($editData))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
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
                        <h3 class="mb-0"> @lang('inventory.supplier_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">
                            <thead>
                            
                                <tr>
                                    <th> @lang('inventory.supplier_name')</th>
                                    <th> @lang('inventory.company_name')</th>
                                    <th> @lang('inventory.company_address')</th>
                                    <th> @lang('common.email')</th>
                                    <th> @lang('common.mobile')</th>
                                    <th> @lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($suppliers))
                                @foreach($suppliers as $value)
                                <tr>

                                    <td>{{$value->contact_person_name}}</td>
                                    <td>{{$value->company_name}}</td>
                                    <td>{{$value->company_address}}</td>
                                    <td>{{$value->contact_person_email}}</td>
                                    <td>{{$value->contact_person_mobile}}</td>
                                    <td>
                                    <x-drop-down>
                                            @if(userPermission('suppliers-edit'))
                                                <a class="dropdown-item" href="{{route('suppliers-edit',$value->id)}}"> @lang('common.edit')</a>
                                            @endif
                                            @if(userPermission('suppliers-delete'))
                                                <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="@lang('inventory.delete_supplier')" href="{{route('delete-supplier-view',$value->id)}}"> @lang('common.delete')</a>
                                            @endif
                                    </x-drop-down>
                                    </td>
                                </tr>

                                @endforeach
                                @endif
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