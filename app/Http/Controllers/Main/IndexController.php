<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\FriendsService;
use App\Services\UserService;
use App\Services\PublicationsService;

class IndexController extends Controller
{
    public function index()
    {
        if (!Auth::guest()) {
            return redirect()->route('profile', Auth::id());
        }
        return view('auth.signin');
    }

    public function profile(Request $request, $id)
    {
        $title = 'Главная';
        $user_profile = User::find($id);

        if (!$user_profile) {
            return view('main.info', ['info' => 'Страница удалена либо ещё не создана.']);
        }

        $listFriends = FriendsService::listFriends($user_profile);

        $allInfo = UserService::getInfo($user_profile);

        $friendForm = FriendsService::getFriendsForms($user_profile);

        list($listFriends, $listCommonFriends, $listOnline, $listOutgoing, $listIncoming) = FriendsService::getAllFriendsLists($user_profile);

        $photos = PublicationsService::getPhotos($user_profile);

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
