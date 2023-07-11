<template>
    <div>
        <div class="chat_view_list ">
            <div class="box_header">
                <div class="main-title">
                    <div class="dropdown CRM_dropdown">
                        <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ this.group.name }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu3">
                            <a v-if="my_role == 1" data-toggle="modal" data-target="#manageRole"
                               class="dropdown-item" type="button">
                                {{__('user_role')}}
                            </a>
                            <a data-toggle="modal" data-target="#chat_search"
                               class="dropdown-item" type="button">
                                {{__('key_search')}}
                            </a>
                            <a v-if="can_add_people" data-toggle="modal" data-target="#addPeopleForm"
                               class="dropdown-item" type="button">
                                {{__('add')}} {{__('people')}}
                            </a>
                            <a data-toggle="modal" data-target="#removePeopleForm"
                               class="dropdown-item" type="button">
                                {{__('member')}}
                            </a>
                            <a :href="files_url"
                               class="dropdown-item" type="button">
                                {{__('media_files')}}
                            </a>
                            <a @click="leave_group()"
                               class="dropdown-item" type="button">
                                {{__('leave_group')}}
                            </a>
                            <a v-if="my_role == 1" @click="delete_group()"
                               class="dropdown-item" type="button">
                                {{__('delete_group')}}
                            </a>
                            <a v-if="my_role == 1 && !this.read_only" @click="make_read_only()"
                               class="dropdown-item" type="button">
                                {{__('mark_as_read_only')}}
                            </a>

                            <a v-if="my_role == 1 && this.read_only" @click="make_read_only('unmark')"
                               class="dropdown-item" type="button">
                                {{__('remove_read_only')}}
                            </a>
                        </div>
                    </div>

                </div>
                <StatusChangeComponent
                    :app_url="this.baseUrl"
                    :user="this.user"
                    :status="this.user.active_status.status"
                />
            </div>


            <div class="chat_view_list_inner crm_full_height ">
                <div v-if="search_result_bar" class="search_indicator py-2">
                    <div class="float-left">
                        <p class="mb-0">{{__('showing')}} {{ this.search_result_count===0? this.search_result_count :this.search_index+1 }} {{__('of')}} {{ this.search_result_count}} {{__('results')}}</p>
                    </div>
                    <div class="float-right">
                        <p class="mb-0">
                            <a href="#" @click="scroll_to_search_up()" class="px-1">
                                <span class="ti-arrow-up"></span>
                            </a>

                            <a href="#" @click="scroll_to_search()" class="px-1">
                                <span class="ti-arrow-down"></span>
                            </a>
                            <a href="#" @click="close_search_bar()" class="px-1">
                                <span class="ti-close"></span>
                            </a>

                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="chat_view_list_inner_scrolled" id="chat_container" style="max-height: 70vh;" v-chat-scroll>
                    <div>
                      <p class="text-center" v-if="loadable">
                        <a class="cursor-pointer" @click="loadMore()">
                          {{__('load_more')}} <span class="ti-arrow-up"></span>
                        </a>
                      </p>
                      <div :id="'target'+index" v-for="(thread, index) in this.only_threads">
                        <div v-if="parseInt(thread.user.id) != parseInt(user.id) && !thread.removedByMe" class="chat_single d-flex" style="overflow: unset">
                          <div class="thumb mr_20">
                            <a v-if="thread.user.avatar" href="#"><img :src="baseUrl+ thread.user.avatar" alt=""></a>
                            <a v-else-if="thread.user.avatar_url" href="#"><img :src="baseUrl+ thread.user.avatar_url" alt=""></a>
                            <a v-else href="#"><img :src="baseUrl+asset_type+'/chat/images/spondon-icon.png'" alt=""></a>
                          </div>
                          <div class="chat_text_info_wraper d-flex align-items-center">
                            <div class="chat_text_info">
                              <div v-if="thread.conversation.reply">
                                <p class="reply_p" v-if="thread.conversation.reply.message_type == 0" v-html="url_maker(thread.conversation.reply.message)"></p>
                                <p class="reply_p" v-else>{{__('attachment')}}</p>
                              </div>

                              <div v-if="thread.conversation.forward_from">
                                <p class="reply_p font-italic" v-if="thread.conversation.forward_from.message_type == 0">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <p v-html="url_maker(thread.conversation.forward_from.message)"></p>
                                </p>
                                <div class="reply_p audio-padding" v-if="thread.conversation.forward_from.message_type == 4">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <audio :src="baseUrl+thread.conversation.forward_from.file_name" controls></audio>
                                </div>
                                <div v-else-if="thread.conversation.forward_from.message_type == 5" class="reply_p p-3 w-100">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <video class="w-100 border-radius-25" controls>
                                    <source :src="baseUrl+thread.conversation.forward_from.file_name" type="video/mp4">
                                  </video>
                                </div>
                                <div class="reply_p p-3" v-else-if="thread.conversation.forward_from.message_type == 1" >
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <img class="border-radius-25 cursor-pointer" @click="imageViewLargeScreen(baseUrl+thread.conversation.forward_from.file_name)" v-if="thread.conversation.forward_from.file_name" :src="baseUrl+thread.conversation.forward_from.file_name" alt="">
                                  <img class="border-radius-25" v-else :src="baseUrl+asset_type+'/chat/images/msg_img.png'" alt="">
                                </div>
                                <p class="reply_p" v-else-if="thread.conversation.message_type == 2 || thread.conversation.message_type == 3">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <u><a style="color: white;" :href="baseUrl+'chat/group/file/download/'+thread.conversation.forward_from.id+'/'+thread.group_id">{{ thread.conversation.forward_from.original_file_name}}</a></u>
                                </p>
                              </div>


                              <div class="audio-padding" v-if="thread.conversation.message_type == 4">
                                <audio :src="baseUrl+thread.conversation.file_name" controls></audio>
                              </div>
                              <div v-else-if="thread.conversation.message_type == 5" class="p-3 w-100">
                                <video class="w-100 border-radius-25" controls>
                                  <source :src="baseUrl+thread.conversation.file_name" type="video/mp4">
                                </video>
                              </div>
                              <p v-else-if="thread.conversation.message_type == 0">
                                <strong>{{ thread.user.first_name}} {{ thread.user.last_name}} : </strong>
                                <br>
                                <span :id="'text'+index" class="textmsg" v-html="url_maker(thread.conversation.message)"></span>
                                <!-- <vue-link-preview v-if="linkable(conversation.message)" url="https://vuejs.org/" @click="handleClick"></vue-link-preview>-->
                              </p>

                              <div class="p-3" v-else-if="thread.conversation.message_type == 1" >
                                <img class="border-radius-25 cursor-pointer" @click="imageViewLargeScreen(baseUrl+thread.conversation.file_name)" v-if="thread.conversation.file_name" :src="baseUrl+thread.conversation.file_name" alt="">
                                <img class="border-radius-25" v-else :src="baseUrl+asset_type+'/chat/images/msg_img.png'" alt="">
                              </div>
                              <p v-else-if="thread.conversation.message_type == 2 || thread.conversation.message_type == 3">
                                <u><a style="color: white;" :href="baseUrl+'chat/group/file/download/'+thread.conversation.id+'/'+thread.group_id">{{ thread.conversation.original_file_name}}</a></u>
                              </p>
                              <p v-else>{{ thread.conversation.file_name }}</p>


                            </div>
                            <span class="chat_date ml_15 ml-2" >{{ diffHuman(thread.conversation.created_at) }}
                                    <div class="dropdown">
                                        <a class="" id="dropdownMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="ti-angle-down cursor-pointer"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu4">
                                            <a @click="reply(thread.conversation)"
                                               class="dropdown-item cursor-pointer">
                                                {{__('quote')}}
                                            </a>
                                            <a @click="forwardModalOpen(thread.conversation)"
                                               class="dropdown-item cursor-pointer">
                                                {{__('forward')}}
                                            </a>
                                            <a v-if="thread.user.id == user.id" @click="deleteMessage(thread)"
                                               class="dropdown-item cursor-pointer">
                                                {{__('delete')}}
                                            </a>
                                        </div>
                                    </div>
                                </span>
                          </div>

                        </div>
                        <div v-if="thread.user.id == user.id && !thread.removedByMe" class="chat_single d-flex sender_chat" style="overflow: unset">
                          <div class="chat_text_info_wraper d-flex align-items-center">
                                <span class="chat_date ml_15">
                                    {{ diffHuman(thread.conversation.created_at) }}
                                    <div class="dropdown">
                                        <a class="" id="dropdownMenu5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="ti-angle-down cursor-pointer"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu5">
                                            <a @click="reply(thread.conversation)"
                                               class="dropdown-item cursor-pointer">
                                                {{__('quote')}}
                                            </a>
                                            <a @click="forwardModalOpen(thread.conversation)"
                                               class="dropdown-item cursor-pointer">
                                                {{__('forward')}}
                                            </a>
                                            <a @click="deleteMessage(thread)"
                                               class="dropdown-item cursor-pointer">
                                                {{__('delete')}}
                                            </a>
                                        </div>
                                    </div>
                                </span>
                            <div class="chat_text_info">
                              <div v-if="thread.conversation.reply">
                                <p class="sender_reply_p" v-if="thread.conversation.reply.message_type == 0" v-html="url_maker(thread.conversation.reply.message )"></p>
                                <p class="sender_reply_p" v-else>{{__('attachment')}}</p>
                              </div>

                              <div v-if="thread.conversation.forward_from">
                                <p class="sender_reply_p font-italic" v-if="thread.conversation.forward_from.message_type == 0">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  {{ thread.conversation.forward_from.message }}
                                </p>
                                <div class="sender_reply_p audio-padding" v-if="thread.conversation.forward_from.message_type == 4">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <audio :src="baseUrl+thread.conversation.forward_from.file_name" controls></audio>
                                </div>
                                <div v-else-if="thread.conversation.forward_from.message_type == 5" class="sender_reply_p p-3 w-100">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <video class="w-100 border-radius-25" controls>
                                    <source :src="baseUrl+thread.conversation.forward_from.file_name" type="video/mp4">
                                  </video>
                                </div>
                                <div class="sender_reply_p p-3" v-else-if="thread.conversation.forward_from.message_type == 1" >
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <img class="border-radius-25 cursor-pointer" @click="imageViewLargeScreen(baseUrl+thread.conversation.forward_from.file_name)" v-if="thread.conversation.forward_from.file_name" :src="baseUrl+thread.conversation.forward_from.file_name" alt="">
                                  <img class="border-radius-25" v-else :src="baseUrl+asset_type+'/chat/images/msg_img.png'" alt="">
                                </div>
                                <p class="sender_reply_p" v-else-if="thread.conversation.message_type == 2 || thread.conversation.message_type == 3">
                                  <span>{{__('forwarded_message')}} : </span><br>
                                  <u><a style="color: white;" :href="baseUrl+'chat/group/file/download/'+thread.conversation.forward_from.id+'/'+thread.group_id">{{ thread.conversation.forward_from.original_file_name}}</a></u>
                                </p>
                              </div>

                              <div class="audio-padding" v-if="thread.conversation.message_type == 4">
                                <audio :src="baseUrl+thread.conversation.file_name" controls></audio>
                              </div>
                              <div v-else-if="thread.conversation.message_type == 5" class="p-3 w-100">
                                <video class="w-100 border-radius-25" controls>
                                  <source :src="baseUrl+thread.conversation.file_name" type="video/mp4">
                                </video>
                              </div>
                              <p v-else-if="thread.conversation.message_type == 0">
                                <span :id="'text'+index" class="textmsg" v-html="url_maker(thread.conversation.message)"></span>
                                <!-- <vue-link-preview v-if="linkable(conversation.message)" url="https://vuejs.org/" @click="handleClick"></vue-link-preview>-->
                              </p>

                              <div class="p-3" v-else-if="thread.conversation.message_type == 1" >
                                <img class="border-radius-25 cursor-pointer" @click="imageViewLargeScreen(baseUrl+thread.conversation.file_name)" v-if="thread.conversation.file_name" :src="baseUrl+thread.conversation.file_name" alt="">
                                <img class="border-radius-25" v-else :src="baseUrl+asset_type+'/chat/images/msg_img.png'" alt="">
                              </div>
                              <p v-else-if="thread.conversation.message_type == 2 || thread.conversation.message_type == 3">
                                <u><a style="color: white;" :href="baseUrl+'chat/group/file/download/'+thread.conversation.id+'/'+thread.group_id">{{ thread.conversation.original_file_name}}</a></u>
                              </p>
                              <p v-else>{{ thread.conversation.file_name }}</p>
                            </div>
                          </div>
                          <div class="thumb">
                            <a v-if="user.avatar" href="#"><img :src="baseUrl+ user.avatar" alt=""></a>
                            <a v-else-if="user.avatar_url" href="#"><img :src="baseUrl+ user.avatar_url" alt=""></a>
                            <a v-else href="#"><img :src="baseUrl+asset_type+'/chat/images/spondon-icon.png'" alt=""></a>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>

                <div v-show="replying" class="bg-gray-200 p-3 replying-box mb-2-3 ml-5">
                    <p>
                        <strong class="text-info">{{__('replying')}} : </strong><br>
                        <span v-html="replying_text"></span>
                    </p>
                    <span @click="quote_close_preview" class="close-quote-preview cursor-pointer"><i class="fa fa-times"></i></span>

                </div>
                <form v-if="!read_only" v-on:submit.prevent="sendMessage" :class="{'pt-100px' : addPadding}">
                    <div class="chat_input_box d-flex align-items-center">
                        <div class="input_thumb">
                            <img v-if="user.avatar" :src="'/'+ user.avatar" alt="">
                            <img v-else-if="user.avatar_url" :src="baseUrl+ user.avatar_url" alt="">
                            <img v-else :src="baseUrl + asset_type+'/chat/images/spondon-icon.png'" alt="">
                        </div>
                        <div class="input-group">
                            <div v-if="preview_url" class="preview_imgs">
<!--                                <span id="ar" ></span>-->
                                <img :src="preview_url" alt="" id="blah" style="object-fit: contain">
                                <span class="close_preview" @click="closePreview">
                                    <i class="ti-close"></i>
                                </span>
                            </div>

                            <div v-if="file_name_preview" class="preview_imgs">
                                <span id="ar">{{ file_name_preview }}</span>
                                <span class="close_preview" @click="closePreview">
                                    <i class="ti-close"></i>
                                </span>
                            </div>
<vue-editor v-model="newMessage" :editor-toolbar="customToolbar" ref="contenteditor"/>
                            <!-- <textarea v-model="newMessage"
                                      @keydown="sendTypingEvent"
                                      @keyup.enter.exact="sendMessage"
                                      oninput="auto_height(this)" type="text"
                                      id="inputbox"
                                      class="form-control auto_height" :placeholder="__('type') + ' '+ __('message')+ '...'"
                                      aria-label="Recipient's username" aria-describedby="basic-addon2"
                                      style="height:40px;">
                            </textarea> -->
                            <div class="input-group-append">
                                <button class="btn pr-2" type="button" @click="toggleRecording"> <i :class="{'microphone-red':record_status}" class="ti-microphone-alt"></i> </button>
                                <button class="btn" type="button" @click="emoji = !emoji"> <i :class="{'imoji-box-open':emoji}" class="ti-face-smile img_toggle"></i> </button>
                                <button v-if="can_file_upload" class="btn" type="button"> <i class="ti-clip"></i>
                                    <input type="file" @change="onFileChange" id="imgInp" ref="file" v-on:change="onChangeFileUpload()" accept=".jpg,.jpeg,.png,.doc,.docx,.pdf,.mp4,.3gp,.webm">
                                </button>
                              <button class="btn svg_send_button" @click="sendMessage" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 40 40" width="20px" height="20px">
                                  <g id="surface57286370">
                                    <rect x="0" y="0" width="40" height="40" style="fill: #F5F8FF; fill-opacity: 1; stroke: none;" />
                                    <path style="stroke: none; fill-rule: nonzero; fill: #F5F8FF; fill-opacity: 1;" d="M 44 7 L 4 23 L 44 39 L 37 23 Z M 36 23 L 17 23 L 35 16 L 36 22 Z M 36 23 " />
                                    <path
                                        style="stroke: none; fill-rule: nonzero; fill: #989898 ; fill-opacity: 1;"
                                        d="M 40.210938 10.667969 L 35.167969 22.199219 L 34.816406 23 L 35.167969 23.800781 L 40.210938 35.332031 L 9.386719 23 L 40.210938 10.667969 M 44 7 L 4 23 L 44 39 L 37 23 Z M 44 7 "/>
                                    <path
                                        style="stroke: none; fill-rule: nonzero; fill:#989898 ; fill-opacity: 1;"
                                        d="M 36 22 L 35 16 L 17 23 L 34 30 L 32 25 L 24 23 L 36 23 Z M 27.660156 21 L 33.433594 18.757812 L 33.804688 21 Z M 27.660156 21 "/>
                                  </g>
                                </svg>
                              </button>

                            </div>
                        </div>
                    </div>
                    <div class="emoji_box" :class="{active : emoji}">
                        <VEmojiPicker @select="selectEmoji" />
                    </div>
                </form>
            </div>
            <span class="text-muted" v-if="typing" >{{__('someone')}} {{__('is_typing')}}...</span>
            <div class="mt-2 bg-white timer-display" v-if="record_status">
                <div class="timer-padding">
                    <span id="timer" class="timer"> {{ timing }} </span>
                </div>
                <div class="timer-padding">
                    <img :src="baseUrl+asset_type+'/chat/images/recording.gif'" alt="" style="width: 140px; height: 35px">
                    <span class="text-muted">
                        {{__('your_voice_is_recording')}}...
                    </span>
                </div>
                <div class="stop-button-padding">
                    <a href="#" @click="toggleRecording">
                        <img :src="baseUrl+asset_type+'/chat/images/recording-stop.png'" alt="" style="height: 35px; width: 35px;">
                    </a>
                </div>
            </div>

        </div>

        <!--    MODALS    -->
        <div class="modal fade admin-query" id="addPeopleForm" aria-modal="true">
            <div class="modal-dialog modal_800px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{__('add')}} {{__('people')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label">{{__('member')}}</label>
                                    <select class="primary_select" v-model="add_user" id="cash_acc_cat_id">
                                        <option value="" disabled>{{__('select')}}</option>
                                        <option v-for="single_user in connected_users" :value="single_user.id">{{ single_user.first_name }}</option>
                                    </select>
                                </div>
                                <button @click="add_new_user()" class="primary-btn radius_30px  fix-gr-bg" href="#">{{__('add')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="my_role == 1" class="modal fade admin-query" id="manageRole" aria-modal="true">
            <div class="modal-dialog modal_800px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{__('manage_role')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label">{{__('member')}}</label>
                                    <select class="primary_select" v-model="assignable_user">
                                        <option value="" disabled>{{__('select')}}</option>
                                        <option v-for="usr in group.users" :value="usr.id">{{ usr.first_name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="primary_input mb-15">
                                    <label class="primary_input_label">{{__('user_role')}}</label>
                                    <select class="primary_select" v-model="assignable_role">
                                        <option value="" disabled>{{__('select')}}</option>
                                        <option value="1">{{__('chat_admin')}}</option>
                                        <option value="2">{{__('chat_moderate')}}</option>
                                    </select>
                                </div>
                                <button @click="assign_role_to_user()" class="primary-btn radius_30px  fix-gr-bg" href="#">{{__('add')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade admin-query" id="removePeopleForm" aria-modal="true">
            <div class="modal-dialog modal_800px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{__('member')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div v-for="single_user in group.users">
                                    <li v-if="single_user.id !== user.id" class="my-4 py-2 remove-list list-unstyled">
                                        <div class="single_list d-flex align-items-center p-2">
                                            <div class="thumb">
                                              <img style="width: 100px" v-if="single_user.avatar_url" :src="baseUrl + single_user.avatar_url" alt="">
                                              <a v-else href="#"><img :src="baseUrl+single_user.avatar" alt=""></a>
                                            </div>
                                            <div class="list_name ml-5">
                                                <a>
                                                    <h4>{{ single_user.first_name }} {{ single_user.last_name }}
                                                    </h4>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="p-2" v-if="my_role == 1">
                                            <button @click="remove_people(single_user.id)" class="primary-btn radius_30px  fix-gr-bg" href="#">{{__('remove')}}</button>
                                        </div>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade admin-query" id="chat_search" aria-modal="true">
            <div class="modal-dialog modal_800px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{__('search')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="primary_input mb_20">
                                    <label class="primary_input_label" for="">{{__('keywords')}}</label>
                                    <input class="primary_input_field" placeholder="-" type="text" v-model="keywords">
                                </div>
                                <br>
                                <button @click="search()" class="primary-btn radius_30px  fix-gr-bg" href="#">{{__('search')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade admin-query" id="imageView" aria-modal="true">
            <div class="modal-dialog modal_800px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{__('preview')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <img :src="imageSrc" alt="" class="w-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade admin-query" id="forward" aria-modal="true">
            <div class="modal-dialog modal_800px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{__('forward')}} {{__('message')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body" v-if="forward_conversation">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card mb-2">
                                    <div class="card-header" v-html="this.forward_conversation.message">
                                    </div>
                                </div>
                                <div class="primary_input mb_20">
                                    <input v-model="forward_addon_text" class="primary_input_field" :placeholder="__('write_something_Optional')" type="text">
                                </div>
                            </div>
                            <div class="users-box">
                                <div class="col-xl-12 mt-1 mb-1" v-for="(list_user, indexFor) in all_connected_users">
                                    <div class="single_list d-flex justify-content-between">
                                        <div class="thumb">
                                            <a href="#">
                                                <img class="forward-image" v-if="list_user.avatar" :src="'/'+ list_user.avatar" alt="">
                                                <img class="forward-image" v-else-if="list_user.avatar_url" :src="baseUrl+ list_user.avatar_url" alt="">
                                                <img class="forward-image" v-else :src="baseUrl+asset_type+'/chat/images/spondon-icon.png'" alt="">
                                                <h4>{{ list_user.first_name }} {{ list_user.last_name }}</h4>
                                            </a>
                                        </div>
                                        <div class="mt-4">
                                            <a href="#" :id="'forwordClick'+indexFor" v-if="forward_conversation.forward_from" @click="forward(forward_conversation.forward_from, list_user,indexFor)" class="primary-btn radius_30px  fix-gr-bg"><i class="ti-share"></i>{{__('send')}}</a>
                                            <a href="#" :id="'forwordClick'+indexFor" v-else @click="forward(forward_conversation, list_user,indexFor)" class="primary-btn radius_30px  fix-gr-bg"><i class="ti-share"></i>{{__('send')}}</a>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0; margin-bottom: 0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--    MODALS ENDS   -->
    </div>
</template>

<script>
import moment from 'moment'
import StatusChangeComponent from "./StatusChangeComponent";
import { VEmojiPicker } from 'v-emoji-picker';
// import LinkPreview from '@ashwamegh/vue-link-preview'
import { VueEditor } from "vue2-editor";


export default {
    components: {
        StatusChangeComponent,
        VEmojiPicker,
        // LinkPreview
           VueEditor
    },
    props:{
        send_message_url: {
            type: String,
            required: true
        },
        add_people_url: {
            type: String,
            required: true
        },
        remove_people_url: {
            type: String,
            required: true
        },
        assign_role_url: {
            type: String,
            required: true
        },
        leave_group_url: {
            type: String,
            required: true
        },
        delete_group_url: {
            type: String,
            required: true
        },
        app_url: {
            type: String,
            required: true
        },
        files_url: {
            type: String,
            required: true
        },
        group:{
            type: Object,
            required: true
        },
        user:{
            type: Object,
            required:true
        },
        connected_users:{
            required:true
        },
        forward_message_url:{
            type: String,
            required:true
        },
        all_connected_users:{
            required:true
        },
        message_remove_url:{
            required:true
        },
        my_role:{
            required:true
        },
        load_more_url:{
            type: String,
            required:true
        },
        can_add_people:{
            default : true,
        },
        can_delete_group:{
            default : true,
        },
        can_manage_roles:{
            default : true,
        },
        read_only:{
            default : false,
        },
        can_file_upload:{
            default : true
        },
        asset_type:{
            default: ''
        },
        single_threads:{
            required: false,
        },
        make_read_only_url:{
            required:true,
        }
    },
    data() {
        return {
            customToolbar: [

                [{ header: [false, 1, 2, 3, 4, 5, 6] }],

                ["bold", "italic", "underline", "strike"],

                ["blockquote"],

                [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],

                [{ script: "sub" }, { script: "super" }],
                ["link"],
[{ 'color': [] }, { 'background': [] }],
                [{ direction: "rtl" }],
                ["clean"]
            ],
          baseUrl: Laravel.baseUrl,
          typing : false,
            messages: [],
            newMessage: '',
            users:[],
            activeUser: false,
            typingTimer: false,
            file: '',
            emoji: false,
            record_status : false,
            recorder: '',
            gumStream: '',
            preview_url : null,
            file_name_preview : null,
            addPadding : false,
            add_user: '',
            assignable_user:'',
            assignable_role:'',
            //search
            keywords:'',
            search_result_bar: false,
            search_result_count : 0,
            search_result_ids: [],
            search_index:0,
            //quote
            replying : false,
            replying_text:'',
            replying_to: null,
            //
            imageSrc : '',
            //forward
            forward_conversation : '',
            forward_addon_text: null,
            //audio second
            seconds : 0,
            timing : '',
            timerId : '',
            timingIntervalId:'',
            //load More
            current_conversation_ids: new Array(),
            loadable : true,
            only_threads : '',
        }
    },
    created() {
        console.log('lis')
        Echo.private('group-chat.'+this.group.id)
            .listen('GroupChatEvent', (e) => {
                this.only_threads.push({
                    conversation : e.conversation,
                    conversation_id : e.conversation.id,
                    created_at:e.thread.created_at,
                    group_id: e.group.id,
                    id:e.thread.id,
                    updated_at: e.thread.updated_at,
                    user:e.user,
                    user_id:e.user.id,
                    reply:e.conversation.reply,
                    deleted_by_to:'0'

                })
                this.reset();
                this.$forceUpdate()
            })
            .listenForWhisper('typing', (e) => {
                this.typing = true;
                setTimeout(() => {
                    this.typing = false
                }, 1500);

            });
    },

    mounted() {
        if (typeof this.single_threads == "object"){
          this.only_threads = Object.keys(this.single_threads).map((key) => {
            return this.single_threads[key]
          })
        }


        if (this.only_threads.length < 20){
            this.loadable = false;
        }
        if (window._rtl) {
            this.$refs.contenteditor.quill.format('direction', 'rtl');
            this.$refs.contenteditor.quill.format('align', 'right');
         }
    },

    methods: {
        sendMessage() {
          this.emoji = false;
          let config = { headers: { 'Content-Type': 'multipart/form-data' } }
            let formData = new FormData();
            if(this.replying_to){
                formData.append('reply', this.replying_to);
            }
            formData.append('file_attach', this.file);
            formData.append('message', this.newMessage);
            formData.append('user_id', this.user.id);
            formData.append('group_id', this.group.id);

            axios.post(this.send_message_url, formData, config).then((response) => {
                if (response.data.empty){
                    return 0;
                }
                this.messagePush(response.data.thread)
                this.replying = false;
                this.reset();
                this.cleanReply();
                this.$forceUpdate();
                this.closePreview();
            }).catch((error) => {
                console.log(error);
            });
            this.newMessage = '';

        },

        deleteMessage(thread){
            this.cleanReply();
            let formData = new FormData();
            formData.append('thread_id', thread.id);
            axios.post(this.message_remove_url, formData,).then((response) => {
                if (response.data.success){
                    const index = this.only_threads.indexOf(thread);
                    if (index > -1) {
                        this.only_threads.splice(index, 1);
                    }
                    toastr.success("Message deleted!");
                    this.$forceUpdate()
                }else{
                    toastr.error("Oops! something went wrong!");
                }
            }).catch((error) => {
                console.log(error);
            });
        },

        sendTypingEvent() {
            Echo.private('group-chat.'+this.group.id)
                .whisper('typing',{
                    name: this.user
                });
        },

        add_new_user(){
            let formData = new FormData();
            formData.append('user_id', this.add_user);
            formData.append('group_id', this.group.id);
            axios.post(this.add_people_url, formData).then((response) => {
                toastr.success("User successfully added!");
                window.location.reload();
            }).catch((error) => {
                console.log(error);
            });
        },

        remove_people(id){
            let formData = new FormData();
            formData.append('user_id', id);
            formData.append('group_id', this.group.id);
            axios.post(this.remove_people_url, formData).then((response) => {
                toastr.success("User successfully removed!");
                window.location.reload();
            }).catch((error) => {
                console.log(error);
            });
        },

        leave_group(){
            let values = confirm('Do you want to leave?')

            if (values){
                let formData = new FormData();
                formData.append('user_id', this.user.id);
                formData.append('group_id', this.group.id);
                axios.post(this.leave_group_url, formData).then((response) => {
                    toastr.success("You successfully leave this group!");
                    window.location.href = response.data.url
                }).catch((error) => {
                    console.log(error);
                });
            }
        },

        delete_group(){
            let values = confirm('Do you want to proceed?')

            if (values){
                let formData = new FormData();
                formData.append('group_id', this.group.id);
                axios.post(this.delete_group_url, formData).then((response) => {
                    if (response.data.notPermitted){
                        toastr.error("You can't perform this action!");
                    }else{
                        toastr.success("Your group successfully deleted");
                        window.location.href = response.data.url
                    }

                }).catch((error) => {
                    console.log(error);
                });
            }
        },

        make_read_only(type=null){
            let values = confirm('Do you want to proceed?')

            if (values){
                let formData = new FormData();
                formData.append('group_id', this.group.id);
                formData.append('type', type);
                axios.post(this.make_read_only_url, formData).then((response) => {
                    if (response.data.notPermitted){
                        toastr.error("You can't perform this action!");
                    }else{
                        toastr.success("Your group successfully mark as read only");
                        window.location.href = response.data.url
                    }

                }).catch((error) => {
                    console.log(error);
                });
            }
        },

        search(){
            this.cleanReply();
            this.search_index = 0;
            this.search_result_ids = [];
            this.search_index = 0;

            $('#chat_search').modal('hide');
            this.search_result_bar = true;
            let elements = document.getElementsByClassName('textmsg'); // get the elements

            for (let i = 0; i < elements.length; i++) {
                if (elements[i].innerHTML.indexOf(this.keywords) > -1) {
                    this.search_result_count += 1;
                    this.search_result_ids.push(elements[i].id)
                }
            }

            for (let k = 0; k < elements.length; k++) {
                if (elements[k].innerHTML.indexOf(this.keywords) > -1) {
                    let targetElement = document.getElementById(elements[k].id)
                    this.scrollIfNeeded(targetElement, document.getElementById('chat_container'));
                    $('#'+elements[k].id).css('background-color','rgb(177 168 104)');
                    break;
                }
            }
        },

        scroll_to_search(){
          if(this.search_index+1 < this.search_result_ids.length){
              this.search_index = this.search_index + 1;
              let targetElement = document.getElementById(this.search_result_ids[this.search_index]);
              this.scrollIfNeeded(targetElement, document.getElementById('chat_container'));
              $('#'+this.search_result_ids[this.search_index]).css('background-color','rgb(177 168 104)');
          }
        },

        scroll_to_search_up(){

            if (this.search_index >= 1) {
                this.search_index -= 1;
                let targetElement = document.getElementById(this.search_result_ids[this.search_index]);
                this.scrollIfNeeded(targetElement, document.getElementById('chat_container'));
                $('#' + this.search_result_ids[this.search_index]).css('background-color', 'rgb(177 168 104)');
            }
        },

        scrollIfNeeded(element, container) {
            if (element.offsetTop < container.scrollTop) {
                container.scrollTop = element.offsetTop-70;
            } else {
                const offsetBottom = element.offsetTop + element.offsetHeight;
                const scrollBottom = container.scrollTop + container.offsetHeight;
                if (offsetBottom > scrollBottom) {
                    container.scrollTop = offsetBottom - container.offsetHeight;
                }
            }
        },

        close_search_bar(){
            let elements = document.getElementsByClassName('textmsg'); // get the elements

            for (let i = 0; i < elements.length; i++) {
                if (elements[i].innerHTML.indexOf(this.keywords) > -1) {
                    $('#'+elements[i].id).css('background-color','unset');

                }
            }
            this.keywords = '';
            this.search_result_bar =  false;
            this.search_result_count = 0;
            this.search_result_ids =  [];
            this.search_index = 0;
        },

        diffHuman(date){
          if (date){
            return moment(date).fromNow();
          }
        },

        reset() {
            this.file = null;
            this.$refs.file.value = null;
        },

        onChangeFileUpload(){
                this.file = this.$refs.file.files[0];
        },

       selectEmoji(emoji) {
             let lastIndex = this.$refs.contenteditor.quill.getText().length - 1;
             this.$refs.contenteditor.quill.insertText(lastIndex, emoji.data);
             this.$refs.contenteditor.quill.focus();
        },

        assign_role_to_user(){
            let formData = new FormData();
            formData.append('user_id', this.assignable_user);
            formData.append('role_id', this.assignable_role);
            formData.append('group_id', this.group.id);
            axios.post(this.assign_role_url, formData).then((response) => {
                if (response.data.notPermitted){
                    toastr.error("You can't perform this action!");
                }else{
                    toastr.success("User in a specific rule!");
                    window.location.reload();
                }

            }).catch((error) => {
                console.log(error);
            });
        },

        toggleRecording() {
          this.emoji = false;
          if (this.recorder && this.recorder.state == "recording") {
                this.record_status = false;
                this.recorder.stop();
                this.gumStream.getAudioTracks()[0].stop();
            } else {
                navigator.mediaDevices.getUserMedia({
                    audio: true
                }).then((stream) => {
                    this.record_status = true;
                    this.timingIntervalId = window.setInterval(() => {
                        this.incrementSeconds()
                    }, 1000);
                    this.gumStream = stream;
                    this.recorder = new MediaRecorder(stream);
                    this.recorder.ondataavailable = (e) =>{
                        clearInterval(this.timingIntervalId);
                        this.seconds = 0;
                        let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                        let formData = new FormData();
                        formData.append('reply', this.replying_to);
                        formData.append('file_attach', e.data);
                        formData.append('message', this.newMessage);
                        formData.append('user_id', this.user.id);
                        formData.append('group_id', this.group.id);

                        axios.post(this.send_message_url, formData, config).then((response) => {
                            this.messagePush(response.data.thread)
                            this.reset();
                            this.quote_close_preview();
                            this.$forceUpdate()
                        }).catch((error) => {
                            console.log(error);
                        });
                        this.newMessage = '';
                    };
                    this.recorder.start();
                });
            }
        },

        incrementSeconds() {
            this.seconds += 1;
            this.timing = this.timeConverter(this.seconds);
        },

        timeConverter(time) {
            // Hours, minutes and seconds
            let hrs = ~~(time / 3600);
            let mins = ~~((time % 3600) / 60);
            let secs = ~~time % 60;

            let ret = "";
            if (hrs > 0) {
                ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
            }
            ret += "" + mins + ":" + (secs < 10 ? "0" : "");
            ret += "" + secs;
            return ret;
        },

        messagePush(message){
            this.only_threads.push({
                conversation : message.conversation,
                conversation_id : message.conversation.id,
                created_at:message.created_at,
                group_id: message.group_id,
                id:message.id,
                updated_at: message.updated_at,
                user:message.user,
                user_id:message.user.id,
                reply:message.reply,
                deleted_by_to:'0'

            })
        },

        onFileChange(e) {
          clearInterval(this.intervalId)
          const file = e.target.files[0];
            this.addPadding = true;
            this.$refs.contenteditor.quill.focus();
            if (['image/jpg', 'image/png', 'image/jpeg', 'image/JPG', 'image/PNG', 'image/JPEG'].includes(file['type'])){
               return this.preview_url = URL.createObjectURL(file);
            }
            return this.file_name_preview = file.name
        },

        closePreview(){
            this.preview_url = null
            this.file_name_preview = null
            this.reset();
            this.addPadding = false;
        },

        handleClick(preview) {
            console.log('click', preview.domain, preview.title, preview.description, preview.img)
        },

        url_maker(text) {
            if(!text){
                return text;
            }
             return text.replace(/((http|https|ftp):\/\/[\w?=&.\/-;#~%-]+(?![\w\s?&.\/;#~%"=-]*>))/g, '<a href="$1" target="_blank">$1</a> ');
            // text = JSON.stringify(text)
            // let urlRegex = /(((https?:\/\/)|(www\.))[^\s]+)/g;
            // let replaceable_text = text.replace(urlRegex, function(url,b,c) {
            //     let url2 = (c == 'www.') ?  'http://' +url : url;
            //     return '<a href="' +url2+ '" target="_blank">' +' '+ url + '</a>';
            // })
            // return replaceable_text
        },

        linkable(text){
            text = JSON.stringify(text)
            let match = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.exec(text);
            if (match){
                return true;
            }else{
                return false
            }
        },

        //quote

        reply(c){
            this.replying = true;
            this.replying_text = c.message
            this.replying_to = c.id
            $('html, body').animate({ scrollTop: this.$refs.contenteditor.quill.offset().top }, 'slow');

        },

        cleanReply(){
            this.replying = false;
            this.replying_text = ''
            this.replying_to = null
        },

        forward(c,u,id){
          $('#forwordClick'+id).text(' Sent');
          $('#forwordClick'+id).attr('class', 'bg-less');
            this.cleanReply();
            c.to_id = u.id;
            c.from_id = this.user.id;
            c.group_id = this.group.id;
            c.forward = c.id;
            if (this.forward_addon_text){
                c.message = this.forward_addon_text;
            }else{
                c.message = null;
            }
            axios.post(this.forward_message_url, c).then((response) => {
                // this.checkNewMessage()
                // this.reset();
                // this.$forceUpdate()
            }).catch((error) => {
                console.log(error);
            });
        },

        forwardModalOpen(c){
            this.forward_conversation = c;
            $('#forward').modal('show');
        },

        quote_close_preview(){
            this.replying = false;
        },

        imageViewLargeScreen(url){
            this.imageSrc = url;
            $('#imageView').modal('show');
        },

        loadMore(){
            this.current_conversation_ids = [];
            for (let c in this.only_threads) {
                this.current_conversation_ids.push(this.only_threads[c].id)
            }
            let formData = new FormData();
            formData.append('ids', JSON.stringify(this.current_conversation_ids));
            formData.append('group_id', this.group.id);
            axios.post(this.load_more_url, formData).then((response) => {
                if (response.data.success){
                    if (response.data.threads){
                        for( let index in response.data.threads){
                            this.only_threads.unshift(response.data.threads[index])
                        }
                        this.$forceUpdate()
                    }else{
                        this.loadable = false;
                    }
                }else{
                    toastr.error("Oops! something went wrong!");
                }
            }).catch((error) => {
                console.log(error);
            });
        }

    }
}
</script>

<style>
.pt-100px {
    padding-top: 100px;
}
.auto_height{
    max-height: 135px!important;
}
.link-preview-section{
    background: white!important;
}

.remove-list{
    background: #efefef;
    border-radius: 10px;
}
.search_indicator{
    background: #ececec;
    padding: 10px;
    border-radius: 10px;
}
/*quote*/
.replying-box {
    background: #dde3f1;
    border-radius: 15px;
}
.mb-2-3{
    margin-bottom: 2.3rem!important;
}
.close-quote-preview {
    float: right;
    margin-top: -57px;
}
.cursor-pointer{
    cursor: pointer;
}
.audio-padding{
    padding: 10px 5px 5px 5px;
}
.border-radius-25{
    border-radius: 25px;
}
.sender_reply_p{
    padding: 10px 20px 5px 20px!important;
    background: #beacde!important;
    border-radius: 25px 0 0 0!important;
}
.reply_p{
    padding: 10px 20px 5px 20px!important;
    background: #b9c4f3!important;
    border-radius: 0 25px 0 0!important;
}
.users-box{
    max-height: 300px;
    width: 100%;
    overflow-x: hidden;
    overflow-y: scroll;
}
.timer-display{
    display: grid;
    grid-template-columns: 0.5fr 2fr 0.5fr;
    grid-column-gap: 5px;
}
.timer{
    font-size: 30px;
    color: red;
}
.timer-padding{
    padding: 13px 13px 5px 13px;
}
.stop-button-padding{
    padding-top: 10px;
}
.imoji-box-open {
    color: #0340ff;
    background: #4a6ee061;
    border-radius: 10px;
}
.microphone-red{
    color: red;
}
.input_thumb img{
  border-radius: 20px;
}
.bg-less {
  background: transparent;
  color: var(--base_color);
  font-size: 12px;
  font-weight: 500;
  border: 1px solid var(--border_color);
  border-radius: 32px;
  padding: 7px 24px 5px 23px;
  text-transform: uppercase;
  overflow: hidden;
  transition: .3s;
  height: 32px;
}
.forward-image{
  width: 60px;
}
.svg_send_button{
  border: none;
  background: #F5F8FF;
}
.svg_send_button svg{
  transform: rotate(180deg);
}
.ql-container .ql-editor {
    min-height: 60px;
    font-size: 16px;
}

.quillWrapper {
    width: 100%;
}

.ql-container {
    min-height: 60px !important;
    border-bottom: 0 !important;
}

.chat_view_list .chat_input_box .input-group-append {
    border-radius: 0 0 10px 10px;
    border: 1px solid #ccc;
    flex: 1 1 0;
    justify-content: flex-end;
    border-top: 0;
    margin: 0;
}
@media (max-width: 575.98px) {
    .quillWrapper .ql-toolbar.ql-snow {
        display: none;
    }

    .ql-container {
    border-top:  1px solid #ccc !important;
}
}
.preview_imgs{
    top: -65px;
}

.chat_single p{
    margin: 0 !important;
    padding: 5px 10px !important;
}

.chat_date {
        display: flex;
    flex-direction: row-reverse;
    gap: 5px;
}
</style>
