<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Video;

use App\Services\GeneralService;
use App\Services\FileService;
use App\Services\VideoService;

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
        $group = 'uploaded';
        $name = time();
        $file = FileService::create($user, $group, $name, $request->videos);
        $thumbnailPath = VideoService::createThumbnails($user, $group, $name, $file);
        $data = VideoService::create($file, $request->title, $thumbnailPath);

        return $data;
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
        $file = $video->videoFile;
        $viewsWithText = $video->viewsWithText();
        $author = $file->authorUser;
        $avatar = $author->avatar();
        $createdAt = Carbon::parse($video->created_at)->diffForHumans();
        $playlist = VideoService::getVideos($user)->pluck("id");

        return compact('video', 'file', 'author', 'avatar', 'viewsWithText', 'createdAt', 'playlist', 'userID');
    }

    public function addView(Request $request)
    {
        $video = Video::find($request->id);
        $video->increment('views');
        $video->update();
    }
}
