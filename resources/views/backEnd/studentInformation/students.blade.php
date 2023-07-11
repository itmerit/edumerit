
@extends('backEnd.master')
@section('title') 
@lang('common.student_list')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.manage_student')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('student.student_information')</a>
                <a href="#">@lang('common.student_list')</a>
            </div>
        </div>
    </div>
</section>


{{-- disable student  --}}
    <div class="modal fade admin-query" id="deleteStudentModal" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('student.disable_student')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    
                    <div class="text-center">
                        <h4>@lang('student.are_you_sure_to_disable')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        {{ Form::open(['route' => 'student-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="id" value="{{@$student->id}}" id="student_delete_i">  {{-- using js in main.js --}}
                            <button class="primary-btn fix-gr-bg" type="submit">@lang('common.disable')</button>
                        {{ Form::close() }}
                    </div>

                </div>

            </div>
        </div>
    </div> 

    <table id="table_id" class="table" cellspacing="0" width="100%">
        {{-- <table id="table_id" class="table table-bordered data-table"> --}}
            <thead>
                <tr>
                    <th>@lang('student.admission_no')</th>
                    <th>@lang('student.name')</th>
                    @if(generalSetting()->multiple_roll==0)
                    <th>@lang('student.id_number')</th>
                    @else
                    @if(!moduleStatusCheck('University'))
                     <th>@lang('student.father_name')</th>
                     @endif
                    @endif
                    <th>@lang('student.date_of_birth')</th>
                    @if(moduleStatusCheck('University'))
                    <th>@lang('student.fac_dept')</th>
                    @else
                    <th>@lang('student.class_sec')</th>
                    @endif
                    <th>@lang('common.gender')</th>
                    <th>@lang('common.type')</th>
                    <th>@lang('common.phone')</th>
                    <th>@lang('common.actions')</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @endsection
        
@include('backEnd.partials.data_table_js')

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
                        url: "{{ route('student_list_datatable') }}",
                        pages: 2 // number of pages to cache
                        
                    } ),
                    columns: [
                            {data: 'admission_no', name: 'admission_no'},
                            {data: 'full_name', name: 'full_name'},
                            @if(generalSetting()->multiple_roll==0)
                            {data: 'roll_no', name: 'roll_no'},
                            @else
                                @if(!moduleStatusCheck('University'))
                                {data: 'parents.fathers_name', name: 'parents.fathers_name'},
                                @endif
                            @endif

                            {data: 'dob', name: 'dob'},
                            @if(moduleStatusCheck('University'))
                            {data: 'fac_dept', name: 'fac_dept'},
                            @else
                            {data: 'class_sec', name: 'class_sec'},
                            @endif
                            {data: 'gender.base_setup_name', name: 'gender.base_setup_name'},
                            {data: 'category.category_name', name: 'category.category_name'},
                            {data: 'mobile', name: 'sm_students.mobile', orderable: false, searchable: false},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                            {data: 'first_name', name: 'first_name', hideable: true, searchable: false},
                        ],
                    bLengthChange: false,
                    bDestroy: true,
                    language: {
                    search: "<i class='ti-search'></i>",
                    searchPlaceholder: "Quick Search",
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
                        titleAttr: "Copy",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "excelHtml5",
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: "Excel",
                        title: $("#logo_title").val(),
                        margin: [10, 10, 10, 0],
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "csvHtml5",
                        text: '<i class="fa fa-file-text-o"></i>',
                        titleAttr: "CSV",
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        title: $("#logo_title").val(),
                        titleAttr: "PDF",
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
                        titleAttr: "Print",
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
