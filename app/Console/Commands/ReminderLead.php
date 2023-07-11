<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Lead\Entities\LeadReminder;
use Modules\Lead\Entities\ReminderSettings;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReminderLead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command For Lead Reminder.';

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
     * @return mixed
     */
    public function handle()
    {
        if(!moduleStatusCheck('Lead')){
            return ;
        }
        $generalSettings = generalSetting();
        $reminder_time_before = ReminderSettings::first()->reminder_time;
        $today = date('Y-m-d');
    
        $end_reminder_time = Carbon::now()->addMinutes($reminder_time_before)
                                          ->setTimezone($generalSettings->timeZone->time_zone)->format('H:i:s');
        $reminders = LeadReminder::where('time', '<', $end_reminder_time)
                                  ->whereDate('date_time', $today)->where('email_to', 1)
                                  ->where('mail_status', 0)->get();

        foreach ($reminders as $reminder) {
            $full_name = $reminder->lead->first_name.' '. $reminder->lead->last_name;
            $compact['lead_assign_user'] = $reminder->staff->full_name;
            $compact['lead_name'] = $full_name;
            $compact['lead_email'] = $reminder->lead->email;
            $compact['lead_phone'] = $reminder->lead->mobile;
            $compact['lead_time'] = $reminder->time;
            $compact['lead_date_time'] = $reminder->date_time;
            $compact['lead_details'] = $reminder->description;
    
            @send_mail($reminder->staff->email, $full_name, 'lead_reminder', $compact);
    
            $reminderUpdate = LeadReminder::find($reminder->id);
            $reminderUpdate->mail_status = 1;
            $reminderUpdate->save();
        }
         
        $this->info('Lead Reminder ');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
