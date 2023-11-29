@push('css')
    <style>
        .primary_input.custom-transfer-account {
            display: inline-block;
        }
    </style>
@endpush
@if(count(@$menus)>0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="accordion" class="dd">
                        <ol class="dd-list">
                            @foreach($menus as $key => $element)
                            <li class="dd-item" data-id="{{$element->id}}">
                                <div class="card accordion_card" id="accordion_{{$element->id}}">
                                    <div class="card-header item_header" id="heading_{{$element->id}}">
                                        <div class="dd-handle">
                                            <div class="pull-left">
                                                @lang('common.title') : {{$element->title}}
                                            </div>
                                        </div>
                                        <div class="pull-right btn_div">
                                            @if(userPermission("element-update"))
                                            <a href="javascript:void(0);" onclick="" data-toggle="collapse" 
                                                data-target="#collapse_{{$element->id}}" aria-expanded="false" 
                                                aria-controls="collapse_{{$element->id}}" class="primary-btn btn_zindex panel-title">
                                                @lang('common.edit') 
                                                <span class="collapge_arrow_normal"></span>
                                            </a>
                                            @endif
                                            @if(env('APP_SYNC')==TRUE)
                                                <a href="javascript:void(0);" class="primary-btn btn_zindex" title="Disable For Demo" data-toggle="tooltip">
                                                    <i class="ti-close"></i>
                                                </a>
                                            @else
                                                @if(userPermission("delete-element"))
                                                <a href="javascript:void(0);" onclick="elementDelete({{$element->id}})" class="primary-btn btn_zindex">
                                                    <i class="ti-close"></i>
                                                </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div id="collapse_{{$element->id}}" class="collapse" aria-labelledby="heading_{{$element->id}}" data-parent="#accordion_{{$element->id}}">
                                        <div class="card-body">
                                            <form enctype="multipart/form-data" id="elementEditForm">
                                                <div class="row">
                                                    <input type="hidden" name="id" value="{{$element->id}}">
                                                        <input type="hidden" name="type" value="{{$element->type}}">
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-25">
                                                                <label class="primary_input_label" for="title">
                                                                    @lang('front_settings.navigation_label') 
                                                                    <span class="text-danger"> *</span>
                                                                </label>
                                                                <input class="primary_input_field form-control title" type="text" name="title" autocomplete="off" value="{{$element->title}}">
                                                            </div>
                                                        </div>
                                                        @if($element->type == 'customLink')
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-25">
                                                                <label class="primary_input_label" for="link">
                                                                    @lang('front_settings.link')
                                                                </label>
                                                                <input class="primary_input_field form-control link" type="text" name="link" autocomplete="off" value="{{$element->link}}"  placeholder="Link">
                                                            </div>
                                                        </div>
                                                        @elseif($element->type == 'dCourse')
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="">
                                                                    @lang('front_settings.course') 
                                                                    <span class="text-danger"> *</span>
                                                                </label>
                                                                <select name="course" class="primary_select optionPopup">
                                                                    @foreach($courses as $key => $course)
                                                                    <option {{$element->element_id == $course->id?'selected':'' }} value="{{$course->id}}">{{$course->title}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger"></span>
                                                            </div>
                                                        </div>
                                                        @elseif($element->type == 'dCourseCategory')
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="">
                                                                    @lang('front_settings.course') 
                                                                    <span class="text-danger"> *</span>
                                                                </label>
                                                                <select name="course_category" class="primary_select optionPopup">
                                                                    @foreach($courseCategories as $key => $courseCategory)
                                                                    <option {{$element->element_id == $courseCategory->id?'selected':'' }} value="{{$courseCategory->id}}">{{$courseCategory->category_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger"></span>
                                                            </div>
                                                        </div>
                                                        @elseif($element->type == 'dPages')
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="">
                                                                    @lang('front_settings.pages') 
                                                                    <span class="text-danger"> *</span>
                                                                </label>
                                                                <select name="page" class="primary_select optionPopup">
                                                                    @foreach($pages as $key => $page)
                                                                    <option {{$element->element_id == $page->id?'selected':'' }} value="{{$page->id}}">{{$page->title}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger"></span>
                                                            </div>
                                                        </div>
                                                        @elseif($element->type == 'sPages')
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="">
                                                                    @lang('front_settings.pages') 
                                                                    <span class="text-danger"> *</span>
                                                                </label>
                                                                <select name="static_pages" class="primary_select optionPopup">
                                                                    @foreach($static_pages as $key => $static_page)
                                                                    <option {{$element->element_id == $static_page->id?'selected':'' }} value="{{$static_page->id}}">{{$static_page->title}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @elseif($element->type == 'dNews')
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="">
                                                                    @lang('front_settings.news') 
                                                                    <span class="text-danger"> *</span>
                                                                </label>
                                                                <select name="news" class="primary_select optionPopup">
                                                                    @foreach($news as $key => $v_news)
                                                                    <option {{$element->element_id == $v_news->id?'selected':'' }} value="{{$v_news->id}}">ttt[uuu]</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @elseif($element->type == 'dNewsCategory')
                                                        <div class="col-lg-6">
                                                            <div class="primary_input mb-15">
                                                                <label class="primary_input_label" for="">
                                                                    @lang('student.category') 
                                                                    <span class="text-danger"> *</span></label>
                                                                <select name="news_category" class="primary_select optionPopup">
                                                                    @foreach($news_categories as $key => $news_category)
                                                                    <option {{$element->element_id == $news_category->id?'selected':'' }} value="{{$news_category->id}}">{{$news_category->category_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-xl-12">
                                                            <div class="primary_input">
                                                                <label class="primary_input_label" for="">
                                                                    @lang('front_settings.show')
                                                                </label>
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="primary_input custom-transfer-account">
                                                                                <input type="radio" name="content_show" id="cont_show{{$element->id}}" value="1" {{$element->show == 1?'checked':''}} class="common-checkbox">
                                                                                <label for="cont_show{{$element->id}}">
                                                                                    @lang('front_settings.left')
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="primary_input custom-transfer-account">
                                                                                <input type="radio" name="content_show" id="cont_show2{{$element->id}}" value="0" {{$element->show == 0?'checked':''}} class="common-checkbox">
                                                                                <label for="cont_show2{{$element->id}}">
                                                                                    @lang('front_settings.right')
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12 mt-30">
                                                            <div class="primary_input">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <input type="checkbox" name="is_newtab" id="is_newtab{{$element->id}}" class="common-checkbox form-control" value="1" {{$element->is_newtab == 1? 'checked':''}}>
                                                                        <label for="is_newtab{{$element->id}}">
                                                                            @lang('front_settings.open_in_a_new_tab')
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 text-center">
                                                            <div class="d-flex justify-content-center pt_20">
                                                                @if(env('APP_SYNC')==TRUE)
                                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo ">
                                                                        <button class="primary-btn fix-gr-bg" style="pointer-events: none;" type="button" > @lang('front_settings.update')</button>
                                                                    </span>
                                                                @else
                                                                    @if(userPermission("element-update"))
                                                                    <button type="submit" class="primary-btn fix-gr-bg">
                                                                        <i class="ti-check"></i>
                                                                        @lang('front_settings.update')
                                                                    </button>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <ol class="dd-list">
                                    @foreach($element->childs as $key => $element)
                                    <li class="dd-item" data-id="{{$element->id}}">
                                        <div class="card accordion_card" id="accordion_{{$element->id}}">
                                            <div class="card-header item_header" id="heading_{{$element->id}}">
                                                <div class="dd-handle">
                                                    <div class="pull-left">
                                                        @lang('front_settings.title') : {{$element->title}}
                                                    </div>
                                                </div>
                                                <div class="pull-right btn_div">
                                                    @if(userPermission("element-update"))
                                                        <a href="javascript:void(0);" onclick="" data-toggle="collapse" data-target="#collapse_{{$element->id}}" aria-expanded="false" aria-controls="collapse_{{$element->id}}" class="primary-btn btn_zindex panel-title">@lang('common.edit') <span class="collapge_arrow_normal"></span></a>
                                                    @endif
                                                    @if(env('APP_SYNC')==TRUE)
                                                        <a href="javascript:void(0);" class="primary-btn btn_zindex" title="Disable For Demo" data-toggle="tooltip">
                                                            <i class="ti-close"></i>
                                                        </a>
                                                    @else
                                                        @if(userPermission("delete-element"))
                                                            <a href="javascript:void(0);" onclick="elementDelete({{$element->id}})" class="primary-btn btn_zindex"><i class="ti-close"></i></a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div id="collapse_{{$element->id}}" class="collapse" aria-labelledby="heading_{{$element->id}}" data-parent="#accordion_{{$element->id}}">
                                                <div class="card-body">
                                                    <form enctype="multipart/form-data" id="elementEditForm">
                                                        <div class="row">
                                                            <input type="hidden" name="id" value="{{$element->id}}">
                                                                <input type="hidden" name="type" value="{{$element->type}}">
                                                                <div class="col-lg-6">
                                                                    <div class="primary_input mb-25">
                                                                        <label class="primary_input_label" for="title">
                                                                            @lang('front_settings.navigation_label') 
                                                                            <span class="text-danger"> *</span>
                                                                        </label>
                                                                        <input class="primary_input_field form-control title" type="text" name="title" autocomplete="off" value="{{$element->title}}">
                                                                    </div>
                                                                </div>
                                                                @if($element->type == 'customLink')
                                                                <div class="col-lg-6">
                                                                    <div class="primary_input mb-25">
                                                                        <label class="primary_input_label" for="link">
                                                                            @lang('front_settings.link')
                                                                        </label>
                                                                        <input class="primary_input_field form-control link" type="text" name="link" autocomplete="off" value="{{$element->link}}"  placeholder="Link">
                                                                    </div>
                                                                </div>
                                                                @elseif($element->type == 'dPages')
                                                                <div class="col-lg-6">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="">
                                                                            @lang('front_settings.pages') 
                                                                            <span class="text-danger"> *</span>
                                                                        </label>
                                                                        <select name="page" class="primary_select optionPopup">
                                                                            @foreach($pages as $key => $page)
                                                                            <option {{$element->element_id == $page->id?'selected':'' }} value="{{$page->id}}">{{$page->title}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="text-danger"></span>
                                                                    </div>
                                                                </div>
                                                                @elseif($element->type == 'sPages')
                                                                <div class="col-lg-6">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="">
                                                                            @lang('front_settings.pages') 
                                                                            <span class="text-danger"> *</span>
                                                                        </label>
                                                                        <select name="static_pages" class="primary_select optionPopup">
                                                                            @foreach($static_pages as $key => $static_page)
                                                                            <option {{$element->element_id == $static_page->id?'selected':'' }} value="{{$static_page->id}}">{{$static_page->title}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @elseif($element->type == 'dNews')
                                                                <div class="col-lg-6">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="">
                                                                            @lang('front_settings.news') 
                                                                            <span class="text-danger"> *</span>
                                                                        </label>
                                                                        <select name="news" class="primary_select optionPopup">
                                                                            @foreach($news as $key => $v_news)
                                                                            <option {{$element->element_id == $v_news->id?'selected':'' }} value="{{$v_news->id}}">ttt[uuu]</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @elseif($element->type == 'dNewsCategory')
                                                                <div class="col-lg-6">
                                                                    <div class="primary_input mb-15">
                                                                        <label class="primary_input_label" for="">
                                                                            @lang('student.category') 
                                                                            <span class="text-danger"> *</span></label>
                                                                        <select name="news_category" class="primary_select optionPopup">
                                                                            @foreach($news_categories as $key => $news_category)
                                                                            <option {{$element->element_id == $news_category->id?'selected':'' }} value="{{$news_category->id}}">{{$news_category->category_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                <div class="col-xl-12">
                                                                    <div class="primary_input">
                                                                        <label class="primary_input_label" for="">
                                                                            @lang('front_settings.show')
                                                                        </label>
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="primary_input custom-transfer-account">
                                                                                        <input type="radio" name="content_show" id="cont_show{{$element->id}}" value="1" {{$element->show == 1?'checked':''}} class="common-checkbox">
                                                                                        <label for="cont_show{{$element->id}}">
                                                                                            @lang('front_settings.left')
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="primary_input custom-transfer-account">
                                                                                        <input type="radio" name="content_show" id="cont_show2{{$element->id}}" value="0" {{$element->show == 0?'checked':''}} class="common-checkbox">
                                                                                        <label for="cont_show2{{$element->id}}">
                                                                                            @lang('front_settings.right')
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-12 mt-30">
                                                                    <div class="primary_input">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <input type="checkbox" name="is_newtab" id="is_newtab{{$element->id}}" class="common-checkbox form-control" value="1" {{$element->is_newtab == 1? 'checked':''}}>
                                                                                <label for="is_newtab{{$element->id}}">
                                                                                    @lang('front_settings.open_in_a_new_tab')
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 text-center">
                                                                    <div class="d-flex justify-content-center pt_20">
                                                                        @if(env('APP_SYNC')==TRUE)
                                                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo ">
                                                                                <button class="primary-btn fix-gr-bg" style="pointer-events: none;" type="button" > @lang('front_settings.update')</button>
                                                                            </span>
                                                                        @else
                                                                            @if(userPermission("element-update"))
                                                                            <button type="submit" class="primary-btn fix-gr-bg">
                                                                                <i class="ti-check"></i>
                                                                                @lang('front_settings.update')
                                                                            </button>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                    <ol class="dd-list">
                                        @foreach($element->childs as $key => $element)
                                        <li class="dd-item" data-id="{{$element->id}}">
                                            <div class="card accordion_card" id="accordion_{{$element->id}}">
                                                <div class="card-header item_header" id="heading_{{$element->id}}">
                                                    <div class="dd-handle">
                                                        <div class="pull-left">
                                                            @lang('front_settings.title') : {{$element->title}}
                                                        </div>
                                                    </div>
                                                    <div class="pull-right btn_div">
                                                        @if(userPermission("element-update"))
                                                            <a href="javascript:void(0);" onclick="" data-toggle="collapse" data-target="#collapse_{{$element->id}}" aria-expanded="false" aria-controls="collapse_{{$element->id}}" class="primary-btn btn_zindex panel-title">@lang('common.edit') <span class="collapge_arrow_normal"></span></a>
                                                        @endif
                                                        @if(env('APP_SYNC')==TRUE)
                                                            <a href="javascript:void(0);" class="primary-btn btn_zindex" title="Disable For Demo" data-toggle="tooltip">
                                                                <i class="ti-close"></i>
                                                            </a>
                                                        @else
                                                            @if(userPermission("delete-element"))
                                                                <a href="javascript:void(0);" onclick="elementDelete({{$element->id}})" class="primary-btn btn_zindex"><i class="ti-close"></i></a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div id="collapse_{{$element->id}}" class="collapse" aria-labelledby="heading_{{$element->id}}" data-parent="#accordion_{{$element->id}}">
                                                    <div class="card-body">
                                                        <form enctype="multipart/form-data" id="elementEditForm">
                                                            <div class="row">
                                                                <input type="hidden" name="id" value="{{$element->id}}">
                                                                    <input type="hidden" name="type" value="{{$element->type}}">
                                                                    <div class="col-lg-6">
                                                                        <div class="primary_input mb-25">
                                                                            <label class="primary_input_label" for="title">
                                                                                @lang('front_settings.navigation_label') 
                                                                                <span class="text-danger"> *</span>
                                                                            </label>
                                                                            <input class="primary_input_field form-control title" type="text" name="title" autocomplete="off" value="{{$element->title}}">
                                                                        </div>
                                                                    </div>
                                                                    @if($element->type == 'customLink')
                                                                    <div class="col-lg-6">
                                                                        <div class="primary_input mb-25">
                                                                            <label class="primary_input_label" for="link">
                                                                                @lang('front_settings.link')
                                                                            </label>
                                                                            <input class="primary_input_field form-control link" type="text" name="link" autocomplete="off" value="{{$element->link}}"  placeholder="Link">
                                                                        </div>
                                                                    </div>
                                                                    @elseif($element->type == 'dPages')
                                                                    <div class="col-lg-6">
                                                                        <div class="primary_input mb-15">
                                                                            <label class="primary_input_label" for="">
                                                                                @lang('front_settings.pages') 
                                                                                <span class="text-danger"> *</span>
                                                                            </label>
                                                                            <select name="page" class="primary_select optionPopup">
                                                                                @foreach($pages as $key => $page)
                                                                                <option {{$element->element_id == $page->id?'selected':'' }} value="{{$page->id}}">{{$page->title}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="text-danger"></span>
                                                                        </div>
                                                                    </div>
                                                                    @elseif($element->type == 'sPages')
                                                                    <div class="col-lg-6">
                                                                        <div class="primary_input mb-15">
                                                                            <label class="primary_input_label" for="">
                                                                                @lang('front_settings.pages') 
                                                                                <span class="text-danger"> *</span>
                                                                            </label>
                                                                            <select name="static_pages" class="primary_select optionPopup">
                                                                                @foreach($static_pages as $key => $static_page)
                                                                                <option {{$element->element_id == $static_page->id?'selected':'' }} value="{{$static_page->id}}">{{$static_page->title}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @elseif($element->type == 'dNews')
                                                                    <div class="col-lg-6">
                                                                        <div class="primary_input mb-15">
                                                                            <label class="primary_input_label" for="">
                                                                                @lang('front_settings.news') 
                                                                                <span class="text-danger"> *</span>
                                                                            </label>
                                                                            <select name="news" class="primary_select optionPopup">
                                                                                @foreach($news as $key => $v_news)
                                                                                <option {{$element->element_id == $v_news->id?'selected':'' }} value="{{$v_news->id}}">ttt[uuu]</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @elseif($element->type == 'dNewsCategory')
                                                                    <div class="col-lg-6">
                                                                        <div class="primary_input mb-15">
                                                                            <label class="primary_input_label" for="">
                                                                                @lang('student.category') 
                                                                                <span class="text-danger"> *</span></label>
                                                                            <select name="news_category" class="primary_select optionPopup">
                                                                                @foreach($news_categories as $key => $news_category)
                                                                                <option {{$element->element_id == $news_category->id?'selected':'' }} value="{{$news_category->id}}">{{$news_category->category_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    <div class="col-xl-12">
                                                                        <div class="primary_input">
                                                                            <label class="primary_input_label" for="">
                                                                                @lang('front_settings.show')
                                                                            </label>
                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="primary_input custom-transfer-account">
                                                                                            <input type="radio" name="content_show" id="cont_show{{$element->id}}" value="1" {{$element->show == 1?'checked':''}} class="common-checkbox">
                                                                                            <label for="cont_show{{$element->id}}">
                                                                                                @lang('front_settings.left')
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-6">
                                                                                        <div class="primary_input custom-transfer-account">
                                                                                            <input type="radio" name="content_show" id="cont_show2{{$element->id}}" value="0" {{$element->show == 0?'checked':''}} class="common-checkbox">
                                                                                            <label for="cont_show2{{$element->id}}">
                                                                                                @lang('front_settings.right')
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-12 mt-30">
                                                                        <div class="primary_input">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <input type="checkbox" name="is_newtab" id="is_newtab{{$element->id}}" class="common-checkbox form-control" value="1" {{$element->is_newtab == 1? 'checked':''}}>
                                                                                    <label for="is_newtab{{$element->id}}">
                                                                                        @lang('front_settings.open_in_a_new_tab')
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 text-center">
                                                                        <div class="d-flex justify-content-center pt_20">
                                                                            @if(env('APP_SYNC')==TRUE)
                                                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo ">
                                                                                    <button class="primary-btn fix-gr-bg" style="pointer-events: none;" type="button" > @lang('front_settings.update')</button>
                                                                                </span>
                                                                            @else
                                                                                @if(userPermission("element-update"))
                                                                                <button type="submit" class="primary-btn fix-gr-bg">
                                                                                    <i class="ti-check"></i>
                                                                                    @lang('front_settings.update')
                                                                                </button>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ol>
                                </ol>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center">
            @lang('front_settings.not_found_data')
        </div>
    </div>
@endif