@if(!$level)
<div class="card">
    <div class="card-header">
        <h2 class="mb-0">
            <div class="btn hideArrow  d-flex align-items-center gap_10" type="button">
                <div class="flex-fill">
                    <h5>Online exam Level {{ $level }} </h5>
                    <span>02 Lectures</span>
                </div>
                <div class="card_btns">
                    <a href="#" class="primary-btn small fix-gr-bg text-nowrap">Download</a>
                </div>

            </div>
        </h2>
    </div>
</div>
@else
    <div class="single_video_menu_list d-flex align-items-center">
        <div class="d-flex align-items-start flex-fill flex-column justify-content-center">
            <label class="primary_vcheckbox d-flex flex-fill">
                <input type="checkbox">
                <span class="checkmark mr_15"></span>
                <span class="label_name">  <i
                            class="ti-control-play"></i> <span>Online exam Level {{  $level }}</span></span>
            </label>
            <span class="sub_text">subtext</span>
        </div>

        <p class="video_time  m-0 text-nowrap">50 Min</p>
    </div>
@endif