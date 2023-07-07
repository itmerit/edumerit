@extends('backEnd.master')
@section('title') 
@lang('fees.fees_master')
@endsection
@section('mainContent')
@push('css')
<style>
    .custom_fees_master{
        border-bottom: 1px solid #d9dce7; 
        padding-top: 5px;
    }

   
    .dloader_img_style{
        width: 40px;
        height: 40px;
    }

    .dloader {
        display: none;
    }

    .pre_dloader {
        display: block;
    }

    .up_st_admin_visitor .input-right-icon button i.ti-calendar {
        top: 10px;
    }

    .up_st_admin_visitor .input-right-icon button i.ti-calendar.upper-icon {
        top: 15px;
    }

    .up_st_admin_visitor .input-right-icon button i.ti-calendar.lower-icon {
        top: 10px;
    }

    .primary-btn.icon-only.delete-row {
        top: 7px;
    }
</style>
@endpush
@php  
    $setting = app('school_info'); 
    if(!empty($setting->currency_symbol)) {
        $currency = $setting->currency_symbol;
    } else { 
        $currency = '$'; 
    } 
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.fees_master')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('fees.fees_collection')</a>
                <a href="#">@lang('fees.fees_master')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($fees_master))
         @if(userPermission('fees-master-store'))
                       
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('fees-master')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        @endif
        <div class="row">
            <div class="col-lg-4">
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($fees_master))
                                    @lang('fees.edit_fees_master')
                                @else
                                    @lang('fees.add_fees_master')
                                @endif
                                  
                            </h3>

                            
                        </div>
                        
                        @if(isset($fees_master))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true,  'route' => array('fees-master-update',$fees_master->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'fees_master_form']) }}
                        @else
                         @if(userPermission("fees-master-store"))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-master',
                        'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'fees_master_form']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">                               
                                @if($errors->any())
                                    <div class="error text-danger ">{{ 'Something went wrong, please try again' }}</div>
                                @endif
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off" value="{{isset($fees_master)? @$fees_master->feesTypes->name: ''}}">
                                            
                                            @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{isset($fees_master)? $fees_master->id: ''}}">
                                <input type="hidden" name="fees_group_id" value="{{isset($fees_master)? $fees_master->fees_group_id: ''}}">
                                <input type="hidden" name="fees_type" value="{{isset($fees_master)? $fees_master->fees_type_id: ''}}">
                                @if(! directFees())
                                    <div class="primary_datepicker_input">
                                        <div class="row no-gutters input-right-icon mt-25">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('fees.due_date') <span></span></label>
                                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" id="startDate" type="text" name="date" value="{{isset($fees_master)? date('m/d/Y', strtotime($fees_master->date)) : date('m/d/Y')}}">
                                                        
                                                    <button class="btn-date" style="top: 70% !important;" data-id="#date_of_birth" type="button">
                                                        <label class="m-0 p-0" for="date_of_birth">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                    @if ($errors->has('date'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('date') }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        
                                        

                                        </div>
                                    </div>
                                @endif 

                                    @if(isset($fees_master))
                                        <div class="row  mt-25" id="fees_master_amount">
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('fees.amount') <span class="text-danger"> *</span></label>
                                                    <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" type="text" name="amount" autocomplete="off" value="{{isset($fees_master)? $fees_master->amount:''}}">
                                                    @error('amount')
                                                        <span class="text-danger">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row  mt-25" id="fees_master_amount">
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('fees.amount') <span class="text-danger"> *</span></label>
                                                    <input oninput="numberCheckWithDot(this)" class="primary_input_field form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                        type="text" name="amount" autocomplete="off" value="{{isset($fees_master)? $fees_master->amount:''}}" id="fees_amount">
                                                    
                                                    
                                                    @if ($errors->has('amount'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('amount') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                   
                                    <div class="row mt-25">
                                        <div class="col-lg-12 mb-30">
                                            <label class="primary_input_label" for="">@lang('common.select_class')<span class="text-danger"> *</span></label>
                                            <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                                <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                                    @foreach($classes as $class)
                                                        @if(isset($fees_master))
                                                            <option value="{{$class->id}}"  {{( $fees_master->class_id == $class->id ? "selected":"")}}>{{$class->class_name}}</option> 
                                                        @else 
                                                            <option value="{{$class->id}}"  {{( old("class") == $class->id ? "selected":"")}}>{{$class->class_name}}</option> 
                                                        @endif    
                                                    @endforeach
                                            </select>
                                            @if ($errors->has('class'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('class') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-lg-12 mb-30" id="select_section__member_div">
                                            <label class="primary_input_label" for="">@lang('common.select_section')</label>
                                            <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section_member" name="section_id">
                                                <option data-display="@lang('fees.all_section')" value="all_section">@lang('fees.all_section')</option>
                                                @if(isset($fees_master))
                                                
                                                    @if(is_null($fees_master->section_id))
                                                        <option selected value="all_section">@lang('fees.all_section')</option>
                                                    @else 
                                                        <option value="{{@$fees_master->section_id}}"  selected >{{@$fees_master->section->section_name}}</option>
                                                    @endif 
                                                @endif
                                            </select>
                                                @if ($errors->has('section'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('section') }}
                                                </span>
                                                @endif
                                            <div class="pull-right loader loader_style" id="select_section_loader">
                                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-25">
                                        <div class="col-lg-12">        
                                            <div class="row">
                                                    <div class="col-lg-10">
                                                    <div class="main-title">
                                                        <h4>{{ __('fees.instalment') }}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    @if(isset($fees_master))
                                                        @if($fees_master->installments)
                                                            <button type="button" class="primary-btn icon-only fix-gr-bg" onclick="addRowMark();" id="addRowUn">
                                                            <span class="ti-plus pr-2"></span></button>
                                                        @endif
                                                    @else  
                                                        <button type="button" class="primary-btn icon-only fix-gr-bg" onclick="addRowMark();" id="addRowUn">
                                                        <span class="ti-plus pr-2"></span></button>   
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <table class="table" id="productTable">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('fees.title')</th>
                                                        <th>@lang('fees.due_date')</th>
                                                        <th>@lang('fees.amount')</th>
                                                      
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(isset($fees_master)) 
                                                    @php $i = 0; $totalPercentage = 0; @endphp
                                                        @foreach ($fees_master->installments as $installment)
                                                        @php $i++; $totalPercentage += $installment->percentange; @endphp
                                                        <tr id="row1" class="mt-40">
                                                            <td class="border-top-0">
                                                                <input type="hidden" value="{{$installment->id}}" id="installment_id" name="installment_id[]">
                                                                <div class="primary_input">
                                                                  
                                                                    <input type="hidden" value="@lang('common.title')" id="lang">
                                                                    <input class="primary_input_field "
                                                                        type="text" id="title" name="title[]" autocomplete="off" value="{{@$installment->title}}">
                                                                       
                                                                </div>
                                                            </td>
                                                            <td class="border-top-0" style="width:40%">
                                                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                                                <div class="row no-gutters input-right-icon">
                                                                    <div class="col">
                                                                        <div class="primary_input">
                                                                            <input class="primary_input_field  primary_input_field date form-control {{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                                                   name="due_date[]"
                                                                                   value="{{isset($installment)? date('m/d/Y', strtotime($installment->due_date)): date('m/d/Y')}}">
                                                                           
                                                                            
                                                                            @if ($errors->has('due_date'))
                                                                                <span class="text-danger" >
                                                                                    {{ $errors->first('due_date') }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button class="" type="button">
                                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="border-top-0" style="width:25%">
                                                                <div class="primary_input">
                                                                    <input oninput="numberCheck(this)" class="primary_input_field form-control{{ $errors->has('unPercentage') ? ' is-invalid' : '' }} unPercentage"
                                                                            type="text" id="unPercentage" name="unPercentage[]" autocomplete="off"  onkeypress="return isNumberKey(event)" {{ $fees_master->installmentAssign ? 'readonly' :'' }} value="{{ isset($installment) ? $installment->percentange : 0 }}">
                                                                            @if ($errors->has('unPercentage'))
                                                                            <span class="text-danger" >
                                                                                {{ $errors->first('unPercentage') }}
                                                                            </span>
                                                                            @endif
                                                                </div>
                                                            </td>
                                                            @if(!$fees_master->installments)
                                                             <td class="border-0" style="width:10%">                               
                                                                <button class="primary-btn icon-only fix-gr-bg delete-row" type="button" id="{{$i != 1? 'removeInPercentage':''}}">
                                                                        <span class="ti-trash"></span>
                                                                </button>
                                                                
                                                            </td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                    <tr id="row1" class="mt-40">
                                                        <td class="border-top-0">
                                                              
                                                            <div class="primary_input">
                                                                <input type="hidden" value="@lang('common.title')" id="lang">
                                                                <input type="hidden" name="installment_id[]" value="0">
                                                               
                                                                <input class="primary_input_field form-control"
                                                                    type="text" id="title" name="title[]" autocomplete="off" value="{{@$installment->title}}">
                                                                    
                                                            </div>
                                                        </td>
                                                        <td class="border-top-0" style="width:45%">
                                                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                                            <div class="row no-gutters input-right-icon">
                                                                <div class="col">
                                                                    <div class="primary_input">
                                                                        <input class="primary_input_field  primary_input_field date form-control {{ $errors->has('date') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                                               name="due_date[]"
                                                                               value="{{date('m/d/Y')}}">
                                                                       
                                                                        
                                                                        @if ($errors->has('date'))
                                                                            <span class="text-danger" >
                                                                                {{ $errors->first('date') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button class="btn-date" data-id="#startDate" type="button">
                                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="border-top-0" style="width: 15%">
                                                            <div class="primary_input">
                                                               
                                                                <input oninput="numberCheck(this)" class="primary_input_field form-control{{ $errors->has('unPercentage') ? ' is-invalid' : '' }} unPercentage"
                                                                        type="text" id="unPercentage" name="unPercentage[]" autocomplete="off"  onkeypress="return isNumberKey(event)"  value="0">
                                                            </div>
                                                        </td>
                                                        <td class="border-0" style="width: 10%">
                                                            
                                                            <button class="primary-btn icon-only fix-gr-bg delete-row" type="button">
                                                                <span class="ti-trash"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                        
                                                    @endif
                                        
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td class="border-top-0"></td>
                                                        <td class="border-top-0">@lang('exam.total')</td>
                                                        <td class="border-top-0" id="totalPercentage">
                                                            <input type="text" class="primary_input_field form-control{{ $errors->has('totalPercentage') ? ' is-invalid' : '' }}" name="totalInstallmentAmount" value="{{ isset($fees_master) ? $totalPercentage :''}}" readonly="true">
                                                            @if ($errors->has('totalInstallmentAmount'))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first('totalInstallmentAmount') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0"></td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>       
                                        </div>
                                    </div>
                    

	                            @php 
                                  $tooltip = "";
                                  if(userPermission("fees-master-store")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp

                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($fees_master))
                                                @lang('fees.update_fees_master')
                                            @else
                                                @lang('fees.save_fees_master')
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
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('fees.fees_master_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                                <thead>
                                 
                                    <tr>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('fees.amount')</th>
                                        <th>@lang('fees.installment')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                    @foreach($fees_masters as $values)
                                       
                                    <tr>
                                        <td valign="top">
                                            @php $i = 0; @endphp
                                            @foreach($values as $fees_master)
                                            @php $i++; @endphp
                                            @if($i == 1)
                                                {{@$fees_master->feesGroups->name}}  
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($values as $fees_master)
     
                                            <div class="row">
                                                  
                                                    <div class="col-sm-2  nowrap">
                                                         {{ currency_format((float)$fees_master->amount)}}
                                                    </div>
                                               
                                            </div>
    
                                             @endforeach
                                        </td>
                                        <td>
                                            @foreach($values as $fees_master)
                                                @foreach ($fees_master->installments as $instalment)
                                                {{ $instalment->title .'['. $instalment->due_date .']'. '['. (( $instalment->percentange)) .']' }} <br>
                                                @endforeach
                                            @endforeach
                                        </td>
                                        <td valign="top">
                                            @php $i = 0; @endphp
                                            @foreach($values as $fees_master)
                                            @php $i++; @endphp
                                            @if($i == 1)
                                            <div class="dropdown CRM_dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if(userPermission(120))
                                                        <a class="dropdown-item" href="{{route('fees-master-edit', [$fees_master->id])}}">@lang('common.edit')</a>
                                                    @endif
                                                    <a class="dropdown-item deleteFeesMasterGroup" data-toggle="modal" href="#" data-id="{{$fees_master->id}}" data-target="#deleteFeesMasterGroup{{$fees_master->
                                                    id}}">@lang('common.delete')</a>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="modal fade admin-query" id="deleteFeesMasterGroup{{$fees_master->id}}" >
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('fees.delete_fees_master')</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
    
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>
    
                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                                {{ Form::open(['url' => 'fees-master-group-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                                                <input type="hidden" name="id" value="{{$fees_master->id}}">
                                                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                                {{ Form::close() }}
                                                            </div>
                                                        </div>
    
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
    
                                        </td>
                                    </tr>
                                       
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


        </div>
    </div>
</div>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
@push('scripts')
<script src="{{ asset('Modules/University/Resources/assets/js/app.js') }}"></script>
<script>
        // add new row for marks distribution
        addRowMark = () => {
        $("#addRowUn").button("loading");
        var tableLength = $("#productTable tbody tr").length;
        var url = $("#url").val();
        var lang = $("#lang").val();
        var tableRow;
        var arrayNumber;
        var count;
        if (tableLength > 0) {
            tableRow = $("#productTable tbody tr:last").attr("id");
            arrayNumber = $("#productTable tbody tr:last").attr("class");
            count = tableRow.substring(3);
            count = Number(count) + 1;
            arrayNumber = Number(arrayNumber) + 1;
        } else {
            // no table row
            count = 1;
            arrayNumber = 0;
        }
        let row_count = parseInt($('#row_count').val());

        $("#addRowUn").button("reset");
        var newRow = `<tr id="row1" class="mt-40">
                            <td class="border-top-0">
                              
                              <div class="primary_input">
                                    <input type="hidden" name="installment_id[]" value="0">
                                  <input type="hidden" value="@lang('common.title')" id="lang">
                                  <input class="primary_input_field "
                                      type="text" id="title" name="title[]" autocomplete="off" >
                              </div>
                          </td>
                        <td class="border-top-0">
                            <div class="row no-gutters input-right-icon">
                                <div class="col">
                                    <div class="primary_input">
                                        <input class="primary_input_field  primary_input_field date form-control has-content" id="startDate${tableLength}" type="text"
                                               name="due_date[]"
                                               value="{{isset($visitor)? date('m/d/Y', strtotime($visitor->date)): date('m/d/Y')}}">
                                        
                                        @if ($errors->has('date'))
                                            <span class="text-danger" >
                                                {{ $errors->first('date') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="" type="button">
                                        <label class="m-0 p-0" for="startDate${tableLength}">
                                            <i class="ti-calendar lower-icon" id="start-date-icon"></i>
                                        </label>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="border-top-0">
                            <div class="primary_input">
                                <input oninput="numberCheck(this)" class="primary_input_field form-control{{ $errors->has('unPercentage') ? ' is-invalid' : '' }} unPercentage"
                                        type="text" id="unPercentage" name="unPercentage[]" autocomplete="off"  onkeypress="return isNumberKey(event)"  value="{{isset($editData)? $editData->unPercentage : 0 }}">
                            </div>
                        </td>
                        <td class="border-0">
                            <button class="primary-btn icon-only fix-gr-bg delete-row" type="button">
                                <span class="ti-trash" id='removeInPercentage'></span>
                            </button>
                        </td>
                    </tr>`;

     
        $('#row_count').val(row_count + 1);
        if (tableLength > 0) {
            $("#productTable tbody tr:last").after(newRow);
        } else {
            $("#productTable tbody").append(newRow);
        }

        $(".primary_input_field.date").datepicker({
            autoclose: true,
            setDate: new Date(),
        });
        
        $(".common-select").addClass("new_select_css");
    };
    // Assign class routine get subject
    $(document).on("click", "#removeInPercentage", function(event) {
        $(this).closest("tr").remove();
        var totalPercentage = 0;
        $('tr#row1 input[name^="unPercentage"]').each(function() {
            if ($(this).val() != "") {
                totalPercentage += parseInt($(this).val());
            }
        });

        $("th#totalPercentage input").val(totalPercentage);
    });

    $(document).on("keyup", ".unPercentage", function(event) {
        var totalPercentage = 0;
        var fees_amount = $('#fees_amount').val();
        $('tr#row1 input[name^="unPercentage"]').each(function() {
            if ($(this).val() != "") {
                totalPercentage += parseInt($(this).val());
                if(fees_amount < totalPercentage){
                    alert("you have distributed instalment more than fees master amount");
                    //  $('#fees_amount').val(totalPercentage);
                     $(":submit").attr("disabled", true);
                }else{
                    $(":submit").attr("disabled", false);
                }
                
            }
        });

        if (totalPercentage > parseInt($("#unPercentage_main").val())) {
            alert("you have distributed instalment more than 100");
            $(this).val(0);
            var totalPercentage = 0;
            var fees_master_amount = $("#fees_master_amount").val();
            $('tr#row1 input[name^="unPercentage"]').each(function() {
                if ($(this).val() != "") {
                    totalPercentage += parseInt($(this).val());
                }
            });
            $("th#totalPercentage input").val(totalPercentage);
            return false;
        }

        $("td#totalPercentage input").val(totalPercentage);
    });
</script>
@endpush
