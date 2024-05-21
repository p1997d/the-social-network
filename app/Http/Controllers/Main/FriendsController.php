<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Enums\FriendRequestStatusEnum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Friends;
use App\Models\User;

use App\Events\FriendsWebSocket;

use App\Services\GeneralService;
use App\Services\FriendsService;

class FriendsController extends Controller
{
    /**
     * Отображает страницу друзей
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function friends(Request $request)
    {
        $user = $request->query('id') ? User::find($request->query('id')) : User::find(Auth::id());

        $section = $request->query('section');

        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $title = GeneralService::getTitle($user, 'Друзья');

        list($listFriends, $listCommonFriends, $listOnline, $listOutgoing, $listIncoming) = FriendsService::getAllFriendsLists($user);

        $friends = match ($section) {
            null => $listFriends->get(),
            'common' => $listCommonFriends->get(),
            'outgoing' => $listOutgoing->get(),
            'incoming' => $listIncoming->get(),
            'online' => $listOnline->get(),
            default => null,
        };

        return view(
            'friends.index',
            compact(
                'section',
                'title',
                'user',
                'friends',
                'listFriends',
                'listCommonFriends',
                'listOutgoing',
                'listIncoming',
                'listOnline',
            )
        );
    }

    /**
     * Отправляет заявку в друзья пользователю
     *
     * @param int $id
     * @return array
     */
    public function addFriend($id)
    {
        $auth_user = User::find(Auth::id());
        $user = User::find($id);

        $models = FriendsService::getAllFriends($user)->filter(function ($item) {
            return $item->status == FriendRequestStatusEnum::SENT_FRIEND_REQUEST || $item->status == FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST;
        });

        if ($models->isEmpty()) {
            $model = new Friends();
            $model->user1 = $auth_user->id;
            $model->user2 = $user->id;
            $model->sented_at = now();
            $model->status = FriendRequestStatusEnum::SENT_FRIEND_REQUEST;
            $model->save();
        }

        event(new FriendsWebSocket($auth_user, $user, true, 'Новая заявка в друзья', 'хочет добавить Вас в друзья'));

        return [
            'form' => FriendsService::getFriendsForms($user),
        ];
    }

    /**
     * Отменяет заявку в друзья пользователю
     *
     * @param int $id
     * @return array
     */
    public function cancelAddFriend($id)
    {
        $auth_user = User::find(Auth::id());
        $user = User::find($id);

        $models = FriendsService::getAllFriends($user)->filter(function ($item) use ($auth_user) {
            return $item->status == FriendRequestStatusEnum::SENT_FRIEND_REQUEST && $item->user1 == $auth_user->id;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => FriendRequestStatusEnum::CANCELED_FRIEND_REQUEST,
                'status_changed_at' => now()
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user));

        return [
            'form' => FriendsService::getFriendsForms($user),
        ];
    }

    /**
     * Принимает заявку в друзья от пользователя
     *
     * @param int $id
     * @return array
     */
    public function approveAddFriend($id)
    {
        $auth_user = User::find(Auth::id());
        $user = User::find($id);

        $models = FriendsService::getAllFriends($user)->filter(function ($item) use ($auth_user) {
            return $item->status == FriendRequestStatusEnum::SENT_FRIEND_REQUEST && $item->user2 == $auth_user->id;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST,
                'status_changed_at' => now()
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user, true, 'Заявка принята', 'принял Вашу заявку в друзья'));

        return [
            'form' => FriendsService::getFriendsForms($user),
        ];
    }

    /**
     * Отклоняет заявку в друзья от пользователя
     *
     * @param int $id
     * @return array
     */
    public function rejectAddFriend($id)
    {
        $auth_user = User::find(Auth::id());
        $user = User::find($id);

        $models = FriendsService::getAllFriends($user)->filter(function ($item) use ($auth_user) {
            return $item->status == FriendRequestStatusEnum::SENT_FRIEND_REQUEST && $item->user2 == $auth_user->id;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => FriendRequestStatusEnum::REJECTED_FRIEND_REQUEST,
                'status_changed_at' => now()
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user));

        return [
            'form' => FriendsService::getFriendsForms($user),
        ];
    }

    /**
     * Удаляет пользователя из списка друзей
     *
     * @param int $id
     * @return array
     */
    public function unfriend($id)
    {
        $auth_user = User::find(Auth::id());
        $user = User::find($id);

        $models = FriendsService::getAllFriends($user)->filter(function ($item) {
            return $item->status == FriendRequestStatusEnum::APPROVED_FRIEND_REQUEST;
        });

        if ($models->isNotEmpty()) {
            $model = $models->last();
            $model->update([
                'status' => FriendRequestStatusEnum::UNFRIEND,
                'unfriend_at' => now(),
                'unfriend_user' => Auth::id(),
            ]);
        }

        event(new FriendsWebSocket($auth_user, $user));

        return [
            'form' => FriendsService::getFriendsForms($user),
        ];
    }
}
