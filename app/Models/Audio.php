<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Audio extends Model
{
    use HasFactory;
    protected $table = 'audios';
    protected $quarde = false;
    protected $guarded = [];

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
