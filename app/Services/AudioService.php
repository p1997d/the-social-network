<?php

namespace App\Services;

use FFMpeg\FFProbe;

use App\Models\User;
use App\Models\Audio;
use App\Models\Playlist;
use App\Models\PlaylistAudio;
use App\Models\CurrentPlaylist;

use Illuminate\Support\Facades\Auth;

class AudioService
{
    public static function getDuration($file)
    {
        $ffprobe = FFProbe::create();
        $info = $ffprobe->format(storage_path("app/public/files/$file->path"));
        $duration = $info->get('duration');
        $formatDuration[] = gmdate("H", $duration) != '00' ? gmdate("H", $duration) : null;
        $formatDuration[] = gmdate("i:s", $duration);
        $formatDuration = implode(':', array_filter($formatDuration));

        return $formatDuration;
    }

    public static function getOrCreatePlaylist($playlist)
    {
        $user = User::find(Auth::id());

        if ($playlist == null) {
            $model = new Playlist();

            $model->playlistable_id = $user->id;
            $model->playlistable_type = $user->getMorphClass();

            $model->save();

            $playlist = $model;
        }

        return $playlist;
    }

    public static function getAudio($id, $playlist)
    {
        $user = Auth::user();

        $audio = Audio::find($id);
        $audioFile = $audio->audiofile;

        $data = compact('audio', 'audioFile');

        if ($playlist != null) {
            $findedPlaylist = Playlist::find($playlist);
            $playlist = $findedPlaylist->audios->pluck("id");

            $data = array_merge($data, compact('playlist'));
        } else {
            $findedPlaylist = $user->playlist;
        }

        self::setCuttentPlaylist($findedPlaylist, $audio);

        return $data;
    }

    public static function getAudios($playlist)
    {
        if ($playlist == null) {
            return collect();
        }

        $audios = $playlist->audios->filter(function ($item) {
            return $item->deleted_at == null;
        });

        return $audios;
    }

    public static function create($user, $file, $title, $artist)
    {
        $playlist = self::getOrCreatePlaylist($user->playlist);

        $duration = self::getDuration($file);

        $audio = new Audio();

        $audio->title = $title;
        $audio->artist = $artist;
        $audio->duration = $duration;
        $audio->file = $file->id;

        $audio->save();

        $playlist_audio = new PlaylistAudio();

        $playlist_audio->playlist = $playlist->id;
        $playlist_audio->audio = $audio->id;

        $playlist_audio->save();

        return ['color' => 'success', 'message' => 'Аудиозапись успешно загружена'];
    }

    public static function delete($id)
    {
        $user = User::find(Auth::id());
        $audio = Audio::find($id);

        PlaylistAudio::where([['playlist', $user->playlist->id], ['audio', $audio->id]])->delete();

        return ['color' => 'success', 'message' => 'Аудиозапись успешно удалена'];
    }

    public static function add($id)
    {
        $user = User::find(Auth::id());

        $audio = Audio::find($id);
        $playlist = self::getOrCreatePlaylist($user->playlist);

        if (!$audio || !$user) {
            abort(404);
        }

        $existingUserAudio = PlaylistAudio::where('playlist', $playlist->id)
            ->where('audio', $audio->id)
            ->first();

        if ($existingUserAudio) {
            return ['color' => 'danger', 'message' => 'Эта аудиозапись уже добавлена'];
        }

        $playlist_audio = new PlaylistAudio();

        $playlist_audio->playlist = $playlist->id;
        $playlist_audio->audio = $audio->id;

        $playlist_audio->save();

        return ['color' => 'success', 'message' => 'Аудиозапись успешно добавлена'];
    }

    public static function setCuttentPlaylist($playlist, $audio)
    {
        $user = User::find(Auth::id());

        CurrentPlaylist::updateOrCreate(
            ['user' => $user->id],
            [
                'playlist' => $playlist->id,
                'last_audio' => $audio->id,
            ]
        );
    }

    public static function getLastAudio()
    {
        $user = Auth::user();

        if (!$user->currentPlaylist || !$user->currentPlaylist->getlastaudio) {
            return null;
        }

        $audio = $user->currentPlaylist->getlastaudio;
        $audioFile = $audio->audiofile;
        $findedPlaylist = $user->currentPlaylist->getplaylist;
        $playlist = $findedPlaylist->audios->pluck("id");

        return compact('audio', 'audioFile', 'playlist');
    }

    public static function getPlaylist($playlist)
    {
        $user = Auth::user();

        switch ($playlist) {
            case 'myPlaylist':
                if (!$user->playlist) {
                    return null;
                }

                return $user->playlist->audios;

            case 'currentPlaylist':
                if (!$user->currentPlaylist || !$user->currentPlaylist->getplaylist) {
                    return null;
                }

                return $user->currentPlaylist->getplaylist->audios;
        }
    }
}
