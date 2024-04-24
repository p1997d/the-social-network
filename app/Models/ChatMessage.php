<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';
    protected $quarde = false;
    protected $guarded = [];

    public function message(): MorphTo
    {
        return $this->morphTo();
    }
}
