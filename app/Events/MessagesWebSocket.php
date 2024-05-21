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

class MessagesWebSocket implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification, $title, $subtitle, $description, $image, $link, $data;

    /**
     * Create a new event instance.
     */
    public function __construct($data, $notification = false)
    {
        $this->notification = $notification;
        if ($this->notification) {
            $this->title = 'Новое сообщение';
            $this->subtitle = $data['subtitle'];
            $this->image = isset($data['chatAvatar']) ? $data['chatAvatar'] : $data['senderAvatar'];
            $this->description = $data['decryptContent'] ? $data['decryptContent'] : 'Файлов: ' . count($data['attachments']);
            $this->link = $data['link'];
        }
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];

        foreach ($this->data['recipients'] as $id){
            $channels[] = new PrivateChannel('Messages.' . $id);
        }

        return $channels;
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
