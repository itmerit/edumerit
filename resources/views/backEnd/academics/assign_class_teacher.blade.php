@extends('backEnd.master')
@section('title') 
@lang('academics.assign_class_teacher')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('academics.assign_class_teacher') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('academics.academics')</a>
                <a href="#">@lang('academics.assign_class_teacher')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($assign_class_teacher))
          @if(userPermission("assign-class-teacher-store")) 
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('assign-class-teacher')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('academics.assign')
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
                                @if(isset($assign_class_teacher))
                                    @lang('academics.edit_assign_class_teacher')
                                @else
                                    @lang('academics.assign_class_teacher')
                                @endif
                            </h3>
                        </div>
                        @if(isset($assign_class_teacher))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('assign-class-teacher-update',@$assign_class_teacher->id), 'method' => 'PUT']) }}
                        @else
                         @if(userPermission("assign-class-teacher-store")) 
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'assign-class-teacher', 'method' => 'POST']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        <label class="primary_input_label" for="">
                                            {{ __('common.class') }}
                                                <span class="text-danger"> *</span>
                                        </label>
                                        <select class="primary_select form-control {{ @$errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                            @foreach($classes as $class)
                                             @if(isset($assign_class_teacher)) 
                                            <option value="{{ @$class->id}}" {{ @$class->id == @$assign_class_teacher->class_id? 'selected':''}}>{{ @$class->class_name}}</option>
                                            @else
                                            <option value="{{ @$class->id}}">{{ @$class->class_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('class') }}
                                        </span>
                                        @endif

                                        
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="{{isset($assign_class_teacher)? $assign_class_teacher->id: ''}}">

                                <div class="row  mt-15">
                                    <div class="col-lg-12" id="select_section_div">
                                        <label class="primary_input_label" for="">
                                            {{ __('common.section') }}
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <select class="primary_select form-control{{ @$errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                        @if(isset($assign_class_teacher))
                                            @foreach($sections as $section)
                                            <option value="{{ @$section->id}}" {{ @$section->id == @$assign_class_teacher->section_id? 'selected':''}}>{{ @$section->section_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ @$errors->first('section') }}
                                    </span>
                                    @endif
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                       
                                        @foreach($teachers as $teacher)
                                        @if(isset($assign_class_teacher))
                                        <div class="">
                                            <label class="primary_input_label" for="">@lang('academics.teacher') <span class="text-danger"> *</span></label>
                                            <input type="radio" id="tecaher{{@$teacher->id}}" class="common-checkbox" name="teacher[]" value="{{ @$teacher->id}}" {{in_array($teacher->id, $teacherId)? 'checked':''}}>
                                            <label for="tecaher{{ @$teacher->id}}">{{ @$teacher->full_name}}</label>
                                        </div>
                                        @else
                                        <div class="">
                                            <input type="radio" id="tecaher{{@$teacher->id}}" class="common-checkbox" name="teacher[]" value="{{@$teacher->id}}">
                                            <label for="tecaher{{@$teacher->id}}">{{@$teacher->full_name}}</label>
                                        </div>
                                        @endif
                                        @endforeach

                                        @if($errors->has('teacher'))
                                            <span class="text-danger" role="alert">
                                                {{ @$errors->first('teacher') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @php 
                                  $tooltip = "";
                                  if(userPermission("assign-class-teacher-store")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($assign_class_teacher))
                                                @lang('academics.update_class_teacher')
                                            @else
                                                @lang('academics.save_class_teacher')
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
                            <h3 class="mb-0">@lang('academics.class_teacher_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">

                                <thead>
                                  
                                    <tr>
                                        <th>@lang('common.class')</th>
                                        <th>@lang('common.section')</th>
                                        <th>@lang('common.teacher')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assign_class_teachers as $assign_class_teacher)
                                    <tr>
                                        <td valign="top">{{@$assign_class_teacher->class !=""? @$assign_class_teacher->class->class_name:""}}</td>
                                        <td valign="top">{{@$assign_class_teacher->section != ""? @$assign_class_teacher->section->section_name:""}}</td>
                                        <td valign="top">
                                            
                                                @php
                                                  @$classTeachers = @$assign_class_teacher->classTeachers;
                                                @endphp
                                                @if($classTeachers !="")
                                                        @foreach($classTeachers as $classTeacher)
                                                            @php 
                                                                @$teacher = @$classTeacher->teacher;
                                                            @endphp
                                                                {{ @$teacher->full_name }}
                                                         @endforeach
                                                @endif
                                        </td>
                                        
                                        <td valign="top">
                                            
                                            @php
                                            $routeList = [
                                                userPermission('assign-class-teacher-edit') ?
                                                '<a class="dropdown-item" href="'.route('assign-class-teacher-edit', [$assign_class_teacher->id]).'">'.__('common.edit').'</a>':null,
                                                userPermission('assign-class-teacher-delete') ?
                                                '<a class="dropdown-item" data-toggle="modal" data-target="#deleteClassModal'.$assign_class_teacher->id.'"  href="#">'.__('common.delete').'</a>' : null,
                                            ]
                                        @endphp
                                         <x-drop-down-action-component :routeList="$routeList" />
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deleteClassModal{{@$assign_class_teacher->id}}" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('academics.delete_assign_teacher')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
    
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
    
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                        {{ Form::open(['route' => array('assign-class-teacher-delete',@$assign_class_teacher->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
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