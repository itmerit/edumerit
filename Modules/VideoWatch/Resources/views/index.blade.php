@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('study.syllabus_list') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('study.study_material')</a>
                <a href="#">@lang('study.watch')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
<input type="hidden" id="url" value="{{url('/')}}">
<input type="hidden" id="study_id" value="{{$ContentDetails->id}}">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">{{@$ContentDetails->content_title}} </h3>
                    </div>
                </div>
            </div>

            <div class="row">
            
                <div class="col-lg-12 text-right">
                    <button class="primary-btn fix-gr-bg" id="play"> <i class="ti-control-play"></i> Play</button>
                    <button class="primary-btn fix-gr-bg" id="pause"> <i class="fa fa-pause"></i> Pause</button>

                    @if (Auth::user()->role_id!=2)
                        
                        <button class="primary-btn fix-gr-bg" onclick ="maximizeVideo()"> <i class="fa fa-arrows-alt"></i> Full Screen</button>
                    @endif
                    <input type="number" hidden id="volume-input" class="" min="0" max="100">
                    <button class="primary-btn fix-gr-bg" id="mute-toggle"> <i class="fa fa-volume-up"></i></button>
                    <p class="d-none" id="current-time"> </p>
                    <p class="d-none" id="duration"></p>
                    <input class="d-none" type="text" id="progress-bar">
                    <hr>
                    <style>
                        #video-placeholder{
                            pointer-events: none;
                        }
                        iframe#video-placeholder {
                            width: 100%;
                            height: 600px;
                        }
                    </style>
                    
                                
                    
                            
                </div>
                <div class="col-12">
                    <div style="" id="video-placeholder"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@section('script')
<script src="https://www.youtube.com/iframe_api"></script>
    
<script>
        var url_string = "{{@$ContentDetails->source_url}}"; //window.location.href
        console.log(url_string);
        var url = new URL(url_string);
        var source_video_id = url.searchParams.get("v");
var player;

$('#pause').hide();
function onYouTubeIframeAPIReady() {

 
    player = new YT.Player('video-placeholder', {
        videoId: source_video_id,
        playerVars: {
            color: 'white'
        },
        events: {
            onReady: initialize
        }
    });
}

function initialize(){
    // Update the controls on load
    updateTimerDisplay();
    updateProgressBar();

    // Clear any old interval.
    clearInterval(time_update_interval);

    // Start interval to update elapsed time display and
    // the elapsed part of the progress bar every second.
    time_update_interval = setInterval(function () {
        updateTimerDisplay();
        updateProgressBar();
    }, 1000)

}

// This function is called by initialize()
function updateTimerDisplay(){
    // Update current time text display.
    $('#current-time').text(formatTime( player.getCurrentTime() ));
    $('#duration').text(formatTime( player.getDuration() ));
}

function formatTime(time){
    time = Math.round(time);

    var minutes = Math.floor(time / 60),
    seconds = time - minutes * 60;

    seconds = seconds < 10 ? '0' + seconds : seconds;

    return minutes + ":" + seconds;
}

$('#progress-bar').on('mouseup touchend', function (e) {

// Calculate the new time for the video.
// new time in seconds = total duration in seconds * ( value of range input / 100 )
var newTime = player.getDuration() * (e.target.value / 100);

// Skip video to new time.
player.seekTo(newTime);

});

// This function is called by initialize()
function updateProgressBar(){
// Update the value of our progress bar accordingly.
$('#progress-bar').val((player.getCurrentTime() / player.getDuration()) * 100);
}

$('#play').on('click', function () {

player.playVideo();
console.log('play');

        var user_id='{{Auth::user()->id}}';
        var study_id=$('#study_id').val();
    
        $.ajax({
            url : "{{url('videowatch/trace')}}",
            method : "GET",
            data : {
                user_id: user_id,
                study_id: study_id,
            },
            // success : function (result){
            //     console.log(result);
            //     toastr.success('Operation successful', 'Successful', {
            //         timeOut: 5000
            //     })
            // }
        })

        $('#play').hide();
        $('#pause').show();

});

$('#pause').on('click', function () {

player.pauseVideo();

$('#play').show();
$('#pause').hide();

});

$('#mute-toggle').on('click', function() {
    var mute_toggle = $(this);

    if(player.isMuted()){
        player.unMute();
        // mute_toggle.text('<i class="ti-volume"></i>');
    }
    else{
        player.mute();
        // mute_toggle.text('<i class="ti-volume"></i>');
    }
});
$('#volume-input').on('change', function () {
    player.setVolume($(this).val());
});
$('#mute-toggle').on('click', function(){
$(this).find('i').toggleClass('fa-volume-up fa-volume-off');
})
</script>


<script type ="text/javascript">

function maximizeVideo() {

        player.playVideo();
        $('#play').hide();
        $('#pause').show();
        var user_id='{{Auth::user()->id}}';
        var study_id=$('#study_id').val();
    
        $.ajax({
            url : "{{url('videowatch/trace')}}",
            method : "GET",
            data : {
                user_id: user_id,
                study_id: study_id,
            },
            success : function (result){
                console.log(result);
                // toastr.success('Operation successful', 'Successful', {
                //     timeOut: 5000
                // })
            }
        })
        var playerElement;

        playerElement = document.getElementById('video-placeholder')

        var requestFullScreen = playerElement.requestFullScreen || playerElement.mozRequestFullScreen || playerElement.webkitRequestFullScreen || playerElement.msRequestFullscreen;

        if (requestFullScreen) {

            requestFullScreen.bind(playerElement)();

        // $(theid).attr('src', $(theid).attr('src'));

        }

        else {

            alert('error');

        }

}


$(document).on('fullscreenchange mozfullscreenchange webkitfullscreenchange msfullscreenchange', function() {
    if (document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen || document.msFullscreenElement)
    {
        $(document).trigger('enterFullScreen');
        console.log('full');

        
        $("#video-placeholder").css("pointer-events", "auto");
    }
    else
    {
        $(document).trigger('leaveFullScreen');
        console.log('small');
        $("#video-placeholder").css("pointer-events", "none");
    }
});


</script>
@endsection
