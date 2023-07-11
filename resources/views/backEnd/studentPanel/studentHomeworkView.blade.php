<div class="container-fluid mt-30">
    <div class="student-details">
        <div class="student-meta-box">
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                    <div class="homework_info">
                        <div class="col-lg-12">
                            <div class="row">
 
                             <h4 class="stu-sub-head">@lang('homework.home_work_summary')</h4>
 
                         </div>
                     </div> 
                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('homework.homework_date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($homeworkDetails))        
                                        {{@$homeworkDetails->homework_date != ""? dateConvert(@$homeworkDetails->homework_date):''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('homework.submission_date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($homeworkDetails))            
                                        {{@$homeworkDetails->submission_date != ""? dateConvert(@$homeworkDetails->submission_date):''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('homework.evaluation_date') 
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(@$homeworkDetails->evaluation_date != "")     
                                        {{@$homeworkDetails->evaluation_date != ""? dateConvert(@$homeworkDetails->evaluation_date):''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('homework.created_by')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                   @if(isset($homeworkDetails))
                                   {{@$homeworkDetails->users->full_name}}
                                   @endif
                               </div>
                           </div>
                       </div>
                   </div>

                   <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @lang('homework.evaluated_by')
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(isset($homeworkDetails))
                                {{@$homeworkDetails->evaluatedBy->full_name}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @if(moduleStatusCheck('University'))
                                    @lang('university::un.semester_label') 
                                @else 
                                    @lang('common.class') 
                                @endif 
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="name">
                                @if(moduleStatusCheck('University'))
                                {{@$homeworkDetails->semesterLabel->name}} ({{@$homeworkDetails->unAcademic->name}})
                                @else
                               {{@$homeworkDetails->classes->class_name}}
                               @endif
                           </div>
                       </div>
                   </div>
               </div>

               <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @if(moduleStatusCheck('University'))
                                        @lang('university::un.department') 
                                @else
                                    @lang('common.section')
                                @endif 
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(moduleStatusCheck('University'))
                                {{@$homeworkDetails->unDepartment->name}}
                                @else
                                {{@$homeworkDetails->sections->section_name}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('common.subject') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{@$homeworkDetails->subjects->subject_name}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('exam.marks') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            {{@$homeworkDetails->marks}}
                        </div>
                    </div>
                </div>
            </div>
            @php
             $student_detail = App\SmStudent::where('user_id', Auth::user()->id)->first();
             $student_result = $student_detail->homeworks->where('homework_id', $homeworkDetails->id)->first();
            @endphp
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('homework.obtained_marks') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            {{@$student_result != ""? @$student_result->marks:''}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('common.attach_file') 
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="name">
                            @php
                                 $files_ext=pathinfo($homeworkDetails->file, PATHINFO_EXTENSION);
                            @endphp
                            @if(@$homeworkDetails->file != "")
                            @if ($files_ext=='jpg' || $files_ext=='jpeg' || $files_ext=='heic' || $files_ext=='png'|| $files_ext=='mp4'|| $files_ext=='mp3'|| $files_ext=='mov'|| $files_ext=='pdf')
                            <a class="dropdown-item viewSubmitedHomework" id="show_files"
                                href="#"> <span class="pl ti-download"></span></a>
                            {{-- <a class="dropdown-item viewSubmitedHomework" data-toggle="modal" data-target="#viewSubmitedHomework"
                                href="#"> <span class="pl ti-download"></span></a> --}}
                            @else
                                <a href="{{url($homeworkDetails->file)}}" download>
                                    @lang('common.download')  <span class="pl ti-download"></span>
                                </a>
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
                            @lang('common.description')  
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{@$homeworkDetails->description}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="file-preview" style="display: none">
                @php
                $std_file =$homeworkDetails->file;
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


<script type="text/javascript">


    // for evaluation date
    $('#evaluation_date_icon').on('click', function() {
        $('#evaluation_date').focus();
    });
    $('#show_files').on('click', function() {
        $('.file-preview').show();
        $('.homework_info').hide();
    });

    $('.primary_input_field.date').datepicker({
        autoclose: true
    });

    $('.primary_input_field.date').on('changeDate', function(ev) {
        $(this).focus();
    });

</script>
@push('script')
<script type="text/javascript">
    jQuery('.admin_view_modal').on('hidden.bs.modal', function (e) {
    //   console.log('closed');
    //   jQuery('#viewSubmitedHomework video').attr("src", jQuery("#viewSubmitedHomework  video").attr("src"));
      $('.video_play').get(0).play();
      $('.video_play').trigger('pause');
      
      $('.audio_play').get(0).play();
      $('.audio_play').trigger('pause');
    });
    </script>
@endpush
