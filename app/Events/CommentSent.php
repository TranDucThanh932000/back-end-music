<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Comment;

class CommentSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $comment;
    public $post_id;
    public function __construct(User $user, Comment $comment, $post_id)
    {
        $this->user = $user;
        $this->comment = $comment;
        $this->post_id = $post_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('comment-' . $this->post_id);
    }
}
