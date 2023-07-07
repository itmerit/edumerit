@extends('backEnd.master')
@section('title')
@lang('leave.apply_leave')
@endsection 
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('leave.apply_leave')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('hr.human_resource')</a>
                <a href="#">@lang('leave.apply_leave')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
<div class="container-fluid p-0">
<div class="row">

    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-0">@lang('leave.leave_list')</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <table id="table_id" class="table" cellspacing="0" width="100%">

                    <thead>
                        
                        <tr>
                            <th>@lang('common.type')</th>
                            <th>@lang('leave.days')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($my_leaves as $my_leave)
                        <tr>
                            <td>{{$my_leave->leaveType !=""?$my_leave->leaveType->type:""}}</td>
                            <td>{{$my_leave->days}}</td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')