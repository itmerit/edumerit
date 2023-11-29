

<div class="container-fluid mt-30">
    <div class="student-details">
        <div class="student-meta-box">
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="content_info">

                       
                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('study.content_title')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($ContentDetails))        
                                        {{@$ContentDetails->content_title != ""? $ContentDetails->content_title:''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('study.content_type')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($ContentDetails))
                                        @if ($ContentDetails->content_type == "sy")
                                            @lang('study.syllabus')
                                        @elseif($ContentDetails->content_type == "as")
                                            @lang('study.assignment')
                                        @else
                                            @lang('study.other_downloads')
                                        @endif        
                                        
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('study.upload_date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($ContentDetails))            
                                        {{@$ContentDetails->upload_date != ""? dateConvert(@$ContentDetails->upload_date):''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('study.created_by')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                   @if(isset($ContentDetails))
                                   {{@$ContentDetails->users->full_name}}
                                   @endif
                               </div>
                           </div>
                       </div>
                   </div>

                   <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @lang('study.available_for')
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(isset($ContentDetails))
                                    @if ($ContentDetails->available_for_admin == 1)
                                        <p>@lang('study.all_admins')</p>
                                    @endif
                                    @if ($ContentDetails->available_for_all_classes == 1)
                                        <p>@lang('study.all_classes')</p>
                                    @endif
                                    @if ($ContentDetails->class != "")
                                        <p>@lang('common.class'): {{$ContentDetails->classes->class_name}}</p>
                                    @endif
                                    @if ($ContentDetails->section != "")
                                        <p>@lang('common.section'): {{$ContentDetails->sections->section_name}}</p>
                                    @endif

                                    @if ($ContentDetails->section ==null)
                                    <p>@lang('common.section'): All Sections</p>
                                @endif

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(@$ContentDetails->source_url != "")
                <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @lang('study.source_url') 
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(isset($ContentDetails))
                                    @if(@$ContentDetails->source_url != "")
                                        @if (moduleStatusCheck('VideoWatch')== TRUE)
                                                <a class="primary-btn small fix-gr-bg" target="_blank" href="{{url('videowatch/view/'.$ContentDetails->id)}}">@lang('study.click_here')</a>
                                        @else
                                            <a class="primary-btn small fix-gr-bg" target="_blank" href="{{@$ContentDetails->source_url}}">@lang('study.click_here')</a>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('common.attach_file') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                          
                            @if(@$ContentDetails->upload_file != "")
                            @php
                                    $files_ext=pathinfo($ContentDetails->upload_file, PATHINFO_EXTENSION);
                            @endphp
                       
                             @if ($files_ext=='jpg' || $files_ext=='jpeg' || $files_ext=='heic' || $files_ext=='png'|| $files_ext=='mp4'|| $files_ext=='mp3'|| $files_ext=='mov'|| $files_ext=='pdf')
                                <a class="dropdown-item viewSubmitedHomework" id="show_files" href="#"> <span class="pl ti-download"></span></a>
                      
                                    
                                @else 
                                    <a href="{{url(@$ContentDetails->upload_file)}}" download> @lang('common.download')  <span class="pl ti-download"></span>
                                @endif
                             </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('common.description')  
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($ContentDetails))
                            {{@$ContentDetails->description}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="file-preview" style="display: none">
            @php
            $std_file =$ContentDetails->upload_file;
            $ext =strtolower(str_replace('"]','',pathinfo($std_file, PATHINFO_EXTENSION)));
            $attached_file=str_replace('"]','',$std_file);
            $attached_file=str_replace('["','',$attached_file);
            $preview_files=['jpg','jpeg','png','heic','mp4','mov','mp3','mp4','pdf'];
           
        @endphp

            @if ($ext=='jpg' || $ext=='jpeg' || $ext=='png' || $ext=='heic')
                <img class="img-responsive mt-20" style="width: 100%; height:auto" src="{{asset($attached_file)}}">
                @elseif($ext=='mp4' || $ext=='mov')
                <video class="mt-20 video_play" width="100%"  controls>
                    <source src="{{asset($attached_file)}}" type="video/mp4">
                    <source src="mov_bbb.ogg" type="video/ogg">
                    Your browser does not support HTML video.
                </video>
            @elseif($ext=='mp3')
            <audio class="mt-20 audio_play" controls  style="width: 100%">
                <source src="{{asset($attached_file)}}" type="audio/ogg">
                <source src="horse.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
            </audio>

            @elseif($ext=='pdf')
            {{-- <embed src="{{asset($attached_file)}}#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600px" /> --}}
            
            <object data='{{asset($attached_file)}}' type="application/pdf" width="100%" height="800">
    
                <iframe src='{{asset($attached_file)}}' width="100%"height="800">
                    <p>This browser does not support PDF!</p>
                </iframe>
    
            </object>
            @endif
            @if (!in_array($ext,$preview_files))
                {{-- <h3 class="text-warning">{{$ext}} File Not Previewable</h3> --}}
                <div class="alert alert-warning">
                    {{$ext}} File Not Previewable</a>.
                </div>
            @endif
            <div class="mt-40 d-flex justify-content-between">
                {{-- <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button> --}}
                @php
                    $set_filename=time().'_'.$std_file;
                @endphp
                <a class="primary-btn tr-bg" download="{{$set_filename}}" href="{{asset($attached_file)}}"> <span class="pl ti-download"> @lang('common.download')</span></a> 
                {{-- {{route('download-uploaded-content-admin',$uploadedContent->id)}} --}}
        </div>
        <hr>
        </div>
        </div>
    </div>
</div>

</div>
</div>
</div>
<script>
      $('#show_files').on('click', function() {
        $('.file-preview').show();
        $('.content_info').hide();
    });
</script>
<script type="text/javascript">
    jQuery('.has-modal').on('hidden.bs.modal', function (e) {
    //   console.log('closed');
    //   jQuery('#viewSubmitedHomework video').attr("src", jQuery("#viewSubmitedHomework  video").attr("src"));
      $('.video_play').get(0).play();
      $('.video_play').trigger('pause');
      
      $('.audio_play').get(0).play();
      $('.audio_play').trigger('pause');
    });
    </script>
