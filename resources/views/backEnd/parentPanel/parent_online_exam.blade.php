@extends('backEnd.master')
@section('title') 
@lang('exam.active_exams')
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
@php
    $user =$student;
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.online_exam') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('parent_online_examination',$user->id) }}">@lang('exam.active_exams')</a>
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
                                            <th>@lang('exam.title')</th>
                                            <th>@lang('exam.subject')</th>
                                            <th>@lang('exam.exam_date')</th>
                                            <th>@lang('exam.duration')</th>
                                            <th>@lang('common.action')</th>
                                            <th>@lang('common.status')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($record->OnlineExam  as $online_exam)
                                            @php
                                                @$submitted_answer = $student->studentOnlineExam->where('online_exam_id',$online_exam->id)->first();
                                            @endphp
                                            @if(!in_array(@$online_exam->id, @$marks_assigned))
                                                <tr>
                                                    <td>{{@$online_exam->title}}</td>
                                                    <td>{{@$online_exam->subject !=""?@$online_exam->subject->subject_name:""}}</td>
                                                    <td data-sort="{{strtotime(@$online_exam->date)}}">
                                                        {{@$online_exam->date != ""? dateConvert(@$online_exam->date):''}}

                                                        <br>
                                                        Time: {{date('h:i A', strtotime(@$online_exam->start_time)).' - '.date('h:i A', strtotime(@$online_exam->end_time))}}
                                                    </td>
                                                    @php

                                                        $totalDuration = $online_exam->end_time !='NULL' ? Carbon::parse($online_exam->end_time)->diffinminutes( Carbon::parse($online_exam->start_time) ) : 0;

                                                    @endphp
                                                    <td>
                                                        {{  $online_exam->end_time !='NULL' ? gmdate($totalDuration) : 'Unlimited'}}  @lang('exam.minutes')
                                                    </td>
                                                    <td>
                                                        {{ $online_exam->total_durations }} @lang('exam.minutes')
                                                    </td>

                                                    <td>
                                                    @if( !empty( $submitted_answer))
                                                        @if(@$submitted_answer->status == 1)
                                                            <span class="btn primary-btn small  fix-gr-bg"
                                                                style="background:green">@lang('exam.already_submitted')</span>
                                                            {{-- <span class="btn btn-success">Already Submitted</span> --}}
                                                            {{-- <a class="btn btn-success" href="#">@lang('exam.not_yet_start')</a> --}}
                                                        {{-- @else
                                                            <span class="btn btn-success">Already Submitted</span> --}}
                                                        @endif
                                                    @else
                                                        
                                                    @php
                                                    // date_default_timezone_set("Asia/Dhaka");
                                                        $startTime = strtotime($online_exam->date . ' ' . $online_exam->start_time);
                                                        $endTime = strtotime($online_exam->date . ' ' . $online_exam->end_time);
                                                        $now = date('h:i:s');
                                                        $now =  strtotime("now");
                                                    @endphp

                                                        @if($startTime <= $now && $now <= $endTime)
                                                            <span class="btn primary-btn small  fix-gr-bg"
                                                                style="background:green">@lang('exam.running')</span>    
                                                        
                                                        @elseif($startTime > $now && $now < $endTime)
                                                            <span class="btn primary-btn small  fix-gr-bg"
                                                                style="background:blue">@lang('exam.not_yet_start')</span>
                                                        @elseif($now >= $endTime)
                                                            <span class="btn primary-btn small  fix-gr-bg"
                                                                style="background:#dc3545">Closed</span>
                                                        @endif
                                                    {{-- @if(strtotime($online_exam->start_time) > strtotime(date('Y-m-d'))) --}}
                                                            {{-- @if($online_exam->start_time > $now && $online_exam->date == date('Y-m-d'))
                                                                <a class="btn btn-success" href="#" >@lang('exam.exam_waiting')</a>
                                                            @elseif($online_exam->start_time < $now && $online_exam->end_time > $now && $online_exam->date == date('Y-m-d'))
                                                                <a class="btn btn-success" href="#" >@lang('exam.exam_running')</a>
                                                            @else
                                                                <a class="btn btn-danger" href="#" >@lang('exam.exam_closed')</a>
                                                            @endif --}}
                                                        {{-- @endif --}}

                                                    @endif
                                                </td>
                                                </tr>
                                            @endif
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

<div class="modal fade admin-query" id="deleteOnlineExam" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('common.delete_item')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'online-exam-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" id="online_exam_id">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@include('backEnd.partials.data_table_js')
