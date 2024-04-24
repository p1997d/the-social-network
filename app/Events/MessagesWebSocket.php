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
            $this->subtitle = $data['sender']['firstname'] . ' ' . $data['sender']['surname'];
            $this->image = $data['senderAvatar'];
            $this->description = $data['decryptContent'] ? $data['decryptContent'] : 'Файлов: ' . count($data['attachments']);
            $this->link = route('messages', ['to' => $data['sender']['id']]);
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
        return [
            new PrivateChannel('Messages.' . $this->data['recipient']['id']),
        ];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
