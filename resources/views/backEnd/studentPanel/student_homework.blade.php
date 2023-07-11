@extends('backEnd.master')
@section('title')
@lang('homework.homework_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('homework.homework_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('homework.home_work')</a>
                <a href="#">@lang('homework.homework_list')</a>
            </div>
        </div>
    </div>
</section>

<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Start Student Details -->
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">

                   @foreach($records as $key => $record) 
                    <li class="nav-item">
                        <a class="nav-link @if($key== 0) active @endif " href="#tab{{$key}}" role="tab" data-toggle="tab">
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
                    @foreach ($records  as $key=> $record)
                        <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <x-table>
                                        <table class="school-table-data table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                  
                                                    <th>@lang('common.subject')</th>
                                                    <th>@lang('exam.marks')</th>
                                                    <th>@lang('homework.homework_date')</th>
                                                    <th>@lang('homework.submission_date')</th>
                                                    <th>@lang('homework.evaluation_date')</th>
                                                    <th>@lang('homework.obtained_marks')</th>
                                                    <th>@lang('common.status')</th>
                                                    <th>@lang('common.action')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php 
                                                $student_detail = App\SmStudent::where('user_id', Auth::user()->id)->first();
                                                if(moduleStatusCheck('University')){
                                                    $homeworks = $record->homeworkContents('is_university');
                                                }else{
                                                    $homeworks = $record->homeworkContents();
                                                }
                                               
                                                @endphp
                                                @foreach($homeworks  as $value)
                                                    @php
                                                        $student_result = $student_detail->homeworks->where('homework_id',$value->id)->first();
                                                        $uploadedContent = $student_detail->homeworkContents->where('homework_id',$value->id)->first();
                                                    
                                                    @endphp
                                                <tr>      
                                                    @if(moduleStatusCheck('University'))    
                                                    <td>{{$value->unSubject->subject_name}}</td>       
                                                    @else                                  
                                                    <td>{{@$value->subjects !=""?@$value->subjects->subject_name:""}}</td>
                                                    @endif
                                                    <td>{{@$value->marks}}</td>
                                                    <td  data-sort="{{strtotime(@$value->homework_date)}}" >{{@$value->homework_date != ""? dateConvert(@$value->homework_date):''}}</td>
                                                    <td  data-sort="{{strtotime(@$value->submission_date)}}" >{{@$value->submission_date != ""? dateConvert(@$value->submission_date):''}}</td>
                                                    <td  data-sort="{{strtotime(@$value->evaluation_date)}}" >
                                                        @if(!empty(@$value->evaluation_date))
                                                            {{@$value->evaluation_date != ""? dateConvert(@$value->evaluation_date):''}}
                                                        @endif
                                                    </td>
                                                    <td>{{@$student_result != ""? @$student_result->marks:''}}</td>
                                                    <td>
                                                        @if(@$student_result != "")
                                                            @if(@$student_result->complete_status == "C")
                                                                <button class="primary-btn small bg-success text-white border-0">@lang('homework.completed')</button>
                                                            @else
                                                                <button class="primary-btn small bg-warning text-white border-0">@lang('homework.incompleted')</button>
                                                            @endif
                                                        @else
                                                            <button class="primary-btn small bg-warning text-white border-0">@lang('homework.incompleted')</button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown CRM_dropdown">
                                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">@lang('common.select')</button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if(moduleStatusCheck('University'))
                                                                    <a class="dropdown-item modalLink" title="Homework View" data-modal-size="modal-lg" href="{{route('student.un_student_homework_view', [$value->un_semester_label_id, $value->id])}}">@lang('common.view')</a>
                                                                    @else 
                                                                    <a class="dropdown-item modalLink" title="Homework View" data-modal-size="modal-lg" href="{{route('student_homework_view', [@$value->class_id, @$value->section_id, @$value->id])}}">@lang('common.view')</a>
                                                                    @endif
                                                                        @if(!@$student_result->marks)
                                                                            <a class="dropdown-item modalLink" title="Add Homework content" data-modal-size="modal-lg" href="{{route('add-homework-content', [@$value->id])}}">
                                                                                @lang('homework.add_content')
                                                                            </a>
                                                                        @endif
                                                                        @if($uploadedContent != "")
                                                                        @if(@$student_result->marks && ($student_detail->id==@$student_result->student_id))
                                                                        
                                                                        @else
                                                                            <a class="dropdown-item modalLink" title="Delete Homework content" data-modal-size="modal-md" href="{{route('deleteview-homework-content', [@$value->id])}}">@lang('homework.delete_uploaded_content')</a>
                                                                        @endif
                                                                        @endif
                                                                        @if($uploadedContent != "")
                                                                            <a class="dropdown-item" href="{{route('download-uploaded-content',[$value->id,Auth::user()->id])}}">@lang('homework.download_uploaded_content') <span class="pl ti-download"></span></a>
                                                                        @endif
                                                                </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </x-table>
                                    </div>
                                </div>
                            </div>
                     

                    @endforeach
                    
                    <!-- End Fees Tab -->
                </div>
            </div>
            <!-- End Student Details -->
        </div>


    </div>
</section>

@include('backEnd.partials.data_table_js')
@push('script')
<script>
    $(document).ready(function () {
    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
      $($.fn.dataTable.tables(true))
        .DataTable()
        .columns.adjust()
        .responsive.recalc();
    });
  });
</script>

@endpush



@endsection
