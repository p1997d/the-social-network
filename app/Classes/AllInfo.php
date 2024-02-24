<?php

namespace App\Classes;

class AllInfo
{
    public $title, $icon, $description;

    public function __construct($title, $icon, $description)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->description = $description;
    }
}
