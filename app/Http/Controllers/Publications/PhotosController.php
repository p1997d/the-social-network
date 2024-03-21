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
    public function index(Request $request)
    {
        $id = $request->query('id');

        list($title, $user) = GeneralService::getTitleAndUser($id, "Фотографии");

        $type = $request->query('type');

        $photos = PhotoService::getPhotos($user, $type);

        return view('publications.photos.index', compact('title', 'user', 'photos', 'type'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photos' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::id());
        FileService::create($user, 'uploaded', time(), $request->photos);

        return ['color' => 'success', 'message' => 'Фотография успешно загружена'];
    }

    public function delete(Request $request)
    {
        return FileService::delete($request->photo);
    }

    public function getPhoto(Request $request)
    {
        $id = $request->id;

        $photo = File::find($id);
        $author = $photo->authorUser;
        $avatar = $author->avatar();

        return compact('photo', 'author', 'avatar');
    }
}
