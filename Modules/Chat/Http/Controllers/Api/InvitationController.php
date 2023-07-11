<?php

namespace Modules\Chat\Http\Controllers\Api;

use App\Models\User;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Invitation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Chat\Services\InvitationService;
use Modules\Chat\Notifications\InvitationNotification;

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
            return response()->json(compact('ownRequest', 'peopleRequest', 'connectedPeoples'));
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

        if ($validation->fails()) {           
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validation->errors());
            }
        }

        $invitation = $this->invitation->invitationCreate($request->to, 0);
        User::find($request->to)->notify(new InvitationNotification($invitation,'You have new connection request!'));
      
      return  response()->json(['message' =>'Request successful!']);

       
    }

    public function open(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'to' => 'required',
        ]);

        if ($validation->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validation->errors());
            }
        }

        $user = User::find($request->to);
        if(chatOpen() || invitationRequired()){
            $invitation = Invitation::where('to', auth()->id())->where('from', $user->id)
                ->orWhere(function ($query) use ($user) {
                    $query->where('from', auth()->id());
                    $query->where('to', $user->id);
                })->first();

            if ($invitation) {
                $invitation->status = 1;
                $invitation->save();
            }
        }

        if (!$user->connectedWithLoggedInUser()) {
            $invitation = $this->invitation->invitationCreate($request->to, 1);
        }

        // return response()->json(['chat.index', $request->to]);
        return  response()->json(['message' =>'Request successful!']);

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

        if ($action) {
            // Toastr::success('Connection request successfully updated!');
            // return redirect()->back();
            return  response()->json(['message' =>'Connection request successfully updated!']);

        }

        \LogActivity::errorLog('Unauthorized action in connection/invitation update!');
       
        return  response()->json(['message' =>'Oops! Something went wrong!']);

    }
}
