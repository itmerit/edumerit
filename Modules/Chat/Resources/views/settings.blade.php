@extends('backEnd.master')
@section('title')
    @lang('chat::chat.settings')
@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="white_box_30px">
                                    <!-- SMTP form  -->
                                    <div class="main-title mb-25">
                                        <h3 class="mb-0">
                                            @lang('chat::chat.chatting_method_settings')
                                        </h3>
                                    </div>

                                    <form action="{{ route('chat.settings') }}" method="post" class="bg-white p-4 rounded">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.chat_settings')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="chat_method" id="relationFather6343" value="pusher" class="common-radio relationButton" {{ app('general_settings')->get('chatting_method') == 'pusher' ? 'checked' : ''}}>
                                                        <label for="relationFather6343">Pusher</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="chat_method" id="relationMother733" value="log" class="common-radio relationButton" {{ app('general_settings')->get('chatting_method') == 'log' ? 'checked' : ''}}>
                                                        <label for="relationMother733">jQuery</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="pusher" style="display: none">
                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="">pusher app id <span class="text-danger"> *</span></label>
                                                    <input required class="primary_input_field" placeholder="-" type="text" name="pusher_app_id" value="{{ app('general_settings')->get('pusher_app_id') }}">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="">pusher app key <span class="text-danger"> *</span></label>
                                                    <input required class="primary_input_field" placeholder="-" type="text" name="pusher_app_key" value="{{ app('general_settings')->get('pusher_app_key') }}">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="">pusher app secret <span class="text-danger"> *</span></label>
                                                    <input required class="primary_input_field" placeholder="-" type="text" name="pusher_app_secret" value="{{ app('general_settings')->get('pusher_app_secret') }}">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="">pusher app cluster <span class="text-danger"> *</span></label>
                                                    <input required class="primary_input_field" placeholder="-" type="text" name="pusher_app_cluster" value="{{ app('general_settings')->get('pusher_app_cluster') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <button class="primary-btn small fix-gr-bg"><i class="ti-check"></i>@lang('common.update')</button>
                                    </form>
                                </div>

                                <div class="white_box_30px mt-5">
                                    <!-- SMTP form  -->
                                    <div class="main-title mb-25">
                                        <h3 class="mb-0">@lang('chat::chat.chat_settings')</h3>
                                    </div>
                                    <form action="{{ route('chat.settings.permission.store') }}" method="post" class="bg-white p-4 rounded">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3 justify-content-between">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.can_teacher_chat_with_parents')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_teacher_chat_with_parents" id="relationFather" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_teacher_chat_with_parents') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_teacher_chat_with_parents" id="relationMother" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_teacher_chat_with_parents') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.can_student_chat_with_admin_accounts')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_student_chat_with_admin_account" id="relationFather1" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_student_chat_with_admin_account') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather1">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_student_chat_with_admin_account" id="relationMother2" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_student_chat_with_admin_account') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother2">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.admin_can_chat_without_invitation')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="admin_can_chat_without_invitation" id="relationFather3" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_admin_can_chat_without_invitation') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather3">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="admin_can_chat_without_invitation" id="relationMother4" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_admin_can_chat_without_invitation') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother4">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.open_chat_system')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="open_chat_system" id="relationFather5" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_open') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather5">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="open_chat_system" id="relationMother6" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_open') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother6">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--                                            <div class="col-xl-12">--}}
                                            {{--                                                <div class="primary_input">--}}
                                            {{--                                                    <label class="primary_input_label" for="">Everyone to Everyone Chat</label>--}}
                                            {{--                                                    <select class="primary_select" name="everyone_to_everyone">--}}
                                            {{--                                                        <option value="yes" {{ app('general_settings')->get('chat_everyone_to_everyone') == 'yes' ? 'selected' : ''}}>Yes</option>--}}
                                            {{--                                                        <option value="no" {{ app('general_settings')->get('chat_everyone_to_everyone') == 'no' ? 'selected' : ''}}>No</option>--}}
                                            {{--                                                    </select>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}

                                            {{--                                            <div class="col-xl-12">--}}
                                            {{--                                                <div class="primary_input">--}}
                                            {{--                                                    <label class="primary_input_label" for="">Teacher can chat with parent on respective class</label>--}}
                                            {{--                                                    <select class="primary_select" name="teacher_can_chat_with_parents">--}}
                                            {{--                                                        <option value="yes" {{ app('general_settings')->get('chat_teacher_can_chat_with_parents') == 'yes' ? 'selected' : ''}}>Yes</option>--}}
                                            {{--                                                        <option value="no" {{ app('general_settings')->get('chat_teacher_can_chat_with_parents') == 'no' ? 'selected' : ''}}>No</option>--}}
                                            {{--                                                    </select>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}

                                        </div>
                                        <button class="primary-btn small fix-gr-bg"><i class="ti-check"></i>@lang('common.update')</button>
                                    </form>
                                </div>


                                <div class="white_box_30px mt-5">
                                    <!-- SMTP form  -->
                                    <div class="main-title mb-25">
                                        <h3 class="mb-0">@lang('chat::chat.invitation_settings')</h3>
                                    </div>
                                    <form action="{{ route('chat.invitation.requirement') }}" method="post" class="bg-white p-4 rounded">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.invitation_requirement')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="invitation_requirement" id="relationFather6" value="required" class="common-radio relationButton" {{ app('general_settings')->get('chat_invitation_requirement') == 'required' ? 'checked' : ''}}>
                                                        <label for="relationFather6">@lang('chat::chat.required')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="invitation_requirement" id="relationMother7" value="none" class="common-radio relationButton" {{ app('general_settings')->get('chat_invitation_requirement') == 'none' ? 'checked' : ''}}>
                                                        <label for="relationMother7">@lang('common.not_required')</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <button class="primary-btn small fix-gr-bg"><i class="ti-check"></i>@lang('common.update')</button>
                                    </form>
                                </div>

                                {{-- @if( is_null(app('general_settings')->get('chat_generate')) || app('general_settings')->get('chat_generate') != 'generated') --}}
                                    <div class="white_box_30px mt-5">
                                        <div class="main-title mb-25">
                                            <h3 class="mb-0">@lang('chat::chat.generate_connections')</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 mb-3">
                                                <form action="{{ route('chat.invitation.generate','single') }}" method="get" class="bg-white p-4 rounded">
                                                    <p class="text-uppercase mb-0">
                                                        @lang('chat::chat.generate_teacher_and_student_connection_for_old_class_&_subjects')
                                                    </p>
                                                    <br>
                                                    <button class="primary-btn radius_30px  fix-gr-bg"><i class="ti-check"></i>@lang('chat::chat.generate')</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                {{-- @endif --}}

                                <div class="white_box_30px mt-5">
                                    <!-- SMTP form  -->
                                    <div class="main-title mb-25">
                                        <h3 class="mb-0">@lang('chat::chat.permission_settings')</h3>
                                    </div>
                                    <form action="{{ route('chat.settings.edu') }}" method="post" class="bg-white p-4 rounded">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.can_upload_file')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_upload_file" id="relationFather6334" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_upload_file') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather6334">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_upload_file" id="relationMother7334" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_upload_file') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother7334">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <div class="primary_input">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="primary_input_label" for="">@lang('chat::chat.upload_file_limit') (@lang('chat::chat.mb'))</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input class="primary_input_field" placeholder="-" type="number" name="file_upload_limit" value="{{ app('general_settings')->get('chat_file_limit') ?? 0 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.student_can_make_group')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_make_group" id="relationFather63" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_make_group') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather63">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_make_group" id="relationMother73" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_make_group') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother73">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0"> @lang('chat::chat.teacher_staff_can_make_group')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="teacher_staff_can_make_group" id="relationFather634" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_teacher_staff_can_make_group') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather634">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="teacher_staff_can_make_group" id="relationMother734" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_teacher_staff_can_make_group') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother734">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.can_staff_or_teacher_ban_tudent')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="staff_or_teacher_can_ban_student" id="relationFather33" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_staff_or_teacher_can_ban_student') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather33">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="staff_or_teacher_can_ban_student" id="relationMother22" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_staff_or_teacher_can_ban_student') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother22">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.teacher_can_pinned_top_message')</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="teacher_can_pin_top_message" id="relationFather11" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_teacher_can_pin_top_message') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather11">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="teacher_can_pin_top_message" id="relationMother00" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_teacher_can_pin_top_message') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother00">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 d-flex relation-button justify-content-between mb-3">
                                                <p class="text-uppercase mb-0">@lang('chat::chat.student_can_add_member')</p>
                                                <div class="d-flex radio-btn-flex ml-30">
                                                    <div class="mr-20">
                                                        <input type="radio" name="student_can_add_member" id="student_can_add_member_yes" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('student_can_add_member') == 'yes' ? 'checked' : ''}}>
                                                        <label for="student_can_add_member_yes">@lang('common.yes')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="student_can_add_member" id="student_can_add_member_no" value="no" class="common-radio relationButton" {{ app('general_settings')->get('student_can_add_member') == 'no' ? 'checked' : 'checked'}}>
                                                        <label for="student_can_add_member_no">@lang('common.no')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="primary-btn small fix-gr-bg"><i class="ti-check"></i>@lang('common.update')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $( document ).ready(function() {
            let method = $('input[name="chat_method"]:checked').val();
            if (method == 'pusher') {
                $('#pusher').css('display','');
                $('#jquery').hide();
                $('#pusher').show();
            }else{
                $('#pusher').hide();
            }
            $('input[name=chat_method]').change(function () {
                let method = $('input[name="chat_method"]:checked').val();
                if (method == 'pusher') {
                    $('#jquery').hide();
                    $('#pusher').show();
                }else{
                    $('#pusher').hide();
                }
            });
        });
    </script>
@endpush
