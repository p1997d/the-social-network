<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Audio;
use App\Models\Group;
use App\Models\User;
use App\Services\AudioService;
use App\Services\FileService;
use App\Services\GeneralService;
use App\Services\PublicationService;

class AudiosController extends Controller
{
    /**
     * Отображает страницу аудиозаписей пользователя
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $groupID = $request->query('group');

        if ($groupID) {
            $model = Group::find($groupID);
        } else {
            $model = $request->query('id') ? User::find($request->query('id')) : User::find(Auth::id());
        }
        if (!$model) {
            return view('main.info', ['title' => 'Информация', 'info' => 'Страница удалена либо ещё не создана.']);
        }

        return view('publications.audios.index', PublicationService::getPage('audio', $model, $request));
    }

    /**
     * Загружает новую аудиозапись
     *
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $request->validate([
            'audios' => 'required|mimes:mp3|max:204800',
        ]);

        $data = (object) collect(['title' => $request->title, 'artist' => $request->artist])->all();
        $audio = FileService::create($request->audios, $data);

        if ($request->group) {
            $model = Group::find($request->group);
        } else {
            $model = User::find(Auth::id());
        }

        $playlist = AudioService::saveToPlaylist($audio, $model);

        if (!$audio) {
            return [
                'notification' => [
                    'color' => 'danger',
                    'message' => 'Загрузка аудиозаписи завершилась с ошибкой'
                ]
            ];
        }

        return [
            'audio' => $audio,
            'playlist' => $playlist,
            'audioDownloadUrl' => route('audios.download', $audio->id),
            'notification' => [
                'color' => 'success',
                'message' => 'Аудиозапись успешно загружена'
            ]
        ];
    }

    /**
     * Удаляет аудиозапись
     *
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        return AudioService::delete($request->audio);
    }

    /**
     * Скачивает аудиозапись
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function download($id)
    {
        $file = Audio::find($id);

        return FileService::download($file);
    }

    /**
     * Добавляет аудиозапись в плейлист пользователя
     *
     * @param Request $request
     * @return array
     */
    public function add(Request $request)
    {
        return AudioService::add($request->audio);
    }

    /**
     * Получает файл и данные о аудиозаписи
     *
     * @param Request $request
     * @return array
     */
    public function getAudio(Request $request)
    {
        return AudioService::getAudio($request->id, $request->playlist);
    }

    /**
     * Получает аудиозаписи из плейлиста
     *
     * @param Request $request
     * @return array
     */
    public function getPlaylist(Request $request)
    {
        return AudioService::getPlaylist($request->playlist);
    }

    /**
     * Получает файл и данные о последней воспроизведенной аудиозаписи
     *
     * @return array
     */
    public function getLastAudio()
    {
        return AudioService::getLastAudio();
    }

    /**
     * Очищает плейлист
     *
     * @return void
     */
    public function clearPlaylist()
    {
        $user = User::find(Auth::id());
        $user->currentPlaylist->delete();
    }
}
