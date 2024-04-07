<?php

namespace App\Services;

use App\Models\User;
use App\Models\Audio;
use App\Models\Playlist;
use App\Models\PlaylistAudio;
use App\Models\CurrentPlaylist;

use App\Services\FileService;

use Illuminate\Support\Facades\Auth;

class AudioService
{
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
        $audioFile = $audio->audioFile;

        $data = compact('audio', 'audioFile');

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
     * Загружает новую аудиозапись
     *
     * @param \App\Models\User $user
     * @param \App\Models\File $file
     * @param string $title
     * @param string $artist
     * @return array
     */
    public static function create($user, $file, $title, $artist)
    {
        $playlist = self::getOrCreatePlaylist($user->playlist);

        $duration = FileService::getDuration($file);

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

    /**
     * Удаляет аудиозапись
     *
     * @param int $id
     * @return array
     */
    public static function delete($id)
    {
        $user = User::find(Auth::id());
        $audio = Audio::find($id);

        PlaylistAudio::where([['playlist', $user->playlist->id], ['audio', $audio->id]])->delete();

        return ['color' => 'success', 'message' => 'Аудиозапись успешно удалена'];
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
        $audioFile = $audio->audioFile;
        $foundPlaylist = $user->currentPlaylist->getPlaylist;
        $playlist = $foundPlaylist->audios->pluck("id");

        return compact('audio', 'audioFile', 'playlist');
    }

    /**
     * Получает аудиозаписи из плейлиста
     *
     * @param \App\Models\Playlist $playlist
     * @return array|null
     */
    public static function getPlaylist($playlist)
    {
        $user = User::find(Auth::id());

        switch ($playlist) {
            case 'myPlaylist':
                if (!$user->playlist) {
                    return null;
                }

                return $user->playlist->audios;

            case 'currentPlaylist':
                if (!$user->currentPlaylist || !$user->currentPlaylist->getPlaylist) {
                    return null;
                }

                return $user->currentPlaylist->getPlaylist->audios;
        }
    }
}
