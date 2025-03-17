<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Message;

class GroupMessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $groupId;

    public function __construct(Message $message, $groupId)
    {
        $this->message = $message;
        $this->groupId = $groupId;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('group-chat.' . $this->groupId);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}