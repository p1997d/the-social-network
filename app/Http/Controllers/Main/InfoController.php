<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Info;
use App\Models\Location;

use App\Services\InfoService;
use App\Services\FileService;

use App\Enums\EducationEnum;
use App\Enums\FamilyStatusEnum;


class InfoController extends Controller
{
    /**
     * Обновляет аватар пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::id());

        $avatar = FileService::create($user, 'profile', time(), $request->avatar);

        Info::updateOrCreate(
            ['user' => $user->id],
            ['avatar' => $avatar->id]
        );

        return back();
    }

    /**
     * Удаляет аватар пользователя
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAvatar()
    {
        $user = User::find(Auth::id());
        if (optional($user->info)->avatar) {
            $model = $user->info;
            $model->update([
                'avatar' => null
            ]);
        }

        return back();
    }

    /**
     * Отображает страницу редактирования профиля
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editProfile()
    {
        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $user = User::find(Auth::id());

        $education = EducationEnum::cases();
        $familyStatus = FamilyStatusEnum::cases();
        $location = InfoService::getLocation();

        $userinfo = optional($user->info);

        $title = 'Редактирование профиля';

        return view('main.editProfile', compact('familyStatus', 'education', 'location', 'userinfo', 'title'));
    }

    /**
     * Сохраняет изменения в профиле пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());

        $location = null;
        $locationArray = array_filter([$request->region1, $request->region2, $request->region3]);

        if (!empty($locationArray)) {
            $location = json_encode($locationArray);
        }

        Info::updateOrCreate(
            [
                'user' => $user->id
            ],
            [
                'location' => $location ?? null,
                'education' => $request->education,
                'family_status' => $request->family_status
            ]
        );

        $user->update([
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'sex' => $request->sex,
            'birth' => $request->birthYear . "-" . $request->birthMonth . "-" . $request->birthDay,
        ]);

        return back()->with('success', 'Изменения сохранены');
    }

    /**
     * Получить следующее местоположение на основе запроса
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function nextLocation(Request $request)
    {
        return Location::where('parent_id', $request->region)->get()->sortBy('name')->values();
    }
}
