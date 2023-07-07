<?php

namespace Modules\Chat\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Chat\Entities\Group;
use SpondonIt\FCM\FcmMessage;

class GroupMessageNotification extends Notification
{
    use Queueable;

    public $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast', 'fcm'];
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $notification = [
            'title' => __('chat::chat.new_group_message'),
            'body' => __('chat::chat.you_have_a_new_group_message'),
            'phone_number' => $notifiable->phone_number,
            'deviceID' => $notifiable->device_token,
            'group' => $this->group,
            'module' => 'chat'
        ];
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => 1,
            'status' => 'done',
            'message' => $notification,
        ];
        $message->content($notification)
                ->data($data)
                ->to($notifiable->device_token)
                ->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        return $message;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'group' => $this->group,
            'user' => auth()->user(),
            'url' => route('chat.group.show', $this->group->id)
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'group' => $this->group,
            'user' => auth()->user(),
            'url' => route('chat.group.show', $this->group->id),
            'user_name' => auth()->user()->first_name. ' '. auth()->user()->last_name,
        ]);
    }
}
