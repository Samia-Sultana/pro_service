<?php

namespace App\Notifications;

use App\Models\ExpertOrder;
use Illuminate\Bus\Queueable;
use App\Events\BroadcastingEvent;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderNotification extends Notification
{
    use Queueable;

    private ExpertOrder $expertOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct(ExpertOrder $expertOrder)
    {
        $this->expertOrder = $expertOrder;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "You have a new order.",
            'model' => $this->expertOrder,
            'url' => "",
            'icon' => 'BriefcaseIcon',
            'variant' => 'light-danger',
        ];
    }


}
