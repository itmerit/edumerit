@extends('backEnd.master')
@section('title')
{{$student_detail->first_name.' '.$student_detail->last_name}} @lang('student.teacher_list')
@endsection
@section('mainContent')
@push('css')
<style>
    table.dataTable tbody th, table.dataTable tbody td {
        padding-left: 20px !important;
    }

    table.dataTable thead th {
        padding-left: 34px !important;
    }

    table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting:after,table.dataTable thead .sorting_desc:after {
        left: 16px;
        top: 10px;
    }
</style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.teacher_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="">@lang('student.teacher_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
       
        <div class="row">
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">

                @foreach($records as $key => $record) 
                    <li class="nav-item mb-0">
                        <a class="nav-link mb-0 @if($key== 0) active @endif " href="#tab{{$key}}" role="tab" data-toggle="tab">{{$record->class->class_name}} ({{$record->section->section_name}}) </a>
                    </li>
                    @endforeach

                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    @foreach($records as $key => $record) 
                        <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                            <div class="row mt-10">
                                <div class="col-lg-12">
                                    <table id="table_id" class="table" cellspacing="0" width="100%">

                                        <thead>
                                            <tr> 
                                                <th>@lang('hr.teacher_name')</th>
                                                <th>@lang('common.email')</th>
                                                <th>@lang('common.phone')</th>
                                            </tr>
                                        </thead>
            
                                        <tbody>
                                            @foreach($record->StudentTeacher as $value)
                                            <tr> 
                                                <td>
                                                    <img src="{{ file_exists(@$value->teacher->staff_photo) ? asset(@$value->teacher->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" class="img img-thumbnail" style="width: 60px; height: auto;">
                                                    {{@$value->teacher !=""?@$value->teacher->full_name:""}}
                                                </td> 
                                                <td>{{@$value->teacher !=""?@$value->teacher->email:""}}</td>
                                                <td>{{@$value->teacher !=""?@$value->teacher->mobile:""}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')