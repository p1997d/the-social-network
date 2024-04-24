<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MessageFile extends Model
{
    use HasFactory;

    protected $table = 'message_files';
    protected $quarde = false;
    protected $guarded = [];

    public function file(): MorphTo
    {
        return $this->morphTo();
    }
}
