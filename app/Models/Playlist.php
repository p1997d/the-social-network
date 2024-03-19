<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Playlist extends Model
{
    use HasFactory;

    protected $table = 'playlists';
    protected $quarde = false;
    protected $guarded = [];

    public function playlistable(): MorphTo
    {
        return $this->morphTo();
    }

    public function audios()
    {
        return $this->belongsToMany(Audio::class, 'playlist_audio', 'playlist', 'audio');
    }
}
