@extends('backEnd.master')
@section('mainContent')
@push('css')
<style>
    .dd {
        position: relative;
        display: block;
        margin: 0;
        padding: 0;
        max-width: 100%;
        list-style: none;
        font-size: 13px;
        line-height: 20px;
        }

    
        .dd-list {
        display: block;
        position: relative;
        margin: 0;
        padding: 0;
        list-style: none;
        }

        .dd-list .dd-list {
        padding-left: 30px;
        }

        .dd-collapsed .dd-list {
        display: none;
        }

    
        .dd-item,
        .dd-empty,
        .dd-placeholder {
        display: block;
        position: relative;
        margin: 0;
        padding: 0;
        min-height: 20px;
        font-size: 13px;
        line-height: 20px;
        margin-bottom: 5px;
        }

    
        .dd-handle {
        display: block;
        margin: 0px;
        text-decoration: none;
        border: 1px solid #ebebeb;
        background: rgba(0, 0, 0, .03);
        -webkit-border-radius: 3px;
        border-radius: 0px;
        / background: #F5F7FB; /
        padding: 2px 10px;
        border: 0;
        height: 50px;
        line-height: 46px;
        font-size: 14px;
        font-weight: 400;
        color: var(--base_color);
        padding-left: 30px;
        cursor: grab;

        }

    
        .dd-handle .menu_icon {
        float: left;
        padding: 0px 16px;
        font-size: 14px;
        font-weight: 400;
        border: 0;
        border: 1px solid #F5F7FB;
        box-sizing: border-box;
        border-radius: 23px 0px 0px 23px;
        color: var(--base_color);
        background: #fff;
        height: 46px;
        margin-right: 12px;
        position: absolute;
        left: 0;
        top: 0;
        }

    
        .edit_icon {
        float: right;
        cursor: pointer;
        font-size: 16px;
        color: #707DB7;
        font-weight: 400;
        padding-right: 20px;
        height: 46px;
        line-height: 46px;
        position: absolute;
        right: 0;
        top: 0;
        }

    
        .dd-item > button {
        display: none;
        position: relative;
        cursor: pointer;
        float: left;
        width: 60px;
        height: 46px;
        padding: 0;
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
        border: 0;
        background: transparent;
        font-size: 12px;
        line-height: 1;
        text-align: center;
        font-weight: bold;
        line-height: 46px;
        margin-left: 0px;
        / z-index: 99; /
        width: 38px;
        }

        
        .dd-item > button:before {
        content: "\e61a";
        position: absolute;
        left: 0;
        top: 0;
        font-family: 'themify';
        font-size: 14px;
        color: var(--base_color);
        top: 0px;
        left: 0px;
        font-size: 14px;
        }

        
        .dd-item > button[data-action="collapse"]:before {
        content: '\e622';
        font-size: 14px;
        }

        
        .dd-placeholder,
        .dd-empty {
        margin: 5px 0;
        padding: 0;
        min-height: 46px;
        background: #f2fbff;
        border: 1px dashed var(--base_color);
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        border-radius: 10px;
        }

        
        .dd-empty {
        border: 1px dashed var(--base_color);
        min-height: 100px;
        background-color: #e5e5e5;
        background-size: 60px 60px;
        background-position: 0 0, 30px 30px;
        }

        
        .dd-dragel {
        position: absolute;
        pointer-events: none;
        z-index: 9999;
        }

        
        .dd-dragel > .dd-item .dd-handle {
        margin-top: 0;
        }

        
        .dd-dragel .dd-handle {
        -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.1);
        box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.1);
        }

        /**
        * Nestable Extras
        */
        
        .nestable-lists {
        display: block;
        clear: both;
        padding: 30px 0;
        width: 100%;
        border: 0;
        border-top: 2px solid #ddd;
        border-bottom: 2px solid #ddd;
        }

        @media only screen and (min-width: 700px) {
        
        .dd + .dd {
        margin-left: 2%;
        }
        }

        
        .dd-hover > .dd-handle {
        background: #2ea8e5 !important;
        }

        /**
        * Nestable Draggable Handles
        */
        
        .dd3-content {
        display: block;
        margin: 5px 0;
        padding: 5px 10px 0px 44px;
        text-decoration: none;
        border: 1px solid #ebebeb;
        background: #fff;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        overflow: hidden;
        }

        
        .dd-dragel > .dd3-item > .dd3-content {
        margin: 0;
        }

        
        .dd3-item > button {
        margin-left: 40px;
        }

        
        .dd3-handle {
        position: absolute;
        margin: 0;
        left: 0;
        top: 0;
        cursor: pointer;
        width: 40px;
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
        border: 1px solid #ebebeb;
        background: #fff;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        }

        
        .dd3-handle:before {
        content: '≡';
        display: block;
        position: absolute;
        left: 0;
        top: 10px;
        width: 100%;
        text-align: center;
        text-indent: 0;
        color: #ccc;
        font-size: 20px;
        font-weight: normal;
        }

        
        .dd3-handle:hover {
        background: #f7f7f7;
        }

        .collapge_arrow_normal::after{
        content: "\f107";
        color: #333;
        top: 0px;
        right: 4px;
        position: absolute;
        font-family: "FontAwesome";
        }
        .panel-title[aria-expanded="true"] .collapge_arrow_normal::after {
        content: "\f106";
        }
        .btn_zindex{
        z-index: 1000;
        }
        .btn_div{
        margin-top: -43px;
        max-height: 10px;
        }

        .mt-55{
        margin-top: 55px!important;
        }
        .column_header{
        padding: 10px;
        background: var(--base_color);
        color: #fff;
        }
        .item_list .card_header{
        background: var(--base_color);
        color: #fff;
        }
        .item_list .card_header h4{
        color: #fff;
        }
        .card_header_element{
        / padding: 3px 1.25rem; /
        padding-top: 10px;
        padding-bottom: 0px;
        }
        .card_header_element .pull-right{
        margin-top: -6px;
        }
        .p-15{
        padding:15px;
        }
        .card-header {
            padding: 5px;
        }
        .card{
            margin-top: 5px;
        }
        .create-title {
            position: relative;
            cursor: pointer;
            }

            .create-title::after {
            content: "\f107";
            color: #333;
            top: 12px;
            right: 5px;
            position: absolute;
            font-family: "FontAwesome"
            }

            .create-title[aria-expanded="true"]::after {
            content: "\f106";
            }

            .cust-btn-link {
                color: var(--base_color);;
                text-decoration: none;
            }


</style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('front_settings.header_menu_manager')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('front_settings.front_settings')</a>
                <a href="#">@lang('front_settings.header_menu_manager')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @lang('front_settings.add_header_menu')
                            </h3>
                        </div>
                        @include('frontEnd.headerMenuElementList')
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('front_settings.menu_list')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="menuList">
                        @include('backEnd.frontSettings.headerSubmenuList')
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Modal Start --}}
    <div class="modal fade admin-query" id="deleteSubmenuItem" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.delete_menu')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>
                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        <input type="hidden" name="id" id="item-delete" value="">
                        <a class="primary-btn fix-gr-bg" id="delete-item" href="#">@lang('common.delete')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Modal End --}}
</section>
@endsection
@include('backEnd.partials.multi_select_js')
@push('script')
<script src="{{asset('public/backEnd/')}}/js/jquery.nestable.min.js"></script>
<script>
    $( document ).ready(function() {
        $(document).on('click', '#add_page_btn', function(event){
            let dPages = $('#dPages').val();
            let pagesText = $('#dPages option:selected').text();

            if(dPages.length > 0){
                $('#dPages').val('');
                let data = {
                    'type' : 'dPages',
                    'element_id' : dPages,
                    '_token' : '{{ csrf_token() }}'
                }
                $.post("{{route('add-element')}}",data, function(data){
                    if(data){
                        blankData();
                        toastr.success("Operation successful","Successful", {timeOut: 5000,});
                        reloadWithData(data);
                    }else{
                        toastr.error("Operation failed","Error", {timeOut: 5000,});
                    }
                });
            }else{
                toastr.error("Operation failed","Error", {timeOut: 5000,});
            }
        });
    });

    $( document ).ready(function() {
        $(document).on('click', '#add_static_page_btn', function(event){
            let sPages = $('#sPages').val();
            let staticPageText = $('#sPages option:selected').text();

            if(sPages.length > 0){
                $('#sPages').val('');
                let data = {
                    'type' : 'sPages',
                    'element_id' : sPages,
                    '_token' : '{{ csrf_token() }}'
                }

                $.post("{{route('add-element')}}",data, function(data){

                    if(data){
                        blankData();
                        toastr.success("Operation successful","Successful", {timeOut: 5000,});
                        reloadWithData(data);
                    }else{
                        toastr.error("Operation failed","Error", {timeOut: 5000,});
                    }
                });
            }else{
                toastr.error("Operation failed","Error", {timeOut: 5000,});
            }
        });
    });

    $( document ).ready(function() {
        $(document).on('click', '#add_course_btn', function(event){
            let dCourse = $('#dCourse').val();
            let courseText = $('#dCourse option:selected').text();

            if(dCourse.length > 0){
                $('#dCourse').val('');
                let data = {
                    'type' : 'dCourse',
                    'element_id' : dCourse,
                    '_token' : '{{ csrf_token() }}'
                }
                $.post("{{route('add-element')}}",data, function(data){
                    if(data){
                        blankData();
                        toastr.success("Operation successful","Successful", {timeOut: 5000,});
                        reloadWithData(data);
                    }else{
                        toastr.error("Operation failed","Error", {timeOut: 5000,});
                    }
                });
            }else{
                toastr.error("Operation failed","Error", {timeOut: 5000,});
            }
        });
    });


    $( document ).ready(function() {
        $(document).on('click', '#add_course_category_btn', function(event){
            let dCourseCategory = $('#dCourseCategory').val();
            let courseCategoryText = $('#dCourseCategory option:selected').text();

            if(dCourseCategory.length > 0){
                $('#dCourseCategory').val('');
                let data = {
                    'type' : 'dCourseCategory',
                    'element_id' : dCourseCategory,
                    '_token' : '{{ csrf_token() }}'
                }
                $.post("{{route('add-element')}}",data, function(data){
                    if(data){
                        blankData();
                        toastr.success("Operation successful","Successful", {timeOut: 5000,});
                        reloadWithData(data);
                    }else{
                        toastr.error("Operation failed","Error", {timeOut: 5000,});
                    }
                });
            }else{
                toastr.error("Operation failed","Error", {timeOut: 5000,});
            }
        });
    });


    $( document ).ready(function() {
        $(document).on('click', '#add_news_btn', function(event){
            let dNews = $('#dNews').val();
            let newsText = $('#dNews option:selected').text();

            if(dNews.length > 0){
                $('#dNews').val('');
                let data = {
                    'type' : 'dNews',
                    'element_id' : dNews,
                    '_token' : '{{ csrf_token() }}'
                }
                $.post("{{route('add-element')}}",data, function(data){
                    if(data){
                        blankData();
                        toastr.success("Operation successful","Successful", {timeOut: 5000,});
                        reloadWithData(data);
                    }else{
                        toastr.error("Operation failed","Error", {timeOut: 5000,});
                    }
                });
            }else{
                toastr.error("Operation failed","Error", {timeOut: 5000,});
            }
        });
    });

    $( document ).ready(function() {
        $(document).on('click', '#add_news_category_btn', function(event){
            let dNewsCategory = $('#dNewsCategory').val();
            let dNewsCategoryText = $('#dNewsCategory option:selected').text();

            if(dNewsCategory.length > 0){
                $('#dNewsCategory').val('');
                let data = {
                    'type' : 'dNewsCategory',
                    'element_id' : dNewsCategory,
                    '_token' : '{{ csrf_token() }}'
                }
                $.post("{{route('add-element')}}",data, function(data){
                    if(data){
                        blankData();
                        toastr.success("Operation successful","Successful", {timeOut: 5000,});
                        reloadWithData(data);
                    }else{
                        toastr.error("Operation failed","Error", {timeOut: 5000,});
                    }
                });
            }else{
                toastr.error("Operation failed","Error", {timeOut: 5000,});
            }
        });
    });

    $( document ).ready(function() {
        $(document).on('click', '#add_custom_link_btn', function(event){
            let tTitle = $('#tTitle').val();
            let tLink = $('#tLink').val();
            if( !$('#tTitle').val()){
                $('#titleError').html('Title field is required');
            }else{
                let data = {
                    'type' : 'customLink',
                    'title' : tTitle,
                    'link' : tLink,
                    '_token' : '{{ csrf_token() }}'
                }
                $.post("{{route('add-element')}}",data, function(data){
                    if(data){
                        blankData();
                        toastr.success("Operation successful","Successful", {timeOut: 5000,});
                        reloadWithData(data);
                    }else{
                        toastr.error("Operation failed","Error", {timeOut: 5000,});
                    }
                });
            }
        });
    });

    $(document).ready(function(){
        $(document).on('mouseover','body', function(){
            $('.dd').nestable({
                maxDepth:3,
                callback:function(l,e){
                let order = JSON.stringify($('.dd').nestable('serialize'));
                    let data = {
                    'order' : order,
                    '_token' : '{{ csrf_token() }}'
                    }
                    $.post('{{route('reordering')}}',data, function(data){
                        if(data != 1){
                            toastr.error( "Element is Not Moved. Error ocurred", "Error", {timeOut: 5000,});
                        }
                    });
                }
            });
        });
    });

    $(document).ready(function(){
        $(document).on('submit', '#elementEditForm', function(event){
            event.preventDefault();
            var formElement = $(this).serializeArray()
            var formData = new FormData();
            formElement.forEach(element => {
            formData.append(element.name, element.value);
            });

            formData.append('_token', "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('element-update') }}",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                reloadWithData(response);
                    toastr.success('Operation successfully.','Success', {timeOut: 5000,});
                },
                error: function(response) {
                    toastr.error("Operation failed","Error", {timeOut: 5000,});
                }
            });

        });
    });

    function elementDelete(id){
        $('#deleteSubmenuItem').modal('show');
        $('#item-delete').val(id);
    }

    $(document).on('click', '#delete-item', function(event){
        event.preventDefault();
        $('#deleteSubmenuItem').modal('hide');
        let id = $('#item-delete').val();
            let data = {
                'id' : id,
                '_token' : '{{ csrf_token() }}',
            }
            $.post("{{ route('delete-element') }}",data, 
            function(data){
                toastr.success('Delete Successfully.','Success', {timeOut: 5000,});
                reloadWithData(data);
            });
    });

    function reloadWithData(response){
        $('#menuList').empty();
        $('#menuList').html(response);
        $('.optionPopup').niceSelect();
    }

    function blankData(){
        $('#tTitle').val('');
        $('#tLink').val('');

        // $('#dNewsCategory').select2({allowClear: true});
        // $('#dCourse').select2({allowClear: true});
        // $('#dCourseCategory').select2({allowClear: true});
        // $('#dNews').select2({allowClear: true});
        // $('#sPages').select2({allowClear: true});
        // $('#dPages').select2({allowClear: true});
        $('.primary_input_field').removeClass('has-content');
    }
</script>
@endpush