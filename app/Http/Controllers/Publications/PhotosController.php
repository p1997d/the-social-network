<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\File;
use App\Models\User;

use App\Services\FileService;
use App\Services\GeneralService;
use App\Services\PhotoService;

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
        $user = $request->query('id') ? User::find($request->query('id')) : User::find(Auth::id());

        $title = GeneralService::getTitle($user, "Фотографии");

        $type = $request->query('type');

        $photos = PhotoService::getPhotos($user, $type);

        return view('publications.photos.index', compact('title', 'user', 'photos', 'type'));
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

        $user = User::find(Auth::id());
        FileService::create($user, 'uploaded', time(), $request->photos);

        return ['color' => 'success', 'message' => 'Фотография успешно загружена'];
    }

    /**
     * Удаляет фотографию
     *
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        return FileService::delete($request->photo);
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

        $photo = File::find($id);
        $author = $photo->authorUser;
        $avatar = $author->avatar();

        return compact('photo', 'author', 'avatar');
    }
}
