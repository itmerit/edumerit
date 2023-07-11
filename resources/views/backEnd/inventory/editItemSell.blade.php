@extends('backEnd.master')
@section('title')
@lang('inventory.edit_sells_details')
@endsection
@section('mainContent')
<style type="text/css">
#productTable tbody tr{
    border-bottom: 1px solid #FFFFFF !important;
}
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('inventory.edit_sells_details')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('inventory.inventory')</a>
                <a href="{{route('item-sell-list')}}">@lang('inventory.item_sell_list')</a>
                <a href="#">@lang('inventory.edit_sells_details')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area">
<div class="container-fluid p-0">

   {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-item-sell-data', 'enctype' => 'multipart/form-data', 'id' => 'edit-item-sell-form']) }}

   <input type="hidden" name="id" value="{{$editData->id}}">
   <div class="row">
    <div class="col-lg-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-title">
                    <h3 class="mb-30">
                        @lang('inventory.edit_sells_details')
                    </h3>
                </div>

                <div class="white-box">
                    <div class="add-visitor">
                        <div class="row">
                        <div class="col-lg-12 mb-30">
                                <select class="primary_select  form-control{{ $errors->has('income_head_id') ? ' is-invalid' : '' }}" name="income_head_id" id="income_head_id">
                                    <option data-display="@lang('accounts.payroll')*" value="">@lang('common.select') *</option>
                                    @foreach($sell_heads as $sell_head)
                                    <option value="{{$sell_head->id}}" {{$sell_head->id == $editData->income_head_id? 'selected':''}}>{{$sell_head->head}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger" id="incomeError"></div>
                                @if ($errors->has('income_head_id'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('income_head_id') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-12 mb-30">
                                <select class="primary_select  form-control" name="payment_method" id="edit_sell_payment_method">
                                    @if($editData->paymentMethodName->method =="Bank")
                                    <option data-string="{{@$editData->paymentMethodName->method}}" value="{{@$editData->payment_method}}" selected>{{@$editData->paymentMethodName->method}}</option>
                                    @else
                                    @foreach($paymentMethhods as $key=>$value)
                                    @if(isset($editData))
                                    <option data-string="{{$value->method}}" value="{{$value->id}}"
                                        {{@$editData->payment_method == $value->id? 'selected': ''}}>{{$value->method}}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-12 mb-30 d-none" id="edit_item_sell_bankAccount">
                                        <div class="primary_input">
                                            <select class="primary_select  form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}" name="bank_id" id="account_id">
                                            @if(isset($editData))
                                                    <option value="{{$editData->account_id}}" selected>{{@$editData->bankName->account_name}} ({{@$editData->bankName->bank_name}})</option>
                                            @endif
                                                </select>
                                                
                                                @if ($errors->has('bank_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('bank_id') }}
                                                </span>
                                                @endif
                                        </div>
                                    </div>
                            <div class="col-lg-12 mb-30">
                                <select class="primary_select  form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" id="buyer_type">
                                    <option data-display="@lang('inventory.sell_to') *" value="">@lang('inventory.sell_to') *</option>
                                    @foreach($roles as $value)
                                    <option value="{{$value->id}}" {{$value->id == $editData->role_id? 'selected':''}}>{{$value->name}}</option>

                                    @endforeach
                                </select>
                                <div class="text-danger" id="buyerError"></div>
                                @if ($errors->has('role_id'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('role_id') }}
                                </span>
                                @endif
                            </div>

                            <div class="col-lg-12 {{$editData->role_id == 2? 'displayBlock':'displayNone'}}" id="student-div">
                                <div class="row">
                                    <div class="col-lg-12 mb-30">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                                @foreach($classes as $class)
                                                <option value="{{$class->id}}" {{$editData->role_id == 2? ($class->id == @$studentClassSection->defaultClass->class_id? 'selected':''):""}}>{{$class->class_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="classError"></div>
                                            @if ($errors->has('class'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('class') }}
                                            </span>
                                            @endif
                                        </div>

                                        <div class="col-lg-12 mb-30" id="select_section_div">

                                         @if($editData->role_id == 2)
                                         <select class="primary_select form-control {{ $errors->has('section') ? ' is-invalid' : '' }}" id="section" name="section">
                                            <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                            @foreach($sections as $section)
                                            <option value="{{$section->id}}"  {{( old("section") == $section->id ? "selected":"")}}
                                                @if($editData)
                                                @if(@$studentClassSection->defaultClass->section_id == $section->id)
                                                selected
                                                @endif
                                                @endif
                                                >{{$section->section_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="sectionError"></div>
                                            @else
                                            <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                                <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                            </select>
                                            @endif

                                            @if ($errors->has('section'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('section') }}
                                            </span>
                                            @endif
                                        </div>


                                        <div class="col-lg-12 mb-30" id="select_student_div">
                                         @if($editData->role_id == 2)
                                        
                                            <select class="primary_select form-control {{ $errors->has('student') ? ' is-invalid' : '' }}" id="student" name="student">
                                                <option data-display="@lang('common.select_student') *" value="">@lang('common.select_student') *</option>
                                                @foreach($allStudentsSameClassSection as $student)
                                                <option value="{{$student->id}}"  {{( old("student") == $student->id ? "selected":"")}}
                                                    @if($editData)
                                                    @if($editData->student_staff_id == $student->id)
                                                    selected
                                                    @endif
                                                    @endif
                                                    >{{$student->full_name}}</option>
                                                    @endforeach
                                                </select>
                                                @else

                                                <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="select_student" name="student">
                                                    <option data-display="@lang('common.select_student') *" value="">@lang('common.select_student') *</option>
                                                </select>
                                                @endif          
                                                <div class="text-danger" id="studentError"></div>

                                                @if ($errors->has('student'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('student') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-30 {{$editData->role_id != 2? 'displayBlock':'displayNone'}}" id="staff-div">
                                        <select class="primary_select  form-control{{ $errors->has('staff_id') ? ' is-invalid' : '' }}" name="staff_id" id="selectStaffs">
                                            <option data-display="@lang('common.name') *" value="">@lang('common.name') *</option>

                                            @if($staffsByRole != "")

                                            @foreach($staffsByRole as $value)

                                            @if($editData->role_id == 3)
                                                <option value="{{$value->id}}" 
                                            {{$value->id == $editData->student_staff_id? 'selected':''}}
                                            >{{$value->fathers_name}}</option>
                                            @else
                                                <option value="{{$value->id}}" 
                                            {{$value->id == $editData->student_staff_id? 'selected':''}}
                                            >{{$value->full_name}}</option>
                                            @endif

                                            @endforeach

                                            @endif
                                        </select>
                                        <div class="text-danger" id="nameError"></div> 
                                        @if ($errors->has('staff_id'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('staff_id') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-12 mb-20">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{{ $errors->has('reference_no') ? ' is-invalid' : '' }}"
                                            type="text" name="reference_no" autocomplete="off" value="{{isset($editData)? $editData->reference_no : '' }}">
                                            <label class="primary_input_label" for="">@lang('inventory.reference_no') <span></span> </label>
                                            
                                            @if ($errors->has('reference_no'))
                                            <span class="text-danger" >
                                                {{ $errors->first('reference_no') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 no-gutters input-right-icon mb-30">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('sell_date') ? ' is-invalid' : '' }}"  id="sell_date" type="text"
                                                name="sell_date" value="{{isset($editData)? date('m/d/Y', strtotime($editData->sell_date)): ''}}" autocomplete="off">
                                                <label class="primary_input_label" for="">@lang('inventory.sell_date')<span></span> </label>
                                                
                                                @if ($errors->has('sell_date'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('sell_date') }}
                                                </span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="receive_date_icon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-20">
                                        <div class="primary_input">
                                            <textarea class="primary_input_field form-control" cols="0" rows="4" name="description" id="description">{{isset($editData) ? $editData->description : ''}}</textarea>
                                            <label class="primary_input_label" for="">@lang('common.description') <span></span> </label>
                                            

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-lg-9">
                    
              <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('inventory.item_sale')</h3>
                    </div>
                </div>

                <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                    <button type="button" class="primary-btn small fix-gr-bg" onclick="addRowInSell();" id="addRowBtn">
                        <span class="ti-plus pr-2"></span>
                        @lang('common.add') 
                    </button>
                </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
               <div class="white-box">

                <div class="alert alert-danger" id="errorMessage2">
                    <div id="itemError"></div>
                    <div id="priceError"></div>
                    <div id="quantityError"></div>                     
                </div>
                   <table class="table" id="productTable">
                    <thead>
                      <tr>
                          <th>@lang('inventory.product_name')</th>
                          <th>@lang('inventory.sell_price')</th>
                          <th>@lang('inventory.quantity')</th>
                          <th>@lang('inventory.sub_total')</th>
                          <th>@lang('common.action')</th>
                      </tr>
                  </thead>
                  <tbody>
                  @php $i = 0; $j=0; $total_quantity = 0; $grand_total = 0; @endphp
                  @if(isset($editDataChildren))
                  @foreach($editDataChildren as $editDataValue)
                      <tr id="row{{++$i}}" class="{{$j++}}">
                        <td>
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                            <div class="primary_input">
                                <select class="primary_select  form-control{{ $errors->has('item_id') ? ' is-invalid' : '' }}" name="item_id[]" id="productName{{$j}}">
                                    <option data-display="@lang('common.select_item') " value="">@lang('common.select')</option>
                                    @foreach($items as $key=>$value)
                                    <option value="{{$value->id}}"
                                        @if(isset($editDataChildren))
                                        @if($editDataValue->item_id == $value->id)
                                        selected
                                        @endif
                                        @endif
                                        >{{$value->item_name}}</option>
                                        @endforeach
                                    </select>
                                    
                                    @if ($errors->has('item_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('item_id') }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="primary_input">
                                    <input class="primary_input_field form-control{{ $errors->has('sell_price') ? ' is-invalid' : '' }}"
                                    type="text" id="unit_price{{$i}}" name="unit_price[]" autocomplete="off" value="{{isset($editDataChildren)? $editDataValue->sell_price : '' }}" onkeyup="getTotalByPrice({{$i}})">

                                    
                                    @if ($errors->has('sell_price'))
                                    <span class="text-danger" >
                                        {{ $errors->first('sell_price') }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="primary_input">
                                    <input class="primary_input_field form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                    type="text" id="quantity{{$i}}" name="quantity[]" autocomplete="off" onkeyup="getTotalInSell({{$i}});" value="{{isset($editDataChildren)? $editDataValue->quantity : '' }}">

                                    
                                    @if ($errors->has('quantity'))
                                    <span class="text-danger" >
                                        {{ $errors->first('quantity') }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="primary_input">
                                    <input class="primary_input_field form-control{{ $errors->has('sub_total') ? ' is-invalid' : '' }}"
                                    type="text" name="total[]" id="total{{$i}}" autocomplete="off" value="{{isset($editDataChildren)? number_format( (float) $editDataValue->sub_total, 2, '.', '') : '' }}">

                                    
                                    @if ($errors->has('sub_total'))
                                    <span class="text-danger" >
                                        {{ $errors->first('sub_total') }}
                                    </span>
                                    @endif
                                </div>
                                <input type="hidden" name="totalValue[]" id="totalValue{{$i}}" autocomplete="off" class="form-control" value="{{isset($editDataChildren)? $editDataValue->sub_total : '' }}"/>
                            </td>
                            <td><a>
                                <button class="primary-btn icon-only fix-gr-bg" type="button">
                                 <span class="ti-trash" id="removeSubject" onclick="deleteSubject(4)"></span>
                                 </button>
                            </a></td>
                        </tr>
                        @php 
                          $total_quantity += $editDataValue->quantity;
                          $grand_total += $editDataValue->sub_total; 
                        @endphp
                        @endforeach
                        @endif
                        <tfoot>
                            <tr>
                               <th colspan="2">@lang('exam.result')</th>
                               <th>
                                   
                                   <input type="text" class="primary_input_field form-control" id="subTotalQuantity" name="subTotalQuantity" placeholder="0" readonly="" value="{{isset($editDataChildren)? $total_quantity : '' }}"/>

                                   <input type="hidden" class="form-control" id="subTotalQuantityValue" value="{{isset($editDataChildren)? $total_quantity : '' }}"  name="subTotalQuantityValue" />

                               </th>

                               <th>
                                   <input type="text" class="primary_input_field form-control" id="subTotal" name="subTotal" placeholder="0.00" readonly="" 
                                   value="{{ number_format( (float) $grand_total, 2, '.', '') }}"
                                   />

                                   <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" value="{{ number_format( (float) $grand_total, 2, '.', '') }}"/>

                               </th>
                               <th></th>
                           </tr>
                       </tfoot>

                   </tbody>
               </table>
           </div>
       </div>
   </div>

   <div class="row mt-30">
    <div class="col-lg-12">
        <div class="white-box">

            <div class="row">
              <div class="col-lg-4 mt-30-md">
               <div class="col-lg-12">
                <div class="primary_input">

                    <input type="checkbox" id="full_paid" class="common-checkbox form-control{{ $errors->has('full_paid') ? ' is-invalid' : '' }}" name="full_paid" value="1" @if($editData->paid_status == 'P')
                    checked
                    @endif>                    
                    <label for="full_paid">@lang('inventory.full_paid')</label>
                </div>
            </div>
        </div>  

        <div class="col-lg-4 mt-30-md">
           <div class="col-lg-12">
            <div class="primary_input">
            <input class="primary_input_field" type="text"  name="totalPaid" id="totalPaid" onkeyup="paidAmount();" value="{{isset($editData)? $editData->total_paid : '' }}">
                <input type="hidden" id="totalPaidValue" name="totalPaidValue">
                <label class="primary_input_label" for="">@lang('inventory.total_paid')</label>
                
            </div>
        </div>
    </div>
    <div class="col-lg-4 mt-30-md">
       <div class="col-lg-12">
        <div class="primary_input">
            <input class="primary_input_field" type="text" value="{{isset($editData)? number_format( (float) $editData->total_due, 2, '.', '') : '' }}" id="totalDue" readonly>
            <input type="hidden" id="totalDueValue" name="totalDueValue" value="{{isset($editData)? $editData->total_due : '' }}">
            <label class="primary_input_label" for="">@lang('inventory.total_due')</label>
            
        </div>
    </div>
</div>
<div class="col-lg-12 mt-20 text-center">
 <button class="primary-btn fix-gr-bg">
    <span class="ti-check"></span>
    @lang('common.update')
</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
{{ Form::close() }}
</div>
</section>
@endsection
@include('backEnd.partials.date_picker_css_js')