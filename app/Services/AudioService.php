<?php

namespace App\Services;

use App\Models\Audio;
use App\Models\File;
use App\Models\User;
use App\Models\UserAvatar;
use App\Models\UserFile;
use App\Models\Playlist;
use App\Models\PlaylistAudio;
use App\Models\CurrentPlaylist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AudioService
{
    /**
     * Загружает новую аудиозапись
     *
     * @param object $file
     * @return \App\Models\Audio
     */
    public static function create($file, $data)
    {
        $type = 'audios';
        $user = User::find(Auth::id());

        $path = FileService::uploadFile($type, $file);

        $duration = FileService::getDuration($path);

        $model = new Audio();
        $model->title = $data->title;
        $model->artist = $data->artist;
        $model->duration = $duration;
        $model->path = $path;
        $model->type = $file->getMimeType();
        $model->size = $file->getSize();
        $model->author = $user->id;

        $model->save();

        return $model;
    }

    /**
     * Получает список аудиозаписей из плейлиста
     *
     * @param \App\Models\Playlist $playlist
     * @return \Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Получает или создает плейлист
     *
     * @param \App\Models\Playlist $playlist
     * @return \App\Models\Playlist
     */
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

    /**
     * Сохраняет аудиозапись в плейлист
     *
     * @param \App\Models\Audio $audio
     * @return void
     */
    public static function saveToPlaylist($audio)
    {
        $user = User::find(Auth::id());
        $playlist = self::getOrCreatePlaylist($user->playlist);

        $playlist_audio = new PlaylistAudio();

        $playlist_audio->playlist = $playlist->id;
        $playlist_audio->audio = $audio->id;

        $playlist_audio->save();
    }

    /**
     * Добавляет аудиозапись в плейлист пользователя
     *
     * @param int $id
     * @return array
     */
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

    /**
     * Получает файл и данные о аудиозаписи
     *
     * @param int $id
     * @param \App\Models\Playlist $playlist
     * @return array
     */
    public static function getAudio($id, $playlist)
    {
        $user = User::find(Auth::id());

        $audio = Audio::find($id);
        $path = $audio->path;

        $data = compact('audio', 'path');

        if ($playlist !== null) {
            $foundPlaylist = Playlist::find($playlist);
            $playlist = $foundPlaylist->audios->pluck("id");
            $data = array_merge($data, compact('playlist'));
        } else {
            $foundPlaylist = $user->playlist;
        }

        self::setCurrentPlaylist($foundPlaylist, $audio);

        return $data;
    }

    /**
     * Сохраняет текущий плейлист
     *
     * @param \App\Models\Playlist $playlist
     * @param \App\Models\Audio $audio
     * @return void
     */
    public static function setCurrentPlaylist($playlist, $audio)
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

    /**
     * Получает аудиозаписи из плейлиста
     *
     * @param string $type
     * @return array
     */
    public static function getPlaylist($type)
    {
        $user = User::find(Auth::id());

        switch ($type) {
            case 'myPlaylist':
                if ($user->playlist) {
                    $playlist = $user->playlist;
                }
                break;

            case 'currentPlaylist':
                if ($user->currentPlaylist && $user->currentPlaylist->getPlaylist) {
                    $playlist = $user->currentPlaylist->getPlaylist;
                }
                break;
        }

        $audios = $playlist->audios;
        $owner = $playlist->playlistable;

        return compact('playlist', 'audios', 'owner');
    }

    /**
     * Получает файл и данные о последней воспроизведенной аудиозаписи
     *
     * @return array|null
     */
    public static function getLastAudio()
    {
        $user = User::find(Auth::id());

        if (!$user->currentPlaylist || !$user->currentPlaylist->getLastAudio) {
            return null;
        }

        $audio = $user->currentPlaylist->getLastAudio;
        $foundPlaylist = $user->currentPlaylist->getPlaylist;
        $playlist = $foundPlaylist->audios->pluck("id");
        $path = $audio->path;

        return compact('audio', 'playlist', 'path');
    }
}
