@extends('backEnd.master')
@section('title')
{{$student_detail->first_name.' '.$student_detail->last_name}} @lang('common.subjects')
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
            <h1>@lang('common.subjects')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('parent_subjects', [$student_detail->id])}}">@lang('common.subjects')</a>
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
                    @foreach($records as $key => $record) 
                        <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                            <div class="row mt-10">
                                <div class="col-lg-12">
                                    <table id="table_id" class="table" cellspacing="0" width="100%">

                                        <thead>
                                            <tr>
                                                <th>@lang('common.subject')</th>
                                                @if(moduleStatusCheck('University'))
                                                <th>@lang('university::un.number_of_hours')</th>
                                                @endif 
                                                <th>@lang('common.teacher')</th>
                                                <th>@lang('academics.subject_type')</th>
                                            </tr>
                                        </thead>

                                        @php 
                                        if(moduleStatusCheck('University')){
                                            $subjects = $record->UnAssignSubject;
                                        }else{
                                            $subjects = $record->AssignSubject ;
                                        }
                                        @endphp
            
                                        <tbody>
                                            @foreach($subjects as $assignSubject)
                                            <tr>
                                                <td>{{@$assignSubject->subject!=""?@$assignSubject->subject->subject_name:""}} - ( {{@$assignSubject->subject->subject_code}} )</td>
                                                @if(moduleStatusCheck('University'))
                                                <td>{{@$assignSubject->subject->number_of_hours}}</td>
                                                @endif

                                                <td>{{@$assignSubject->teacher!=""?@$assignSubject->teacher->full_name:""}}</td>
                                                @if(moduleStatusCheck('University'))
                                                <td>
                                                    {{@$assignSubject->subject->subject_type}}
                                                </td>
                                                @else
                                                <td>
                                                    @if(!empty(@$assignSubject->subject))
                                                    {{@$assignSubject->subject->subject_type == "T"? 'Theory': 'Practical'}}
                                                    @endif
                                                </td>
                                                @endif
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