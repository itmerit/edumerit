<?php


namespace Modules\Chat\Http\Controllers\Api;


use App\Events\ClassTeacherGetAllStudent;
use App\Events\CreateClassGroupChat;
use App\SmAssignClassTeacher;
use App\SmAssignSubject;
use App\SmClassTeacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Pusher\Pusher;

class SettingsController extends Controller
{
    public function chatSettings(Request $request) {
        try {
            app('general_settings')->put([
                'chat_file_limit' => $request->file_upload_limit,
                'chat_can_upload_file' => $request->can_upload_file,
                'chat_can_make_group' => $request->can_make_group,
                'chat_staff_or_teacher_can_ban_student' => $request->staff_or_teacher_can_ban_student,
                'chat_teacher_can_pin_top_message' => $request->teacher_can_pin_top_message,
                'student_can_add_member'=>$request->student_can_add_member,
            ]);
          
            return response()->json(['message'=>'Settings successfully updated!']);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Oops! something went wrong!']);
        }
    }

    public function chatPermission()
    {
       $permission_settings =[
            'chat_can_upload_file' =>app('general_settings')->get('chat_can_upload_file'),
            'chat_file_limit' => app('general_settings')->get('chat_file_limit'),
            'chat_can_make_group' => app('general_settings')->get('chat_can_make_group'),
            'chat_staff_or_teacher_can_ban_student' => app('general_settings')->get('chat_staff_or_teacher_can_ban_student'),
            'chat_teacher_can_pin_top_message' => app('general_settings')->get('chat_teacher_can_pin_top_message'),
        ];
        $invitation_settings = [
            'chat_invitation_requirement' =>  app('general_settings')->get('chat_invitation_requirement'),
        ];
        $chat_settings = [
            'chat_can_teacher_chat_with_parents' =>  app('general_settings')->get('chat_can_teacher_chat_with_parents'),
            'chat_can_student_chat_with_admin_account' =>  app('general_settings')->get('chat_can_student_chat_with_admin_account'),
            'chat_admin_can_chat_without_invitation' =>  app('general_settings')->get('chat_admin_can_chat_without_invitation'),
            'chat_open' =>  app('general_settings')->get('chat_open'),
            'method' => app('general_settings')->get('chatting_method'),
            'pusher_app_key' => app('general_settings')->get('pusher_app_key'),
            'pusher_app_cluster' => app('general_settings')->get('pusher_app_cluster'),
            'pusher_app_secret' => app('general_settings')->get('pusher_app_secret'),
            'pusher_app_id' => app('general_settings')->get('pusher_app_id'),
        ];
         return  response()->json(compact('permission_settings', 'invitation_settings', 'chat_settings'));
    }

    public function chatPermissionStore(Request $request)
    {
        try {
            app('general_settings')->put([
                'chat_can_teacher_chat_with_parents' => $request->can_teacher_chat_with_parents,
                'chat_can_student_chat_with_admin_account' => $request->can_student_chat_with_admin_account,
                'chat_everyone_to_everyone' => $request->everyone_to_everyone,
                'chat_teacher_can_chat_with_parents' => $request->teacher_can_chat_with_parents,
                'chat_admin_can_chat_without_invitation' => $request->admin_can_chat_without_invitation,
                'chat_open' => $request->open_chat_system,
            ]);
           
            return response()->json(['message'=>'Settings successfully updated!']);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Oops! something went wrong!']);
        }
    }

    public function generate($type)
    {
        try {
            $subjects = SmAssignSubject::all();
            foreach ($subjects as $assignSubject) {
                event(new CreateClassGroupChat($assignSubject));
            }
//            clasteacher to all student

            $subject_teachers = SmClassTeacher::all();
            foreach ($subject_teachers as $st) {
                $assign_class_teacher_collection = SmAssignClassTeacher::find($st->assign_class_teacher_id);
                event(new ClassTeacherGetAllStudent($assign_class_teacher_collection, $st));
            }

            app('general_settings')->put('chat_generate', 'generated');

           
            return response()->json(['message'=>'Data Successfully Populated!']);
           
        } catch (\Exception $exception) {
           
            return response()->json(['message'=>'Oops! something went wrong!']);

        }
    }
    
    public function pusherAuth(Request $request){
        \Log::info('PusherAuthController', $request->toArray());
        
        $pusher = new Pusher(
            app('general_settings')->get('pusher_app_key'),
            app('general_settings')->get('pusher_app_secret'),
            app('general_settings')->get('pusher_app_id')
        );

        $channel     = request('channel_name');
        $socket_id   = request('socket_id');

        return $pusher->socket_auth($channel, $socket_id);
    }
}