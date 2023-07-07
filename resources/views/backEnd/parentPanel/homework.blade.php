@extends('backEnd.master')
@section('title') 
@lang('homework.home_work_list')
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
                <h1>@lang('homework.home_work_list')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('homework.home_work')</a>
                    <a href="#">@lang('homework.home_work_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="row mt-40">
            <!-- Start Student Details -->
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">

                    @foreach($records as $key => $record)
                        <li class="nav-item mb-0">
                            <a class="nav-link mb-0 @if($key== 0) active @endif " href="#tab{{$key}}" role="tab" data-toggle="tab">
                                @if(moduleStatusCheck('University'))
                                {{$record->semesterLabel->name}} ({{$record->unSection->section_name}}) - {{@$record->unAcademic->name}}
                           @else
                                {{$record->class->class_name}} ({{$record->section->section_name}})
                            @endif
                            </a>
                        </li>
                    @endforeach

                </ul>


                <!-- Tab panes -->
                <div class="tab-content">
                <!-- Start Fees Tab -->
                @foreach($records as $key=> $record)
                    <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                        <div class="row mt-10">
                            <div class="col-lg-12">
                                    <table id="table_id" class="table" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>

                                            <th>@lang('homework.subject')</th>
                                            <th>@lang('exam.marks')</th>
                                            <th>@lang('homework.home_work_date')</th>
                                            <th>@lang('homework.submission_date')</th>
                                            <th>@lang('homework.evaluation_date')</th>
                                            <th>@lang('homework.obtained_marks')</th>
                                            <th>@lang('common.status')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                if(moduleStatusCheck('University')){
                                                    $homeworks = $record->homeworkContents('is_university');
                                                }else{
                                                    $homeworks = $record->homeworkContents();
                                                }
                                            @endphp
                                        @foreach($homeworks as $value)
                                            @php
                                                $student_result = $student_detail->homeworks->where('homework_id',$value->id)->first();
                                            @endphp

                                            <tr>

                                                @if(moduleStatusCheck('University'))
                                                    <td>{{$value->unSubject->subject_name}}</td>
                                                    @else
                                                    <td>{{@$value->subjects !=""?@$value->subjects->subject_name:""}}</td>
                                                @endif

                                                <td>{{$value->marks}}</td>
                                                <td data-sort="{{strtotime($value->homework_date)}}">
                                                    {{$value->homework_date != ""? dateConvert($value->homework_date):''}}
                                                </td>
                                                <td data-sort="{{strtotime($value->submission_date)}}">{{$value->submission_date != ""? dateConvert($value->submission_date):''}}</td>
                                                <td data-sort="{{strtotime($value->evaluation_date)}}">
                                                    @if(!empty($value->evaluation_date))
                                                        {{$value->evaluation_date != ""? dateConvert($value->evaluation_date):''}}

                                                    @endif
                                                </td>


                                                <td>{{$student_result != ""? $student_result->marks:''}}</td>
                                                <td>
                                                    @if($student_result != "")

                                                        @if($student_result->complete_status == "C")
                                                            <button class="primary-btn small bg-success text-white border-0">@lang('homework.completed')</button>
                                                        @else
                                                            <button class="primary-btn small bg-warning text-white border-0">@lang('homework.incompleted')</button>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown CRM_dropdown">
                                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                            @lang('common.select')
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @if(userPermission('parent_homework_view'))
                                                            @if(moduleStatusCheck('University'))
                                                            <a class="dropdown-item modalLink" title="Homework View"
                                                            data-modal-size="modal-lg"
                                                            href="{{route('un_student_homework_view', [$value->un_semester_label_id, $value->id])}}">@lang('common.view')</a>
                                                            @else
                                                                <a class="dropdown-item modalLink" title="Homework View"
                                                                   data-modal-size="modal-lg"
                                                                   href="{{route('parent_homework_view', [$value->class_id, $value->section_id, $value->id])}}">@lang('common.view')</a>
                                                            @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>

                @endforeach
                <!-- End Fees Tab -->
                </div>
            </div>
   <!-- End Student Details -->






        </div>
    </section>
@endsection

@include('backEnd.partials.data_table_js')
