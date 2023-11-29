@extends('backEnd.master')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>{{@$data->name}} @lang('common.info') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">{{@$data->name}} @lang('common.info') </a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area mb-40">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">{{@$data->name}} @lang('common.info')  </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12"> 
                <div class="white-box">
                    <div class="white-box">
                        <h1>{{@$data->name}}</h1>
                        <div class="add-visitor">
                            <table style="width:100%; box-shadow: none;" class="table school-table-style"> 
                                <tr>
                                    <td>@lang('common.name')</td>
                                    <td>{{@$data->name}}</td> 
                                </tr>
                                <tr>
                                    <td>@lang('common.notes')</td>
                                    <td>{{@$data->notes}}</td> 
                                </tr>
                                <tr>
                                    <td>@lang('common.module_version')</td>
                                    <td>{{@$data->version}}</td> 
                                </tr>
                                <tr>
                                    <td>Check update</td>
                                    <td><a href="{{@$data->update_url}}" target="_blank"> <i class="ti-new-window"> </i> Update </a> </td>
                                </tr>
                                <tr>
                                    <td>@lang('common.purchase_code')</td>
                                    <td>{{@$data->purchase_code}}</td>
                                </tr>
                                <tr>
                                    <td>@lang('common.installed_domain')</td>
                                    <td>{{@$data->installed_domain}}</td>
                                </tr> 

                                <tr>
                                    <td> @lang('common.system_activated_date')</td>
                                    <td>{{dateConvert(@$data->activated_date)}}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


@endsection
