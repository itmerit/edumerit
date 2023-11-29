@extends('backEnd.master')
@section('title')
@lang('inventory.item_category_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('inventory.item_category_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('inventory.inventory')</a>
                <a href="#">@lang('inventory.item_category_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
       @if(isset($editData))
        @if(userPermission("item-category-store"))
           
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('item-category')}}" class="primary-btn small fix-gr-bg">
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
                                    @lang('inventory.edit_item_category')
                                @else
                                    @lang('inventory.add_item_category')
                                @endif
                         
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('item-category-update',$editData->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                         @if(userPermission("item-category-store"))
           
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'item-category',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row"> 
                                    <div class="col-lg-12 mb-20">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('inventory.category_name') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('category_name') ? ' is-invalid' : '' }}"
                                            type="text" name="category_name" autocomplete="off" value="{{isset($editData)? $editData->category_name : Request::old('category_name') }}">
                                            
                                            
                                            @if ($errors->has('category_name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('category_name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>
                 				@php 
                                  $tooltip = "";
                                  if(userPermission("item-category-store")){
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
                    <h3 class="mb-0"> @lang('inventory.item_category_list')</h3>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">
                <x-table>
                <table id="table_id" class="table" cellspacing="0" width="100%">

                    <thead>
                      
                        <tr>
                            <th> @lang('common.sl')</th>
                            <th> @lang('student.category_title')</th>
                            <th> @lang('common.action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($itemCategories))
                        @foreach($itemCategories as $key=>$value)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{$value->category_name}}</td>
                            <td>
                               <x-drop-down>
                                    @if(userPermission('item-category-edit'))
                                        <a class="dropdown-item" href="{{route('item-category-edit',$value->id)}}"> @lang('common.edit')</a>
                                    @endif
                                    @if(userPermission('delete-item-category-view'))
                                        <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="{{ __('inventory.delete_item_category') }}" href="{{route('delete-item-category-view',@$value->id)}}"> @lang('common.delete')</a>
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