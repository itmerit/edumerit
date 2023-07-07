@extends('backEnd.master')
@section('title')
@lang('leave.leave_define')
@endsection
@section('mainContent')
@if(isset($leave_define))
    @if( is_null($staff) ) 
        <style>
            #selectStaffsDiv{
                display: none;
            }
            .forStudentWrapper{
                display:block;
            }
        </style>
    @endif
    @if( is_null($student)) 
        <style>
            #selectStaffsDiv{
                display: block;
            }
            .forStudentWrapper{
                display:none;
            }
        </style>
    @endif
@else 
<style type="text/css">
    #selectStaffsDiv, .forStudentWrapper{
        display: none;
    }
</style>
@endif 
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('leave.leave_define')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('leave.leave')</a>
                <a href="#">@lang('leave.leave_define')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($leave_define))
            @if(userPermission("leave-define-store"))  
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('leave-define')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
                    </div>
                </div>
            @endif
        @endif
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($leave_define))
                                    @lang('leave.edit_leave_define')
                                @else
                                    @lang('leave.add_leave_define')
                                @endif
                                  
                            </h3>
                        </div>
                            @if(isset($leave_define))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('leave-define-update',$leave_define->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                            @else
                                @if(userPermission("leave-define-store"))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'leave-define',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @endif
                            @endif
                            <input type="hidden" name="id" value="{{isset($leave_define)? $leave_define->id:''}}">
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12 mb-15">
                                        <label class="primary_input_label" for="">@lang('hr.role') <span class="text-danger"> *</span> </label>
                                        <select class="primary_select  form-control{{ $errors->has('member_type') ? ' is-invalid' : '' }}" name="member_type" id="member_type">
                                            <option data-display=" @lang('leave.select_role') *" value="">@lang('leave.select_role') *</option>
                                                @if(isset($leave_define))
                                                    @if(! is_null($student)) 
                                                        <option value="{{@$student->student->role_id}}" selected >{{@$student->student->roles->name}}</option>
                                                    @endif

                                                    @if(! is_null($staff)) 
                                                        <option value="{{@$staff->role_id}}" selected>{{@$staff->roles->name}}</option>
                                                    @endif
                                                @else 
                                                    @foreach($roles as $value)
                                                        <option value="{{$value->id}}" >{{$value->name}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                        @if ($errors->has('member_type'))
                                            <span class="text-danger" >
                                                {{ $errors->first('member_type') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="forStudentWrapper col-lg-12">
                                        @if(moduleStatusCheck('University'))
                                            @includeIf('university::common.session_faculty_depart_academic_semester_level',['required' => ['USN','UF', 'UD', 'UA', 'US', 'USL'] , 'hide' => ['USUB'],'row' => 1, 'div' => 'col-lg-12', 'mt' =>'mt-0'])
                                        @else 
                                            <div class="row">
                                                <div class="col-lg-12 mb-15">
                                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="class_library_member" name="class">
                                                        <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                                        @if(! isset($leave_define))
                                                            @foreach($classes as $class)
                                                                <option value="{{$class->id}}"  {{( old("class") == $class->id ? "selected":"")}}>{{$class->class_name}}</option>    
                                                            @endforeach
                                                        @else
                                                            <option value="{{@$student->class_id}}"  selected>{{@$student->class->class_name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 mb-15" id="select_section__member_div">
                                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section_member" name="section">
                                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                                        @if(isset($leave_define))
                                                            <option value="{{@$student->user_id}}"  selected >{{@$student->section->section_name}}</option>
                                                        @endif
                                                    </select>
                                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mb-15" id="select_student_div">
                                                    <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="select_student" name="student">
                                                        <option data-display="@lang('common.select_student') " value="">@lang('common.select_student')</option>
                                                        @if(isset($leave_define))
                                                            <option value="{{@$student->student->user_id}}"  selected >{{@$student->student->full_name}}</option>
                                                        @endif
                                                    </select>
                                                    <div class="pull-right loader loader_style" id="select_student_loader">
                                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif     
                                    </div>

                                    <div class="col-lg-12 mb-15" id="selectStaffsDiv">
                                        <label class="primary_input_label" for="">@lang('hr.staff') <span></span> </label>
                                        <select class="primary_select  form-control{{ $errors->has('staff_id') ? ' is-invalid' : '' }}" name="staff" id="selectStaffs">
                                            <option data-display="@lang('common.name') " value="">@lang('common.name') </option>
                                            @if(! isset($leave_define))
                                                @foreach($roles as $value)
                                                    <option value="{{$value->id}}" >{{$value->full_name}}</option>
                                                @endforeach
                                            @else
                                                <option value="{{@$staff->user_id}}" selected >{{@$staff->full_name}}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">@lang('leave.leave_type') <span class="text-danger"> *</span> </label>
                                        <select class="primary_select  form-control{{ $errors->has('leave_type') ? ' is-invalid' : '' }}" name="leave_type">
                                            <option data-display="@lang('leave.leave_type')  *" value="">@lang('leave.leave_type') *</option>
                                            @foreach($leave_types as $leave_type)
                                                <option value="{{$leave_type->id}}" {{isset($leave_define)? ($leave_define->type_id == $leave_type->id? 'selected':''):''}}>{{$leave_type->type}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('leave_type'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('leave_type') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('leave.days') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('days') ? ' is-invalid' : '' }}" type="text" name="days" autocomplete="off" value="{{isset($leave_define)? $leave_define->days:''}}">
                                            
                                            
                                            @if ($errors->has('days'))
                                            <span class="text-danger" >
                                                {{ $errors->first('days') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                 @php
                                  $tooltip = "";
                                    if(userPermission("leave-define-store") || userPermission("leave-define-edit") ){
                                            $tooltip = "";
                                        }else{
                                            $tooltip = "You have no permission to add";
                                        }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($editData))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('leave.leave_define_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                        <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('leave.user')</th>
                                    <th>@lang('leave.role')</th>
                                    <th>@lang('leave.leave_type')</th>
                                    <th>@lang('leave.days')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


  <!-- MOdal Here  -->
<div class="modal fade admin-query" id="deleteLeaveDefineModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('leave.delete_leave_define')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h2>@lang('common.are_you_sure_to_delete')</h2>                        
                </div>
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                    {{ Form::open(['route' => array('leave-define-delete'), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="id" id="showId">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add Days Modal  -->
<div class="modal fade admin-query" id="addLeaveDayModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('leave.update_leave_define') </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {{ Form::open(['route' => 'leave-define-updateLeave', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <div class="form-group">
                    <input type="hidden" name="id" id="showId">
                    <div class="row mt-15">
                        <div class="col-lg-12">
                            <div class="primary_input">
                                <input class="primary_input_field form-control has-content" type="text" name="days" autocomplete="off" id="showDays">
                                <label class="primary_input_label" for="">@lang('leave.days') <span class="text-danger"> *</span> </label>
                                
                                @if ($errors->has('days'))
                                <span class="text-danger" >
                                    {{ $errors->first('days') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.close')</button>
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.update')</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@include('backEnd.partials.data_table_js')
@push('script')
    <script>
        function deleteLeaveDefine(id){
            var modal = $('#deleteLeaveDefineModal');
            modal.find('#showId').val(id)
            modal.modal('show');

        }

        function addLeaveDay(id){ 
            var modal = $('#addLeaveDayModal');
            var total_days = $('.reason'+ id).data('total_days');
            modal.find('#showId').val(id);
            modal.find('#showDays').val(total_days);
            modal.modal('show');
        }
        
    </script>
@endpush


@section('script')  
@include('backEnd.partials.server_side_datatable')

<script>
//
// DataTables initialisation
//
$(document).ready(function() {
   $('.data-table').DataTable({
                 processing: true,
                 serverSide: true,
                 "ajax": $.fn.dataTable.pipeline( {
                       url: "{{url('leave-define-ajax')}}",
                       data: { 
                            
                        },
                       pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                       
                   } ),
                   columns: [
                       {data: 'user.full_name', name: 'user.full_name'},
                       {data: 'role.name', name: 'role.name'},
                       {data: 'leave_type.type', name: 'leave_type.type'},
                       {data: 'days', name: 'days'},
                       {data: 'action', name: 'action', orderable: true, searchable: true},
                       
                    ],
                    bLengthChange: false,
                    bDestroy: true,
                    language: {
                        search: "<i class='ti-search'></i>",
                        searchPlaceholder: window.jsLang('quick_search'),
                        paginate: {
                            next: "<i class='ti-arrow-right'></i>",
                            previous: "<i class='ti-arrow-left'></i>",
                        },
                    },
                    dom: "Bfrtip",
                    buttons: [{
                        extend: "copyHtml5",
                        text: '<i class="fa fa-files-o"></i>',
                        title: $("#logo_title").val(),
                        titleAttr: window.jsLang('copy_table'),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "excelHtml5",
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: window.jsLang('export_to_excel'),
                        title: $("#logo_title").val(),
                        margin: [10, 10, 10, 0],
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "csvHtml5",
                        text: '<i class="fa fa-file-text-o"></i>',
                        titleAttr: window.jsLang('export_to_csv'),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        title: $("#logo_title").val(),
                        titleAttr: window.jsLang('export_to_pdf'),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                        orientation: "landscape",
                        pageSize: "A4",
                        margin: [0, 0, 0, 12],
                        alignment: "center",
                        header: true,
                        customize: function(doc) {
                            doc.content[1].margin = [100, 0, 100, 0]; //left, top, right, bottom
                            doc.content.splice(1, 0, {
                                margin: [0, 0, 0, 12],
                                alignment: "center",
                                image: "data:image/png;base64," + $("#logo_img").val(),
                            });
                            doc.defaultStyle = {
                                font: "DejaVuSans",
                            };
                        },
                    },
                    {
                        extend: "print",
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: window.jsLang('print'),
                        title: $("#logo_title").val(),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "colvis",
                        text: '<i class="fa fa-columns"></i>',
                        postfixButtons: ["colvisRestore"],
                    },
                ],
                columnDefs: [{
                    visible: false,
                }, ],
                responsive: true,
            });
        } );
        </script>
@endsection
