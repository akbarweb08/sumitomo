<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverAssigned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;

    /**
     * Create a new event instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast on a private channel specific to the driver.
        // We will just use public Channel for simplicity if we don't have Laravel Echo auth,
        // since we are using pure socket.io we might just use normal Channel and filter by ID in the node server or client.
        // Let's use Channel to avoid auth setup for broadcasting for now, or just use it.
        return [
            new Channel('driver-channel.' . $this->task->driver_id),
        ];
    }
    
    public function broadcastAs()
    {
        return 'driver.assigned';
    }
}
