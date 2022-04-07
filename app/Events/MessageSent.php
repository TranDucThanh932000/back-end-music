<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $room_id;
    public function __construct(User $user, Message $message, $room_id)
    {
        $this->user = $user;
        $this->message = $message;
        $this->room_id = $room_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat-' . $this->room_id);
    }

    
}
