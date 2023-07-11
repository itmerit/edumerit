@extends('backEnd.master')
@section('mainContent')
    <link rel="stylesheet" href="{{ asset('/Modules/RolePermission/public/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/Modules/RolePermission/public/css/custom.css') }}">

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.role_permission') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.role_permission')</a>
                </div>
            </div>
        </div>
    </section>

    <div class="role_permission_wrap">
        <div class="permission_title">
            <h4>Assign Permission ({{ @$role->name }})</h4>
        </div>
    </div>

    <div class="erp_role_permission_area ">



        <!-- single_permission  -->

        {{ Form::open([
            'class' => 'form-horizontal',
            'files' => true,
            'route' => 'rolepermission/role-permission-assign',
            'method' => 'POST',
        ]) }}

        <input type="hidden" name="role_id" value="{{ @$role->id }}">

        <div class="mesonary_role_header">


            @foreach ($all_permissions as $key => $permission)
                <div class="single_role_blocks">
                    <div class="single_permission" id="{{ $permission->id }}">

                        <div class="permission_header d-flex align-items-center justify-content-between">

                            <div>
                                <input type="checkbox" name="module_id[]" value="{{ $permission->id }}"
                                    id="Main_Module_{{ $key }}"
                                    class="common-radio permission-checkAll main_module_id_{{ $permission->id }}"
                                    {{ in_array($permission->id, $already_assigned) ? 'checked' : '' }}>
                                <label
                                    for="Main_Module_{{ $key }}">{{ __('rolepermission::permissions.' . $permission->name) }}</label>
                            </div>

                            <div class="arrow collapsed" data-toggle="collapse" data-target="#Role{{ $permission->id }}">


                            </div>

                        </div>

                        <div id="Role{{ $permission->id }}" class="collapse">
                            <div class="permission_body">
                                <ul>
                                    @foreach ($permission->subModule as $row2)
                                        <li>
                                            <div class="submodule">
                                                <input id="Sub_Module_{{ $row2->id }}" name="module_id[]"
                                                    value="{{ $row2->id }}"
                                                    class="infix_csk common-radio  module_id_{{ $permission->id }} module_link"
                                                    type="checkbox"
                                                    {{ in_array($row2->id, $already_assigned) ? 'checked' : '' }}>

                                                <label
                                                    for="Sub_Module_{{ $row2->id }}">{{ __('rolepermission::permissions.' . $row2->name) }}</label>
                                                <br>
                                            </div>

                                            <ul class="option">

                                                @foreach ($row2->subModule as $row3)
                                                    <li>
                                                        <div class="module_link_option_div" id="{{ $row2->id }}">
                                                            <input id="Option_{{ $row3->id }}" name="module_id[]"
                                                                value="{{ $row3->id }}"
                                                                class="infix_csk common-radio    module_id_{{ $permission->id }} module_option_{{ $permission->id }}_{{ $row2->id }} module_link_option"
                                                                type="checkbox"
                                                                {{ in_array($row3->id, $already_assigned) ? 'checked' : '' }}>

                                                            <label
                                                                for="Option_{{ $row3->id }}">{{ __('rolepermission::permissions.' . $row3->name) }}</label>
                                                            <br>
                                                        </div>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>


        <div class="row mt-40">
            <div class="col-lg-12 text-center">
                <button class="primary-btn fix-gr-bg">
                    <span class="ti-check"></span>
                    @lang('submit')

                </button>
            </div>
        </div>

        {{ Form::close() }}


    </div>
@endsection



@section('script')
    <script type="text/javascript">
        // Fees Assign
        $('.permission-checkAll').on('click', function() {

            //$('.module_id_'+$(this).val()).prop('checked', this.checked);


            if ($(this).is(":checked")) {
                $('.module_id_' + $(this).val()).each(function() {
                    $(this).prop('checked', true);
                });
            } else {
                $('.module_id_' + $(this).val()).each(function() {
                    $(this).prop('checked', false);
                });
            }
        });



        $('.module_link').on('click', function() {

            var module_id = $(this).parents('.single_permission').attr("id");
            var module_link_id = $(this).val();


            if ($(this).is(":checked")) {
                $(".module_option_" + module_id + '_' + module_link_id).prop('checked', true);
            } else {
                $(".module_option_" + module_id + '_' + module_link_id).prop('checked', false);
            }

            var checked = 0;
            $('.module_id_' + module_id).each(function() {
                if ($(this).is(":checked")) {
                    checked++;
                }
            });

            if (checked > 0) {
                $(".main_module_id_" + module_id).prop('checked', true);
            } else {
                $(".main_module_id_" + module_id).prop('checked', false);
            }
        });




        $('.module_link_option').on('click', function() {

            var module_id = $(this).parents('.single_permission').attr("id");
            var module_link = $(this).parents('.module_link_option_div').attr("id");




            // module link check

            var link_checked = 0;

            $('.module_option_' + module_id + '_' + module_link).each(function() {
                if ($(this).is(":checked")) {
                    link_checked++;
                }
            });

            if (link_checked > 0) {
                $("#Sub_Module_" + module_link).prop('checked', true);
            } else {
                $("#Sub_Module_" + module_link).prop('checked', false);
            }

            // module check
            var checked = 0;

            $('.module_id_' + module_id).each(function() {
                if ($(this).is(":checked")) {
                    checked++;
                }
            });


            if (checked > 0) {
                $(".main_module_id_" + module_id).prop('checked', true);
            } else {
                $(".main_module_id_" + module_id).prop('checked', false);
            }
        });
    </script>
    <!-- <script>
        // $(".arrow").on("click", function(){
        //     $(this).find($("i")).toggleClass('ti-plus').toggleClass('ti-minus');
        // });
    </script> -->
@endsection
