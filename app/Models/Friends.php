<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\FriendRequestStatusEnum;

class Friends extends Model
{
    use HasFactory;

    protected $table = 'friends';
    protected $quarde = false;
    protected $guarded = [];

    protected $casts = [
        'status' => FriendRequestStatusEnum::class,
    ];
}
