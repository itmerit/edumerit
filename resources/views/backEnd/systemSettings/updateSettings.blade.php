@extends('backEnd.master')

@section('title')
@lang('system_settings.update_system')
@endsection

@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.update_system') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.update_system') </a>
                </div>
            </div>
        </div>
    </section>   

    <section class="admin-visitor-area up_admin_visitor empty_table_tab">
        <div class="container-fluid p-0">

        {{-- <div class="row">
            <div class="offset-lg-8 col-lg-4 text-right col-md-12">
                <a href="{{route('database-upgrade')}}" class="primary-btn small fix-gr-bg  demo_view">
                    <span class="ti-support"></span>
                    @lang('system_settings.database')   {{__('sync')}}
                </a>
            </div>
        </div> --}}

            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-40 cust-lawngreen">@lang('system_settings.upload_from_local_directory')</h3>
                            </div>
                            @if(userPermission('admin/update-system'))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'versionUpdateInstall', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif   
                            <div class="white-box sm_mb_20  ">
                                    <div class="add-visitor">

                                        <div class="row no-gutters input-right-icon mb-20">
                                            <div class="col">
                                                <div class="primary_input">
                                                    
                                                    
                                                    @if ($errors->has('updateFile'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('updateFile') }}
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="primary-btn-small-input" type="button">
                                                   
                                                </button>

                                            </div>
                                        </div>
                                        <div class="primary_input">
                                            <div class="primary_file_uploader">
                                                    <input class="primary_input_field form-control {{ $errors->has('updateFile') ? ' is-invalid' : '' }}" readonly="true" type="text"
                                                    placeholder="{{isset($editData->file) && @$editData->file != ""? getFilePath3(@$editData->file):'Upload File'}} "  id="placeholderInputUpdate" name="updateFile">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="upload_update_file">@lang('common.browse')</label>
                                                    <input type="file" class="d-none form-control" name="updateFile" id="upload_update_file">
                                                </button>
                                            </div>
                                        </div>
                                        @php 
                                            $tooltip = "";
                                            if(userPermission('admin/update-system')){
                                                    $tooltip = "";
                                                }else{
                                                    $tooltip = "You have no permission to add";
                                                }
                                        @endphp
                                        <div class="row mt-40">
                                            <div class="col-lg-12 text-center"> 
                                                <button class="primary-btn fix-gr-bg submit"  data-toggle="tooltip" title="{{@$tooltip}}">
                                                    <span class="ti-check"></span>
                                                    @if(isset($session))
                                                        @lang('common.update')
                                                    @else
                                                        @lang('common.save')
                                                    @endif
                                                    @lang('common.file')
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
                        <div class="col-lg-12">
                            <div class="main-title mb-20" >
                                <h3 >@lang('system_settings.update_details')</h3>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <h1> @lang('system_settings.system_info') </h1>
                                <div class="add-visitor">
                                    <table style="width:100%; box-shadow: none;" class="table school-table-style">
                                      
                                        <tr>
                                            <td>@lang('system_settings.software_version')</td>
                                            <td>{{@file_get_contents('storage/app/.version')}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('system_settings.check_update')</td>
                                            <td><a href="https://codecanyon.net/user/codethemes/portfolio" target="_blank"> <i class="ti-new-window"> </i> Update </a> </td>
                                        </tr> 
                                        <tr>
                                            <td> @lang('system_settings.PHP_version')</td>
                                            <td>{{phpversion() }}</td>
                                        </tr>
                                        <tr>
                                            <td> @lang('system_settings.curl_enable')</td>
                                            <td>@php
                                            if  (in_array  ('curl', get_loaded_extensions())) {
                                                echo 'enable';
                                            }
                                            else {
                                                echo 'disable';
                                            }
                                            @endphp</td>
                                        </tr>
                            
                                        
                                        <tr>
                                            <td> @lang('system_settings.purchase_code')</td>

                                            <td>{{__('Verified')}}
                                            @if(! Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                 @includeIf('service::license.revoke')
                                             @endif 
                                             </td>
                                        </tr>
                            

                                        <tr>
                                            <td> @lang('system_settings.install_domain')</td>
                                            <td>{{@$data->system_domain}}</td>
                                        </tr>

                                        <tr>
                                            <td> @lang('system_settings.system_activation_date')</td>
                                            <td>{{@dateConvert($data->system_activated_date)}}</td>
                                        </tr>
                                        <tr>
                                            <td> @lang('system_settings.last_update')</td>
                                            <td>
                                            @if(is_null($data->last_update))
                                                 {{@dateConvert($data->system_activated_date)}}
                                            @else
                                                    {{@dateConvert($data->last_update)}}
                                            @endif
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('script')
    <script language="JavaScript">

        $('#selectAll').click(function () {
            $('input:checkbox').prop('checked', this.checked);

        });

        $(document).on('change', '#upload_update_file', function(event){
            getFileName($(this).val(),'#placeholderInputUpdate');
        });


    </script>
@endsection


