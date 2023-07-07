@extends('backEnd.master')

@section('title') 
@lang('academics.optional_subject')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('academics.optional_subject')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('academics.academics')</a>
                <a href="#">@lang('academics.optional_subject')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12"> 
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'assign_optional_subject_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                          
                            @include('backEnd.common.search_criteria', [
                                'div'=>'col-lg-4',
                                'subject'=>true,
                                'visiable'=>['class', 'section', 'subject'], 
                                'required'=>['class', 'section', 'subject'], 
                                
                                ]) 
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>
 @if(isset($students))
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('academics.assign_optional_subject') - ({{ @$subject_info->subject_name }})  </h3>
                    </div>
                </div>
                <div class="col-lg-6 text-right">
                     
                </div>
            </div>
            <style>
            .all_check{
                background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
                color: #ffffff;
                background-size: 200% auto;
            }
            </style>
            <div class="row"> 
                <div class="col-lg-12">
                    <div class="white-box"> 
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'assign-optional-subject-store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'formid']) }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="assign-subject" id="assign-subject">
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        <input type="hidden" name="class_id" id="class_id" value="{{@$class_id}}">
                                        <input type="hidden" name="section_id" id="class_id" value="{{@$section_id}}">
                                        <input type="hidden" name="subject_id" id="" value="{{@$subject_id}}">
                                        <input type="hidden" name="update" value="1"> 
                                        <div class="table-responsive">
                                        
                                        <table id="" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><span class="all_check btn btn-sm fix-gr-bg" id="all_check" tyle="width: 143.715px; height: 143.715px; top: -48.611px; left: -26.5173px;" > Select All </span> </th> 
                                                    <th class="nowrap p-2" >@lang('student.admission_no').</th>
                                                    <th class="nowrap p-2">@lang('student.name')</th>
                                                    <th class="nowrap p-2">@lang('academics.optional_subject')</th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count=1; @endphp
                                                @foreach($students as $student)
                                                @php 
                                                    $subjects =  $student->studentDetail->subjectAssign->subject ?? null;
                                                @endphp 
                                                <tr>
                                                    <td>  
                                                        <div class="col-lg-12"> 
                                                            <div class="primary_input">
                                                                <input type="checkbox" id="optional_subject_{{@$count}}" class="common-checkbox optional_subject fix-gr-bg small" name="student_id[]" {{ (@$subjects->subject_name == @$subject_info->subject_name? 'checked': '' ) }} value="{{@$student->id}}">
                                                                <label for="optional_subject_{{@$count}}"> {{@$count++}} </label>
                                                            </div> 
                                                        </div> 
                                                    </td> 
                                                    <td>{{@$student->studentDetail->admission_no}}</td>
                                                    <td class="nowrap">{{@$student->studentDetail->full_name}}</td> 
                                                    <td>
                                                        <span class="" style="border-bottom: 2px dashed #ddd !important;">{{@$subject_info->subject_name}}</span>
                                                    </td>   
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table> 
                                        </div>
                                    </div>
                                </div>
                                @if(userPermission('assign-subject-store'))
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-save pr-2"></span>
                                        @lang('academics.assign_subject')
                                    </button>
                                </div>
                                @endif
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<script language="JavaScript">
function checkAll() {
    console.log("clicked");
        
        $('input:checkbox').prop('checked', this.checked);
} 


</script>
 

@endsection
