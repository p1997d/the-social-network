<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Audio;
use App\Models\Playlist;

use App\Services\FileService;
use App\Services\AudioService;
use App\Services\GeneralService;

use function PHPUnit\Framework\returnSelf;

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

        $user = $request->query('id') ? User::find($request->query('id')) : User::find(Auth::id());

        $title = GeneralService::getTitle($user, "Аудиозаписи");

        $playlist = $user->playlist;
        $audios = AudioService::getAudios($playlist);

        return view('publications.audios.index', compact('title', 'user', 'audios', 'playlist'));
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

        $user = User::find(Auth::id());
        $file = FileService::create($user, 'uploaded', time(), $request->audios);
        $data = AudioService::create($user, $file, $request->title, $request->artist);

        return $data;
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
    public function download(Request $request)
    {
        $path = storage_path("app/public/files/{$request->file}");

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            abort(404, 'File not found');
        }
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
