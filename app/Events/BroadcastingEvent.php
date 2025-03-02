<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\DatabaseNotification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class BroadcastingEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $databaseNotification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DatabaseNotification $databaseNotification)
    {
        $this->databaseNotification = $databaseNotification;
    }

    public function broadcastOn()
    {

        return new Channel('my-channel');


    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return "notification-" . $this->databaseNotification->notifiable_id;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->databaseNotification->toArray();
    }
}
