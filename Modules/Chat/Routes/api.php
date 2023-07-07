<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\Api\UserController;
use Modules\Chat\Http\Controllers\Api\ChatController;
use Modules\Chat\Http\Controllers\Api\GroupChatController;
use Modules\Chat\Http\Controllers\Api\SettingsController;
use Modules\Chat\Http\Controllers\Api\InvitationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:web', 'subdomain'])->get('chat', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api', 'subdomain'])->group(function () {
     
    Route::get('chat/settings', [UserController::class, 'settings']);
    Route::post('chat/settings', [UserController::class, 'settingsUpdate']);
    
    Route::post('chat/invitation/requirement', [UserController::class, 'invitationRequirementSetting']);
    
    
    Route::get('chat/user/profile', [UserController::class, 'profile']);
    Route::post('chat/user/profile', [UserController::class, 'profileUpdate']);
    Route::post('chat/user/password', [UserController::class, 'passwordUpdate']);
    
    Route::post('chat/user/password/reset', [UserController::class, 'passwordResetLink']);
    Route::get('chat/user/status/{type}', [UserController::class, 'changeStatus']);
    Route::get('chat/user/action/{type?}/{user?}', [UserController::class, 'blockAction']);
    Route::get('chat/users/blocked', [UserController::class, 'blockedUsers']);
    
    Route::get('chat/open/{id?}/{notification_id?}', [ChatController::class, 'index']);
    Route::get('chat/new', [ChatController::class, 'new']);
    
    
    Route::get('chat/user/show/{type}', [ChatController::class, 'show']);
    Route::post('chat/send/user', [ChatController::class, 'store']);
    Route::post('chat/delete', [ChatController::class, 'destroy']);
    Route::post('chat/send/forward', [ChatController::class, 'forward']);
    Route::post('chat/send/group/forward', [GroupChatController::class, 'forward']);
    Route::get('chat/file/download/{id}', [ChatController::class, 'download']);
    
    
    Route::get('chat/user/search', [UserController::class, 'search']);
    
    Route::get('chat/invitation/index/{notification_id?}', [InvitationController::class, 'index']);
    Route::get('chat/invitation/action/{type}/{id}', [InvitationController::class, 'action']);
    Route::post('chat/invitation/user', [InvitationController::class, 'create']);
    Route::post('chat/invitation/user/open', [InvitationController::class, 'open']);
    
    Route::get('chat/group/create', [GroupChatController::class, 'create']);
    Route::post('chat/group/create', [GroupChatController::class, 'store']);
    Route::get('chat/group/open/{group?}', [GroupChatController::class, 'show']);
    Route::post('chat/group/open/send', [GroupChatController::class, 'send']);
    Route::post('chat/group/add/people', [GroupChatController::class, 'addPeople']);
    Route::post('chat/group/remove/people', [GroupChatController::class, 'removePeople']);
    Route::post('chat/group/message/delete', [GroupChatController::class, 'removeMessage']);
    Route::post('chat/group/leave', [GroupChatController::class, 'leaveGroup']);
    Route::post('chat/group/delete', [GroupChatController::class, 'destroy']);
    Route::post('chat/group/assign', [GroupChatController::class, 'assignRole']);
    Route::post('chat/group/read-only', [GroupChatController::class, 'readOnly']);
    
    Route::get('chat/files/{type}/{id}', [ChatController::class, 'files']);
    Route::get('chat/group/file/download/{id}/{group}', [GroupChatController::class, 'download']);
    Route::post('chat/message/check', [ChatController::class, 'newMessageCheck']);
    Route::post('chat/message/load/more', [ChatController::class, 'loadMore']);
    Route::post('chat/group/message/load/more', [GroupChatController::class, 'loadMore']);
    Route::post('chat/group/message/check', [GroupChatController::class, 'newMessageCheck']);
    Route::post('chat/check/notification', [ChatController::class, 'newNotificationCheck']);
    Route::get('chat/notification/all-read', [ChatController::class, 'allRead']);
    
        //for edu
    Route::post('chat/file/limit', [SettingsController::class, 'chatSettings']);
    Route::get('chat/settings/permission', [SettingsController::class, 'chatPermission']);
    Route::post('chat/settings/permission', [SettingsController::class, 'chatPermissionStore']);
    Route::get('chat/invitation/generate/{type}', [SettingsController::class, 'generate']);
    
    Route::post('broadcasting/auth', [SettingsController::class,'pusherAuth']);
});
