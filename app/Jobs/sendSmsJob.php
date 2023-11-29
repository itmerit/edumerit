<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommunicateNotification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class sendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sms;
    protected $title;
    protected $user;
    protected $numbers=[];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sms,$title,$numbers,$user)
    {
        $this->sms = $sms;
        $this->title = $title;
        $this->numbers = $numbers;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        try {
            foreach ($this->numbers as $key => $number) {
                $notification_data=[];
                $notification_data['title']=$this->title;
                $notification_data['body']=$this->sms;
                $notification_data['phone_number']=$number;
                $notification_data['deviceID']=$this->user->device_token;
                Notification::send($this->user, new CommunicateNotification($notification_data));
                // Log::info($notification_data);
            }
        }catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        
    }
}
