<?php

namespace Modules\Chat\Http\Controllers;

use App\Models\User;
use App\Traits\ImageStore;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Entities\Conversation;
use App\Events\ChatEvent;
use Modules\Chat\Entities\Group;
use Modules\Chat\Entities\Notification;
use Modules\Chat\Notifications\MessageNotification;
use Modules\Chat\Services\ConversationService;
use Modules\Chat\Services\GroupService;
use Modules\Chat\Services\InvitationService;
use Illuminate\Contracts\Support\Renderable;
use PhpParser\Node\Stmt\DeclareDeclare;

class ChatController extends Controller
{
    use ImageStore;

    public $invitationService, $groupService, $conversationService;

    public function __construct(InvitationService $invitationService, GroupService $groupService, ConversationService $conversationService)
    {
        $this->invitationService = $invitationService;
        $this->groupService = $groupService;
        $this->conversationService = $conversationService;
    }

    public function index($id=null, $notification_id=null)
    {
        try {
            $users = $this->invitationService->getAllConnectedUsers();
            if ($id){
                $activeUser = $users->where('id',$id)->first();

                $notification = auth()->user()->notifications()->find($notification_id) ?? null;
                if ($notification_id && $notification){
                    $notification->markAsRead();
                }
            }else{
                $activeUser = $users->last();
            }

            if ($users->isEmpty()){
                $activeUser = null;
                $messages = [];
            }else{
                $this->conversationService->readAllNotification($activeUser);
                $messages = auth()->user()->userSpecificConversation($activeUser->id);
                if (in_array($activeUser->id,$this->invitationService->getBlockUsers())){
                    $activeUser->blocked = true;
                }else{
                    $activeUser->blocked = false;
                }
            }

            $groups = $this->groupService->getAllGroup();
            return view('chat::index', compact('users','activeUser','messages','groups'));
        }catch (\Exception $exception){
            Toastr::error('Something happened Wrong!', 'Error!!');
            return redirect()->back();
        }
    }

    public function create()
    {
        return view('chat::create');
    }

    public function store(Request $request)
    {
        $limit = ((int) app('general_settings')->get('chat_file_limit')*1024) ?? 204800;
        $validation = \Validator::make($request->all(), [
//            'message' => 'required',
            'from_id' => 'required',
            'to_id' => 'required',
            'file_attach' => 'max:'.$limit
        ]);

        if ($validation->fails()){
            Toastr::error($validation->messages());
            return redirect()->back();
        }

        if ($request->message == null && $request->file_attach == 'null'){
            return response()->json([
                'empty' => true
            ]);
        }

        list($img_name, $original_name, $type) = $this->fileHandle($request);

        $this->replyValidation($request);

        $message = Conversation::create([
            'from_id' => auth()->id(),
            'to_id' => $request->to_id,
            'message' => $request->message,
            'file_name' => $img_name,
            'original_file_name' => $original_name,
            'message_type' => $type,
            'reply' => $request->reply,
        ])->load('reply','forwardFrom');

        User::find($request->to_id)->notify(new MessageNotification($message));
        broadcast(new ChatEvent($message))->toOthers();

        return ['status' => 'success','message' => $message];
    }

    public function show($id)
    {
        $users = $this->invitationService->getAllConnectedUsers();
        $activeUser = User::with('ownConversations','oppositeConversations','activeStatus')->find($id);
        $messages = auth()->user()->userSpecificConversation($activeUser->id);

        return view('chat::show', compact('users','activeUser','messages'));
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
        $validation = \Validator::make($request->all(), [
            'user_id' => 'required',
            'conversation_id' => 'required',
        ]);

        if ($validation->fails()){
            return response()->json([
                'success' => false,
                'message' => $validation->messages()
            ]);
        }

        try {
            if ($this->conversationService->oneToOneDelete($request->all())){
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

    public function download($id)
    {
        $conversation = Conversation::find($id);
        if (!in_array(auth()->id(),[$conversation->to_id, $conversation->from_id])){
            Toastr::error('Something happened Wrong!', 'Error!!');
            return redirect()->back();
        }

        return response()->download(base_path($conversation->file_name));
    }

    public function files($type, $id)
    {
        $groups = $this->groupService->getAllGroup();
        $users = $this->invitationService->getAllConnectedUsers();
        $group = null;

        if ($type == 'single'){
            $user = User::find($id);
            $name = $user->first_name. " ". $user->last_name;
            $messages = auth()->user()->userSpecificConversationCollection($id)->where('message_type', '<>',0);
        }else{
            $group = Group::where('id', $id)->first();
            $messages = collect();
            $group->load('threads.conversation');
            foreach ($group->threads as $message){
                if ($message->conversation->message_type != 0){
                    $messages->push($message->conversation);
                }
            }
            $name = $group->name;
        }


        return view('chat::files', compact('groups', 'users', 'messages', 'name','type', 'group'));
    }

    public function newMessageCheck (Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'user_id' => 'required',
            'last_conversation_id' => 'required',
        ]);
        $from_user = User::findOrFail($request->user_id);

        if ($validation->fails() || !$from_user->activeConnectionWithLoggedInUser()){
            return response()->json([
                'invalid' => true,
            ]);
        }

        $messages = auth()->user()->userSpecificConversationCollection($from_user->id)
            ->where('id', '>', $request->last_conversation_id);

        return response()->json([
            'invalid' => false,
            'messages' => $messages
        ]);
    }

    public function newNotificationCheck(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'notification_ids' => 'required',
        ]);
        if ($validation->fails()){
            return response()->json([
                'invalid' => true,
            ]);
        }
        (array) $array = json_decode($request->notification_ids);


        $notifications = DB::table('notifications')->where('notifiable_id', auth()->id())
        ->whereNotIn('id', $array)
        ->where('read_at', null)
        ->get();

        foreach ($notifications as $notification){
            $notification->data = json_decode($notification->data);
        }

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function allRead(){
        $notifications = DB::table('notifications')->where('notifiable_id', auth()->id())
            ->where('read_at', null)
            ->get();

        foreach ($notifications as $notification){
            Notification::find($notification->id)->update([
                'read_at' => now()
            ]);
        }

        Toastr::success('Notifications marked as read!','Success');
        return redirect()->back();
    }

    public function forward(Request $request)
    {

        $validation = \Validator::make($request->all(), [
//            'message' => 'required',
            'from_id' => 'required',
            'to_id' => 'required',
        ]);

        if ($validation->fails()){
            Toastr::error($validation->messages());
            return response()->json([
                'code'      =>  404,
                'message'   =>  'Error'
            ], 404);
        }

        $message = Conversation::create([
            'from_id' => $request->from_id,
            'to_id' => $request->to_id,
            'message' => $request->message ?? 'This is a forwarded message.',
            'file_name' => $request->file_name,
            'original_file_name' => $request->original_file_name,
            'message_type' => 0,
            'forward' => $request->forward,
            'reply' => 0,
        ])->load('reply','forwardFrom');

        User::find($request->to_id)->notify(new MessageNotification($message));
        broadcast(new ChatEvent($message))->toOthers();

        return ['status' => 'success','message' => $message];
    }

    public function fileHandle(Request $request): array
    {
        $img_name = null;
        $original_name = null;
        $type = 0;

        if ($request->hasFile('file_attach')) {
            $extension = $request->file('file_attach')->extension();
            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                $img_name = ImageStore::saveImage($request->file('file_attach'));
            } else {
                $img_name = ImageStore::saveFile($request->file('file_attach'));
            }
            $original_name = $request->file('file_attach')->getClientOriginalName();

            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                $type = 1;
            } elseif ($extension == 'pdf') {
                $type = 2;
            } elseif ($extension == 'doc' || $extension == 'docx') {
                $type = 3;
            } elseif ($extension == 'webm' || $extension == 'oga' || $extension == 'ogg') {
                $type = 4;
            } elseif (in_array($extension, ['mp4', '3gp', 'mkv'])) {
                $type = 5;
            } else {
                $type = 0;
            }
        }
        return array($img_name, $original_name, $type);
    }

    public function replyValidation(Request $request): void
    {
        if ($request->reply && ($request->reply == 'null' || $request->reply == null)) {
            $request->reply = null;
        } else {
            $request->reply = (int)$request->reply;
        }
    }

    public function new()
    {
        $users = $this->invitationService->getAllConnectedUsers();
        $groups = $this->groupService->getAllGroup();
        return view('chat::new-chat', compact('users','groups'));
    }

    public function loadMore(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'ids' => 'required',
            'user_id' => 'required',
        ]);

        if ($validation->fails()){
            return response()->json([
                'success' => false
            ]);
        }

        $messages = auth()->user()->userSpecificConversationForLoadMore($request->user_id, $request->ids);

        if ($messages->isEmpty()){
            $messages = null;
        }
        return response()->json([
            'success' => true,
            'conversations' => $messages
        ]);

    }
}
