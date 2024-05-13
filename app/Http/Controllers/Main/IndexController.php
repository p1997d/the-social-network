<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Services\FriendsService;
use App\Services\UserService;

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
        $user = User::find($id);

        if (!$user) {
            return view('main.info', ['title' => 'Информация', 'info' => 'Страница удалена либо ещё не создана.']);
        }

        $title = "$user->firstname $user->surname";
        $friendForm = FriendsService::getFriendsForms($user);

        list($listFriends, $listCommonFriends, $listOnline, $listOutgoing, $listIncoming) = FriendsService::getAllFriendsLists($user);

        return view(
            'profile.index',
            compact(
                'user',
                'title',
                'listFriends',
                'listCommonFriends',
                'listOutgoing',
                'listIncoming',
                'listOnline',
                'friendForm',
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

        $user = User::find(Auth::id());
        $posts = UserService::getNews()->forPage(0, 25);

        return view('main.feed', compact('title', 'posts', 'user'));
    }
}
