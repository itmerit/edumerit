@extends('backEnd.master')
@section('title')
@lang('system_settings.optional_subject')
@endsection 
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.assign_optional_subject')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.optional_subject')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('system_settings.assign_optional_subject')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12"> 
                <div class="white-box">
                    @if(userPermission('optional_subject_setup_post'))
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'optional_subject_setup_post', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                    @endif    
                    <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4">
                                    <label class="primary_input_label" for="">@lang('common.select_class') <span class="text-danger"> *</span></label>
                                    @foreach($classes as $class)
                                        <div class="primary_input">
                                            <input type="checkbox" id="class{{@$class->id}}" class="common-checkbox exam-checkbox" name="class[]" value="{{@$class->id}}" {{isset($editData)? (@$class->id == @$editData->class_id? 'checked':''):''}}>
                                            <label for="class{{@$class->id}}">{{@$class->class_name}}</label>
                                        </div>
                                    @endforeach
                                <div class="primary_input">
                                    <input type="checkbox" id="all_exams" class="common-checkbox" name="all_exams[]" value="0" {{ (is_array(old('class')) and in_array(@$class->id, old('class'))) ? ' checked' : '' }}>
                                    <label for="all_exams">@lang('system_settings.all_select')</label>
                                </div>
                                @if($errors->has('class'))
                                <span class="text-danger validate-textarea-checkbox" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                            @endif
                                </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('reports.gpa_above') <span class="text-danger"> *</span></label>
                                            <input oninput= "numberCheckWithDot(this)" class="primary_input_field form-control{{ $errors->has('gpa_above') ? ' is-invalid' : '' }}"
                                             name="gpa_above" id="exam_mark_main" autocomplete="off" value="{{isset($editData)?  number_format(@$editData->gpa_above, 2, '.', ' ') : 0}}" >
                                           
                                            
                                            @if ($errors->has('gpa_above'))
                                            <span class="text-danger" >
                                                {{ $errors->first('gpa_above') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                               @php 
                                    $tooltip = "";
                                    if(userPermission('optional_subject_setup_post') || userPermission('class_optional_edit')){
                                            $tooltip = "";
                                        }else{
                                            $tooltip = "You have no permission to add";
                                        }
                                @endphp
                                <div class="col-lg-4 mt-30-md mt-35" id="select_subject_div">
                                    <button type="submit" class="primary-btn small fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                        <span class="pr-2"></span>
                                        @if (isset($editData))
                                        @lang('system_settings.update')
                                        @else
                                        @lang('system_settings.save')
                                        @endif
                                    </button>
                                </div> 
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>
 @if(isset($class_optionals))
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12 col-md-12">
                    <div class="main-title">
                        <h3 class="mb-30"> @lang('system_settings.optional_subject')  </h3>
                    </div>
                </div>
                
            </div>
            <div class="row"> 
                <div class="col-lg-12">
                    <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">

                            <thead>
                            
                                <tr>
                                    <th>@lang('common.sl')</th>
                                    <th>@lang('common.class_name')</th>
                                    <th>@lang('reports.gpa_above')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $i=0; @endphp
                                @foreach($class_optionals as $class_optional)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>{{@$class_optional->class_name}}</td>
                                    <td>{{ number_format(@$class_optional->gpa_above, 2, '.', ' ')}}</td>
                                
                                    <td>
                                        <div class="row">
                                        
                                                <x-drop-down>
                                                        @if(userPermission('class_optional_edit'))
                                                            <a class="dropdown-item" href="{{route('class_optional_edit', [@$class_optional->id])}}">@lang('common.edit')</a>
                                                        @endif
                                                        @if(userPermission('delete_optional_subject'))
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#deleteSubjectModal{{@$class_optional->id}}"  href="#">@lang('common.delete')</a>
                                                        @endif
                                                </x-drop-down>
                                            
                                        </div>


                                    

                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteSubjectModal{{@$class_optional->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('common.delete_optional_subject')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                    <a href="{{route('delete_optional_subject', [@$class_optional->id])}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
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
    </section>
@endif
  
 

@endsection
@include('backEnd.partials.data_table_js')