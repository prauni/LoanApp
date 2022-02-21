<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Log;

class PostCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $pid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($pid)
    {
        Log::debug('*** Event created ***'.$pid.' ****');
        $this->pid = $pid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::debug('*** Event broadcastOn ***'.$this->pid.' ****');
        return new PrivateChannel('channel-name');
    }
}
