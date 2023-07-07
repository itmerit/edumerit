<?php

namespace Database\Seeders;

use App\SmSchool;
use App\SmAcademicYear;
use Illuminate\Database\Seeder;
use Database\Seeders\SmSchoolSeeder;
use Database\Seeders\ContinentsTableSeeder;
use Database\Seeders\Lesson\SmTopicsTableSeeder;
use Database\Seeders\Admin\SmVisitorsTableSeeder;
use Database\Seeders\Exam\SmExamTypesTableSeeder;
use Database\Seeders\Lesson\SmLessonsTableSeeder;
use Database\Seeders\Fees\SmFeesAssignTableSeeder;
use Database\Seeders\Fees\SmFeesGroupsTableSeeder;
use Database\Seeders\Admin\SmComplaintsTableSeeder;
use Database\Seeders\Fees\SmFeesPaymentTableSeeder;
use Database\Seeders\Leave\SmLeaveTypesTableSeeder;
use Database\Seeders\Transport\SmRoutesTableSeeder;
use Database\Seeders\Academics\SmClassesTableSeeder;
use Database\Seeders\Fees\SmFeesDiscountTableSeeder;
use Database\Seeders\Academics\SmSectionsTableSeeder;
use Database\Seeders\Academics\SmSubjectsTableSeeder;
use Database\Seeders\Communicate\SmSmTodoTableSeeder;
use Database\Seeders\Exam\SmExamSchedulesTableSeeder;
use Database\Seeders\HomeWork\SmHomeworksTableSeeder;
use Database\Seeders\Inventory\SmSupplierTableSeeder;
use Database\Seeders\Lesson\SmLessonPlansTableSeeder;
use Database\Seeders\Transport\SmVehiclesTableSeeder;
use Database\Seeders\Admin\SmPostalReceiveTableSeeder;
use Database\Seeders\Dormitory\SmRoomListsTableSeeder;
use Database\Seeders\Dormitory\SmRoomTypesTableSeeder;
use Database\Seeders\HumanResources\StaffsTableSeeder;
use Database\Seeders\Inventory\SmItemStoreTableSeeder;
use Database\Seeders\Academics\SmClassRoomsTableSeeder;
use Database\Seeders\Accounts\SmIncomeHeadsTableSeeder;
use Database\Seeders\Admin\SmPostalDispatchTableSeeder;
use Database\Seeders\Exam\SmExamAttendancesTableSeeder;
use Database\Seeders\Student\SmStudentGroupTableSeeder;
use Database\Seeders\Accounts\SmBankAccountsTableSeeder;
use Database\Seeders\Accounts\SmExpenseHeadsTableSeeder;
use Database\Seeders\Admin\SmContactMessagesTableSeeder;
use Database\Seeders\Fees\SmFeesCarryForwardTableSeeder;
use Database\Seeders\OnlineExam\SmOnlineExamTableSeeder;
use Database\Seeders\Student\SmOptionSubjectTableSeeder;
use Database\Seeders\Library\SmBookCategoriesTableSeeder;
use Database\Seeders\SystemSettings\SmHolidayTableSeeder;
use Database\Seeders\Academics\SmAcademicYearsTableSeeder;
use Database\Seeders\Communicate\SmNoticeBoardTableSeeder;
use Database\Seeders\Communicate\SmSendMessageTableSeeder;
use Database\Seeders\Exam\SmExamMarksRegistersTableSeeder;
use Database\Seeders\Fees\SmFeesAssignDiscountTableSeeder;
use Database\Seeders\Academics\SmAssignSubjectsTableSeeder;
use Database\Seeders\Admin\SmStudentCertificateTableSeeder;
use Database\Seeders\Communicate\SmEmailSmsLogsTableSeeder;
use Database\Seeders\Dormitory\SmDormitoryListsTableSeeder;
use Database\Seeders\Inventory\SmItemCategoriesTableSeeder;
use Database\Seeders\Transport\SmAssignVehiclesTableSeeder;
use Database\Seeders\HomeWork\SmHomeworkStudentsTableSeeder;
use Database\Seeders\OnlineExam\SmQuestionGroupsTableSeeder;
use Database\Seeders\Student\SmStudentAttendanceTableSeeder;
use Database\Seeders\Student\SmStudentCategoriesTableSeeder;
use Database\Seeders\HumanResources\SmDesignationsTableSeeder;
use Database\Seeders\UploadContent\SmUploadContentTableSeeder;
use Database\Seeders\Academics\SmAssignClassTeacherTableSeeder;
use Database\Seeders\Academics\SmClassRoutineUpdatesTableSeeder;
use Database\Seeders\HumanResources\SmStaffAttendancesTableSeeder;
use Database\Seeders\FrontSettings\SmBackgroundSettingsTableSeeder;
use Database\Seeders\FrontSettings\SmFrontendPermissionTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

//        $this->call(SmSchoolSeeder::class);
        $schools = SmSchool::query()->get();
       
        foreach ($schools as $school) {
            $params = [];
            $params['school_id'] = $school->id;

            $this->callWith(SmVisitorsTableSeeder::class, array_merge($params, ['count' => 10]));

            $this->callWith(SmDesignationsTableSeeder::class, array_merge($params, ['count' => 5]));
            $this->callWith(SmVehiclesTableSeeder::class, array_merge($params, ['count' => 5]));

            // $this->callWith(SmAcademicYearsTableSeeder::class, array_merge($params, ['count' => 1]));

            $this->callWith(SmExpenseHeadsTableSeeder::class, array_merge($params, ['count' => 10]));
            $this->callWith(SmIncomeHeadsTableSeeder::class, array_merge($params, ['count' => 10]));
            $this->callWith(SmBankAccountsTableSeeder::class, array_merge($params, ['count' => 10]));
            // $this->callWith(SmBookCategoriesTableSeeder::class, array_merge($params, ['count' => 10]));
            $this->callWith(SmContactMessagesTableSeeder::class, array_merge($params, ['count' => 10]));
            $this->callWith(SmDormitoryListsTableSeeder::class, array_merge($params, ['count' => 7]));
            $this->callWith(SmRoomTypesTableSeeder::class, array_merge($params, ['count' => 6]));
            $this->callWith(SmRoomListsTableSeeder::class, array_merge($params, ['count' => 10]));


            $this->callWith(SmStudentCategoriesTableSeeder::class, array_merge($params, ['count' => 6]));

             $this->callWith(StaffsTableSeeder::class, array_merge($params, ['count' => 5]));
            $this->callWith(SmBackgroundSettingsTableSeeder::class, array_merge($params, ['count' => 2]));
            $this->callWith(SmFrontendPermissionTableSeeder::class, array_merge($params, ['count' => 2]));


            $academicYears = SmAcademicYear::where('school_id', $school->id)->get();


            foreach ($academicYears as $academicYear) {
                $params['academic_id'] = $academicYear->id;
                $this->callWith(SmStudentGroupTableSeeder::class, array_merge($params, ['count' => 6]));
                $this->callWith(SmSectionsTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmSubjectsTableSeeder::class, array_merge($params, ['count' => 10]));
                $this->callWith(SmClassesTableSeeder::class, array_merge($params, ['count' => 10]));

                $this->callWith(SmStudentAttendanceTableSeeder::class, array_merge($params, ['count' => 10]));

                $this->callWith(SmRoutesTableSeeder::class, array_merge($params, ['count' => 10]));
                $this->callWith(SmClassRoomsTableSeeder::class, array_merge($params, ['count' => 10]));
                $this->callWith(SmComplaintsTableSeeder::class, array_merge($params, ['count' => 10]));
                $this->callWith(SmComplaintsTableSeeder::class, array_merge($params, ['count' => 10]));
                $this->callWith(SmEmailSmsLogsTableSeeder::class, array_merge($params, ['count' => 10]));


                 $this->callWith(SmExamTypesTableSeeder::class, array_merge($params, ['count' => 3]));
                $this->callWith(SmStaffAttendancesTableSeeder::class, array_merge($params, ['count' => 1]));
                $this->callWith(SmAssignSubjectsTableSeeder::class, array_merge($params, ['count' => 1]));
                $this->callWith(SmAssignVehiclesTableSeeder::class, array_merge($params, ['count' => 1]));
                $this->callWith(SmClassRoutineUpdatesTableSeeder::class, array_merge($params, ['count' => 1]));
                $this->callWith(SmExamSchedulesTableSeeder::class, array_merge($params, ['count' => 1]));
                $this->callWith(SmExamAttendancesTableSeeder::class, array_merge($params, ['count' => 1]));
//                 $this->callWith(SmExamMarksRegistersTableSeeder::class, array_merge($params, ['count' => 1]));

                $this->callWith(SmFeesGroupsTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmFeesDiscountTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmFeesAssignDiscountTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmFeesAssignTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmFeesPaymentTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmFeesCarryForwardTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmLeaveTypesTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmLessonsTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmTopicsTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmLessonPlansTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmHomeworksTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmHomeworkStudentsTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmItemCategoriesTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmItemStoreTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmSupplierTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmQuestionGroupsTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmQuestionGroupsTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmOnlineExamTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmHolidayTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmNoticeBoardTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmPostalDispatchTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmPostalReceiveTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmSendMessageTableSeeder::class, array_merge($params, ['count' => 5]));

                $this->callWith(SmUploadContentTableSeeder::class, array_merge($params, ['count' => 5]));

                // $this->callWith(SmStudentCertificateTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmSmTodoTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmOptionSubjectTableSeeder::class, array_merge($params, ['count' => 5]));
                $this->callWith(SmAssignClassTeacherTableSeeder::class, array_merge($params, ['count' => 5]));

            }
        }
        
    }
}