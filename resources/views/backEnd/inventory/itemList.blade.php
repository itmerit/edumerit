@extends('backEnd.master')
@section('title')
@lang('inventory.item_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('inventory.item_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('inventory.inventory')</a>
                <a href="#">@lang('inventory.item_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($editData))
        @if(userPermission("item-list-store"))
           
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('item-list')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        @endif
      <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($editData))
                                    @lang('inventory.edit_item')
                                @else
                                    @lang('inventory.add_item')
                                @endif
                               
                            </h3>
                        </div>
                        @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('item-list-update',$editData->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        @if(userPermission("item-list-store"))
           
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'item-list',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row"> 
                                    <div class="col-lg-12 mb-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('inventory.item_name') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('item_name') ? ' is-invalid' : '' }}"
                                            type="text" name="item_name" autocomplete="off" value="{{isset($editData)? $editData->item_name : '' }}">
                                           
                                            
                                            @if ($errors->has('item_name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('item_name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('inventory.item_category') <span class="text-danger"> *</span> </label>
                                            <select class="primary_select  form-control{{ $errors->has('category_name') ? ' is-invalid' : '' }}" name="category_name" id="category_name">
                                                <option data-display="@lang('inventory.select_item_category') *" value="">@lang('common.select')</option>
                                                @foreach($itemCategories as $key=>$value)
                                                <option value="{{$value->id}}"
                                                @if(isset($editData))
                                                @if($editData->item_category_id == $value->id)
                                                    selected
                                                @endif
                                                @endif
                                                >{{$value->category_name}}</option>
                                                @endforeach
                                            </select>
                                            
                                            @if ($errors->has('category_name'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('category_name') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description') <span></span> </label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="4" name="description" id="description">{{isset($editData) ? $editData->description : ''}}</textarea>
                                            
                                            

                                        </div>
                                    </div>
                             </div>
                  				@php 
                                  $tooltip = "";
                                  if(userPermission("item-list-store") || userPermission('item-list-edit')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                       <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{$tooltip}}">

                                            <span class="ti-check"></span>
                                            @if(isset($editData))
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
                    <h3 class="mb-0">@lang('inventory.item_list')</h3>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">
                <x-table>
                <table id="table_id" class="table data-table" cellspacing="0" width="100%">

                    <thead>
                       
                        <tr>
                            <th>@lang('common.sl')</th>
                            <th>@lang('inventory.item_name')</th>
                            <th>@lang('student.category') </th>
                            <th>@lang('inventory.total_in_stock') </th>
                            <th>@lang('common.action')</th>
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
                           url: "{{route('item-list-ajax')}}",
                           data: { 
                               
                            },
                           pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                           
                       } ),
                       columns: [
                           {data: 'DT_RowIndex', name: 'id'},
                            {data: 'item_name', name: 'item_name'},
                            {data: 'category.category_name', name: 'category.category_name'},
                            {data: 'total_in_stock', name: 'total_in_stock'},
                           {data: 'action', name: 'action', orderable: false, searchable: true},
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
<script>
    function deleteHomeWork(id){
        var modal = $('#deleteHomeWorkModal');
        modal.find('input[name=id]').val(id)
        modal.modal('show');
    }
</script>
@endpush