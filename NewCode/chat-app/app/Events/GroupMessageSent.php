<?php

namespace App\Events;

use App\Models\GroupMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class GroupMessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public function __construct(GroupMessage $message)
    {
        $this->message = $message->load('user');
    }

    public function broadcastOn()
    {
        // Broadcast on a public channel named after the group ID
        return new Channel('group.' . $this->message->group_id);
    }
    
    public function broadcastAs()
    {
        return 'GroupMessageSent';
    }

}
