<script type="text/javascript" src="{{asset('public/backEnd/js/main.js')}}"></script>
<div class="container-fluid">
   {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'saveToDoData',
   'method' => 'POST', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return validateToDoForm()']) }}

   <div class="row">
    <div class="col-lg-12">
        <div class="row mt-25">
            <div class="col-lg-12" id="sibling_class_div">
                <div class="primary_input">
                    <input  class="primary_input_field form-control" type="text" name="todo_title" id="todo_title">
                    <label class="primary_input_label" for="">@lang('dashboard.to_do_title') <span></span> </label>
                    
                   <span class="modal_input_validation red_alert"></span>
                </div>
            </div>
        </div>

        <div class="row mt-30">
            <div class="col-lg-12" id="">
                <div class="no-gutters input-right-icon">
                    <div class="col">
                        <div class="primary_input">
                            <input class="read-only-input primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" id="startDate" type="text" autocomplete="off" readonly="true" name="date" value="{{date('m/d/Y')}}">
                            <label class="primary_input_label" for="">@lang('common.date') <span></span> </label>
                            @if ($errors->has('date'))
                                <span class="text-danger" >
                                    {{ $errors->first('date') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="" type="button">
                            <i class="ti-calendar" id="start-date-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <div class="col-lg-12 text-center">
        <div class="mt-40 d-flex justify-content-between">
            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
            <input class="primary-btn fix-gr-bg" type="submit" value="save">
        </div>
    </div>
</div>
{{ Form::close() }}
</div>

@include('backEnd.partials.date_picker_css_js')