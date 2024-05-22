<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentPlaylist extends Model
{
    use HasFactory;
    protected $table = 'current_playlists';
    protected $quarde = false;
    protected $guarded = [];

    public function getPlaylist()
    {
        return $this->belongsTo(Playlist::class, 'playlist');
    }

    public function getLastAudio()
    {
        return $this->belongsTo(Audio::class, 'last_audio')->where('deleted_at', null);
    }
}
