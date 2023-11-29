@if(userPermission('chat') && menuStatus(900))
    <li  data-position="{{menuPosition(900)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="fas fa fa-weixin"></span>
            </div>
            <div class="nav_title">
                <span>@lang('chat::chat.chat')</span>
                
            </div>
        </a>
        <ul class="list-unstyled" id="subMenuChat">
            @if(userPermission('chat.index') && menuStatus(901))
                <li  data-position="{{menuPosition(901)}}" >
                    <a href="{{ route('chat.index') }}">@lang('chat::chat.chat_box')</a>
                </li>
            @endif

            @if(userPermission('chat.invitation') && menuStatus(903))
                <li data-position="{{menuPosition(903)}}" >
                    <a href="{{ route('chat.invitation') }}">@lang('chat::chat.invitation')</a>
                </li>
            @endif

            @if(userPermission('chat.blocked.users') && menuStatus(904))
                <li data-position="{{menuPosition(904)}}" >
                    <a href="{{ route('chat.blocked.users') }}">@lang('chat::chat.blocked_user')</a>
                </li>
            @endif

            @if(userPermission('chat.settings') && menuStatus(905))
                <li data-position="{{menuPosition(905)}}" >
                    <a href="{{ route('chat.settings') }}">@lang('chat::chat.settings')</a>
                </li>
            @endif
        </ul>
    </li>
@endif