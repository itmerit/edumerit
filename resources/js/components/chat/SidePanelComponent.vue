<template>
    <div>
        <div class="chat_flow_list crm_full_height">
            <div class="chat_flow_list_inner">
                <div class="serach_field_chat mb_30">
                    <div class="search_inner">
                        <form :action="search_url" method="GET">
                            <div class="search_field">
                                <input type="text" name="keywords" :placeholder="__('text_search')" id="users_list_sidebar">
                            </div>
                            <button type="submit"> <i class="ti-search"></i> </button>
                        </form>
                    </div>
                </div>
                <ul>
                    <div v-if="1===1">
                        <li v-for="(user, index) in users">
                            <div class="single_list d-flex align-items-center">
                                <div class="thumb">
                                    <a @click="openUserProfileModal('profileEditForm'+index)" href="#">
                                        <img v-if="user.avatar" :src="baseUrl+ user.avatar" alt="">
                                        <img v-else-if="user.avatar_url" :src="baseUrl+ user.avatar_url" alt="" height="50" width="50">
                                        <img v-else :src="baseUrl + asset_type+'/chat/images/spondon-icon.png'" alt="">
                                    </a>
                                </div>
                                <div class="list_name">
                                    <a :href="single_chat_url+'/'+user.id">
                                        <h4>{{ user.first_name }} {{ user.last_name }}
                                            <span v-if="user.active_status.status == 1" class="active_chat" ></span>
                                            <span v-else-if="user.active_status.status == 0" class="inactive_chat" ></span>
                                            <span v-else-if="user.active_status.status == 3" class="busy_chat" ></span>
                                            <span v-else class="away_chat" ></span>
                                        </h4>
                                    </a>
                                    <p v-if="user.last_message" :id="'last_message'+index">
                                        {{ user.last_message.replace(/(<([^>]+)>)/ig, '').substring(0,20)+"..."}}
                                    </p>
                                </div>
                            </div>
                            <div class="modal fade admin-query" :id="'profileEditForm'+index" aria-modal="true">
                                <div class="modal-dialog modal_800px modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">
                                                <div class="thumb" style="display: inline">
                                                    <a href="#">
                                                        <img v-if="user.avatar" :src="baseUrl+ user.avatar" alt="" height="50" width="50">
                                                        <img v-else-if="user.avatar_url" :src="baseUrl+ user.avatar_url" alt="" height="50" width="50">
                                                        <img v-else :src="baseUrl + asset_type+'/chat/images/spondon-icon.png'" alt="" height="50" width="50">
                                                    </a>
                                                </div>
                                                {{ user.first_name }} {{ user.last_name }}
                                            </h4>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <i class="ti-close "></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="primary_input mb-25" v-if="user.role_id != 4 || (settings.teacher_phone_view && settings.teacher_email_view)">
                                                        <label class="primary_input_label" for="">{{__('username')}} <span class="text-danger"> *</span></label>
                                                        <input name="name" disabled class="primary_input_field name" placeholder="Name" :value="user.username" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6" v-if="user.role_id != 4 || settings.teacher_email_view">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="">{{__('email')}} <span class="text-danger"> *</span></label>
                                                        <input name="email" class="primary_input_field name" disabled placeholder="Email" :value="user.email" type="email" readonly="">
                                                        <span class="text-danger"></span>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="primary_input mb-25" v-if="user.role_id != 4 || settings.teacher_phone_view">
                                                        <label class="primary_input_label" for="">{{__('phone')}}</label>
                                                        <input name="username" class="primary_input_field name" disabled :value="user.phone" type="text" readonly="">
                                                    </div>
                                                    <a v-if="user.blocked && user.blocked_by_me" :href="chat_block_url+'/'+'unblock/'+ user.id" class="primary-btn small fix-gr-bg"><span class="ripple rippleEffect" style="width: 30px; height: 30px; top: -6.99219px; left: 19.2578px;"></span>
                                                        {{__('unblock')}} {{__('this')}} {{__('user')}}
                                                    </a>
                                                    <a v-else-if="user.blocked" href=""></a>
                                                    <a v-else :href="chat_block_url+'/'+'block/'+ user.id" class="primary-btn small fix-gr-bg"><span class="ripple rippleEffect" style="width: 30px; height: 30px; top: -6.99219px; left: 19.2578px;"></span>
                                                        {{__('block')}} {{__('this')}} {{__('user')}}
                                                    </a>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="primary_input mb-25">
                                                        <label class="primary_input_label" for="">{{__('description')}}</label>
                                                        <p>
                                                            {{ user.description }}
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </div>
                    <div v-else>
                        <p>{{__('no')}} {{__('conversition')}} {{__('found')}}!</p>
                    </div>
                </ul>
            </div>
            <div class="main-title2 d-flex align-items-center justify-content-between">
                <h4 class="">{{__('group')}}</h4>
                <label class="primary_input_label green_input_label m-0" for="">
                    <a v-if="can_create_group" :href="create_group_url">{{__('create')}} {{__('group')}} <i class="fa fa-plus-circle"></i></a>
                </label>
            </div>
            <div class="chat_flow_list_inner">
                <ul>
                    <li v-for="group in groups">
                        <div class="single_list d-flex align-items-center">
                            <div class="thumb">
                                <a href="#">
                                    <img v-if="group.photo_url" :src="baseUrl+group.photo_url" alt="">
                                    <img v-else :src="baseUrl + asset_type+'/chat/images/bw-spondon-icon.png'" alt="">
                                </a>
                            </div>
                            <div class="list_name ">
                                <div class="create_group d-flex align-items-center justify-content-between">
                                    <a :href="group_chat_show+'/'+group.id"><h4 class="m-0">{{ group.name }} </h4></a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    components: {
        vSelect,
    },
    props:{
        search_url:{
            type: String,
            required: true,
        },
        single_chat_url:{
            type: String,
            required: true,
        },
        chat_block_url:{
            type: String,
            required: true,
        },
        create_group_url:{
            type: String,
            required: true,
        },
        group_chat_show:{
            type: String,
            required: true,
        },
        users:{
            required: true,
        },
        all_users:{
            required: true,
        },
        groups:{
            required: true,
        },
        can_create_group:{
            default : true,
        },
        asset_type:{
          default : ''
        },
      settings : {
          type: Object,
        required: true
      }
    },
    data() {
        return {
          baseUrl: Laravel.baseUrl,
          tags : []
        }
    },
    created() {
    },

    mounted() {
        this.tags = this.all_users.map(function(value) {
            if (value.last_name){
                return value.first_name;
            }else{
                return value.first_name;
            }
        });

        console.log(this.settings)

        $("#users_list_sidebar").autocomplete({
            source: this.tags
        });
    },

    methods: {
        sendMessage() {
            let config = { headers: { 'Content-Type': 'multipart/form-data' } }
            let formData = new FormData();
            if(this.replying_to){
                formData.append('reply', this.replying_to);
            }
            formData.append('file_attach', this.file);
            formData.append('message', this.newMessage);
            formData.append('from_id', this.from_user.id);
            formData.append('to_id', this.to_user.id);

            axios.post(this.send_message_url, formData, config).then((response) => {
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
            $("#inputbox").css('height','40px');

        },
        sendTypingEvent() {
            Echo.private('single-chat.'+this.to_user.id)
                .whisper('single-typing',{
                    name: 'kjk'
                });
        },

        openUserProfileModal(id){
            $('#'+id).modal('show');
        }

    }
}
</script>

<style scoped>

  .chat_flow_list_inner ul{
      list-style: none;
  }

</style>
