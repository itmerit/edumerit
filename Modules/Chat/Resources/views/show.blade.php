@extends('backEnd.master')
@section('title')
    Show
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
                                    <h3 class="m-0">Chat List</h3>
                                </div>
                                <a class="primary-btn radius_30px  fix-gr-bg" href="{{url('admin/communication_new_chat')}}"><i class="ti-plus"></i>New Chat</a>
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
                                :can_create_group="{{ json_encode(createGroupPermission())}}"
                                :asset_type="{{ json_encode('/public') }}"
                            ></side-panel-component>
                            <!--/ chat_list  -->
                        </div>



                        @if(env('BROADCAST_DRIVER') == null || env('BROADCAST_DRIVER') == 'log')
                            <jquery-chat-component
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
                                :can_upload_file="{{ json_encode(app('general_settings')->get('chat_can_upload_file')== 'yes') }}"
                                :asset_type="{{ json_encode('/public') }}"
                            ></jquery-chat-component>
                        @else
                            <chat-component
                                :to_user="{{ json_encode($activeUser->load('activeStatus')) }}"
                                :from_user="{{ json_encode(auth()->user()->load('activeStatus')) }}"
                                :send_message_url="{{ json_encode(route('chat.send')) }}"
                                :app_url="{{ json_encode(env('APP_URL'). '/') }}"
                                :files_url="{{ json_encode(route('chat.files', ['type' => 'single', 'id' => $activeUser->id])) }}"
                                :loaded_conversations="{{ json_encode(collect($messages)) }}"
                                :connected_users="{{ json_encode(collect($users)) }}"
                                :forward_message_url="{{ json_encode(route('chat.send.forward')) }}"
                                :delete_message_url="{{ json_encode(route('chat.delete')) }}"
                                :can_upload_file="{{ json_encode(app('general_settings')->get('chat_can_upload_file')== 'yes') }}"
                                :asset_type="{{ json_encode('/public') }}"
                            ></chat-component>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
