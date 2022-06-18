<?php

namespace Rockbuzz\LaraComments\Events;

use Illuminate\Queue\SerializesModels;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\{Channel, InteractsWithSockets, PresenceChannel, PrivateChannel};

class CommentCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Comment
     */
    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
