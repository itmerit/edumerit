@extends('backEnd.master')
@section('title') 
@lang('fees.assign_fees_discount')
@endsection
@section('mainContent')
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">                   
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-discount-assign-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <input type="hidden" name="fees_discount_id" id="fees_discount_id" value="{{$fees_discount_id}}">

                            @if(moduleStatusCheck('University'))
                                    @includeIf('university::common.session_faculty_depart_academic_semester_level',['hide'=>['USUB']])
                                @else
                                <div class="col-lg-3 mt-30-md">
                                    <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')*</option>
                                        @foreach($classes as $class)
                                            <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('class') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md" id="select_section_div">
                                    <select class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                    @if ($errors->has('section'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('section') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md">
                                    <select class="primary_select  form-control{{ $errors->has('category') ? ' is-invalid' : '' }}" name="category">
                                        <option data-display="@lang('fees.select_category')" value="">@lang('fees.select_category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{isset($category_id)? ($category_id == $category->id? 'selected':''):''}}>{{$category->category_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('category') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md">
                                    <select class="primary_select  form-control{{ $errors->has('group') ? ' is-invalid' : '' }}" name="group">
                                        <option data-display="@lang('fees.select_group')" value="">@lang('fees.select_group') </option>
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}" {{isset($group_id)? ($group_id == $group->id? 'selected':''):''}}>{{$group->group}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('group'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('group') }}
                                        </span>
                                    @endif
                                </div>
                                @endif 


                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            @if(!empty($students))
                {{ Form::open(['class' => 'form-horizontal', 'method' => 'POST', 'url' => 'fees-discount-assign-store'])}}
                    <div class="row mt-40">
                        <div class="col-lg-12">
                            <div class="row mb-30">
                                <div class="col-lg-6 no-gutters">
                                    <div class="main-title">
                                        <h3 class="mb-0">@lang('fees.assign_fees_discount')</h3>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="fees_discount_id" value="{{$fees_discount_id}}" id="fees_discount_id">
                            <div class="row">
                                <div class="col-lg-4">
                                    <x-table>
                                        <table id="table_id_table" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <tr>
                                                        <th>@lang('fees.fees_discount')</th>
                                                        <th>@lang('fees.amount')</th>
                                                    </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{$fees_discount->name}}</td>
                                                    <td>{{$fees_discount->amount}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </x-table>
                                </div>
                                <div class="col-lg-8">
                                    <div class="table-responsive">
                                        <x-table>
                                            <table  class="table school-table-style" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">
                                                            <input type="checkbox" id="checkAll" class="common-checkbox" name="checkAll"  
                                                                @php
                                                                    if(count($students) > 0){
                                                                        if(count($students) == count($pre_assigned)){
                                                                            echo 'checked';
                                                                        }
                                                                    }
                                                                @endphp>
                                                            <label for="checkAll"> @lang('common.all')</label>
                                                        </th>
                                                        <th width="20%">@lang('student.student_name')</th>
                                                        <th width="10%">@lang('student.admission_no')</th>
                                                        <th width="15%">@lang('common.class')</th>
                                                        <th width="15%">@lang('fees.fees_type')</th>
                                                        <th width="15%">@lang('student.father_name')</th>
                                                        <th width="10%">@lang('student.category')</th>
                                                        <th width="5%">@lang('common.gender')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($students as $student)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                    if ($fees_discount->type=='once') {
                                                                    $checkPayment=App\SmFeesDiscount::CheckAppliedDiscount($fees_discount_id,$student->id, $student->id);
                                                                    $show='';
                                                                        if ($checkPayment=='false') {
                                                                            $show='disabled';
                                                                        }
                                                                    } else {
                                                                    $checkPayment=App\SmFeesDiscount::CheckAppliedYearlyDiscount($fees_discount_id,$student->id, $student->id);
                                                                    $show='';
                                                                        if ($checkPayment=='false') {
                                                                            $show='disabled';
                                                                        }
                                                                    }
                                                                @endphp
                                                                <input type="checkbox" id="student.{{$student->id}}" {{@$show}} class="common-checkbox" name="data[{{$loop->index}}][checked]" value="1" {{in_array($student->student_id, $pre_assigned)? 'checked':''}} {{in_array($student->student_id, $already_paid)? 'disabled="disabled"':''}} >
                                                                <label for="student.{{$student->id}}"></label>
                                                            </td>
                                                                <input type="hidden" name="data[{{$loop->index}}][class_id]" value="{{@$student->class_id}}">
                                                                <input type="hidden" name="data[{{$loop->index}}][section_id]" value="{{@$student->section_id}}">
                                                                <input type="hidden" name="data[{{$loop->index}}][record_id]" value="{{@$student->id}}">
                                                                <input type="hidden" name="data[{{$loop->index}}][student_id]" value="{{@$student->studentDetail->forwardBalance->id ?? $student->student_id}}">
                                                            <td>{{$student->studentDetail->full_name}}</td>
                                                            <td>{{$student->studentDetail->admission_no}}</td>
                                                            <td>{{$student->class != ""? @$student->class->class_name :""}} {{'('.$student->section!=""? $student->section->section_name:"".')' }}</td>
                                                            <td>
                                                                @php
                                                                    $check_discount_apply= DB::table('sm_fees_assign_discounts')
                                                                                        ->where('student_id',$student->student_id)
                                                                                        ->where('record_id',$student->id)
                                                                                        ->where('fees_discount_id',$fees_discount_id)
                                                                                        ->leftjoin('sm_fees_types','sm_fees_types.id','=','sm_fees_assign_discounts.fees_type_id')
                                                                                        ->leftjoin('sm_fees_groups','sm_fees_groups.id','=','sm_fees_assign_discounts.fees_group_id')
                                                                                        ->select('sm_fees_assign_discounts.*','sm_fees_groups.name as fees_group_name','sm_fees_types.name as fees_type_name')
                                                                                        ->first();
                                                                    $group_ids= array();
                                                                @endphp
    
                                                                @if($fees_discount->type=='once')
                                                                    @if($check_discount_apply=='') 
                                                                        <select class="primary_select w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" {{@$show}} name="data[{{$loop->index}}][fees_master_id]" id="fees_master{{$student->id}}">
                                                                            <option data-display="@lang('fees.select_fees_type') *" value="">@lang('fees.select_fees_type') *</option>
                                                                            @foreach($assigned_fees_groups[$student->id] as $key=> $fees_group)
                                                                                @php
                                                                                    if( in_array($fees_group->group_id, $group_ids) ) { 
                                                                                        continue;
                                                                                    }
                                                                                    array_push($group_ids,$fees_group->group_id);
                                                                                @endphp
                                                                                    <option value="" disabled >{{$fees_group->name}} </option>
                                                                                        @php
                                                                                            $studentAssingFees_types=App\SmFeesAssign::studentFeesTypeDiscount($fees_group->group_id,$student->student_id,$fees_discount->amount, $student->id);
                                                                                        @endphp
                                                                                    @foreach ($studentAssingFees_types as $fees_type)
                                                                                        <option value="{{$fees_type->id}}">{{$fees_type->name}}</option>
                                                                                    @endforeach
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('fees_master_id'))
                                                                            <span class="text-danger invalid-select" role="alert">
                                                                                {{ $errors->first('fees_master_id') }}
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <select class="primary_select w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" name="data[{{$loop->index}}][fees_master_id]" title="Discount Applied" id="fees_master{{$student->id}}">
                                                                            <option data-display="@lang('fees.select_fees_type') *" value=""> @lang('fees.select_fees_type') *</option>
                                                                                {{-- @foreach(@$assigned_fees_types[$student->id] as $fees_type) --}}
                                                                                    <option value="{{$check_discount_apply->fees_type_id}}" selected  >{{$check_discount_apply->fees_type_name}}</option>
                                                                                {{-- @endforeach --}}
                                                                        </select>
                                                                        @if ($errors->has('fees_master_id'))
                                                                            <span class="text-danger invalid-select" role="alert">
                                                                                {{ $errors->first('fees_master_id') }}
                                                                            </span>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @php
                                                                        $group_ids= array();
                                                                    @endphp
                                                                    @if ($check_discount_apply=='')
                                                                        <select class="primary_select w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" name="data[{{$loop->index}}][fees_master_id]" {{@$show}} id="fees_master{{$student->id}}">
                                                                            <option data-display="@lang('fees.select_fees_group')" value="">@lang('fees.select_fees_group')</option>
                                                                            @foreach($assigned_fees_groups[$student->id] as $key=> $fees_group)
                                                                                @php
                                                                                    if( in_array($fees_group->group_id, $group_ids) ) {
                                                                                        continue;
                                                                                        }
                                                                                    array_push($group_ids,$fees_group->group_id);
                                                                                @endphp
                                                                                <option value="{{$fees_group->group_id}}")}} >{{$fees_group->name}} </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('fees_master_id'))
                                                                            <span class="text-danger invalid-select" role="alert">
                                                                                {{ $errors->first('fees_master_id') }}
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        {{-- {{$check_discount_apply->fees_group_id}} --}}
                                                                        <select class="primary_select w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" name="data[{{$loop->index}}][fees_master_id]]" id="fees_master{{$student->id}}">
                                                                            {{-- <option data-display="@lang('common.select') @lang('fees.fees_group')" value="">@lang('common.select') @lang('fees.fees_group')</option> --}}
                                                                            <option value="{{@$check_discount_apply->fees_group_id}}" checked >{{@$check_discount_apply->fees_group_name}} </option>
                                                                        </select>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>{{$student->studentDetail->parents!=""?$student->studentDetail->parents->fathers_name:""}}</td>
                                                            <td>{{$student->studentDetail->category!=""?$student->studentDetail->category->category_name:""}}</td>
                                                            <td>{{$student->studentDetail->gender!=""?$student->studentDetail->gender->base_setup_name:""}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                @if($students->count() > 0)
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="text-center">
                                                                <button type="submit" class="primary-btn fix-gr-bg mb-0" id="btn-assign-fees-discount">
                                                                    <span class="ti-save pr"></span>
                                                                    @lang('fees.assign_discount')
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </x-table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            @endif
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')