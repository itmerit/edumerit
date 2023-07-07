<?php

namespace App\Notifications;


use App\SmHomework;
use App\SmNotification;
use Illuminate\Bus\Queueable;
use SpondonIt\FCM\FcmMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FlutterAppNotification extends Notification
{
    use Queueable;

    private $sm_notification;
    private $title;

  
   public function __construct(SmNotification $sm_notification,$title)
    {
        $this->sm_notification = $sm_notification;
        $this->title = $title;
    }

   
    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $notification = [
            'title' => $this->title,
            'body' => $this->sm_notification->message,
        ];
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => 1,
            'status' => 'done',
            'message' => $notification,
            "image" => "https://freeschoolsoftware.in/spn4/infixedu/v7.0.1/public/uploads/settings/logo.png"
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
