<?php

namespace App\Notifications;

use App\SmFeesAssign;
use App\SmNotification;
use Illuminate\Bus\Queueable;
use SpondonIt\FCM\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FeesAssignNotification extends Notification
{
    use Queueable;

    private $sm_notification;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SmNotification $sm_notification)
    {
        $this->sm_notification = $sm_notification;
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
            'title' => app('translator')->get('fees.fees_notification'),
            'body' => $this->sm_notification->message,
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
