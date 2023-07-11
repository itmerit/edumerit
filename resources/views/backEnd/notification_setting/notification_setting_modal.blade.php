
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/editor/summernote-bs4.css') }}">
<input type="hidden" id="id" value="{{ $id }}">
<input type="hidden" id="key" value="{{ $key }}">
<div class="container-fluid mt-30">
    <div class="row">
        <div class="col-lg-10 mb-20">
            <label> <strong>@lang('communicate.variables') :</strong> </label>
            <span class="text-primary">
                {{ $shortcode }}
            </span>
        </div>
        <div class="col-lg-12">
            <div class="primary_input">
                {{-- {{ Form::open(['class' => 'form-horizontal', 'route' => '', 'method' => 'PUT']) }} --}}
                <label class="primary_input_label" for="">@lang('system_settings.subject/title')</label>
                <input class="primary_input_field form-control" type="text" name="subject" id="subject"
                    value="{{ $subject }}">
                <div class="primary_input mt-20">
                    <label class="primary_input_label" for="">@lang('system_settings.email_body')</label>
                    <textarea class="primary_input_field summer_note form-control" id="email_body"
                        cols="0" rows="4" name="emailBody" >
                    {{ $emailBody }}
                </textarea>
                </div>
                <div class="primary_input mt-20">
                    <label class="primary_input_label" for="">@lang('system_settings.sms_text')</label>
                    <textarea class="primary_input_field form-control" id="sms_body"
                        cols="0" rows="4" name="smsBody" >
                    {{ $smsBody }}
                </textarea>
                </div>
                <div class="primary_input mt-20">
                    <label class="primary_input_label" for="">@lang('system_settings.web_text')</label>
                    <textarea class="primary_input_field form-control" id="app_body"
                        cols="0" rows="4" name="appBody" >
                    {{ $appBody }}
                </textarea>
                </div>
                <div class="primary_input mt-20">
                    <label class="primary_input_label" for="">@lang('system_settings.app_text')</label>
                    <textarea class="primary_input_field form-control"
                        cols="0" rows="4" name="webBody" id="web_body">
                    {{ $webBody }}
                </textarea>
                </div>
                <div class="row mt-40">
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="primary-btn fix-gr-bg text-nowrap updateNotificationModal" data-toggle="tooltip">
                            <span class="ti-check"></span>
                            @lang('common.update')
                        </button>
                    </div>
                </div>
                {{-- {{ Form::close() }} --}}
            </div>
        </div>
    </div>
</div>

<script src="{{asset('public/backEnd/')}}/vendors/editor/summernote-bs4.js"></script>
<script>
    $('.summer_note').summernote({
        placeholder: 'Write here',
        tabsize: 2,
        height: 400
    });
    </script>
