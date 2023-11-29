<?php


namespace Modules\Chat\Http\Controllers\Edu;


use App\Events\ClassTeacherGetAllStudent;
use App\Events\CreateClassGroupChat;
use App\SmAssignClassTeacher;
use App\SmAssignSubject;
use App\SmClassTeacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function chatSettings(Request $request){
        try {
            app('general_settings')->put([
                'chat_file_limit' => $request->file_upload_limit,
                'chat_can_upload_file' => $request->can_upload_file,
                'chat_can_make_group' => $request->can_make_group,
                'chat_teacher_staff_can_make_group' => $request->teacher_staff_can_make_group,
                'chat_staff_or_teacher_can_ban_student' => $request->staff_or_teacher_can_ban_student,
                'chat_teacher_can_pin_top_message' => $request->teacher_can_pin_top_message,
            ]);
            Toastr::success('Settings successfully updated!');
            return redirect()->back();
        }catch (\Exception $e){
            Toastr::error('Oops! Something went wrong!');
            return redirect()->back();
        }
    }

    public function chatPermission()
    {
        return view('chat::edu.permission');
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
            Toastr::success('Settings successfully updated!','Success');
            return redirect()->back();
        }catch (\Exception $e){
            Toastr::error('Oops! Something went wrong!');
            return redirect()->back();
        }
    }

    public function generate($type)
    {
        try {
            $subjects = SmAssignSubject::where('school_id', auth()->user()->school_id)->get();
            foreach ($subjects as $assignSubject){
                event(new CreateClassGroupChat($assignSubject)); //subjectwise group
            }
//            clasteacher to all student

            $subject_teachers = SmClassTeacher::where('school_id', auth()->user()->school_id)->get();
            foreach ($subject_teachers as $st){
                $assign_class_teacher_collection = SmAssignClassTeacher::find($st->assign_class_teacher_id);
                event(new ClassTeacherGetAllStudent($assign_class_teacher_collection, $st));
            }

            app('general_settings')->put('chat_generate', 'generated');

            Toastr::success('Data successfully populated!');
            return redirect()->back();
        }catch (\Exception $exception){
            Toastr::error('Oops! something went wrong!');
            return redirect()->back();
        }
    }
}