<?php

namespace App\Services;

use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class InfoService
{
    public $title, $icon, $description;

    public function __construct($title, $icon, $description)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->description = $description;
    }
    public static function getLocation()
    {
        $user = User::find(Auth::id());
        $userLocation = json_decode(optional($user->info)->location);

        $location[] = Location::where('parent_id', null)->get()->sortBy('name')->values();

        if (!$userLocation) {
            return $location;
        }

        if (isset($userLocation[0])) {
            $location[] = Location::where('parent_id', $userLocation[0])->get()->sortBy('name')->values();
        }

        if (isset($userLocation[1])) {
            $location[] = Location::where('parent_id', $userLocation[1])->get()->sortBy('name')->values();
        }

        return $location;
    }
}
