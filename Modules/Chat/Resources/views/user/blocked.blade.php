@extends('backEnd.master')
@section('title')
    @lang('chat::chat.blocked_user')
@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-xl-12">

                    <div class="box_header">
                        <div class="main-title">
                            <h3 class="m-0">@lang('chat::chat.blocked_user') </h3>
                        </div>
                    </div>
                    <div class="chat_flow_list crm_full_height">

                        <div class="main-title2 mt-0">
                            <h4 class="">@lang('chat::chat.people')</h4>
                        </div>

                        <div class="chat_flow_list_inner">
                            <ul>
                                @forelse($users as $index => $user)
                                    <li>
                                        <div class="single_list d-flex align-items-center">
                                            <div class="thumb">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#profileEditForm{{ $index }}"><img
                                                        src="{{ file_exists(@$user->avatar) ? asset($user->avatar) : asset('public/chat/images/spondon-icon.png') }}"
                                                        alt=""></a>
                                            </div>
                                            <div class="list_name">
                                                <a href="#">
                                                    <h4>{{ $user->first_name }} {{ $user->last_name }} <span
                                                            class="active_chat"></span> </h4>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="{{ route('chat.user.block', ['type' => 'unblock', 'user' => $user->id]) }}"
                                                    class="primary-btn small fix-gr-bg"><span class="ripple rippleEffect"
                                                        style="width: 30px; height: 30px; top: -6.99219px; left: 19.2578px;"></span>
                                                    @lang('chat::chat.unblock')
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <div class="modal fade admin-query" id="profileEditForm{{ $index }}"
                                        aria-modal="true">
                                        <div class="modal-dialog modal_800px modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">
                                                        <div class="thumb" style="display: inline">
                                                            <a href="#"><img
                                                                    src="{{ file_exists(@$user->avatar) ? asset($user->avatar) : asset('public/chat/images/spondon-icon.png') }}"
                                                                    height="50" width="50" alt=""></a>
                                                        </div>
                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                    </h4>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <i class="ti-close "></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <div class="primary_input mb-25">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('chat::chat.username') <span
                                                                        class="text-danger"> *</span></label>
                                                                <input name="name" disabled
                                                                    class="primary_input_field name" placeholder="Name"
                                                                    value="{{ $user->username }}" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <div class="primary_input mb-25">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('common.email') <span
                                                                        class="text-danger"> *</span></label>
                                                                <input name="email" class="primary_input_field name"
                                                                    disabled placeholder="Email"
                                                                    value="{{ $user->email }}" type="email"
                                                                    readonly="">
                                                                <span class="text-danger"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-6">
                                                            <div class="primary_input mb-25">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('common.phone')</label>
                                                                <input name="username" class="primary_input_field name"
                                                                    disabled value="{{ $user->phone }}" type="text"
                                                                    readonly="">
                                                            </div>
                                                            @if ($user->blockedByMe())
                                                                <a href="{{ route('chat.user.block', ['type' => 'unblock', 'user' => $user->id]) }}"
                                                                    class="primary-btn small fix-gr-bg"><span
                                                                        class="ripple rippleEffect"
                                                                        style="width: 30px; height: 30px; top: -6.99219px; left: 19.2578px;"></span>
                                                                    @lang('chat::chat.unblock_user')
                                                                </a>
                                                            @else
                                                                <a href="{{ route('chat.user.block', ['type' => 'block', 'user' => $user->id]) }}"
                                                                    class="primary-btn small fix-gr-bg"><span
                                                                        class="ripple rippleEffect"
                                                                        style="width: 30px; height: 30px; top: -6.99219px; left: 19.2578px;"></span>
                                                                    @lang('chat::chat.block_user')
                                                                </a>
                                                            @endif
                                                        </div>

                                                        <div class="col-xl-6">
                                                            <div class="primary_input mb-25">
                                                                <label class="primary_input_label"
                                                                    for="">@lang('common.description')</label>
                                                                <p>
                                                                    {{ $user->description }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>@lang('chat::chat.no_user_found!')</p>
                                @endforelse

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
