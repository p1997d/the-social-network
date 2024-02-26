<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\GeneralService;
use App\Services\MessagesService;

class Dialog extends Model
{
    use HasFactory;

    protected $table = 'dialogs';
    protected $quarde = false;
    protected $guarded = [];

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function date()
    {
        return GeneralService::getDate($this->sent_at);
    }

    public function attachments($type = null)
    {
        return MessagesService::getAttachments($this->attachments, $type);
    }
}
