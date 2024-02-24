<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageDelete extends Model
{
    use HasFactory;

    protected $table = 'chat_message_delete';
    protected $quarde = false;
    protected $guarded = [];

}
