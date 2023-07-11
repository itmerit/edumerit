@extends('backEnd.master')
@section('title')
    @lang('inventory.issue_item_list')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('inventory.issue_item_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('inventory.inventory')</a>
                    <a href="#">@lang('inventory.issue_item_list')</a>
                </div>
            </div>
        </div>
    </section>
    <style type="text/css">
        #selectStaffsDiv,
        .forStudentWrapper {
            display: none;
        }
    </style>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">

                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @lang('inventory.issue_a_item')
                                </h3>
                            </div>
                            @if (isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['holiday-update', $editData->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                            @else
                                @if (userPermission('save-item-issue-data'))
                                    {{ Form::open([
                                        'class' => 'form-horizontal',
                                        'files' => true,
                                        'route' => 'save-item-issue-data',
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">

                                        <div class="col-lg-12 mb-15">
                                            <label class="primary_input_label" for="">@lang('common.role') <span class="text-danger"> *</span> </label>
                                            <select
                                                class="primary_select  form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                                name="role_id" id="member_type">
                                                <option data-display=" @lang('inventory.user_type') *" value="">@lang('inventory.user_type')
                                                    *</option>
                                                @foreach ($roles as $value)
                                                    @if (isset($editData))
                                                        <option value="{{ $value->id }}"
                                                            {{ $value->id == $editData->role_id ? 'selected' : '' }}>
                                                            {{ $value->name }}</option>
                                                    @else
                                                        <option value="{{ $value->id }}"
                                                            {{ old('role_id') != '' ? (old('role_id') == $value->id ? 'selected' : '') : '' }}>
                                                            {{ $value->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('role_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('role_id') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="forStudentWrapper col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-12 mb-15">
                                                    <label class="primary_input_label" for="">@lang('common.class') <span class="text-danger"> *</span> </label>
                                                    <select
                                                        class="primary_select form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                                        id="select_class" name="class">
                                                        <option data-display="@lang('common.select_class') *" value="">
                                                            @lang('common.select_class') *</option>
                                                        @foreach ($classes as $class)
                                                            <option value="{{ $class->id }}"
                                                                {{ old('class') == $class->id ? 'selected' : '' }}>
                                                                {{ $class->class_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('class'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            {{ $errors->first('class') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-lg-12 mb-15" id="select_section_div">
                                                    <label class="primary_input_label" for="">@lang('common.section') <span class="text-danger"> *</span> </label>
                                                    <select
                                                        class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                                        id="select_section" name="section">
                                                        <option data-display="@lang('common.select_section') *" value="">
                                                            @lang('common.select_section') *</option>
                                                    </select>
                                                    <div class="pull-right loader loader_style" id="select_section_loader">
                                                        <img class="loader_img_style"
                                                            src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                            alt="loader">
                                                    </div>
                                                    @if ($errors->has('section'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            {{ $errors->first('section') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-lg-12 mb-15" id="select_student_div">
                                                    <label class="primary_input_label" for="">@lang('common.student') <span class="text-danger"> *</span> </label>
                                                    <select
                                                        class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}"
                                                        id="select_student" name="student">
                                                        <option data-display="@lang('common.select_student')*" value="">
                                                            @lang('inventory.select_student_for_issue') *</option>
                                                    </select>
                                                    <div class="pull-right loader loader_style" id="select_student_loader">
                                                        <img class="loader_img_style"
                                                            src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                                                            alt="loader">
                                                    </div>
                                                    @if ($errors->has('student'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            {{ $errors->first('student') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-15" id="selectStaffsDiv">
                                            <label class="primary_input_label" for="">@lang('inventory.issue_to') <span></span> </label>
                                            <select
                                                class="primary_select  form-control{{ $errors->has('staff_id') ? ' is-invalid' : '' }}"
                                                name="staff_id" id="selectStaffs">
                                                <option data-display="@lang('inventory.issue_to')" value="">@lang('inventory.issue_to')
                                                </option>

                                                @if (isset($staffsByRole))
                                                    @foreach ($staffsByRole as $value)
                                                        <option value="{{ $value->id }}"
                                                            {{ $value->id == $editData->staff_id ? 'selected' : '' }}>
                                                            {{ $value->full_name }}</option>
                                                    @endforeach
                                                @else
                                                @endif
                                            </select>
                                            @if ($errors->has('staff_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('staff_id') }}
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="row mb-15">                                        
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('inventory.issue_date') <span></span> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input
                                                                class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('issue_date') ? ' is-invalid' : '' }}"
                                                                id="startDate" type="text" name="issue_date"
                                                                value="{{ isset($editData) ? date('m/d/Y', strtotime($editData->issue_date)) : date('m/d/Y') }}">
                                                            </div>
                                                        </div>
                                                        <button class="btn-date" data-id="#issue_date" type="button">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{$errors->first('issue_date')}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-15">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('inventory.due_date') <span></span> </label>
                                                <div class="primary_datepicker_input">
                                                    <div class="no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="">
                                                                <input
                                                    class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}"
                                                    id="endDate" type="text" name="due_date"
                                                    value="{{ isset($editData) ? date('m/d/Y', strtotime($editData->issue_date)) : date('m/d/Y') }}">
                                                            </div>
                                                        </div>
                                                        <button class="btn-date" data-id="#due_date" type="button">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{$errors->first('due_date')}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-12 mb-15">
                                            <label class="primary_input_label" for="">@lang('inventory.category') <span class="text-danger"> *</span> </label>
                                            <select
                                                class="primary_select  form-control{{ $errors->has('item_category_id') ? ' is-invalid' : '' }}"
                                                name="item_category_id" id="item_category_id">
                                                <option data-display="@lang('inventory.item_category') *" value="">
                                                    @lang('inventory.item_category') *</option>
                                                @foreach ($itemCat as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('item_category_id') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->category_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('item_category_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('item_category_id') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-lg-12 mb-15" id="selectItemsDiv">
                                            <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span> </label>
                                            <select
                                                class="primary_select  form-control{{ $errors->has('item_id') ? ' is-invalid' : '' }}"
                                                name="item_id" id="selectItems">
                                                <option data-display="@lang('inventory.item_name') *" value="">
                                                    @lang('inventory.item_name') *</option>
                                            </select>
                                            @if ($errors->has('item_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('item_id') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-lg-12 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('inventory.quantity') <span class="text-danger"> *</span> </label>
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                                    type="text" onkeypress="return isNumberKey(event)" name="quantity"
                                                    autocomplete="off" value="{{ old('quantity') }}">
                                               
                                                
                                                @if ($errors->has('quantity'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('quantity') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('inventory.note') <span></span> </label>
                                                <textarea class="primary_input_field form-control" cols="0" rows="4" name="description" id="description">{{ isset($editData) ? $editData->description : old('description') }}</textarea>
                                               
                                                

                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                                    @php
                                        $tooltip = '';
                                        if (userPermission('save-item-issue-data')) {
                                            $tooltip = '';
                                        } else {
                                            $tooltip = 'You have no permission to add';
                                        }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit issuedSubmit" data-toggle="tooltip"
                                                title="{{ $tooltip }}">

                                                <span class="ti-check"></span>
                                                @if (isset($editData))
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
                                <h3 class="mb-0"> @lang('inventory.issued_item_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th> @lang('common.sl')</th>
                                            <th> @lang('inventory.item_name')</th>
                                            <th> @lang('inventory.item_category')</th>
                                            <th> @lang('inventory.issue_to')</th>
                                            <th> @lang('inventory.issue_date')</th>
                                            <th> @lang('inventory.return_date')</th>
                                            <th> @lang('inventory.quantity')</th>
                                            <th> @lang('common.status')</th>
                                            <th> @lang('common.action')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (isset($issuedItems))
                                            @foreach ($issuedItems as $key => $value)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->items != '' ? $value->items->item_name : '' }}</td>
                                                    <td>{{ $value->categories != '' ? $value->categories->category_name : '' }}</td>

                                                    @if ($value->role_id == 2)
                                                        @php
                                                            $getMemberDetail = App\SmBook::getMemberDetails($value->issue_to);
                                                        @endphp
                                                    @else
                                                        @php
                                                            $getMemberDetail = App\SmBook::getMemberStaffsDetails($value->issue_to);
                                                        @endphp
                                                    @endif

                                                    <td>
                                                        @if (!empty($getMemberDetail))
                                                            {{ $getMemberDetail->full_name }}
                                                        @endif
                                                    </td>
                                                    <td data-sort="{{ strtotime($value->issue_date) }}">
                                                        {{ $value->issue_date != '' ? dateConvert($value->issue_date) : '' }}

                                                    </td>
                                                    <td data-sort="{{ strtotime($value->due_date) }}">
                                                        {{ $value->due_date != '' ? dateConvert($value->due_date) : '' }}


                                                    </td>

                                                    <td>{{ $value->quantity }}</td>
                                                    <td>
                                                        @if ($value->issue_status == 'I')
                                                            <button class="primary-btn small bg-success text-white border-0">
                                                                @lang('inventory.issued')</button>
                                                        @else
                                                            <button
                                                                class="primary-btn small bg-primary text-white border-0">@lang('inventory.returned')</button>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($value->issue_status == 'I')
                                                        <x-drop-down>
                                                                    @if (userPermission('return-item-view'))
                                                                        <a class="dropdown-item modalLink"
                                                                            title="@lang('inventory.return_item')"
                                                                            data-modal-size="modal-md"
                                                                            href="{{ route('return-item-view', @$value->id) }}">@lang('inventory.return')</a>
                                                                    @endif
                                                        </x-drop-down>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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
@include('backEnd.partials.date_picker_css_js')
@push('scripts')
  <script>
      $(document).on('.issuedSubmit', 'click', function (e) {
          let issue_date = $('#startDate').val();
          let return_date = $('#endDate').val();
          alert(return_date);
          if(issue_date > return_date) {
            toastr.error("Return Date will be greater Than issue date");
            return;
            e.preventdefault();
          }
      })
  </script>
@endpush