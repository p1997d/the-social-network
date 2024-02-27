<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Info;
use App\Models\Location;
use App\Services\InfoService;
use App\Enums\Education;
use App\Enums\FamilyStatus;


class InfoController extends Controller
{
    public function updateAvatar(Request $request)
    {

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        $avatarPath = $user->id . '/' . time() . '.' . request()->avatar->getClientOriginalExtension();

        $request->avatar->storeAs('avatars', $avatarPath, 'public');

        Info::updateOrCreate(
            ['user' => $user->id],
            ['avatar' => $avatarPath]
        );

        return back();
    }

    public function deleteAvatar()
    {
        $user = Auth::user();
        if (optional($user->info)->avatar && Storage::exists("public/avatars/" . $user->info->avatar)) {
            Storage::delete("public/avatars/" . $user->info->avatar);

            $model = $user->info;
            $model->update([
                'avatar' => null
            ]);
        }

        return back();
    }

    public function editProfile()
    {
        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $user = User::find(Auth::id());

        $education = Education::cases();
        $familyStatus = FamilyStatus::cases();
        $location = InfoService::getLocation();

        return view('main.editprofile', compact('familyStatus', 'education', 'location'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

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

    public function nextLocation(Request $request)
    {
        $location = Location::where('parent_id', $request->region)->get()->sortBy('name')->values();
        return $location;
    }
}
