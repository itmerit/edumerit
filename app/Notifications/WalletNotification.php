<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use SpondonIt\FCM\FcmMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WalletNotification extends Notification
{
    use Queueable;
    private $addPayment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($addPayment)
    {
        $this->addPayment = $addPayment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $notification = [
            'title' => 'New Homework Added',
        ];
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => 1,
            'status' => 'done',
            'message' => $notification,
        ];
        $message->content($notification)
                ->data($data)
                ->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        return $message;
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

   


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
