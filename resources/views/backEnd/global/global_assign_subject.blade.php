@extends('backEnd.master')
    @section('title') 
        @lang('academics.assign_subject')
    @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('academics.assign_subject')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('academics.academics')</a>
                <a href="#">@lang('academics.assign_subject')</a>
            </div>
        </div>
    </div>
</section>

<div id="ajaxSpinnerContainer">
     {{-- <img src="{{asset('public/uploads/settings')}}/ajax-loader.gif" id="ajaxSpinnerImage" title="loading..." /> --}}
</div>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="main-title">
                    <h3 class="mb-30 ">@lang('common.select_criteria')</h3>
                </div>
            </div>
        @if(userPermission(251))
            <div class="offset-lg-5 col-lg-3 text-right col-md-6 col-sm-6">
                <a href="{{route('global_assign_subject_create')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('academics.assign_subject')
                </a>
            </div>
        @endif
        </div>
        <div class="row">
            <div class="col-lg-12">              
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'assign-subject', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-6 mt-30-md">
                                <select class="primary_select form-control {{ @$errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class')*" value="">@lang('common.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{@$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="text-danger invalid-select" role="alert">
                                    <strong>{{ @$errors->first('class') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-6 mt-30-md" id="select_section_div">
                                <select class="primary_select form-control{{ @$errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section')*</option>
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                @if ($errors->has('section'))
                                <span class="text-danger invalid-select" role="alert">
                                    <strong>{{ @$errors->first('section') }}</strong>
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
    </div>
</section>

@if(isset($assign_subjects) && $assign_subjects->count() > 0)
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-0">@lang('academics.assign_subject') </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table id="table_id" class="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>@lang('academics.subject')</th>
                            <th>@lang('common.teacher')</th>
                            @if(@generalSetting()->result_type == 'mark')
                                <th>@lang('academics.pass_mark')</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                         @php $i = 4; @endphp
                        @foreach($assign_subjects as $assign_subject)
                        <tr>
                            <td>{{@$assign_subject->subject != ""? @$assign_subject->subject->subject_name:''}}</td>
                            <td>
                                @if(@$assign_subject->teacher != "") 
                                    {{@$assign_subject->teacher->full_name}}
                                @else
                                    @lang('academics.not_assigned_yet')
                                @endif
                            </td>
                            @if(@generalSetting()->result_type == 'mark')
                                <td>{{@$assign_subject->pass_mark}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endif
            



@endsection
@include('backEnd.partials.data_table_js')