<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Info extends Model
{
    use HasFactory;

    protected $table = 'user_info';
    protected $quarde = false;
    protected $guarded = [];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user');
    }

    public static function educationList()
    {
        $education = [
            1 => "Дошкольное",
            2 => "Начальное общее — 1—4 классы",
            3 => "Основное общее — 5—9 классы",
            4 => "Среднее общее — 10—11 классы",
            5 => "Среднее профессиональное",
            6 => "Высшее I степени — бакалавриат",
            7 => "Высшее II степени — специалитет, магистратура",
            8 => "Высшее III степени — подготовка кадров высшей квалификации",
        ];

        return $education;
    }

    public static function familyStatusList($sex)
    {
        $familyStatus = [
            1 => $sex == "male" ? "Не женат" : "Не замужем",
            2 => "Встречаюсь",
            3 => $sex == "male" ? "Помолвлен" : "Помолвлена",
            4 => $sex == "male" ? "Женат" : "Замужем",
            5 => "В гражданском браке",
            6 => $sex == "male" ? "Влюблён" : "Влюблена",
            7 => "Всё сложно",
            8 => "В активном поиске"
        ];

        return $familyStatus;
    }

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

    public function getEducation()
    {
        if (!$this->education) {
            return null;
        }

        $education = self::educationList()[$this->education];

        return $education;
    }

    public function getFamilyStatus()
    {
        if (!$this->family_status) {
            return null;
        }

        $familyStatus = self::familyStatusList($this->getUser->sex)[$this->family_status];

        return $familyStatus;
    }

    public function getLocation()
    {
        if (!$this->location) {
            return null;
        }

        $location = self::locationList($this->location);

        return $location;
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
