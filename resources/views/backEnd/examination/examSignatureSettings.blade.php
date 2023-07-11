@extends('backEnd.master')
    @section('title')
        @lang('exam.exam_signature_settings')
    @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_signature_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.exam')</a>
                <a href="#">@lang('exam.exam_signature_settings')</a>
            </div>
        </div>
    </div>
</section>
<section class="mb-40 student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="javascript:void(0)" class="primary-btn small fix-gr-bg" id="addExam-Signature">
                    <span class="ti-plus pr-2"></span>
                    @lang('exam.add_signature')
                </a>
            </div>
        </div>
        <div class="row pt-20">
            <div class="col-lg-12 ">
                <p class="alert alert-warning mb-2 text-center">{{ __('exam.signature_tips') }}</p>
            </div>
            <div class="col-lg-12">
                @if($allSignature->count() > 0)
                    {{ Form::open(['route' => 'exam-signature-settings-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                @else
                    {{ Form::open(['route' => 'exam-signature-settings-store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                @endif
                <div class="white-box">
                        <div id="showExamSignature">
                            @foreach($allSignature as $key => $signatureData)
                                <div class="row mb-20 allDiv-Rm">
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label">@lang('common.title')<span class="text-danger"> *</span></label>
                                            <input type="text" name="exam_signature[{{$key}}][title]" class="primary_input_field form-control{{ @$errors->has('title') ? ' is-invalid' : '' }}" autocomplete="off" value="{{@$signatureData->title}}">
                                            @error('title')
                                                <span class="text-danger" >
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="primary_input">
                                            <label class="primary_input_label">@lang('exam.signature')<span></span></label>
                                            <div class="primary_file_uploader">
                                                    <input class="primary_input_field form-control {{ $errors->has('signature') ? ' is-invalid' : '' }} file-upload-multi-placeholder" readonly="true" type="text" id="placeholderInputUpdate{{$key}}"
                                                    placeholder="{{isset($signatureData->signature) && @$signatureData->signature != ""? getFilePath3(@$signatureData->signature):'Upload File'}}">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg" for="upload_update_file{{$key}}">@lang('common.browse')</label>
                                                    <input type="file" class="d-none form-control file-upload-multi" name="exam_signature[{{$key}}][signature]" id="upload_update_file{{$key}}">
                                                    <input type="hidden" name="exam_signature[{{$key}}][image_path]" value="{{@$signatureData->signature}}">
                                                </button>
                                            </div>
                                        </div>
                                        <code class="nowrap d-block mb-30">(Allow file jpg, png, jpeg, svg)</code>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="primary_input">
                                            <label class="primary_input_label">@lang('common.status')</label>
                                            <label class="switch_toggle mt-10" for="cck{{$key}}">
                                                <input type="checkbox" id="cck{{$key}}" name="exam_signature[{{$key}}][active_status]" class="student_show_btn" {{@$signatureData->active_status == 1 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="primary_input_label">@lang('common.delete')<span></span></label>
                                        <button class="primary-btn icon-only fix-gr-bg remove-ExamSignature" type="button">
                                            <span class="ti-trash" ></span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip">
                                    <span class="ti-check"></span>
                                    @if($allSignature->count() > 0)
                                        @lang('common.update')
                                    @else
                                        @lang('common.save')
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript">
    var totalData = {{$allSignature->count()}};
    $(document).ready(function(){
        $(document).on('click','#addExam-Signature', function(event) {
            event.preventDefault();
            $('#showExamSignature').append(
                `
                <div class="row mb-20 allDiv-Rm">
                    <div class="col-lg-4">
                        <div class="primary_input">
                            <label class="primary_input_label">@lang('common.title')<span class="text-danger"> *</span></label>
                            <input type="text" name="exam_signature[${totalData}][title]" class="primary_input_field form-control{{ @$errors->has('title') ? ' is-invalid' : '' }}" autocomplete="off">
                            @error('title')
                                <span class="text-danger" >
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="primary_input">
                            <label class="primary_input_label">@lang('exam.signature')<span></span></label>
                            <div class="primary_file_uploader">
                                    <input class="primary_input_field form-control {{ $errors->has('signature') ? ' is-invalid' : '' }} file-upload-multi-placeholder" readonly="true" type="text" id="placeholderInputUpdate${totalData}"
                                    placeholder="{{trans('exam.signature')}}">
                                <button class="" type="button">
                                    <label class="primary-btn small fix-gr-bg" for="upload_update_file${totalData}">@lang('common.browse')</label>
                                    <input type="file" class="d-none form-control file-upload-multi" data-id="${totalData}" name="exam_signature[${totalData}][signature]" id="upload_update_file${totalData}">
                                </button>
                            </div>
                        </div>
                        <code class="nowrap d-block mb-30">(Allow file jpg, png, jpeg, svg)</code>
                    </div>
                    <div class="col-lg-2">
                        <div class="primary_input">
                            <label class="primary_input_label">@lang('common.status')</label>
                            <label class="switch_toggle mt-10" for="cck${totalData}">
                                <input type="checkbox" id="cck${totalData}" name="exam_signature[${totalData}][active_status]" class="student_show_btn" {{@$signatureData->active_status == 1 ? 'checked' : ''}}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="primary_input_label">@lang('common.delete')<span></span></label>
                        <button class="primary-btn icon-only fix-gr-bg remove-ExamSignature" type="button">
                            <span class="ti-trash" ></span>
                        </button>
                    </div>
                </div>
                `
            );
            totalData += 1;
        });

        $(document).on('click', '.remove-ExamSignature', function () {
            $(this).parents('.allDiv-Rm').remove();
        });
    });

    $(document).on('change','.file-upload-multi',function(e){
        let fileName = e.target.files[0].name;
        $(this).parent().parent().find('.file-upload-multi-placeholder').attr('placeholder',fileName);
    });
</script>
@endpush
