<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class InfoService
{
    public static function locationList($location)
    {
        $areas = json_decode(File::get(public_path("json/areas.json")), true);
        $regions[] = $areas;
        $locationArray = [];

        if ($location) {
            $locationArray = explode('.', $location);
        }

        if (array_key_exists(0, $locationArray)) {
            $region1 = $areas[array_search($locationArray[0], array_column($areas, 'id'))];
            $regions[] = $region1;
        }
        if (array_key_exists(1, $locationArray)) {
            $region2 = $region1['areas'][array_search($locationArray[1], array_column($region1['areas'], 'id'))];
            $regions[] = $region2;
        }
        if (array_key_exists(2, $locationArray)) {
            $region3 = $region2['areas'][array_search($locationArray[2], array_column($region2['areas'], 'id'))];
            $regions[] = $region3;
        }

        return $regions;
    }

    public static function getLocation($location)
    {
        if (!$location) {
            return null;
        }

        return self::locationList($location);
    }

    public static function getAreas($r1 = null, $r2 = null, $r3 = null)
    {
        $areas = json_decode(File::get(public_path("json/areas.json")), true);

        $regions = null;

        if ($r1 && $r1 != 0) {
            $region1 = $areas[array_search($r1, array_column($areas, 'id'))];
            $regions = $region1;
        }

        if ($r2 && $r2 != 0) {
            $region2 = $region1['areas'][array_search($r2, array_column($region1['areas'], 'id'))];
            $regions = $region2;
        }

        if ($r3 && $r3 != 0) {
            $region3 = $region2['areas'][array_search($r3, array_column($region2['areas'], 'id'))];
            $regions = $region3;
        }

        return $regions;
    }
}
