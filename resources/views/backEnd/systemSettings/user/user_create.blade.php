@extends('backEnd.master')
@section('mainContent')
 <section class="mb-40">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="main-title">
                    <h3>@lang('system_settings.user_setup')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <form>
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" type="text" placeholder="First Name *" name="first_name" autocomplete="off">
                                                
                                                @if ($errors->has('first_name'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('first_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text" placeholder="Last Name *" name="last_name" autocomplete="off">
                                                
                                                @if ($errors->has('last_name'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('last_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('designation') ? ' is-invalid' : '' }}" type="text" placeholder="Designation *" name="designation" autocomplete="off">
                                                
                                                @if ($errors->has('designation'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('designation') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" id="date_of_birth" type="text"
                                                    placeholder="Date Of Birth" name="date_of_birth">
                                                
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="admission-date-icon"></i>
                                        </button>
                                        @if ($errors->has('date_of_birth'))
                                            <span class="text-danger" >
                                                {{ $errors->first('date_of_birth') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" type="text" placeholder="Address *" name="address" autocomplete="off">
                                                
                                                @if ($errors->has('address'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('address') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <select class="primary_select  form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="gender">
                                        <option value="">@lang('common.select_gender')</option>
                                        <option value="1">@lang('common.male')</option>
                                        <option value="2">@lang('common.female')</option>
                                        <option value="3">@lang('coommon.others')</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('gender') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" placeholder="Email *" name="email" autocomplete="off">
                                                
                                                @if ($errors->has('email'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="Password" placeholder="Password *" name="password" autocomplete="off">
                                                
                                                @if ($errors->has('password'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" type="Password" placeholder="Phone *" name="phone" autocomplete="off">
                                                
                                                @if ($errors->has('phone'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('phone') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" type="text" placeholder="Phone *" name="phone" autocomplete="off">
                                                
                                                @if ($errors->has('phone'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('phone') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control{{ $errors->has('photo') ? ' is-invalid' : '' }}" type="file" placeholder="Photo *" name="photo" autocomplete="off">
                                                
                                                @if ($errors->has('photo'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('photo') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 text-center">
                                    <div class="row mt-40">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                            @lang('common.save_content')
                                        </button>
                                    </div>
                                 </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@include('backEnd.partials.date_picker_css_js')