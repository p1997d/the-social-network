<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Photo;

use App\Services\FileService;
use App\Services\GeneralService;
use App\Services\PhotoService;
use App\Services\PublicationService;

class PhotosController extends Controller
{
    /**
     * Отображает страницу фотографий пользователя
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

        return view('publications.photos.index', PublicationService::getPage('photo', $model, $request));
    }

    /**
     * Загружает новую фотографию
     *
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photos' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photo = FileService::create($request->photos);

        if ($request->group) {
            $group = Group::find($request->group);
            FileService::saveForGroup($group, $photo);
        } else {
            $user = User::find(Auth::id());
            FileService::saveForUser($user, $photo);
        }

        return [
            'photo',
            $photo,
            'notification' => [
                'color' => 'success',
                'message' => 'Фотография успешно загружена'
            ]
        ];
    }

    /**
     * Удаляет фотографию
     *
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $file = Photo::find($request->photo);
        return FileService::delete($file);
    }

    /**
     * Получает фотографию и данные о ее владельце
     *
     * @param Request $request
     * @return array
     */
    public function getPhoto(Request $request)
    {
        $id = $request->id;

        $photo = Photo::find($id);
        $author = $photo->authorUser;

        $to = $request->to;
        $chat = $request->chat;

        $queryContent = $request->content;
        $contentArray = explode('_', $queryContent);
        $typeContent = $contentArray[0];
        $user = User::find($contentArray[1]);
        $activeContent = $contentArray[2];
        $groupContent = array_key_exists(3, $contentArray) ? $contentArray[3] : null;

        $content = PhotoService::getPhotos($user, $groupContent, $to, $chat);

        $links = PhotoService::getAuthorLinks($groupContent, $author);

        return [
            'photo' => $photo,
            ...$links,
            'photoModalDate' => $photo->createdAtDiffForHumans(),
            'photoModalComments' => 'Возможность комментирования этой фотографии ограничена.',
            'photoModalSetLike' => [
                'id' => $photo->id,
                'type' => $photo->getMorphClass(),
                'data' => class_basename($photo) . $photo->id,
                'count' => $photo->likes->count(),
                'class' => $photo->myLike !== null ? 'btn btn-sm btn-outline-danger active' : 'btn btn-sm btn-outline-secondary',
            ],
            'content' => $content,
            'typeContent' => $typeContent,
            'activeContent' => $activeContent,
            'groupContent' => $groupContent
        ];
    }
}
