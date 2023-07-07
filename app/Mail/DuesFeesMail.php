<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DuesFeesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student_detail;
    public $fees_info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student_detail, $fees_info)
    {
        $this->student_detail = $student_detail;
        $this->fees_info = $fees_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $student_detail = $this->student_detail;
        $fees_info = $this->fees_info;
        return $this->view('backEnd.feesCollection.dues_fees_email', compact('student_detail', 'fees_info'));
    }
}
