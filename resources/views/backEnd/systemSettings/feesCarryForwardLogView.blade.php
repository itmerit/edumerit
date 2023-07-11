@extends('backEnd.master')
    @section('title') 
        @lang('fees.fees_carry_forward_log')
    @endsection
@section('mainContent')
@php  $setting = generalSetting(); 
if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }
else{ $currency = '$'; } 
@endphp

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.fees_carry_forward_log')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                @if (request()->is('fees-carry-forward-log-view-fees-collection'))
                    <a href="#">@lang('fees.fees_collection')</a>
                    <input type="hidden" id="feesType" value="installment">
                @else
                    <a href="#">@lang('fees.fees')</a>
                    <input type="hidden" id="feesType" value="fees">
                @endif
                <a href="#">@lang('fees.fees_carry_forward_log')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
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
                        
                    <div class="white-box">
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-4 mt-30-md md_mb_20">
                                <label class="primary_input_label" for="">{{ __('common.class') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')*</option>
                                    @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @error('class')
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="col-lg-4 mt-30-md md_mb_20" id="select_section_div">
                                <label class="primary_input_label" for="">{{ __('common.section') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                @error('section')
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="col-lg-4 mt-30-md md_mb_20" id="select_student_div">
                                <label class="primary_input_label" for="">{{ __('common.student') }}<span class="text-danger"></span></label>
                                <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="select_student" name="student">
                                    <option data-display="@lang('reports.select_student')" value="">@lang('reports.select_student')</option>
                                </select>
                                <div class="pull-right loader loader_style" id="select_student_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                            </div>

                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg searchLog">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('fees.previous_Session_Balance_Fees')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table data-table-for-log Crm_table_active3 no-footer dtr-inline collapsed" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('student.name')</th>
                                            <th>@lang('common.note')</th>
                                            <th>@lang('accounts.amount')</th>
                                            <th>@lang('common.date')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')

@push('script')
    <script>
        var carryForwardLog = '';
        $(document).ready(function() {
            $(document).on('click', '.searchLog', function(){
                if($('#select_class').val() == ''){
                    setTimeout(function () {
                        toastr.warning('Class Can Not Be Blank', 'Warning',{timeOut: 2000});
                    }, 500);
                    return ;
                }
                if($('#select_section').val() == ''){
                    setTimeout(function () {
                        toastr.warning('Section Can Not Be Blank', 'Warning',{timeOut: 2000});
                    }, 500);
                    return ;
                }
                carryForwardLog.clearPipeline();
                carryForwardLog.ajax.reload();
            })

            var columnData = [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'student_id', name: 'studentRecord.studentDetail.full_name' },
                { data: 'note', name: 'note' },
                { data: 'amount', name: 'amount' },
                { data: 'date', name: 'date' }
            ]

            
            carryForwardLog = $('.data-table-for-log').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{url('fees-carry-forward-log-search')}}",
                    data: function(d){
                        d.fees_type = $('#feesType').val(),
                        d.class_id = $('#select_class').val(),
                        d.section_id = $('#select_section').val(),
                        d.student_id = $('#select_student').val()
                    },
                    pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                } ),
                columns: columnData,
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