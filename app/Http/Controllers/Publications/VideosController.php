<?php

namespace App\Http\Controllers\Publications;

use App\DTO\VideoDTO;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Video;

use App\Services\GeneralService;
use App\Services\VideoService;
use App\Services\FileService;
use App\Services\PublicationService;
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
        $groupID = $request->query('group');

        if ($groupID) {
            $model = Group::find($groupID);
        } else {
            $model = $request->query('id') ? User::find($request->query('id')) : User::find(Auth::id());
        }
        if (!$model) {
            return view('main.info', ['title' => 'Информация', 'info' => 'Страница удалена либо ещё не создана.']);
        }

        return view('publications.videos.index', PublicationService::getPage('video', $model, $request));
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

        if ($request->group) {
            $group = Group::find($request->group);
            FileService::saveForGroup($group, $video);
        } else {
            $user = User::find(Auth::id());
            FileService::saveForUser($user, $video);
        }

        $newVideo = [
            'id' => $video->id,
            'title' => $video->title,
            'author' => $video->author,
            'duration' => $video->duration,
            'thumbnailPath' => $video->thumbnailPath,
            'viewsWithText' => $video->viewsWithText(),
            'createdAtDiffForHumans' => $video->createdAtDiffForHumans(),
        ];

        if (!$video) {
            return [
                'video' => $newVideo,
                'notification' => [
                    'color' => 'danger',
                    'message' => 'Загрузка видеозаписи завершилась с ошибкой'
                ]
            ];
        }

        return [
            'video' => $newVideo,
            'notification' => [
                'color' => 'success',
                'message' => 'Видеозапись успешно загружена'
            ]
        ];
    }

    /**
     * Получает видеозапись и данные о ее владельце
     *
     * @param Request $request
     * @return array
     */
    public function getVideo(Request $request)
    {
        $video = Video::find($request->id);
        $author = $video->authorUser;

        $data = new VideoDTO($request->model);
        $user = $data->user;
        $group = $data->group;

        if ($group) {
            $videos = Group::find($group)->videos;
        } else {
            $videos = User::find($user)->videos;
        }

        $playlist = $videos->map(function ($video) use ($group) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                'author' => $video->author,
                'duration' => $video->duration,
                'thumbnailPath' => $video->thumbnailPath,
                'viewsWithText' => $video->viewsWithText(),
                'createdAtDiffForHumans' => $video->createdAtDiffForHumans(),
                'group' => $group,
            ];
        });

        return [
            'video' => $video,
            'videoModalAvatar' => $author->avatar()->thumbnailPath,
            'videoModalDate' => $video->createdAtDiffForHumans(),
            'viewsWithText' => $video->viewsWithText(),
            'playlist' => $playlist,
            'userID' => $user,
            'videoModalSetLike' => [
                'id' => $video->id,
                'type' => $video->getMorphClass(),
                'data' => class_basename($video) . $video->id,
                'count' => $video->likes->count(),
                'class' => $video->myLike !== null ? 'btn btn-sm btn-outline-danger active' : 'btn btn-sm btn-outline-secondary',
            ]
        ];
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

    /**
     * Удаляет видео
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $video = Video::find($request->id);
        $video->delete();
        return back();
    }
}
