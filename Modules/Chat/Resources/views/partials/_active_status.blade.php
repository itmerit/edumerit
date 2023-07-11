@if($user->activeStatus->isActive())
    <span class="active_chat" ></span>
@elseif($user->activeStatus->isInactive())
    <span class="inactive_chat" ></span>
@elseif($user->activeStatus->isBusy())
    <span class="busy_chat" ></span>
@else
    <span class="away_chat" ></span>
@endif

