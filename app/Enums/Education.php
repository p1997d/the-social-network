<?php

namespace App\Enums;

enum Education: int
{
    case PRESCHOOL = 1;
    case ELEMENTARY = 2;
    case BASIC = 3;
    case HIGH_SCHOOL = 4;
    case VOCATIONAL = 5;
    case BACHELOR = 6;
    case MASTER = 7;
    case POSTGRADUATE = 8;

    public function description(): string
    {
        return match ($this) {
            self::PRESCHOOL => "Дошкольное",
            self::ELEMENTARY => "Начальное общее — 1—4 классы",
            self::BASIC => "Основное общее — 5—9 классы",
            self::HIGH_SCHOOL => "Среднее общее — 10—11 классы",
            self::VOCATIONAL => "Среднее профессиональное",
            self::BACHELOR => "Высшее I степени — бакалавриат",
            self::MASTER => "Высшее II степени — специалитет, магистратура",
            self::POSTGRADUATE => "Высшее III степени — подготовка кадров высшей квалификации",
        };
    }
}
