<div class="white-box">


<div class="row">
    <div class="col-lg-12">
        <div id="accordion">
            <div class="card mt-10">
                <div class="card-header" id="pages">
                    <h5 class="mb-0 collapsed create-title" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <button class="btn btn-link cust-btn-link add_btn_link">
                            @lang('front_settings.pages')
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="pages" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">
                                        @lang('front_settings.pages') 
                                        <span class="text-danger"> *</span></label>
                                    <select name="page[]" id="dPages" multiple="multiple" class="multypol_check_select active mb-15">
                                        @foreach ($pages as $key => $page)
                                            <option value="{{ $page->id }}">{{ $page->title }}</option>
                                        @endforeach
                                    </select>
                                    <span id="elementError"></span>
                                    <div class="row mt-10">
                                        <div class="col-lg-8">
                                            @if(env('APP_SYNC')==TRUE)
                                                <button class="primary-btn fix-gr-bg submit_btn pull-right" title="Disable For Demo" data-toggle="tooltip" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                            @else
                                                @if(userPermission("add-element"))
                                                <button id="add_page_btn" type="submit" class="primary-btn fix-gr-bg submit_btn pull-right" data-toggle="tooltip" title="" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="staticPages">
                    <h5 class="mb-0 collapsed create-title" data-toggle="collapse" data-target="#pages2" aria-expanded="false" aria-controls="collapseThree">
                        <button class="btn btn-link cust-btn-link add_btn_link">
                            @lang('front_settings.static_pages')
                        </button>
                    </h5>
                </div>
                <div id="pages2" class="collapse" aria-labelledby="staticPages" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">@lang('front_settings.pages') 
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select name="static_pages[]" id="sPages" multiple="multiple" class="multypol_check_select active mb-15">
                                        @foreach ($static_pages as $key => $static_page)
                                            <option value="{{ $static_page->id }}">{{ $static_page->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="row mt-10">
                                        <div class="col-lg-8">
                                            @if(env('APP_SYNC')==TRUE)
                                                <button class="primary-btn fix-gr-bg submit_btn pull-right" title="Disable For Demo" data-toggle="tooltip" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                            @else
                                                @if(userPermission("add-element"))
                                                <button id="add_static_page_btn" type="submit" class="primary-btn fix-gr-bg submit_btn pull-right" data-toggle="tooltip" title="" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="course">
                    <h5 class="mb-0 collapsed create-title"  data-toggle="collapse" data-target="#pages6" aria-expanded="false" aria-controls="collapsePages">
                        <button class="btn btn-link cust-btn-link add_btn_link">
                            @lang('front_settings.course')
                        </button>
                    </h5>
                </div>
                <div id="pages6" class="collapse" aria-labelledby="course" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">
                                        @lang('front_settings.course')
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select name="course[]" id="dCourse" multiple="multiple" class="multypol_check_select active mb-15">
                                        @foreach ($courses as $key => $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="row mt-10">
                                        <div class="col-lg-8">
                                            @if(env('APP_SYNC')==TRUE)
                                                <button class="primary-btn fix-gr-bg submit_btn pull-right" title="Disable For Demo" data-toggle="tooltip" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                            @else
                                                @if(userPermission("add-element"))
                                                <button id="add_course_btn" type="submit" class="primary-btn fix-gr-bg submit_btn pull-right" data-toggle="tooltip" title="" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="courseCategory">
                    <h5 class="mb-0 collapsed create-title"  data-toggle="collapse" data-target="#pages7" aria-expanded="false" aria-controls="collapsePages">
                        <button class="btn btn-link cust-btn-link add_btn_link">
                            @lang('front_settings.course_category')
                        </button>
                    </h5>
                </div>
                <div id="pages7" class="collapse" aria-labelledby="courseCategory" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">
                                        @lang('student.category')
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select name="course_category[]" id="dCourseCategory" multiple="multiple" class="multypol_check_select active mb-15">
                                        @foreach ($courseCategories as $key => $courseCategory)
                                            <option value="{{ $courseCategory->id }}">{{ $courseCategory->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="row mt-10">
                                        <div class="col-lg-8">
                                            @if(env('APP_SYNC')==TRUE)
                                                <button class="primary-btn fix-gr-bg submit_btn pull-right" title="Disable For Demo" data-toggle="tooltip" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                            @else
                                                @if(userPermission("add-element"))
                                                <button id="add_course_category_btn" type="submit" class="primary-btn fix-gr-bg submit_btn pull-right" data-toggle="tooltip" title="" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="news">
                    <h5 class="mb-0 collapsed create-title"  data-toggle="collapse" data-target="#pages3" aria-expanded="false" aria-controls="collapsePages">
                        <button class="btn btn-link cust-btn-link add_btn_link">
                            @lang('front_settings.news')
                        </button>
                    </h5>
                </div>
                <div id="pages3" class="collapse" aria-labelledby="news" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">
                                        @lang('front_settings.news')
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select name="news[]" id="dNews" multiple="multiple" class="multypol_check_select active mb-15">
                                        @foreach ($news as $key => $v_news)
                                            <option value="{{ $v_news->id }}">{{ $v_news->news_title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="row mt-10">
                                        <div class="col-lg-8">
                                            @if(env('APP_SYNC')==TRUE)
                                                <button class="primary-btn fix-gr-bg submit_btn pull-right" title="Disable For Demo" data-toggle="tooltip" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                            @else
                                                @if(userPermission("add-element"))
                                                <button id="add_news_btn" type="submit" class="primary-btn fix-gr-bg submit_btn pull-right" data-toggle="tooltip" title="" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="newsCategory">
                    <h5 class="mb-0 collapsed create-title"  data-toggle="collapse" data-target="#pages4" aria-expanded="false" aria-controls="collapsePages">
                        <button class="btn btn-link cust-btn-link add_btn_link">
                            @lang('front_settings.news') @lang('student.category')
                        </button>
                    </h5>
                </div>
                <div id="pages4" class="collapse" aria-labelledby="newsCategory" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label" for="">
                                        @lang('student.category')
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select name="news_category[]" id="dNewsCategory" multiple="multiple" class="multypol_check_select active mb-15">
                                        @foreach ($news_categories as $key => $news_category)
                                            <option value="{{ $news_category->id }}">{{ $news_category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="row mt-10">
                                        <div class="col-lg-8">
                                            @if(env('APP_SYNC')==TRUE)
                                                <button class="primary-btn fix-gr-bg submit_btn pull-right" title="Disable For Demo" data-toggle="tooltip" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                            @else
                                                @if(userPermission("add-element"))
                                                <button id="add_news_category_btn" type="submit" class="primary-btn fix-gr-bg submit_btn pull-right" data-toggle="tooltip" title="" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    @lang('common.add_menu') 
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="customLink">
                    <h5 class="mb-0 collapsed create-title"  data-toggle="collapse" data-target="#pages5" aria-expanded="false" aria-controls="collapsePages">
                        <button class="btn btn-link cust-btn-link add_btn_link">
                            @lang('front_settings.custom_links')
                        </button>
                    </h5>
                </div>
                <div id="pages5" class="collapse" aria-labelledby="customLink" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class='primary_input'>
                                    <label class="primary_input_label" for="">@lang('front_settings.title')<span class="text-danger"> *</span></label>
                                    <input class='primary_input_field form-control' type='text' id="tTitle" name='title' autocomplete='off'>
                                    <span class='focus-border'></span>
                                    <span class="text-danger" id="titleError"></span>
                                </div>
                                
                            </div>
                            <div class="col-lg-12 mt-40 mb-30">
                                <div class='primary_input'>
                                    <label class="primary_input_label" for="">@lang('front_settings.link')</label>
                                    <input class='primary_input_field form-control' type='text' id="tLink" name='link' autocomplete='off'>
                                    <span class='focus-border'></span>
                                </div>
                                <span class="text-danger" id="linkError"></span>
                            </div>
                            <div class="col-lg-12 text-center mt-10">
                                @if(env('APP_SYNC')==TRUE)
                                    <button class="primary-btn fix-gr-bg submit_btn pull-right" title="Disable For Demo" data-toggle="tooltip" data-original-title="">
                                        <span class="ti-check"></span>
                                        @lang('common.add_menu') 
                                    </button>
                                @else
                                    @if(userPermission("add-element"))
                                    <button id="add_custom_link_btn" type="submit" class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip" title="" data-original-title="">
                                        <span class="ti-check"></span>
                                        @lang('common.add_menu') 
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>