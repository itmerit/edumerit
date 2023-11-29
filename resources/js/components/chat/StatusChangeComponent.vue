<template>
    <div class="dropdown CRM_dropdown">
        <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ user.first_name }}
            <span v-if="status == 1" class="active_chat" ></span>
            <span v-else-if="status == 0" class="inactive_chat" ></span>
            <span v-else-if="status == 3" class="busy_chat" ></span>
            <span v-else class="away_chat" ></span>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
            <a :href="domain+'chat/user/status/1?url='+current_path"
               class="dropdown-item" type="button">
                <i v-if="status === 1" class="fa fa-check-circle"></i>
                {{__('active')}}
            </a>
            <a :href="domain+'chat/user/status/0?url='+current_path"
               class="dropdown-item" type="button">
                <i v-if="status === 0" class="fa fa-check-circle"></i>
                {{__('inactive')}}
            </a>
            <a :href="domain+'chat/user/status/2?url='+current_path"
               class="dropdown-item" type="button">
                <i v-if="status === 2" class="fa fa-check-circle"></i>
                {{__('away')}}
            </a>
            <a :href="domain+'chat/user/status/3?url='+current_path"
               class="dropdown-item" type="button">
                <i v-if="status === 3" class="fa fa-check-circle"></i>
                {{__('busy')}}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: "StatusChangeComponent",

    props:{
        app_url: {
            type: String,
            required: true
        },
        user: {
            required: true
        },
        status: {
            required: true
        },
    },
    mounted() {
        // this.current_path = window.location.pathname
    },

    data(){
        return {
            current_path: Laravel.current_path_without_domain,
            domain : Laravel.baseUrl
        }
    }

}
</script>

<style scoped>
span.active_chat {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #00d700;
    border-radius: 50%;
    margin-left: 7px;
    position: relative;
    top: -4px;
}

span.inactive_chat {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #757975;
    border-radius: 50%;
    margin-left: 7px;
    position: relative;
    top: -4px;
}

span.busy_chat {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #ff0000;
    border-radius: 50%;
    margin-left: 7px;
    position: relative;
    top: -4px;
}

span.away_chat {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #ff9f13;
    border-radius: 50%;
    margin-left: 7px;
    position: relative;
    top: -4px;
}
</style>
