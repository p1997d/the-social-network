<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Friends;

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

        $listFriends = $user_profile->listFriends();

        $allInfo = $user_profile->getInfo();

        $listCommonFriends = null;
        $listIncoming = null;
        $listOutgoing = null;
        $listOnline = null;

        if (Auth::check()) {
            $listIncoming = Friends::listIncoming();
            $listOutgoing = Friends::listOutgoing();
            $listCommonFriends = $user_profile->listCommonFriends();
            $listOnline = $user_profile->listOnlineFriends();
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
