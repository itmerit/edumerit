@extends('backEnd.master')
@section('title')
@lang('library.student_book_issue')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('library.student_book_issue')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('library.library')</a>
                <a href="#">@lang('library.student_book_issue')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
<div class="row mt-40">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-0">@lang('library.all_issued_book_list')</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <x-table>
                    <table id="table_id" class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('library.book_title')</th>
                                <th>@lang('library.book_no')</th>
                                <th>@lang('library.isbn_no')</th>
                            {{-- <th>Member Name</th> --}}
                                <th>@lang('library.author')</th>
                                <th>@lang('common.subject')</th>
                                <th>@lang('library.issue_date')</th>
                                <th>@lang('library.return_date')</th>
                                <th>@lang('common.status')</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issueBooks as $value)
                            <tr>
                            <td>{{@$value->books !=""?@$value->books->book_title:""}}</td>
                            <td>{{@$value->books !=""?@$value->books->book_number:""}}</td>
                            <td>{{@$value->books !=""?@$value->books->isbn_no:""}}</td>

                                <td>{{@$value->books !=""?@$value->books->author_name:""}}</td>
                                <td>{{$value->subject_name !=""?$value->subject_name:""}}</td>

                                <td  data-sort="{{strtotime(@$value->given_date)}}" >
                                {{@$value->given_date != ""? dateConvert(@$value->given_date):''}}

                                </td>
                                <td  data-sort="{{strtotime(@$value->due_date)}}" >
                                {{$value->due_date != ""? dateConvert(@$value->due_date):''}}

                                </td>
                                <td>
                                    @php
                                        $now=new DateTime($value->given_date);
                                        $end=new DateTime($value->due_date);
                                    @endphp
                                    @if($value->issue_status == 'I')
                                        @if($end<$now)
                                            <button class="primary-btn small bg-danger text-white border-0">@lang('library.expired')</button>
                                        @else
                                            <button class="primary-btn small bg-success text-white border-0">@lang('library.issued')</button>
                                        @endif
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
</div>
</section>
@endsection
@include('backEnd.partials.data_table_js')