<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Modules\Chat\Entities\Group;

Broadcast::channel('single-chat.{id}', function ($user, $id) {
    \Log::info(config('broadcasting'));
    return Auth::check();
});

Broadcast::channel('group-chat.{group}', function ($user, Group $group) {
    return $group->users->contains('id', $user->id);
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return true;
});