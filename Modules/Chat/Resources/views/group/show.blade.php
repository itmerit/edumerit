@extends('backEnd.master')
@section('title')
    @lang('chat::chat.group_chat_list')
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
                                <a class="primary-btn radius_30px  fix-gr-bg" href="{{ route('chat.new') }}"><i class="ti-plus"></i>@lang('chat::chat.new_chat')</a>
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
                                    :groups="{{ json_encode($myGroups) }}"
                                    :all_users="{{ json_encode(\App\Models\User::where('id', '!=', auth()->id())->get()) }}"
                                    :can_create_group="{{ json_encode(createGroupPermission())}}"
                                    :asset_type="{{ json_encode('/public') }}"
                            ></side-panel-component>
                        </div>

                        @if(app('general_settings')->get('chatting_method') == null || app('general_settings')->get('chatting_method')  == 'log')
                            <jquery-group-chat-component
                                    :group="{{ json_encode($group) }}"
                                    :send_message_url="{{ json_encode(route('chat.group.send')) }}"
                                    :new_message_check_url="{{ json_encode(route('chat.group.message.check')) }}"
                                    :add_people_url="{{ json_encode(route('chat.group.addPeople')) }}"
                                    :remove_people_url="{{ json_encode(route('chat.group.removePeople')) }}"
                                    :delete_group_url="{{ json_encode(route('chat.group.delete')) }}"
                                    :message_remove_url="{{ json_encode(route('chat.group.message.destroy')) }}"
                                    :leave_group_url="{{ json_encode(route('chat.group.leave')) }}"
                                    :assign_role_url="{{ json_encode(route('chat.group.role')) }}"
                                    :app_url="{{ json_encode(env('APP_URL')) }}"
                                    :user="{{ json_encode(auth()->user()->load('activeStatus')) }}"
                                    :user="{{ json_encode(auth()->user()->load('activeStatus')) }}"
                                    :connected_users="{{ json_encode($remainingUsers->toArray()) }}"
                                    :all_connected_users="{{ json_encode($users) }}"
                                    :forward_message_url="{{ json_encode(route('chat.send.forward')) }}"
                                    :my_role="{{ json_encode($myRole) }}"
                                    :files_url="{{ json_encode(route('chat.files', ['type' => 'group', 'id' => $group->id])) }}"
                                    :load_more_url="{{ json_encode(route('chat.load.more.group')) }}"
                                    :read_only="{{ json_encode($group->read_only ? true : false) }}"
                                    :can_file_upload="{{ json_encode(app('general_settings')->get('chat_can_upload_file')== 'yes') }}"
                                    :asset_type="{{ json_encode('/public') }}"
                                    :single_threads="{{ json_encode($single_threads) }}"
                                    :make_read_only_url="{{ json_encode(route('chat.group.read.only')) }}"

                            ></jquery-group-chat-component>
                        @else
                            <group-chat-component
                                    :group="{{ json_encode($group) }}"
                                    :send_message_url="{{ json_encode(route('chat.group.send')) }}"
                                    :add_people_url="{{ json_encode(route('chat.group.addPeople')) }}"
                                    :remove_people_url="{{ json_encode(route('chat.group.removePeople')) }}"
                                    :message_remove_url="{{ json_encode(route('chat.group.message.destroy')) }}"
                                    :delete_group_url="{{ json_encode(route('chat.group.delete')) }}"
                                    :leave_group_url="{{ json_encode(route('chat.group.leave')) }}"
                                    :assign_role_url="{{ json_encode(route('chat.group.role')) }}"
                                    :app_url="{{ json_encode(env('APP_URL')) }}"
                                    :user="{{ json_encode(auth()->user()->load('activeStatus')) }}"
                                    :user="{{ json_encode(auth()->user()->load('activeStatus')) }}"
                                    :connected_users="{{ json_encode($remainingUsers->toArray()) }}"
                                    :all_connected_users="{{ json_encode($users) }}"
                                    :forward_message_url="{{ json_encode(route('chat.send.forward')) }}"
                                    :my_role="{{ json_encode($myRole) }}"
                                    :files_url="{{ json_encode(route('chat.files', ['type' => 'group', 'id' => $group->id])) }}"
                                    :load_more_url="{{ json_encode(route('chat.load.more.group')) }}"
                                    :read_only="{{ json_encode($group->read_only ? true : false) }}"
                                    :can_file_upload="{{ json_encode(app('general_settings')->get('chat_can_upload_file')== 'yes') }}"
                                    :asset_type="{{ json_encode('/public') }}"
                                    :single_threads="{{ json_encode($single_threads) }}"
                                    :make_read_only_url="{{ json_encode(route('chat.group.read.only')) }}"
                            ></group-chat-component>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
