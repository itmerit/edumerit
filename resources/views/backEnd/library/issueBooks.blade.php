@extends('backEnd.master')
@section('title')
    @lang('library.issue_books')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('library.library_book_issue')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('library.library')</a>
                    <a href="{{ route('member-list') }}">@lang('library.member_list')</a>
                    <a href="#">@lang('library.issue_books')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-40 student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Start Student Meta Information -->
                    <div class="main-title">
                        <h3 class="mb-20">@lang('library.issue_books')</h3>
                    </div>
                    <div class="student-meta-box mt-30">
                        <div class="student-meta-top"></div>
                    
                        @if (@$memberDetails->member_type == 2)
                            <img class="student-meta-img img-100"
                                src="{{ file_exists(@$memberDetails->studentDetails->student_photo) ? asset($memberDetails->studentDetails->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                alt="">
                        @elseif(@$memberDetails->member_type == 3)
                            <img class="student-meta-img img-100"
                                src="{{ file_exists(@$staffDetails->guardians_photo) ? asset($staffDetails->guardians_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                alt="">
                        @else
                            <img class="student-meta-img img-100"
                                src="{{ file_exists(@$memberDetails->staffDetails->staff_photo) ? asset($memberDetails->staffDetails->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                alt="">
                        @endif
                        <div class="white-box">
                            <div class="single-meta mt-10">
                                <div class="d-flex justify-content-between">
                                    @if ($memberDetails->member_type == 3)
                                        <div class="name">
                                            @lang('library.parent_name')
                                        </div>
                                    @elseif($memberDetails->member_type == 2)
                                        <div class="name">
                                            @lang('common.student_name')
                                        </div>
                                    @else
                                        <div class="name">
                                            @lang('library.staff_name')
                                        </div>
                                    @endif

                                    <div class="value">
                                        @if (isset($getMemberDetails))
                                            @if ($memberDetails->member_type == 3)
                                                {{ $getMemberDetails->guardians_name }}
                                            @elseif($memberDetails->member_type == 2)
                                                {{ $getMemberDetails->first_name . ' ' . $getMemberDetails->last_name }}
                                                @else 
                                                {{ $getMemberDetails->full_name}}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('library.member_id')
                                    </div>
                                    <div class="value">
                                        @if (isset($memberDetails))
                                            {{ $memberDetails->member_ud_id }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('library.member_type')
                                    </div>
                                    <div class="value">
                                        @if (isset($memberDetails))
                                            {{ $memberDetails->memberTypes->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('common.mobile')
                                    </div>
                                    <div class="value">
                                        @if (isset($getMemberDetails))
                                            @if ($memberDetails->member_type == 3)
                                                {{ $getMemberDetails->guardians_mobile }}
                                            @else
                                                {{ $getMemberDetails->mobile }}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Student Meta Information -->
                    @if (userPermission('issue-books'))
                        <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h3 class="mb-30">
                                        @lang('library.issue_book')
                                    </h3>
                                </div>
                                @if (isset($editData))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['book-category-list-update', $editData->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                                @else
                                    {{ Form::open([
                                        'class' => 'form-horizontal',
                                        'files' => true,
                                        'route' => 'save-issue-book-data',
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) }}
                                @endif
                                <div class="white-box">
                                    <div class="add-visitor">
                                        <div class="row">
                                            <div class="col-lg-12 mb-20">

                                                <div class="primary_input">
                                                    <select
                                                        class="primary_select  form-control{{ $errors->has('book_id') ? ' is-invalid' : '' }}"
                                                        name="book_id" id="classSelectStudent">
                                                        <option data-display="@lang('library.select_book') *" value="">
                                                            @lang('library.select_book')</option>
                                                        @foreach ($books as $key => $value)
                                                            <option value="{{ $value->id }}">{{ $value->book_title }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('book_id'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                            {{ $errors->first('book_id') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-20">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('library.return_date') <span class="text-danger"> *</span></label>
                                                    <div class="primary_datepicker_input">
                                                        <div class="no-gutters input-right-icon">
                                                            <div class="col">
                                                                <div class="">
                                                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('due_date') ? ' is-invalid' : '' }}" id="due_date" type="text" name="due_date" value="{{isset($invoiceInfo)? date('m/d/Y', strtotime($invoiceInfo->due_date)) : date('m/d/Y')}}">
                                                                </div>
                                                            </div>
                                                            <button class="btn-date" data-id="#due_date" type="button">
                                                                <label for="due_date">
                                                                    <i class="ti-calendar" id="due_date"></i>
                                                                </label>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('due_date'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('due_date') }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <input type="hidden" name="member_id"
                                                value="{{ @$memberDetails->student_staff_id }}">
                                            <input type="hidden" name="url" id="url"
                                                value="{{ URL::to('/') }}">
                                        </div>
                                        <div class="row mt-40">
                                            <div class="col-lg-12 text-center">
                                                <button class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    @lang('library.issue_book')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0"> @lang('library.issued_book')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="15%">@lang('library.book_title')</th>
                                            <th width="15%">@lang('library.book_number')</th>
                                            <th width="15%">@lang('library.issue_date')</th>
                                            <th width="15%">@lang('library.return_date')</th>
                                            <th width="15%">@lang('common.status')</th>
                                            <th width="15%">@lang('common.action')</th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @if (isset($totalIssuedBooks))
                                            @foreach ($totalIssuedBooks as $value)
                                                <tr>
                                                    <td>{{ $value->books->book_title }}</td>
                                                    <td>{{ $value->books->book_number }}</td>
                                                    <td data-sort="{{ strtotime($value->given_date) }}">
                                                        {{ $value->given_date != '' ? dateConvert($value->given_date) : '' }}
    
                                                    </td>
                                                    <td data-sort="{{ strtotime($value->due_date) }}">
                                                        {{ $value->due_date != '' ? dateConvert($value->due_date) : '' }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $now = new DateTime($value->given_date);
                                                            $end = new DateTime($value->due_date);
                                                        @endphp
                                                        @if ($value->issue_status == 'I')
                                                            @if ($end < $now)
                                                                <button
                                                                    class="primary-btn small bg-danger text-white border-0">@lang('library.expired')</button>
                                                            @else
                                                                <button
                                                                    class="primary-btn small bg-success text-white border-0">@lang('library.issued')</button>
                                                            @endif
                                                        @else
                                                            <button
                                                                class="primary-btn small bg-success text-white border-0">@lang('library.returned')</button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            @if ($value->issue_status == 'I')
                                                                @if (userPermission('return-book-view'))
                                                                    <a title="{{ __('library.return_Book') }}"
                                                                        data-modal-size="modal-md"
                                                                        href="{{ route('return-book-view', $value->id) }}"
                                                                        class="modalLink primary-btn fix-gr-bg">@lang('library.return')</a>
                                                                @endif
                                                            @endif
                                                        </div>
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
