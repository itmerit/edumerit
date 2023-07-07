@extends('backEnd.master')
@section('title')
@lang('rolepermission::role.login_permission')
@endsection
@section('mainContent')
<style>
    table.dataTable thead .sorting_asc:after {
        top: 10px;
        left: 5px;
    }

    table.dataTable thead .sorting_asc {
    vertical-align: text-top;
}

    table.dataTable thead .sorting:after {
        top: 10px;
        left: 4px;
    }

</style>
<link rel="stylesheet" href="{{asset('public/backEnd/css/login_access_control.css')}}" />
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('rolepermission::role.login_permission') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('rolepermission::role.role_permission')</a>
                <a href="#">@lang('rolepermission::role.login_permission')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'login-access-control', 'enctype' => 'multipart/form-data', 'method' => 'POST']) }}
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12 mb-20">
                                        <select
                                            class="primary_select  form-control{{ $errors->has('role') ? ' is-invalid' : '' }}"
                                            name="role" id="member_type">
                                            <option data-display=" @lang('common.select_role') *" value="">
                                                @lang('common.select_role') *</option>
                                            @foreach($roles as $value)
                                            <option value="{{@$value->id}}">{{@$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="forStudentWrapper col-lg-12">
                                        <div class="row">
                                            @if(moduleStatusCheck('University'))
                                            @includeIf('university::common.session_faculty_depart_academic_semester_level',['hide'=>['USUB','USN'],
                                            'slb_mt'=>'mt-25', 'se_mt'=>'mt-0', 'mt'=>'mt-25'])
                                            @else
                                            <div class="col-lg-6 mb-30">
                                                <label class="primary_input_label" for="">{{ __('common.class')}} <span
                                                        class="text-danger"> *</span></label>
                                                <select
                                                    class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                                    id="select_class" name="class">
                                                    <option data-display="@lang('common.select_class') *" value="">
                                                        @lang('common.select_class')*</option>
                                                    @foreach($classes as $class)
                                                    <option value="{{@$class->id}}"
                                                        {{( old("class") == @$class->id ? "selected":"")}}>
                                                        {{@$class->class_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6 mb-30" id="select_section_div">
                                                <label class="primary_input_label" for="">{{ __('common.section')}}
                                                    <span class="text-danger"> *</span></label>
                                                <select
                                                    class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                                    id="select_section" name="section">
                                                    <option data-display="@lang('common.select_section')" value="">
                                                        @lang('common.select_section') *</option>
                                                </select>
                                            </div>
                                            @endif

                                        </div>
                                    </div>



                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>

                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        @if(isset($students))
        <div class="row mt-60">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-3">@lang('common.student_list') ({{@$students->count()}})</h3>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <x-table>
                            <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed"
                                cellspacing="0" width="100%">

                                <thead>
                                    <tr>
                                        <th>@lang('student.admission') </th>
                                        <th>@lang('student.roll')</th>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('common.class')</th>

                                        <th>@lang('rolepermission::role.student_permission')</th>
                                        <th style="width: 200px;">@lang('rolepermission::role.student_password')</th>

                                        <th>@lang('rolepermission::role.parents_permission')</th>
                                        <th style="width: 200px;">@lang('rolepermission::role.parents_password')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($students as $student)
                                    <tr id="{{@$student->user_id}}">

                                        <td class="pl-3">
                                            <input type="hidden" id="id" value="{{@$student->user_id}}">
                                            <input type="hidden" id="role" value="{{@$role}}">
                                            {{@$student->admission_no}}
                                        </td>
                                        <td class="pl-3"> {{@$student->roll_no}}</td>
                                        <td class="pl-1">{{@$student->first_name.' '.@$student->last_name}} </td>
                                        <td class="pl-1">
                                            @foreach($student->studentRecords as $record)
                                            {{!empty(@$record->class)?@$record->class->class_name:''}}
                                            ({{!empty(@$record->section)?@$record->section->section_name:''}})
                                            @endforeach

                                        </td>
                                        <td>
                                            <input type="hidden" name="id" value="{{$student->user_id}}">
                                            <label class="switch_toggle">
                                                @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                <input type="checkbox" disabled id="ch{{@$student->user_id}}"
                                                    onclick="lol({{@$student->user_id}},{{@$role}})" class="switch-input11"
                                                    {{@$student->user->access_status == 0? '':'checked'}}>
                                                <span class="slider round" data-toggle="tooltip"
                                                    title="Disabled For Demo"></span>
                                                @else
                                                <input type="checkbox" id="ch{{@$student->user_id}}"
                                                    onclick="lol({{@$student->user_id}},{{@$role}})" class="switch-input11"
                                                    {{@$student->user->access_status == 0? '':'checked'}}>
                                                <span class="slider round"></span>
                                                @endif

                                            </label>
                                        </td>
                                        <td style="white-space: nowrap;">
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                            <input type="hidden" name="id" value="{{@$student->user_id}}">
                                            <div class="row mt-10">
                                                <div class="col-lg-6">
                                                    <div class="primary_input md_mb_20">
                                                        <input class="primary_input_field read-only-input" type="text"
                                                            name="new_password" required="true" minlength="6">
                                                        <label class="primary_input_label"
                                                            for="">@lang('common.password')</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                        title="Disabled For Demo ">
                                                        <button class="primary-btn small tr-bg icon-only mt-10"
                                                            style="pointer-events: none;" type="button"><span
                                                                class="ti-save"> </button>
                                                    </span>

                                                    @else

                                                    <button type="submit" class="primary-btn small tr-bg icon-only mt-10"
                                                        data-toggle="tooltip"
                                                        title="@lang('rolepermission::role.update_password')">
                                                        <span class="ti-save"></span>
                                                    </button>

                                                    @endif

                                                    <button data-toggle="tooltip" title="Reset Password as Default"
                                                        type="button" class="primary-btn small tr-bg icon-only mt-10"
                                                        onclick="changePassword({{@$student->user_id}},{{@$role}})">
                                                        <span class="ti-reload"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </td>
                                        <td>

                                            <input type="hidden" name="ParentID" value="{{@$student->parents->user_id}}"
                                                id="ParentID">

                                            <label class="switch_toggle">
                                                @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                <input type="checkbox" disabled class="parent-login-disable"
                                                    {{@$student->parents->parent_user->access_status == 0? '':'checked'}}>
                                                <span class="slider round" data-toggle="tooltip"
                                                    title="Disabled For Demo"></span>
                                                @else

                                                <input type="checkbox" class="parent-login-disable"
                                                    {{@$student->parents->parent_user->access_status == 0? '':'checked'}}>
                                                <span class="slider round"></span>

                                                @endif
                                            </label>

                                        </td>
                                        <td style="white-space: nowrap;">
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                            <input type="hidden" name="id" value="{{@$student->parents->user_id}}">
                                            <div class="row mt-10">
                                                <div class="col-lg-6">
                                                    <div class="primary_input md_mb_20">
                                                        <input class="primary_input_field read-only-input" type="text"
                                                            name="new_password" required="true" minlength="6">
                                                        <label class="primary_input_label"
                                                            for="">@lang('common.password')</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                        title="Disabled For Demo ">
                                                        <button class="primary-btn small tr-bg icon-only mt-10"
                                                            style="pointer-events: none;" type="button"><span
                                                                class="ti-save"> </button>
                                                    </span>

                                                    @else

                                                    <button type="submit" class="primary-btn small tr-bg icon-only mt-10"
                                                        data-toggle="tooltip"
                                                        title="@lang('rolepermission::role.update_password')">
                                                        <span class="ti-save"></span>
                                                    </button>

                                                    @endif
                                                    <button data-toggle="tooltip" title="Reset Password as Default"
                                                        type="button" class="primary-btn small tr-bg icon-only mt-10"
                                                        onclick="changePassword({{@$student->parents->user_id}},{{@$role}})">
                                                        <span class="ti-reload"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
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
        @endif

        @if(isset($staffs))
        <div class="row mt-60">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('hr.staff_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('hr.staff_no')</th>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('common.role')</th>
                                        <th>@lang('common.email')</th>
                                        <th>@lang('rolepermission::role.login_permission')</th>
                                        <th>@lang('common.password')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($staffs as $value)
                                    <tr id="{{@$value->user_id}}">
                                        <input type="hidden" id="id" value="{{@$value->user_id}}">
                                        <input type="hidden" id="role" value="{{@$role}}">
                                        <td class="pl-3">{{@$value->staff_no}}</td>
                                        <td>{{@$value->first_name}}&nbsp;{{@$value->last_name}}</td>
                                        <td>{{!empty(@$value->roles->name)?@$value->roles->name:''}}</td>
                                        <td>{{@$value->email}}</td>
                                        <td class="pl-3">
                                            @php
                                            if(@$value->staff_user->access_status == 0){
                                            $permission_id='login-access-control-on';
                                            }else{
                                            $permission_id='login-access-control-off';
                                            }
                                            @endphp
                                            @if(userPermission($permission_id))
                                            <label class="switch_toggle">
                                                @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                <input type="checkbox" disabled class="switch-input"
                                                    {{@$value->staff_user->access_status == 0? '':'checked'}}>
                                                <span class="slider round" data-toggle="tooltip"
                                                    title="Disabled For Demo"></span>
                                                @else
                                                <input type="checkbox" class="switch-input"
                                                    {{@$value->staff_user->access_status == 0? '':'checked'}}>
                                                <span class="slider round"></span>
                                                @endif
                                            </label>
                                            @endif
                                        </td>
                                        <td style="white-space: nowrap;">
                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'reset-student-password', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                            <input type="hidden" name="id" value="{{$value->user_id}}">
                                            <div class="row mt-10">
                                                <div class="col-lg-6">
                                                    <div class="primary_input md_mb_20">
                                                        <input class="primary_input_field read-only-input" type="text"
                                                            name="new_password" required="true" minlength="6">
                                                        <label class="primary_input_label"
                                                            for="">@lang('common.password')</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                        title="Disabled For Demo ">
                                                        <button class="primary-btn small tr-bg icon-only mt-10"
                                                            style="pointer-events: none;" type="button"><span
                                                                class="ti-save"> </button>
                                                    </span>

                                                    @else

                                                    <button type="submit" class="primary-btn small tr-bg icon-only mt-10"
                                                        data-toggle="tooltip"
                                                        title="@lang('rolepermission::role.update_password')">
                                                        <span class="ti-save"></span>
                                                    </button>

                                                    @endif

                                                    <button data-toggle="tooltip" title="Reset Password as Default"
                                                        type="button" class="primary-btn small tr-bg icon-only mt-10"
                                                        onclick="changePassword({{@$value->user_id}},{{@$role}})">
                                                        <span class="ti-reload"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
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
        @endif

        @if(isset($parents))
        <div class="row mt-60">


            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('common.parents_list')</h3>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed"
                                cellspacing="0" width="100%">
                                <thead>

                                    <tr>
                                        <th>@lang('student.guardian_phone') </th>
                                        <th>@lang('student.father_name') </th>
                                        <th>@lang('student.father_phone') </th>
                                        <th>@lang('student.mother_name') </th>
                                        <th>@lang('student.mother_phone') </th>
                                        <th>@lang('rolepermission::role.login_permission')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($parents as $parent)
                                    <tr id="{{@$parent->user_id}}">
                                        <input type="hidden" id="id" value="{{@$parent->user_id}}">
                                        <input type="hidden" id="role" value="{{@$role}}">
                                        <td>{{@$parent->guardians_mobile}}</td>
                                        <td>{{@$parent->fathers_name}}</td>
                                        <td>{{@$parent->fathers_mobile}}</td>
                                        <td>{{@$parent->mothers_name}}</td>
                                        <td>{{@$parent->mothers_mobile}}</td>
                                        <td>
                                            @php
                                            if(@$value->staff_user->access_status == 0){
                                            $permission_id=422;
                                            }else{
                                            $permission_id=423;
                                            }
                                            @endphp
                                            @if(userPermission($permission_id))
                                            <label class="switch_toggle">
                                                <input type="checkbox" class="switch-input"
                                                    {{@$parent->parent_user->access_status == 0? '':'checked'}}>
                                                <span class="slider round"></span>
                                            </label>
                                            @endif
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
        @endif
    </div>
    </div>
    </div>
    </div>
</section>


@endsection
@include('backEnd.partials.data_table_js')

@push('script')
<script>
    $( document ).ready( function () {
                $( '.data-table' ).DataTable( {
                            processing: true,
                            serverSide: true,
                            "ajax": $.fn.dataTable.pipeline( {
                                url: "{{url('student-list-datatable')}}",
                                data: {
                                    academic_year: $( '#academic_id' ).val(),
                                    class: $( '#class' ).val(),
                                    section: $( '#section' ).val(),
                                    roll_no: $( '#roll' ).val(),
                                    name: $( '#name' ).val(),
                                    un_session_id: $( '#un_session' ).val(),
                                    un_academic_id: $( '#un_academic' ).val(),
                                    un_faculty_id: $( '#un_faculty' ).val(),
                                    un_department_id: $( '#un_department' ).val(),
                                    un_semester_label_id: $( '#un_semester_label' ).val(),
                                    un_section_id: $( '#un_section' ).val(),
                                },
                                pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                            } ),
                            columns: [ {
                                    data: 'admission_no',
                                    name: 'admission_no'
                                },
                                {
                                    data: 'full_name',
                                    name: 'full_name'
                                },
                                @if( !moduleStatusCheck( 'University' ) && generalSetting() -> with_guardian ) {
                                    data: 'parents.fathers_name',
                                    name: 'parents.fathers_name'
                                },
                                @endif {
                                    data: 'dob',
                                    name: 'dob'
                                },
                                @if( moduleStatusCheck( 'University' ) ) {
                                    data: 'semester_label',
                                    name: 'semester_label'
                                },
                                {
                                    data: 'class_sec',
                                    name: 'class_sec'
                                },
                                @else {
                                    data: 'class_sec',
                                    name: 'class_sec'
                                },
                                @endif {
                                    data: 'gender.base_setup_name',
                                    name: 'gender.base_setup_name'
                                },
                                {
                                    data: 'category.category_name',
                                    name: 'category.category_name'
                                },
                                {
                                    data: 'mobile',
                                    name: 'sm_students.mobile'
                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'first_name',
                                    name: 'first_name',
                                    visible: false
                                },
                                {
                                    data: 'last_name',
                                    name: 'last_name',
                                    visible: false
                                },
                            ],
                            bLengthChange: false,
                            bDestroy: true,
                            language: {
                                search: "<i class='ti-search'></i>",
                                searchPlaceholder: window.jsLang( 'quick_search' ),
                                paginate: {
                                    next: "<i class='ti-arrow-right'></i>",
                                    previous: "<i class='ti-arrow-left'></i>",
                                },
                            },
                            dom: "Bfrtip",
                            buttons: [ {
                                        extend: "copyHtml5",
                                        text: '<i class="fa fa-files-o"></i>',
                                        title: $( "#logo_title" ).val(),
                                        titleAttr: window.jsLang( 'copy_table' ),
                                        exportOptions: {
                                            columns: ':visible:not(.not-export-col)'
                                        },
                                    },
                                    {
                                        extend: "excelHtml5",
                                        text: '<i class="fa fa-file-excel-o"></i>',
                                        titleAttr: window.jsLang( 'export_to_excel' ),
                                        title: $( "#logo_title" ).val(),
                                        margin: [ 10, 10, 10, 0 ],
                                        exportOptions: {
                                            columns: ':visible:not(.not-export-col)'
                                        },
                                    },
        {
                    extend: "csvHtml5",
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: window.jsLang( 'export_to_csv' ),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                {
                    extend: "pdfHtml5",
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    title: $( "#logo_title" ).val(),
                    titleAttr: window.jsLang( 'export_to_pdf' ),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                    orientation: "landscape",
                    pageSize: "A4",
                    margin: [ 0, 0, 0, 12 ],
                    alignment: "center",
                    header: true,
                    customize: function ( doc ) {
                        doc.content[ 1 ].margin = [ 100, 0, 100, 0 ]; //left, top, right, bottom
                        doc.content.splice( 1, 0, {
                            margin: [ 0, 0, 0, 12 ],
                            alignment: "center",
                            image: "data:image/png;base64," + $( "#logo_img" ).val(),
                        } );
                        doc.defaultStyle = {
                            font: 'DejaVuSans'
                        }
                    },
                },
                {
                    extend: "print",
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: window.jsLang( 'print' ),
                    title: $( "#logo_title" ).val(),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                {
                    extend: "colvis",
                    text: '<i class="fa fa-columns"></i>',
                    postfixButtons: [ "colvisRestore" ],
                },
            ],
            columnDefs: [ {
                visible: false,
            }, ],
            responsive: true,
        } );
    } );

</script>
@endpush
