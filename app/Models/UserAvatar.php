<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{
    use HasFactory;
    protected $table = 'user_avatars';
    protected $quarde = false;
    protected $guarded = [];

    public function files()
    {
        return $this->hasMany(Photo::class, 'id', 'avatar');
    }
}
