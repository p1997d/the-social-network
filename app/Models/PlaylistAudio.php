<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistAudio extends Model
{
    use HasFactory;
    protected $table = 'playlist_audio';
    protected $quarde = false;
    protected $guarded = [];
}
