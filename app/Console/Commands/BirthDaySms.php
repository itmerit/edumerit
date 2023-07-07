<?php

namespace App\Console\Commands;

use App\SmSchool;
use App\SmStaff;
use App\SmStudent;
use Illuminate\Console\Command;

class BirthDaySms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:birthdaysms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        date_default_timezone_set(timeZone());
        $currentDate = date('-m-d');

        $allStudents = SmStudent::where('date_of_birth', 'like', '%'. $currentDate)->get();
        foreach($allStudents as $student){
            $compact['user_email'] = $student->email;
            @send_sms($student->mobile, 'student_birthday', $compact, $student->school_id);
        }

        $allStaffs = SmStaff::where('date_of_birth', 'like', '%'. $currentDate)->get();
        foreach($allStaffs as $staff){
            $compact['user_email'] = $staff->email;
            @send_sms($staff->mobile, 'staff_birthday', $compact, $staff->school_id);
        }

        return true;
    }
}
