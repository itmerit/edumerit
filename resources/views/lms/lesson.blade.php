<div class="single_video_menu_list d-flex align-items-center" @if($content) data-toggle="collapse"
     data-target="#collapseExample" @endif>
    <!-- <span class="ti-menu-alt subArrow_icon"></span> -->
    <label class="primary_vcheckbox d-flex flex-fill">
        <input type="checkbox">
        <span class="checkmark mr_15"></span>
        <span class="label_name">  <i
                    class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
    </label>

    <p class="video_time  m-0 text-nowrap">50 Min
        @if($content)
        <span class="ti-angle-down subArrow_collape_arrow"></span>
        @endif
    </p>
</div>

@if($content)
<div class="collapse second_card_body" id="collapseExample">
    <div class="single_video_menu_list d-flex align-items-center">
        <label class="primary_vcheckbox d-flex flex-fill">
            <input type="checkbox">
            <span class="checkmark mr_15"></span>
            <span class="label_name">  <i
                        class="ti-control-play"></i> <span>Learning from heatmaps</span></span>
        </label>
        <p class="video_time  m-0 text-nowrap">50 Min</p>
    </div>
    @include('lms.online_exam', ['level' => 2])
</div>
@endif

