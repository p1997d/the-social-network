<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserFile extends Model
{
    use HasFactory;
    protected $table = 'user_files';
    protected $quarde = false;
    protected $guarded = [];

    public function files(): MorphTo
    {
        return $this->morphTo();
    }
}
