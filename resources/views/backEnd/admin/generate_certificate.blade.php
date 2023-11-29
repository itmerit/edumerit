@extends('backEnd.master')
@section('title')
    @lang('admin.generate_certificate')
@endsection

@section('mainContent')
    <style>
        table.dataTable thead .sorting_asc::after {
            left: 38px !important;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('admin.generate_certificate')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('admin.admin_section')</a>
                    <a href="#">@lang('admin.generate_certificate')</a>
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
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'generate_certificate', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            @if (moduleStatusCheck('University'))
                                @includeIf(
                                    'university::common.session_faculty_depart_academic_semester_level',
                                    [
                                        'hide' => ['USUB', 'UA', 'US', 'USL', 'USEC'],
                                        'required' => ['US'],
                                        'div' => 'col-lg-3',
                                    ]
                                )
                            @else
                                <div class="col-lg-4 mt-30-md">
                                    <label class="primary_input_label" for="">@lang('common.class') <span
                                            class="text-danger"> *</span></label>
                                    <select
                                        class="primary_select  form-control {{ @$errors->has('class') ? ' is-invalid' : '' }}"
                                        id="select_class" name="class">
                                        <option data-display="@lang('common.select_class')*" value="">
                                            @lang('common.select_class') *</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ @$class->id }}"
                                                {{ isset($class_id) ? ($class_id == $class->id ? 'selected' : '') : '' }}>
                                                {{ @$class->class_name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('class') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <label class="primary_input_label" for="">@lang('common.section') <span
                                            class="text-danger"> </span></label>
                                    <select class="primary_select " id="select_section" name="section">
                                        <option data-display="@lang('common.select_section')" value="">
                                            @lang('common.select_section')</option>
                                    </select>
                                    @if ($errors->has('section'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('section') }}
                                        </span>
                                    @endif
                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                        <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                            alt="loader">
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    <label class="primary_input_label" for="">@lang('admin.certificate') <span
                                            class="text-danger"> *</span></label>
                                    <select
                                        class="primary_select  form-control {{ @$errors->has('certificate') ? ' is-invalid' : '' }}"
                                        name="certificate" id="certificate">
                                        <option data-display="@lang('admin.select_certificate') *" value="">
                                            @lang('admin.select_certificate') *</option>
                                        @foreach ($certificates as $certificate)
                                            <option value="{{ @$certificate->id }}"
                                                {{ isset($certificate_id) ? ($certificate_id == $certificate->id ? 'selected' : '') : (old('certificate') == $certificate->id ? 'selected' : '') }}>
                                                {{ @$certificate->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('certificate'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('certificate') }}
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
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </section>


    @if (isset($students))
        <section class="admin-visitor-area up_admin_visitor">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-2 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('student.student_list')</h3>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <a href="javascript:;" id="genearte-certificate-print-button"
                                    class="primary-btn small fix-gr-bg">
                                    @lang('admin.generate')
                                </a>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="10%">
                                                    <input type="checkbox" id="checkAll"
                                                        class="common-checkbox generate-certificate-print-all"
                                                        name="checkAll"
                                                        value="">
                                                    <label for="checkAll">@lang('admin.all')</label>
                                                </th>
                                                <th>@lang('admin.admission_no')</th>
                                                <th>@lang('admin.name')</th>
                                                <th>@lang('admin.class_Sec')</th>
                                                <th>@lang('common.father_name')</th>
                                                <th>@lang('admin.date_of_birth')</th>
                                                <th>@lang('admin.gender')</th>
                                                <th>@lang('admin.mobile')</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($students as $record)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" id="student.{{ @$record->id }}"
                                                            class="common-checkbox generate-certificate-print"
                                                            name="student_checked[]" value="{{ @$record->id }}">
                                                        <label for="student.{{ @$record->id }}"></label>
                                                    </td>
                                                    <td>{{ @$record->student->admission_no }}</td>
                                                    <td>{{ @$record->student->full_name }}</td>
                                                    <td>{{ @$record->class != '' ? @$record->class->class_name : '' }}
                                                        ({{ @$record->section != '' ? @$record->section->section_name : '' }})
                                                    </td>

                                                    <td>{{ @$record->student->parents != '' ? @$record->student->parents->fathers_name : '' }}
                                                    </td>
                                                    <td>

                                                        {{ @$record->student->date_of_birth != '' ? dateConvert(@$record->student->date_of_birth) : '' }}

                                                    </td>
                                                    <td>{{ @$record->student->gender != '' ? @$record->student->gender->base_setup_name : '' }}
                                                    </td>
                                                    <td>{{ @$record->student->mobile }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


@endsection
@include('backEnd.partials.data_table_js')
