@extends('backEnd.master')
@section('title')
@lang('transport.student_transport_report')
@endsection
@section('mainContent')
@php  @$setting = generalSetting();  if(!empty(@$setting->currency_symbol)){ @$currency = @$setting->currency_symbol; }else{ @$currency = '$'; }   @endphp 

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('transport.student_transport_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('transport.transport')</a>
                <a href="#">@lang('transport.student_transport_report')</a>
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
                   
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_transport_report', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                @if(moduleStatusCheck('University'))
                                @includeIf('university::common.session_faculty_depart_academic_semester_level',['required'=>['USN','UD','UA','US','USL','USEC'], 'hide' => ['USUB']])
                                @else 
                                <div class="col-lg-3 mt-30">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.class') }}
                                            <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')*</option>
                                        @foreach($classes as $class)
                                        <option value="{{@$class->id}}"  {{isset($class_id)? (@$class_id == @$class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('class') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30" id="select_section_div">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.section') }}
                                            <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                        @if(isset($class_id))
                                            @foreach ($class->classSection as $section)
                                            <option value="{{ $section->sectionName->id }}" {{ old('section')==$section->sectionName->id ? 'selected' : '' }} >
                                                {{ $section->sectionName->section_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('section'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('section') }}
                                        </span>
                                    @endif
                                </div>
                                @endif 
                                <div class="col-lg-3 mt-25">
                                    <label class="primary_input_label" for="">
                                        {{ __('transport.route') }}
                                            <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select form-control{{ $errors->has('route') ? ' is-invalid' : '' }}" name="route">
                                        <option data-display="@lang('transport.select_route') *" value="">@lang('transport.select_route') *</option>
                                        @foreach($routes as $route)
                                            <option value="{{$route->id}}"  {{isset($route_id)? (@$route_id == @$route->id? 'selected':''):''}}>{{@$route->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('route'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('route') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-25">
                                    <label class="primary_input_label" for="">
                                        {{ __('transport.vehicle') }}
                                            <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select form-control{{ $errors->has('vehicle') ? ' is-invalid' : '' }}" name="vehicle">
                                        <option data-display="@lang('transport.select_vehicle') *" value="">@lang('transport.select_vehicle') *</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{$vehicle->id}}"  {{isset($vechile_id)? (@$vechile_id == @$vehicle->id? 'selected':''):''}}>{{@$vehicle->vehicle_no}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('vehicle'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('vehicle') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
          
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('transport.student_transport_report')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                <thead>
                                   
                                    <tr>
                                        <th>Si</th>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('student.student_name')</th>
                                        <th>@lang('common.mobile')</th>
                                        <th>@lang('student.father_name')</th>
                                        <th>@lang('student.father_phone')</th>
                                        <th>@lang('transport.route_title')</th>
                                        <th>@lang('transport.vehicle_number')</th>
                                        <th>@lang('transport.driver_name')</th>
                                        <th>@lang('transport.driver_contact')</th>
                                        <th>@lang('transport.fare')({{generalSetting()->currency_symbol}})</th>
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
                           url: "{{route('studentTransportReportAjax')}}",
                           data: { 
                               
                            },
                           pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                           
                       } ),
                       columns: [
                           {data: 'DT_RowIndex', name: 'id'},
                            {data: 'admission_no', name: 'admission_no'},
                            {data: 'full_name', name: 'full_name'},
                           {data: 'mobile', name: 'mobile'},
                           {data: 'parents.fathers_name', name: 'parents.fathers_name'},
                           {data: 'parents.guardians_mobile', name: 'parents.guardians_mobile'},
                           {data: 'route.title', name: 'route.title'},
                           {data: 'vehicle.vehicle_no', name: 'vehicle.vehicle_no'},
                           {data: 'drivers.full_name', name: 'drivers.full_name'},
                           {data: 'drivers.mobile', name: 'drivers.mobile'},
                           {data: 'route.far', name: 'route.far', orderable: false, searchable: true},
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