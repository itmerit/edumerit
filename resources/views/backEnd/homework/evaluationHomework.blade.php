@extends('backEnd.master')
@section('title')
@lang('homework.evaluation')
@endsection
@push('css')
    <style>
        .single-meta table th,.single-meta table td {
            vertical-align: middle;
        }

        .single-meta table tbody tr:hover {
            background: #fbfcff !important;
        }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('homework.evaluation')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('homework.home_work')</a>
                <a href="#">@lang('homework.homework_list')</a>
                <a href="#">@lang('homework.evaluation')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
<div class="container-fluid mt-30">
    <div class="row">
        <div class="student-details">
            <div class="student-meta-box">
                <div class="single-meta">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save-homework-evaluation-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-9 col-md-9">
                            <div class="white-box">
                                <div class="table-responsive">
                                    <table id="" class="table table-condensed table-hover custome-radio-class" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('student.admission_no')</th>
                                                <th>@lang('student.student_name')</th>
                                                <th>@lang('homework.marks')</th>
                                                <th>@lang('homework.comments')</th>
                                                <th>@lang('homework.home_work_status')</th>
                                                <th>@lang('homework.download')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students as $value)

                                                @php
                                                    $submitted_student =  App\SmHomework::evaluationHomework($value->id, $homeworkDetails->id);
                                                    @$uploadedContent = App\SmHomework::uploadedContent(@$value->id, $homeworkDetails->id);
                                                    $file_paths=[];
                                                    foreach ($uploadedContent as $key => $files_row) {
                                                        $only_files=json_decode($files_row->file);
                                                        foreach ($only_files as $second_key => $upload_file_path) {
                                                            $file_paths[]= $upload_file_path;
                                                        }
                                                    }
                                                    $files_ext=[];
                                                    foreach ($file_paths as $key => $file) {
                                                        $files_ext[]=pathinfo($file, PATHINFO_EXTENSION);
                                                    }
                                                @endphp

                                                @if($submitted_student != "")
                                                    <tr>
                                                        <td>{{$submitted_student->studentInfo ?$submitted_student->studentInfo->admission_no:null}}</td>
                                                        <td>{{$submitted_student->studentInfo ?$submitted_student->studentInfo->full_name:''}}</td>
                                                        <td>
                                                            <div class="primary_input">
                                                                @if ($submitted_student->marks>0)
                                                                <input class="primary_input_field form-control" min="0" max="{{$homeworkDetails->marks}}" type="number" step="0.01" name="marks[]" value="{{$submitted_student->marks}}">
                                                                
                                                                <label></label>
                                                                @if ($errors->has('marks'))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first('marks') }}
                                                                </span>
                                                                @endif
                                                                @else
                                                                <input class="primary_input_field form-control" min="0" max="{{$homeworkDetails->marks}}" type="number" step="0.01" name="marks[]" value="{{$submitted_student->marks}}">
                                                                
                                                                <label class="primary_input_label" for="">@lang('homework.marks')</label>
                                                                @if ($errors->has('marks'))
                                                                <span class="text-danger" >
                                                                    {{ $errors->first('marks') }}
                                                                </span>
                                                                @endif
                                                                @endif 
                                                            </div>
                                                            <input type="hidden" name="student_id[]" value="{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}">
                                                            <input type="hidden" name="homework_id" value="{{$homework_id}}">
                                                            
                                                        </td>
                                                        <td>
                                                            
                                                            <div class="d-flex flex-column">
                                                                <div class="mr-30">
                                                                    <input type="radio" id="buttonG{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}" class="common-radio" name="teacher_comments[{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}]" value="G" {{$submitted_student->teacher_comments == "G"? 'checked':''}} > &nbsp; &nbsp; &nbsp; &nbsp;<label for="buttonG{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}">@lang('homework.good')</label> &nbsp; &nbsp;
                                                                </div>
                                                                <div class="mr-30">
                                                                    <input type="radio" id="buttonNG{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}" class="common-radio" name="teacher_comments[{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}]" value="NG" {{$submitted_student->teacher_comments == "NG"? 'checked':''}}> &nbsp; &nbsp;<label class="nowrap" for="buttonNG{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}">@lang('homework.not_good')</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <div class="mr-30">
                                                                    <input type="radio" id="buttonC{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}" class="common-radio" name="homework_status[{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}]" value="C" {{$submitted_student->complete_status == "C"? 'checked':''}} checked> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<label for="buttonC{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}">@lang('homework.completed')</label> &nbsp; &nbsp;
                                                                </div>
                                                                <div class="mr-30">
                                                                    <input type="radio" id="buttonNC{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}" class="common-radio" name="homework_status[{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}]" value="NC" {{$submitted_student->complete_status == "NC"? 'checked':''}}>&nbsp; &nbsp; <label class="nowrap" for="buttonNC{{$submitted_student->studentInfo?$submitted_student->studentInfo->id:''}}">@lang('homework.not_completed')</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            
                                                            {{-- href="{{route('downloadHomeWorkContent',[$homework_id,$value->id])}}" --}}
                                                            @if($uploadedContent->count() >0 )
                                                                
                                                                @if (in_array('jpg',$files_ext) || in_array('jpeg',$files_ext) || in_array('heic',$files_ext) || in_array('png',$files_ext) || in_array('mp4',$files_ext) || in_array('mp3',$files_ext) || in_array('mov',$files_ext) || in_array('pdf',$files_ext))
                                                                    
                                                                    <a class="dropdown-item viewSubmitedHomework" data-toggle="modal" data-target="#viewSubmitedHomework{{$value->id}}"
                                                                    href="#"> <span class="pl ti-download"></span></a>
                                                            
                                                                @else
                                                                <a class="dropdown-item " href="{{route('download-uploaded-content-admin',[$homeworkDetails->id,$value->id])}}">
                                                                        <span class="pl ti-download"></span>
                                                                </a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @else

                                                    <tr>
                                                        <td>{{$value->admission_no}}</td>
                                                        <td>{{$value->full_name}}</td>
                                                        <td>
                                                            <div class="primary_input">
                                                                <input class="primary_input_field form-control" type="number" min="0" step="0.01" max="{{@$homeworkDetails->marks}}" name="marks[]">
                                                                {{-- <label class="primary_input_label" for="">@lang('homework.marks')</label> --}}
                                                                @if ($errors->has('marks'))
                                                                    <span class="text-danger" >
                                                                        {{ $errors->first('marks') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <input type="hidden" name="student_id[]" value="{{$value->id}}">
                                                            <input type="hidden" name="homework_id" value="{{$homework_id}}">
                                                            
                                                        </td>
                                                        <td>
                                                            <div class="radio-btn-flex mb-1">
                                                                <input type="radio" id="buttonG{{$value->id}}" class="common-radio" name="teacher_comments[{{$value->id}}]" value="G" checked>
                                                                <label for="buttonG{{$value->id}}">@lang('homework.good')</label>
                                                            </div>
                                                            <div class="radio-btn-flex">
                                                                <input type="radio" id="buttonNG{{$value->id}}" class="common-radio" class="common-radio" name="teacher_comments[{{$value->id}}]" value="NG" checked>
                                                                <label class="nowrap" for="buttonNG{{$value->id}}">@lang('homework.not_good')</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="radio-btn-flex mb-1">
                                                                <input type="radio" id="buttonC{{$value->id}}" class="common-radio" name="homework_status[{{$value->id}}]" value="C">
                                                                <label for="buttonC{{$value->id}}">@lang('homework.completed')</label>
                                                            </div>
                                                            <div class="radio-btn-flex">
                                                                <input type="radio" id="buttonNC{{$value->id}}" class="common-radio" name="homework_status[{{$value->id}}]" value="NC" checked>
                                                                <label class="nowrap" for="buttonNC{{$value->id}}">@lang('homework.not_completed')</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($uploadedContent->count()>0 )
                                                                @if (in_array('jpg',$files_ext) || in_array('jpeg',$files_ext) || in_array('heic',$files_ext) || in_array('png',$files_ext) || in_array('mp4',$files_ext) || in_array('mp3',$files_ext) || in_array('mov',$files_ext) || in_array('pdf',$files_ext))
                                                                    <a class="dropdown-item viewSubmitedHomework" data-toggle="modal" data-target="#viewSubmitedHomework{{$value->id}}" href="#">
                                                                        <span class="pl ti-download"></span>
                                                                    </a>
                                                                @else
                                                                    <a class="dropdown-item " href="{{route('download-uploaded-content-admin',[$homeworkDetails->id,$value->id])}}">
                                                                        <span class="pl ti-download"></span>
                                                                    </a>
                                                                    {{-- {{route('download-uploaded-content-admin',$uploadedContent->id)}} --}}
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif

                                                @if($uploadedContent->count()>0 )
                                                    <div class="modal fade admin-query admin_view_modal" id="viewSubmitedHomework{{$value->id}}" >
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">@lang('homework.home_work_attach_file')</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                    
                                                                <div class="modal-body">
                                                                    @foreach ($file_paths as $file_key=> $std_file)
                                                                        @php
                                                                            $ext =strtolower(str_replace('"]','',pathinfo($std_file, PATHINFO_EXTENSION)));
                                                                            $attached_file=str_replace('"]','',$std_file);
                                                                            $attached_file=str_replace('["','',$attached_file);
                                                                            $preview_files=['jpg','jpeg','png','heic','mp4','mov','mp3','mp4','pdf'];
                                                                        
                                                                        @endphp

                                                                            @if ($ext=='jpg' || $ext=='jpeg' || $ext=='png' || $ext=='heic')
                                                                                <img class="img-responsive mt-20" style="max-width: 600px; height:auto" src="{{asset($attached_file)}}">
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
                                                                                    $set_filename=time().'_'.$file_key;
                                                                                @endphp
                                                                                <a class="primary-btn tr-bg" download="{{$set_filename}}" href="{{asset($attached_file)}}"> <span class="pl ti-download"> @lang('homework.download')</span></a> 
                                                                                {{-- {{route('download-uploaded-content-admin',$uploadedContent->id)}} --}}
                                                                        </div>
                                                                        <hr>
                                                                        @endforeach
                                                                        </div>
                                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
               
                            <div class="white-box mt-30">
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="">
                                            <span class="ti-check"></span>
                                            @lang('homework.save_evaluation')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                        <div class="col-lg-3 col-md-3">
                            <div class="white-box">
                        <div class="col-lg-12">
                            <div class="row">

                                <h4 class="stu-sub-head">@lang('homework.home_work_summary')</h4>

                            </div>
                        </div> 

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                        @lang('homework.home_work_date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($homeworkDetails))
                                    
                                    {{$homeworkDetails->homework_date != ""? dateConvert($homeworkDetails->homework_date):''}}

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
                                   
                                     {{$homeworkDetails->submission_date != ""? dateConvert($homeworkDetails->submission_date):''}}

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
                                  
                                        {{@$homeworkDetails->evaluation_date != ""? dateConvert($homeworkDetails->evaluation_date):''}}
              
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
                                   {{$homeworkDetails->users->full_name}}
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
                                 {{@$homeworkDetails->semesterlabel->name}} ({{ @$homeworkDetails->unAcademic->name}})
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
                                @lang('homework.subject')
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{$homeworkDetails->subjects->subject_name}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                                @lang('homework.marks')
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            
                            {{@$homeworkDetails->marks}}
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('homework.attach_file') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(@$homeworkDetails->file != "")
                                @php
                                    $files_ext=pathinfo($homeworkDetails->file, PATHINFO_EXTENSION);
                                @endphp
                                @if ($files_ext=='jpg' || $files_ext=='jpeg' || $files_ext=='heic' || $files_ext=='png'|| $files_ext=='mp4'|| $files_ext=='mp3'|| $files_ext=='mov'|| $files_ext=='pdf')
                                    <a class="dropdown-item viewSubmitedHomework" data-toggle="modal" data-target="#viewHomeworkFile" href="#"> 
                                        <span class="pl ti-download"></span>
                                    </a>
                                    
                                @else
                                    <a href="{{url(@$homeworkDetails->file)}}" download>
                                        @lang('homework.download') 
                                        <span class="pl ti-download"></span>
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
                                {{$homeworkDetails->description}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade admin-query admin_view_modal" id="viewHomeworkFile" >
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">@lang('homework.home_work_file')</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
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
                                    <object data='{{asset($attached_file)}}' type="application/pdf" width="100%" height="800">
                                        <iframe src='{{asset($attached_file)}}' width="100%"height="800">
                                            <p>This browser does not support PDF!</p>
                                        </iframe>
                                    </object>
                                @endif
                                @if (!in_array($ext,$preview_files))
                                    <div class="alert alert-warning">
                                        {{$ext}} File Not Previewable</a>.
                                    </div>
                                @endif
                                <div class="mt-40 d-flex justify-content-between">
                                        {{-- <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button> --}}
                                    @php
                                        $set_filename=time().'_'.$std_file;
                                    @endphp
                                    <a class="primary-btn tr-bg" download="{{$set_filename}}" href="{{asset($attached_file)}}"> <span class="pl ti-download"> @lang('homework.download')</span></a> 
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
    </div>
</section>


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
@endsection