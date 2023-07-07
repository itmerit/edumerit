<div class="card">
    <div class="card-header" id="headingOne">
        <h2 class="mb-0">
            <a href="#" class="btn" type="button" data-toggle="collapse"
               data-target="#collapseOne" aria-expanded="true"
               aria-controls="collapseOne">
                <h5>Section 1: Getting started <i class="fa fa-info-circle information_icon "></i></h5>
                <span>02 Lectures</span>
            </a>
        </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
         data-parent="#accordion_ex">
        <div class="card-body">
            <div class="video_menu_list">
                @include('lms.lesson', ['content' => false])
                @include('lms.lesson', ['content' => true])
                @include('lms.online_exam', ['level' => 1])
                <!-- 2nd level::end  -->
            </div>
        </div>
    </div>
</div>