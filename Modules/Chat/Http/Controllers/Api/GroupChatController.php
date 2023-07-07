<?php

namespace Modules\Chat\Http\Controllers\Api;

use App\Models\User;
use App\ApiBaseMethod;
use App\Traits\FileStore;
use App\Traits\ImageStore;
use Illuminate\Http\Request;
use App\Events\GroupChatEvent;
use Modules\Chat\Entities\Group;
use Illuminate\Routing\Controller;
use function Clue\StreamFilter\fun;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Chat\Entities\GroupUser;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Services\GroupService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Chat\Services\InvitationService;
use Modules\Chat\Services\ConversationService;
use Modules\Chat\Entities\GroupMessageRecipient;
use Modules\Chat\Notifications\GroupMessageNotification;
use Modules\Chat\Notifications\GroupCreationNotification;

class GroupChatController extends Controller
{
    use ImageStore;

    public $invitationService;
    public $groupService;
    public $conversationService;


    public function __construct(InvitationService $invitationService, GroupService $groupService, ConversationService $conversationService)
    {
        $this->invitationService = $invitationService;
        $this->groupService = $groupService;
        $this->conversationService = $conversationService;
    }

    public function index($id = null, $notification_id = null)
    {

    }

    public function create()
    {
        $myGroups = $this->groupService->getAllGroup();
        $users = $this->invitationService->getAllConnectedUsers();
        return response()->json(compact('users', 'myGroups'));
    }

    public function store(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'name' => 'required',
            'users' => 'required',
           
        ]);

        if ($validation->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validation->errors());
            }
        }

        if ($request->hasFile('group_photo')) {
            $request['photo_url'] = ImageStore::saveImage($request->group_photo);
        }

        $group = Group::create($request->except('_token'));


        GroupUser::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'added_by' => auth()->id()
        ]);

        foreach ($request->users as $user) {
            GroupUser::create([
                'group_id' => $group->id,
                'user_id' => $user,
                'added_by' => auth()->id(),
                'role' => 3
            ]);
            $user = User::find($user);
            if ($user) {
                $user->notify(new GroupCreationNotification($group));
            }
        }

        return response()->json(['success'=>'Group Created!']);

        // Toastr::success('Group Created!');
        // return redirect()->route('chat.index');
    }

    public function show(Group $group)
    {
        $group->load('users');
        if (!$group->users->contains('id', auth()->id())) {
             return response()->json(['error'=>'Something went wrong!']);
        }

        $myGroups = $this->groupService->getAllGroup();
        $users = $this->invitationService->getAllConnectedUsers();
        $remainingUsers = $users->filter(function ($value, $key) use ($users, $group) {
            return !in_array($value->id, array_merge($group->users->pluck('id')->toArray(), [auth()->id()]));
        });

        $this->conversationService->readAllNotificationGroup($group->id);

        $group->load([
            'threads' => function($query) {
                $query->latest();
                $query->take(20);
            },
            'threads.conversation.reply',
            'threads.conversation.forwardFrom',
            'threads.removeMessages.user',
            'threads.user.activeStatus', 'users'
        ]);

        foreach ($group->threads as $thread) {
            $contain = $thread->removeMessages->contains('user_id', auth()->id());
            if ($contain) {
                $thread->removedByMe = true;
            }
        }

        $only_threads = GroupMessageRecipient::with('conversation.reply', 'conversation.forwardFrom', 'removeMessages.user', 'user.activeStatus')
            ->where('group_id', $group->id)
            ->get();

            $single_threads = $only_threads->sortByDesc('created_at')->take(20)->map(function($value, $key){
                $contain = $value->removeMessages->contains('user_id', auth()->id());
                if ($contain){
                    $value->removedByMe = true;
                }
    
                return $value;
            });

        $myRole = GroupUser::where('user_id', auth()->id())->where('group_id', $group->id)->first()->role;

        return response()->json(compact('group', 'users', 'myGroups', 'remainingUsers', 'myRole', 'single_threads'));
    }

    public function edit($id)
    {
        return view('chat::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request)
    {
        Group::find($request->group_id);

        $relatedGroup = GroupUser::where('group_id', $request->group_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($relatedGroup->role != 1) {
            return response()->json(['notPermitted' => true]);
        }

        GroupUser::where('group_id', $request->group_id)->delete();
        $relatedGroup->delete();
        return response()->json(['notPermitted' => false, 'url' => route('chat.index')]);
    }

    public function send(Request $request)
    {
        try {
            $validation = \Validator::make($request->all(), [
//            'message' => 'required',
                'from_id' => 'required',
                'to_id' => 'required',
            ]);

            if ($validation->fails()) {
                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Validation Error.', $validation->errors());
                }
//            return redirect()->back();
            }

            if ($request->message == null && $request->file_attach == 'null') {
                return response()->json([
                    'empty' => true
                ]);
            }

            $img_name = null;
            $original_name = null;
            $type = 0;

            if ($request->reply && ($request->reply == 'null' || $request->reply == null)){
                $request->reply = null;
            } else {
                $request->reply = (int) $request->reply;
            }

            if ($request->hasFile('file_attach')) {
                $extension = $request->file('file_attach')->extension();
                if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                    $img_name = ImageStore::saveImage($request->file('file_attach'));
                } else {
                    $img_name = FileStore::saveFile($request->file('file_attach'));
                }
                $original_name = $request->file('file_attach')->getClientOriginalName();

                if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                    $type = 1;
                } elseif ($extension == 'pdf') {
                    $type = 2;
                } elseif ($extension == 'doc' || $extension == 'docx') {
                    $type = 3;
                } elseif ($extension == 'webm') {
                    $type = 4;
                } elseif (in_array($extension, ['mp4','3gp','mkv'])) {
                    $type = 5;
                } else {
                    $type = 0;
                }
            }

            $message = Conversation::create([
                'message' => $request->message,
                'file_name' => $img_name,
                'original_file_name' => $original_name,
                'message_type' => $type,
                'reply' => $request->reply,
            ])->load('reply');

            $group = Group::find($request->group_id);

            $thread = GroupMessageRecipient::create([
                'user_id' => $request->user_id,
                'conversation_id' => $message->id,
                'group_id' => $group->id
            ]);

            foreach ($group->users as $user){
                if($user->id != auth()->id()){
                    User::find($user->id)->notify(new GroupMessageNotification($group));
                }
            }
            broadcast(new GroupChatEvent($group, $thread, $message, auth()->user()))->toOthers();
            $group->load('threads.conversation.reply', 'threads.user');
            return ['status' => 'success','thread' => $thread->load('conversation.reply','user','group')];

        }catch (\Exception $e){
            return $e->getMessage();
        }

    }

    public function addPeople(Request $request)
    {
        $group = Group::find($request->group_id);
        GroupUser::create([
            'group_id' => $group->id,
            'user_id' => $request->user_id,
            'added_by' => auth()->id(),
        ]);
        User::find($request->user_id)->notify(new GroupCreationNotification($group));

        return true;
    }

    public function removePeople(Request $request)
    {
        GroupUser::where('user_id', $request->user_id)
            ->where('group_id', $request->group_id)
            ->first()
            ->delete();
        return true;
    }

    public function LeaveGroup(Request $request)
    {
        GroupUser::where('user_id', $request->user_id)
            ->where('group_id', $request->group_id)
            ->first()
            ->delete();
        return response()->json(['notPermitted' => false, 'url' => route('chat.index')]);
    }

    public function assignRole(Request $request)
    {
        $currentUser = GroupUser::where('user_id', auth()->id())->where('group_id', $request->group_id)->first();
        if ($currentUser->role != 1){
            return response()->json(['notPermitted' => true]);
        }
        $group = GroupUser::where('user_id', $request->user_id)
            ->where('group_id', $request->group_id)
            ->first();
        $group->update([
            'role' => $request->role_id
        ]);

        return response()->json(['notPermitted' => false]);

    }

    public function readOnly(Request $request)
    {
        $group = Group::find($request->group_id);

        $permitted = GroupUser::where('group_id', $group->id)
            ->where('user_id', auth()->id())
            ->where('role', 1)
            ->first();

        if ($permitted){
            $group->update([
                'read_only' => $request->type == 'unmark' ? 0 : 1
            ]);
            return response()->json(['notPermitted' => false, 'url' => url()->previous()]);
        }

        return response()->json(['notPermitted' => true]);

    }

    public function download($id, Group $group)
    {

        $conversation = Conversation::find($id);
        if (!$group->users->contains('id', auth()->id())){
            Toastr::error('Something happened Wrong!', 'Error!!');
            return redirect()->back();
        }

        return response()->download(public_path($conversation->file_name));
    }

    public function newMessageCheck (Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'group_id' => 'required',
            'last_thread_id' => 'required',
        ]);
        if ($validation->fails()){
            return response()->json([
                'invalid' => true,
            ]);
        }

        $group = Group::findOrFail($request->group_id);
        $group->load('threads.conversation.reply', 'threads.user', 'users');

        if(!$group->users->contains('id', auth()->id())){
            return response()->json([
                'invalid' => true,
            ]);
        }

        $messages = $group->threads->where('id', '>', $request->last_thread_id);
        return response()->json([
            'invalid' => false,
            'messages' => $messages
        ]);
    }

    public function forward(Request $request)
    {
        try {
            $message = Conversation::create([
                'message' => $request->message,
                'file_name' => $request->file_name,
                'original_file_name' => $request->original_file_name,
                'message_type' => 0,
                'forward' => $request->forward,
                'reply' => is_array($request->reply) ? $request->reply['id'] : $request->reply,
            ])->load('reply','forwardFrom');

            $group = Group::find($request->group_id);

            $thread = GroupMessageRecipient::create([
                'user_id' => $request->user_id,
                'conversation_id' => $message->id,
                'group_id' => $group->id
            ]);

            foreach ($group->users as $user){
                if($user->id != auth()->id()){
                    User::find($user->id)->notify(new GroupMessageNotification($group));
                }
            }
            broadcast(new GroupChatEvent($group, $thread, $message, auth()->user()))->toOthers();
            $group->load('threads.conversation.reply','threads.conversation.forwardFrom', 'threads.user');
            return ['status' => 'success','thread' => $thread->load('conversation.reply','conversation.forwardFrom','user','group')];

        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function removeMessage(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'thread_id' => 'required',
        ]);

        if ($validation->fails()){
            return response()->json([
                'success' => false,
                'message' => $validation->messages()
            ]);
        }

        try {
            if ($this->conversationService->groupMessageDelete($request->all())){
                return response()->json([
                    'success' => true
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function loadMore(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'ids' => 'required',
            'group_id' => 'required',
        ]);

        if ($validation->fails()){
            return response()->json([
                'success' => false
            ]);
        }

        $group = Group::find($request->group_id)->load('threads');

        $group->load(
            'threads.conversation.reply',
            'threads.conversation.forwardFrom',
            'threads.removeMessages.user',
            'threads.user', 'users'
        );

        foreach ($group->threads as $thread){
            $contain = $thread->removeMessages->contains('user_id', auth()->id());
            if ($contain){
                $thread->removedByMe = true;
            }
        }
        $ids = $request->ids;
        $newThreads = $group->threads->filter(function($value, $key) use ($ids){
            return !in_array($value->id, json_decode($ids));
        })->take(20);

        $newThreads->load(
            'conversation.reply',
            'conversation.forwardFrom',
            'removeMessages.user',
            'user','user.activeStatus'
        );


        if ($newThreads->isEmpty()){
            $newThreads = null;
        }

        return response()->json([
            'success' => true,
            'threads' => $newThreads
        ]);

    }
}
