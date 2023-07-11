@extends('backEnd.master')
@section('title')
    @lang('exam.format_settings')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('exam.format_settings')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examination')</a>
                    <a href="#">@lang('exam.settings')</a>
                    <a href="#">@lang('exam.format_settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if(isset($editData))
                                        @lang('exam.edit_exam_format')
                                    @else
                                        @lang('exam.add_exam_format')
                                    @endif
                                </h3>
                            </div>
                            @if(isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => 'true', 'route' => 'update-exam-content', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{$editData->id}}">
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => 'true', 'route' => 'save-exam-content', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row mb-25">
                                        <div class="col-lg-12 ">
                                            <p class="alert alert-warning mb-2 text-center">{{ __('exam.exam_settings_tips') }}  </p>
                                        </div>

                                        <div class="col-lg-12 mb-30">
                                            @php
                                                $format_for = old('format_for') ?? (!isset($editData) || @$editData->exam_type ? 'term_exam' : 'progress_card') ;
                                            @endphp
                                            <label class="primary_input_label" for="">
                                                {{ __('common.type') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <select class="primary_select form-control{{ $errors->has('format_for') ? ' is-invalid' : '' }}"
                                                    name="format_for" id="format_for">

                                                <option value="term_exam" {{ $format_for == 'term_exam' ? 'selected' : '' }}>{{ __('exam.term_exam') }}</option>
                                                <option value="progress_card" {{ $format_for == 'progress_card' ? 'selected' : '' }}>{{ __('exam.progress_card') }}</option>
                                            </select>
                                            @if ($errors->has('format_for'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('format_for') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        {{-- <div class="col-lg-12 mb-15">
                                            <label class="primary_input_label" for="">
                                                {{ __('common.exam') }}
                                                    <span class="text-danger"> *</span>
                                            </label>
                                            <select class="primary_select  form-control{{ $errors->has('exam_type') ? ' is-invalid' : '' }}" name="exam_type">
                                                <option data-display="@lang('common.select_exam') *" value="">@lang('common.select_exam')  *</option>
                                                @foreach($exams as $exam)
                                                @if(!in_array($exam->id, $already_assigned))
                                                @if(isset($editData))
                                                <option value="{{$exam->id}}" {{isset($editData)? ($editData->exam_type == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                                @else
                                                    <option value="{{$exam->id}}">{{$exam->title}}</option>
                                                @endif
                                                @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('exam_type'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('exam_type') }}
                                            </span>
                                            @endif
                                        </div> --}}

                                        <div class="col-lg-12 mb-15 term_exam">
                                            <label class="primary_input_label" for="">
                                                {{ __('common.exam') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <select class="primary_select form-control{{ $errors->has('exam_type') ? ' is-invalid' : '' }}"
                                                    name="exam_type">
                                                <option data-display="@lang('common.select_exam') *"
                                                        value="">@lang('common.select_exam') *
                                                </option>
                                                @foreach($exams as $exam)
                                                    @if(!in_array($exam->id, $already_assigned))
                                                        @if(isset($editData))
                                                            <option value="{{$exam->id}}" {{isset($editData)? ($editData->exam_type == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                                        @else
                                                            <option value="{{$exam->id}}">{{$exam->title}}</option>
                                                        @endif

                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('exam_type'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                <strong>{{ $errors->first('exam_type') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="col-lg-12 mb-15 term_exam">
                                            <div class="primary_input">
                                                <label> @lang('exam.controller_title') <span
                                                            class="text-danger"> *</span> </label>
                                                <input class="primary_input_field  form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                       type="text" name="title" autocomplete="off"
                                                       value="{{isset($editData)? @$editData->title:old('title')}}">
                                                @if ($errors->has('title'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('title') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-12 term_exam">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('exam.signature')
                                                    <span></span> </label>
                                                <div class="primary_file_uploader">
                                                    <input class="primary_input_field" type="text" id="placeholderPhoto"
                                                           placeholder="{{isset($editData->file) && @$editData->file != ""? getFilePath3(@$editData->file):trans('exam.signature')}}"
                                                           readonly>
                                                    <button class="" type="button">
                                                        <label class="primary-btn small fix-gr-bg"
                                                               for="upload_content_file">{{ __('common.browse') }}</label>
                                                        <input type="file" class="d-none" name="file"
                                                               id="upload_content_file">
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('file'))
                                                <span class="text-danger">
                                                {{ $errors->first('file') }}
                                            </span>
                                            @endif
                                            <code class="nowrap d-block mb-30">(Allow file jpg, png, jpeg, svg)</code>
                                        </div>
                                        <div class="row"></div>
                                        <div class="col-lg-12 mb-15">
                                            <div class="primary_input ">
                                                <label class="primary_input_label"
                                                       for="">@lang('exam.result_publication_date') <span
                                                            class="text-danger"> *</span> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input
                                                                        class="primary_input_field primary_input_field date form-control{{ $errors->has('publish_date') ? ' is-invalid' : '' }}"
                                                                        id="upload_date" type="text"
                                                                        name="publish_date"
                                                                        value="{{isset($editData)? date('m/d/Y', strtotime(@$editData->publish_date)): date('m/d/Y')}}">
                                                            </div>
                                                        </div>
                                                        <button class="btn-date" data-id="#upload_date" type="button">
                                                            <label class="m-0 p-0" for="upload_date">
                                                                <i class="ti-calendar" id="start-date-icon"></i>
                                                            </label>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{$errors->first('date_of_birth')}}</span>
                                            </div>
                                        </div>
                                        <div class="row no-gutters input-right-icon mb-15 term_exam">
                                            <h4>@lang('exam.attendance')</h4>
                                        </div>
                                        <div class="col-lg-12 mb-15 term_exam">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('exam.start_date')<span
                                                            class="text-danger"> *</span> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input
                                                                        class="primary_input_field primary_input_field date form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                                                                        id="start_date" type="text" name="start_date"
                                                                        value="{{isset($editData) && $editData->start_date ? date('m/d/Y', strtotime(@$editData->start_date)): date('m/d/Y')}}">
                                                            </div>
                                                        </div>
                                                        <button class="btn-date" data-id="#start_date" type="button">
                                                            <label class="m-0 p-0" for="start_date">
                                                                <i class="ti-calendar" id="start-date-icon"></i>
                                                            </label>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{$errors->first('start_date')}}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-15 term_exam">
                                            <div class="primary_input ">
                                                <label class="primary_input_label" for="">@lang('exam.end_date')<span
                                                            class="text-danger"> *</span> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input
                                                                        class="primary_input_field primary_input_field date form-control form-control{{ $errors->has('end_date') ? ' is-invalid' : '' }}"
                                                                        id="end_date" type="text" name="end_date"
                                                                        value="{{isset($editData) && $editData->end_date? date('m/d/Y', strtotime(@$editData->end_date)): date('m/d/Y')}}">
                                                            </div>
                                                        </div>
                                                        <button class="btn-date" data-id="#end_date" type="button">
                                                            <label class="m-0 p-0" for="end_date">
                                                                <i class="ti-calendar" id="start-date-icon"></i>
                                                            </label>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{$errors->first('end_date')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $tooltip = "";
                                        if(userPermission('save-exam-content') || userPermission('edit-exam-settings')){
                                                @$tooltip = "";
                                            }else{
                                                @$tooltip = "You have no permission to add";
                                            }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" type="submit"
                                                    data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($editData))
                                                    @lang('exam.update_content')
                                                @else
                                                    @lang('exam.save_content')
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
                                <h3 class="mb-0"> @lang('exam.exam_format_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th> @lang('exam.exam')</th>
                                        <th> @lang('exam.title')</th>
                                        <th> @lang('exam.signature')</th>
                                        <th> @lang('exam.publish_date')</th>
                                        <th> @lang('exam.start_date')</th>
                                        <th> @lang('exam.end_date')</th>
                                        <th> @lang('common.action')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($content_infos as $content_info)
                                        <tr>
                                            <td class="nowrap">{{@$content_info->examName->title ?? __('exam.progress_card')}}</td>
                                            <td class="nowrap">{{$content_info->title}}</td>
                                            <td>
                                                @if ($content_info->file)
                                                    <img src="{{asset($content_info->file)}}" width="100px">
                                                @endif
                                            </td>
                                            <td>{{dateConvert($content_info->publish_date)}}</td>
                                            <td>{{$content_info->start_date ? dateConvert($content_info->start_date) : null}}</td>
                                            <td>{{$content_info->end_date ? dateConvert($content_info->end_date) : null}}</td>
                                            <td>
                                                <x-drop-down>
                                                    @if(userPermission('edit-exam-settings'))
                                                        <a class="dropdown-item"
                                                           href="{{route('edit-exam-settings',$content_info->id)}}">@lang('common.edit')</a>
                                                    @endif
                                                    @if(userPermission('delete-content'))
                                                        <a class="dropdown-item" data-toggle="modal"
                                                           data-target="#deleteApplyLeaveModal{{$content_info->id}}"
                                                           href="#">
                                                            @lang('common.delete')
                                                        </a>
                                                    @endif
                                                </x-drop-down>
                                            </td>
                                        </tr>
                                        <div class="modal fade admin-query"
                                             id="deleteApplyLeaveModal{{$content_info->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('exam.delete_upload_content')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                            <a href="{{route('delete-content',$content_info->id)}}"
                                                               class="text-light">
                                                                <button class="primary-btn fix-gr-bg"
                                                                        type="submit">@lang('common.delete')</button>
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
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')

@push('scripts')
    <script>
        $(document).ready(function(){
            changeFormatFor($('#format_for').val());
        })
        $(document).on('change', '#format_for', function (){
            changeFormatFor($(this).val());
        })

        function changeFormatFor(val){
            if(typeof val == 'undefined' || val === 'term_exam'){
                $('.term_exam').show();
            }else{
                $('.term_exam').hide();
            }
        }
    </script>
@endpush