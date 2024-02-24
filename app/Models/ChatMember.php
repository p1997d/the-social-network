<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatMember extends Model
{
    use HasFactory;

    protected $table = 'chat_members';
    protected $quarde = false;
    protected $guarded = [];
}
