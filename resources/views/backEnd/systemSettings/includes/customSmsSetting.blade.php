@push('css')
    @if (!isset($editData))
        <style>
            .add_new_form {
                display: none;
            }

            .addFromBtn {
                display: block;
                width: max-content;
                margin-left: auto;
            }

            .closeFormBtn {
                display: none;
                width: max-content;
                margin-left: auto;
            }
        </style>
    @endif

    <style>
        .dt-buttons {
            display: none !important;
        }

        .primary_input_field~label {
            top: 5px;
        }

        .backBtn {
            width: max-content;
            margin-left: auto;
        }
    </style>

@endpush

<div role="tabpanel" class="tab-pane fade @if (Session::get('Custom_sms') == 'active') show active @endif " id="Custom_sms">
    <div class="white-box">

        <div class="row mb-30">
            @if (isset($editData))
                <a href="{{ route('sms-settings') }}" class="primary-btn small fix-gr-bg backBtn">
                    @lang('common.back')
                </a>
            @else
                <div class="col-md-12">
                    <a onclick="addnewForm()" href="#" class="primary-btn small fix-gr-bg addFromBtn"
                        id="addFromBtn">
                        <span class="ti-plus pr-2"></span> @lang('system_settings.add_new_gateway')
                    </a>

                    <a onclick="closeForm()" href="#" class="primary-btn small fix-gr-bg closeFormBtn"
                        id="closeFormBtn">
                        @lang('system_settings.close')
                    </a>
                </div>
            @endif
            <div class=" add_new_form pb-4 border-bottom" id="add_new_form">
                @if (isset($editData))
                    {{ Form::open(['class' => 'form-horizontal mb-0', 'files' => true, 'route' => 'update-custom-sms-setting', 'id' => 'update-custom-sms-setting']) }}
                    <input type="hidden" name="id" value="{{ @$editData->id }}">
                @else
                    {{ Form::open(['class' => 'form-horizontal mb-0', 'files' => true, 'route' => 'save-custom-sms-setting', 'id' => 'save-custom-sms-setting']) }}
                @endif
                <div class="row mb-30 mt-30">
                    <div class="col-lg-4 mb-30">
                        <div class="primary_input">
                            <label class="primary_input_label" for="">@lang('system_settings.gateway_name') <span class="text-danger"> *</span></label>
                            <input
                                class="primary_input_field form-control{{ $errors->has('gateway_name') ? ' is-invalid' : '' }}"
                                type="text" name="gateway_name" autocomplete="off"
                                value="{{ isset($editData) ? @$editData->gateway_name : old('gateway_name') }}"
                                id="gateway_name">


                            @if ($errors->has('gateway_name'))
                                <span class="text-danger" >
                                    {{ $errors->first('gateway_name') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-30">
                        <div class="primary_input">
                            <label class="primary_input_label" for="">@lang('system_settings.gateway_url') <span class="text-danger"> *</span></label>
                            <input
                                class="primary_input_field form-control{{ $errors->has('gateway_url') ? ' is-invalid' : '' }}"
                                type="text" name="gateway_url" autocomplete="off"
                                value="{{ isset($editData) ? @$editData->gateway_url : old('gateway_url') }}"
                                id="gateway_url">


                            @if ($errors->has('gateway_url'))
                                <span class="text-danger" >
                                    {{ $errors->first('gateway_url') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-30">
                        <div class="primary_input">
                            <label class="primary_input_label" for="">@lang('system_settings.request_method') <span class="text-danger"> *</span> </label>
                            <select
                                class="primary_select  form-control{{ $errors->has('request_method') ? ' is-invalid' : '' }}"
                                name="request_method">
                                <option data-display="@lang('system_settings.select_request_method') *" value="">@lang('system_settings.select_a_SMS_service') *</option>
                                <option value="get" @if (isset($editData) && $editData->request_method == 'get') selected @endif>GET</option>
                                <option value="post" @if (isset($editData) && $editData->request_method == 'post') selected @endif>POST</option>
                            </select>
                            @if ($errors->has('request_method'))
                                <span class="text-danger" >
                                    {{ $errors->first('request_method') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-30">
                        <div class="primary_input">
                            <label class="primary_input_label" for="">@lang('system_settings.send_to_parameter_name') <span class="text-danger"> *</span> </label>
                            <input
                                class="primary_input_field form-control{{ $errors->has('send_to_parameter_name') ? ' is-invalid' : '' }}"
                                type="text" name="send_to_parameter_name" autocomplete="off"
                                value="{{ isset($editData) ? @$editData->send_to_parameter_name : old('send_to_parameter_name') }}"
                                id="send_to_parameter_name">


                            @if ($errors->has('send_to_parameter_name'))
                                <span class="text-danger" >
                                    {{ $errors->first('send_to_parameter_name') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-30">
                        <div class="primary_input">
                            <label class="primary_input_label" for="">@lang('system_settings.messege_to_parameter_name') <span class="text-danger"> *</span> </label>
                            <input
                                class="primary_input_field form-control{{ $errors->has('messege_to_parameter_name') ? ' is-invalid' : '' }}"
                                type="text" name="messege_to_parameter_name" autocomplete="off"
                                value="{{ isset($editData) ? @$editData->messege_to_parameter_name : old('messege_to_parameter_name') }}"
                                id="messege_to_parameter_name">


                            @if ($errors->has('messege_to_parameter_name'))
                                <span class="text-danger" >
                                    {{ $errors->first('messege_to_parameter_name') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-30 d-flex relation-button mt-30">
                        <p class="text-uppercase mb-0 mt-10">@lang('system_settings.set_authetication')</p>
                        <div class="d-flex radio-btn-flex ml-30">
                            <div class="mr-20 mt-10">
                                <input type="radio" name="set_auth" id="set_auth_on" value="header"
                                    class="common-radio relationButton"
                                    @if (isset($editData) && $editData->set_auth == 'header') checked @endif>
                                <label for="set_auth_on">Header</label>
                            </div>
                            <div class="mr-20 mt-10">
                                <input type="radio" name="set_auth" id="set_auth" value="url"
                                    class="common-radio relationButton"
                                    @if (isset($editData) && $editData->set_auth == 'url') checked @endif>
                                <label for="set_auth">URL</label>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="row mb-30">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_1') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_1') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_1" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_1 : old('param_key_1') }}"
                                        id="param_key_1">


                                    @if ($errors->has('param_key_1'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_1') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_1') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_1') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_1" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_1 : old('param_value_1') }}"
                                        id="param_value_1">


                                    @if ($errors->has('param_value_1'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_1') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_3') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_3') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_3" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_3 : old('param_key_3') }}"
                                        id="param_key_3">


                                    @if ($errors->has('param_key_3'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_3') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_3') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_3') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_3" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_3 : old('param_value_3') }}"
                                        id="param_value_3">


                                    @if ($errors->has('param_value_3'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_3') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_2') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_2') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_2" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_2 : old('param_key_2') }}"
                                        id="param_key_2">


                                    @if ($errors->has('param_key_2'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_2') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_2') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_2') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_2" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_2 : old('param_value_2') }}"
                                        id="param_value_2">


                                    @if ($errors->has('param_value_2'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_2') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_4') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_4') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_4" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_4 : old('param_key_4') }}"
                                        id="param_key_4">


                                    @if ($errors->has('param_key_4'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_4') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_4') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_4') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_4" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_4 : old('param_value_4') }}"
                                        id="param_value_4">


                                    @if ($errors->has('param_value_4'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_4') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_5') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_5') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_5" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_5 : old('param_key_5') }}"
                                        id="param_key_5">


                                    @if ($errors->has('param_key_5'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_5') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_5') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_5') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_5" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_5 : old('param_value_5') }}"
                                        id="param_value_5">


                                    @if ($errors->has('param_value_5'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_5') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_7') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_7') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_7" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_7 : old('param_key_7') }}"
                                        id="param_key_7">


                                    @if ($errors->has('param_key_7'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_7') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_7') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_7') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_7" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_7 : old('param_value_7') }}"
                                        id="param_value_7">


                                    @if ($errors->has('param_value_7'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_7') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_6') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_6') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_6" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_6 : old('param_key_6') }}"
                                        id="param_key_6">


                                    @if ($errors->has('param_key_6'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_6') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_6')</label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_6') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_6" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_6 : old('param_value_6') }}"
                                        id="param_value_6">


                                    @if ($errors->has('param_value_2'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_2') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_key_8') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_key_8') ? ' is-invalid' : '' }}"
                                        type="text" name="param_key_8" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_key_8 : old('param_key_8') }}"
                                        id="param_key_8">


                                    @if ($errors->has('param_key_8'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_key_8') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 mb-30">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('system_settings.param_value_8') </label>
                                    <input
                                        class="primary_input_field form-control{{ $errors->has('param_value_8') ? ' is-invalid' : '' }}"
                                        type="text" name="param_value_8" autocomplete="off"
                                        value="{{ isset($editData) ? @$editData->param_value_8 : old('param_value_8') }}"
                                        id="param_value_8">


                                    @if ($errors->has('param_value_8'))
                                        <span class="text-danger" >
                                            {{ $errors->first('param_value_8') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{ @$tooltip }}">
                            <span class="ti-check"></span>
                            @if (isset($editData))
                                @lang('common.update')
                            @else
                                @lang('common.save')
                            @endif
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>

            @if (!isset($editData))
                <div class="col-lg-12">
                    <x-table>
                        <table id="noSearch" class="table shadow-none" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('student.status')</th>
                                    <th>@lang('system_settings.gateway_name')</th>
                                    <th>@lang('student.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @foreach ($all_sms_services->where('gateway_type','custom') as $key => $custom_sms)
                                    <tr>
                                        <td>
                                            <div class="primary_input">
                                                <input type="checkbox" data-id="{{ @$custom_sms->id }}"
                                                    id="custom_sms{{ @$custom_sms->id }}"
                                                    class="common-checkbox class-checkbox custom_sms_status"
                                                    name="custom_sms_status" value="{{ @$custom_sms->id }}"
                                                    {{ @$custom_sms->active_status == 1 ? 'checked' : '' }}>
                                                <label for="custom_sms{{ @$custom_sms->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ @$custom_sms->gateway_name }}</td>
                                        <td>
                                            <div class="CRM_dropdown dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('edit-custom-sms-setting', [$custom_sms->id]) }}">@lang('common.edit')</a>
    
                                                    <a class="dropdown-item" data-toggle="modal"
                                                        onclick="deleteCustomSms({{ $custom_sms->id }})"
                                                        href="#">@lang('common.delete')</a>
    
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-table>
                </div>
            @endif
        </div>


    </div>



    <div class="modal fade admin-query" id="deleteCustomSmsModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('system_settings.delete_custom_sms_gateway')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {{ Form::open(['route' => 'delete-custom-sms-setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <input type="hidden" name="id" value="">
                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg"
                            data-dismiss="modal">@lang('common.cancel')</button>

                        <input type="hidden" name="id">
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>

                    </div>
                </div>
                {{ Form::close() }}


            </div>
        </div>
    </div>


</div>

@push('script')
    <script>
        function deleteCustomSms(id) {
            var modal = $('#deleteCustomSmsModal');
            modal.find('input[name=id]').val(id)
            modal.modal('show');
        }

        function addnewForm() {
            $("#add_new_form").css("display", "block");
            $("#closeFormBtn").css("display", "block");
            $("#addFromBtn").css("display", "none");
        }

        function closeForm() {
            $("#add_new_form").css("display", "none");
            $("#closeFormBtn").css("display", "none");
            $("#addFromBtn").css("display", "block");
        }
    </script>
@endpush
