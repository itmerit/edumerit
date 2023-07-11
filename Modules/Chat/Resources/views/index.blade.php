@extends('backEnd.master')
@section('title')
    @lang('chat::chat.chat')
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
                                    <a class="primary-btn small fix-gr-bg" href="{{ route('chat.new') }}"><i class="ti-plus"></i>@lang('chat::chat.new_chat')</a>
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

                        @if($activeUser)
                            @if(app('general_settings')->get('chatting_method') == null || app('general_settings')->get('chatting_method')  == 'log')
                                <jquery-chat-component
                                        :settings="{{ generalSetting() }}"
                                        :new_message_check_url="{{ json_encode(route('chat.message.check')) }}"
                                        :to_user="{{ json_encode($activeUser->load('activeStatus')) }}"
                                        :from_user="{{ json_encode(auth()->user()->load('activeStatus')) }}"
                                        :send_message_url="{{ json_encode(route('chat.send')) }}"
                                        :app_url="{{ json_encode(env('APP_URL'). '/') }}"
                                        :files_url="{{ json_encode(route('chat.files', ['type' => 'single', 'id' => $activeUser->id])) }}"
                                        :loaded_conversations="{{ json_encode(collect($messages)) }}"
                                        :connected_users="{{ json_encode(collect($users)) }}"
                                        :forward_message_url="{{ json_encode(route('chat.send.forward')) }}"
                                        :delete_message_url="{{ json_encode(route('chat.delete')) }}"
                                        :load_more_url="{{ json_encode(route('chat.load.more')) }}"
                                        :can_file_upload="{{ json_encode(app('general_settings')->get('chat_can_upload_file')== 'yes') }}"
                                        :asset_type="{{ json_encode('/public') }}"
                                ></jquery-chat-component>
                            @else
                                <chat-component
                                        :to_user="{{ json_encode($activeUser->load('activeStatus')) }}"
                                        :from_user="{{ json_encode(auth()->user()->load('activeStatus')) }}"
                                        :send_message_url="{{ json_encode(route('chat.send')) }}"
                                        :app_url="{{ json_encode(env('APP_URL'). '/') }}"
                                        :files_url="{{ json_encode(route('chat.files', ['type' => 'single', 'id' => $activeUser->id])) }}"
                                        :loaded_conversations="{{ json_encode($messages) }}"
                                        :connected_users="{{ json_encode(collect($users)) }}"
                                        :forward_message_url="{{ json_encode(route('chat.send.forward')) }}"
                                        :delete_message_url="{{ json_encode(route('chat.delete')) }}"
                                        :load_more_url="{{ json_encode(route('chat.load.more')) }}"
                                        :can_file_upload="{{ json_encode(app('general_settings')->get('chat_can_upload_file')== 'yes') }}"
                                        :asset_type="{{ json_encode('/public') }}"
                                ></chat-component>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
