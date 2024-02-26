<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\FriendsService;
use App\Services\UserService;

class IndexController extends Controller
{
    public function index()
    {
        if (!Auth::guest()) {
            return redirect()->route('profile', Auth::id());
        }
        return view('auth.signin');
    }

    public function profile($id)
    {
        $title = 'Главная';
        $user_profile = User::find($id);

        if (!$user_profile) {
            return view('main.info', ['info' => 'Страница удалена либо ещё не создана.']);
        }

        $listFriends = FriendsService::listFriends($user_profile);

        $allInfo = UserService::getInfo($user_profile);

        $listCommonFriends = null;
        $listIncoming = null;
        $listOutgoing = null;
        $listOnline = null;

        $friendForm = UserService::getFriendsForms($user_profile);

        if (Auth::check()) {
            $listIncoming = FriendsService::listIncoming();
            $listOutgoing = FriendsService::listOutgoing();
            $listCommonFriends = FriendsService::listCommonFriends($user_profile);
            $listOnline = FriendsService::listOnlineFriends($user_profile);
        }

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
            )
        );
    }

    public function signup()
    {
        return view('auth.signup');
    }
    public function signin()
    {
        return view('auth.signin');
    }
}
