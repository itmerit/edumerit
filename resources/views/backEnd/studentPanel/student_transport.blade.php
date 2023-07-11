@extends('backEnd.master')
@section('title')
@lang('transport.transport')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('transport.transport')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="">@lang('transport.transport')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
       
        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('transport.transport_route_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 mb-30">
                                <!-- Start Student Meta Information -->
                                <div class="student-meta-box">
                                    <div class="student-meta-top"></div>
                                    <img class="student-meta-img img-100" src="{{ file_exists($student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="">
                                    <div class="white-box radius-t-y-0">
                                        <div class="single-meta mt-10">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('student.student_name')
                                                </div>
                                                <div class="value">
                                                   {{$student_detail->first_name.' '.$student_detail->last_name}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('student.admission_no')
                                                </div>
                                                <div class="value">
                                                    {{$student_detail->admission_no}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('student.class')
                                                </div>
                                                <div class="value">
                                                    @if($student_detail->defaultClass!="")
                                                        {{@$student_detail->defaultClass->class->class_name}}
                                                        {{-- ({{@$academic_year->year}}) --}}
                                                    @elseif ($student_detail->studentRecord !="")  
                                                    {{@$student_detail->studentRecord->class->class_name}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('student.section')
                                                </div>
                                                <div class="value">
                                                    
                                                    @if($student_detail->defaultClass!="")
                                                    {{@$student_detail->defaultClass->section->section_name}}
                                                   
                                                    @elseif ($student_detail->studentRecord !="")  
                                                    {{@$student_detail->studentRecord->section->section_name}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('common.gender')
                                                </div>
                                                <div class="value">
                                                    {{$student_detail->gender !=""?$student_detail->gender->base_setup_name:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Student Meta Information -->
                
                            </div>
                    <div class="col-lg-9">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">

                                <thead>
                                    <tr>
                                        <th class="col-6">@lang('transport.route')</th>
                                        <th class="col-6">@lang('transport.vehicle')</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($routes as $route)
                                    <tr>
                                        <td valign="top" class="col-6">{{@$route->route->title}}</td>
                                        <td class="col-6">
                                            <table>
                                                @php
                                                @$vehicles = explode(",",@$route->vehicle_id);
                                                @endphp
                                                @foreach($vehicles as $vehicle)
                                                <tr>
                                                    <td>
                                                        @php @$vehicle = App\SmVehicle::find(@$vehicle);
                                                        @endphp
                                                        {{@$vehicle->vehicle_no}}


                                                    </td>
                                                    <td >
                                                        <div class="col-sm-6">
                                                            
                                                        @if(@$student_detail->route_list_id == @$route->route->id && @$student_detail->vechile_id == @$vehicle->id)
                                                            <a href="javascript:void(0)" class="primary-btn small fix-gr-bg">Assigned</a> 
                                                        @endif
                                                        </div>
                                                        
                                                        <div class="col-sm-6">
                                                            
                                                            <a class="primary-btn small fix-gr-bg modalLink" title="Transport Details" data-modal-size="modal" href="{{route('student_transport_view_modal', [@$route->route->id, @$vehicle->id])}}">@lang('common.view')</a>
                                                    
                                                        </div>
                                                        

                                                    </td>

                                                    
                                                </tr>
                                                @endforeach

                                            </table>
                                        </td> 
                                    </tr>
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