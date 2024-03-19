<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Friends;
use App\Models\User;

use App\Events\FriendsWebSocket;

use App\Services\GeneralService;
use App\Services\FriendsService;

class FriendsController extends Controller
{
    public function friends(Request $request)
    {
        $id = $request->query('id');
        $section = $request->query('section');

        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        list($title, $user_profile) = GeneralService::getTitleAndUser($id, 'Друзья');

        list($listFriends, $listCommonFriends, $listOnline, $listOutgoing, $listIncoming) = FriendsService::getAllFriendsLists($user_profile);

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
            'friends.index',
            compact(
                'section',
                'title',
                'user_profile',
                'friends',
                'listFriends',
                'listCommonFriends',
                'listOutgoing',
                'listIncoming',
                'listOnline',
            )
        );
    }

    public function addFriend($id)
    {

        $auth_user = User::find(Auth::id());
        $user_profile = User::find($id);

        $models = FriendsService::getFriendsModels($user_profile)->filter(function ($item) {
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
        $auth_user = User::find(Auth::id());
        $user_profile = User::find($id);

        $models = FriendsService::getFriendsModels($user_profile)->filter(function ($item) use ($auth_user) {
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
        $auth_user = User::find(Auth::id());
        $user_profile = User::find($id);

        $models = FriendsService::getFriendsModels($user_profile)->filter(function ($item) use ($auth_user) {
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
        $auth_user = User::find(Auth::id());
        $user_profile = User::find($id);

        $models = FriendsService::getFriendsModels($user_profile)->filter(function ($item) use ($auth_user) {
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
        $auth_user = User::find(Auth::id());
        $user_profile = User::find($id);

        $models = FriendsService::getFriendsModels($user_profile)->filter(function ($item) {
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
