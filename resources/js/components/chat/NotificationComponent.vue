<template>
    <li class="scroll_notification_list" id="notification_count">
        <a class="pulse theme_color bell_notification_clicker" @click="open_modal = !open_modal" href="#">
            <i class="far fa-comment chat-icon-size-comment"></i>
            <span class="chat_badge notification_count" >{{ count_unread }}  </span>
            <span class="pulse-ring notification_count_pulse"></span>
        </a>
        <div class="Menu_NOtification_Wrap" :class="{active:open_modal}">
            <div class="notification_Header">
                <h4>{{__('notification')}}</h4>
                <audio id="sound" :src="asset_type+'/chat/audio/notification.mp3'" muted></audio>
            </div>
            <div class="Notification_body">
                <div v-for="unread in this.unreads" class="single_notify d-flex align-items-center">
                    <div class="notify_thumb">
                        <a href="#"><img :src="asset_type + '/chat/images/spondon-icon.png'" alt=""></a>
                    </div>

                    <div class="notify_content">
                        <div v-if="unread.type == 'Modules\\Chat\\Notifications\\InvitationNotification'">
                            <a v-if="unread.data" :href="unread.data.url+'/'+unread.id">
                                <h5>{{ unread.data.user.first_name}}</h5>
                            </a>
                            <a v-else :href="unread.url+'/'+unread.id"><h5>{{ unread.user.first_name}}</h5></a>

                            <p v-if="unread.data" v-html="unread.data.message"></p>
                            <p v-else v-html="unread.message"></p>

                        </div>
                        <div v-else-if="unread.type == 'Modules\\Chat\\Notifications\\GroupCreationNotification'">
                            <a v-if="unread.data" :href="unread.data.url">
                                <h5>{{ unread.data.group.name}}</h5>
                            </a>
                            <a v-else :href="unread.url"><h5>{{ unread.group.name}}</h5></a>

                            <p> {{__('you_are_invited_in_new_group')}}!</p>
                        </div>

                        <div v-else-if="unread.type == 'Modules\\Chat\\Notifications\\GroupMessageNotification'">
                            <a v-if="unread.data" :href="unread.data.url">
                                <h5>{{ unread.data.group.name}}</h5>
                            </a>
                            <a v-else :href="unread.url"><h5>{{ unread.group.name}}</h5></a>

                            <p> {{__('new_message_in_this_group')}}!</p>
                        </div>
                        <div v-else>
                            <a v-if="unread.data" :href="redirect_url+'/'+unread.data.user.id+'/'+unread.id"><h5>{{ unread.data.user.first_name}}</h5></a>
                            <a v-else :href="redirect_url+'/'+unread.user.id+'/'+unread.id"><h5>{{ unread.user.first_name}}</h5></a>

                            <p v-if="unread.thread" v-html="unread.thread.message"></p>
                            <p v-else v-html="unread.data.thread.message"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nofity_footer">
                <div class="submit_button text-center pt_20">
                  <a :href="mark_all_as_read_url" class="primary-btn radius_30px text_white  fix-gr-bg">{{__('mark_all_as_read')}}</a>
                </div>
            </div>
        </div>
    </li>
</template>

<script>

export default {
    props:{
        unreads: {
            type: Array,
            required: true
        },
        redirect_url: {
            type: String,
            required: true
        },
        user_id: {
            type: Number,
            required: true
        },
        asset_type:{
            default : ''
        },
        mark_all_as_read_url: {
          type: String,
          required: true
        },
    },
    data() {
        return {
            count_unread : '',
            open_modal: false,
            demotext:''
        }
    },
    mounted() {
        this.count_unread = this.unreads.length
    },
    created() {
        Echo.private('App.Models.User.' + this.user_id)
            .notification((notification) => {
                this.unreads.push(notification)
                this.count_unread += 1;
                let sound = document.getElementById('sound')
                sound.pause();
                sound.currentTime = 0;
                sound.volume = 0.3;
                sound.play();
            });
    },

    methods: {

    }
}
</script>
