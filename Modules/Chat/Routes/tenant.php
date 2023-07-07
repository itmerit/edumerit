<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Chat\Http\Controllers\ChatController;
use Modules\Chat\Http\Controllers\Edu\SettingsController;
use Modules\Chat\Http\Controllers\GroupChatController;
use Modules\Chat\Http\Controllers\InvitationController;
use Modules\Chat\Http\Controllers\UserController;

Route::prefix('chat')->middleware('auth')->group(function() {

    Route::get('settings', [UserController::class, 'settings'])->name('chat.settings')->middleware('userRolePermission:chat.settings');
    Route::post('settings', [UserController::class, 'settingsUpdate'])->name('chat.settings')->middleware('userRolePermission:chat.settings');

    Route::post('invitation/requirement', [UserController::class, 'invitationRequirementSetting'])->name('chat.invitation.requirement');


    Route::get('user/profile', [UserController::class, 'profile'])->name('chat.user.profile');
    Route::post('user/profile', [UserController::class, 'profileUpdate'])->name('chat.user.profile');
    Route::post('user/password', [UserController::class, 'passwordUpdate'])->name('chat.user.password');

    Route::post('user/password/reset', [UserController::class, 'passwordResetLink'])->name('chat.user.passwordResetLink');
    Route::get('user/status/{type}', [UserController::class, 'changeStatus'])->name('chat.user.changeStatus');
    Route::get('user/action/{type?}/{user?}', [UserController::class, 'blockAction'])->name('chat.user.block');
    Route::get('users/blocked', [UserController::class, 'blockedUsers'])->name('chat.blocked.users');

    Route::get('open/{id?}/{notification_id?}', [ChatController::class, 'index'])->name('chat.index');
    Route::get('new', [ChatController::class, 'new'])->name('chat.new');


    Route::get('user/show/{type}', [ChatController::class, 'show'])->name('chat.user.show');
    Route::post('send/user', [ChatController::class, 'store'])->name('chat.send');
    Route::post('delete', [ChatController::class, 'destroy'])->name('chat.delete');
    Route::post('send/forward', [ChatController::class, 'forward'])->name('chat.send.forward');
    Route::post('send/group/forward', [GroupChatController::class, 'forward'])->name('chat.send.forward.group');
    Route::get('file/download/{id}', [ChatController::class, 'download'])->name('chat.file.download');


    Route::get('user/search', [UserController::class, 'search'])->name('chat.user.search');

    Route::get('invitation/index/{notification_id?}', [InvitationController::class, 'index'])->name('chat.invitation');
    Route::get('invitation/action/{type}/{id}', [InvitationController::class, 'action'])->name('chat.invitation.action');
    Route::post('invitation/user', [InvitationController::class, 'create'])->name('chat.invitation.create');
    Route::post('invitation/user/open', [InvitationController::class, 'open'])->name('chat.invitation.open');

    Route::get('group/create', [GroupChatController::class, 'create'])->name('chat.group.create');
    Route::post('group/create', [GroupChatController::class, 'store'])->name('chat.group.create');
    Route::get('group/open/{group?}', [GroupChatController::class, 'show'])->name('chat.group.show');
    Route::post('group/open/send', [GroupChatController::class, 'send'])->name('chat.group.send');
    Route::post('group/add/people', [GroupChatController::class, 'addPeople'])->name('chat.group.addPeople');
    Route::post('group/remove/people', [GroupChatController::class, 'removePeople'])->name('chat.group.removePeople');
    Route::post('group/message/delete', [GroupChatController::class, 'removeMessage'])->name('chat.group.message.destroy');
    Route::post('group/leave', [GroupChatController::class, 'leaveGroup'])->name('chat.group.leave');
    Route::post('group/delete', [GroupChatController::class, 'destroy'])->name('chat.group.delete');
    Route::post('group/assign', [GroupChatController::class, 'assignRole'])->name('chat.group.role');
    Route::post('group/read-only', [GroupChatController::class, 'readOnly'])->name('chat.group.read.only');

    Route::get('files/{type}/{id}', [ChatController::class, 'files'])->name('chat.files');
    Route::get('group/file/download/{id}/{group}', [GroupChatController::class, 'download'])->name('chat.file.download.group');

    Route::post('message/check', [ChatController::class, 'newMessageCheck'])->name('chat.message.check');
    Route::post('message/load/more', [ChatController::class, 'loadMore'])->name('chat.load.more');
    Route::post('group/message/load/more', [GroupChatController::class, 'loadMore'])->name('chat.load.more.group');
    Route::post('group/message/check', [GroupChatController::class, 'newMessageCheck'])->name('chat.group.message.check');
    Route::post('check/notification', [ChatController::class, 'newNotificationCheck'])->name('chat.notification.check');
    Route::get('notification/all-read', [ChatController::class, 'allRead'])->name('chat.notification.allRead');

    //for edu
    Route::post('file/limit', [SettingsController::class, 'chatSettings'])->name('chat.settings.edu');
    Route::get('settings/permission', [SettingsController::class, 'chatPermission'])->name('chat.settings.permission');
    Route::post('settings/permission', [SettingsController::class, 'chatPermissionStore'])->name('chat.settings.permission.store');

    Route::get('invitation/generate/{type}', [SettingsController::class, 'generate'])->name('chat.invitation.generate');

});
