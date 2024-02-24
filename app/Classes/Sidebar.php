<?php

namespace App\Classes;

class Sidebar
{
    public $title, $icon, $link, $counter;

    public function __construct($title, $icon, $link, $counter = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->link = $link;
        $this->counter = $counter;
    }
}
