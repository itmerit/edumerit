@extends('backEnd.master')
@section('title') 
@lang('communicate.event_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.event_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.event_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($editData))
        @if(userPermission("event-store"))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('event')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($editData))
                                    @lang('communicate.edit_event')
                                @else
                                    @lang('communicate.add_event')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('event-update',$editData->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        @if(userPermission("event-store"))
             
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'event',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12 mb-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.event_title') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('event_title') ? ' is-invalid' : '' }}"
                                            type="text" name="event_title" autocomplete="off" value="{{isset($editData)? $editData->event_title : '' }}">
                                            
                                            
                                            @if ($errors->has('event_title'))
                                            <span class="text-danger" >
                                                {{ $errors->first('event_title') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <label class="primary_input_label" for="">@lang('communicate.for_whom') <span class="text-danger"> *</span> </label>
                                        <select class="primary_select form-control {{ $errors->has('for_whom') ? ' is-invalid' : '' }}" id="for_whom" name="for_whom">
                                            <option data-display="@lang('communicate.for_whom') *" value="">@lang('communicate.for_whom') *</option>
                                            
                                            <option value="All" {{isset($editData)? ($editData->for_whom == 'All'? 'selected' : ''):"" }}>@lang('communicate.all')</option>
                                            <option value="Teacher" {{isset($editData)? ($editData->for_whom == 'Teacher'? 'selected' : ''):"" }}>@lang('communicate.teachers')</option>
                                            <option value="Student" {{isset($editData)? ($editData->for_whom == 'Student'? 'selected' : ''):"" }}>@lang('communicate.students')</option>
                                            <option value="Parents" {{isset($editData)? ($editData->for_whom == 'Parents'? 'selected' : ''):"" }}>@lang('communicate.parents')</option>
                                            
                                            
                                        </select>
                                        @if ($errors->has('for_whom'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('for_whom') }}
                                        </span>
                                        @endif

                                    </div>
                                    <div class="col-lg-12 mb-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.event_location') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('event_location') ? ' is-invalid' : '' }}"
                                            type="text" name="event_location" autocomplete="off" value="{{isset($editData)? $editData->event_location : '' }}">
                                            
                                            
                                            @if ($errors->has('event_location'))
                                            <span class="text-danger" >
                                                {{ $errors->first('event_location') }}
                                            </span>
                                            @endif
                                        </div>

                                    </div>

                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>
                               
                                <div class="row mb-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input ">
                                            <label class="primary_input_label" for="from_date">{{ __('common.from_date') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('from_date') ? ' is-invalid' : '' }}" id="event_from_date" type="text"
                                                            name="from_date" value="{{isset($editData)? date('m/d/Y', strtotime($editData->from_date)): ''}}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#from_date" type="button">
                                                        <label class="m-0 p-0" for="event_from_date">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{$errors->first('from_date')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-15">
                                    
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.to_date')<span class="text-danger"> *</span> </label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('to_date') ? ' is-invalid' : '' }}" id="event_to_date" type="text"
                                                            name="to_date" value="{{isset($editData)? date('m/d/Y', strtotime($editData->to_date)): '' }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#from_date" type="button">
                                                        <label class="m-0 p-0" for="event_to_date">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{$errors->first('to_date')}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description') <span class="text-danger"> *</span> </label>
                                            <textarea class="primary_input_field form-control {{ $errors->has('event_des') ? ' is-invalid' : '' }}" cols="0" rows="4" name="event_des">{{isset($editData)? $editData->event_des: ''}}</textarea>
                                           
                                            
                                            @if ($errors->has('event_des'))
                                            <span class="text-danger" >
                                                {{ $errors->first('event_des') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <div class="primary_file_uploader">
                                                <input class="primary_input_field form-control{{ $errors->has('upload_event_image') ? ' is-invalid' : '' }}" type="text" 
                                            placeholder="{{isset($editData->uplad_image_file) && $editData->uplad_image_file != ""? getFilePath3($editData->uplad_image_file): trans('communicate.attach_file').''}}"
                                              id="placeholderEventFile" readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="upload_event_image">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="upload_event_image" id="upload_event_image">
                                                </button>
                                                <code>(PDF,DOC,DOCX,JPG,JPEG,PNG,TXT are allowed for upload)</code>
                                                @if ($errors->has('upload_event_image'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('upload_event_image') }}
                                                    </span>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  @php 
                                  $tooltip = "";
                                  if(userPermission("event-store")){
                                        $tooltip = "";
                                    }elseif(userPermission('event-edit') && isset($editData)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{ @$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($editData))
                                                @lang('communicate.update')
                                            @else
                                                @lang('communicate.save')
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
                        <h3 class="mb-0">@lang('communicate.event_list')</h3>
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
                            <th>@lang('communicate.event_title')</th>
                            <th>@lang('communicate.for_whom')</th>
                            <th>@lang('communicate.from_date')</th>
                            <th>@lang('communicate.to_date')</th>
                            <th>@lang('communicate.location')</th>
                            <th>@lang('common.action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($events))
                        @foreach($events as $key=>$value)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ @$value->event_title}}</td>
                            <td>{{ @$value->for_whom}}</td>
                           
                            <td>{{ @$value->from_date != ""? dateConvert(@$value->from_date):''}}</td>

                          
                            <td  data-sort="{{strtotime(@$value->to_date)}}" >{{$value->to_date != ""? dateConvert(@$value->to_date):''}}</td>

                            <td>{{ @$value->event_location}}</td>

                            <td>
                               <x-drop-down>
                                         @if(userPermission('event-edit'))
                                         <a class="dropdown-item" href="{{route('event-edit',$value->id)}}">@lang('common.edit')</a>
                                        @endif
                                         @if(userPermission('delete-event-view') )
                                          <a class="deleteUrl dropdown-item" data-modal-size="modal-md" title="{{ __('communicate.delete_event') }}" href="{{route('delete-event-view',$value->id)}}">@lang('common.delete')</a>
                                        @endif
                                        @if($value->uplad_image_file != "")
                                                <a class="dropdown-item"
                                                    href="{{url(@$value->uplad_image_file)}}" download>
                                                    @lang('communicate.download') <span class="pl ti-download"></span>
                                        @endif
                               </x-drop-down>
                            </td>
                        </tr>
                        
                        @endforeach
                        @endif
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