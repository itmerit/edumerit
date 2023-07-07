@extends('backEnd.master')

@section('title')
@lang('study.others_download')
@endsection
@push('css')
    <style>
        table.dataTable thead th {
            padding-left: 30px !important;
        }
        table.dataTable tbody th, table.dataTable tbody td {
            padding: 20px 10px 20px 17px !important;
        }
    </style>
@endpush
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('study.others_download')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('study.study_material')</a>
                <a href="#">@lang('study.others_download')</a>
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
                    <div class="tab-content mt-40">
                        @foreach($records as $key => $record)
                            <div role="tabpanel" class="tab-pane fade  @if($key== 0) active show @endif" id="tab{{$key}}">
                                <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">

                                    <thead>

                                        <tr>
                                            <th>@lang('study.content_title')</th>
                                            <th>@lang('common.type')</th>
                                            <th>@lang('common.date')</th>
                                            <th>@lang('study.available_for')</th>
                                            <th style="max-width:30%">@lang('common.description')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @php
                                            if(moduleStatusCheck('University')){
                                                $contents =  $record->getUploadContent('ot','for_university');
                                            }else{
                                                $contents =  $record->getUploadContent('ot',0);
                                            }
                                        @endphp

                                        @foreach($contents as $value)
                                            <tr>

                                                <td>{{@$value->content_title}}</td>
                                                <td>
                                                    @if(@$value->content_type == 'as')
                                                        {{'Assignment'}}
                                                    @elseif(@$value->content_type == 'st')
                                                        {{'Study Material'}}
                                                    @elseif(@$value->content_type == 'sy')
                                                        {{'Syllabus'}}
                                                    @else
                                                        {{'Others Download'}}
                                                    @endif
                                                </td>
                                                <td  data-sort="{{strtotime(@$value->upload_date)}}" >
                                                {{@$value->upload_date != ""? dateConvert(@$value->upload_date):''}}

                                                </td>
                                                <td>
                                                    @if(@$value->available_for_admin == 1)
                                                        @lang('study.all_admins')<br>
                                                    @endif
                                                    @if(@$value->available_for_all_classes == 1)
                                                        @lang('study.all_classes_student')
                                                    @endif

                                                    @if(@$value->classes != "" && $value->sections != "")
                                                        @lang('study.all_students_of') ({{@$value->classes->class_name.'->'.@$value->sections->section_name}})
                                                    @endif
                                                    @if(@$value->classes != "" && $value->sections ==null)
                                                    @lang('study.all_students_of') ({{@$value->classes->class_name.'->'.'All Sections'}})
                                                @endif
                                                </td>
                                                <td>

                                                {{\Illuminate\Support\Str::limit(@$value->description, 500)}}


                                                </td>
                                                <td>
                                                    <x-drop-down>
                                                            <a data-modal-size="modal-lg" title="View Content Details" class="dropdown-item modalLink" href="{{route('upload-content-student-view', $value->id)}}">@lang('common.view')</a>
                                                            @if(@$value->upload_file != "")
                                                                @if(userPermission("student_others_download"))
                                                                <a class="dropdown-item" href="{{url(@$value->upload_file)}}" download>
                                                                    @lang('common.download')  <span class="pl ti-download"></span>
                                                                </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        </div>
                                                    </x-drop-down>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                    </table>
                                </x-table>
                            </div>
                        @endforeach
                    </div>
            </div>
        </div>


    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')