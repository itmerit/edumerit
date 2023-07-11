@extends('backEnd.master')
@section('title')
@lang('library.book_list')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('library.book_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('library.library')</a>
                <a href="#">@lang('library.book_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
    <div class="row mt-40">
        <div class="col-lg-12">
           <div class="row">
               <div class="col-lg-12">
                <x-table>
                    <table id="table_id" class="table" cellspacing="0" width="100%">

                        <thead> 
                            
                            <tr>
                                <th>@lang('library.book_title')</th>
                                <th>@lang('library.book_no')</th>
                                <th>@lang('library.isbn_no')</th>
                                <th>@lang('student.category')</th>
                                <th>@lang('common.subject')</th>
                                <th>@lang('library.publisher_name')</th>
                                <th>@lang('library.author_name')</th>
                                <th>@lang('library.quantity')</th>
                                <th>@lang('library.price')</th>
                            </tr>
                        </thead>

                        <tbody>
                        
                            @foreach($books as $value)
                            <tr>
                                <td>{{@$value->book_title}}</td>
                                <td>{{@$value->book_number}}</td>
                                <td>{{@$value->isbn_no}}</td>
                                <td>
                                @if(!empty(@$value->book_category_id))
                                    {{@$value->bookCategory->category_name}}
                                @endif
                                </td>
                                <td>
                                    @if(!empty(@$value->book_subject_id))
                                        {{@$value->bookSubject->subject_name}}
                                    @endif
                                </td>
                                <td>{{@$value->publisher_name}}</td>
                                <td>{{@$value->author_name}}</td>
                                <td>{{@$value->quantity}}</td>
                               <td>{{@$value->book_price}}</td>
                                
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