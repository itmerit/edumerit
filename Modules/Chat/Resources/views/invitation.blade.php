@extends('backEnd.master')
@section('title')
    @lang('chat::chat.invitation')
@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="chat_flow_list_wrapper ">
                        <div class="box_header">
                            <div class="main-title">
                                <h3 class="m-0">@lang('chat::chat.your_requests')</h3>
                            </div>
                        </div>
                        <!-- chat_list  -->
                        <div class="chat_flow_list crm_full_height">
                            <div class="chat_flow_list_inner">
                                <ul>
                                    @forelse($ownRequest as $myRequest)
                                        <li class="list-unstyled">
                                            <div class="single_list d-flex align-items-center">
                                                <div class="thumb">
                                                    @if($myRequest->requestTo->avatar)
                                                        <a><img src="{{asset($myRequest->requestTo->avatar)}}" alt=""></a>
                                                    @elseif($myRequest->requestTo->avatar_url)
                                                        <a><img src="{{ asset($myRequest->requestTo->avatar_url) }}" alt=""></a>
                                                    @else
                                                        <a><img src="{{asset('public/chat/images/spondon-icon.png')}}" alt=""></a>
                                                    @endif
                                                </div>
                                                <div class="list_name">
                                                    <a>
                                                        <h4>{{ $myRequest->requestTo->first_name }}
                                                            @if($myRequest->requestTo->activeStatus->isActive())
                                                                <span class="active_chat" ></span>
                                                            @elseif($myRequest->requestTo->activeStatus->isInactive())
                                                                <span class="inactive_chat" ></span>
                                                            @elseif($myRequest->requestTo->activeStatus->isBusy())
                                                                <span class="busy_chat" ></span>
                                                            @else
                                                                <span class="away_chat" ></span>
                                                            @endif
                                                        </h4>
                                                    </a>
                                                    <p>Your request to {{ $myRequest->requestTo->first_name }} </p>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <p>@lang('chat::chat.no_connection_request_found')!</p>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <!--/ chat_list  -->
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="chat_flow_list_wrapper">
                        <div class="box_header">
                            <div class="main-title">
                                <h3 class="m-0">@lang('chat::chat.people_requests_you_to_connect')</h3>
                            </div>
                        </div>
                        <!-- chat_list  -->
                        <div class="chat_flow_list crm_full_height">
                            <div class="chat_flow_list_inner">
                                <ul>
                                    @forelse($peopleRequest as $request)
                                        <li class="list-unstyled">
                                            <div class="single_list d-flex align-items-center">
                                                <div class="thumb">
                                                    @if($request->requestFrom->avatar)
                                                        <a><img src="{{asset($request->requestFrom->avatar)}}" alt=""></a>
                                                    @elseif($request->requestFrom->avatar_url)
                                                        <a><img src="{{ asset($request->requestFrom->avatar_url) }}" alt=""></a>
                                                    @else
                                                        <a><img src="{{asset('public/chat/images/spondon-icon.png')}}" alt=""></a>
                                                    @endif
                                                </div>
                                                <div class="list_name w-50">
                                                    <a>
                                                        <h4>{{ $request->requestFrom->first_name }}
                                                            @if($request->requestFrom->activeStatus->isActive())
                                                                <span class="active_chat" ></span>
                                                            @elseif($request->requestFrom->activeStatus->isInactive())
                                                                <span class="inactive_chat" ></span>
                                                            @elseif($request->requestFrom->activeStatus->isBusy())
                                                                <span class="busy_chat" ></span>
                                                            @else
                                                                <span class="away_chat" ></span>
                                                            @endif
                                                        </h4>
                                                    </a>
                                                    <p>{{ $request->requestFrom->first_name }} requested to connect..</p>
                                                </div>
                                                <div>
                                                    <a href="{{ route('chat.invitation.action',['type' => 'accept', 'id' => $request->id]) }}" class="single-icon primary-btn small fix-gr-bg text-white" title="Accept">
                                                        <span class="ti-check pr-2"></span>
                                                    </a>

                                                    <a href="{{ route('chat.invitation.action',['type' => 'reject', 'id' => $request->id]) }}" class="single-icon primary-btn small fix-gr-bg text-white" title="Reject">
                                                        <span class="ti-close pr-2"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <p>@lang('chat::chat.no_connection_request_found')!</p>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <!--/ chat_list  -->
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-xl-6">
                    <div class="chat_flow_list_wrapper ">
                        <div class="box_header">
                            <div class="main-title">
                                <h3 class="m-0">@lang('chat::chat.connection_connected_with_you')</h3>
                            </div>
                        </div>
                        <!-- chat_list  -->
                        <div class="chat_flow_list crm_full_height">
                            <div class="chat_flow_list_inner">
                                <ul>
                                    @forelse($connectedPeoples as $request)
                                        <li class="list-unstyled">
                                            <div class="single_list d-flex align-items-center">
                                                <div class="thumb">
                                                    @if($request->avatar)
                                                        <a><img src="{{asset($request->avatar)}}" alt=""></a>
                                                    @elseif($request->avatar_url)
                                                        <a><img src="{{ asset($request->avatar_url) }}" alt=""></a>
                                                    @else
                                                        <a><img src="{{asset('public/chat/images/spondon-icon.png')}}" alt=""></a>
                                                    @endif
                                                </div>
                                                <div class="list_name w-50">
                                                    <a>
                                                        <h4>{{ $request->first_name }}
                                                            @if($request->activeStatus->isActive())
                                                                <span class="active_chat" ></span>
                                                            @elseif($request->activeStatus->isInactive())
                                                                <span class="inactive_chat" ></span>
                                                            @elseif($request->activeStatus->isBusy())
                                                                <span class="busy_chat" ></span>
                                                            @else
                                                                <span class="away_chat" ></span>
                                                            @endif
                                                        </h4>
                                                    </a>

                                                    <p>{{ $request->first_name }} @lang('chat::chat.connected_with_you').</p>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <p>@lang('chat::chat.no_connection_connected_request_found')!</p>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <!--/ chat_list  -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
