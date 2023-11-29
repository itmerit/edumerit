@extends('backEnd.master')

@section('title')
    {{ @$pt }}
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ @$pt }}</h1>
                <div class="bc-pages">
                    <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('student.student_information')</a>
                    <a href="#">{{ @$pt }}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor full_wide_table">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table data-table Crm_table_active3" cellspacing="0" width="100%">
                                    <thead>

                                        <tr>
                                            <th>@lang('student.admission_no')</th>
                                            <th>@lang('student.roll_no')</th>
                                            <th>@lang('student.name')</th>
                                            @if (generalSetting()->with_guardian)
                                                <th>@lang('student.father_name')</th>
                                            @endif
                                            <th>@lang('common.date_of_birth')</th>
                                            <th>@lang('common.gender')</th>
                                            <th>@lang('common.type')</th>
                                            <th>@lang('common.phone')</th>
                                            <th>@lang('common.actions')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{-- @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->admission_no }}</td>
                                                <td>{{ $student->roll_no }}</td>
                                                <td>{{ $student->first_name . ' ' . $student->last_name }}</td>
                                                <td>{{ $student->class != '' ? $student->class->class_name : '' }}</td>
                                                @if (generalSetting()->with_guardian)
                                                    <td>{{ $student->parents != '' ? $student->parents->fathers_name : '' }}</td>
                                                @endif
                                                <td data-sort="{{ strtotime($student->date_of_birth) }}">
                                                    {{ $student->date_of_birth != '' ? dateConvert($student->date_of_birth) : '' }}
                                                </td>
                                                <td>{{ $student->gender != '' ? $student->gender->base_setup_name : '' }}</td>
                                                <td>{{ $student->category != '' ? $student->category->category_name : '' }}</td>
                                                <td>{{ $student->mobile }}</td>
                                                <td>
                                                    @php
                                                        $routeList = [
                                                            '<a class="dropdown-item" href="'.route('student_view', [$student->id]).'">'.__('common.view').'</a>',

                                                            '<a class="dropdown-item" href="' . route('student.assign-class', [$student->id]) . '"> <i class="fa-solid fa-rotate"></i>' . __('student.assign_class') . '</a>', 

                                                            userPermission('disable_student_delete') ? '<a onclick="deleteId(' . $student->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="' . $student->id . '">' . __('common.delete') . '</a>' : null
                                                        ];
                                                    @endphp
                                                    <x-drop-down-action-component :routeList="$routeList" />
                                                </td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade admin-query" id="deleteStudentModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation Required</h4>
                    {{-- <h4 class="modal-title">@lang('student.delete') @lang('student.student')</h4> --}}
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        {{-- <h4>@lang('student.are_you_sure_to_delete')</h4> --}}
                        <h4 class="text-danger">You are going to remove
                            {{ @$student->first_name . ' ' . @$student->last_name }}. Removed data CANNOT be restored! Are you
                            ABSOLUTELY Sure!</h4>
                        {{-- <div class="alert alert-warning">@lang('student.student_delete_note')</div> --}}
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        {{ Form::open(['route' => 'disable_student_delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="" id="student_delete_i"> {{-- using js in main.js --}}
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')

@push('script')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{route('unassign-student-list-datatable')}}",
                    data: {
                    },
                    pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                } ),
                columns: [
                    {data: 'admission_no', name: 'admission_no'},
                    {data: 'roll_no', name: 'roll_no'},                     
                    {data: 'full_name', name: 'full_name'}, 
                     
                    @if(!moduleStatusCheck('University') && generalSetting()->with_guardian)
                     {data: 'parents.fathers_name', name: 'parents.fathers_name'},
                    @endif
                    {data: 'dob', name: 'dob'},
                    
                    {data: 'gender.base_setup_name', name: 'gender.base_setup_name'},
                    {data: 'category.category_name', name: 'category.category_name'},
                    {data: 'mobile', name: 'sm_students.mobile'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'first_name', name: 'first_name', visible : false},
                    {data: 'last_name', name: 'last_name', visible : false},
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
                                font: 'DejaVuSans'
                            }
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
@endpush