<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\GeneralService;
use App\Services\GroupService;
use App\Services\PostService;

class IndexController extends Controller
{
    /**
     * Отображает список групп пользователя
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list(Request $request)
    {
        $tab = $request->query('tab');
        $user = $request->query('id') ? User::find($request->query('id')) : User::find(Auth::id());

        $title = GeneralService::getTitle($user, "Группы");

        $groups = $user->groups;
        $administeredGroups = $user->groupsWhereAdmin();

        $listGroups = match ($tab) {
            default => $groups,
            'admin' => $administeredGroups
        };

        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        return view('groups.list.index', compact('title', 'user', 'groups', 'administeredGroups', 'listGroups', 'tab'));
    }

    /**
     * Отображает группу
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $id)
    {
        $group = Group::find($id);

        if (!$group) {
            return view('main.info', ['title' => 'Информация', 'info' => 'Группа удалена либо ещё не создана.']);
        }

        $title = $group->title;

        $friends = GroupService::friendInGroup($group);

        $posts = PostService::getPosts($group->posts);

        return view('groups.group.index', compact('group', 'title', 'friends', 'posts'));
    }

    /**
     * Создает группу
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $group = GroupService::create($request->title, $request->theme);

        GroupService::subscribe($group->id);

        return back();
    }

    /**
     * Подписывает пользователя на группу
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe($id)
    {
        GroupService::subscribe($id);

        return back();
    }

    /**
     * Отписывает пользователя от группы
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unsubscribe($id)
    {
        GroupService::unsubscribe($id);

        return back();
    }

    /**
     * Отображает настройки группы
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settings(Request $request, $id)
    {
        $act = $request->query('act');
        $group = Group::find($id);

        if (!$group) {
            return view('main.info', ['title' => 'Информация', 'info' => 'Группа удалена либо ещё не создана.']);
        }

        if (!$group->admins()->contains('id', auth()->user()->id)) {
            return view('main.info', ['title' => 'Информация', 'info' => 'Доступ запрещен']);
        }

        $title = $group->title . ': Настройки';

        return match ($act) {
            default => view('groups.settings.main', compact('title', 'group')),
            'main' => view('groups.settings.main', compact('title', 'group')),
            'members' => view('groups.settings.members', compact('title', 'group')),
        };
    }

    /**
     * Редактирует информацию о группе
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $group = Group::find($id);

        $group->update([
            'title' => $request->title,
            'theme' => $request->theme,
        ]);

        return back();
    }

    /**
     * Выгоняет пользователя из группы
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function kick(Request $request, $id)
    {
        $group = Group::find($id);
        $user = User::find($request->user);

        if (!$group || !$user) {
            abort(404);
        }

        if (!$group->isAdmin(auth()->user()) && $group->author !== auth()->user()->id){
            abort(403);
        }

        GroupUser::where([
            ['group', $group->id],
            ['user', $user->id],
        ])->first()->delete();

        return back();
    }

    /**
     * Выдает или забирает права администратора у пользователя
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchAdmin(Request $request, $id){
        $group = Group::find($id);
        $user = User::find($request->user);

        if (!$group || !$user) {
            abort(404);
        }

        if (!$group->isAdmin(auth()->user()) && $group->author !== auth()->user()->id){
            abort(403);
        }

        $group_user = GroupUser::where([
            ['group', $group->id],
            ['user', $user->id],
        ])->first();

        $group_user->update([
            'admin' => !$group_user->admin
        ]);

        return back();
    }
}
