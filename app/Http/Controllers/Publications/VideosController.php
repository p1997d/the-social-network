<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Services\UserService;
use App\Services\GeneralService;
// use App\Services\VideoService;

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

        $videos = [];

        return view('publications.videos.index', compact('title', 'user', 'videos'));
    }
}
