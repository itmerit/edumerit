<?php

namespace Modules\Chat\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Invitation;
use Modules\Chat\Notifications\InvitationNotification;
use Modules\Chat\Services\InvitationService;

class InvitationController extends Controller
{
    protected $invitation;

    public function __construct(InvitationService $invitation)
    {
        $this->invitation = $invitation;
    }

    public function index($notification_id =null)
    {
        try {
            if ($notification_id){
                DB::table('notifications')
                    ->where('id',$notification_id)
                    ->update([
                        'read_at' => now()
                    ]);
            }

            $ownRequest = $this->invitation->myRequest();
            $peopleRequest = $this->invitation->peopleRequest();
            $connectedPeoples = $this->invitation->getAllConnectedUsers();
            return view('chat::invitation', compact('ownRequest','peopleRequest','connectedPeoples'));
        }catch (\Exception $exception){
            Toastr::error(__('chat.something_went_wrong'));
            return redirect()->back();
        }
    }


    public function create(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'to' => 'required',
        ]);

        if ($validation->fails()){
            Toastr::error(__('chat.something_went_wrong'));
            return redirect()->back();
        }

        $invitation = $this->invitation->invitationCreate($request->to, 0);
        User::find($request->to)->notify(new InvitationNotification($invitation,'You have new connection request!'));

        Toastr::success('Request successful!');

        return redirect()->back();
    }

    public function open(Request $request)
    {
        
        $validation = \Validator::make($request->all(), [
            'to' => 'required',
        ]);

        if ($validation->fails()){
            Toastr::error($validation->messages());
            return redirect()->back();
        }

        $user = User::find($request->to);
       
        if(chatOpen() || invitationRequired()){
            $invitation = Invitation::where('to', auth()->id())->where('from', $user->id)
                ->orWhere(function ($query) use ($user){
                    $query->where('from', auth()->id());
                    $query->where('to', $user->id);
                })->first();

            if ($invitation){
                $invitation->status = 1;
                $invitation->save();
            }
        }

        if (!$user->connectedWithLoggedInUser()){
            $invitation = $this->invitation->invitationCreate($request->to, 1);
        }



        return redirect()->route('chat.index', $request->to);
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return view('chat::show');
    }


    public function edit($id)
    {
        return view('chat::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function action($type, $id)
    {
        $action = $this->invitation->invitationUpdate($type, $id);

        if ($action){
            Toastr::success('Connection request successfully updated!');
            return redirect()->back();
        }

        \LogActivity::errorLog('Unauthorized action in connection/invitation update!');
        Toastr::error('Oops! Something went wrong!', 'Error!!');
        return redirect()->back();
    }
}
