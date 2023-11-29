<?php
$start = strtotime($leaveDetails->leave_from);
$end = strtotime($leaveDetails->leave_to);
$days_between = ceil(abs($end - $start) / 86400);
$days = $days_between + 1;
?>
<div class="container-fluid">
    <div class="student-details">
        <div class="row">
            <div class="{{isset($apply)? 'col-md-12':'col-md-8'}}">
                <div class="student-meta-box">
                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-2 col-md-5">
                                <div class="value text-left">
                                    @lang('leave.leave_type')
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7">
                                <div class="name">
                                    @if($leaveDetails->leaveDefine !="" && $leaveDetails->leaveDefine->leaveType !="")
                                        {{$leaveDetails->leaveDefine->leaveType->type}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-5">
                                <div class="value text-left">
                                    @lang('common.duration')
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7">
                                <div class="name">
                                    {{$days == 1? $days.' day': $days.' days'}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-2 col-md-5">
                                <div class="value text-left">
                                    @lang('leave.leave_from')
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7">
                                <div class="name">


                                    {{$leaveDetails->leave_from != ""? dateConvert($leaveDetails->leave_from):''}}

                                </div>
                            </div>
                            <div class="col-lg-2 col-md-5">
                                <div class="value text-left">
                                    @lang('leave.leave_to')
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7">
                                <div class="name">

                                    {{$leaveDetails->leave_to != ""? dateConvert($leaveDetails->leave_to):''}}


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-2 col-md-5">
                                <div class="value text-left">
                                    @lang('leave.apply_date')
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7">
                                <div class="name">
                                    {{$leaveDetails->apply_date != ""? dateConvert($leaveDetails->apply_date):''}}


                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="value text-left">
                                    @lang('leave.reason')
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10">
                                <div class="name">
                                    {{$leaveDetails->reason}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="value text-left">
                            @lang('common.attach_file') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(@$leaveDetails->file != "")
                             <a href="{{url(@$leaveDetails->file)}}" download>
                                @lang('common.download')  <span class="pl ti-download"></span>
                             </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
                    


                    @if(!isset($apply))

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-approve-leave',
                                    'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="{{$leaveDetails->id}}">
                        <div class="single-meta mt-40">
                            <div class="row">
                                <div class="col-lg-2 col-md-5">
                                    <div class="value text-left">
                                        @lang('leave.leave_status')
                                    </div>
                                </div>
                                @if (Auth::user()->role_id==1)
                                    <div class="col-lg-4 col-md-7">
                                        <div class="d-flex radio-btn-flex flex-column">
                                            <div class="">
                                                <input type="radio" name="approve_status" value="P" class="common-radio"
                                                       id="P" {{$leaveDetails->approve_status == "P"? 'checked':''}}>
                                                <label for="P">@lang('common.pending')</label>
                                            </div>
                                            <div class="">
                                                <input type="radio" name="approve_status" value="A" class="common-radio"
                                                       id="A" {{$leaveDetails->approve_status == "A"? 'checked':''}}>
                                                <label for="A">@lang('common.approve')</label>
                                            </div>
                                            <div class="">
                                                <input type="radio" name="approve_status" value="C" class="common-radio"
                                                       id="C" {{$leaveDetails->approve_status == "C"? 'checked':''}}>
                                                <label for="C">@lang('common.cancel')</label>

                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-4 col-md-7">
                                        <div class="d-flex radio-btn-flex flex-column">
                                            <div class="">
                                                {{-- <input type="radio" name="approve_status"  value="P" class="common-radio" id="P" {{$leaveDetails->approve_status == "P"? 'checked':''}}> --}}

                                                @if($leaveDetails->approve_status == 'P')
                                                    <button class="primary-btn small tr-bg">@lang('common.pending')</button>
                                                @endif

                                                @if($leaveDetails->approve_status == 'A')
                                                    <button class="primary-btn small tr-bg">@lang('common.approved')</button>
                                                @endif

                                                @if($leaveDetails->approve_status == 'C')
                                                    <button class="primary-btn small bg-danger text-white border-0">@lang('common.cancelled')</button>
                                                @endif
                                            </div>


                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>
                        <div class="single-meta mt-30">
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg submit">
                                        <span class="ti-check"></span>
                                        @lang('leave.save_leave_status')
                                    </button>
                                </div>

                            </div>
                        </div>
                        {{ Form::close() }}
                    @endif
                </div>
            </div>
            @if(!isset($apply))
                <div class="col-md-4">
                    <!-- Start Student Meta Information -->
                    <div class="student-meta-box">
                        @if ($leaveDetails->role_id == 2)
                            <h5 class="mt-20 mb-20">@lang('hr.user_details')</h5>
                        @else
                            <h5 class="mt-20 mb-20">@lang('hr.staff_details')</h5>
                        @endif
                        <div class="white-box-modal radius-t-y-0">

                            <div class="single-meta mt-10">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @if ($leaveDetails->role_id == 2)
                                            @lang('hr.user_name')
                                        @else
                                            @lang('hr.staff_name')
                                        @endif
                                    </div>
                                    <div class="value">
                                        @if ($leaveDetails->role_id == 2)
                                            {{$leaveDetails->student != ""? $leaveDetails->student->full_name:''}}
                                        @else
                                            {{$leaveDetails->staffs != ""? $leaveDetails->staffs->full_name:''}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @if ($leaveDetails->role_id == 2)
                                            @lang('hr.user_no')
                                        @else
                                            @lang('hr.staff_no')
                                        @endif
                                    </div>
                                    <div class="value">
                                        @if ($leaveDetails->role_id == 2)
                                            {{$leaveDetails->student != ""? $leaveDetails->student->id:''}}
                                        @else
                                            {{$leaveDetails->staffs != ""? $leaveDetails->staffs->staff_no:''}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('hr.date_of_joining')
                                    </div>
                                    <div class="value">
                                        @if ($leaveDetails->role_id == 2)
                                            {{$leaveDetails->student->created_at != ""? dateConvert($leaveDetails->student->created_at):''}}
                                        @else
                                            {{$leaveDetails->staffs->date_of_joining != ""? dateConvert($leaveDetails->staffs->date_of_joining):''}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="leave-details">
                            <h5 class="mt-20 mb-20">@lang('leave.leave_details')</h5>
                            <table class="table school-table-style-modal" cellspacing="0" width="100%">

                                <thead>

                                <tr>
                                    <th>@lang('common.type')</th>
                                    <th>@lang('leave.remaining_days')</th>
                                    <th>@lang('leave.extra_taken')</th>
                                    <th>@lang('leave.total_days')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($staff_leaves as $staff_leave)
                                    @php

                                        $approved_leaves = App\SmLeaveRequest::approvedLeaveModal($staff_leave->id, $leaveDetails->role_id, $leaveDetails->staff_id);
                                            $remaining_days = $staff_leave->days - $approved_leaves;
                                    @endphp
                                    <tr>
                                        <td>{{$staff_leave->leaveType->type}}</td>
                                        <td>{{$remaining_days >= 0? $remaining_days:0}}</td>

                                        <td>{{$remaining_days < 0? $approved_leaves - $staff_leave->days:0}}</td>
                                        <td>{{$staff_leave->days}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- End Student Meta Information -->

                </div>
            @endif
        </div>

    </div>
</div>


<!-- <div class="col-lg-12 text-center mt-40">
    <div class="mt-40 d-flex justify-content-between">
        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Cancel</button>

        <button class="primary-btn fix-gr-bg" id="" data-dismiss="modal" type="button">save information</button>
    </div>
</div> -->
