@extends('backEnd.master')
@section('title') 
@push('css')
    <style>
        .check_box_table .QA_table .table tbody td:first-child {
            padding-left: 25px !important;
        }
        .check_box_table .QA_table .table tbody td:nth-child(2) {
            padding-left: 10px !important;
        }

    </style>
@endpush
@lang('dormitory.dormitory')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('dormitory.dormitory') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="">@lang('dormitory.dormitory')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        
        <div class="row">
            <div class="col-lg-3 mb-30">
                <!-- Start Student Meta Information -->
                    @if (moduleStatusCheck('University'))
                        @includeIf('university::promote.inc.student_profile',['student_detail'=>$student_detail->defaultClass])
                    @else
                        @includeIf('backEnd.studentInformation.inc.student_profile')
                    @endif
              
                <!-- End Student Meta Information -->

            </div>
            <div class="col-lg-9 mt-40">

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">

                                <thead>
                                    <tr>
                                        <th>@lang('dormitory.dormitory')</th>
                                        <th>@lang('dormitory.room_name')</th>
                                        <th>@lang('dormitory.room_type')</th>
                                        <th>@lang('dormitory.no_of_bed')</th>
                                        <th>@lang('common.status')</th>
                                        <th>@lang('dormitory.cost_per_bed')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($room_lists as $values)
                                        @foreach($values as $room_list)
                                        <tr>
                                            <td>{{isset($room_list->dormitory->dormitory_name)? $room_list->dormitory->dormitory_name:''}}</td>
                                            <td>{{$room_list->name}}</td>
                                            <td>{{isset($room_list->roomType->type)? $room_list->roomType->type: ''}}</td>
                                            <td>{{$room_list->number_of_bed}}</td>
                                            <td>
                                                @if($student_detail->room_id == $room_list->id)
                                                    <button class="primary-btn small fix-gr-bg">@lang('dormitory.assigned')</button>
                                                @endif

                                            </td>
                                            <td>{{$room_list->cost_per_bed}}</td>
                                        </tr>
                                        @endforeach
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
