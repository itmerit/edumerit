<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendEmailTest;
use Mail;
use Illuminate\Contracts\Mail\Mailer;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


       protected $data = [];
       protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $details)
    {
      $this->data = $data;
      $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
            $mailer->send('backEnd.emails.mail', ['data'=> $this->data], function ($message) {
                $message->from($this->data['system_email'], $this->data['school_name']);
                $message->to($this->details)->subject($this->data['email_sms_title']);
            });
    }
}