@extends('backEnd.master')
@section('title')
@lang('exam.marks_grade')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('exam.marks_grade') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examination')</a>
                    <a href="#">@lang('exam.marks_grade')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($marks_grade))
             @if(userPermission("marks-grade-store"))

                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('marks-grade')}}" class="primary-btn small fix-gr-bg">
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
                                <h3 class="mb-30">@if(isset($marks_grade))
                                        @lang('exam.edit_grade')
                                    @else
                                        @lang('exam.add_grade')
                                    @endif
                                  
                                </h3>
                            </div>
                            @if(isset($marks_grade))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('marks-grade-update',$marks_grade->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                            @else
                            @if(userPermission("marks-grade-store"))

                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'marks-grade',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12">
                                        
                                            <div class="primary_input">
                                                <label> @lang('exam.grade_name') <span class="text-danger"> *</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('grade_name') ? ' is-invalid' : '' }}"
                                                    type="text" name="grade_name" autocomplete="off"
                                                    value="{{isset($marks_grade)? $marks_grade->grade_name:Request::old('grade_name')}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($marks_grade)? $marks_grade->id: ''}}">
                                              
                                                
                                                @if ($errors->has('grade_name'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('grade_name') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    @if(generalSetting()->result_type != 'mark')
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.gpa') <span class="text-danger"> *</span></label>
                                                <input oninput="numberCheckWithDot(this)"
                                                    class="primary_input_field form-control{{ $errors->has('gpa') ? ' is-invalid' : '' }}"
                                                    type="text" step="0.1" name="gpa" autocomplete="off"
                                                    value="{{isset($marks_grade)? $marks_grade->gpa:Request::old('gpa')}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($marks_grade)? $marks_grade->id: Request::old('gpa')}}">
                                                
                                                
                                                @if ($errors->has('gpa'))
                                                    <span class="text-danger" >
                                                {{ $errors->first('gpa') }}
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    @endif 
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.percent_from')<span class="text-danger"> *</span></label>
                                                <input oninput="numberCheckWithDot(this)"
                                                    class="primary_input_field form-control{{ $errors->has('percent_from') ? ' is-invalid' : '' }}"
                                                    type="text" name="percent_from" autocomplete="off" 
                                                    value="{{isset($marks_grade)? $marks_grade->percent_from:Request::old('percent_from')}}">
                                                
                                                
                                                @if ($errors->has('percent_from'))
                                                    <span class="text-danger" >
                                                {{ $errors->first('percent_from') }}
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.percent_to')<span class="text-danger"> *</span></label>
                                                <input oninput="numberCheckWithDot(this)"
                                                    class="primary_input_field form-control{{ $errors->has('percent_upto') ? ' is-invalid' : '' }}"
                                                    type="text" name="percent_upto" autocomplete="off"
                                                    value="{{isset($marks_grade)? $marks_grade->percent_upto:Request::old('percent_upto')}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($marks_grade)? $marks_grade->id: ''}}">
                                                
                                                
                                                @if ($errors->has('percent_upto'))
                                                    <span class="text-danger" >
                                                {{ $errors->first('percent_upto') }}
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    @if(generalSetting()->result_type != 'mark')
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.gpa_from')<span class="text-danger"> *</span></label>
                                                <input oninput="numberCheckWithDot(this)"
                                                    class="primary_input_field form-control{{ $errors->has('grade_from') ? ' is-invalid' : '' }}"
                                                    type="text" step="0.1" name="grade_from" autocomplete="off"
                                                    value="{{isset($marks_grade)? $marks_grade->from:Request::old('grade_from')}}">
                                               
                                                
                                                @if ($errors->has('grade_from'))
                                                    <span class="text-danger" >
                                                {{ $errors->first('grade_from') }}
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.gpa_to')<span class="text-danger"> *</span></label>
                                                <input oninput="numberCheckWithDot(this)"
                                                    class="primary_input_field form-control{{ $errors->has('grade_upto') ? ' is-invalid' : '' }}"
                                                    type="text" step="0.1" name="grade_upto" autocomplete="off"
                                                    value="{{isset($marks_grade)? $marks_grade->up: ''}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($marks_grade)? $marks_grade->id: ''}}">
                                                
                                                
                                                @if ($errors->has('grade_upto'))
                                                    <span class="text-danger" >
                                                {{ $errors->first('grade_upto') }}
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    @endif 
                                    <div class="row mt-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('common.description') @if(generalSetting()->result_type == 'mark')<span class="text-danger"> *</span>@endif </label>
                                                <textarea class="primary_input_field form-control" cols="0" rows="4"
                                                          name="description">{{isset($marks_grade)? $marks_grade->description: Request::old('description')}}</textarea>
                                              
                                                
                                                @if ($errors->has('description'))
                                                    <span class="text-danger" >
                                                {{ $errors->first('description') }}
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
	                                @php 
                                        $tooltip = "";
                                      if(userPermission("marks-grade-store") || userPermission("marks-grade-edit")){
                                            $tooltip = "";
                                        }else{
                                            $tooltip = "You have no permission to add";
                                        }
                                    @endphp

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                           <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                                <span class="ti-check"></span>

                                                @if(isset($marks_grade))
                                                    @lang('exam.update_grade')
                                                @else
                                                    @lang('exam.save_grade')
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
                                <h3 class="mb-0">@lang('exam.grade_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">

                                <thead>
                               
                                <tr>
                                    <th>
                                        @lang('common.sl')
                                    </th>
                                    <th>
                                        @lang('exam.grade')
                                    </th>
                                    @if(generalSetting()->result_type != 'mark')
                                    <th>
                                        @lang('exam.gpa')
                                    </th>
                                    @endif 
                                    <th>
                                        @lang('exam.percent_from_to')
                                    </th>
                                    
                                    <th>
                                        @if(generalSetting()->result_type == 'mark')
                                            @lang('common.description')
                                        @else 
                                            @lang('exam.gpa_from_to')
                                        @endif 
                                    </th>
                                   
                                    <th>
                                        @lang('common.action')
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                @foreach($marks_grades as $marks_grade)
                                    <tr>
                                        <td>
                                            {{ @$i++}}
                                        </td>
                                        <td>
                                            {{ @$marks_grade->grade_name}}
                                        </td>
                                        @if(generalSetting()->result_type != 'mark')
                                        <td>
                                            {{ @$marks_grade->gpa}}
                                        </td>
                                        @endif 
                                        <td>
                                            {{ @$marks_grade->percent_from}}-{{ @$marks_grade->percent_upto}}%
                                        </td>
                                       
                                        <td>
                                            @if(generalSetting()->result_type == 'mark')
                                            {{ @$marks_grade->description}}
                                            @else 
                                            {{ @$marks_grade->from}}-{{ @$marks_grade->up}}
                                            @endif 
                                            
                                        </td>
                                        

                                        
                                        <td>
                                            <x-drop-down>
                                                   @if(userPermission('marks-grade-edit'))

                                                   <a class="dropdown-item" href="{{route('marks-grade-edit', [$marks_grade->id
                                                    ])}}">@lang('common.edit')</a>
                                                   @endif
                                                   @if(userPermission('marks-grade-delete'))

                                                   <a class="dropdown-item" data-toggle="modal"
                                                       data-target="#deleteMarksGradeModal{{@$marks_grade->id}}"
                                                       href="#">@lang('common.delete')</a>
                                               @endif
                                            </x-drop-down>
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deleteMarksGradeModal{{@$marks_grade->id}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('exam.delete_grade')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">@lang('common.cancel')</button>
                                                        {{ Form::open(['route' => array('marks-grade-delete',$marks_grade->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                        <button class="primary-btn fix-gr-bg"
                                                                type="submit">@lang('common.delete')</button>
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