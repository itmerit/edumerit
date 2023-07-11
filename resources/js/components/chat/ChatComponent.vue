<template>
    <div>
        <div class="chat_view_list ">
            <div class="box_header">
                <div class="main-title">
                    <div class="dropdown CRM_dropdown">
                        <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu3" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            {{ this.to_user.first_name }} {{ this.to_user.last_name }}
                            <span v-if="this.to_user.active_status.status == 1" class="active_chat"></span>
                            <span v-else-if="this.to_user.active_status.status == 0" class="inactive_chat"></span>
                            <span v-else-if="this.to_user.active_status.status == 3" class="busy_chat"></span>
                            <span v-else class="away_chat"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu3">
                            <a data-toggle="modal" data-target="#chat_search" class="dropdown-item" type="button">
                                {{ __('search') }}
                            </a>
                            <a :href="files_url" class="dropdown-item" type="button">
                                {{ __('chat_files') }}
                            </a>
                        </div>
                    </div>

                </div>

                <StatusChangeComponent :app_url="this.app_url" :user="this.from_user"
                    :status="this.from_user.active_status.status" />
            </div>


            <div class="chat_view_list_inner crm_full_height ">
                <div v-if="search_result_bar" class="search_indicator py-2">
                    <div class="float-left">
                        <p class="mb-0">{{ __('showing') }} {{ this.search_result_count === 0 ? this.search_result_count
                                : this.search_index + 1
                        }} {{ __('of') }} {{ this.search_result_count }} {{ __('results') }}</p>
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
                        <p class="text-center" v-show="loadable">
                            <a class="cursor-pointer" @click="loadMore()">
                                {{ __('load_more') }} <span class="ti-arrow-up"></span>
                            </a>
                        </p>

                        <div :id="'target' + index" v-for="(conversation, index) in this.conversations">
                            <div v-if="parseInt(conversation.from_id) != from_user.id && conversation.deleted_by_to == '0'"
                                class="chat_single d-flex" style="overflow: unset">
                                <div class="thumb mr_20">
                                    <a v-if="to_user.avatar" href="#"><img :src="'/' + to_user.avatar" alt=""></a>
                                    <a v-else-if="to_user.avatar_url" href="#"><img :src="baseUrl + to_user.avatar_url"
                                            alt=""></a>
                                    <a v-else href="#"><img
                                            :src="baseUrl + asset_type + '/chat/images/spondon-icon.png'" alt=""></a>
                                </div>
                                <div class="chat_text_info_wraper d-flex align-items-center">
                                    <div class="chat_text_info">
                                        <div v-if="conversation.reply">
                                            <p class="reply_p" v-if="conversation.reply.message_type == 0" v-html="url_maker(conversation.reply.message)"></p>
                                            <p class="reply_p" v-else>{{ __('attachment') }}</p>
                                        </div>

                                        <div v-if="conversation.forward_from">
                                            <p class="reply_p font-italic"
                                                v-if="conversation.forward_from.message_type == 0">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <p v-html="url_maker(conversation.forward_from.message)"></p>
                                            </p>
                                            <div class="reply_p audio-padding"
                                                v-if="conversation.forward_from.message_type == 4">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <audio :src="baseUrl + conversation.forward_from.file_name"
                                                    controls></audio>
                                            </div>
                                            <div v-else-if="conversation.forward_from.message_type == 5"
                                                class="reply_p p-3 w-100">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <video class="w-100 border-radius-25" controls>
                                                    <source :src="baseUrl + conversation.forward_from.file_name"
                                                        type="video/mp4">
                                                </video>
                                            </div>
                                            <div class="reply_p p-3"
                                                v-else-if="conversation.forward_from.message_type == 1">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <img class="border-radius-25 cursor-pointer"
                                                    @click="imageViewLargeScreen(baseUrl + conversation.forward_from.file_name)"
                                                    v-if="conversation.forward_from.file_name"
                                                    :src="baseUrl + conversation.forward_from.file_name" alt="">
                                                <img class="border-radius-25" v-else
                                                    :src="baseUrl + asset_type + '/chat/images/msg_img.png'" alt="">
                                            </div>
                                            <p class="reply_p"
                                                v-else-if="conversation.message_type == 2 || conversation.message_type == 3">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <u><a style="color: white;"
                                                        :href="app_url + 'chat/file/download/' + conversation.forward_from.id">{{
                                                                conversation.forward_from.original_file_name
                                                        }}</a></u>
                                            </p>
                                        </div>


                                        <div class="audio-padding" v-if="conversation.message_type == 4">
                                            <audio :src="baseUrl + conversation.file_name" controls></audio>
                                        </div>
                                        <div v-else-if="conversation.message_type == 5" class="p-3 w-100">
                                            <video class="w-100 border-radius-25" controls>
                                                <source :src="baseUrl + conversation.file_name" type="video/mp4">
                                            </video>
                                        </div>
                                        <p v-else-if="conversation.message_type == 0">
                                            <span :id="'text' + index" class="textmsg"
                                                v-html="url_maker(conversation.message)"></span>
                                            <!--                                        <vue-link-preview v-if="linkable(conversation.message)" url="https://vuejs.org/" @click="handleClick"></vue-link-preview>-->

                                        </p>

                                        <div class="p-3" v-else-if="conversation.message_type == 1">
                                            <img class="border-radius-25 cursor-pointer"
                                                @click="imageViewLargeScreen(baseUrl + conversation.file_name)"
                                                v-if="conversation.file_name" :src="baseUrl + conversation.file_name"
                                                alt="">
                                            <img class="border-radius-25" v-else
                                                :src="baseUrl + asset_type + '/chat/images/msg_img.png'" alt="">
                                        </div>
                                        <p v-else-if="conversation.message_type == 2 || conversation.message_type == 3">
                                            <u><a style="color: white;"
                                                    :href="app_url + 'chat/file/download/' + conversation.id">{{
                                                            conversation.original_file_name
                                                    }}</a></u>
                                        </p>
                                        <p v-else>{{ conversation.file_name }}</p>
                                    </div>
                                    <span class="chat_date ml_15 ml-2">{{ diffHuman(conversation.created_at) }}
                                        <div class="dropdown">
                                            <a class="" id="dropdownMenu4" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <span class="ti-angle-down cursor-pointer"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenu4">
                                                <a @click="reply(conversation)" class="dropdown-item cursor-pointer">
                                                    {{ __('quote') }}
                                                </a>
                                                <a @click="forwardModalOpen(conversation)"
                                                    class="dropdown-item cursor-pointer">
                                                    {{ __('forward') }}
                                                </a>
                                                <a @click="deleteMessage(conversation)"
                                                    class="dropdown-item cursor-pointer">
                                                    {{ __('delete') }}
                                                </a>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div v-if="conversation.from_id == from_user.id" class="chat_single d-flex sender_chat"
                                style="overflow: unset">
                                <div class="chat_text_info_wraper d-flex align-items-center">
                                    <span class="chat_date ml_15">
                                        {{ diffHuman(conversation.created_at) }}
                                        <div class="dropdown">
                                            <a class="" id="dropdownMenu5" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <span class="ti-angle-down cursor-pointer"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenu5">
                                                <a @click="reply(conversation)" class="dropdown-item cursor-pointer">
                                                    {{ __('quote') }}
                                                </a>
                                                <a @click="forwardModalOpen(conversation)"
                                                    class="dropdown-item cursor-pointer">
                                                    {{ __('forward') }}
                                                </a>
                                                <a @click="deleteMessage(conversation)"
                                                    class="dropdown-item cursor-pointer">
                                                    {{ __('delete') }}
                                                </a>
                                            </div>
                                        </div>
                                    </span>
                                    <div class="chat_text_info">
                                        <div v-if="conversation.reply">
                                            <p class="sender_reply_p" v-if="conversation.reply.message_type == 0" v-html="url_maker(conversation.reply.message)"></p>
                                            <p class="sender_reply_p" v-else>{{ __('attachment') }}</p>
                                        </div>

                                        <div v-if="conversation.forward_from">
                                            <p class="sender_reply_p font-italic"
                                                v-if="conversation.forward_from.message_type == 0">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <span v-html="url_maker(conversation.forward_from.message)"></span>
                                            </p>
                                            <div class="sender_reply_p audio-padding"
                                                v-if="conversation.forward_from.message_type == 4">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <audio :src="baseUrl + conversation.forward_from.file_name"
                                                    controls></audio>
                                            </div>
                                            <div v-else-if="conversation.forward_from.message_type == 5"
                                                class="sender_reply_p p-3 w-100">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <video class="w-100 border-radius-25" controls>
                                                    <source :src="baseUrl + conversation.forward_from.file_name"
                                                        type="video/mp4">
                                                </video>
                                            </div>
                                            <div class="sender_reply_p p-3"
                                                v-else-if="conversation.forward_from.message_type == 1">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <img class="border-radius-25 cursor-pointer"
                                                    @click="imageViewLargeScreen(baseUrl + conversation.forward_from.file_name)"
                                                    v-if="conversation.forward_from.file_name"
                                                    :src="baseUrl + conversation.forward_from.file_name" alt="">
                                                <img class="border-radius-25" v-else
                                                    :src="baseUrl + asset_type + '/chat/images/msg_img.png'" alt="">
                                            </div>
                                            <p class="sender_reply_p"
                                                v-else-if="conversation.message_type == 2 || conversation.message_type == 3">
                                                <span>{{ __('forwarded_message') }} : </span><br>
                                                <u><a style="color: white;"
                                                        :href="app_url + 'chat/file/download/' + conversation.forward_from.id">{{
                                                                conversation.forward_from.original_file_name
                                                        }}</a></u>
                                            </p>
                                        </div>

                                        <div class="audio-padding" v-if="conversation.message_type == 4">
                                            <audio :src="baseUrl + conversation.file_name" controls></audio>
                                        </div>
                                        <div v-else-if="conversation.message_type == 5" class="p-3 w-100">
                                            <video class="w-100 border-radius-25" controls>
                                                <source :src="baseUrl + conversation.file_name" type="video/mp4">
                                            </video>
                                        </div>
                                        <p v-else-if="conversation.message_type == 0">
                                            <span :id="'text' + index" class="textmsg"
                                                v-html="url_maker(conversation.message)"></span>
                                            <!--                                        <vue-link-preview v-if="linkable(conversation.message)" url="https://vuejs.org/" @click="handleClick"></vue-link-preview>-->

                                        </p>

                                        <div class="p-3" v-else-if="conversation.message_type == 1">
                                            <img class="border-radius-25 cursor-pointer"
                                                @click="imageViewLargeScreen(baseUrl + conversation.file_name)"
                                                v-if="conversation.file_name" :src="baseUrl + conversation.file_name"
                                                alt="">
                                            <img class="border-radius-25" v-else
                                                :src="baseUrl + asset_type + '/chat/images/msg_img.png'" alt="">
                                        </div>
                                        <p v-else-if="conversation.message_type == 2 || conversation.message_type == 3">
                                            <u><a style="color: white;"
                                                    :href="app_url + 'chat/file/download/' + conversation.id">{{
                                                            conversation.original_file_name
                                                    }}</a></u>
                                        </p>
                                        <p v-else>{{ conversation.file_name }}</p>
                                    </div>
                                </div>
                                <div class="thumb">
                                    <a v-if="from_user.avatar" href="#"><img :src="'/' + from_user.avatar" alt=""></a>
                                    <a v-else-if="from_user.avatar_url" href="#"><img
                                            :src="baseUrl + from_user.avatar_url" alt=""></a>
                                    <a v-else href="#"><img
                                            :src="baseUrl + asset_type + '/chat/images/spondon-icon.png'" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!to_user.blocked" v-show="replying" class="bg-gray-200 p-3 replying-box mb-2-3 ml-5">
                    <p>
                        <strong class="text-info">{{ __('replying') }} : </strong><br>
                        <span v-html="replying_text"></span>
                    </p>
                    <span @click="quote_close_preview" class="close-quote-preview cursor-pointer"><i
                            class="fa fa-times"></i></span>

                </div>
                <form v-if="!to_user.blocked" v-on:submit.prevent="sendMessage" :class="{ 'pt-100px': addPadding }">
                    <div class="chat_input_box d-flex align-items-center">
                        <div class="input_thumb">
                            <img v-if="from_user.avatar" :src="'/' + from_user.avatar" alt="">
                            <img v-if="from_user.avatar_url" :src="baseUrl + from_user.avatar_url" alt="">
                            <img v-else :src="baseUrl + asset_type + '/chat/images/spondon-icon.png'" alt="">
                        </div>
                        <div class="input-group ">
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

                            <div class="input-group-append">
                                <button class="btn pr-2" type="button" @click="toggleRecording"> <i
                                        :class="{ 'microphone-red': record_status }" class="ti-microphone-alt"></i>
                                </button>
                                <button class="btn" type="button" @click="emoji = !emoji"> <i
                                        :class="{ 'imoji-box-open': emoji }" class="ti-face-smile img_toggle"></i>
                                </button>
                                <button v-show="can_file_upload" class="btn" type="button"> <i class="ti-clip"></i>
                                    <input type="file" @change="onFileChange" id="imgInp" ref="file"
                                        v-on:change="onChangeFileUpload()"
                                        accept=".jpg,.jpeg,.png,.doc,.docx,.pdf,.mp4,.3gp,.webm">
                                </button>
                                <button class="btn svg_send_button" @click="sendMessage" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 40 40" width="20px" height="20px">
                                        <g id="surface57286370">
                                            <rect x="0" y="0" width="40" height="40"
                                                style="fill: #F5F8FF; fill-opacity: 1; stroke: none;" />
                                            <path
                                                style="stroke: none; fill-rule: nonzero; fill: #F5F8FF; fill-opacity: 1;"
                                                d="M 44 7 L 4 23 L 44 39 L 37 23 Z M 36 23 L 17 23 L 35 16 L 36 22 Z M 36 23 " />
                                            <path
                                                style="stroke: none; fill-rule: nonzero; fill: #989898 ; fill-opacity: 1;"
                                                d="M 40.210938 10.667969 L 35.167969 22.199219 L 34.816406 23 L 35.167969 23.800781 L 40.210938 35.332031 L 9.386719 23 L 40.210938 10.667969 M 44 7 L 4 23 L 44 39 L 37 23 Z M 44 7 " />
                                            <path
                                                style="stroke: none; fill-rule: nonzero; fill:#989898 ; fill-opacity: 1;"
                                                d="M 36 22 L 35 16 L 17 23 L 34 30 L 32 25 L 24 23 L 36 23 Z M 27.660156 21 L 33.433594 18.757812 L 33.804688 21 Z M 27.660156 21 " />
                                        </g>
                                    </svg>
                                </button>

                            </div>
                        </div>
                    </div>
                    <div class="emoji_box" :class="{ active: emoji }">
                        <VEmojiPicker @select="selectEmoji" />
                    </div>
                </form>
            </div>
            <span class="text-muted" v-if="typing">{{ to_user.first_name }} {{ __('is_typing') }}...</span>
            <div class="mt-2 bg-white timer-display" v-if="record_status">
                <div class="timer-padding">
                    <span id="timer" class="timer"> {{ timing }} </span>
                </div>
                <div class="timer-padding">
                    <img :src="baseUrl + asset_type + '/chat/images/recording.gif'" alt=""
                        style="width: 140px; height: 35px">
                    <span class="text-muted">
                        {{ __('your_voice_is_recording') }}...
                    </span>
                </div>
                <div class="stop-button-padding">
                    <a href="#" @click="toggleRecording">
                        <img :src="baseUrl + asset_type + '/chat/images/recording-stop.png'" alt=""
                            style="height: 35px; width: 35px;">
                    </a>
                </div>
            </div>
        </div>

        <div class="modal fade admin-query" id="chat_search" aria-modal="true">
            <div class="modal-dialog modal_800px modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ __('search') }}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="primary_input mb_20">
                                    <label class="primary_input_label" for="">{{ __('keywords') }}</label>
                                    <input class="primary_input_field" placeholder="-" type="text" v-model="keywords">
                                </div>
                                <br>
                                <button @click="search()" class="primary-btn radius_30px  fix-gr-bg" href="#">{{
                                        __('search')
                                }}</button>
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
                            {{ __('preview') }}
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
                            {{ __('forward') }} {{ __('message') }}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="ti-close "></i>
                        </button>
                    </div>
                    <div class="modal-body" v-if="forward_conversation">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card mb-2">
                                    <div class="card-header">
                                        "<span v-html="this.forward_conversation.message" ></span>"<br>
                                        - {{ this.forward_conversation.from_user.first_name }}
                                    </div>
                                </div>
                                <div class="primary_input mb_20">
                                    <input v-model="forward_addon_text" class="primary_input_field"
                                        :placeholder="__('write_something_Optional')" type="text">
                                </div>
                            </div>
                            <div class="users-box">
                                <div class="col-xl-12 mt-1 mb-1" v-for="(list_user, indexFor) in connected_users">
                                    <div class="single_list d-flex justify-content-between">
                                        <div class="thumb">
                                            <a href="#">
                                                <img class="forward-image" v-if="list_user.avatar"
                                                    :src="'/' + list_user.avatar" alt="">
                                                <img class="forward-image" v-else-if="list_user.avatar_url"
                                                    :src="baseUrl + list_user.avatar_url" alt="">
                                                <img class="forward-image" v-else
                                                    :src="baseUrl + asset_type + '/chat/images/spondon-icon.png'"
                                                    alt="">
                                                <h4>{{ list_user.first_name }}</h4>
                                            </a>
                                        </div>
                                        <div class="mt-4">
                                            <a href="#" :id="'forwordClick' + indexFor"
                                                v-if="forward_conversation.forward_from"
                                                @click="forward(forward_conversation.forward_from, list_user, indexFor)"
                                                class="primary-btn radius_30px  fix-gr-bg"><i class="ti-share"></i>{{
                                                        __('send')
                                                }}</a>
                                            <a href="#" :id="'forwordClick' + indexFor" v-else
                                                @click="forward(forward_conversation, list_user, indexFor)"
                                                class="primary-btn radius_30px  fix-gr-bg"><i class="ti-share"></i>{{
                                                        __('send')
                                                }}</a>
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
        VueEditor
        // LinkPreview
    },
    props: {
        send_message_url: {
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
        from_user: {
            type: Object,
            required: true
        },
        to_user: {
            type: Object,
            required: true
        },
        loaded_conversations: {
            required: true
        },
        connected_users: {
            type: Array,
            required: true
        },
        forward_message_url: {
            type: String,
            required: true
        },
        delete_message_url: {
            type: String,
            required: true
        },
        load_more_url: {
            type: String,
            required: true
        },
        can_file_upload: {
            default: true
        },
        asset_type: {
            default: ''
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
            typing: false,
            messages: [],
            newMessage: null,
            users: [],
            activeUser: false,
            typingTimer: false,
            file: null,
            emoji: false,
            record_status: false,
            recorder: '',
            gumStream: '',
            preview_url: null,
            file_name_preview: null,
            addPadding: false,
            conversations: '',
            //search
            keywords: '',
            search_result_bar: false,
            search_result_count: 0,
            search_result_ids: [],
            search_index: 0,
            //quote
            replying: false,
            replying_text: '',
            replying_to: null,
            //
            imageSrc: '',
            //forward
            forward_conversation: '',
            forward_addon_text: null,
            //audio secound
            seconds: 0,
            timing: '',
            timerId: '',
            timingIntervalId: '',
            //load More
            current_conversation_ids: new Array(),
            loadable: true,
        }
    },
    created() {
        Echo.private('single-chat.' + this.from_user.id)
            .listen('ChatEvent', (e) => {
                console.log('come')
                this.messagePush(e.message)
                this.reset();
                this.$forceUpdate()
            })
            .listenForWhisper('single-typing', (e) => {
                this.typing = true;
                setTimeout(() => {
                    this.typing = false
                }, 1500);
            });
    },

    mounted() {
        if (typeof this.loaded_conversations == "object") {
            this.conversations = Object.keys(this.loaded_conversations).map((key) => {
                return this.loaded_conversations[key]
            })
        }

        if (this.conversations.length < 20) {
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
            if (this.replying_to) {
                formData.append('reply', this.replying_to);
            }
            formData.append('file_attach', this.file);
            formData.append('message', this.newMessage);
            formData.append('from_id', this.from_user.id);
            formData.append('to_id', this.to_user.id);

            axios.post(this.send_message_url, formData, config).then((response) => {
                if (response.data.empty) {
                    return 0;
                }
                this.replying = false;
                this.messagePush(response.data.message)
                this.reset();
                this.cleanReply();
                this.$forceUpdate()
                this.closePreview()
            }).catch((error) => {
                console.log(error);
            });
            this.newMessage = '';
            

        },
        sendTypingEvent() {
            Echo.private('single-chat.' + this.to_user.id)
                .whisper('single-typing', {
                    name: 'kjk'
                });
        },

        diffHuman(date) {
            return moment(date).fromNow();
        },
        reset() {
            this.file = null;
            this.$refs.file.value = null;
        },
        onChangeFileUpload() {
            this.file = this.$refs.file.files[0];
        },
        selectEmoji(emoji) {
             let lastIndex = this.$refs.contenteditor.quill.getText().length - 1;
             this.$refs.contenteditor.quill.insertText(lastIndex, emoji.data);
             this.$refs.contenteditor.quill.focus();
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
                    this.recorder.ondataavailable = (e) => {
                        clearInterval(this.timingIntervalId);
                        this.seconds = 0;
                        let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                        let formData = new FormData();
                        formData.append('reply', this.replying_to);
                        formData.append('file_attach', e.data);
                        formData.append('message', this.newMessage);
                        formData.append('from_id', this.from_user.id);
                        formData.append('to_id', this.to_user.id);

                        axios.post(this.send_message_url, formData, config).then((response) => {
                            this.messagePush(response.data.message)
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

        messagePush(message) {
            this.conversations.push({
                from_id: message.from_id,
                to_id: message.to_id,
                id: message.id,
                message: message.message,
                message_type: message.message_type,
                file_name: message.file_name,
                original_file_name: message.original_file_name,
                reply: message.reply,
                forward_from: message.forward_from,
                deleted_by_to: '0'
            })
        },

        deleteMessage(c) {
            this.cleanReply();
            let formData = new FormData();
            formData.append('conversation_id', c.id);
            formData.append('user_id', this.from_user.id);

            axios.post(this.delete_message_url, formData,).then((response) => {
                if (response.data.success) {
                    const index = this.conversations.indexOf(c);
                    if (index > -1) {
                        this.conversations.splice(index, 1);
                    }
                    toastr.success("Message deleted!");
                    this.$forceUpdate()
                } else {
                    toastr.error("Oops! something went wrong!");
                }
            }).catch((error) => {
                console.log(error);
            });
        },

        onFileChange(e) {
            const file = e.target.files[0];
            this.addPadding = true;
            this.$refs.contenteditor.quill.focus();
            if (['image/jpg', 'image/png', 'image/jpeg', 'image/JPG', 'image/PNG', 'image/JPEG'].includes(file['type'])) {
                return this.preview_url = URL.createObjectURL(file);
            }
            return this.file_name_preview = file.name
        },
        closePreview() {
            this.preview_url = null
            this.file_name_preview = null
            this.reset();
            this.addPadding = false;
        },
        handleClick(preview) {
            console.log('click', preview.domain, preview.title, preview.description, preview.img)
        },
        url_maker(text) {

            if (!text) {
                return text;
            }

            return text.replace(/((http|https|ftp):\/\/[\w?=&.\/-;#~%-]+(?![\w\s?&.\/;#~%"=-]*>))/g, '<a href="$1" target="_blank">$1</a> ');

          
        },
        linkable(text) {
            text = JSON.stringify(text)
            let match = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.exec(text);
            if (match) {
                return true;
            } else {
                return false
            }
        },

        search() {
            this.search_index = 0;
            this.search_result_ids = [];
            this.search_index = 0;
            this.cleanReply();

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
                    $('#' + elements[k].id).css('background-color', 'rgb(177 168 104)');
                    break;
                }
            }
        },

        scroll_to_search() {
            if (this.search_index + 1 < this.search_result_ids.length) {
                this.search_index = this.search_index + 1;
                let targetElement = document.getElementById(this.search_result_ids[this.search_index]);
                this.scrollIfNeeded(targetElement, document.getElementById('chat_container'));
                $('#' + this.search_result_ids[this.search_index]).css('background-color', 'rgb(177 168 104)');
            }
        },

        scroll_to_search_up() {

            if (this.search_index >= 1) {
                this.search_index -= 1;
                let targetElement = document.getElementById(this.search_result_ids[this.search_index]);
                this.scrollIfNeeded(targetElement, document.getElementById('chat_container'));
                $('#' + this.search_result_ids[this.search_index]).css('background-color', 'rgb(177 168 104)');
            }
        },

        scrollIfNeeded(element, container) {
            if (element.offsetTop < container.scrollTop) {
                container.scrollTop = element.offsetTop - 70;
            } else {
                const offsetBottom = element.offsetTop + element.offsetHeight;
                const scrollBottom = container.scrollTop + container.offsetHeight;
                if (offsetBottom > scrollBottom) {
                    container.scrollTop = offsetBottom - container.offsetHeight;
                }
            }
        },

        close_search_bar() {
            let elements = document.getElementsByClassName('textmsg'); // get the elements

            for (let i = 0; i < elements.length; i++) {
                if (elements[i].innerHTML.indexOf(this.keywords) > -1) {
                    $('#' + elements[i].id).css('background-color', 'unset');

                }
            }
            this.keywords = '';
            this.search_result_bar = false;
            this.search_result_count = 0;
            this.search_result_ids = [];
            this.search_index = 0;
        },

        //quote

        reply(c) {
            this.replying = true;
            this.replying_text = c.message
            this.replying_to = c.id
            $('html, body').animate({ scrollTop: this.$refs.contenteditor.quill.offset().top }, 'slow');

        },

        cleanReply() {
            this.replying = false;
            this.replying_text = ''
            this.replying_to = null
        },

        forward(c, u, id) {
            $('#forwordClick' + id).text(' Sent');
            $('#forwordClick' + id).attr('class', 'bg-less');
            this.cleanReply();
            c.to_id = u.id;
            c.from_id = this.from_user.id;
            c.forward = c.id;
            if (this.forward_addon_text) {
                c.message = this.forward_addon_text;
            } else {
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

        forwardModalOpen(c) {
            $('#forward').modal('show');
            this.forward_conversation = c;
        },

        quote_close_preview() {
            this.replying = false;
        },

        imageViewLargeScreen(url) {
            this.imageSrc = url;
            $('#imageView').modal('show');
        },

        loadMore() {
            this.current_conversation_ids = [];
            for (let c in this.conversations) {
                this.current_conversation_ids.push(this.conversations[c].id)
            }
            let formData = new FormData();
            formData.append('ids', JSON.stringify(this.current_conversation_ids));
            formData.append('user_id', this.to_user.id);
            axios.post(this.load_more_url, formData).then((response) => {
                if (response.data.success) {
                    if (response.data.conversations) {
                        console.log(response.data.conversations)
                        for (let index in response.data.conversations) {
                            this.conversations.unshift(response.data.conversations[index])
                        }
                    } else {
                        this.loadable = false;
                    }
                } else {
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
.chat_view_list .chat_input_box {
    position: relative;
    padding: 0;
}

.pt-100px {
    padding-top: 100px;
}

.auto_height {
    max-height: 135px !important;
}

.link-preview-section {
    background: white !important;
}

.search_indicator {
    background: #ececec;
    padding: 10px;
    border-radius: 10px;
}

/*quote*/
.replying-box {
    background: #dde3f1;
    border-radius: 15px;
}

.mb-2-3 {
    margin-bottom: 2.3rem !important;
}

.close-quote-preview {
    float: right;
    margin-top: -57px;
}

.cursor-pointer {
    cursor: pointer;
}

.audio-padding {
    padding: 10px 5px 5px 5px;
}

.border-radius-25 {
    border-radius: 25px;
}

.sender_reply_p {
    padding: 10px 20px 5px 20px !important;
    background: #beacde !important;
    border-radius: 25px 0 0 0 !important;
}

.reply_p {
    padding: 10px 20px 5px 20px !important;
    background: #b9c4f3 !important;
    border-radius: 0 25px 0 0 !important;
}

.users-box {
    max-height: 300px;
    width: 100%;
    overflow-x: hidden;
    overflow-y: scroll;
}

.timer-display {
    display: grid;
    grid-template-columns: 0.5fr 2fr 0.5fr;
    grid-column-gap: 5px;
}

.timer {
    font-size: 30px;
    color: red;
}

.timer-padding {
    padding: 13px 13px 5px 13px;
}

.stop-button-padding {
    padding-top: 10px;
}

.imoji-box-open {
    color: #0340ff;
    background: #4a6ee061;
    border-radius: 10px;
}

.microphone-red {
    color: red;
}

.input_thumb img {
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

.forward-image {
    width: 60px;
}

.svg_send_button {
    border: none;
    background: #F5F8FF;
}

.svg_send_button svg {
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

html[dir="rtl"] .ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) svg {
  left: 0 !important;
  right: auto !important;
}

</style>
