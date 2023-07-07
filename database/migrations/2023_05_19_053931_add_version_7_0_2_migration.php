<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class AddVersion702Migration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $routeLists = ['general_settings', 'fees_settings', 'exam_settings', 'frontend_cms', 'students_report', 'exam_report', 'staff_report', 'fees_report', 'accounts_report'];

        Permission::whereIn('route', $routeLists)->where('user_id', 1)
            ->update(['permission_section'=>0, 'user_id'=>null]);

        Permission::whereIn('route', $routeLists)->whereNotNull('user_id')->delete();
        Permission::whereIn('route', ['general_settings', 'frontend_cms'])
            ->where('parent_route', 'settings_section')->delete();

        Permission::where('route', 'system_settings')
            ->update(['route'=>'general_settings', 'name' => 'General Settings',
                'lang_name' => 'common.general_settings',
                'icon' => 'fas fa-cogs']);
        Permission::where('parent_route', 'system_settings')->update([
            'parent_route'=>'general_settings'
        ]);
        Permission::where('route', 'front_settings')
            ->update(['route'=>'frontend_cms', 'name' => 'Frontend CMS',
                'lang_name' => 'common.frontend_cms',
                'icon' => 'flaticon-software']);

        Permission::where('parent_route', 'front_settings')
            ->update([
                'parent_route'=>'frontend_cms'
            ]);


        Permission::whereIn('route', ['fees_settings','exam_settings'])->update(['parent_route'=>null]);

        $feesSettingsSections = [
            'fees.fees-invoice-settings', 'invoice-settings',
        ];

        $examSettingsSections = [
            'custom-result-setting', 'exam-settings', 'exam-report-position', 'all-exam-report-position', 'examplan.admitcard.setting', 'examplan.seatplan.setting',
        ];
        Permission::whereIn('route', $feesSettingsSections)->update(['parent_route'=>'fees_settings']);
        Permission::whereIn('route', $examSettingsSections)->update(['parent_route'=>'exam_settings']);
        // report section
        $reportSections = [
            'students_report', 'exam_report', 'staff_report', 'fees_report', 'accounts_report',
        ];
        Permission::whereIn('route', $reportSections)->where('parent_route', 'report_section')->update(['parent_route'=>null]);
        $staffReports = [
            'staff_attendance_report', 'payroll-report',
        ];
        Permission::whereIn('route', $staffReports)->update(['parent_route'=>'staff_report']);
        $feesReports = [
            'fees.due-fees', 'fees.fine-report', 'fees.payment-report', 'fees.balance-report', 'fees.waiver-report', 'wallet-report','fees_statement', 'balance_fees_report'
        ];
        Permission::whereIn('route', $feesReports)->update(['parent_route'=>'fees_report']);

        $accountReports = [
            'accounts-payroll-report', 'transaction',
        ];
        Permission::whereIn('route', $accountReports)->update(['parent_route'=>'accounts_report']);

        $studentReportSections = [
            'student_attendance_report', 'subject-attendance-report', 'student_transport_report', 'student_report', 'student_history', 'guardian_report',
            'student_login_report', 'class_report', 'class_routine_report', 'previous-record', 'user_log', 'evaluation-report'
        ];
        Permission::whereIn('route', $studentReportSections)->update(['parent_route'=>'students_report']);
        $examReportSections = [
            'exam_routine_report', 'merit_list_report', 'online_exam_report', 'mark_sheet_report_student', 'tabulation_sheet_report', 'progress_card_report', 'custom_progress_card_report_percent', 'previous-class-results',
        ];
        Permission::whereIn('route', $examReportSections)->update(['parent_route'=>'exam_report']);
        Permission::where('route', 'wallet-report')->update(['route'=>'wallet.wallet-report']);
        $classRoutines = array(
            'add-new-class-routine-store' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Add',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'add-new-class-routine-store',
                'parent_route' => 'class_routine',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 8,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 3,
                'old_id' => 246,
            ),
            'delete-class-routine' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Delete',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'delete-class-routine',
                'parent_route' => 'class_routine',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 8,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 3,
                'old_id' => 246,
            ),
            'update-my-profile' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Update',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'update-my-profile',
                'parent_route' => 'student-profile.profile',
                'is_admin' => 0,
                'is_teacher' => 0,
                'is_student' => 1,
                'is_parent' => 0,
                'position' => 0,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 3,
                'old_id' => 12,
            ),
        );
        foreach($classRoutines as $item){
            storePermissionData($item);
        }

        \Modules\MenuManage\Entities\Sidebar::truncate();
    }

    public function down()
    {
        //
    }
}
