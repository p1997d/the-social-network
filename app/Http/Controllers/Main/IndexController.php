<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Services\FriendsService;
use App\Services\UserService;
use App\Services\PhotoService;
use App\Services\AudioService;

class IndexController extends Controller
{
    /**
     * Отображает главную страницу
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::guest()) {
            return redirect()->route('profile', Auth::id());
        }
        return view('auth.signin', ['title' => 'Вход']);
    }

    /**
     * Отображает страницу пользователя
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile(Request $request, $id)
    {
        $user_profile = User::find($id);

        if (!$user_profile) {
            return view('main.info', ['title' => 'Информация', 'info' => 'Страница удалена либо ещё не создана.']);
        }

        $listFriends = FriendsService::listFriends($user_profile);

        $allInfo = UserService::getInfo($user_profile);

        $friendForm = FriendsService::getFriendsForms($user_profile);

        list($listFriends, $listCommonFriends, $listOnline, $listOutgoing, $listIncoming) = FriendsService::getAllFriendsLists($user_profile);

        $photos = PhotoService::getPhotos($user_profile);

        $playlist = $user_profile->playlist;
        $audios = AudioService::getAudios($playlist);

        $title = "$user_profile->firstname $user_profile->surname";

        return view(
            'profile.index',
            compact(
                'title',
                'user_profile',
                'allInfo',
                'listFriends',
                'listCommonFriends',
                'listOutgoing',
                'listIncoming',
                'listOnline',
                'friendForm',
                'photos',
                'audios',
                'playlist',
            )
        );
    }

    /**
     * Отображает страницу регистрации
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function signup()
    {
        return view('auth.signup', ['title' => 'Регистрация']);
    }

    /**
     * Отображает страницу авторизации
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function signin()
    {
        return view('auth.signin', ['title' => 'Вход']);
    }

    /**
     * Отображает страницу новости
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function feed()
    {
        $title = 'Новости';

        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }
        return view('main.feed', compact('title'));
    }

    /**
     * Отображает список групп пользователя
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function groups()
    {
        $title = 'Группы';

        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }
        return view('main.groups', compact('title'));
    }
}
