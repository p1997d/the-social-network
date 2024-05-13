<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class FriendsWebSocket implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auth_user, $user, $title, $subtitle, $description, $image, $link, $notification;

    /**
     * Create a new event instance.
     */
    public function __construct($auth_user, $user, $notification = false, $title = "", $description = "")
    {
        $this->notification = $notification;
        $this->auth_user = $auth_user;
        $this->user = $user;
        $this->title = $title;
        $this->subtitle = "";
        $this->description = $auth_user ? "$auth_user->firstname $auth_user->surname $description" : null;
        $this->image = $auth_user ? $auth_user->avatar() : null;
        $this->link = route('profile', $auth_user->id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('Friends.' . $this->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'friend';
    }
}
