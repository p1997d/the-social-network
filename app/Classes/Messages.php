<?php

namespace App\Classes;

class Messages
{
    public $id, $type, $avatar, $online, $title, $lastMessage;

    public function __construct($model, $type)
    {
        $this->id = $model->id;
        $this->type = $type;
        $this->avatar = $model->getAvatar();
        $this->online = class_basename($model) == "User" ? $model->isOnline() : null;
        $this->title = class_basename($model) == "User" ? "$model->firstname $model->surname" : $model->title;
        $this->lastMessage = $model->getLastMessage();
    }
}
