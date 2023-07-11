<div class="chat_flow_list_wrapper ">
    <div class="box_header">
        <div class="main-title">
            <h3 class="m-0">@lang('common.search_list')</h3>
        </div>
    </div>
    <!-- chat_list  -->
    <div class="chat_flow_list">
        <div class="chat_flow_list_inner">
            <div class="serach_field_chat mb_30">
                <div class="search_inner">
                    <form action="{{ route('chat.user.search') }}" method="GET">
                        <div class="search_field">
                            <input type="text" name="keywords" placeholder="@lang('chat::.chat.search_people_or_group')" value="{{ request('keywords') }}">
                        </div>
                        <button type="submit"> <i class="ti-search"></i> </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ chat_list  -->
</div>
