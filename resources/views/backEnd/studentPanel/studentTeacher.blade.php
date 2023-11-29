@extends('backEnd.master')
@section('title')
@lang('hr.teachers_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('hr.teachers_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('hr.teachers')</a>
                <a href="#">@lang('hr.teachers_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
       
        <div class="row">
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">

                @foreach($records as $key => $record) 
                    <li class="nav-item">
                        <a class="nav-link @if($key== 0) active @endif " href="#tab{{$key}}" role="tab" data-toggle="tab">{{$record->class->class_name}} ({{$record->section->section_name}}) </a>
                    </li>
                    @endforeach

                </ul>
                <!-- Tab panes -->
                <div class="tab-content mt-40">
                    @foreach($records as $key => $record) 
                        <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead> 
                                    <tr> 
                                        <th>@lang('hr.teacher_name')</th>
                                        @if(generalSetting()->teacher_email_view)
                                        <th>@lang('common.email')</th>
                                        @endif 
                                        @if(generalSetting()->teacher_phone_view)
                                        <th>@lang('common.phone')</th>
                                        @endif 
                                    </tr>
                                </thead>
    
                                <tbody>
                                    @foreach($record->StudentTeacher as $value)
                                    <tr> 
                                        <td>
                                            <img src="{{ file_exists(@$value->teacher->staff_photo) ? asset(@$value->teacher->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" class="img img-thumbnail" style="width: 60px; height: auto;">
                                            {{@$value->teacher !=""?@$value->teacher->full_name:""}}
                                        </td> 
                                        @if(generalSetting()->teacher_email_view)
                                        <td>{{@$value->teacher !=""?@$value->teacher->email:""}}</td>
                                        @endif 
                                        @if(generalSetting()->teacher_phone_view)
                                        <td>{{@$value->teacher !=""?@$value->teacher->mobile:""}}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </x-table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@include('backEnd.partials.data_table_js')