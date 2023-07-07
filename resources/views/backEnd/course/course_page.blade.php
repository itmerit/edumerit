@extends('backEnd.master')
@section('title')
@lang('front_settings.course')
@endsection
@push('css')
<style>
    .invalid-select-label {
        position: absolute;
        bottom: 0px;
        margin-top: 0px !important;
    }
    .invalid-select-label strong{
        top: -7px;
    }
</style>
    
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.add_course')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#"> @lang('front_settings.front_settings')</a>
                    <a href="#">@lang('common.add_course')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($add_course))
                @if(userPermission("store_course"))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{route('course-list')}}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('common.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($add_course))
                                    @lang('front_settings.edit_course')
                                @else
                                    @lang('front_settings.add_course')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($add_course))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update_course',
                            'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @else
                            @if(userPermission("store_course"))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'store_course',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12 mb-30">
                                        <div class="primary_input">
                                            <input class="primary_input_field "
                                                   type="text" name="title" autocomplete="off"
                                                   value="{{isset($add_course)? $add_course->title: old('title')}}">
                                            <input type="hidden" name="id"
                                                   value="{{isset($add_course)? $add_course->id: ''}}">
                                            <label class="primary_input_label" for="">@lang('common.title') <span class="text-danger"> *</span></label>
                                            
                                            @if ($errors->has('title'))
                                                <span class="text-danger" >
                                                        {{ $errors->first('title') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <select class="primary_select  form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }} mb-30" name="category_id">
                                                <option data-display="@lang('front_settings.course_category')*" value="">@lang('common.select') *</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" {{ (@$add_course->category_id == $category->id) ? 'selected' :''}}>{{$category->category_name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('category_id'))
                                                <span class="text-danger invalid-select-label" role="alert">
                                                    {{ $errors->first('category_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col mb-30">
                                            <div class="row no-gutters input-right-icon">
                                                <div class="col">
                                                    <div class="primary_input">
                                                        <input class="primary_input_field form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" type="text"
                                                                id="placeholderFileOneName"
                                                                placeholder="{{isset($add_course)? ($add_course->image !="") ? getFilePath3($add_course->image) :trans('common.image') .' *' :trans('common.image') . '(' .trans('common.min')." 1420*450 PX)" }}"
                                                                readonly
                                                        >
                                                        
                                                        @if ($errors->has('image'))
                                                            <span class="text-danger" >
                                                    {{ $errors->first('image') }}
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button class="primary-btn-small-input" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                                for="document_file_1">@lang('common.browse')</label>
                                                        <input type="file" class="d-none" name="image"
                                                                id="document_file_1">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 ">
                                    <div class="row mt-20">
                                        <div class="col-md-12 mt-20">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('front_settings.overview') </label>
                                                
                                                <textarea class="primary_input_field form-control{{ $errors->has('overview') ? ' is-invalid' : '' }}" cols="0" rows="4" name="overview" maxlength="500">{{isset($add_course)? $add_course->overview: old('overview')}}</textarea>
                                                
                                                
                                                @if($errors->has('overview'))
                                                    <span class="text-danger" >{{ $errors->first('overview') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>      
                                    <div class="row mt-20">
                                        <div class="col-md-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('front_settings.outline') <span></span></label>
                                                <textarea class="primary_input_field form-control{{ $errors->has('outline') ? ' is-invalid' : '' }}" cols="0" rows="4" name="outline" maxlength="500">{{isset($add_course)? $add_course->outline: old('outline')}}</textarea>
                                                
                                                
                                                @if($errors->has('outline'))
                                                    <span class="error text-danger">
                                                        {{ $errors->first('outline') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-md-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('front_settings.prerequisites') <span></span></label>
                                                <textarea class="primary_input_field form-control{{ $errors->has('prerequisites') ? ' is-invalid' : '' }}" cols="0" rows="4" name="prerequisites" maxlength="500">{{isset($add_course)? $add_course->prerequisites: old('prerequisites')}}</textarea>
                                                
                                                
                                                @if($errors->has('prerequisites'))
                                                    <span class="error text-danger">
                                                        {{ $errors->first('prerequisites') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-md-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('front_settings.resources') <span></span></label>
                                                <textarea class="primary_input_field form-control{{ $errors->has('resources') ? ' is-invalid' : '' }}" cols="0" rows="4" name="resources" maxlength="500">{{isset($add_course)? $add_course->resources: old('resources')}}</textarea>
                                                
                                                
                                                @if($errors->has('resources'))
                                                    <span class="error text-danger">
                                                        {{ $errors->first('resources') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-md-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('front_settings.stats') <span></span></label>
                                                <textarea class="primary_input_field form-control{{ $errors->has('stats') ? ' is-invalid' : '' }}" cols="0" rows="4" name="stats" maxlength="500">{{isset($add_course)? $add_course->stats: old('stats')}}</textarea>
                                                
                                                
                                                @if($errors->has('stats'))
                                                    <span class="error text-danger">
                                                        {{ $errors->first('stats') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                        $tooltip = "";
                        if(userPermission("store_course")){
                                $tooltip = "";
                            }else{
                                $tooltip = "You have no permission to add";
                            }
                    @endphp
                    <div class="row mt-40">
                        <div class="col-lg-12 text-center">
                            @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('front_settings.update_course')</button></span>
                                @else
                                <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                    <span class="ti-check"></span>
                                    @if(isset($add_course))
                                        @lang('front_settings.update_course')
                                    @else
                                        @lang('front_settings.add_course')
                                    @endif
                                   
                                </button>
                            @endif
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
        <div class="col-lg-12 mt-40">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('front_settings.course_list')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table id="table_id" class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('common.title')</th>
                                <th>@lang('front_settings.overview')</th>
                                <th>@lang('common.image')</th>
                                <th>@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($course))
                                @foreach($course as $value)
                                    <tr>
                                        <td>{{@$value->title}}</td>
                                        <td>{!! substr($value->overview, 0, 50) !!}</td>
                                        <td><img src="{{asset(@$value->image)}}" width="60px" height="50px"></td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if(userPermission('course-Details-admin'))
                                                        <a href="{{route('course-Details-admin',$value->id)}}"
                                                        class="dropdown-item small fix-gr-bg modalLink"
                                                        title="Course Details" data-modal-size="full-width-modal">
                                                            @lang('common.view')
                                                        </a>
                                                    @endif
                                                    @if(userPermission('edit-course'))
                                                        <a class="dropdown-item"
                                                        href="{{route('edit-course',$value->id)}}">@lang('common.edit')</a>
                                                    @endif

                                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span  tabindex="0" data-toggle="tooltip" title="Disabled For Demo"> <a href="#" class="dropdown-item small fix-gr-bg  demo_view" style="pointer-events: none;" >@lang('common.delete')</a></span>
                                                        @else
                                                            @if(userPermission('for-delete-course'))
                                                                <a href="{{route('for-delete-course',$value->id)}}"
                                                                class="dropdown-item small fix-gr-bg modalLink"
                                                                title="Delete Course" data-modal-size="modal-md">
                                                                    @lang('common.delete')
                                                                </a>
                                                            @endif
                                                    @endif 
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@push('script')


<script>
                                                   
    CKEDITOR.replace("overview");
    CKEDITOR .replace( "outline" );
    CKEDITOR.replace( "prerequisites" );
    CKEDITOR.replace( "resources" );
    CKEDITOR.replace( "stats" );
 </script>
@endpush