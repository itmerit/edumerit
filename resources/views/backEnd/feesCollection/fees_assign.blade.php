@extends('backEnd.master')
@section('title')
    @lang('fees.fees_collection')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('fees.fees_assign')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('fees.fees_collection')</a>
                    <a href="{{ route('fees_assign', [$fees_group_id]) }}">@lang('fees.fees_assign')</a>
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-assign-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <input type="hidden" name="fees_group_id" id="fees_group_id" value="{{ @$fees_group_id }}">
                            @if (moduleStatusCheck('University'))
                                @includeIf(
                                    'university::common.session_faculty_depart_academic_semester_level',
                                    ['hide' => ['USUB']]
                                )
                            @else
                                <div class="col-lg-3 mt-30-md">
                                    <select
                                        class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                        id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}"
                                                {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                                                {{ @$class->class_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                        <span class="text-danger" role="alert">
                                            {{ $errors->first('class') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md" id="select_section_div">
                                    <select
                                        class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                        id="select_section" name="section">
                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                            alt="loader">
                                    </div>
                                    @if ($errors->has('section'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('section') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="col-lg-3 mt-30-md">
                                    <select
                                        class="primary_select  form-control{{ $errors->has('category') ? ' is-invalid' : '' }}"
                                        name="category">
                                        <option data-display="@lang('fees.select_category')" value="">@lang('fees.select_category')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ isset($category_id) ? ($category_id == $category->id ? 'selected' : '') : '' }}>
                                                {{ @$category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('category') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md">
                                    <select
                                        class="primary_select  form-control{{ $errors->has('group') ? ' is-invalid' : '' }}"
                                        name="group">
                                        <option data-display="@lang('fees.select_group')" value="">@lang('fees.select_group')</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ isset($group_id) ? ($group_id == $group->id ? 'selected' : '') : '' }}>
                                                {{ $group->group }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('group'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('group') }}
                                        </span>
                                    @endif
                                </div>
                            @endif

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
            @if (isset($students))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'btn-assign-fees-group', 'enctype' => 'multipart/form-data']) }}
                <input type="hidden" name="fees_group_id" id="fees_group_id" value="{{ @$fees_group_id }}">
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row mb-30">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('fees.assign_fees_group')</h3>
                                </div>
                            </div>
                        </div>
                        <!-- </div> -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div id="table_id_table_wrapper" class="dataTables_wrapper">
                                    <table id="table_id_table" class="table dataTable" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                @php $i = 0; @endphp
                                                @foreach ($fees_assign_groups as $fees_assign_group)
                                                    @php $i++; @endphp
                                                    @if ($i == 1)
                                            <tr>
                                                <th>{{ @$fees_assign_group->feesGroups->name }}</th>
                                                <th>@lang('fees.amount') </th>
                                            </tr>
            @endif
            @endforeach
            </tr>
            </thead>
            <tbody>
                @foreach ($fees_assign_groups as $fees_assign_group)
                    <tr>
                        <td style="padding: 20px 10px 10px 17px !important;">
                            {{ @$fees_assign_group->feesTypes != '' ? @$fees_assign_group->feesTypes->name : '' }}
                        </td>
                        <td style="padding: 20px 10px 10px 17px !important;">{{ @$fees_assign_group->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>

        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 search_hide_md">
                    <a class="primary-btn fix-gr-bg mb-0 submit" data-toggle="modal" data-target="#assignAllStudentsModal"
                        href="">@lang('fees.assign_all_students')</a>
                    <a class="primary-btn fix-gr-bg mb-0 submit" data-toggle="modal" data-target="#unassignAllStudentsModal"
                        href="">@lang('fees.unassign_all_students')</a>
                    <x-table>
                        <table id="table_id" class="table data-table school-table-style" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%">
                                        <input type="checkbox" id="checkAll" class="common-checkbox" name="checkAll"
                                            @php
if(count($students) > 0){
                                                                if(count($students) == count($pre_assigned)){
                                                                    echo 'checked';
                                                                }

                                                            } @endphp>
                                        <label for="checkAll" class="mb-0">@lang('fees.all')</label>
                                    </th>
                                    <th width="20%">@lang('student.student_name')</th>
                                    <th width="15%">@lang('student.admission_no')</th>
                                    <th width="15%">@lang('common.class')</th>
                                    <th width="20%">@lang('student.father_name')</th>
                                    <th width="10%">@lang('fees.category')</th>
                                    <th width="10%">@lang('common.gender')</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                            @if ($student_count > 0)
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center">
                                            <button type="submit" class="primary-btn fix-gr-bg mb-0 submit"
                                                id="btn-assign-fees-group"
                                                data-loading-text="<i class='fas fa-spinner'></i> Processing Data">
                                                <span class="ti-save pr"></span>
                                                @lang('fees.save_fees')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </x-table>
                </div>
            </div>
        </div>

        </div>
        </div>
        </div>
        {{ Form::close() }}
        @endif
        </div>
    </section>

    {{-- start assign all students modal --}}
    <div class="modal fade admin-query" id="assignAllStudentsModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('fees.assign_fees_group_to_all')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('fees.are_you_sure_you_want_to_assign_this_fees_group_to_all_the_students_this_list')</h4>
                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('admin.cancel')</button>
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'btn-assign-fees-group', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="fees_assign_class"
                            value="{{ isset($requestData) ? $requestData['class'] : '' }}">
                        <input type="hidden" name="fees_assign_section"
                            value="{{ isset($requestData) ? $requestData['section'] : '' }}">
                        <input type="hidden" name="fees_assign_category"
                            value="{{ isset($requestData) ? $requestData['category'] : '' }}">
                        <input type="hidden" name="fees_assign_group"
                            value="{{ isset($requestData) ? $requestData['group'] : '' }}">
                        <input type="hidden" name="fees_group_id" id="fees_group_id" value="{{ @$fees_group_id }}">
                        <input type="hidden" name="fees_assign_all" value="1">
                        <button type="submit" class="text-light primary-btn fix-gr-bg">@lang('fees.assign')</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end assign all students modal --}}

    {{-- start unassign all students modal --}}
    <div class="modal fade admin-query" id="unassignAllStudentsModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('fees.unassign_fees_group_to_all')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('fees.are_you_sure_you_want_to_unassign_this_fees_group_from_all_the_students_this_list')</h4>
                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('admin.cancel')</button>
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'unssign-all-fees-group', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="fees_assign_class"
                            value="{{ isset($requestData) ? $requestData['class'] : '' }}">
                        <input type="hidden" name="fees_assign_section"
                            value="{{ isset($requestData) ? $requestData['section'] : '' }}">
                        <input type="hidden" name="fees_assign_category"
                            value="{{ isset($requestData) ? $requestData['category'] : '' }}">
                        <input type="hidden" name="fees_assign_group"
                            value="{{ isset($requestData) ? $requestData['group'] : '' }}">
                        <input type="hidden" name="fees_group_id" id="fees_group_id" value="{{ @$fees_group_id }}">
                        <button type="submit" class="text-light primary-btn fix-gr-bg">@lang('fees.unassign')</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end unassign all students modal --}}

@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')

@push('script')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                // bLengthChange: true,
                // "lengthChange": true,
                // "lengthMenu": [
                //     [10, 25, 50, 100, -1],
                //     [10, 25, 50, 100, "All"]
                // ],
                // lengthChange: true,
                // lengthMenu: [ 10, 25, 50, 100 ],
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline({
                    url: "{{ url('fees-assign-datatable') }}",
                    data: {
                        class: '{{ @$requestData['class'] }}',
                        section: '{{ @$requestData['section'] }}',
                        category: '{{ @$requestData['category'] }}',
                        group: '{{ @$requestData['group'] }}',
                        fees_group_id: '{{ @$requestData['fees_group_id'] }}',
                    },
                    pages: "{{ generalSetting()->ss_page_load }}" // number of pages to cache
                }),
                columns: [{
                        data: 'input_checkbox',
                        name: 'input_checkbox',
                        sortable: false
                    },
                    {
                        data: 'full_name',
                        name: 'student_detail.full_name'
                    },
                    {
                        data: 'admission_no',
                        name: 'student_detail.admission_no'
                    },
                    {
                        data: 'class_section_name',
                        name: 'class.class_name'
                    },
                    {
                        data: 'parents_name',
                        name: 'student_detail.parents.fathers_name'
                    },
                    {
                        data: 'category_name',
                        name: 'student_detail.category.category_name'
                    },
                    {
                        data: 'base_setup_name',
                        name: 'student_detail.gender.base_setup_name'
                    },
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
        });
    </script>
@endpush
