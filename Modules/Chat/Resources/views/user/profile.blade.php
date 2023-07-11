@extends('backEnd.master')
@section('mainContent')
    <div class="main-title mb-25">
        <h3 class="mb-0">{{ __('user.profile') }}</h3>
    </div>

    <form action="{{ route('chat.user.profile') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="General_system_wrap_area">
            <div class="single_system_wrap">
                <div class="single_system_wrap_inner text-center">
                    <div class="logo">
                        <span>{{ __('user.avatar') }}</span>
                    </div>
                    <div class="logo_img" style="width: 170px; height: 68px;">
                        <img src="{{ asset(auth()->user()->avatar) }}" alt="" id="generalSettingLogo" style="height: 68px;">
                    </div>
                    <div class="update_logo_btn">
                        <button class="primary-btn small fix-gr-bg">
                            <input placeholder="Upload Logo" type="file" name="profile_avatar" id="site_logo" onchange="imageChangeWithFile(this,'#generalSettingLogo')">
                            {{ __('user.upload_avatar') }}
                        </button>
                    </div>
                <!--  <a href="#" class="remove_logo">{{ __('general_settings.remove') }}</a> -->
                </div>
            </div>

            <div class="single_system_wrap">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label" for="">{{ __('user.first_name') }}</label>
                            <input class="primary_input_field" type="text" id="site_title" name="first_name" value="{{ auth()->user()->first_name }}">
                            @error('first_name')
                            <p required class="text-danger text-sm-left"><em>{{ $message }}</em></p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label" for="">{{ __('user.last_name') }} </label>
                            <input required class="primary_input_field" type="text" name="last_name" value="{{ auth()->user()->last_name }}">
                            @error('last_name')
                            <p class="text-danger text-sm-left"><em>{{ $message }}</em></p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="primary_input mb-25t">
                            <label class="primary_input_label" for="">{{ __('user.username') }}</label>
                            <input required class="primary_input_field" type="text" name="username" value="{{ auth()->user()->username }}">
                            @error('username')
                            <p class="text-danger text-sm-left"><em>{{ $message }}</em></p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="primary_input mb-25t">
                            <label class="primary_input_label" for="">{{ __('user.email') }}</label>
                            <input required class="primary_input_field" type="email" name="email" value="{{ auth()->user()->email }}">
                            @error('email')
                            <p class="text-danger text-sm-left"><em>{{ $message }}</em></p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="submit_btn text-center mt-4">
            <button class="primary_btn_large" type="submit"> <i class="ti-check"></i> {{ __('common.update') }}</button>
        </div>
    </form>


    <div class="main-title mb-25 mt-5">
        <h3 class="mb-0">Change Password</h3>
    </div>

    <form action="{{ route('chat.user.password') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-1">
            <div class="col-xl-4">
                <div class="primary_input mb-25t">
                    <label class="primary_input_label" for="">{{ __('user.current_password') }}</label>
                    <input required class="primary_input_field" type="password" name="current_password" placeholder="********">
                    @error('current_password')
                    <p class="text-danger text-sm-left"><em>{{ $message }}</em></p>
                    @enderror
                </div>
            </div>
            <div class="col-xl-4">
                <div class="primary_input mb-25t">
                    <label class="primary_input_label" for="">{{ __('user.password') }}</label>
                    <input required class="primary_input_field" type="password" name="password" placeholder="********">
                    @error('password')
                    <p class="text-danger text-sm-left"><em>{{ $message }}</em></p>
                    @enderror
                </div>
            </div>

            <div class="col-xl-4">
                <div class="primary_input mb-25t">
                    <label class="primary_input_label" for="">{{ __('user.confirm_password') }}</label>
                    <input required class="primary_input_field" type="password" name="password_confirmation" placeholder="********">
                    @error('password_confirmation')
                    <p class="text-danger text-sm-left"><em>{{ $message }}</em></p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="submit_btn text-center mt-4">
            <button class="primary_btn_large" type="submit"> <i class="ti-check"></i> {{ __('common.update') }}</button>
        </div>
    </form>

@endsection
