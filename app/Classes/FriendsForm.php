<?php

namespace App\Classes;

class FriendsForm
{
    public $title, $icon, $link, $color;

    public function __construct($title, $icon, $link, $color)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->link = $link;
        $this->color = $color;
    }
}
