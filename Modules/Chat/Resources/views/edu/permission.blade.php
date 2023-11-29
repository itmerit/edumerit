@extends('backEnd.master')
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
                                        <h3 class="mb-0">{{ __('Chatting Method setting') }}</h3>
                                    </div>
                                    <form action="{{ route('chat.settings.permission.store') }}" method="post" class="bg-white p-4 rounded">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 d-flex relation-button mb-3">
                                                <p class="text-uppercase mb-0">Can Teacher Chat with Parents</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_teacher_chat_with_parents" id="relationFather" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_teacher_chat_with_parents') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather">Yes</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_teacher_chat_with_parents" id="relationMother" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_teacher_chat_with_parents') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button mb-3">
                                                <p class="text-uppercase mb-0">Can Student Chat with Admin, Accounts</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_student_chat_with_admin_account" id="relationFather1" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_student_chat_with_admin_account') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather1">Yes</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="can_student_chat_with_admin_account" id="relationMother2" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_can_student_chat_with_admin_account') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother2">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button mb-3">
                                                <p class="text-uppercase mb-0">Admin can chat without invitation</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="admin_can_chat_without_invitation" id="relationFather3" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_admin_can_chat_without_invitation') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather3">Yes</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="admin_can_chat_without_invitation" id="relationMother4" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_admin_can_chat_without_invitation') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother4">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 d-flex relation-button mb-3">
                                                <p class="text-uppercase mb-0">Open Chat system</p>
                                                <div class="d-flex radio-btn-flex ml-30 mt-1">
                                                    <div class="mr-20">
                                                        <input type="radio" name="open_chat_system" id="relationFather5" value="yes" class="common-radio relationButton" {{ app('general_settings')->get('chat_open') == 'yes' ? 'checked' : ''}}>
                                                        <label for="relationFather5">Yes</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="open_chat_system" id="relationMother6" value="no" class="common-radio relationButton" {{ app('general_settings')->get('chat_open') == 'no' ? 'checked' : ''}}>
                                                        <label for="relationMother6">No</label>
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
                                        <button class="primary-btn radius_30px  fix-gr-bg"><i class="ti-check"></i>Update</button>
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

@endpush
