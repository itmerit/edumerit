@extends('backEnd.master')
    @section('title') 
        @lang('fees.fees_forward')
    @endsection
@section('mainContent')
@php  $setting = generalSetting(); 
if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }
else{ $currency = '$'; } 
@endphp

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.fees_forward')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('fees.fees_collection')</a>
                <a href="#">@lang('fees.fees_forward')</a>
            </div>
        </div>
    </div>
</section>
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-forward-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-6 mt-30-md">
                                    <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}"  {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                     @if ($errors->has('class'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('class') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-6 mt-30-md" id="select_section_div">
                                    <select class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section') }}
                                    </span>
                                    @endif
                                </div>
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
        @if(isset($students))
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'route' => 'fees-forward-store', 'enctype' => 'multipart/form-data'])}}
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('fees.previous_Session_Balance_Fees')</h3>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="update" value="{{isset($update)? 1 : ''}}">
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table" cellspacing="0" width="100%">
                                        <thead>
                                            @if(isset($update))
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="alert">
                                                        <h4 class="mb-0"> @lang('fees.previous_balance_can_only_update_now.') </h4>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                                <tr>
                                                    <th width="15%">@lang('student.student_name')</th>
                                                    <th width="15%">@lang('student.admission_no')</th>
                                                    <th width="15%">@lang('student.roll_no')</th>
                                                    <th width="15%">@lang('student.father_name')</th>
                                                    <th width="15%">@lang('fees.balance') ({{generalSetting()->currency_symbol}})</th>
                                                    <th width="25%">@lang('fees.short_note')</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students as $student)
                                                <tr>
                                                    <td>{{$student->studentDetail->full_name}} <input type="hidden" name="id[]" value="{{isset($update)? $student->studentDetail->forwardBalance->id: $student->student_id}}"></td>
                                                    <td>{{$student->studentDetail->admission_no}}</td>
                                                    <td>{{$student->studentDetail->roll_no}}</td>
                
                                                    <td>{{$student->studentDetail->parents !=""?$student->studentDetail->parents->fathers_name:""}}</td>
                                                    <td>
                                                        <div class="primary_input">
                                                            <input oninput="numberCheckWithDot(this)" type="text" class="primary_input_field form-control" cols="0" rows="1" name="balance[{{isset($update)? $student->studentDetail->forwardBalance->id:$student->student_id}}]" maxlength="8" value="{{isset($update)?
                                                            $student->studentDetail->forwardBalance->balance: ''}}">
                                                           
                                                            
                                                            {{-- <span class="text-danger">
                                                                <strong>@lang('fees.error')</strong>
                                                            </span> --}}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="primary_input">
                                                            <input type="text" class="primary_input_field form-control" cols="0" rows="1" name="notes[{{isset($update)? $student->studentDetail->forwardBalance->id:$student->student_id}}]" value="{{isset($update)?
                                                            $student->studentDetail->forwardBalance->notes: 'Fees Carry Forward'}}">
                                                          
                                                            
                                                            {{-- <span class="text-danger">
                                                                <strong>@lang('fees.error')</strong>
                                                            </span> --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tr>
                                            <td colspan="6">
                                                <div class="text-center">
                                                    <button type="submit" class="primary-btn fix-gr-bg mb-0 submit">
                                                        <span class="ti-save pr"></span>
                                                            @lang('common.save')
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        @endif
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')