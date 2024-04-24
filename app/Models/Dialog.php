<?php

namespace App\Models;

use App\Services\DialogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialog extends Model
{
    use HasFactory;

    protected $table = 'dialogs';
    protected $quarde = false;
    protected $guarded = [];

    public function interlocutor()
    {
        if ($this->sender == auth()->user()->id) {
            $interlocutor = $this->belongsTo(User::class, 'recipient');
        } else {
            $interlocutor = $this->belongsTo(User::class, 'sender');
        }
        return $interlocutor;
    }

    public function unreadMessagesCount()
    {
        return DialogService::getUnreadMessagesCount($this->id);
    }

    public function messages()
    {
        $relationship = $this->belongsToMany(Message::class, 'dialog_messages', 'dialog', 'message');

        if ($this->sender == auth()->user()->id) {
            $relationship = $relationship->wherePivot('delete_for_sender', 0);
        } elseif ($this->recipient == auth()->user()->id) {
            $relationship = $relationship->wherePivot('delete_for_recipient', 0);
        }

        return $relationship->get();
    }
}
