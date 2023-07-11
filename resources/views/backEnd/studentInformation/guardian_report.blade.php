@extends('backEnd.master')
    @section('title')
        @lang('reports.guardian_report')
    @endsection
@section('mainContent')
<input type="text" hidden value="{{ @$clas->class_name }}" id="cls">
<input type="text" hidden value="{{ @$clas->section_name->sectionName->section_name }}" id="sec">
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.guardian_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.guardian_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'guardian_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                @if(moduleStatusCheck('University'))
                                @includeIf('university::common.session_faculty_depart_academic_semester_level',['required' => ['US'], 'hide' => ['USUB']])
                                @else
                                @include('backEnd.common.search_criteria', [
                                'visiable'=>['class', 'section'],
                                'div'=>'col-lg-6',
                                'required'=>['class']
                                ])
                                @endif

                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
         
            @if(isset($student_records))
            <div class="row mt-40"> 
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('reports.guardian_report')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                    
                                        <tr>
                                            @if(moduleStatusCheck('University'))
                                            <th>@lang('university::un.semester_label')</th>
                                            <th>@lang('university::un.department')</th>
                                            @else
                                            <th>@lang('common.class')</th>
                                            <th>@lang('common.section')</th>
                                            @endif

                                            <th>@lang('student.admission_no')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('common.mobile')</th>
                                            <th>@lang('student.guardian_name')</th>
                                            <th>@lang('reports.relation_with_guardian')</th>
                                            <th>@lang('student.guardian_phone') </th>
                                            <th>@lang('student.father_name') </th>
                                            <th>@lang('student.father_phone') </th>
                                            <th>@lang('student.mother_name') </th>
                                            <th>@lang('student.mother_phone') </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    
                                        @foreach($student_records as $record)
                                        <tr>
                                            @if(moduleStatusCheck('University'))
                                            <td>{{@$record->UnSemesterLabel->name}}</td>
                                            <td> {{@$record->unDepartment->name}}</td>
                                            @else
                                            <td>{{@$record->class->class_name}}</td>
                                            <td> {{@$record->section->section_name}}</td>
                                            @endif
                                            <td>{{@$record->student->admission_no}}</td>
                                            <td>{{@$record->student->full_name}}</td>
                                            <td>{{@$record->student->mobile}}</td>
                                            <td>{{@$record->student->parents!=""?@$record->student->parents->guardians_name:""}}</td>
                                            <td>{{@$record->student->parents!=""?@$record->student->parents->guardians_relation:""}}</td>
                                            <td>{{@$record->student->parents!=""?@$record->student->parents->guardians_mobile:""}}</td>
                                            <td>{{@$record->student->parents!=""?@$record->student->parents->fathers_name:""}}</td>
                                            <td>{{@$record->student->parents!=""?@$record->student->parents->fathers_mobile:""}}</td>
                                            <td>{{@$record->student->parents!=""?@$record->student->parents->mothers_name:""}}</td>
                                            <td>{{@$record->student->parents!=""?@$record->student->parents->mothers_mobile:""}}</td>
                                        </tr>
                                        
                                        @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
    </div>
</section>



@endsection
@include('backEnd.partials.data_table_js')