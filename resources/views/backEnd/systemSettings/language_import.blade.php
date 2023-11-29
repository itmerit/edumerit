@extends('backEnd.master')
@section('title')
@lang('system_settings.language_import')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>{{ $language->language_name }} <span> </span>@lang('system_settings.language_import')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="">@lang('system_settings.language_settings')</a>
                <a href="">@lang('system_settings.import')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('system_settings.upload_from_local_directory')</h3>
                        </div>
                           
                            @if(userPermission('file-import'))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'file-import',
                            'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        {{-- @endif --}}
                        <div class="white-box sm_mb_20  ">
                            <div class="add-visitor">
                                <a href="https://ticket.aorasoft.com/single/content/917" target="_blank"><span class="text-danger">Before Upload Please Read Documentation Properly</span></a>
                                <input type="hidden" value="{{ $language->language_universal }}" name="language">
                                <div class="row no-gutters input-right-icon mb-20 mt-25">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control {{ $errors->has('language_file') ? ' is-invalid' : '' }}" readonly="true" type="text"
                                            placeholder="{{ trans('common.attach_file') . "*"}} "  id="placeholderUploadContent" name="language_file">
                                            
                                            @if ($errors->has('language_file'))
                                        <span class="text-danger" >
                                            {{ $errors->first('language_file') }}
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="upload_content_file">@lang('common.browse')</label>
                                            <input type="file" class="d-none form-control" name="language_file" id="upload_content_file">
                                        </button>

                                    </div>
                                </div>
                               
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">

                   
                                        @php 
                                            $tooltip = "";
                                            if(userPermission('file-import')){
                                                    $tooltip = "";
                                                }else{
                                                    $tooltip = "You have no permission to add";
                                                }
                                        @endphp
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                           
                                                @lang('system_settings.update_language_file')    
                                           
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


                    <div class="col-lg-4  col-xl-3">
                        <div class="main-title mb-20" >
                            <h3 class="mb-0">{{ $language->language_name }} <span> </span> @lang('system_settings.language_backup_list') </h3>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-9 text-right col-md-12 mb-20  md_mb_20 title_custom_margin">
                    {{-- <div class="col-lg-12 col-xl-8 text-right col-md-12 mb-20 text_xs_left text_sm_left md_mb_20 title_custom_margin"> --}}


                    {{-- DEMO LIVE --}}
                   
                    @if(userPermission('backup-lang'))
                    <a href="{{route('backup-lang', $language->language_universal)}}" class="primary-btn small fix-gr-bg demo_view"> <span class="ti-arrow-circle-down pr-2"></span> {{ $language->language_name }} <span> </span> @lang('system_settings.language_backup') </a>
                    @endif

                        

                    </div>
                    </div>
                <div class="row">
                    <div class="col-lg-12">

                        <table id="default_table" class="table " cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                    <th>@lang('system_settings.size')</th>
                                    <th>@lang('system_settings.created_date_time')</th>
                                    <th>@lang('system_settings.backup_files')</th>
                                    <th>@lang('system_settings.file_type')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($backuplangs as $bLanguage)
                                <tr>
                                    <td>
                                        @php
                                        if(file_exists(@$bLanguage->source_link)){
                                        @$size = filesize(@$bLanguage->source_link);
                                            @$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
                                            @$power = @$size > 0 ? floor(log(@$size, 1024)) : 0;
                                            echo number_format(@$size / pow(1024, @$power), 2, '.', ',') . ' ' . @$units[@$power];
                                        }else{
                                            echo 'File already deleted.';
                                        }
                                        @endphp
                                    </td>
                                    <td> 
                                        {{@$bLanguage->created_at != ""? dateConvert(@$bLanguage->created_at):''}}

                                    </td>
                                    <td>{{@$bLanguage->file_name}}</td>
                                    <td>
                                        {{  $bLanguage->lang_type }}
                                    </td>
                                    <td>

                    
                                    @if(userPermission('download-files'))
                                        <a  class="primary-btn small tr-bg  " href="{{route('download-files',@$bLanguage->id)}}"  >
                                            <span class="pl ti-download"></span> @lang('common.download')
                                        </a>
                                    @endif
                                        

                                        @if(userPermission('delete_database'))
                                       <a data-target="#deleteDatabase{{@$bLanguage->id}}" data-toggle="modal" class="primary-btn small tr-bg  " href="{{url('/'.@$bLanguage->id)}}"  >
                                            <span class="pl ti-close"></span>  @lang('common.delete')
                                        </a>
                                        @endif
                                    </td>
                                </tr>



                                  <div class="modal fade admin-query" id="deleteDatabase{{@$bLanguage->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"> @lang('common.delete_item')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4> @lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal"> @lang('common.cancel')</button>
                                                    <a href="{{route('delete_database', [@$bLanguage->id])}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit"> @lang('common.delete')</button>
                                                     </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection
