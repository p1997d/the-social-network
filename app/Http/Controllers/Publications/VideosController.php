<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Video;

use App\Services\GeneralService;
use App\Services\VideoService;
use App\Services\FileService;

use Carbon\Carbon;

class VideosController extends Controller
{
    /**
     * Отображает страницу видеозаписей пользователя
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->query('id') ? User::find($request->query('id')) : User::find(Auth::id());

        $title = GeneralService::getTitle($user, 'Видеозаписи');

        $videos = VideoService::getVideos($user);

        return view('publications.videos.index', compact('title', 'user', 'videos'));
    }

    /**
     * Загружает новую видеозапись
     *
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $request->validate([
            'videos' => 'required|mimes:mp4|max:204800',
        ]);

        $user = User::find(Auth::id());
        $data = (object) collect(['title' => $request->title])->all();
        $video = FileService::create($request->videos, $data);

        FileService::saveForUser($user, $video);

        if (!$video) {
            return ['color' => 'danger', 'message' => 'Загрузка видеозаписи завершилась с ошибкой'];
        }

        return ['color' => 'success', 'message' => 'Видеозапись успешно загружена'];
    }

    /**
     * Получает видеозапись и данные о ее владельце
     *
     * @param Request $request
     * @return array
     */
    public function getVideo(Request $request)
    {
        $userID = $request->user;
        $user = User::find($userID);
        $video = Video::find($request->id);
        $viewsWithText = $video->viewsWithText();
        $avatar = $video->authorUser->avatar();
        $createdAt = Carbon::parse($video->created_at)->diffForHumans();
        $playlist = VideoService::getVideos($user)->pluck("id");
        $path = $video->path;

        return compact('video', 'avatar', 'viewsWithText', 'createdAt', 'playlist', 'userID', 'path');
    }

    /**
     * Увеличивает счетчик просмотров
     *
     * @param Request $request
     * @return void
     */
    public function addView(Request $request)
    {
        $video = Video::find($request->id);
        $video->increment('views');
        $video->update();
    }
}
