<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Info extends Model
{
    use HasFactory;

    protected $table = 'user_info';
    protected $quarde = false;
    protected $guarded = [];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
