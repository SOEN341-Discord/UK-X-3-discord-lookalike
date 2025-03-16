<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class GroupMessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('group.'.$this->message->group_id);
    }

    public function broadcastWith()
    {
        return ['message' => $this->message];
    }
}