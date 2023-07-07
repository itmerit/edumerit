@extends('backEnd.master')
@section('title')
    @lang('common.file')
@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor" id="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="chat_main_wrapper">
                        <div class="chat_flow_list_wrapper ">
                            <div class="box_header">
                                <div class="main-title">
                                    <h3 class="m-0">@lang('chat::chat.chat_list')</h3>
                                </div>
                                @if(userPermission(902))
                                    <a class="primary-btn radius_30px  fix-gr-bg" href="{{ route('chat.new') }}"><i class="ti-plus"></i>@lang('chat::chat.new_chat')</a>
                                @endif
                            </div>
                            <!-- chat_list  -->
                            <side-panel-component
                                    :settings="{{ json_encode(generalSetting()->only(['teacher_phone_view', 'teacher_email_view'])) }}"
                                :search_url="{{ json_encode(route('chat.user.search')) }}"
                                :single_chat_url="{{ json_encode(route('chat.index')) }}"
                                :chat_block_url="{{ json_encode(route('chat.user.block')) }}"
                                :create_group_url="{{ json_encode(route('chat.group.create')) }}"
                                :group_chat_show="{{ json_encode(route('chat.group.show')) }}"
                                :users="{{ json_encode($users) }}"
                                :groups="{{ json_encode($groups) }}"
                                :all_users="{{ json_encode(\App\Models\User::where('id', '!=', auth()->id())->get()) }}"
                                :can_create_group="{{ json_encode(createGroupPermission())}}"
                                :asset_type="{{ json_encode('/public') }}"
                            ></side-panel-component>
                            <!--/ chat_list  -->
                        </div>

                        <div class="chat_view_list ">
                            <div class="box_header">
                                <h3 class="">
                                    {{ $name }}'s Files
                                </h3>
                            </div>
                            <div class="row align-items-center fileshow">
                                @forelse($messages as $message)
                                    <div class="single-entry">
                                        @if($message->message_type == 1)
                                            <img style="max-height: 120px;width: 100%;" class="img-fluid" src="{{ asset($message->file_name) }}" alt="">
                                        @elseif( in_array($message->message_type, [2,3]) )
                                            @if($type == 'group')
                                            <a href="{{ route('chat.file.download.group', ['id' => $message->id, 'group' => $group->id]) }}">{{ $message->original_file_name }}</a>
                                            @else
                                                <a href="{{ route('chat.file.download', $message->id) }}">{{ $message->original_file_name }}</a>
                                            @endif
                                        @else
                                            <div>
                                                <audio class="w-100" src="{{ asset($message->file_name) }}" controls></audio>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p>@lang('chat::chat.no_file_found')!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
