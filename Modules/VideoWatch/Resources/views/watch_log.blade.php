@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('study.watch') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('study.study_material')</a>
                <a href="#">@lang('study.watch')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area DM_table_info">
    <div class="container-fluid p-0">
       {{--  <div class="row justify-content-between p-3">
            <div class="bc-pages">
             
            </div>
    </div> --}}

    <div class="col-md-12">

        <div class="row student-details mt_0_sm">

            <!-- Start Sms Details -->
            <div class="col-lg-12 p-0">
                <ul class="nav nav-tabs mt_0_sm mb-20 ml-0 mb-40 sm_mb_20" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#group_email_sms" selectTab="G" role="tab" data-toggle="tab">@lang('study.seen')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" selectTab="I" href="#indivitual_email_sms" role="tab" data-toggle="tab">@lang('study.unseen')</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" selectTab="C" href="#class_section_email_sms" role="tab" data-toggle="tab">@lang('common.class')</a>
                    </li> --}}

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <input type="hidden" name="selectTab" id="selectTab">
                    <div role="tabpanel" class="tab-pane fade show active" id="group_email_sms">
                        <table id="table_id" class="table  mt-20" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <tr>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('student.roll_no')</th>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('common.title')</th>
                                        <th>@lang('common.view')</th>
                                       
                                    </tr>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($watchLogs as $log)
                                <tr>
                                    <td>{{@$log->admission_no}}</td>
                                    <td>{{@$log->roll_no}}</td>
                                    <td>{{@$log->full_name}}</td>
                                    <td>{{@$log->content_title}}</td>
                                    <td>{{dateConvert(@$log->created_at)}} - {{date('h:i A', strtotime($log->created_at))}}</td>
                                  
                                </tr>
                            
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="indivitual_email_sms">
                        <div class="row mb-35">

                            <div class="col-lg-12">
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <tr>
                                                <th>@lang('student.admission_no')</th>
                                                <th>@lang('student.roll_no')</th>
                                                <th>@lang('common.name')</th>
                                                <th>@lang('common.class')</th>
                                                <th>@lang('common.section')</th>
                                               
                                            </tr>
                                        </tr>
                                    </thead>
        
                                    <tbody>
                                        @foreach($unseen_lists as $log)
                                        <tr>
                                            <td>{{@$log['admission_no']}}</td>
                                            <td>{{@$log['roll_no']}}</td>
                                            <td>{{@$log['full_name']}}</td>
                                            <td>{{@$log['class']}}</td>
                                            <td>{{@$log['section']}}</td>
                                          
                                        </tr>
                                    
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            
                        </div>
                    </div>
                    <!-- End Individual Tab -->

                   
                </div>
            </div>
        </div>
    </div>




    </div>
</section>


@endsection
@include('backEnd.partials.data_table_js')