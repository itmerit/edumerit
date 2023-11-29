@extends('backEnd.master')
@section('title')
@lang('system_settings.language_settings')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.language_settings')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.language_settings')</a>

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            
            <div class="row">
            @if(moduleStatusCheck('Saas')== FALSE   )
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@if(isset($edit_languages))
                                        @lang('system_settings.edit_language')
                                    @else
                                        @lang('system_settings.add_language')
                                    @endif
                                  
                                </h3>
                            </div>
                           
                            @if(isset($selected_languages))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'language-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                                @if(userPermission('language-add'))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'language-add',
                                    'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">

                                    @if(isset($select_language))
                                        <input type="hidden" name="id" value="{{@$select_language->id}}">

                                    @endif

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <select
                                                    class="primary_select  form-control {{ $errors->has('lang_id') ? ' is-invalid' : '' }}"
                                                    name="lang_id" id="lang_id">
                                                    <option data-display="@lang('system_settings.select_language') *"
                                                            value="">@lang('system_settings.select_language') *</option>
                                                    @foreach($all_languages as $lang)
                                                        <option value="{{$lang->id}}"
                                                            {{isset($select_language) ? (@$select_language->lang_id == @$lang->id )? 'selected':'':'' }}
                                                        > {{@$lang->name}} - {{@$lang->native}} </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('lang_id'))
                                                    <span class="text-danger" >
                                                    {{ $errors->first('lang_id') }}
                                                </span>
                                                @endif
                                            </div>


                                        </div>
                                    </div>

                                    @php 
                                        $tooltip = "";
                                        if(userPermission('language-add')){
                                                $tooltip = "";
                                            }else{
                                                $tooltip = "You have no permission to add";
                                            }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($select_language))
                                                    @lang('system_settings.update_language')
                                                @else
                                                    @lang('system_settings.save_language')
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
            @endif
            @if(moduleStatusCheck('Saas')== FALSE   )                                            
                <div class="col-lg-9">
            @endif

            @if(moduleStatusCheck('Saas')== TRUE   )                                            
                <div class="col-lg-12">
            @endif
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('system_settings.language_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table data-table Crm_table_active3 no-footer dtr-inline collapsed" cellspacing="0" width="100%">
                                <thead>
                                
                                <tr>
                                    <th>@lang('common.sl')</th>
                                    <th>@lang('system_settings.language')</th>
                                    <th>@lang('system_settings.native')</th>
                                    <th>@lang('system_settings.universal')</th>

                                    <th>@lang('common.status')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $i=0;
                                    @$active     = 'primary-btn-small-input primary-btn small fix-gr-bg';
                                    @$inactive   =  'primary-btn small tr-bg';
                                @endphp

                                @foreach($sms_languages as $sms_language)
                                    <tr>
                                        <td>{{++$i}}
                                        <td>{{@$sms_language->language_name}}</td>
                                        <td>{{@$sms_language->native}}</td>
                                        <td>{{@$sms_language->language_universal}}</td>


                                        <td>
                                        @if(@$sms_language->active_status==1)
                                            <!-- <span class="badge badge-pill badge-success"></span> -->
                                                <strong>Active</strong>

                                        @else
                                            <!-- <span class="badge badge-pill badge-secondary"></span> -->
                                                In Active
                                            @endif

                                        </td>
                                        <td>

                                            @if(@$sms_language->active_status==1)
                                                <a href="{{route('change-language',@$sms_language->id)}}"
                                                   class="{{@$sms_language->active_status==1?@$active:@$inactive}} "   > <span
                                                        class="ti-check"></span> @lang('system_settings.default')</a>
                                            @else
                                                @if(userPermission('change-language'))
                                                <a href="{{route('change-language',@$sms_language->id)}}"
                                                    class="{{@$sms_language->active_status==1?@$active:@$inactive}} "   > <span
                                                            class="ti-check"></span> @lang('system_settings.make_default')</a>
                                                @endif
                                            @endif

                                            {{-- <a href="{{URL::to('/locale/'.$sms_language->language_universal)}}" class="primary-btn small tr-bg white_space"  > <span class="ti-check"></span> @lang('system_settings.make_default')</a>--}}

                                           @if(moduleStatusCheck('Saas') == FALSE) 

                                            @if(userPermission('language-setup') )
                                            <a href="{{route('language-setup',@$sms_language->language_universal)}} "
                                               class="primary-btn small tr-bg white_space"> <span
                                                    class="ti-settings"></span> @lang('system_settings.setup') </a>
                                            @endif
                                            <a href="{{route('lang-file-export',@$sms_language->language_universal)}} "
                                                class="primary-btn small tr-bg white_space"> <span
                                                        class="ti-download"></span> @lang('common.export') 
                                            </a>
                                            <a href="{{route('lang-file-import',@$sms_language->language_universal)}} "
                                                class="primary-btn small tr-bg white_space"> <span
                                                        class="ti-upload"></span> @lang('common.import') 
                                                    
                                            </a>
                                                    @if($sms_language->language_universal !='en' && $sms_language->active_status==0)
                                              @if(userPermission('language-delete'))
                                                    <a 
                                                   href="{{route('language-delete')}}" class="primary-btn small tr-bg white_space" data-toggle="modal"
                                                   data-target="#deleteLanguage{{@$sms_language->id}}" >
                                                    <span class="ti-close"></span> @lang('system_settings.remove') 
                                              </a>
                                            @endif

                                            @endif
                                        @endif

                                        

                                            <div class="modal fade admin-query"
                                                 id="deleteLanguage{{@$sms_language->id}}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('system_settings.delete_language')</h4>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('system_settings.are_you_sure_to_remove')</h4>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('common.cancel')</button>
                                                                {{ Form::open(['route' => 'language-delete', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                                <input type="hidden" name="id"
                                                                       value="{{@$sms_language->id}}">
                                                                <button class="primary-btn fix-gr-bg"
                                                                        type="submit">@lang('system_settings.remove')</button>
                                                                {{ Form::close() }}
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


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

@push('script')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{url('student-list-datatable')}}",
                    data: {
                        academic_year: $('#academic_id').val(),
                        class: $('#class').val(),
                        section: $('#section').val(),
                        roll_no: $('#roll').val(),
                        name: $('#name').val(),
                        un_session_id: $('#un_session').val(),
                        un_academic_id: $('#un_academic').val(),
                        un_faculty_id: $('#un_faculty').val(),
                        un_department_id: $('#un_department').val(),
                        un_semester_label_id: $('#un_semester_label').val(),
                        un_section_id: $('#un_section').val(),
                    },
                    pages: "{{generalSetting()->ss_page_load}}"
                } ),
                columns: [
                    {data: 'admission_no', name: 'admission_no'},                  
                    {data: 'full_name', name: 'full_name'},  
                    @if(!moduleStatusCheck('University') && generalSetting()->with_guardian)
                     {data: 'parents.fathers_name', name: 'parents.fathers_name'},
                    @endif
                    {data: 'dob', name: 'dob'},
                    @if(moduleStatusCheck('University'))
                        {data: 'semester_label', name: 'semester_label'},
                        {data: 'class_sec', name: 'class_sec'},
                    @else
                        {data: 'class_sec', name: 'class_sec'},
                    @endif
                    {data: 'gender.base_setup_name', name: 'gender.base_setup_name'},
                    {data: 'category.category_name', name: 'category.category_name'},
                    {data: 'mobile', name: 'sm_students.mobile'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'first_name', name: 'first_name', visible : false},
                    {data: 'last_name', name: 'last_name', visible : false},
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
                            doc.content[1].margin = [100, 0, 100, 0];
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