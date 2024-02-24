<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Friends;
use App\Models\User;
use morphos\Russian;
use App\Events\FriendsWebSocket;

class FriendsController extends Controller
{
    public function friends(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $user_auth = Auth::user();
        $id = $request->query('id');
        $section = $request->query('section');

        if (!$id) {
            $user_profile = $user_auth;
            $title = 'Мои друзья';
        } else {
            $user_profile = User::find($id);
            $title = "Друзья " . Russian\inflectName($user_profile->firstname, 'родительный') . " " . Russian\inflectName($user_profile->surname, 'родительный');
        }

        $listFriends = $user_profile->listFriends();
        $listCommonFriends = $user_profile->listCommonFriends();
        $listOnline = $user_profile->listOnlineFriends();
        $listOutgoing = Friends::listOutgoing();
        $listIncoming = Friends::listIncoming();

        switch ($section) {
            case '':
                $friends = $listFriends->get();
                break;
            case 'common':
                $friends = $listCommonFriends->get();
                break;
            case 'outgoing':
                $friends = $listOutgoing->get();
                break;
            case 'incoming':
                $friends = $listIncoming->get();
                break;
            case 'online':
                $friends = $listOnline->get();
                break;
            default:
                $friends = null;
        }


        return view(
            'main.friends',
            compact(
                'title',
                'user_profile',
                'friends',
                'listFriends',
                'listCommonFriends',
                'listOutgoing',
                'listIncoming',
                'listOnline'
            )
        );
    }

    public function addFriend($id)
    {

        $auth_user = Auth::user();
        $user_profile = User::find($id);

        $models = $user_profile->getFriendsModels()->filter(function ($item) {
            return $item->status == 0 || $item->status == 1;
        });

        if ($models->isEmpty()) {
            $model = new Friends();
            $model->user1 = $auth_user->id;
            $model->user2 = $user_profile->id;
            $model->sented_at = now();
            $model->save();
        }

        event(new FriendsWebSocket($auth_user, $user_profile, true, 'Новая заявка в друзья', 'хочет добавить Вас в друзья'));
    }

    public function cancelAddFriend($id)
    {
        $auth_user = Auth::user();
        $user_profile = User::find($id);

        $models = $user_profile->getFriendsModels()->filter(function ($item) use ($auth_user) {
            return $item->status == 0 && $item->user1 == $auth_user->id;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => 3,
                'status_changed_at' => now()
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user_profile));
    }

    public function approveAddFriend($id)
    {
        $auth_user = Auth::user();
        $user_profile = User::find($id);

        $models = $user_profile->getFriendsModels()->filter(function ($item) use ($auth_user) {
            return $item->status == 0 && $item->user2 == $auth_user->id;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => 1,
                'status_changed_at' => now()
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user_profile, true, 'Заявка принята', 'принял Вашу заявку в друзья'));
    }

    public function rejectAddFriend($id)
    {
        $auth_user = Auth::user();
        $user_profile = User::find($id);

        $models = $user_profile->getFriendsModels()->filter(function ($item) use ($auth_user) {
            return $item->status == 0 && $item->user2 == $auth_user->id;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => 2,
                'status_changed_at' => now()
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user_profile));
    }

    public function unfriend($id)
    {
        $auth_user = Auth::user();
        $user_profile = User::find($id);

        $models = $user_profile->getFriendsModels()->filter(function ($item) {
            return $item->status == 1;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => 4,
                'unfriend_at' => now(),
                'unfriend_user' => Auth::id(),
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user_profile));
    }
}