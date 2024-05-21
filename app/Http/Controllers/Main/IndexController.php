<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Services\FriendsService;
use App\Services\GeneralService;
use App\Services\MenuService;
use App\Services\PostService;
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
     * @param integer $id
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

        $posts = PostService::getPosts($user->posts);

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
                'posts',
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
        $title = 'Регистрация';

        $months = GeneralService::getMonthNames();

        return view('auth.signup', compact('title', 'months'));
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
    public function feed(Request $request)
    {
        $title = 'Новости';

        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $user = User::find(Auth::id());

        $section = $request->query('section');

        $posts = match($section) {
            default => PostService::getNews(),
            'likes' => PostService::getLikes(),
        };

        return view('main.feed', compact('title', 'posts', 'user', 'section'));
    }

    /**
     * Получает счетчики непрочитанных сообщений и входящих заявок в друзья
     *
     * @return array
     */
    public function getCounters()
    {
        return MenuService::getCounters();
    }
}
