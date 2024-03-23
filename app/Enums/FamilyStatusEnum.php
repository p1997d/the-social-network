<?php

namespace App\Enums;

enum FamilyStatusEnum: int
{
    case NOT_MARRIED = 1;
    case DATING = 2;
    case ENGAGED = 3;
    case MARRIED = 4;
    case CIVIL_UNION = 5;
    case IN_LOVE = 6;
    case COMPLICATED = 7;
    case ACTIVE_SEARCH = 8;

    public function description($sex): string
    {
        return match ($this) {
            self::NOT_MARRIED => $sex == "male" ? "Не женат" : "Не замужем",
            self::DATING => "Встречаюсь",
            self::ENGAGED => $sex == "male" ? "Помолвлен" : "Помолвлена",
            self::MARRIED => $sex == "male" ? "Женат" : "Замужем",
            self::CIVIL_UNION => "В гражданском браке",
            self::IN_LOVE => $sex == "male" ? "Влюблён" : "Влюблена",
            self::COMPLICATED => "Всё сложно",
            self::ACTIVE_SEARCH => "В активном поиске",
        };
    }
}


